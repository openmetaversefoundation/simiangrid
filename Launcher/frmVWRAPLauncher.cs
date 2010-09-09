using System;
using System.Collections.Generic;
using System.ComponentModel;
using System.Data;
using System.Drawing;
using System.IO;
using System.Text;
using System.Windows.Forms;
using Microsoft.Win32;

namespace VWRAPLauncher
{
    /// <summary>
    /// Main form
    /// </summary>
    public partial class frmVWRAPLauncher : Form
    {
        #region Constants

        const int CHOOSE_ITEM_HEIGHT = 25;
        const int VIEWER_ITEM_HEIGHT = 36;

        const string START_HOME = "My Home";
        const string START_LAST = "My Last Location";
        const string START_TYPE = "<Type region name>";

        /// <summary>Registry key for Cable Beach</summary>
        const string REG_VWRAP_LAUNCHER = "Software\\VWRAP Launcher";

        /// <summary>Registry key that stores our list of viewers</summary>
        const string REG_VIEWER_LIST = "Software\\VWRAP Launcher\\RecentFiles";

        /// <summary>Registry key name for storing the preferred viewer</summary>
        const string REG_REMEMBER = "Preference";

        /// <summary>Default arguments passed to viewer executables</summary>
        const string DEFAULT_ARGUMENTS = "-multiple";

        /// <summary>Common hiding places for the viewer on Windows</summary>
        static readonly string[] COMMON_WINDOWS_PATHS =
        {
            "C:\\Program Files\\SecondLife\\SecondLife.exe",
            "C:\\Program Files\\SecondLifeReleaseCandidate\\SecondLifeReleaseCandidate.exe",
            "C:\\Program Files\\SecondLifeViewer2\\SecondLife.exe",
            "C:\\Program Files\\Imprudence\\imprudence.exe",
            "C:\\Program Files (x86)\\SecondLife\\SecondLife.exe",
            "C:\\Program Files (x86)\\SecondLifeReleaseCandidate\\SecondLifeReleaseCandidate.exe",
            "C:\\Program Files (x86)\\SecondLifeViewer2\\SecondLife.exe",
            "C:\\Program Files (x86)\\Imprudence\\imprudence.exe",
        };

        /// <summary>Common hiding places for the viewer on a Mac</summary>
        static readonly string[] COMMON_OSX_PATHS =
        {
            "/Applications/Second Life.app/Contents/MacOS/Second Life",
            "/Applications/Second Life Release Candidate.app/Contents/MacOS/Second Life",
        };

        /// <summary>Common names for the viewer launch script on Linux</summary>
        static readonly string[] COMMON_LINUX_APPNAMES =
        {
            "secondlife",
        };

        #endregion Constants

        /// <summary>Path to load the launch document from</summary>
        public static string LaunchDocumentPath;

        /// <summary>Information about the selected viewer</summary>
        private ViewerInfo m_selectedViewerInfo;
        /// <summary>The parsed launch document</summary>
        private LaunchDocument m_launchDocument;

        /// <summary>
        /// Constructor
        /// </summary>
        public frmVWRAPLauncher()
        {
            InitializeComponent();
        }

        private void frmVWRAPLauncher_Load(object sender, EventArgs e)
        {
            string path, arguments;

            cboStartLocation.Items.Add(START_HOME);
            cboStartLocation.Items.Add(START_LAST);
            cboStartLocation.Items.Add(START_TYPE);
            cboStartLocation.SelectedIndex = 1;

            panel.VerticalScroll.Enabled = true;
            panel.Click += HighlightItem;

            PopulateList();

            if (!String.IsNullOrEmpty(LaunchDocumentPath))
            {
                m_launchDocument = LaunchDocument.FromFile(LaunchDocumentPath);
                if (m_launchDocument == null)
                {
                    MessageBox.Show("Failed to load a VWRAP launch document from \"" + LaunchDocumentPath + "\"",
                        "VWRAP Launcher", MessageBoxButtons.OK, MessageBoxIcon.Warning);
                }
            }

            if (m_launchDocument != null)
            {
                // If a viewer preference was saved we can go straight to launching
                if (LoadViewerPreference(out path, out arguments))
                {
                    chkRemember.Checked = true;
                    LaunchViewer(path, arguments);
                }
            }
            else
            {
                // Clear the remember preference
                chkRemember_CheckedChanged(chkRemember, null);

                MessageBox.Show("The VWRAP Launcher will automatically handle VWRAP documents in a web browser. " +
                    "You do not need to run this application directly.", "VWRAP Launcher",
                    MessageBoxButtons.OK, MessageBoxIcon.Information);
            }
        }

