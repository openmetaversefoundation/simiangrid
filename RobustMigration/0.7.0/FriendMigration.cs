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
using System.Collections.Specialized;
using System.Linq;
using MySql.Data.MySqlClient;
using OpenMetaverse;
using OpenMetaverse.StructuredData;

namespace RobustMigration.v070
{
    public class FriendMigration
    {
        private MySqlConnection m_connection;
        private opensim m_db;
        private string m_userUrl;

        public FriendMigration(string connectString, string userServiceUrl)
        {
            using (m_connection = new MySqlConnection(connectString))
            {
                using (m_db = new opensim(m_connection))
                {
                    m_userUrl = userServiceUrl;
                    m_userUrl = userServiceUrl;

                    var friends = from f in m_db.friends
                                  select f;

                    int i = 0;
                    foreach (var friend in friends)
                    {
                        CreateFriend(friend);
                        if (++i % 100 == 0) Console.Write("+");
                    }
                }
            }
        }

        private void CreateFriend(friends friend)
        {
            // Create this friendship
            NameValueCollection requestArgs = new NameValueCollection
            {
                { "RequestMethod", "AddGeneric" },
                { "OwnerID", friend.PrincipalID },
                { "Type", "Friend" },
                { "Key", friend.Friend },
                { "Value", friend.Flags }
            };
            // TODO: Is friend.Offered used at all?

            OSDMap response = WebUtil.PostToService(m_userUrl, requestArgs);
            bool success = response["Success"].AsBoolean();

            if (!success)
               Console.WriteLine("Failed to store friend " + friend.Friend + " for user " + friend.PrincipalID + ": " + response["Message"].AsString());
        }
    }
}
