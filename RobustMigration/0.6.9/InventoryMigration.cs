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
using MySql.Data.MySqlClient;
using OpenMetaverse;
using OpenMetaverse.StructuredData;

namespace RobustMigration.v069
{
    public class InventoryMigration
    {
        private const string UUID_ZERO = "00000000-0000-0000-0000-000000000000";

        private MySqlConnection m_connection;
        private opensim m_db;
        private string m_inventoryUrl;
        private string m_userUrl;
        private int m_counter;
        private Dictionary<string, string> m_rewriteFolderIDs = new Dictionary<string, string>();

        public InventoryMigration(string connectString, string inventoryServiceUrl, string userServiceUrl)
        {
            using (m_connection = new MySqlConnection(connectString))
            {
                using (m_db = new opensim(m_connection))
                {
                    m_inventoryUrl = inventoryServiceUrl;
                    m_userUrl = userServiceUrl;

                    var rootFolders = from i in m_db.inventoryfolders
                                      where i.parentFolderID == UUID_ZERO
                                      select i;

                    foreach (var rootFolder in rootFolders)
                    {
                        // SimianGrid uses UserIDs as root inventory folderIDs. Rewrite all of the 
                        // root folderIDs
                        m_rewriteFolderIDs[rootFolder.folderID] = rootFolder.agentID;

                        CreateFolder(rootFolder);

                        m_counter = 0;
                        Console.Write("+");
                    }
                }
            }
        }

        private void CreateFolder(inventoryfolders folder)
        {
            ++m_counter;

            #region Folder Creation

            string folderID = folder.folderID;
            if (m_rewriteFolderIDs.ContainsKey(folderID))
                folderID = m_rewriteFolderIDs[folderID];

            string parentFolderID = folder.parentFolderID;
            if (m_rewriteFolderIDs.ContainsKey(parentFolderID))
                parentFolderID = m_rewriteFolderIDs[parentFolderID];

            NameValueCollection requestArgs = new NameValueCollection
            {
                { "RequestMethod", "AddInventoryFolder" },
                { "FolderID", folderID },
                { "ParentID", parentFolderID },
                { "OwnerID", folder.agentID },
                { "Name", folder.folderName },
                { "ContentType", LLUtil.SLAssetTypeToContentType(folder.type) }
            };

            OSDMap response = WebUtil.PostToService(m_inventoryUrl, requestArgs);
            bool success = response["Success"].AsBoolean();

            if (!success)
            {
                Console.WriteLine("Error creating folder " + folder.folderName + " for " + folder.agentID + ": " + response["Message"].AsString());
                return;
            }

            #endregion Folder Creation

            #region Child Folders

            var children = from i in m_db.inventoryfolders
                           where (i.parentFolderID == folder.folderID)
                           select i;

            foreach (var child in children)
            {
                CreateFolder(child);
                if (m_counter % 10 == 0) Console.Write(".");
            }

            #endregion Child Folders

            #region Child Items

            var items = from i in m_db.inventoryitems
                        where (i.parentFolderID == folder.folderID)
                        select i;

            foreach (var item in items)
            {
                CreateItem(item);
                if (m_counter % 10 == 0) Console.Write(".");
            }

            #endregion Child Items
        }

