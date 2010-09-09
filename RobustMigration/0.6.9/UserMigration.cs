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
    public class UserMigration
    {
        private const string USER_ACCOUNT_TYPE = "UserAccount";
        private const string AP_PREFIX = "_ap_";

        private MySqlConnection m_connection;
        private opensim m_db;
        private string m_userUrl;
        private bool m_masterUserSet;

        public UserMigration(string connectString, string userServiceUrl, string gridOwner)
        {
            using (m_connection = new MySqlConnection(connectString))
            {
                using (m_db = new opensim(m_connection))
                {
                    m_userUrl = userServiceUrl;
                    m_userUrl = userServiceUrl;

                    var users = from u in m_db.users
                                select u;

                    foreach (var user in users)
                    {
                        CreateUser(user, gridOwner);
                        Console.Write("+");
                    }
                }
            }

            if (!m_masterUserSet)
                Console.WriteLine("No grid owner set. You should manually assign one user to have an access level of 255 in the database");
        }

        private void CreateUser(users user, string gridOwner)
        {
            // Create this user
            string name = user.username + " " + user.lastname;
            string email = user.email;

            // If this is the grid owner set them to the maximum AccessLevel. Otherwise, make sure 
            // their AccessLevel is at least 1 (representing a verified, non-anonymous account)
            int accessLevel = (!String.IsNullOrEmpty(gridOwner) && name.Equals(gridOwner, StringComparison.InvariantCultureIgnoreCase))
                ? 255
                : user.godLevel;
            accessLevel = Utils.Clamp(accessLevel, 1, 255);
            if (accessLevel == 255)
                m_masterUserSet = true;

            // Cannot have an empty e-mail address
            if (String.IsNullOrEmpty(email))
                email = "INVALID " + UUID.Random().ToString();

            NameValueCollection requestArgs = new NameValueCollection
            {
                { "RequestMethod", "AddUser" },
                { "UserID", user.UUID },
                { "Name", name },
                { "Email", email },
                { "AccessLevel", accessLevel.ToString() }
            };

            OSDMap response = WebUtil.PostToService(m_userUrl, requestArgs);

            if (response["Success"].AsBoolean())
            {
                Dictionary<string, string> userData = new Dictionary<string, string>
                {
                    { "CreationDate", (user.created != 0) ? user.created.ToString() : Utils.DateTimeToUnixTime(DateTime.UtcNow).ToString() },
                    { "UserFlags", user.userFlags.ToString() }
                };
                AddUserData(user.UUID, userData);
            }
            else
            {
                Console.WriteLine("Failed to store user account for " + name + ": " + response["Message"].AsString());
            }

            AddLocations(user);
            AddAppearance(user);
            AddIdentity(user);
        }

        private void AddLocations(users user)
        {
            // Home location
            Vector3 homePosition;
            if (user.homeLocationX.HasValue && user.homeLocationY.HasValue && user.homeLocationZ.HasValue)
                homePosition = new Vector3(user.homeLocationX.Value, user.homeLocationY.Value, user.homeLocationZ.Value);
            else
                homePosition = Vector3.Zero;

            Vector3 homeLookAt;
            if (user.homeLookAtX.HasValue && user.homeLookAtY.HasValue && user.homeLookAtZ.HasValue)
                homeLookAt = new Vector3(user.homeLookAtX.Value, user.homeLookAtY.Value, user.homeLookAtZ.Value);
            else
                homeLookAt = Vector3.Zero;

            UUID homeRegionID;
            UUID.TryParse(user.homeRegionID, out homeRegionID);

            Dictionary<string, string> userData = new Dictionary<string, string>();

            if (homeRegionID != UUID.Zero)
                userData["HomeLocation"] = SerializeLocation(homeRegionID, homePosition, homeLookAt);
            //if (lastRegionID != UUID.Zero)
            //    userData["LastLocation"] = SerializeLocation(lastRegionID, lastPosition, lastLookAt);

            if (userData.Count > 0)
                AddUserData(user.UUID, userData);
        }

        private void AddAppearance(users user)
        {
            OSDMap map = new OSDMap();
            OSDMap attachMap = new OSDMap();

            // Avatar appearance
            var appearance = m_db.avatarappearance.SingleOrDefault(a => a.Owner == user.UUID);

            if (appearance != null)
            {
                map["Height"] = OSD.FromReal(appearance.AvatarHeight);

                map["ShapeAsset"] = OSD.FromString(appearance.BodyAsset);
                map["ShapeItem"] = OSD.FromString(appearance.BodyItem);
                map["EyesAsset"] = OSD.FromString(appearance.EyesAsset);
                map["EyesItem"] = OSD.FromString(appearance.EyesItem);
                map["GlovesAsset"] = OSD.FromString(appearance.GlovesAsset);
                map["GlovesItem"] = OSD.FromString(appearance.GlovesItem);
                map["HairAsset"] = OSD.FromString(appearance.HairAsset);
                map["HairItem"] = OSD.FromString(appearance.HairItem);
                map["JacketAsset"] = OSD.FromString(appearance.JacketAsset);
                map["JacketItem"] = OSD.FromString(appearance.JacketItem);
                map["PantsAsset"] = OSD.FromString(appearance.PantsAsset);
                map["PantsItem"] = OSD.FromString(appearance.PantsItem);
                map["ShirtAsset"] = OSD.FromString(appearance.ShirtAsset);
                map["ShirtItem"] = OSD.FromString(appearance.ShirtItem);
                map["ShoesAsset"] = OSD.FromString(appearance.ShoesAsset);
                map["ShoesItem"] = OSD.FromString(appearance.ShoesItem);
                map["SkinAsset"] = OSD.FromString(appearance.SkinAsset);
                map["SkinItem"] = OSD.FromString(appearance.SkinItem);
                map["SkirtAsset"] = OSD.FromString(appearance.SkirtAsset);
                map["SkirtItem"] = OSD.FromString(appearance.SkirtItem);
                map["SocksAsset"] = OSD.FromString(appearance.SocksAsset);
                map["SocksItem"] = OSD.FromString(appearance.SocksItem);
                map["UnderpantsAsset"] = OSD.FromString(appearance.UnderpantsAsset);
                map["UnderpantsItem"] = OSD.FromString(appearance.UnderpantsItem);
                map["UndershirtAsset"] = OSD.FromString(appearance.UndershirtAsset);
                map["UndershirtItem"] = OSD.FromString(appearance.UndershirtItem);
            }

            // Avatar attachments
            var attachments = from a in m_db.avatarattachments
                              where a.UUID == user.UUID
                              select a;

            foreach (var attachment in attachments)
            {
                attachMap[AP_PREFIX + attachment.attachpoint] = OSD.FromString(attachment.item);
            }

            Dictionary<string, string> userData = new Dictionary<string, string>();

            if (map.Count > 0)
                userData["LLAppearance"] = OSDParser.SerializeJsonString(map);
            if (attachMap.Count > 0)
                userData["LLAttachments"] = OSDParser.SerializeJsonString(attachMap);

            if (userData.Count > 0)
                AddUserData(user.UUID, userData);
        }

        private void AddIdentity(users user)
        {
            string name = user.username + " " + user.lastname;
            string credential = user.passwordHash;

            // If the password is actually salted, store "hash:salt"
            if (!String.IsNullOrEmpty(user.passwordSalt))
                credential += ":" + user.passwordSalt;

            // Make sure $1$ is prepended (our md5hash format in SimianGrid requires this)
            if (!credential.StartsWith("$1$"))
                credential = "$1$" + credential;

            NameValueCollection requestArgs = new NameValueCollection
            {
                { "RequestMethod", "AddIdentity" },
                { "Identifier", name },
                { "Credential", credential },
                { "Type", "md5hash" },
                { "UserID", user.UUID }
            };

            OSDMap response = WebUtil.PostToService(m_userUrl, requestArgs);
            bool success = response["Success"].AsBoolean();

            Console.Write(".");

            if (!success)
                Console.WriteLine("Failed to set password for {0} ({1})", name, user.UUID);
        }

        private void AddUserData(string userID, Dictionary<string, string> data)
        {
            NameValueCollection requestArgs = new NameValueCollection
            {
                { "RequestMethod", "AddUserData" },
                { "UserID", userID }
            };

            foreach (KeyValuePair<string, string> kvp in data)
                requestArgs[kvp.Key] = kvp.Value;

            OSDMap response = WebUtil.PostToService(m_userUrl, requestArgs);
            bool success = response["Success"].AsBoolean();

            Console.Write(".");

            if (!success)
                Console.WriteLine("Failed to store user data for " + userID + ": " + response["Message"].AsString());
        }

        private static string SerializeLocation(UUID regionID, Vector3 position, Vector3 lookAt)
        {
            return "{" + String.Format("\"SceneID\":\"{0}\",\"Position\":\"{1}\",\"LookAt\":\"{2}\"", regionID, position, lookAt) + "}";
        }
    }
}