        private bool LoadViewerPreference(out string path, out string arguments)
        {
            try
            {
                RegistryKey vwrapKey = Registry.CurrentUser.OpenSubKey(REG_VWRAP_LAUNCHER);

                string viewerPath = vwrapKey.GetValue(REG_REMEMBER) as string;
                string args = String.Empty;

                // Unseparate the viewer path and arguments
                if (viewerPath.Contains("|"))
                {
                    int pipeIdx = viewerPath.IndexOf('|');
                    args = viewerPath.Substring(pipeIdx + 1);
                    viewerPath = viewerPath.Substring(0, pipeIdx);
                }

                // Make sure the viewer still exists
                if (File.Exists(viewerPath))
                {
                    path = viewerPath;
                    arguments = args;
                    return true;
                }
            }
            catch { }

            path = null;
            arguments = null;
            return false;
        }

        #region Display

        private void PopulateList()
        {
            ClearList();

            panel.Controls.Add(CreateChooseItem());

            List<ViewerInfo> viewers = GetSavedViewers();

            List<ViewerInfo> discoveredViewers = GetDiscoveredViewers();
            foreach (ViewerInfo viewer in discoveredViewers)
            {
                if (!viewers.Contains(viewer))
                    viewers.Add(viewer);
            }

            for (int i = viewers.Count - 1; i >= 0; i--)
            {
                ViewerInfo viewer = viewers[i];
                panel.Controls.Add(viewer);
            }

            SaveViewerList();
        }

        private void ClearList()
        {
            foreach (Control control in panel.Controls)
                control.Dispose();

            panel.Controls.Clear();
        }

        private Panel CreateChooseItem()
        {
            Panel chooseItem = new Panel();
            chooseItem.Width = panel.Width;
            chooseItem.Height = CHOOSE_ITEM_HEIGHT;
            chooseItem.BackColor = panel.BackColor;
            chooseItem.Dock = DockStyle.Top;
            chooseItem.Padding = new Padding(0, 0, 5, 0);
            chooseItem.Click += HighlightItem;

            Label chooseLabel = new Label();
            chooseLabel.Text = "Choose a Viewer";
            chooseLabel.Height = chooseItem.Height;
            chooseLabel.TextAlign = ContentAlignment.MiddleLeft;
            chooseLabel.Dock = DockStyle.Left;
            chooseLabel.Click += HighlightItem;

            Button chooseButton = new Button();
            chooseButton.Text = "&Choose...";
            chooseButton.Width = 68;
            chooseButton.Height = chooseItem.Height;
            chooseButton.Dock = DockStyle.Right;
            chooseButton.BackColor = cmdOK.BackColor;
            chooseButton.Click += chooseButton_Click;

            chooseItem.Controls.Add(chooseLabel);
            chooseItem.Controls.Add(chooseButton);

            return chooseItem;
        }

        private ViewerInfo CreateViewerItem(string name, string path, string arguments)
        {
            ViewerInfo viewerItem = new ViewerInfo(name, path, arguments);
            viewerItem.Width = panel.Width;
            viewerItem.Height = VIEWER_ITEM_HEIGHT;
            viewerItem.BackColor = panel.BackColor;
            viewerItem.Dock = DockStyle.Top;
            viewerItem.Click += HighlightItem;

            Label viewerLabel = new Label();
            viewerLabel.Font = new Font(viewerLabel.Font, FontStyle.Bold);
            viewerLabel.AutoEllipsis = true;
            viewerLabel.Text = name;
            viewerLabel.Height = viewerItem.Height / 2;
            viewerLabel.Width = panel.Width;
            viewerLabel.TextAlign = ContentAlignment.MiddleLeft;
            viewerLabel.Dock = DockStyle.Top;
            viewerLabel.Click += HighlightItem;

            Label viewerPathLabel = new Label();
            viewerPathLabel.AutoEllipsis = true;
            viewerPathLabel.Text = path;
            viewerPathLabel.Height = viewerItem.Height / 2;
            viewerPathLabel.Width = panel.Width;
            viewerPathLabel.TextAlign = ContentAlignment.MiddleLeft;
            viewerPathLabel.Dock = DockStyle.Top;
            viewerPathLabel.Click += HighlightItem;

            viewerItem.Controls.Add(viewerPathLabel);
            viewerItem.Controls.Add(viewerLabel);

            return viewerItem;
        }

