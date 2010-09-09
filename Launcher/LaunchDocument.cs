using System;
using System.IO;
using OpenMetaverse.StructuredData;

namespace VWRAPLauncher
{
    /// <summary>
    /// VWRAP Launch Document
    /// </summary>
    /// <remarks>http://tools.ietf.org/html/draft-hamrick-vwrap-launch-00</remarks>
    public class LaunchDocument
    {
        /// <summary>Account identifier</summary>
        public string AccountName;
        /// <summary>Full avatar name. A combination of first and last name on
        /// many grids</summary>
        public string Name;
        /// <summary>Grid login server URL</summary>
        public string LoginUrl;
        /// <summary>Optional URI for the starting location</summary>
        public string Region;
        /// <summary>True if the authentication type is login URL capability,
        /// otherwise false</summary>
        public bool IsLoginUrlCapability;
        /// <summary>Extended data - OpenSim/SL specific</summary>
        public string WelcomeUrl;
        /// <summary>Extended data - OpenSim/SL specific</summary>
        public string EconomyUrl;
        /// <summary>Extended data - OpenSim/SL specific</summary>
        public string AboutUrl;
        /// <summary>Extended data - OpenSim/SL specific</summary>
        public string RegisterUrl;
        /// <summary>Extended data - OpenSim/SL specific</summary>
        public string HelpUrl;
        /// <summary>Extended data - OpenSim/SL specific</summary>
        public string PasswordUrl;

        /// <summary>Parses the first name out of Name or returns an empty
        /// string</summary>
        public string FirstName
        {
            get
            {
                if (!String.IsNullOrEmpty(Name) && Name.Contains(" "))
                    return Name.Substring(0, Name.IndexOf(' '));
                return String.Empty;
            }
        }

        /// <summary>Parses the last name out of Name or returns an empty
        /// string</summary>
        public string LastName
        {
            get
            {
                if (!String.IsNullOrEmpty(Name) && Name.Contains(" "))
                    return Name.Substring(Name.IndexOf(' ') + 1);
                return String.Empty;
            }
        }

        /// <summary>
        /// Parses a VWRAP Launch Document file
        /// </summary>
        /// <param name="path">Filename of the launch document to parse</param>
        /// <returns>The parsed document, or null on failure</returns>
        public static LaunchDocument FromFile(string path)
        {
            try
            {
                using (FileStream stream = new FileStream(path, FileMode.Open, FileAccess.Read))
                {
                    OSDMap launchMap = OSDParser.Deserialize(stream) as OSDMap;

                    if (launchMap != null)
                    {
                        LaunchDocument document = new LaunchDocument();

                        document.LoginUrl = launchMap["loginurl"].AsString();
                        document.WelcomeUrl = launchMap["welcomeurl"].AsString(); // --loginpage
                        document.EconomyUrl = launchMap["economyurl"].AsString(); // --helperuri
                        document.AboutUrl = launchMap["abouturl"].AsString();
                        document.RegisterUrl = launchMap["registerurl"].AsString();
                        document.HelpUrl = launchMap["helpurl"].AsString();
                        document.PasswordUrl = launchMap["passwordurl"].AsString();

                        // Not a valid launch doc without a loginurl
                        if (String.IsNullOrEmpty(document.LoginUrl))
                            return null;

                        document.Region = launchMap["region"].AsString();

                        OSDMap authenticatorMap = launchMap["authenticator"] as OSDMap;
                        if (authenticatorMap != null)
                        {
                            document.IsLoginUrlCapability = (authenticatorMap["type"].AsString() == "capability");
                        }

                        OSDMap identifierMap = launchMap["identifier"] as OSDMap;
                        if (identifierMap != null)
                        {
                            document.AccountName = launchMap["account_name"].AsString();
                            document.Name = launchMap["name"].AsString();

                            // Legacy support
                            if (String.IsNullOrEmpty(document.Name))
                            {
                                string first = launchMap["first_name"].AsString();
                                string last = launchMap["last_name"].AsString();

                                document.Name = (first + " " + last).Trim();
                            }
                        }

                        return document;
                    }
                }
            }
            catch
            {
            }

            return null;
        }
    }
}
