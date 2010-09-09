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
using System.Linq;
using MySql.Data.MySqlClient;

namespace RobustMigration
{
    class Program
    {
        static void Main(string[] args)
        {
            bool printHelp = false;
            bool printVersion = false;

            string connectionString = null;
            string userUrl = null;
            string inventoryUrl = null;
            string assetUrl = null;
            string gridOwner = null;

            #region Command Line Argument Handling

            Mono.Options.OptionSet set = new Mono.Options.OptionSet()
            {
                { "c=|connection=", "OpenSim database connection string (ex. \"Data Source=localhost;Database=opensim;User ID=opensim;Password=opensim;\")", v => connectionString = v },
                { "u=|user=", "SimianGrid user service URL (ex. http://localhost/Grid/)", v => userUrl = v },
                { "i=|inventory=", "SimianGrid inventory service URL (ex. http://localhost/Grid/) (optional)", v => inventoryUrl = v },
                { "a=|asset=", "SimianGrid asset service URL (ex. http://localhost/Grid/) (optional)", v => assetUrl = v },
                { "g=|gridowner=", "Full name of a migrated user to appoint as the grid owner (ex. \"Master OpenSim\") (optional)", v => gridOwner = v },
                { "h|?|help", "Show this help", v => printHelp = true },
                { "v|version", "Show version information", v => printVersion = true }
            };
            set.Parse(args);

            if (String.IsNullOrEmpty(connectionString) || String.IsNullOrEmpty(userUrl))
                printHelp = true;

            if (printHelp || printVersion)
            {
                string version = System.Reflection.Assembly.GetExecutingAssembly().GetName().Version.ToString();
                Console.WriteLine("OpenSim Robust to SimianGrid database migration tool version " + version);
                Console.WriteLine("part of SimianGrid, an Open Metaverse Foundation project");
                Console.WriteLine("Written by John Hurliman, Intel Corporation");
                Console.WriteLine("Distributed under the BSD license");

                if (printHelp)
                    Console.WriteLine();
                else
                    Environment.Exit(0);
            }

            if (printHelp)
            {
                set.WriteOptionDescriptions(Console.Out);
                Environment.Exit(0);
            }

            #endregion Command Line Argument Handling

            try
            {
                Console.WriteLine("Detecting OpenSim database version...");

                bool is070;
                if (TryGetStoreVersion(connectionString, out is070))
                {
                    if (is070)
                        Migrate070(connectionString, userUrl, assetUrl, inventoryUrl, gridOwner);
                    else
                        Migrate069(connectionString, userUrl, assetUrl, inventoryUrl, gridOwner);
                }
                else
                {
                    Console.WriteLine("Failed to detect the OpenSim database version");
                }
            }
            catch (Exception ex)
            {
                Console.WriteLine("Migration failed: " + ex);
            }
        }

        static bool TryGetStoreVersion(string connectString, out bool is070)
        {
            const string ASSET_STORE_MIGRATION = "AssetStore";
            const int LAST_069_ASSET_MIGRATION = 6;

            using (MySqlConnection connection = new MySqlConnection(connectString))
            {
                using (v069.opensim db = new v069.opensim(connection))
                {
                    var assetStoreMigration = db.migrations.SingleOrDefault(m => ASSET_STORE_MIGRATION == m.name);

                    if (assetStoreMigration != null && assetStoreMigration.version.HasValue)
                    {
                        is070 = (assetStoreMigration.version.Value > LAST_069_ASSET_MIGRATION);
                        return true;
                    }
                }
            }

            is070 = false;
            return false;
        }

        static void Migrate069(string connectionString, string userUrl, string assetUrl, string inventoryUrl, string gridOwner)
        {
            Console.WriteLine("Migrating from OpenSim 0.6.9 to SimianGrid");

            if (!String.IsNullOrEmpty(userUrl))
            {
                Console.WriteLine("Starting user migrations");
                v069.UserMigration users = new v069.UserMigration(connectionString, userUrl, gridOwner);
                Console.WriteLine();
            }

            if (!String.IsNullOrEmpty(assetUrl))
            {
                Console.WriteLine("Starting asset migrations");
                v069.AssetMigration assets = new v069.AssetMigration(connectionString, assetUrl);
                Console.WriteLine();
            }

            if (!String.IsNullOrEmpty(inventoryUrl) && !String.IsNullOrEmpty(userUrl))
            {
                Console.WriteLine("Starting inventory migrations");
                v069.InventoryMigration inventories = new v069.InventoryMigration(connectionString, inventoryUrl, userUrl);
                Console.WriteLine();
            }

            if (!String.IsNullOrEmpty(userUrl))
            {
                Console.WriteLine("Starting friend migrations");
                v069.FriendMigration friends = new v069.FriendMigration(connectionString, userUrl);
                Console.WriteLine();
            }

            Console.WriteLine("Done.");
        }

        static void Migrate070(string connectionString, string userUrl, string assetUrl, string inventoryUrl, string gridOwner)
        {
            Console.WriteLine("Migrating from OpenSim 0.7.0 to SimianGrid");

            if (!String.IsNullOrEmpty(userUrl))
            {
                Console.WriteLine("Starting user migrations");
                v070.UserMigration users = new v070.UserMigration(connectionString, userUrl, gridOwner);
                Console.WriteLine();
            }

            if (!String.IsNullOrEmpty(assetUrl))
            {
                Console.WriteLine("Starting asset migrations");
                v070.AssetMigration assets = new v070.AssetMigration(connectionString, assetUrl);
                Console.WriteLine();
            }

            if (!String.IsNullOrEmpty(inventoryUrl) && !String.IsNullOrEmpty(userUrl))
            {
                Console.WriteLine("Starting inventory migrations");
                v069.InventoryMigration inventories = new v069.InventoryMigration(connectionString, inventoryUrl, userUrl);
                Console.WriteLine();
            }

            if (!String.IsNullOrEmpty(userUrl))
            {
                Console.WriteLine("Starting friend migrations");
                v070.FriendMigration friends = new v070.FriendMigration(connectionString, userUrl);
                Console.WriteLine();
            }

            Console.WriteLine("Done.");
        }
    }
}