        #endregion Display

        #region Saving/Loading

        private List<ViewerInfo> GetDiscoveredViewers()
        {
            List<string> paths = FindSecondLifeWindows();
            paths.AddRange(FindSecondLifeOSX());
            paths.AddRange(FindSecondLifeLinux());

            List<ViewerInfo> viewers = new List<ViewerInfo>(paths.Count);

            for (int i = 0; i < paths.Count; i++)
            {
                string path = paths[i];

                ViewerInfo viewer = CreateViewerItem(
                    Path.GetFileNameWithoutExtension(path),
                    path,
                    DEFAULT_ARGUMENTS);

                viewers.Add(viewer);
            }

            return viewers;
        }

        private List<ViewerInfo> GetSavedViewers()
        {
            List<ViewerInfo> viewers = new List<ViewerInfo>();

            RegistryKey savedViewersKey = Registry.CurrentUser.OpenSubKey(REG_VIEWER_LIST);

            if (savedViewersKey != null)
            {
                string[] keys = savedViewersKey.GetValueNames();

                for (int i = 0; i < keys.Length; i++)
                {
                    string viewerPath = savedViewersKey.GetValue(keys[i]).ToString();
                    string args = String.Empty;

                    // Unseparate the viewer path and arguments
                    if (viewerPath.Contains("|"))
                    {
                        int pipeIdx = viewerPath.IndexOf('|');
                        args = viewerPath.Substring(pipeIdx + 1);
                        viewerPath = viewerPath.Substring(0, pipeIdx);
                    }

                    // Make sure the viewer still exists
                    if (File.Exists(viewerPath))
                    {
                        ViewerInfo viewer = CreateViewerItem(
                            Path.GetFileNameWithoutExtension(viewerPath),
                            viewerPath,
                            args);
                        viewers.Add(viewer);
                    }
                }
            }

            return viewers;
        }

        private void SaveViewerList()
        {
            try { Registry.CurrentUser.DeleteSubKey(REG_VIEWER_LIST); }
            catch (Exception) { }

            RegistryKey savedViewersKey = Registry.CurrentUser.CreateSubKey(REG_VIEWER_LIST);

            int j = 0;
            for (int i = panel.Controls.Count - 1; i >= 0; i--)
            {
                Panel item = panel.Controls[i] as Panel;
                if (item is ViewerInfo)
                {
                    ViewerInfo viewer = (ViewerInfo)item;
                    savedViewersKey.SetValue(j++.ToString(), viewer.Path + "|" + viewer.Arguments);
                }
            }
        }

        #endregion Saving/Loading

        #region SL Executable Finding

        private List<string> FindSecondLifeWindows()
        {
            List<string> paths = new List<string>();

            try
            {
                // Check the registry for a URI handler
                RegistryKey secondlifeKey = Registry.ClassesRoot.OpenSubKey("secondlife\\shell\\open\\command");
                if (secondlifeKey != null)
                {
                    string path = (secondlifeKey.GetValue(String.Empty)) as string;

                    if (path != null)
                    {
                        // Parse the executable path out of the first pair of quotes
                        path = path.TrimStart('"');
                        path = path.Substring(0, path.IndexOf('"'));

                        if (File.Exists(path) && !paths.Contains(path))
                            paths.Add(path);
                    }
                }
            }
            catch (Exception) { }

            // Check a couple of common paths
            for (int i = 0; i < COMMON_WINDOWS_PATHS.Length; i++)
            {
                string path = COMMON_WINDOWS_PATHS[i];
                if (File.Exists(path) && !paths.Contains(path))
                    paths.Add(path);
            }

            return paths;
        }

        private List<string> FindSecondLifeOSX()
        {
            List<string> paths = new List<string>(); ;

            // Check a couple of common paths
            for (int i = 0; i < COMMON_OSX_PATHS.Length; i++)
            {
                string path = COMMON_OSX_PATHS[i];
                if (File.Exists(path) && !paths.Contains(path))
                    paths.Add(path);
            }

            return paths;
        }

        private List<string> FindSecondLifeLinux()
        {
            List<string> paths = new List<string>();

            string pathVariable = Environment.GetEnvironmentVariable("PATH");
            string[] pathDirs = pathVariable.Split(Path.PathSeparator);

            // Search all paths in the PATH variable for common SL executable names
            for (int i = 0; i < pathDirs.Length; i++)
            {
                for (int j = 0; j < COMMON_LINUX_APPNAMES.Length; j++)
                {
                    string path = pathDirs[i] + Path.DirectorySeparatorChar + COMMON_LINUX_APPNAMES[j];
                    if (File.Exists(path) && !paths.Contains(path))
                        paths.Add(path);
                }
            }

            return paths;
        }

