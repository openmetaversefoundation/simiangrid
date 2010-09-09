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

namespace RobustMigration.v070
{
    public class UserMigration
    {
        private const string USER_ACCOUNT_TYPE = "UserAccount";
        private const string AP_PREFIX = "_ap_";

        private static readonly HashSet<string> ALLOWED_APPEARANCE_ENTRIES = new HashSet<string>
        {
            "Height",
            "ShapeAsset",
            "ShapeItem",
            "EyesAsset",
            "EyesItem",
            "GlovesAsset",
            "GlovesItem",
            "HairAsset",
            "HairItem",
            "JacketAsset",
            "JacketItem",
            "PantsAsset",
            "PantsItem",
            "ShirtAsset",
            "ShirtItem",
            "ShoesAsset",
            "ShoesItem",
            "SkinAsset",
            "SkinItem",
            "SkirtAsset",
            "SkirtItem",
            "SocksAsset",
            "SocksItem",
            "UnderpantsAsset",
            "UnderpantsItem",
            "UndershirtAsset",
            "UndershirtItem"
        };

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

                    var users = from u in m_db.useraccounts
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

        private void CreateUser(useraccounts user, string gridOwner)
        {
            // Create this user
            string name = user.FirstName + " " + user.LastName;
            string email = user.Email;

            // If this is the grid owner set them to the maximum AccessLevel. Otherwise, make sure 
            // their AccessLevel is at least 1 (representing a verified, non-anonymous account)
            int accessLevel = (!String.IsNullOrEmpty(gridOwner) && name.Equals(gridOwner, StringComparison.InvariantCultureIgnoreCase))
                ? 255
                : user.UserLevel;
            accessLevel = Utils.Clamp(accessLevel, 1, 255);
            if (accessLevel == 255)
                m_masterUserSet = true;

            // Cannot have an empty e-mail address
            if (String.IsNullOrEmpty(email))
                email = "INVALID " + UUID.Random().ToString();

            NameValueCollection requestArgs = new NameValueCollection
            {
                { "RequestMethod", "AddUser" },
                { "UserID", user.PrincipalID },
                { "Name", name },
                { "Email", email },
                { "AccessLevel", accessLevel.ToString() }
            };

            OSDMap response = WebUtil.PostToService(m_userUrl, requestArgs);

            if (response["Success"].AsBoolean())
            {
                Dictionary<string, string> userData = new Dictionary<string,string>
                {
                    { "CreationDate", (user.Created.HasValue ? user.Created.Value : (int)Utils.DateTimeToUnixTime(DateTime.UtcNow)).ToString() },
                    { "UserFlags", user.UserFlags.ToString() },
                    { "UserTitle", user.UserTitle }
                };
                AddUserData(user.PrincipalID, userData);
            }
            else
            {
                Console.WriteLine("Failed to store user account for " + name + ": " + response["Message"].AsString());
            }

            AddLocations(user);
            AddAppearance(user);
            AddIdentity(user);
        }

        private void AddLocations(useraccounts user)
        {
            // Home and last location
            var locations = m_db.griduser.SingleOrDefault(g => g.UserID == user.PrincipalID);
            if (locations != null)
            {
                Vector3 homePosition, homeLookAt, lastPosition, lastLookAt;
                UUID homeRegionID, lastRegionID;

                Vector3.TryParse(locations.HomePosition, out homePosition);
                Vector3.TryParse(locations.HomeLookAt, out homeLookAt);
                UUID.TryParse(locations.HomeRegionID, out homeRegionID);

                Vector3.TryParse(locations.LastPosition, out lastPosition);
                Vector3.TryParse(locations.LastLookAt, out lastLookAt);
                UUID.TryParse(locations.LastRegionID, out lastRegionID);

                Dictionary<string, string> userData = new Dictionary<string,string>();

                if (homeRegionID != UUID.Zero)
                    userData["HomeLocation"] = SerializeLocation(homeRegionID, homePosition, homeLookAt);
                if (lastRegionID != UUID.Zero)
                    userData["LastLocation"] = SerializeLocation(lastRegionID, lastPosition, lastLookAt);

                if (userData.Count > 0)
                    AddUserData(user.PrincipalID, userData);
            }
        }

        private void AddAppearance(useraccounts user)
        {
            // Avatar appearance
            var metadata = from a in m_db.avatars
                           where a.PrincipalID == user.PrincipalID
                           select a;

            OSDMap appearance = new OSDMap();
            OSDMap attachments = new OSDMap();

            foreach (var entry in metadata)
            {
                if (entry.Name.StartsWith(AP_PREFIX))
                {
                    attachments[entry.Name] = OSD.FromString(entry.Value);
                }
                else
                {
                    string name = entry.Name;
                    if (name == "AvatarHeight") name = "Height";
                    if (name == "BodyItem") name = "ShapeItem";
                    if (name == "BodyAsset") name = "ShapeAsset";

                    if (ALLOWED_APPEARANCE_ENTRIES.Contains(name))
                        appearance[name] = OSD.FromString(entry.Value);
                }
            }

            Dictionary<string, string> userData = new Dictionary<string, string>();

            if (appearance.Count > 0)
                userData["LLAppearance"] = OSDParser.SerializeJsonString(appearance);
            if (attachments.Count > 0)
                userData["LLAttachments"] = OSDParser.SerializeJsonString(attachments);

            if (userData.Count > 0)
                AddUserData(user.PrincipalID, userData);
        }

        private void AddIdentity(useraccounts user)
        {
            string name = user.FirstName + " " + user.LastName;

            // Create the user identity
            var auth = m_db.auth.SingleOrDefault(a => a.UUID == user.PrincipalID);
            if (auth != null && auth.accountType == USER_ACCOUNT_TYPE)
            {
                string credential = auth.passwordHash;

                // If the password is actually salted store "hash:salt"
                if (!String.IsNullOrEmpty(auth.passwordSalt))
                    credential += ":" + auth.passwordSalt;

                // Make sure $1$ is prepended (our md5hash format in SimianGrid requires this)
                if (!credential.StartsWith("$1$"))
                    credential = "$1$" + credential;

                NameValueCollection requestArgs = new NameValueCollection
                {
                    { "RequestMethod", "AddIdentity" },
                    { "Identifier", name },
                    { "Credential", credential },
                    { "Type", "md5hash" },
                    { "UserID", user.PrincipalID }
                };

                OSDMap response = WebUtil.PostToService(m_userUrl, requestArgs);
                bool success = response["Success"].AsBoolean();

                Console.Write(".");

                if (!success)
                    Console.WriteLine("Failed to set password for {0} ({1})", name, user.PrincipalID);
            }
            else
            {
                Console.WriteLine("No authorization info found for " + name);
            }
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