        private void CreateItem(inventoryitems item)
        {
            ++m_counter;

            AssetType assetType = (item.assetType.HasValue) ? (AssetType)item.assetType.Value : AssetType.Unknown;
            InventoryType inventoryType = (item.invType.HasValue) ? (InventoryType)item.invType.Value : InventoryType.Unknown;
            UUID groupID;
            UUID.TryParse(item.groupID, out groupID);

            string parentFolderID = item.parentFolderID;
            if (m_rewriteFolderIDs.ContainsKey(parentFolderID))
                parentFolderID = m_rewriteFolderIDs[parentFolderID];

            // Create this item
            OSDMap permissions = new OSDMap
            {
                { "BaseMask", OSD.FromInteger(item.inventoryBasePermissions) },
                { "EveryoneMask", OSD.FromInteger(item.inventoryEveryOnePermissions) },
                { "GroupMask", OSD.FromInteger(item.inventoryGroupPermissions) },
                { "NextOwnerMask", OSD.FromInteger(item.inventoryNextPermissions.HasValue ? item.inventoryNextPermissions.Value : item.inventoryBasePermissions) },
                { "OwnerMask", OSD.FromInteger(item.inventoryCurrentPermissions.HasValue ? item.inventoryCurrentPermissions.Value : item.inventoryBasePermissions) }
            };

            OSDMap extraData = new OSDMap()
            {
                { "Flags", OSD.FromInteger(item.flags) },
                { "GroupID", OSD.FromUUID(groupID) },
                { "GroupOwned", OSD.FromBoolean(item.groupOwned != 0) },
                { "SalePrice", OSD.FromInteger(item.salePrice) },
                { "SaleType", OSD.FromInteger(item.saleType) },
                { "Permissions", permissions }
            };

            // Add different asset type only if it differs from inventory type
            // (needed for links)
            string invContentType = LLUtil.SLInvTypeToContentType((int)inventoryType);
            string assetContentType = LLUtil.SLAssetTypeToContentType((int)assetType);
            if (invContentType != assetContentType)
                extraData["LinkedItemType"] = OSD.FromString(assetContentType);

            string ownerID = item.avatarID;
            string creatorID = item.creatorID;

            // Handle OpenSim profile anchors by rewriting non-UUID creatorIDs to the ownerID
            // More information on (OSPA) profile anchors @ http://opensimulator.org/wiki/OpenSim_Profile_Anchors
            UUID creatorUUID;
            if (!UUID.TryParse(creatorID, out creatorUUID))
                creatorID = ownerID;

            NameValueCollection requestArgs = new NameValueCollection
            {
                { "RequestMethod", "AddInventoryItem" },
                { "ItemID", item.inventoryID },
                { "AssetID", item.assetID },
                { "ParentID", parentFolderID },
                { "OwnerID", ownerID },
                { "Name", item.inventoryName },
                { "Description", item.inventoryDescription },
                { "CreatorID", creatorID },
                { "ContentType", invContentType },
                { "ExtraData", OSDParser.SerializeJsonString(extraData) }
            };

            OSDMap response = WebUtil.PostToService(m_inventoryUrl, requestArgs);
            bool success = response["Success"].AsBoolean();

            if (success)
            {
                // Gesture handling
                if (assetType == AssetType.Gesture)
                    UpdateGesture(UUID.Parse(item.avatarID), UUID.Parse(item.inventoryID), item.flags == 1);
            }
            else
            {
                Console.WriteLine("Error creating item " + item.inventoryName + " for " + item.avatarID + ": " + response["Message"].AsString());
            }
        }

        #region Gesture Handling

        private void UpdateGesture(UUID userID, UUID itemID, bool enabled)
        {
            OSDArray gestures = FetchGestures(userID);
            OSDArray newGestures = new OSDArray();

            for (int i = 0; i < gestures.Count; i++)
            {
                UUID gesture = gestures[i].AsUUID();
                if (gesture != itemID)
                    newGestures.Add(OSD.FromUUID(gesture));
            }

            if (enabled)
                newGestures.Add(OSD.FromUUID(itemID));

            SaveGestures(userID, newGestures);
        }

        private OSDArray FetchGestures(UUID userID)
        {
            NameValueCollection requestArgs = new NameValueCollection
            {
                { "RequestMethod", "GetUser" },
                { "UserID", userID.ToString() }
            };

            OSDMap response = WebUtil.PostToService(m_userUrl, requestArgs);
            if (response["Success"].AsBoolean())
            {
                OSDMap user = response["User"] as OSDMap;
                if (user != null && response.ContainsKey("Gestures"))
                {
                    OSD gestures = OSDParser.DeserializeJson(response["Gestures"].AsString());
                    if (gestures != null && gestures is OSDArray)
                        return (OSDArray)gestures;
                    else
                        Console.WriteLine("Unrecognized active gestures data for " + userID);
                }
            }
            else
            {
                Console.WriteLine("Failed to fetch active gestures for " + userID + ": " + response["Message"].AsString());
            }

            return new OSDArray();
        }

        private void SaveGestures(UUID userID, OSDArray gestures)
        {
            NameValueCollection requestArgs = new NameValueCollection
            {
                { "RequestMethod", "AddUserData" },
                { "UserID", userID.ToString() },
                { "Gestures", OSDParser.SerializeJsonString(gestures) }
            };

            OSDMap response = WebUtil.PostToService(m_userUrl, requestArgs);
            if (!response["Success"].AsBoolean())
            {
                Console.WriteLine("Failed to save active gestures for " + userID + ": " +
                    response["Message"].AsString());
            }
        }

        #endregion Gesture Handling
    }
}