        #endregion SL Executable Finding

        #region Events

        private void chooseButton_Click(object sender, EventArgs e)
        {
            OpenFileDialog dialog = new OpenFileDialog();
            dialog.Multiselect = false;

            if (Environment.OSVersion.Platform != PlatformID.MacOSX && Environment.OSVersion.Platform != PlatformID.Unix)
                dialog.Filter = "Viewer Executable (*.exe) |*.exe";

            if (dialog.ShowDialog() == DialogResult.OK)
            {
                // Extra paranoid sanity check
                if (File.Exists(dialog.FileName))
                {
                    ViewerInfo viewer = CreateViewerItem(
                        Path.GetFileNameWithoutExtension(dialog.FileName),
                        dialog.FileName,
                        DEFAULT_ARGUMENTS);

                StartRemoval:
                    foreach (Panel item in panel.Controls)
                    {
                        if (item is ViewerInfo)
                        {
                            ViewerInfo viewerEntry = (ViewerInfo)item;
                            if (viewer.Equals(viewerEntry))
                            {
                                panel.Controls.Remove(viewerEntry);
                                viewerEntry.Dispose();
                                goto StartRemoval;
                            }
                        }
                    }

                    panel.Controls.Add(viewer);

                    SaveViewerList();
                }
            }
        }

        private void HighlightItem(object sender, EventArgs e)
        {
            if (!(sender is Panel))
            {
                Control control = (Control)sender;
                HighlightItem(control.Parent, null);
                return;
            }

            Panel highlightItem = (Panel)sender;

            if (highlightItem != panel)
            {
                // Highlight this panel
                if (highlightItem is ViewerInfo)
                {
                    m_selectedViewerInfo = (ViewerInfo)highlightItem;
                    cmdOK.Enabled = true;
                    chkRemember.Enabled = true;
                }
                else
                {
                    m_selectedViewerInfo = null;
                    cmdOK.Enabled = false;
                    chkRemember.Enabled = false;
                }

                highlightItem.BackColor = System.Drawing.SystemColors.Highlight;

                foreach (Control control in highlightItem.Controls)
                {
                    if (control is Label)
                    {
                        Label label = (Label)control;
                        label.ForeColor = panel.BackColor;
                    }
                }

                // Unhighlight all of the other panels
                foreach (Panel item in panel.Controls)
                {
                    if (item != highlightItem)
                    {
                        item.BackColor = panel.BackColor;

                        foreach (Control control in item.Controls)
                        {
                            if (control is Label)
                            {
                                Label label = (Label)control;
                                label.ForeColor = label1.ForeColor;
                            }
                        }
                    }
                }
            }
            else
            {
                // Unhighlight all of the panels
                cmdOK.Enabled = false;
                chkRemember.Enabled = false;
                m_selectedViewerInfo = null;

                foreach (Panel item in panel.Controls)
                {
                    item.BackColor = panel.BackColor;

                    foreach (Control control in item.Controls)
                    {
                        if (control is Label)
                        {
                            Label label = (Label)control;
                            label.ForeColor = label1.ForeColor;
                        }
                    }
                }
            }
        }

        private void cmdOK_Click(object sender, EventArgs e)
        {
            ViewerInfo viewer = m_selectedViewerInfo;

            // Sanity check
            if (viewer == null)
            {
                cmdOK.Enabled = false;
                chkRemember.Enabled = false;
                return;
            }

            LaunchViewer(viewer.Path, viewer.Arguments);
        }

        private void cmdCancel_Click(object sender, EventArgs e)
        {
            Application.Exit();
        }

        private void chkRemember_CheckedChanged(object sender, EventArgs e)
        {
            try
            {
                RegistryKey rememberViewerKey = Registry.CurrentUser.CreateSubKey(REG_VWRAP_LAUNCHER);

                if (chkRemember.Checked)
                {
                    ViewerInfo viewer = m_selectedViewerInfo;

                    // Sanity check
                    if (viewer == null)
                    {
                        chkRemember.Enabled = false;
                        return;
                    }

                    rememberViewerKey.SetValue(REG_REMEMBER, viewer.Path + "|" + viewer.Arguments);
                }
                else
                {
                    rememberViewerKey.DeleteValue(REG_REMEMBER);
                }
            }
            catch { }
        }

        #endregion Events

