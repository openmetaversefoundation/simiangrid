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
using System.IO;
using System.Net;
using OpenMetaverse;
using OpenMetaverse.StructuredData;

namespace RobustMigration
{
    public static class WebUtil
    {
        /// <summary>
        /// POST URL-encoded form data to a web service that returns LLSD or
        /// JSON data
        /// </summary>
        public static OSDMap PostToService(string url, NameValueCollection data)
        {
            string errorMessage;

            try
            {
                string queryString = BuildQueryString(data);
                byte[] requestData = System.Text.Encoding.UTF8.GetBytes(queryString);

                HttpWebRequest request = (HttpWebRequest)HttpWebRequest.Create(url);
                request.Method = "POST";
                request.ContentLength = requestData.Length;
                request.ContentType = "application/x-www-form-urlencoded";

                using (Stream requestStream = request.GetRequestStream())
                    requestStream.Write(requestData, 0, requestData.Length);

                using (WebResponse response = request.GetResponse())
                {
                    using (Stream responseStream = response.GetResponseStream())
                    {
                        try
                        {
                            string responseStr = responseStream.GetStreamString();
                            OSD responseOSD = OSDParser.Deserialize(responseStr);
                            if (responseOSD.Type == OSDType.Map)
                                return (OSDMap)responseOSD;
                            else
                                errorMessage = "Response format was invalid (" + responseOSD.Type + ")";
                        }
                        catch
                        {
                            errorMessage = "Failed to parse the response (" + responseStream.Length + " bytes of " + response.ContentType + ")";
                        }
                    }
                }
            }
            catch (Exception ex)
            {
                errorMessage = ex.Message;
            }

            return new OSDMap { { "Message", OSD.FromString("Service request failed. " + errorMessage) } };
        }

        /// <summary>
        /// Convert a NameValueCollection into a query string. This is the
        /// inverse of HttpUtility.ParseQueryString()
        /// </summary>
        /// <param name="parameters">Collection of key/value pairs to convert</param>
        /// <returns>A query string with URL-escaped values</returns>
        public static string BuildQueryString(NameValueCollection parameters)
        {
            List<string> items = new List<string>(parameters.Count);

            foreach (string key in parameters.Keys)
            {
                string[] values = parameters.GetValues(key);
                if (values != null && values.Length > 0)
                {
                    foreach (string value in values)
                        items.Add(String.Concat(key, "=", WebUtil.UrlEncode(value ?? String.Empty)));
                }
                else
                {
                    items.Add(key + "=");
                }
            }

            return String.Join("&", items.ToArray());
        }

        /// <summary>
        /// Encodes a url string
        /// </summary>
        /// <param name="str">String to encode</param>
        /// <returns>URL-encoded string</returns>
        /// <remarks>Spaces will be encoded as a percent symbol followed by 20,
        /// not a plus sign</remarks>
        public static string UrlEncode(string str)
        {
            str = System.Web.HttpUtility.UrlEncode(str);
            return str.Replace("+", "%20");
        }

        /// <summary>
        /// Converts an entire stream to a string, regardless of current stream
        /// position
        /// </summary>
        /// <param name="stream">The stream to convert to a string</param>
        /// <returns></returns>
        /// <remarks>When this method is done, the stream position will be 
        /// reset to its previous position before this method was called</remarks>
        public static string GetStreamString(this Stream stream)
        {
            string value = null;

            if (stream != null && stream.CanRead)
            {
                long rewindPos = -1;

                if (stream.CanSeek && stream.Position != 0)
                {
                    rewindPos = stream.Position;
                    stream.Seek(0, SeekOrigin.Begin);
                }

                StreamReader reader = new StreamReader(stream);
                value = reader.ReadToEnd();

                if (rewindPos >= 0)
                    stream.Seek(rewindPos, SeekOrigin.Begin);
            }

            return value;
        }

        /// <summary>
        /// Copies the contents of one stream to another, starting at the 
        /// current position of each stream
        /// </summary>
        /// <param name="copyFrom">The stream to copy from, at the position 
        /// where copying should begin</param>
        /// <param name="copyTo">The stream to copy to, at the position where 
        /// bytes should be written</param>
        /// <param name="maximumBytesToCopy">The maximum bytes to copy</param>
        /// <returns>The total number of bytes copied</returns>
        /// <remarks>
        /// Copying begins at the streams' current positions. The positions are
        /// NOT reset after copying is complete.
        /// </remarks>
        public static int CopyTo(this Stream copyFrom, Stream copyTo, int maximumBytesToCopy)
        {
            byte[] buffer = new byte[4096];
            int readBytes;
            int totalCopiedBytes = 0;

            while ((readBytes = copyFrom.Read(buffer, 0, Math.Min(4096, maximumBytesToCopy))) > 0)
            {
                int writeBytes = Math.Min(maximumBytesToCopy, readBytes);
                copyTo.Write(buffer, 0, writeBytes);
                totalCopiedBytes += writeBytes;
                maximumBytesToCopy -= writeBytes;
            }

            return totalCopiedBytes;
        }
    }
}
