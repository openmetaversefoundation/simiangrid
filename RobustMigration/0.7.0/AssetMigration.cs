/*
 * Copyright (c) 2010 Open Metaverse Foundation
 * All rights reserved.
 *
 * - Redistribution and use in source and binary forms, with or without 
 *   modification, are permitted provided that the following conditions are met:
 *
 * - Redistributions of source code must retain the above copyright notice, this
 *   list of conditions and the following disclaimer.
 * - Neither the name of the openmetaverse.org nor the names 
 *   of its contributors may be used to endorse or promote products derived from
 *   this software without specific prior written permission.
 *
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS" 
 * AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE 
 * IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE 
 * ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT OWNER OR CONTRIBUTORS BE 
 * LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR 
 * CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF 
 * SUBSTITUTE GOODS OR SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS 
 * INTERRUPTION) HOWEVER CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN 
 * CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE) 
 * ARISING IN ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE 
 * POSSIBILITY OF SUCH DAMAGE.
 */

using System;
using System.Collections.Generic;
using System.Collections.Specialized;
using System.Linq;
using System.IO;
using System.Net;
using System.Threading;
using MySql.Data.MySqlClient;
using OpenMetaverse;
using OpenMetaverse.StructuredData;

namespace RobustMigration.v070
{
    [Flags]
    public enum AssetFlags : int
    {
        Normal = 0,         // Immutable asset
        Maptile = 1,        // What it says
        Rewritable = 2,     // Content can be rewritten
        Collectable = 4     // Can be GC'ed after some time
    }

    public class AssetMigration
    {
        private const int MAX_CONCURRENT_UPLOADS = 5;

        private MySqlConnection m_connection;
        private opensim m_db;
        private string m_assetUrl;
        private Semaphore m_semaphore = new Semaphore(MAX_CONCURRENT_UPLOADS, MAX_CONCURRENT_UPLOADS);

        public AssetMigration(string connectString, string assetServiceUrl)
        {
            using (m_connection = new MySqlConnection(connectString))
            {
                m_assetUrl = assetServiceUrl;

                for (int i = 0; i < 4096; i++)
                {
                    string lstr = i.ToString("x3");
                    string hstr = (i + 1).ToString("x3");
                    CreateAssetSet(lstr, hstr);
                }

                CreateAssetSet("fff", "zzz");
            }
        }

        private void CreateAssetSet(string lstr, string hstr)
        {
            string query = String.Format("SELECT * FROM assets where id > '{0}' and id <= '{1}'", lstr, hstr);
            Console.WriteLine(String.Empty);
            Console.WriteLine(query);

            using (m_db = new opensim(m_connection))
            {
                var assets = m_db.ExecuteQuery<assets>(query);

                int count = 0;
                foreach (var asset in assets)
                {
                    CreateAsset(asset);
                    if (++count % 10 == 0) Console.Write(".");
                }
            }
        }

        private void CreateAsset(assets asset)
        {
            AssetFlags flags = (AssetFlags)asset.assetflags;
            AssetType type = (AssetType)asset.assetType;
            string contentType = LLUtil.SLAssetTypeToContentType((int)type);
            bool isPublic = true;

            // Don't bother copying map tiles, garbage-collectibe, or temporary assets
            if ((flags & AssetFlags.Maptile) == AssetFlags.Maptile ||
                (flags & AssetFlags.Collectable) == AssetFlags.Collectable ||
                asset.temporary != 0)
            {
                return;
            }

            // Distinguish public and private assets
            switch (type)
            {
                case AssetType.CallingCard:
                case AssetType.Gesture:
                case AssetType.LSLBytecode:
                case AssetType.LSLText:
                    isPublic = false;
                    break;
            }

            object[] args = new object[] { asset.id, asset.CreatorID, isPublic, asset.name, contentType, asset.data };

            m_semaphore.WaitOne();
            ThreadPool.QueueUserWorkItem(DoCreateAsset, args);
        }

        private void DoCreateAsset(object o)
        {
            try
            {
                object[] array = (object[])o;

                string assetID = (string)array[0];
                string creatorID = (string)array[1];
                bool isPublic = (bool)array[2];
                string assetName = (string)array[3];
                string contentType = (string)array[4];
                byte[] assetData = (byte[])array[5];

                string errorMessage = null;

                // Build the remote storage request
                List<MultipartForm.Element> postParameters = new List<MultipartForm.Element>()
            {
                new MultipartForm.Parameter("AssetID", assetID),
                new MultipartForm.Parameter("CreatorID", creatorID),
                new MultipartForm.Parameter("Public", isPublic ? "1" : "0"),
                new MultipartForm.File("Asset", assetName, contentType, assetData)
            };

                // Make the remote storage request
                try
                {
                    HttpWebRequest request = (HttpWebRequest)HttpWebRequest.Create(m_assetUrl);

                    HttpWebResponse response = MultipartForm.Post(request, postParameters);
                    using (Stream responseStream = response.GetResponseStream())
                    {
                        string responseStr = null;

                        try
                        {
                            responseStr = responseStream.GetStreamString();
                            OSD responseOSD = OSDParser.Deserialize(responseStr);
                            if (responseOSD.Type == OSDType.Map)
                            {
                                OSDMap responseMap = (OSDMap)responseOSD;
                                if (responseMap["Success"].AsBoolean())
                                    return;
                                else
                                    errorMessage = "Upload failed: " + responseMap["Message"].AsString();
                            }
                            else
                            {
                                errorMessage = "Response format was invalid:\n" + responseStr;
                            }
                        }
                        catch (Exception ex)
                        {
                            if (!String.IsNullOrEmpty(responseStr))
                                errorMessage = "Failed to parse the response:\n" + responseStr;
                            else
                                errorMessage = "Failed to retrieve the response: " + ex.Message;
                        }
                    }
                }
                catch (WebException ex)
                {
                    errorMessage = ex.Message;
                }

                Console.WriteLine("Failed to store asset \"{0}\" ({1}, {2}): {3}", assetName, assetID, contentType, errorMessage);
            }
            finally
            {
                m_semaphore.Release();
            }
        }
    }
}