        private void LaunchViewer(string path, string arguments)
        {
            // Parse the starting location
            string startLocation = cboStartLocation.Text;
            string location = "last";
            string region = String.Empty;

            if (startLocation == START_HOME)
            {
                location = "home";
            }
            else if (startLocation != START_LAST && startLocation != START_TYPE)
            {
                location = "specify";
                region = System.Web.HttpUtility.UrlEncode(startLocation).Replace("+", "%20");
            }

            string launchArgs = String.Empty;

            if (!String.IsNullOrEmpty(arguments))
                launchArgs = arguments;

            if (m_launchDocument != null)
            {
                if (!String.IsNullOrEmpty(m_launchDocument.WelcomeUrl))
                    launchArgs += String.Format(" --loginpage \"{0}\"", m_launchDocument.WelcomeUrl);
                if (!String.IsNullOrEmpty(m_launchDocument.EconomyUrl))
                    launchArgs += String.Format(" --helperuri \"{0}\"", m_launchDocument.EconomyUrl);

                if (m_launchDocument.IsLoginUrlCapability)
                {
                    string autoLaunchUri = BuildAutoLaunchUri(m_launchDocument.FirstName, m_launchDocument.LastName, location, region);
                    launchArgs += String.Format(" -loginuri \"{0}\" -url \"{1}\"", m_launchDocument.LoginUrl, autoLaunchUri);
                }
                else
                {
                    string loginScreenUri = BuildLoginScreenUri(location, region);
                    launchArgs += String.Format(" -loginuri \"{0}\" -url \"{1}\"", m_launchDocument.LoginUrl, loginScreenUri);
                }
            }

            launchArgs = launchArgs.Trim();

            try
            {
                System.Diagnostics.Process.Start(path, launchArgs);
                Application.Exit();
            }
            catch (Exception ex)
            {
                MessageBox.Show("Failed to start the viewer at " + path + ": " + ex.Message);
            }
        }

        private static string BuildLoginScreenUri(string location, string region)
        {
            // A secondlife:/// URI is passed with location and optionally 
            // region information to fill in the starting location on the login
            // screen
            const string DUMMY_URI = "secondlife:///app/login?location=";

            if (!String.IsNullOrEmpty(region))
                return DUMMY_URI + location + "&region=" + region;
            else
                return DUMMY_URI + location;
        }

        private static string BuildAutoLaunchUri(string firstName, string lastName, string location, string region)
        {
            // A secondlife:/// URI is passed with a dummy web_login_key to get
            // the client to automatically start the login process. All of the 
            // real data is embedded on the server side in the temporary login 
            // capability URL
            const string DUMMY_URI = "secondlife:///app/login?first_name={0}&last_name={1}&location={2}&web_login_key=00000000-0000-0000-0000-000000000001";

            if (String.IsNullOrEmpty(firstName) || String.IsNullOrEmpty(lastName))
            {
                firstName = "Unknown";
                lastName = "User";
            }

            string autoLaunchUri = String.Format(DUMMY_URI, firstName, lastName, location);
            if (!String.IsNullOrEmpty(region))
                autoLaunchUri += "&region=" + region;
            return autoLaunchUri;
        }
    }

    /// <summary>
    /// Holds information about a viewer executable
    /// </summary>
    public class ViewerInfo : Panel, IEquatable<ViewerInfo>
    {
        /// <summary>Viewer executable path</summary>
        public string Path;
        /// <summary>Arguments to pass to the viewer</summary>
        public string Arguments;

        /// <summary>
        /// Constructor
        /// </summary>
        public ViewerInfo(string name, string path, string arguments)
        {
            base.Name = name;
            Path = path;
            Arguments = arguments;

            base.Text = this.ToString();
        }

        /// <summary>
        /// ToString override
        /// </summary>
        public override string ToString()
        {
            return Name + " (" + Path + " " + Arguments + ")";
        }

        /// <summary>
        /// GetHashCode override (based on Path)
        /// </summary>
        public override int GetHashCode()
        {
            return Path.GetHashCode();
        }

        /// <summary>
        /// Equals override (based on Path)
        /// </summary>
        public override bool Equals(object obj)
        {
            return Equals(obj as ViewerInfo);
        }

        /// <summary>
        /// Equals override (based on Path)
        /// </summary>
        public bool Equals(ViewerInfo viewer)
        {
            if (viewer == null)
                return false;

            return viewer.Path.Equals(this.Path, StringComparison.InvariantCultureIgnoreCase);
        }
    }
}
