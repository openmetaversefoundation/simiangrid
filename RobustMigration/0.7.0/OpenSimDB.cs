// 
//  ____  _     __  __      _        _ 
// |  _ \| |__ |  \/  | ___| |_ __ _| |
// | | | | '_ \| |\/| |/ _ \ __/ _` | |
// | |_| | |_) | |  | |  __/ || (_| | |
// |____/|_.__/|_|  |_|\___|\__\__,_|_|
//
// Auto-generated from opensim on 2010-06-15 12:25:05Z.
// Please visit http://code.google.com/p/dblinq2007/ for more information.
//
namespace RobustMigration.v070
{
	using System;
	using System.ComponentModel;
	using System.Data;
#if MONO_STRICT
	using System.Data.Linq;
#else   // MONO_STRICT
	using DbLinq.Data.Linq;
	using DbLinq.Vendor;
#endif  // MONO_STRICT
	using System.Data.Linq.Mapping;
	using System.Diagnostics;
	
	
	public partial class opensim : DataContext
	{
		
		#region Extensibility Method Declarations
		partial void OnCreated();
		#endregion
		
		
		public opensim(string connectionString) : 
				base(connectionString)
		{
			this.OnCreated();
		}
		
		public opensim(string connection, MappingSource mappingSource) : 
				base(connection, mappingSource)
		{
			this.OnCreated();
		}
		
		public opensim(IDbConnection connection, MappingSource mappingSource) : 
				base(connection, mappingSource)
		{
			this.OnCreated();
		}
		
		public Table<assets> assets
		{
			get
			{
				return this.GetTable<assets>();
			}
		}
		
		public Table<auth> auth
		{
			get
			{
				return this.GetTable<auth>();
			}
		}
		
		public Table<avatars> avatars
		{
			get
			{
				return this.GetTable<avatars>();
			}
		}
		
		public Table<estateban> estateban
		{
			get
			{
				return this.GetTable<estateban>();
			}
		}
		
		public Table<estategroups> estategroups
		{
			get
			{
				return this.GetTable<estategroups>();
			}
		}
		
		public Table<estatemanagers> estatemanagers
		{
			get
			{
				return this.GetTable<estatemanagers>();
			}
		}
		
		public Table<estatemap> estatemap
		{
			get
			{
				return this.GetTable<estatemap>();
			}
		}
		
		public Table<estatesettings> estatesettings
		{
			get
			{
				return this.GetTable<estatesettings>();
			}
		}
		
		public Table<estateusers> estateusers
		{
			get
			{
				return this.GetTable<estateusers>();
			}
		}
		
		public Table<friends> friends
		{
			get
			{
				return this.GetTable<friends>();
			}
		}
		
		public Table<griduser> griduser
		{
			get
			{
				return this.GetTable<griduser>();
			}
		}
		
		public Table<inventoryfolders> inventoryfolders
		{
			get
			{
				return this.GetTable<inventoryfolders>();
			}
		}
		
		public Table<inventoryitems> inventoryitems
		{
			get
			{
				return this.GetTable<inventoryitems>();
			}
		}
		
		public Table<land> land
		{
			get
			{
				return this.GetTable<land>();
			}
		}
		
		public Table<landaccesslist> landaccesslist
		{
			get
			{
				return this.GetTable<landaccesslist>();
			}
		}
		
		public Table<migrations> migrations
		{
			get
			{
				return this.GetTable<migrations>();
			}
		}
		
		public Table<presence> presence
		{
			get
			{
				return this.GetTable<presence>();
			}
		}
		
		public Table<primitems> primitems
		{
			get
			{
				return this.GetTable<primitems>();
			}
		}
		
		public Table<prims> prims
		{
			get
			{
				return this.GetTable<prims>();
			}
		}
		
		public Table<primshapes> primshapes
		{
			get
			{
				return this.GetTable<primshapes>();
			}
		}
		
		public Table<regionban> regionban
		{
			get
			{
				return this.GetTable<regionban>();
			}
		}
		
		public Table<regions> regions
		{
			get
			{
				return this.GetTable<regions>();
			}
		}
		
		public Table<regionsettings> regionsettings
		{
			get
			{
				return this.GetTable<regionsettings>();
			}
		}
		
		public Table<terrain> terrain
		{
			get
			{
				return this.GetTable<terrain>();
			}
		}
		
		public Table<tokens> tokens
		{
			get
			{
				return this.GetTable<tokens>();
			}
		}
		
		public Table<useraccounts> useraccounts
		{
			get
			{
				return this.GetTable<useraccounts>();
			}
		}
	}
	
	#region Start MONO_STRICT
#if MONO_STRICT

	public partial class opensim
	{
		
		public opensim(IDbConnection connection) : 
				base(connection)
		{
			this.OnCreated();
		}
	}
	#region End MONO_STRICT
	#endregion
#else     // MONO_STRICT
	
	public partial class opensim
	{
		
		public opensim(IDbConnection connection) : 
				base(connection, new DbLinq.MySql.MySqlVendor())
		{
			this.OnCreated();
		}
		
		public opensim(IDbConnection connection, IVendor sqlDialect) : 
				base(connection, sqlDialect)
		{
			this.OnCreated();
		}
		
		public opensim(IDbConnection connection, MappingSource mappingSource, IVendor sqlDialect) : 
				base(connection, mappingSource, sqlDialect)
		{
			this.OnCreated();
		}
	}
	#region End Not MONO_STRICT
	#endregion
#endif     // MONO_STRICT
	#endregion
	
	[Table(Name="assets")]
	public partial class assets : System.ComponentModel.INotifyPropertyChanging, System.ComponentModel.INotifyPropertyChanged
	{
		
		private static System.ComponentModel.PropertyChangingEventArgs emptyChangingEventArgs = new System.ComponentModel.PropertyChangingEventArgs("");
		
		private System.Nullable<int> _accesstime;
		
		private int _assetflags;
		
		private sbyte _assetType;
		
		private System.Nullable<int> _createtime;
		
		private string _creatorID;
		
		private byte[] _data;
		
		private string _description;
		
		private string _id;
		
		private sbyte _local;
		
		private string _name;
		
		private sbyte _temporary;
		
		#region Extensibility Method Declarations
		partial void OnCreated();
		
		partial void OnaccesstimeChanged();
		
		partial void OnaccesstimeChanging(System.Nullable<int> value);
		
		partial void OnassetflagsChanged();
		
		partial void OnassetflagsChanging(int value);
		
		partial void OnassetTypeChanged();
		
		partial void OnassetTypeChanging(sbyte value);
		
		partial void OncreatetimeChanged();
		
		partial void OncreatetimeChanging(System.Nullable<int> value);
		
		partial void OnCreatorIDChanged();
		
		partial void OnCreatorIDChanging(string value);
		
		partial void OndataChanged();
		
		partial void OndataChanging(byte[] value);
		
		partial void OndescriptionChanged();
		
		partial void OndescriptionChanging(string value);
		
		partial void OnidChanged();
		
		partial void OnidChanging(string value);
		
		partial void OnlocalChanged();
		
		partial void OnlocalChanging(sbyte value);
		
		partial void OnnameChanged();
		
		partial void OnnameChanging(string value);
		
		partial void OntemporaryChanged();
		
		partial void OntemporaryChanging(sbyte value);
		#endregion
		
		
		public assets()
		{
			this.OnCreated();
		}
		
		[Column(Storage="_accesstime", Name="access_time", DbType="int", AutoSync=AutoSync.Never)]
		[DebuggerNonUserCode()]
		public System.Nullable<int> accesstime
		{
			get
			{
				return this._accesstime;
			}
			set
			{
				if ((_accesstime != value))
				{
					this.OnaccesstimeChanging(value);
					this.SendPropertyChanging();
					this._accesstime = value;
					this.SendPropertyChanged("accesstime");
					this.OnaccesstimeChanged();
				}
			}
		}
		
		[Column(Storage="_assetflags", Name="asset_flags", DbType="int", AutoSync=AutoSync.Never, CanBeNull=false)]
		[DebuggerNonUserCode()]
		public int assetflags
		{
			get
			{
				return this._assetflags;
			}
			set
			{
				if ((_assetflags != value))
				{
					this.OnassetflagsChanging(value);
					this.SendPropertyChanging();
					this._assetflags = value;
					this.SendPropertyChanged("assetflags");
					this.OnassetflagsChanged();
				}
			}
		}
		
		[Column(Storage="_assetType", Name="assetType", DbType="tinyint(4)", AutoSync=AutoSync.Never, CanBeNull=false)]
		[DebuggerNonUserCode()]
		public sbyte assetType
		{
			get
			{
				return this._assetType;
			}
			set
			{
				if ((_assetType != value))
				{
					this.OnassetTypeChanging(value);
					this.SendPropertyChanging();
					this._assetType = value;
					this.SendPropertyChanged("assetType");
					this.OnassetTypeChanged();
				}
			}
		}
		
		[Column(Storage="_createtime", Name="create_time", DbType="int", AutoSync=AutoSync.Never)]
		[DebuggerNonUserCode()]
		public System.Nullable<int> createtime
		{
			get
			{
				return this._createtime;
			}
			set
			{
				if ((_createtime != value))
				{
					this.OncreatetimeChanging(value);
					this.SendPropertyChanging();
					this._createtime = value;
					this.SendPropertyChanged("createtime");
					this.OncreatetimeChanged();
				}
			}
		}
		
		[Column(Storage="_creatorID", Name="CreatorID", DbType="varchar(128)", AutoSync=AutoSync.Never, CanBeNull=false)]
		[DebuggerNonUserCode()]
		public string CreatorID
		{
			get
			{
				return this._creatorID;
			}
			set
			{
				if (((_creatorID == value) 
							== false))
				{
					this.OnCreatorIDChanging(value);
					this.SendPropertyChanging();
					this._creatorID = value;
					this.SendPropertyChanged("CreatorID");
					this.OnCreatorIDChanged();
				}
			}
		}
		
		[Column(Storage="_data", Name="data", DbType="longblob", AutoSync=AutoSync.Never, CanBeNull=false)]
		[DebuggerNonUserCode()]
		public byte[] data
		{
			get
			{
				return this._data;
			}
			set
			{
				if (((_data == value) 
							== false))
				{
					this.OndataChanging(value);
					this.SendPropertyChanging();
					this._data = value;
					this.SendPropertyChanged("data");
					this.OndataChanged();
				}
			}
		}
		
		[Column(Storage="_description", Name="description", DbType="varchar(64)", AutoSync=AutoSync.Never, CanBeNull=false)]
		[DebuggerNonUserCode()]
		public string description
		{
			get
			{
				return this._description;
			}
			set
			{
				if (((_description == value) 
							== false))
				{
					this.OndescriptionChanging(value);
					this.SendPropertyChanging();
					this._description = value;
					this.SendPropertyChanged("description");
					this.OndescriptionChanged();
				}
			}
		}
		
		[Column(Storage="_id", Name="id", DbType="char(36)", IsPrimaryKey=true, AutoSync=AutoSync.Never, CanBeNull=false)]
		[DebuggerNonUserCode()]
		public string id
		{
			get
			{
				return this._id;
			}
			set
			{
				if (((_id == value) 
							== false))
				{
					this.OnidChanging(value);
					this.SendPropertyChanging();
					this._id = value;
					this.SendPropertyChanged("id");
					this.OnidChanged();
				}
			}
		}
		
		[Column(Storage="_local", Name="local", DbType="tinyint(1)", AutoSync=AutoSync.Never, CanBeNull=false)]
		[DebuggerNonUserCode()]
		public sbyte local
		{
			get
			{
				return this._local;
			}
			set
			{
				if ((_local != value))
				{
					this.OnlocalChanging(value);
					this.SendPropertyChanging();
					this._local = value;
					this.SendPropertyChanged("local");
					this.OnlocalChanged();
				}
			}
		}
		
		[Column(Storage="_name", Name="name", DbType="varchar(64)", AutoSync=AutoSync.Never, CanBeNull=false)]
		[DebuggerNonUserCode()]
		public string name
		{
			get
			{
				return this._name;
			}
			set
			{
				if (((_name == value) 
							== false))
				{
					this.OnnameChanging(value);
					this.SendPropertyChanging();
					this._name = value;
					this.SendPropertyChanged("name");
					this.OnnameChanged();
				}
			}
		}
		
		[Column(Storage="_temporary", Name="temporary", DbType="tinyint(1)", AutoSync=AutoSync.Never, CanBeNull=false)]
		[DebuggerNonUserCode()]
		public sbyte temporary
		{
			get
			{
				return this._temporary;
			}
			set
			{
				if ((_temporary != value))
				{
					this.OntemporaryChanging(value);
					this.SendPropertyChanging();
					this._temporary = value;
					this.SendPropertyChanged("temporary");
					this.OntemporaryChanged();
				}
			}
		}
		
		public event System.ComponentModel.PropertyChangingEventHandler PropertyChanging;
		
		public event System.ComponentModel.PropertyChangedEventHandler PropertyChanged;
		
		protected virtual void SendPropertyChanging()
		{
			System.ComponentModel.PropertyChangingEventHandler h = this.PropertyChanging;
			if ((h != null))
			{
				h(this, emptyChangingEventArgs);
			}
		}
		
		protected virtual void SendPropertyChanged(string propertyName)
		{
			System.ComponentModel.PropertyChangedEventHandler h = this.PropertyChanged;
			if ((h != null))
			{
				h(this, new System.ComponentModel.PropertyChangedEventArgs(propertyName));
			}
		}
	}
	
	[Table(Name="auth")]
	public partial class auth : System.ComponentModel.INotifyPropertyChanging, System.ComponentModel.INotifyPropertyChanged
	{
		
		private static System.ComponentModel.PropertyChangingEventArgs emptyChangingEventArgs = new System.ComponentModel.PropertyChangingEventArgs("");
		
		private string _accountType;
		
		private string _passwordHash;
		
		private string _passwordSalt;
		
		private string _uuid;
		
		private string _webLoginKey;
		
		#region Extensibility Method Declarations
		partial void OnCreated();
		
		partial void OnaccountTypeChanged();
		
		partial void OnaccountTypeChanging(string value);
		
		partial void OnpasswordHashChanged();
		
		partial void OnpasswordHashChanging(string value);
		
		partial void OnpasswordSaltChanged();
		
		partial void OnpasswordSaltChanging(string value);
		
		partial void OnUUIDChanged();
		
		partial void OnUUIDChanging(string value);
		
		partial void OnwebLoginKeyChanged();
		
		partial void OnwebLoginKeyChanging(string value);
		#endregion
		
		
		public auth()
		{
			this.OnCreated();
		}
		
		[Column(Storage="_accountType", Name="accountType", DbType="varchar(32)", AutoSync=AutoSync.Never, CanBeNull=false)]
		[DebuggerNonUserCode()]
		public string accountType
		{
			get
			{
				return this._accountType;
			}
			set
			{
				if (((_accountType == value) 
							== false))
				{
					this.OnaccountTypeChanging(value);
					this.SendPropertyChanging();
					this._accountType = value;
					this.SendPropertyChanged("accountType");
					this.OnaccountTypeChanged();
				}
			}
		}
		
		[Column(Storage="_passwordHash", Name="passwordHash", DbType="char(32)", AutoSync=AutoSync.Never, CanBeNull=false)]
		[DebuggerNonUserCode()]
		public string passwordHash
		{
			get
			{
				return this._passwordHash;
			}
			set
			{
				if (((_passwordHash == value) 
							== false))
				{
					this.OnpasswordHashChanging(value);
					this.SendPropertyChanging();
					this._passwordHash = value;
					this.SendPropertyChanged("passwordHash");
					this.OnpasswordHashChanged();
				}
			}
		}
		
		[Column(Storage="_passwordSalt", Name="passwordSalt", DbType="char(32)", AutoSync=AutoSync.Never, CanBeNull=false)]
		[DebuggerNonUserCode()]
		public string passwordSalt
		{
			get
			{
				return this._passwordSalt;
			}
			set
			{
				if (((_passwordSalt == value) 
							== false))
				{
					this.OnpasswordSaltChanging(value);
					this.SendPropertyChanging();
					this._passwordSalt = value;
					this.SendPropertyChanged("passwordSalt");
					this.OnpasswordSaltChanged();
				}
			}
		}
		
		[Column(Storage="_uuid", Name="UUID", DbType="char(36)", IsPrimaryKey=true, AutoSync=AutoSync.Never, CanBeNull=false)]
		[DebuggerNonUserCode()]
		public string UUID
		{
			get
			{
				return this._uuid;
			}
			set
			{
				if (((_uuid == value) 
							== false))
				{
					this.OnUUIDChanging(value);
					this.SendPropertyChanging();
					this._uuid = value;
					this.SendPropertyChanged("UUID");
					this.OnUUIDChanged();
				}
			}
		}
		
		[Column(Storage="_webLoginKey", Name="webLoginKey", DbType="varchar(255)", AutoSync=AutoSync.Never, CanBeNull=false)]
		[DebuggerNonUserCode()]
		public string webLoginKey
		{
			get
			{
				return this._webLoginKey;
			}
			set
			{
				if (((_webLoginKey == value) 
							== false))
				{
					this.OnwebLoginKeyChanging(value);
					this.SendPropertyChanging();
					this._webLoginKey = value;
					this.SendPropertyChanged("webLoginKey");
					this.OnwebLoginKeyChanged();
				}
			}
		}
		
		public event System.ComponentModel.PropertyChangingEventHandler PropertyChanging;
		
		public event System.ComponentModel.PropertyChangedEventHandler PropertyChanged;
		
		protected virtual void SendPropertyChanging()
		{
			System.ComponentModel.PropertyChangingEventHandler h = this.PropertyChanging;
			if ((h != null))
			{
				h(this, emptyChangingEventArgs);
			}
		}
		
		protected virtual void SendPropertyChanged(string propertyName)
		{
			System.ComponentModel.PropertyChangedEventHandler h = this.PropertyChanged;
			if ((h != null))
			{
				h(this, new System.ComponentModel.PropertyChangedEventArgs(propertyName));
			}
		}
	}
	
	[Table(Name="Avatars")]
	public partial class avatars : System.ComponentModel.INotifyPropertyChanging, System.ComponentModel.INotifyPropertyChanged
	{
		
		private static System.ComponentModel.PropertyChangingEventArgs emptyChangingEventArgs = new System.ComponentModel.PropertyChangingEventArgs("");
		
		private string _name;
		
		private string _principalID;
		
		private string _value;
		
		#region Extensibility Method Declarations
		partial void OnCreated();
		
		partial void OnNameChanged();
		
		partial void OnNameChanging(string value);
		
		partial void OnPrincipalIDChanged();
		
		partial void OnPrincipalIDChanging(string value);
		
		partial void OnValueChanged();
		
		partial void OnValueChanging(string value);
		#endregion
		
		
		public avatars()
		{
			this.OnCreated();
		}
		
		[Column(Storage="_name", Name="Name", DbType="varchar(32)", IsPrimaryKey=true, AutoSync=AutoSync.Never, CanBeNull=false)]
		[DebuggerNonUserCode()]
		public string Name
		{
			get
			{
				return this._name;
			}
			set
			{
				if (((_name == value) 
							== false))
				{
					this.OnNameChanging(value);
					this.SendPropertyChanging();
					this._name = value;
					this.SendPropertyChanged("Name");
					this.OnNameChanged();
				}
			}
		}
		
		[Column(Storage="_principalID", Name="PrincipalID", DbType="char(36)", IsPrimaryKey=true, AutoSync=AutoSync.Never, CanBeNull=false)]
		[DebuggerNonUserCode()]
		public string PrincipalID
		{
			get
			{
				return this._principalID;
			}
			set
			{
				if (((_principalID == value) 
							== false))
				{
					this.OnPrincipalIDChanging(value);
					this.SendPropertyChanging();
					this._principalID = value;
					this.SendPropertyChanged("PrincipalID");
					this.OnPrincipalIDChanged();
				}
			}
		}
		
		[Column(Storage="_value", Name="Value", DbType="varchar(255)", AutoSync=AutoSync.Never, CanBeNull=false)]
		[DebuggerNonUserCode()]
		public string Value
		{
			get
			{
				return this._value;
			}
			set
			{
				if (((_value == value) 
							== false))
				{
					this.OnValueChanging(value);
					this.SendPropertyChanging();
					this._value = value;
					this.SendPropertyChanged("Value");
					this.OnValueChanged();
				}
			}
		}
		
		public event System.ComponentModel.PropertyChangingEventHandler PropertyChanging;
		
		public event System.ComponentModel.PropertyChangedEventHandler PropertyChanged;
		
		protected virtual void SendPropertyChanging()
		{
			System.ComponentModel.PropertyChangingEventHandler h = this.PropertyChanging;
			if ((h != null))
			{
				h(this, emptyChangingEventArgs);
			}
		}
		
		protected virtual void SendPropertyChanged(string propertyName)
		{
			System.ComponentModel.PropertyChangedEventHandler h = this.PropertyChanged;
			if ((h != null))
			{
				h(this, new System.ComponentModel.PropertyChangedEventArgs(propertyName));
			}
		}
	}
	
	[Table(Name="estateban")]
	public partial class estateban
	{
		
		private string _bannedIp;
		
		private string _bannedIpHostMask;
		
		private string _bannedNameMask;
		
		private string _bannedUuid;
		
		private uint _estateID;
		
		#region Extensibility Method Declarations
		partial void OnCreated();
		
		partial void OnbannedIpChanged();
		
		partial void OnbannedIpChanging(string value);
		
		partial void OnbannedIpHostMaskChanged();
		
		partial void OnbannedIpHostMaskChanging(string value);
		
		partial void OnbannedNameMaskChanged();
		
		partial void OnbannedNameMaskChanging(string value);
		
		partial void OnbannedUUIDChanged();
		
		partial void OnbannedUUIDChanging(string value);
		
		partial void OnEstateIDChanged();
		
		partial void OnEstateIDChanging(uint value);
		#endregion
		
		
		public estateban()
		{
			this.OnCreated();
		}
		
		[Column(Storage="_bannedIp", Name="bannedIp", DbType="varchar(16)", AutoSync=AutoSync.Never, CanBeNull=false)]
		[DebuggerNonUserCode()]
		public string bannedIp
		{
			get
			{
				return this._bannedIp;
			}
			set
			{
				if (((_bannedIp == value) 
							== false))
				{
					this.OnbannedIpChanging(value);
					this._bannedIp = value;
					this.OnbannedIpChanged();
				}
			}
		}
		
		[Column(Storage="_bannedIpHostMask", Name="bannedIpHostMask", DbType="varchar(16)", AutoSync=AutoSync.Never, CanBeNull=false)]
		[DebuggerNonUserCode()]
		public string bannedIpHostMask
		{
			get
			{
				return this._bannedIpHostMask;
			}
			set
			{
				if (((_bannedIpHostMask == value) 
							== false))
				{
					this.OnbannedIpHostMaskChanging(value);
					this._bannedIpHostMask = value;
					this.OnbannedIpHostMaskChanged();
				}
			}
		}
		
		[Column(Storage="_bannedNameMask", Name="bannedNameMask", DbType="varchar(64)", AutoSync=AutoSync.Never)]
		[DebuggerNonUserCode()]
		public string bannedNameMask
		{
			get
			{
				return this._bannedNameMask;
			}
			set
			{
				if (((_bannedNameMask == value) 
							== false))
				{
					this.OnbannedNameMaskChanging(value);
					this._bannedNameMask = value;
					this.OnbannedNameMaskChanged();
				}
			}
		}
		
		[Column(Storage="_bannedUuid", Name="bannedUUID", DbType="varchar(36)", AutoSync=AutoSync.Never, CanBeNull=false)]
		[DebuggerNonUserCode()]
		public string bannedUUID
		{
			get
			{
				return this._bannedUuid;
			}
			set
			{
				if (((_bannedUuid == value) 
							== false))
				{
					this.OnbannedUUIDChanging(value);
					this._bannedUuid = value;
					this.OnbannedUUIDChanged();
				}
			}
		}
		
		[Column(Storage="_estateID", Name="EstateID", DbType="int unsigned", AutoSync=AutoSync.Never, CanBeNull=false)]
		[DebuggerNonUserCode()]
		public uint EstateID
		{
			get
			{
				return this._estateID;
			}
			set
			{
				if ((_estateID != value))
				{
					this.OnEstateIDChanging(value);
					this._estateID = value;
					this.OnEstateIDChanged();
				}
			}
		}
	}
	
	[Table(Name="estate_groups")]
	public partial class estategroups
	{
		
		private uint _estateID;
		
		private string _uuid;
		
		#region Extensibility Method Declarations
		partial void OnCreated();
		
		partial void OnEstateIDChanged();
		
		partial void OnEstateIDChanging(uint value);
		
		partial void OnuuidChanged();
		
		partial void OnuuidChanging(string value);
		#endregion
		
		
		public estategroups()
		{
			this.OnCreated();
		}
		
		[Column(Storage="_estateID", Name="EstateID", DbType="int unsigned", AutoSync=AutoSync.Never, CanBeNull=false)]
		[DebuggerNonUserCode()]
		public uint EstateID
		{
			get
			{
				return this._estateID;
			}
			set
			{
				if ((_estateID != value))
				{
					this.OnEstateIDChanging(value);
					this._estateID = value;
					this.OnEstateIDChanged();
				}
			}
		}
		
		[Column(Storage="_uuid", Name="uuid", DbType="char(36)", AutoSync=AutoSync.Never, CanBeNull=false)]
		[DebuggerNonUserCode()]
		public string uuid
		{
			get
			{
				return this._uuid;
			}
			set
			{
				if (((_uuid == value) 
							== false))
				{
					this.OnuuidChanging(value);
					this._uuid = value;
					this.OnuuidChanged();
				}
			}
		}
	}
	
	[Table(Name="estate_managers")]
	public partial class estatemanagers
	{
		
		private uint _estateID;
		
		private string _uuid;
		
		#region Extensibility Method Declarations
		partial void OnCreated();
		
		partial void OnEstateIDChanged();
		
		partial void OnEstateIDChanging(uint value);
		
		partial void OnuuidChanged();
		
		partial void OnuuidChanging(string value);
		#endregion
		
		
		public estatemanagers()
		{
			this.OnCreated();
		}
		
		[Column(Storage="_estateID", Name="EstateID", DbType="int unsigned", AutoSync=AutoSync.Never, CanBeNull=false)]
		[DebuggerNonUserCode()]
		public uint EstateID
		{
			get
			{
				return this._estateID;
			}
			set
			{
				if ((_estateID != value))
				{
					this.OnEstateIDChanging(value);
					this._estateID = value;
					this.OnEstateIDChanged();
				}
			}
		}
		
		[Column(Storage="_uuid", Name="uuid", DbType="char(36)", AutoSync=AutoSync.Never, CanBeNull=false)]
		[DebuggerNonUserCode()]
		public string uuid
		{
			get
			{
				return this._uuid;
			}
			set
			{
				if (((_uuid == value) 
							== false))
				{
					this.OnuuidChanging(value);
					this._uuid = value;
					this.OnuuidChanged();
				}
			}
		}
	}
	
	[Table(Name="estate_map")]
	public partial class estatemap : System.ComponentModel.INotifyPropertyChanging, System.ComponentModel.INotifyPropertyChanged
	{
		
		private static System.ComponentModel.PropertyChangingEventArgs emptyChangingEventArgs = new System.ComponentModel.PropertyChangingEventArgs("");
		
		private int _estateID;
		
		private string _regionID;
		
		#region Extensibility Method Declarations
		partial void OnCreated();
		
		partial void OnEstateIDChanged();
		
		partial void OnEstateIDChanging(int value);
		
		partial void OnRegionIDChanged();
		
		partial void OnRegionIDChanging(string value);
		#endregion
		
		
		public estatemap()
		{
			this.OnCreated();
		}
		
		[Column(Storage="_estateID", Name="EstateID", DbType="int", AutoSync=AutoSync.Never, CanBeNull=false)]
		[DebuggerNonUserCode()]
		public int EstateID
		{
			get
			{
				return this._estateID;
			}
			set
			{
				if ((_estateID != value))
				{
					this.OnEstateIDChanging(value);
					this.SendPropertyChanging();
					this._estateID = value;
					this.SendPropertyChanged("EstateID");
					this.OnEstateIDChanged();
				}
			}
		}
		
		[Column(Storage="_regionID", Name="RegionID", DbType="char(36)", IsPrimaryKey=true, AutoSync=AutoSync.Never, CanBeNull=false)]
		[DebuggerNonUserCode()]
		public string RegionID
		{
			get
			{
				return this._regionID;
			}
			set
			{
				if (((_regionID == value) 
							== false))
				{
					this.OnRegionIDChanging(value);
					this.SendPropertyChanging();
					this._regionID = value;
					this.SendPropertyChanged("RegionID");
					this.OnRegionIDChanged();
				}
			}
		}
		
		public event System.ComponentModel.PropertyChangingEventHandler PropertyChanging;
		
		public event System.ComponentModel.PropertyChangedEventHandler PropertyChanged;
		
		protected virtual void SendPropertyChanging()
		{
			System.ComponentModel.PropertyChangingEventHandler h = this.PropertyChanging;
			if ((h != null))
			{
				h(this, emptyChangingEventArgs);
			}
		}
		
		protected virtual void SendPropertyChanged(string propertyName)
		{
			System.ComponentModel.PropertyChangedEventHandler h = this.PropertyChanged;
			if ((h != null))
			{
				h(this, new System.ComponentModel.PropertyChangedEventArgs(propertyName));
			}
		}
	}
	
	[Table(Name="estate_settings")]
	public partial class estatesettings : System.ComponentModel.INotifyPropertyChanging, System.ComponentModel.INotifyPropertyChanged
	{
		
		private static System.ComponentModel.PropertyChangingEventArgs emptyChangingEventArgs = new System.ComponentModel.PropertyChangingEventArgs("");
		
		private string _abuseEmail;
		
		private sbyte _abuseEmailToEstateOwner;
		
		private sbyte _allowDirectTeleport;
		
		private sbyte _allowVoice;
		
		private float _billableFactor;
		
		private sbyte _blockDwell;
		
		private sbyte _denyAnonymous;
		
		private sbyte _denyIdentified;
		
		private sbyte _denyMinors;
		
		private sbyte _denyTransacted;
		
		private uint _estateID;
		
		private string _estateName;
		
		private string _estateOwner;
		
		private sbyte _estateSkipScripts;
		
		private sbyte _fixedSun;
		
		private uint _parentEstateID;
		
		private int _pricePerMeter;
		
		private sbyte _publicAccess;
		
		private int _redirectGridX;
		
		private int _redirectGridY;
		
		private sbyte _resetHomeOnTeleport;
		
		private double _sunPosition;
		
		private sbyte _taxFree;
		
		private sbyte _useGlobalTime;
		
		#region Extensibility Method Declarations
		partial void OnCreated();
		
		partial void OnAbuseEmailChanged();
		
		partial void OnAbuseEmailChanging(string value);
		
		partial void OnAbuseEmailToEstateOwnerChanged();
		
		partial void OnAbuseEmailToEstateOwnerChanging(sbyte value);
		
		partial void OnAllowDirectTeleportChanged();
		
		partial void OnAllowDirectTeleportChanging(sbyte value);
		
		partial void OnAllowVoiceChanged();
		
		partial void OnAllowVoiceChanging(sbyte value);
		
		partial void OnBillableFactorChanged();
		
		partial void OnBillableFactorChanging(float value);
		
		partial void OnBlockDwellChanged();
		
		partial void OnBlockDwellChanging(sbyte value);
		
		partial void OnDenyAnonymousChanged();
		
		partial void OnDenyAnonymousChanging(sbyte value);
		
		partial void OnDenyIdentifiedChanged();
		
		partial void OnDenyIdentifiedChanging(sbyte value);
		
		partial void OnDenyMinorsChanged();
		
		partial void OnDenyMinorsChanging(sbyte value);
		
		partial void OnDenyTransactedChanged();
		
		partial void OnDenyTransactedChanging(sbyte value);
		
		partial void OnEstateIDChanged();
		
		partial void OnEstateIDChanging(uint value);
		
		partial void OnEstateNameChanged();
		
		partial void OnEstateNameChanging(string value);
		
		partial void OnEstateOwnerChanged();
		
		partial void OnEstateOwnerChanging(string value);
		
		partial void OnEstateSkipScriptsChanged();
		
		partial void OnEstateSkipScriptsChanging(sbyte value);
		
		partial void OnFixedSunChanged();
		
		partial void OnFixedSunChanging(sbyte value);
		
		partial void OnParentEstateIDChanged();
		
		partial void OnParentEstateIDChanging(uint value);
		
		partial void OnPricePerMeterChanged();
		
		partial void OnPricePerMeterChanging(int value);
		
		partial void OnPublicAccessChanged();
		
		partial void OnPublicAccessChanging(sbyte value);
		
		partial void OnRedirectGridXChanged();
		
		partial void OnRedirectGridXChanging(int value);
		
		partial void OnRedirectGridYChanged();
		
		partial void OnRedirectGridYChanging(int value);
		
		partial void OnResetHomeOnTeleportChanged();
		
		partial void OnResetHomeOnTeleportChanging(sbyte value);
		
		partial void OnSunPositionChanged();
		
		partial void OnSunPositionChanging(double value);
		
		partial void OnTaxFreeChanged();
		
		partial void OnTaxFreeChanging(sbyte value);
		
		partial void OnUseGlobalTimeChanged();
		
		partial void OnUseGlobalTimeChanging(sbyte value);
		#endregion
		
		
		public estatesettings()
		{
			this.OnCreated();
		}
		
		[Column(Storage="_abuseEmail", Name="AbuseEmail", DbType="varchar(255)", AutoSync=AutoSync.Never, CanBeNull=false)]
		[DebuggerNonUserCode()]
		public string AbuseEmail
		{
			get
			{
				return this._abuseEmail;
			}
			set
			{
				if (((_abuseEmail == value) 
							== false))
				{
					this.OnAbuseEmailChanging(value);
					this.SendPropertyChanging();
					this._abuseEmail = value;
					this.SendPropertyChanged("AbuseEmail");
					this.OnAbuseEmailChanged();
				}
			}
		}
		
		[Column(Storage="_abuseEmailToEstateOwner", Name="AbuseEmailToEstateOwner", DbType="tinyint(4)", AutoSync=AutoSync.Never, CanBeNull=false)]
		[DebuggerNonUserCode()]
		public sbyte AbuseEmailToEstateOwner
		{
			get
			{
				return this._abuseEmailToEstateOwner;
			}
			set
			{
				if ((_abuseEmailToEstateOwner != value))
				{
					this.OnAbuseEmailToEstateOwnerChanging(value);
					this.SendPropertyChanging();
					this._abuseEmailToEstateOwner = value;
					this.SendPropertyChanged("AbuseEmailToEstateOwner");
					this.OnAbuseEmailToEstateOwnerChanged();
				}
			}
		}
		
		[Column(Storage="_allowDirectTeleport", Name="AllowDirectTeleport", DbType="tinyint(4)", AutoSync=AutoSync.Never, CanBeNull=false)]
		[DebuggerNonUserCode()]
		public sbyte AllowDirectTeleport
		{
			get
			{
				return this._allowDirectTeleport;
			}
			set
			{
				if ((_allowDirectTeleport != value))
				{
					this.OnAllowDirectTeleportChanging(value);
					this.SendPropertyChanging();
					this._allowDirectTeleport = value;
					this.SendPropertyChanged("AllowDirectTeleport");
					this.OnAllowDirectTeleportChanged();
				}
			}
		}
		
		[Column(Storage="_allowVoice", Name="AllowVoice", DbType="tinyint(4)", AutoSync=AutoSync.Never, CanBeNull=false)]
		[DebuggerNonUserCode()]
		public sbyte AllowVoice
		{
			get
			{
				return this._allowVoice;
			}
			set
			{
				if ((_allowVoice != value))
				{
					this.OnAllowVoiceChanging(value);
					this.SendPropertyChanging();
					this._allowVoice = value;
					this.SendPropertyChanged("AllowVoice");
					this.OnAllowVoiceChanged();
				}
			}
		}
		
		[Column(Storage="_billableFactor", Name="BillableFactor", DbType="float", AutoSync=AutoSync.Never, CanBeNull=false)]
		[DebuggerNonUserCode()]
		public float BillableFactor
		{
			get
			{
				return this._billableFactor;
			}
			set
			{
				if ((_billableFactor != value))
				{
					this.OnBillableFactorChanging(value);
					this.SendPropertyChanging();
					this._billableFactor = value;
					this.SendPropertyChanged("BillableFactor");
					this.OnBillableFactorChanged();
				}
			}
		}
		
		[Column(Storage="_blockDwell", Name="BlockDwell", DbType="tinyint(4)", AutoSync=AutoSync.Never, CanBeNull=false)]
		[DebuggerNonUserCode()]
		public sbyte BlockDwell
		{
			get
			{
				return this._blockDwell;
			}
			set
			{
				if ((_blockDwell != value))
				{
					this.OnBlockDwellChanging(value);
					this.SendPropertyChanging();
					this._blockDwell = value;
					this.SendPropertyChanged("BlockDwell");
					this.OnBlockDwellChanged();
				}
			}
		}
		
		[Column(Storage="_denyAnonymous", Name="DenyAnonymous", DbType="tinyint(4)", AutoSync=AutoSync.Never, CanBeNull=false)]
		[DebuggerNonUserCode()]
		public sbyte DenyAnonymous
		{
			get
			{
				return this._denyAnonymous;
			}
			set
			{
				if ((_denyAnonymous != value))
				{
					this.OnDenyAnonymousChanging(value);
					this.SendPropertyChanging();
					this._denyAnonymous = value;
					this.SendPropertyChanged("DenyAnonymous");
					this.OnDenyAnonymousChanged();
				}
			}
		}
		
		[Column(Storage="_denyIdentified", Name="DenyIdentified", DbType="tinyint(4)", AutoSync=AutoSync.Never, CanBeNull=false)]
		[DebuggerNonUserCode()]
		public sbyte DenyIdentified
		{
			get
			{
				return this._denyIdentified;
			}
			set
			{
				if ((_denyIdentified != value))
				{
					this.OnDenyIdentifiedChanging(value);
					this.SendPropertyChanging();
					this._denyIdentified = value;
					this.SendPropertyChanged("DenyIdentified");
					this.OnDenyIdentifiedChanged();
				}
			}
		}
		
		[Column(Storage="_denyMinors", Name="DenyMinors", DbType="tinyint(4)", AutoSync=AutoSync.Never, CanBeNull=false)]
		[DebuggerNonUserCode()]
		public sbyte DenyMinors
		{
			get
			{
				return this._denyMinors;
			}
			set
			{
				if ((_denyMinors != value))
				{
					this.OnDenyMinorsChanging(value);
					this.SendPropertyChanging();
					this._denyMinors = value;
					this.SendPropertyChanged("DenyMinors");
					this.OnDenyMinorsChanged();
				}
			}
		}
		
		[Column(Storage="_denyTransacted", Name="DenyTransacted", DbType="tinyint(4)", AutoSync=AutoSync.Never, CanBeNull=false)]
		[DebuggerNonUserCode()]
		public sbyte DenyTransacted
		{
			get
			{
				return this._denyTransacted;
			}
			set
			{
				if ((_denyTransacted != value))
				{
					this.OnDenyTransactedChanging(value);
					this.SendPropertyChanging();
					this._denyTransacted = value;
					this.SendPropertyChanged("DenyTransacted");
					this.OnDenyTransactedChanged();
				}
			}
		}
		
		[Column(Storage="_estateID", Name="EstateID", DbType="int unsigned", IsPrimaryKey=true, IsDbGenerated=true, AutoSync=AutoSync.Never, CanBeNull=false)]
		[DebuggerNonUserCode()]
		public uint EstateID
		{
			get
			{
				return this._estateID;
			}
			set
			{
				if ((_estateID != value))
				{
					this.OnEstateIDChanging(value);
					this.SendPropertyChanging();
					this._estateID = value;
					this.SendPropertyChanged("EstateID");
					this.OnEstateIDChanged();
				}
			}
		}
		
		[Column(Storage="_estateName", Name="EstateName", DbType="varchar(64)", AutoSync=AutoSync.Never)]
		[DebuggerNonUserCode()]
		public string EstateName
		{
			get
			{
				return this._estateName;
			}
			set
			{
				if (((_estateName == value) 
							== false))
				{
					this.OnEstateNameChanging(value);
					this.SendPropertyChanging();
					this._estateName = value;
					this.SendPropertyChanged("EstateName");
					this.OnEstateNameChanged();
				}
			}
		}
		
		[Column(Storage="_estateOwner", Name="EstateOwner", DbType="varchar(36)", AutoSync=AutoSync.Never, CanBeNull=false)]
		[DebuggerNonUserCode()]
		public string EstateOwner
		{
			get
			{
				return this._estateOwner;
			}
			set
			{
				if (((_estateOwner == value) 
							== false))
				{
					this.OnEstateOwnerChanging(value);
					this.SendPropertyChanging();
					this._estateOwner = value;
					this.SendPropertyChanged("EstateOwner");
					this.OnEstateOwnerChanged();
				}
			}
		}
		
		[Column(Storage="_estateSkipScripts", Name="EstateSkipScripts", DbType="tinyint(4)", AutoSync=AutoSync.Never, CanBeNull=false)]
		[DebuggerNonUserCode()]
		public sbyte EstateSkipScripts
		{
			get
			{
				return this._estateSkipScripts;
			}
			set
			{
				if ((_estateSkipScripts != value))
				{
					this.OnEstateSkipScriptsChanging(value);
					this.SendPropertyChanging();
					this._estateSkipScripts = value;
					this.SendPropertyChanged("EstateSkipScripts");
					this.OnEstateSkipScriptsChanged();
				}
			}
		}
		
		[Column(Storage="_fixedSun", Name="FixedSun", DbType="tinyint(4)", AutoSync=AutoSync.Never, CanBeNull=false)]
		[DebuggerNonUserCode()]
		public sbyte FixedSun
		{
			get
			{
				return this._fixedSun;
			}
			set
			{
				if ((_fixedSun != value))
				{
					this.OnFixedSunChanging(value);
					this.SendPropertyChanging();
					this._fixedSun = value;
					this.SendPropertyChanged("FixedSun");
					this.OnFixedSunChanged();
				}
			}
		}
		
		[Column(Storage="_parentEstateID", Name="ParentEstateID", DbType="int unsigned", AutoSync=AutoSync.Never, CanBeNull=false)]
		[DebuggerNonUserCode()]
		public uint ParentEstateID
		{
			get
			{
				return this._parentEstateID;
			}
			set
			{
				if ((_parentEstateID != value))
				{
					this.OnParentEstateIDChanging(value);
					this.SendPropertyChanging();
					this._parentEstateID = value;
					this.SendPropertyChanged("ParentEstateID");
					this.OnParentEstateIDChanged();
				}
			}
		}
		
		[Column(Storage="_pricePerMeter", Name="PricePerMeter", DbType="int", AutoSync=AutoSync.Never, CanBeNull=false)]
		[DebuggerNonUserCode()]
		public int PricePerMeter
		{
			get
			{
				return this._pricePerMeter;
			}
			set
			{
				if ((_pricePerMeter != value))
				{
					this.OnPricePerMeterChanging(value);
					this.SendPropertyChanging();
					this._pricePerMeter = value;
					this.SendPropertyChanged("PricePerMeter");
					this.OnPricePerMeterChanged();
				}
			}
		}
		
		[Column(Storage="_publicAccess", Name="PublicAccess", DbType="tinyint(4)", AutoSync=AutoSync.Never, CanBeNull=false)]
		[DebuggerNonUserCode()]
		public sbyte PublicAccess
		{
			get
			{
				return this._publicAccess;
			}
			set
			{
				if ((_publicAccess != value))
				{
					this.OnPublicAccessChanging(value);
					this.SendPropertyChanging();
					this._publicAccess = value;
					this.SendPropertyChanged("PublicAccess");
					this.OnPublicAccessChanged();
				}
			}
		}
		
		[Column(Storage="_redirectGridX", Name="RedirectGridX", DbType="int", AutoSync=AutoSync.Never, CanBeNull=false)]
		[DebuggerNonUserCode()]
		public int RedirectGridX
		{
			get
			{
				return this._redirectGridX;
			}
			set
			{
				if ((_redirectGridX != value))
				{
					this.OnRedirectGridXChanging(value);
					this.SendPropertyChanging();
					this._redirectGridX = value;
					this.SendPropertyChanged("RedirectGridX");
					this.OnRedirectGridXChanged();
				}
			}
		}
		
		[Column(Storage="_redirectGridY", Name="RedirectGridY", DbType="int", AutoSync=AutoSync.Never, CanBeNull=false)]
		[DebuggerNonUserCode()]
		public int RedirectGridY
		{
			get
			{
				return this._redirectGridY;
			}
			set
			{
				if ((_redirectGridY != value))
				{
					this.OnRedirectGridYChanging(value);
					this.SendPropertyChanging();
					this._redirectGridY = value;
					this.SendPropertyChanged("RedirectGridY");
					this.OnRedirectGridYChanged();
				}
			}
		}
		
		[Column(Storage="_resetHomeOnTeleport", Name="ResetHomeOnTeleport", DbType="tinyint(4)", AutoSync=AutoSync.Never, CanBeNull=false)]
		[DebuggerNonUserCode()]
		public sbyte ResetHomeOnTeleport
		{
			get
			{
				return this._resetHomeOnTeleport;
			}
			set
			{
				if ((_resetHomeOnTeleport != value))
				{
					this.OnResetHomeOnTeleportChanging(value);
					this.SendPropertyChanging();
					this._resetHomeOnTeleport = value;
					this.SendPropertyChanged("ResetHomeOnTeleport");
					this.OnResetHomeOnTeleportChanged();
				}
			}
		}
		
		[Column(Storage="_sunPosition", Name="SunPosition", DbType="double", AutoSync=AutoSync.Never, CanBeNull=false)]
		[DebuggerNonUserCode()]
		public double SunPosition
		{
			get
			{
				return this._sunPosition;
			}
			set
			{
				if ((_sunPosition != value))
				{
					this.OnSunPositionChanging(value);
					this.SendPropertyChanging();
					this._sunPosition = value;
					this.SendPropertyChanged("SunPosition");
					this.OnSunPositionChanged();
				}
			}
		}
		
		[Column(Storage="_taxFree", Name="TaxFree", DbType="tinyint(4)", AutoSync=AutoSync.Never, CanBeNull=false)]
		[DebuggerNonUserCode()]
		public sbyte TaxFree
		{
			get
			{
				return this._taxFree;
			}
			set
			{
				if ((_taxFree != value))
				{
					this.OnTaxFreeChanging(value);
					this.SendPropertyChanging();
					this._taxFree = value;
					this.SendPropertyChanged("TaxFree");
					this.OnTaxFreeChanged();
				}
			}
		}
		
		[Column(Storage="_useGlobalTime", Name="UseGlobalTime", DbType="tinyint(4)", AutoSync=AutoSync.Never, CanBeNull=false)]
		[DebuggerNonUserCode()]
		public sbyte UseGlobalTime
		{
			get
			{
				return this._useGlobalTime;
			}
			set
			{
				if ((_useGlobalTime != value))
				{
					this.OnUseGlobalTimeChanging(value);
					this.SendPropertyChanging();
					this._useGlobalTime = value;
					this.SendPropertyChanged("UseGlobalTime");
					this.OnUseGlobalTimeChanged();
				}
			}
		}
		
		public event System.ComponentModel.PropertyChangingEventHandler PropertyChanging;
		
		public event System.ComponentModel.PropertyChangedEventHandler PropertyChanged;
		
		protected virtual void SendPropertyChanging()
		{
			System.ComponentModel.PropertyChangingEventHandler h = this.PropertyChanging;
			if ((h != null))
			{
				h(this, emptyChangingEventArgs);
			}
		}
		
		protected virtual void SendPropertyChanged(string propertyName)
		{
			System.ComponentModel.PropertyChangedEventHandler h = this.PropertyChanged;
			if ((h != null))
			{
				h(this, new System.ComponentModel.PropertyChangedEventArgs(propertyName));
			}
		}
	}
	
	[Table(Name="estate_users")]
	public partial class estateusers
	{
		
		private uint _estateID;
		
		private string _uuid;
		
		#region Extensibility Method Declarations
		partial void OnCreated();
		
		partial void OnEstateIDChanged();
		
		partial void OnEstateIDChanging(uint value);
		
		partial void OnuuidChanged();
		
		partial void OnuuidChanging(string value);
		#endregion
		
		
		public estateusers()
		{
			this.OnCreated();
		}
		
		[Column(Storage="_estateID", Name="EstateID", DbType="int unsigned", AutoSync=AutoSync.Never, CanBeNull=false)]
		[DebuggerNonUserCode()]
		public uint EstateID
		{
			get
			{
				return this._estateID;
			}
			set
			{
				if ((_estateID != value))
				{
					this.OnEstateIDChanging(value);
					this._estateID = value;
					this.OnEstateIDChanged();
				}
			}
		}
		
		[Column(Storage="_uuid", Name="uuid", DbType="char(36)", AutoSync=AutoSync.Never, CanBeNull=false)]
		[DebuggerNonUserCode()]
		public string uuid
		{
			get
			{
				return this._uuid;
			}
			set
			{
				if (((_uuid == value) 
							== false))
				{
					this.OnuuidChanging(value);
					this._uuid = value;
					this.OnuuidChanged();
				}
			}
		}
	}
	
	[Table(Name="Friends")]
	public partial class friends : System.ComponentModel.INotifyPropertyChanging, System.ComponentModel.INotifyPropertyChanged
	{
		
		private static System.ComponentModel.PropertyChangingEventArgs emptyChangingEventArgs = new System.ComponentModel.PropertyChangingEventArgs("");
		
		private string _flags;
		
		private string _friend;
		
		private string _offered;
		
		private string _principalID;
		
		#region Extensibility Method Declarations
		partial void OnCreated();
		
		partial void OnFlagsChanged();
		
		partial void OnFlagsChanging(string value);
		
		partial void OnFriendChanged();
		
		partial void OnFriendChanging(string value);
		
		partial void OnOfferedChanged();
		
		partial void OnOfferedChanging(string value);
		
		partial void OnPrincipalIDChanged();
		
		partial void OnPrincipalIDChanging(string value);
		#endregion
		
		
		public friends()
		{
			this.OnCreated();
		}
		
		[Column(Storage="_flags", Name="Flags", DbType="varchar(16)", AutoSync=AutoSync.Never, CanBeNull=false)]
		[DebuggerNonUserCode()]
		public string Flags
		{
			get
			{
				return this._flags;
			}
			set
			{
				if (((_flags == value) 
							== false))
				{
					this.OnFlagsChanging(value);
					this.SendPropertyChanging();
					this._flags = value;
					this.SendPropertyChanged("Flags");
					this.OnFlagsChanged();
				}
			}
		}
		
		[Column(Storage="_friend", Name="Friend", DbType="varchar(255)", IsPrimaryKey=true, AutoSync=AutoSync.Never, CanBeNull=false)]
		[DebuggerNonUserCode()]
		public string Friend
		{
			get
			{
				return this._friend;
			}
			set
			{
				if (((_friend == value) 
							== false))
				{
					this.OnFriendChanging(value);
					this.SendPropertyChanging();
					this._friend = value;
					this.SendPropertyChanged("Friend");
					this.OnFriendChanged();
				}
			}
		}
		
		[Column(Storage="_offered", Name="Offered", DbType="varchar(32)", AutoSync=AutoSync.Never, CanBeNull=false)]
		[DebuggerNonUserCode()]
		public string Offered
		{
			get
			{
				return this._offered;
			}
			set
			{
				if (((_offered == value) 
							== false))
				{
					this.OnOfferedChanging(value);
					this.SendPropertyChanging();
					this._offered = value;
					this.SendPropertyChanged("Offered");
					this.OnOfferedChanged();
				}
			}
		}
		
		[Column(Storage="_principalID", Name="PrincipalID", DbType="char(36)", IsPrimaryKey=true, AutoSync=AutoSync.Never, CanBeNull=false)]
		[DebuggerNonUserCode()]
		public string PrincipalID
		{
			get
			{
				return this._principalID;
			}
			set
			{
				if (((_principalID == value) 
							== false))
				{
					this.OnPrincipalIDChanging(value);
					this.SendPropertyChanging();
					this._principalID = value;
					this.SendPropertyChanged("PrincipalID");
					this.OnPrincipalIDChanged();
				}
			}
		}
		
		public event System.ComponentModel.PropertyChangingEventHandler PropertyChanging;
		
		public event System.ComponentModel.PropertyChangedEventHandler PropertyChanged;
		
		protected virtual void SendPropertyChanging()
		{
			System.ComponentModel.PropertyChangingEventHandler h = this.PropertyChanging;
			if ((h != null))
			{
				h(this, emptyChangingEventArgs);
			}
		}
		
		protected virtual void SendPropertyChanged(string propertyName)
		{
			System.ComponentModel.PropertyChangedEventHandler h = this.PropertyChanged;
			if ((h != null))
			{
				h(this, new System.ComponentModel.PropertyChangedEventArgs(propertyName));
			}
		}
	}
	
	[Table(Name="GridUser")]
	public partial class griduser : System.ComponentModel.INotifyPropertyChanging, System.ComponentModel.INotifyPropertyChanged
	{
		
		private static System.ComponentModel.PropertyChangingEventArgs emptyChangingEventArgs = new System.ComponentModel.PropertyChangingEventArgs("");
		
		private string _homeLookAt;
		
		private string _homePosition;
		
		private string _homeRegionID;
		
		private string _lastLookAt;
		
		private string _lastPosition;
		
		private string _lastRegionID;
		
		private string _login;
		
		private string _logout;
		
		private string _online;
		
		private string _userID;
		
		#region Extensibility Method Declarations
		partial void OnCreated();
		
		partial void OnHomeLookAtChanged();
		
		partial void OnHomeLookAtChanging(string value);
		
		partial void OnHomePositionChanged();
		
		partial void OnHomePositionChanging(string value);
		
		partial void OnHomeRegionIDChanged();
		
		partial void OnHomeRegionIDChanging(string value);
		
		partial void OnLastLookAtChanged();
		
		partial void OnLastLookAtChanging(string value);
		
		partial void OnLastPositionChanged();
		
		partial void OnLastPositionChanging(string value);
		
		partial void OnLastRegionIDChanged();
		
		partial void OnLastRegionIDChanging(string value);
		
		partial void OnLoginChanged();
		
		partial void OnLoginChanging(string value);
		
		partial void OnLogoutChanged();
		
		partial void OnLogoutChanging(string value);
		
		partial void OnOnlineChanged();
		
		partial void OnOnlineChanging(string value);
		
		partial void OnUserIDChanged();
		
		partial void OnUserIDChanging(string value);
		#endregion
		
		
		public griduser()
		{
			this.OnCreated();
		}
		
		[Column(Storage="_homeLookAt", Name="HomeLookAt", DbType="char(64)", AutoSync=AutoSync.Never, CanBeNull=false)]
		[DebuggerNonUserCode()]
		public string HomeLookAt
		{
			get
			{
				return this._homeLookAt;
			}
			set
			{
				if (((_homeLookAt == value) 
							== false))
				{
					this.OnHomeLookAtChanging(value);
					this.SendPropertyChanging();
					this._homeLookAt = value;
					this.SendPropertyChanged("HomeLookAt");
					this.OnHomeLookAtChanged();
				}
			}
		}
		
		[Column(Storage="_homePosition", Name="HomePosition", DbType="char(64)", AutoSync=AutoSync.Never, CanBeNull=false)]
		[DebuggerNonUserCode()]
		public string HomePosition
		{
			get
			{
				return this._homePosition;
			}
			set
			{
				if (((_homePosition == value) 
							== false))
				{
					this.OnHomePositionChanging(value);
					this.SendPropertyChanging();
					this._homePosition = value;
					this.SendPropertyChanged("HomePosition");
					this.OnHomePositionChanged();
				}
			}
		}
		
		[Column(Storage="_homeRegionID", Name="HomeRegionID", DbType="char(36)", AutoSync=AutoSync.Never, CanBeNull=false)]
		[DebuggerNonUserCode()]
		public string HomeRegionID
		{
			get
			{
				return this._homeRegionID;
			}
			set
			{
				if (((_homeRegionID == value) 
							== false))
				{
					this.OnHomeRegionIDChanging(value);
					this.SendPropertyChanging();
					this._homeRegionID = value;
					this.SendPropertyChanged("HomeRegionID");
					this.OnHomeRegionIDChanged();
				}
			}
		}
		
		[Column(Storage="_lastLookAt", Name="LastLookAt", DbType="char(64)", AutoSync=AutoSync.Never, CanBeNull=false)]
		[DebuggerNonUserCode()]
		public string LastLookAt
		{
			get
			{
				return this._lastLookAt;
			}
			set
			{
				if (((_lastLookAt == value) 
							== false))
				{
					this.OnLastLookAtChanging(value);
					this.SendPropertyChanging();
					this._lastLookAt = value;
					this.SendPropertyChanged("LastLookAt");
					this.OnLastLookAtChanged();
				}
			}
		}
		
		[Column(Storage="_lastPosition", Name="LastPosition", DbType="char(64)", AutoSync=AutoSync.Never, CanBeNull=false)]
		[DebuggerNonUserCode()]
		public string LastPosition
		{
			get
			{
				return this._lastPosition;
			}
			set
			{
				if (((_lastPosition == value) 
							== false))
				{
					this.OnLastPositionChanging(value);
					this.SendPropertyChanging();
					this._lastPosition = value;
					this.SendPropertyChanged("LastPosition");
					this.OnLastPositionChanged();
				}
			}
		}
		
		[Column(Storage="_lastRegionID", Name="LastRegionID", DbType="char(36)", AutoSync=AutoSync.Never, CanBeNull=false)]
		[DebuggerNonUserCode()]
		public string LastRegionID
		{
			get
			{
				return this._lastRegionID;
			}
			set
			{
				if (((_lastRegionID == value) 
							== false))
				{
					this.OnLastRegionIDChanging(value);
					this.SendPropertyChanging();
					this._lastRegionID = value;
					this.SendPropertyChanged("LastRegionID");
					this.OnLastRegionIDChanged();
				}
			}
		}
		
		[Column(Storage="_login", Name="Login", DbType="char(16)", AutoSync=AutoSync.Never, CanBeNull=false)]
		[DebuggerNonUserCode()]
		public string Login
		{
			get
			{
				return this._login;
			}
			set
			{
				if (((_login == value) 
							== false))
				{
					this.OnLoginChanging(value);
					this.SendPropertyChanging();
					this._login = value;
					this.SendPropertyChanged("Login");
					this.OnLoginChanged();
				}
			}
		}
		
		[Column(Storage="_logout", Name="Logout", DbType="char(16)", AutoSync=AutoSync.Never, CanBeNull=false)]
		[DebuggerNonUserCode()]
		public string Logout
		{
			get
			{
				return this._logout;
			}
			set
			{
				if (((_logout == value) 
							== false))
				{
					this.OnLogoutChanging(value);
					this.SendPropertyChanging();
					this._logout = value;
					this.SendPropertyChanged("Logout");
					this.OnLogoutChanged();
				}
			}
		}
		
		[Column(Storage="_online", Name="Online", DbType="char(5)", AutoSync=AutoSync.Never, CanBeNull=false)]
		[DebuggerNonUserCode()]
		public string Online
		{
			get
			{
				return this._online;
			}
			set
			{
				if (((_online == value) 
							== false))
				{
					this.OnOnlineChanging(value);
					this.SendPropertyChanging();
					this._online = value;
					this.SendPropertyChanged("Online");
					this.OnOnlineChanged();
				}
			}
		}
		
		[Column(Storage="_userID", Name="UserID", DbType="varchar(255)", IsPrimaryKey=true, AutoSync=AutoSync.Never, CanBeNull=false)]
		[DebuggerNonUserCode()]
		public string UserID
		{
			get
			{
				return this._userID;
			}
			set
			{
				if (((_userID == value) 
							== false))
				{
					this.OnUserIDChanging(value);
					this.SendPropertyChanging();
					this._userID = value;
					this.SendPropertyChanged("UserID");
					this.OnUserIDChanged();
				}
			}
		}
		
		public event System.ComponentModel.PropertyChangingEventHandler PropertyChanging;
		
		public event System.ComponentModel.PropertyChangedEventHandler PropertyChanged;
		
		protected virtual void SendPropertyChanging()
		{
			System.ComponentModel.PropertyChangingEventHandler h = this.PropertyChanging;
			if ((h != null))
			{
				h(this, emptyChangingEventArgs);
			}
		}
		
		protected virtual void SendPropertyChanged(string propertyName)
		{
			System.ComponentModel.PropertyChangedEventHandler h = this.PropertyChanged;
			if ((h != null))
			{
				h(this, new System.ComponentModel.PropertyChangedEventArgs(propertyName));
			}
		}
	}
	
	[Table(Name="inventoryfolders")]
	public partial class inventoryfolders : System.ComponentModel.INotifyPropertyChanging, System.ComponentModel.INotifyPropertyChanged
	{
		
		private static System.ComponentModel.PropertyChangingEventArgs emptyChangingEventArgs = new System.ComponentModel.PropertyChangingEventArgs("");
		
		private string _agentID;
		
		private string _folderID;
		
		private string _folderName;
		
		private string _parentFolderID;
		
		private short _type;
		
		private int _version;
		
		#region Extensibility Method Declarations
		partial void OnCreated();
		
		partial void OnagentIDChanged();
		
		partial void OnagentIDChanging(string value);
		
		partial void OnfolderIDChanged();
		
		partial void OnfolderIDChanging(string value);
		
		partial void OnfolderNameChanged();
		
		partial void OnfolderNameChanging(string value);
		
		partial void OnparentFolderIDChanged();
		
		partial void OnparentFolderIDChanging(string value);
		
		partial void OntypeChanged();
		
		partial void OntypeChanging(short value);
		
		partial void OnversionChanged();
		
		partial void OnversionChanging(int value);
		#endregion
		
		
		public inventoryfolders()
		{
			this.OnCreated();
		}
		
		[Column(Storage="_agentID", Name="agentID", DbType="char(36)", AutoSync=AutoSync.Never)]
		[DebuggerNonUserCode()]
		public string agentID
		{
			get
			{
				return this._agentID;
			}
			set
			{
				if (((_agentID == value) 
							== false))
				{
					this.OnagentIDChanging(value);
					this.SendPropertyChanging();
					this._agentID = value;
					this.SendPropertyChanged("agentID");
					this.OnagentIDChanged();
				}
			}
		}
		
		[Column(Storage="_folderID", Name="folderID", DbType="char(36)", IsPrimaryKey=true, AutoSync=AutoSync.Never, CanBeNull=false)]
		[DebuggerNonUserCode()]
		public string folderID
		{
			get
			{
				return this._folderID;
			}
			set
			{
				if (((_folderID == value) 
							== false))
				{
					this.OnfolderIDChanging(value);
					this.SendPropertyChanging();
					this._folderID = value;
					this.SendPropertyChanged("folderID");
					this.OnfolderIDChanged();
				}
			}
		}
		
		[Column(Storage="_folderName", Name="folderName", DbType="varchar(64)", AutoSync=AutoSync.Never)]
		[DebuggerNonUserCode()]
		public string folderName
		{
			get
			{
				return this._folderName;
			}
			set
			{
				if (((_folderName == value) 
							== false))
				{
					this.OnfolderNameChanging(value);
					this.SendPropertyChanging();
					this._folderName = value;
					this.SendPropertyChanged("folderName");
					this.OnfolderNameChanged();
				}
			}
		}
		
		[Column(Storage="_parentFolderID", Name="parentFolderID", DbType="char(36)", AutoSync=AutoSync.Never)]
		[DebuggerNonUserCode()]
		public string parentFolderID
		{
			get
			{
				return this._parentFolderID;
			}
			set
			{
				if (((_parentFolderID == value) 
							== false))
				{
					this.OnparentFolderIDChanging(value);
					this.SendPropertyChanging();
					this._parentFolderID = value;
					this.SendPropertyChanged("parentFolderID");
					this.OnparentFolderIDChanged();
				}
			}
		}
		
		[Column(Storage="_type", Name="type", DbType="smallint(6)", AutoSync=AutoSync.Never, CanBeNull=false)]
		[DebuggerNonUserCode()]
		public short type
		{
			get
			{
				return this._type;
			}
			set
			{
				if ((_type != value))
				{
					this.OntypeChanging(value);
					this.SendPropertyChanging();
					this._type = value;
					this.SendPropertyChanged("type");
					this.OntypeChanged();
				}
			}
		}
		
		[Column(Storage="_version", Name="version", DbType="int", AutoSync=AutoSync.Never, CanBeNull=false)]
		[DebuggerNonUserCode()]
		public int version
		{
			get
			{
				return this._version;
			}
			set
			{
				if ((_version != value))
				{
					this.OnversionChanging(value);
					this.SendPropertyChanging();
					this._version = value;
					this.SendPropertyChanged("version");
					this.OnversionChanged();
				}
			}
		}
		
		public event System.ComponentModel.PropertyChangingEventHandler PropertyChanging;
		
		public event System.ComponentModel.PropertyChangedEventHandler PropertyChanged;
		
		protected virtual void SendPropertyChanging()
		{
			System.ComponentModel.PropertyChangingEventHandler h = this.PropertyChanging;
			if ((h != null))
			{
				h(this, emptyChangingEventArgs);
			}
		}
		
		protected virtual void SendPropertyChanged(string propertyName)
		{
			System.ComponentModel.PropertyChangedEventHandler h = this.PropertyChanged;
			if ((h != null))
			{
				h(this, new System.ComponentModel.PropertyChangedEventArgs(propertyName));
			}
		}
	}
	
	[Table(Name="inventoryitems")]
	public partial class inventoryitems : System.ComponentModel.INotifyPropertyChanging, System.ComponentModel.INotifyPropertyChanged
	{
		
		private static System.ComponentModel.PropertyChangingEventArgs emptyChangingEventArgs = new System.ComponentModel.PropertyChangingEventArgs("");
		
		private string _assetID;
		
		private System.Nullable<int> _assetType;
		
		private string _avatarID;
		
		private int _creationDate;
		
		private string _creatorID;
		
		private uint _flags;
		
		private string _groupID;
		
		private sbyte _groupOwned;
		
		private uint _inventoryBasePermissions;
		
		private System.Nullable<uint> _inventoryCurrentPermissions;
		
		private string _inventoryDescription;
		
		private uint _inventoryEveryOnePermissions;
		
		private uint _inventoryGroupPermissions;
		
		private string _inventoryID;
		
		private string _inventoryName;
		
		private System.Nullable<uint> _inventoryNextPermissions;
		
		private System.Nullable<int> _invType;
		
		private string _parentFolderID;
		
		private int _salePrice;
		
		private sbyte _saleType;
		
		#region Extensibility Method Declarations
		partial void OnCreated();
		
		partial void OnassetIDChanged();
		
		partial void OnassetIDChanging(string value);
		
		partial void OnassetTypeChanged();
		
		partial void OnassetTypeChanging(System.Nullable<int> value);
		
		partial void OnavatarIDChanged();
		
		partial void OnavatarIDChanging(string value);
		
		partial void OncreationDateChanged();
		
		partial void OncreationDateChanging(int value);
		
		partial void OncreatorIDChanged();
		
		partial void OncreatorIDChanging(string value);
		
		partial void OnflagsChanged();
		
		partial void OnflagsChanging(uint value);
		
		partial void OngroupIDChanged();
		
		partial void OngroupIDChanging(string value);
		
		partial void OngroupOwnedChanged();
		
		partial void OngroupOwnedChanging(sbyte value);
		
		partial void OninventoryBasePermissionsChanged();
		
		partial void OninventoryBasePermissionsChanging(uint value);
		
		partial void OninventoryCurrentPermissionsChanged();
		
		partial void OninventoryCurrentPermissionsChanging(System.Nullable<uint> value);
		
		partial void OninventoryDescriptionChanged();
		
		partial void OninventoryDescriptionChanging(string value);
		
		partial void OninventoryEveryOnePermissionsChanged();
		
		partial void OninventoryEveryOnePermissionsChanging(uint value);
		
		partial void OninventoryGroupPermissionsChanged();
		
		partial void OninventoryGroupPermissionsChanging(uint value);
		
		partial void OninventoryIDChanged();
		
		partial void OninventoryIDChanging(string value);
		
		partial void OninventoryNameChanged();
		
		partial void OninventoryNameChanging(string value);
		
		partial void OninventoryNextPermissionsChanged();
		
		partial void OninventoryNextPermissionsChanging(System.Nullable<uint> value);
		
		partial void OninvTypeChanged();
		
		partial void OninvTypeChanging(System.Nullable<int> value);
		
		partial void OnparentFolderIDChanged();
		
		partial void OnparentFolderIDChanging(string value);
		
		partial void OnsalePriceChanged();
		
		partial void OnsalePriceChanging(int value);
		
		partial void OnsaleTypeChanged();
		
		partial void OnsaleTypeChanging(sbyte value);
		#endregion
		
		
		public inventoryitems()
		{
			this.OnCreated();
		}
		
		[Column(Storage="_assetID", Name="assetID", DbType="varchar(36)", AutoSync=AutoSync.Never)]
		[DebuggerNonUserCode()]
		public string assetID
		{
			get
			{
				return this._assetID;
			}
			set
			{
				if (((_assetID == value) 
							== false))
				{
					this.OnassetIDChanging(value);
					this.SendPropertyChanging();
					this._assetID = value;
					this.SendPropertyChanged("assetID");
					this.OnassetIDChanged();
				}
			}
		}
		
		[Column(Storage="_assetType", Name="assetType", DbType="int", AutoSync=AutoSync.Never)]
		[DebuggerNonUserCode()]
		public System.Nullable<int> assetType
		{
			get
			{
				return this._assetType;
			}
			set
			{
				if ((_assetType != value))
				{
					this.OnassetTypeChanging(value);
					this.SendPropertyChanging();
					this._assetType = value;
					this.SendPropertyChanged("assetType");
					this.OnassetTypeChanged();
				}
			}
		}
		
		[Column(Storage="_avatarID", Name="avatarID", DbType="char(36)", AutoSync=AutoSync.Never)]
		[DebuggerNonUserCode()]
		public string avatarID
		{
			get
			{
				return this._avatarID;
			}
			set
			{
				if (((_avatarID == value) 
							== false))
				{
					this.OnavatarIDChanging(value);
					this.SendPropertyChanging();
					this._avatarID = value;
					this.SendPropertyChanged("avatarID");
					this.OnavatarIDChanged();
				}
			}
		}
		
		[Column(Storage="_creationDate", Name="creationDate", DbType="int", AutoSync=AutoSync.Never, CanBeNull=false)]
		[DebuggerNonUserCode()]
		public int creationDate
		{
			get
			{
				return this._creationDate;
			}
			set
			{
				if ((_creationDate != value))
				{
					this.OncreationDateChanging(value);
					this.SendPropertyChanging();
					this._creationDate = value;
					this.SendPropertyChanged("creationDate");
					this.OncreationDateChanged();
				}
			}
		}
		
		[Column(Storage="_creatorID", Name="creatorID", DbType="varchar(128)", AutoSync=AutoSync.Never, CanBeNull=false)]
		[DebuggerNonUserCode()]
		public string creatorID
		{
			get
			{
				return this._creatorID;
			}
			set
			{
				if (((_creatorID == value) 
							== false))
				{
					this.OncreatorIDChanging(value);
					this.SendPropertyChanging();
					this._creatorID = value;
					this.SendPropertyChanged("creatorID");
					this.OncreatorIDChanged();
				}
			}
		}
		
		[Column(Storage="_flags", Name="flags", DbType="int unsigned", AutoSync=AutoSync.Never, CanBeNull=false)]
		[DebuggerNonUserCode()]
		public uint flags
		{
			get
			{
				return this._flags;
			}
			set
			{
				if ((_flags != value))
				{
					this.OnflagsChanging(value);
					this.SendPropertyChanging();
					this._flags = value;
					this.SendPropertyChanged("flags");
					this.OnflagsChanged();
				}
			}
		}
		
		[Column(Storage="_groupID", Name="groupID", DbType="varchar(36)", AutoSync=AutoSync.Never, CanBeNull=false)]
		[DebuggerNonUserCode()]
		public string groupID
		{
			get
			{
				return this._groupID;
			}
			set
			{
				if (((_groupID == value) 
							== false))
				{
					this.OngroupIDChanging(value);
					this.SendPropertyChanging();
					this._groupID = value;
					this.SendPropertyChanged("groupID");
					this.OngroupIDChanged();
				}
			}
		}
		
		[Column(Storage="_groupOwned", Name="groupOwned", DbType="tinyint(4)", AutoSync=AutoSync.Never, CanBeNull=false)]
		[DebuggerNonUserCode()]
		public sbyte groupOwned
		{
			get
			{
				return this._groupOwned;
			}
			set
			{
				if ((_groupOwned != value))
				{
					this.OngroupOwnedChanging(value);
					this.SendPropertyChanging();
					this._groupOwned = value;
					this.SendPropertyChanged("groupOwned");
					this.OngroupOwnedChanged();
				}
			}
		}
		
		[Column(Storage="_inventoryBasePermissions", Name="inventoryBasePermissions", DbType="int unsigned", AutoSync=AutoSync.Never, CanBeNull=false)]
		[DebuggerNonUserCode()]
		public uint inventoryBasePermissions
		{
			get
			{
				return this._inventoryBasePermissions;
			}
			set
			{
				if ((_inventoryBasePermissions != value))
				{
					this.OninventoryBasePermissionsChanging(value);
					this.SendPropertyChanging();
					this._inventoryBasePermissions = value;
					this.SendPropertyChanged("inventoryBasePermissions");
					this.OninventoryBasePermissionsChanged();
				}
			}
		}
		
		[Column(Storage="_inventoryCurrentPermissions", Name="inventoryCurrentPermissions", DbType="int unsigned", AutoSync=AutoSync.Never)]
		[DebuggerNonUserCode()]
		public System.Nullable<uint> inventoryCurrentPermissions
		{
			get
			{
				return this._inventoryCurrentPermissions;
			}
			set
			{
				if ((_inventoryCurrentPermissions != value))
				{
					this.OninventoryCurrentPermissionsChanging(value);
					this.SendPropertyChanging();
					this._inventoryCurrentPermissions = value;
					this.SendPropertyChanged("inventoryCurrentPermissions");
					this.OninventoryCurrentPermissionsChanged();
				}
			}
		}
		
		[Column(Storage="_inventoryDescription", Name="inventoryDescription", DbType="varchar(128)", AutoSync=AutoSync.Never)]
		[DebuggerNonUserCode()]
		public string inventoryDescription
		{
			get
			{
				return this._inventoryDescription;
			}
			set
			{
				if (((_inventoryDescription == value) 
							== false))
				{
					this.OninventoryDescriptionChanging(value);
					this.SendPropertyChanging();
					this._inventoryDescription = value;
					this.SendPropertyChanged("inventoryDescription");
					this.OninventoryDescriptionChanged();
				}
			}
		}
		
		[Column(Storage="_inventoryEveryOnePermissions", Name="inventoryEveryOnePermissions", DbType="int unsigned", AutoSync=AutoSync.Never, CanBeNull=false)]
		[DebuggerNonUserCode()]
		public uint inventoryEveryOnePermissions
		{
			get
			{
				return this._inventoryEveryOnePermissions;
			}
			set
			{
				if ((_inventoryEveryOnePermissions != value))
				{
					this.OninventoryEveryOnePermissionsChanging(value);
					this.SendPropertyChanging();
					this._inventoryEveryOnePermissions = value;
					this.SendPropertyChanged("inventoryEveryOnePermissions");
					this.OninventoryEveryOnePermissionsChanged();
				}
			}
		}
		
		[Column(Storage="_inventoryGroupPermissions", Name="inventoryGroupPermissions", DbType="int unsigned", AutoSync=AutoSync.Never, CanBeNull=false)]
		[DebuggerNonUserCode()]
		public uint inventoryGroupPermissions
		{
			get
			{
				return this._inventoryGroupPermissions;
			}
			set
			{
				if ((_inventoryGroupPermissions != value))
				{
					this.OninventoryGroupPermissionsChanging(value);
					this.SendPropertyChanging();
					this._inventoryGroupPermissions = value;
					this.SendPropertyChanged("inventoryGroupPermissions");
					this.OninventoryGroupPermissionsChanged();
				}
			}
		}
		
		[Column(Storage="_inventoryID", Name="inventoryID", DbType="char(36)", IsPrimaryKey=true, AutoSync=AutoSync.Never, CanBeNull=false)]
		[DebuggerNonUserCode()]
		public string inventoryID
		{
			get
			{
				return this._inventoryID;
			}
			set
			{
				if (((_inventoryID == value) 
							== false))
				{
					this.OninventoryIDChanging(value);
					this.SendPropertyChanging();
					this._inventoryID = value;
					this.SendPropertyChanged("inventoryID");
					this.OninventoryIDChanged();
				}
			}
		}
		
		[Column(Storage="_inventoryName", Name="inventoryName", DbType="varchar(64)", AutoSync=AutoSync.Never)]
		[DebuggerNonUserCode()]
		public string inventoryName
		{
			get
			{
				return this._inventoryName;
			}
			set
			{
				if (((_inventoryName == value) 
							== false))
				{
					this.OninventoryNameChanging(value);
					this.SendPropertyChanging();
					this._inventoryName = value;
					this.SendPropertyChanged("inventoryName");
					this.OninventoryNameChanged();
				}
			}
		}
		
		[Column(Storage="_inventoryNextPermissions", Name="inventoryNextPermissions", DbType="int unsigned", AutoSync=AutoSync.Never)]
		[DebuggerNonUserCode()]
		public System.Nullable<uint> inventoryNextPermissions
		{
			get
			{
				return this._inventoryNextPermissions;
			}
			set
			{
				if ((_inventoryNextPermissions != value))
				{
					this.OninventoryNextPermissionsChanging(value);
					this.SendPropertyChanging();
					this._inventoryNextPermissions = value;
					this.SendPropertyChanged("inventoryNextPermissions");
					this.OninventoryNextPermissionsChanged();
				}
			}
		}
		
		[Column(Storage="_invType", Name="invType", DbType="int", AutoSync=AutoSync.Never)]
		[DebuggerNonUserCode()]
		public System.Nullable<int> invType
		{
			get
			{
				return this._invType;
			}
			set
			{
				if ((_invType != value))
				{
					this.OninvTypeChanging(value);
					this.SendPropertyChanging();
					this._invType = value;
					this.SendPropertyChanged("invType");
					this.OninvTypeChanged();
				}
			}
		}
		
		[Column(Storage="_parentFolderID", Name="parentFolderID", DbType="char(36)", AutoSync=AutoSync.Never)]
		[DebuggerNonUserCode()]
		public string parentFolderID
		{
			get
			{
				return this._parentFolderID;
			}
			set
			{
				if (((_parentFolderID == value) 
							== false))
				{
					this.OnparentFolderIDChanging(value);
					this.SendPropertyChanging();
					this._parentFolderID = value;
					this.SendPropertyChanged("parentFolderID");
					this.OnparentFolderIDChanged();
				}
			}
		}
		
		[Column(Storage="_salePrice", Name="salePrice", DbType="int", AutoSync=AutoSync.Never, CanBeNull=false)]
		[DebuggerNonUserCode()]
		public int salePrice
		{
			get
			{
				return this._salePrice;
			}
			set
			{
				if ((_salePrice != value))
				{
					this.OnsalePriceChanging(value);
					this.SendPropertyChanging();
					this._salePrice = value;
					this.SendPropertyChanged("salePrice");
					this.OnsalePriceChanged();
				}
			}
		}
		
		[Column(Storage="_saleType", Name="saleType", DbType="tinyint(4)", AutoSync=AutoSync.Never, CanBeNull=false)]
		[DebuggerNonUserCode()]
		public sbyte saleType
		{
			get
			{
				return this._saleType;
			}
			set
			{
				if ((_saleType != value))
				{
					this.OnsaleTypeChanging(value);
					this.SendPropertyChanging();
					this._saleType = value;
					this.SendPropertyChanged("saleType");
					this.OnsaleTypeChanged();
				}
			}
		}
		
		public event System.ComponentModel.PropertyChangingEventHandler PropertyChanging;
		
		public event System.ComponentModel.PropertyChangedEventHandler PropertyChanged;
		
		protected virtual void SendPropertyChanging()
		{
			System.ComponentModel.PropertyChangingEventHandler h = this.PropertyChanging;
			if ((h != null))
			{
				h(this, emptyChangingEventArgs);
			}
		}
		
		protected virtual void SendPropertyChanged(string propertyName)
		{
			System.ComponentModel.PropertyChangedEventHandler h = this.PropertyChanged;
			if ((h != null))
			{
				h(this, new System.ComponentModel.PropertyChangedEventArgs(propertyName));
			}
		}
	}
	
	[Table(Name="land")]
	public partial class land : System.ComponentModel.INotifyPropertyChanging, System.ComponentModel.INotifyPropertyChanged
	{
		
		private static System.ComponentModel.PropertyChangingEventArgs emptyChangingEventArgs = new System.ComponentModel.PropertyChangingEventArgs("");
		
		private System.Nullable<int> _area;
		
		private System.Nullable<int> _auctionID;
		
		private string _authbuyerID;
		
		private byte[] _bitmap;
		
		private System.Nullable<int> _category;
		
		private System.Nullable<int> _claimDate;
		
		private System.Nullable<int> _claimPrice;
		
		private string _description;
		
		private int _dwell;
		
		private string _groupUuid;
		
		private System.Nullable<int> _isGroupOwned;
		
		private System.Nullable<int> _landFlags;
		
		private System.Nullable<int> _landingType;
		
		private System.Nullable<int> _landStatus;
		
		private System.Nullable<int> _localLandID;
		
		private System.Nullable<int> _mediaAutoScale;
		
		private string _mediaTextureUuid;
		
		private string _mediaUrl;
		
		private string _musicUrl;
		
		private string _name;
		
		private int _otherCleanTime;
		
		private string _ownerUuid;
		
		private System.Nullable<float> _passHours;
		
		private System.Nullable<int> _passPrice;
		
		private string _regionUuid;
		
		private System.Nullable<int> _salePrice;
		
		private string _snapshotUuid;
		
		private System.Nullable<float> _userLocationX;
		
		private System.Nullable<float> _userLocationY;
		
		private System.Nullable<float> _userLocationZ;
		
		private System.Nullable<float> _userLookAtX;
		
		private System.Nullable<float> _userLookAtY;
		
		private System.Nullable<float> _userLookAtZ;
		
		private string _uuid;
		
		#region Extensibility Method Declarations
		partial void OnCreated();
		
		partial void OnAreaChanged();
		
		partial void OnAreaChanging(System.Nullable<int> value);
		
		partial void OnAuctionIDChanged();
		
		partial void OnAuctionIDChanging(System.Nullable<int> value);
		
		partial void OnAuthbuyerIDChanged();
		
		partial void OnAuthbuyerIDChanging(string value);
		
		partial void OnBitmapChanged();
		
		partial void OnBitmapChanging(byte[] value);
		
		partial void OnCategoryChanged();
		
		partial void OnCategoryChanging(System.Nullable<int> value);
		
		partial void OnClaimDateChanged();
		
		partial void OnClaimDateChanging(System.Nullable<int> value);
		
		partial void OnClaimPriceChanged();
		
		partial void OnClaimPriceChanging(System.Nullable<int> value);
		
		partial void OnDescriptionChanged();
		
		partial void OnDescriptionChanging(string value);
		
		partial void OnDwellChanged();
		
		partial void OnDwellChanging(int value);
		
		partial void OnGroupUUIDChanged();
		
		partial void OnGroupUUIDChanging(string value);
		
		partial void OnIsGroupOwnedChanged();
		
		partial void OnIsGroupOwnedChanging(System.Nullable<int> value);
		
		partial void OnLandFlagsChanged();
		
		partial void OnLandFlagsChanging(System.Nullable<int> value);
		
		partial void OnLandingTypeChanged();
		
		partial void OnLandingTypeChanging(System.Nullable<int> value);
		
		partial void OnLandStatusChanged();
		
		partial void OnLandStatusChanging(System.Nullable<int> value);
		
		partial void OnLocalLandIDChanged();
		
		partial void OnLocalLandIDChanging(System.Nullable<int> value);
		
		partial void OnMediaAutoScaleChanged();
		
		partial void OnMediaAutoScaleChanging(System.Nullable<int> value);
		
		partial void OnMediaTextureUUIDChanged();
		
		partial void OnMediaTextureUUIDChanging(string value);
		
		partial void OnMediaURLChanged();
		
		partial void OnMediaURLChanging(string value);
		
		partial void OnMusicURLChanged();
		
		partial void OnMusicURLChanging(string value);
		
		partial void OnNameChanged();
		
		partial void OnNameChanging(string value);
		
		partial void OnOtherCleanTimeChanged();
		
		partial void OnOtherCleanTimeChanging(int value);
		
		partial void OnOwnerUUIDChanged();
		
		partial void OnOwnerUUIDChanging(string value);
		
		partial void OnPassHoursChanged();
		
		partial void OnPassHoursChanging(System.Nullable<float> value);
		
		partial void OnPassPriceChanged();
		
		partial void OnPassPriceChanging(System.Nullable<int> value);
		
		partial void OnRegionUUIDChanged();
		
		partial void OnRegionUUIDChanging(string value);
		
		partial void OnSalePriceChanged();
		
		partial void OnSalePriceChanging(System.Nullable<int> value);
		
		partial void OnSnapshotUUIDChanged();
		
		partial void OnSnapshotUUIDChanging(string value);
		
		partial void OnUserLocationXChanged();
		
		partial void OnUserLocationXChanging(System.Nullable<float> value);
		
		partial void OnUserLocationYChanged();
		
		partial void OnUserLocationYChanging(System.Nullable<float> value);
		
		partial void OnUserLocationZChanged();
		
		partial void OnUserLocationZChanging(System.Nullable<float> value);
		
		partial void OnUserLookAtXChanged();
		
		partial void OnUserLookAtXChanging(System.Nullable<float> value);
		
		partial void OnUserLookAtYChanged();
		
		partial void OnUserLookAtYChanging(System.Nullable<float> value);
		
		partial void OnUserLookAtZChanged();
		
		partial void OnUserLookAtZChanging(System.Nullable<float> value);
		
		partial void OnUUIDChanged();
		
		partial void OnUUIDChanging(string value);
		#endregion
		
		
		public land()
		{
			this.OnCreated();
		}
		
		[Column(Storage="_area", Name="Area", DbType="int", AutoSync=AutoSync.Never)]
		[DebuggerNonUserCode()]
		public System.Nullable<int> Area
		{
			get
			{
				return this._area;
			}
			set
			{
				if ((_area != value))
				{
					this.OnAreaChanging(value);
					this.SendPropertyChanging();
					this._area = value;
					this.SendPropertyChanged("Area");
					this.OnAreaChanged();
				}
			}
		}
		
		[Column(Storage="_auctionID", Name="AuctionID", DbType="int", AutoSync=AutoSync.Never)]
		[DebuggerNonUserCode()]
		public System.Nullable<int> AuctionID
		{
			get
			{
				return this._auctionID;
			}
			set
			{
				if ((_auctionID != value))
				{
					this.OnAuctionIDChanging(value);
					this.SendPropertyChanging();
					this._auctionID = value;
					this.SendPropertyChanged("AuctionID");
					this.OnAuctionIDChanged();
				}
			}
		}
		
		[Column(Storage="_authbuyerID", Name="AuthbuyerID", DbType="varchar(36)", AutoSync=AutoSync.Never, CanBeNull=false)]
		[DebuggerNonUserCode()]
		public string AuthbuyerID
		{
			get
			{
				return this._authbuyerID;
			}
			set
			{
				if (((_authbuyerID == value) 
							== false))
				{
					this.OnAuthbuyerIDChanging(value);
					this.SendPropertyChanging();
					this._authbuyerID = value;
					this.SendPropertyChanged("AuthbuyerID");
					this.OnAuthbuyerIDChanged();
				}
			}
		}
		
		[Column(Storage="_bitmap", Name="Bitmap", DbType="longblob", AutoSync=AutoSync.Never)]
		[DebuggerNonUserCode()]
		public byte[] Bitmap
		{
			get
			{
				return this._bitmap;
			}
			set
			{
				if (((_bitmap == value) 
							== false))
				{
					this.OnBitmapChanging(value);
					this.SendPropertyChanging();
					this._bitmap = value;
					this.SendPropertyChanged("Bitmap");
					this.OnBitmapChanged();
				}
			}
		}
		
		[Column(Storage="_category", Name="Category", DbType="int", AutoSync=AutoSync.Never)]
		[DebuggerNonUserCode()]
		public System.Nullable<int> Category
		{
			get
			{
				return this._category;
			}
			set
			{
				if ((_category != value))
				{
					this.OnCategoryChanging(value);
					this.SendPropertyChanging();
					this._category = value;
					this.SendPropertyChanged("Category");
					this.OnCategoryChanged();
				}
			}
		}
		
		[Column(Storage="_claimDate", Name="ClaimDate", DbType="int", AutoSync=AutoSync.Never)]
		[DebuggerNonUserCode()]
		public System.Nullable<int> ClaimDate
		{
			get
			{
				return this._claimDate;
			}
			set
			{
				if ((_claimDate != value))
				{
					this.OnClaimDateChanging(value);
					this.SendPropertyChanging();
					this._claimDate = value;
					this.SendPropertyChanged("ClaimDate");
					this.OnClaimDateChanged();
				}
			}
		}
		
		[Column(Storage="_claimPrice", Name="ClaimPrice", DbType="int", AutoSync=AutoSync.Never)]
		[DebuggerNonUserCode()]
		public System.Nullable<int> ClaimPrice
		{
			get
			{
				return this._claimPrice;
			}
			set
			{
				if ((_claimPrice != value))
				{
					this.OnClaimPriceChanging(value);
					this.SendPropertyChanging();
					this._claimPrice = value;
					this.SendPropertyChanged("ClaimPrice");
					this.OnClaimPriceChanged();
				}
			}
		}
		
		[Column(Storage="_description", Name="Description", DbType="varchar(255)", AutoSync=AutoSync.Never)]
		[DebuggerNonUserCode()]
		public string Description
		{
			get
			{
				return this._description;
			}
			set
			{
				if (((_description == value) 
							== false))
				{
					this.OnDescriptionChanging(value);
					this.SendPropertyChanging();
					this._description = value;
					this.SendPropertyChanged("Description");
					this.OnDescriptionChanged();
				}
			}
		}
		
		[Column(Storage="_dwell", Name="Dwell", DbType="int", AutoSync=AutoSync.Never, CanBeNull=false)]
		[DebuggerNonUserCode()]
		public int Dwell
		{
			get
			{
				return this._dwell;
			}
			set
			{
				if ((_dwell != value))
				{
					this.OnDwellChanging(value);
					this.SendPropertyChanging();
					this._dwell = value;
					this.SendPropertyChanged("Dwell");
					this.OnDwellChanged();
				}
			}
		}
		
		[Column(Storage="_groupUuid", Name="GroupUUID", DbType="varchar(255)", AutoSync=AutoSync.Never)]
		[DebuggerNonUserCode()]
		public string GroupUUID
		{
			get
			{
				return this._groupUuid;
			}
			set
			{
				if (((_groupUuid == value) 
							== false))
				{
					this.OnGroupUUIDChanging(value);
					this.SendPropertyChanging();
					this._groupUuid = value;
					this.SendPropertyChanged("GroupUUID");
					this.OnGroupUUIDChanged();
				}
			}
		}
		
		[Column(Storage="_isGroupOwned", Name="IsGroupOwned", DbType="int", AutoSync=AutoSync.Never)]
		[DebuggerNonUserCode()]
		public System.Nullable<int> IsGroupOwned
		{
			get
			{
				return this._isGroupOwned;
			}
			set
			{
				if ((_isGroupOwned != value))
				{
					this.OnIsGroupOwnedChanging(value);
					this.SendPropertyChanging();
					this._isGroupOwned = value;
					this.SendPropertyChanged("IsGroupOwned");
					this.OnIsGroupOwnedChanged();
				}
			}
		}
		
		[Column(Storage="_landFlags", Name="LandFlags", DbType="int", AutoSync=AutoSync.Never)]
		[DebuggerNonUserCode()]
		public System.Nullable<int> LandFlags
		{
			get
			{
				return this._landFlags;
			}
			set
			{
				if ((_landFlags != value))
				{
					this.OnLandFlagsChanging(value);
					this.SendPropertyChanging();
					this._landFlags = value;
					this.SendPropertyChanged("LandFlags");
					this.OnLandFlagsChanged();
				}
			}
		}
		
		[Column(Storage="_landingType", Name="LandingType", DbType="int", AutoSync=AutoSync.Never)]
		[DebuggerNonUserCode()]
		public System.Nullable<int> LandingType
		{
			get
			{
				return this._landingType;
			}
			set
			{
				if ((_landingType != value))
				{
					this.OnLandingTypeChanging(value);
					this.SendPropertyChanging();
					this._landingType = value;
					this.SendPropertyChanged("LandingType");
					this.OnLandingTypeChanged();
				}
			}
		}
		
		[Column(Storage="_landStatus", Name="LandStatus", DbType="int", AutoSync=AutoSync.Never)]
		[DebuggerNonUserCode()]
		public System.Nullable<int> LandStatus
		{
			get
			{
				return this._landStatus;
			}
			set
			{
				if ((_landStatus != value))
				{
					this.OnLandStatusChanging(value);
					this.SendPropertyChanging();
					this._landStatus = value;
					this.SendPropertyChanged("LandStatus");
					this.OnLandStatusChanged();
				}
			}
		}
		
		[Column(Storage="_localLandID", Name="LocalLandID", DbType="int", AutoSync=AutoSync.Never)]
		[DebuggerNonUserCode()]
		public System.Nullable<int> LocalLandID
		{
			get
			{
				return this._localLandID;
			}
			set
			{
				if ((_localLandID != value))
				{
					this.OnLocalLandIDChanging(value);
					this.SendPropertyChanging();
					this._localLandID = value;
					this.SendPropertyChanged("LocalLandID");
					this.OnLocalLandIDChanged();
				}
			}
		}
		
		[Column(Storage="_mediaAutoScale", Name="MediaAutoScale", DbType="int", AutoSync=AutoSync.Never)]
		[DebuggerNonUserCode()]
		public System.Nullable<int> MediaAutoScale
		{
			get
			{
				return this._mediaAutoScale;
			}
			set
			{
				if ((_mediaAutoScale != value))
				{
					this.OnMediaAutoScaleChanging(value);
					this.SendPropertyChanging();
					this._mediaAutoScale = value;
					this.SendPropertyChanged("MediaAutoScale");
					this.OnMediaAutoScaleChanged();
				}
			}
		}
		
		[Column(Storage="_mediaTextureUuid", Name="MediaTextureUUID", DbType="varchar(255)", AutoSync=AutoSync.Never)]
		[DebuggerNonUserCode()]
		public string MediaTextureUUID
		{
			get
			{
				return this._mediaTextureUuid;
			}
			set
			{
				if (((_mediaTextureUuid == value) 
							== false))
				{
					this.OnMediaTextureUUIDChanging(value);
					this.SendPropertyChanging();
					this._mediaTextureUuid = value;
					this.SendPropertyChanged("MediaTextureUUID");
					this.OnMediaTextureUUIDChanged();
				}
			}
		}
		
		[Column(Storage="_mediaUrl", Name="MediaURL", DbType="varchar(255)", AutoSync=AutoSync.Never)]
		[DebuggerNonUserCode()]
		public string MediaURL
		{
			get
			{
				return this._mediaUrl;
			}
			set
			{
				if (((_mediaUrl == value) 
							== false))
				{
					this.OnMediaURLChanging(value);
					this.SendPropertyChanging();
					this._mediaUrl = value;
					this.SendPropertyChanged("MediaURL");
					this.OnMediaURLChanged();
				}
			}
		}
		
		[Column(Storage="_musicUrl", Name="MusicURL", DbType="varchar(255)", AutoSync=AutoSync.Never)]
		[DebuggerNonUserCode()]
		public string MusicURL
		{
			get
			{
				return this._musicUrl;
			}
			set
			{
				if (((_musicUrl == value) 
							== false))
				{
					this.OnMusicURLChanging(value);
					this.SendPropertyChanging();
					this._musicUrl = value;
					this.SendPropertyChanged("MusicURL");
					this.OnMusicURLChanged();
				}
			}
		}
		
		[Column(Storage="_name", Name="Name", DbType="varchar(255)", AutoSync=AutoSync.Never)]
		[DebuggerNonUserCode()]
		public string Name
		{
			get
			{
				return this._name;
			}
			set
			{
				if (((_name == value) 
							== false))
				{
					this.OnNameChanging(value);
					this.SendPropertyChanging();
					this._name = value;
					this.SendPropertyChanged("Name");
					this.OnNameChanged();
				}
			}
		}
		
		[Column(Storage="_otherCleanTime", Name="OtherCleanTime", DbType="int", AutoSync=AutoSync.Never, CanBeNull=false)]
		[DebuggerNonUserCode()]
		public int OtherCleanTime
		{
			get
			{
				return this._otherCleanTime;
			}
			set
			{
				if ((_otherCleanTime != value))
				{
					this.OnOtherCleanTimeChanging(value);
					this.SendPropertyChanging();
					this._otherCleanTime = value;
					this.SendPropertyChanged("OtherCleanTime");
					this.OnOtherCleanTimeChanged();
				}
			}
		}
		
		[Column(Storage="_ownerUuid", Name="OwnerUUID", DbType="varchar(255)", AutoSync=AutoSync.Never)]
		[DebuggerNonUserCode()]
		public string OwnerUUID
		{
			get
			{
				return this._ownerUuid;
			}
			set
			{
				if (((_ownerUuid == value) 
							== false))
				{
					this.OnOwnerUUIDChanging(value);
					this.SendPropertyChanging();
					this._ownerUuid = value;
					this.SendPropertyChanged("OwnerUUID");
					this.OnOwnerUUIDChanged();
				}
			}
		}
		
		[Column(Storage="_passHours", Name="PassHours", DbType="float", AutoSync=AutoSync.Never)]
		[DebuggerNonUserCode()]
		public System.Nullable<float> PassHours
		{
			get
			{
				return this._passHours;
			}
			set
			{
				if ((_passHours != value))
				{
					this.OnPassHoursChanging(value);
					this.SendPropertyChanging();
					this._passHours = value;
					this.SendPropertyChanged("PassHours");
					this.OnPassHoursChanged();
				}
			}
		}
		
		[Column(Storage="_passPrice", Name="PassPrice", DbType="int", AutoSync=AutoSync.Never)]
		[DebuggerNonUserCode()]
		public System.Nullable<int> PassPrice
		{
			get
			{
				return this._passPrice;
			}
			set
			{
				if ((_passPrice != value))
				{
					this.OnPassPriceChanging(value);
					this.SendPropertyChanging();
					this._passPrice = value;
					this.SendPropertyChanged("PassPrice");
					this.OnPassPriceChanged();
				}
			}
		}
		
		[Column(Storage="_regionUuid", Name="RegionUUID", DbType="varchar(255)", AutoSync=AutoSync.Never)]
		[DebuggerNonUserCode()]
		public string RegionUUID
		{
			get
			{
				return this._regionUuid;
			}
			set
			{
				if (((_regionUuid == value) 
							== false))
				{
					this.OnRegionUUIDChanging(value);
					this.SendPropertyChanging();
					this._regionUuid = value;
					this.SendPropertyChanged("RegionUUID");
					this.OnRegionUUIDChanged();
				}
			}
		}
		
		[Column(Storage="_salePrice", Name="SalePrice", DbType="int", AutoSync=AutoSync.Never)]
		[DebuggerNonUserCode()]
		public System.Nullable<int> SalePrice
		{
			get
			{
				return this._salePrice;
			}
			set
			{
				if ((_salePrice != value))
				{
					this.OnSalePriceChanging(value);
					this.SendPropertyChanging();
					this._salePrice = value;
					this.SendPropertyChanged("SalePrice");
					this.OnSalePriceChanged();
				}
			}
		}
		
		[Column(Storage="_snapshotUuid", Name="SnapshotUUID", DbType="varchar(255)", AutoSync=AutoSync.Never)]
		[DebuggerNonUserCode()]
		public string SnapshotUUID
		{
			get
			{
				return this._snapshotUuid;
			}
			set
			{
				if (((_snapshotUuid == value) 
							== false))
				{
					this.OnSnapshotUUIDChanging(value);
					this.SendPropertyChanging();
					this._snapshotUuid = value;
					this.SendPropertyChanged("SnapshotUUID");
					this.OnSnapshotUUIDChanged();
				}
			}
		}
		
		[Column(Storage="_userLocationX", Name="UserLocationX", DbType="float", AutoSync=AutoSync.Never)]
		[DebuggerNonUserCode()]
		public System.Nullable<float> UserLocationX
		{
			get
			{
				return this._userLocationX;
			}
			set
			{
				if ((_userLocationX != value))
				{
					this.OnUserLocationXChanging(value);
					this.SendPropertyChanging();
					this._userLocationX = value;
					this.SendPropertyChanged("UserLocationX");
					this.OnUserLocationXChanged();
				}
			}
		}
		
		[Column(Storage="_userLocationY", Name="UserLocationY", DbType="float", AutoSync=AutoSync.Never)]
		[DebuggerNonUserCode()]
		public System.Nullable<float> UserLocationY
		{
			get
			{
				return this._userLocationY;
			}
			set
			{
				if ((_userLocationY != value))
				{
					this.OnUserLocationYChanging(value);
					this.SendPropertyChanging();
					this._userLocationY = value;
					this.SendPropertyChanged("UserLocationY");
					this.OnUserLocationYChanged();
				}
			}
		}
		
		[Column(Storage="_userLocationZ", Name="UserLocationZ", DbType="float", AutoSync=AutoSync.Never)]
		[DebuggerNonUserCode()]
		public System.Nullable<float> UserLocationZ
		{
			get
			{
				return this._userLocationZ;
			}
			set
			{
				if ((_userLocationZ != value))
				{
					this.OnUserLocationZChanging(value);
					this.SendPropertyChanging();
					this._userLocationZ = value;
					this.SendPropertyChanged("UserLocationZ");
					this.OnUserLocationZChanged();
				}
			}
		}
		
		[Column(Storage="_userLookAtX", Name="UserLookAtX", DbType="float", AutoSync=AutoSync.Never)]
		[DebuggerNonUserCode()]
		public System.Nullable<float> UserLookAtX
		{
			get
			{
				return this._userLookAtX;
			}
			set
			{
				if ((_userLookAtX != value))
				{
					this.OnUserLookAtXChanging(value);
					this.SendPropertyChanging();
					this._userLookAtX = value;
					this.SendPropertyChanged("UserLookAtX");
					this.OnUserLookAtXChanged();
				}
			}
		}
		
		[Column(Storage="_userLookAtY", Name="UserLookAtY", DbType="float", AutoSync=AutoSync.Never)]
		[DebuggerNonUserCode()]
		public System.Nullable<float> UserLookAtY
		{
			get
			{
				return this._userLookAtY;
			}
			set
			{
				if ((_userLookAtY != value))
				{
					this.OnUserLookAtYChanging(value);
					this.SendPropertyChanging();
					this._userLookAtY = value;
					this.SendPropertyChanged("UserLookAtY");
					this.OnUserLookAtYChanged();
				}
			}
		}
		
		[Column(Storage="_userLookAtZ", Name="UserLookAtZ", DbType="float", AutoSync=AutoSync.Never)]
		[DebuggerNonUserCode()]
		public System.Nullable<float> UserLookAtZ
		{
			get
			{
				return this._userLookAtZ;
			}
			set
			{
				if ((_userLookAtZ != value))
				{
					this.OnUserLookAtZChanging(value);
					this.SendPropertyChanging();
					this._userLookAtZ = value;
					this.SendPropertyChanged("UserLookAtZ");
					this.OnUserLookAtZChanged();
				}
			}
		}
		
		[Column(Storage="_uuid", Name="UUID", DbType="varchar(255)", IsPrimaryKey=true, AutoSync=AutoSync.Never, CanBeNull=false)]
		[DebuggerNonUserCode()]
		public string UUID
		{
			get
			{
				return this._uuid;
			}
			set
			{
				if (((_uuid == value) 
							== false))
				{
					this.OnUUIDChanging(value);
					this.SendPropertyChanging();
					this._uuid = value;
					this.SendPropertyChanged("UUID");
					this.OnUUIDChanged();
				}
			}
		}
		
		public event System.ComponentModel.PropertyChangingEventHandler PropertyChanging;
		
		public event System.ComponentModel.PropertyChangedEventHandler PropertyChanged;
		
		protected virtual void SendPropertyChanging()
		{
			System.ComponentModel.PropertyChangingEventHandler h = this.PropertyChanging;
			if ((h != null))
			{
				h(this, emptyChangingEventArgs);
			}
		}
		
		protected virtual void SendPropertyChanged(string propertyName)
		{
			System.ComponentModel.PropertyChangedEventHandler h = this.PropertyChanged;
			if ((h != null))
			{
				h(this, new System.ComponentModel.PropertyChangedEventArgs(propertyName));
			}
		}
	}
	
	[Table(Name="landaccesslist")]
	public partial class landaccesslist
	{
		
		private string _accessUuid;
		
		private System.Nullable<int> _flags;
		
		private string _landUuid;
		
		#region Extensibility Method Declarations
		partial void OnCreated();
		
		partial void OnAccessUUIDChanged();
		
		partial void OnAccessUUIDChanging(string value);
		
		partial void OnFlagsChanged();
		
		partial void OnFlagsChanging(System.Nullable<int> value);
		
		partial void OnLandUUIDChanged();
		
		partial void OnLandUUIDChanging(string value);
		#endregion
		
		
		public landaccesslist()
		{
			this.OnCreated();
		}
		
		[Column(Storage="_accessUuid", Name="AccessUUID", DbType="varchar(255)", AutoSync=AutoSync.Never)]
		[DebuggerNonUserCode()]
		public string AccessUUID
		{
			get
			{
				return this._accessUuid;
			}
			set
			{
				if (((_accessUuid == value) 
							== false))
				{
					this.OnAccessUUIDChanging(value);
					this._accessUuid = value;
					this.OnAccessUUIDChanged();
				}
			}
		}
		
		[Column(Storage="_flags", Name="Flags", DbType="int", AutoSync=AutoSync.Never)]
		[DebuggerNonUserCode()]
		public System.Nullable<int> Flags
		{
			get
			{
				return this._flags;
			}
			set
			{
				if ((_flags != value))
				{
					this.OnFlagsChanging(value);
					this._flags = value;
					this.OnFlagsChanged();
				}
			}
		}
		
		[Column(Storage="_landUuid", Name="LandUUID", DbType="varchar(255)", AutoSync=AutoSync.Never)]
		[DebuggerNonUserCode()]
		public string LandUUID
		{
			get
			{
				return this._landUuid;
			}
			set
			{
				if (((_landUuid == value) 
							== false))
				{
					this.OnLandUUIDChanging(value);
					this._landUuid = value;
					this.OnLandUUIDChanged();
				}
			}
		}
	}
	
	[Table(Name="migrations")]
	public partial class migrations
	{
		
		private string _name;
		
		private System.Nullable<int> _version;
		
		#region Extensibility Method Declarations
		partial void OnCreated();
		
		partial void OnnameChanged();
		
		partial void OnnameChanging(string value);
		
		partial void OnversionChanged();
		
		partial void OnversionChanging(System.Nullable<int> value);
		#endregion
		
		
		public migrations()
		{
			this.OnCreated();
		}
		
		[Column(Storage="_name", Name="name", DbType="varchar(100)", AutoSync=AutoSync.Never)]
		[DebuggerNonUserCode()]
		public string name
		{
			get
			{
				return this._name;
			}
			set
			{
				if (((_name == value) 
							== false))
				{
					this.OnnameChanging(value);
					this._name = value;
					this.OnnameChanged();
				}
			}
		}
		
		[Column(Storage="_version", Name="version", DbType="int", AutoSync=AutoSync.Never)]
		[DebuggerNonUserCode()]
		public System.Nullable<int> version
		{
			get
			{
				return this._version;
			}
			set
			{
				if ((_version != value))
				{
					this.OnversionChanging(value);
					this._version = value;
					this.OnversionChanged();
				}
			}
		}
	}
	
	[Table(Name="Presence")]
	public partial class presence : System.ComponentModel.INotifyPropertyChanging, System.ComponentModel.INotifyPropertyChanged
	{
		
		private static System.ComponentModel.PropertyChangingEventArgs emptyChangingEventArgs = new System.ComponentModel.PropertyChangingEventArgs("");
		
		private string _regionID;
		
		private string _secureSessionID;
		
		private string _sessionID;
		
		private string _userID;
		
		#region Extensibility Method Declarations
		partial void OnCreated();
		
		partial void OnRegionIDChanged();
		
		partial void OnRegionIDChanging(string value);
		
		partial void OnSecureSessionIDChanged();
		
		partial void OnSecureSessionIDChanging(string value);
		
		partial void OnSessionIDChanged();
		
		partial void OnSessionIDChanging(string value);
		
		partial void OnUserIDChanged();
		
		partial void OnUserIDChanging(string value);
		#endregion
		
		
		public presence()
		{
			this.OnCreated();
		}
		
		[Column(Storage="_regionID", Name="RegionID", DbType="char(36)", AutoSync=AutoSync.Never, CanBeNull=false)]
		[DebuggerNonUserCode()]
		public string RegionID
		{
			get
			{
				return this._regionID;
			}
			set
			{
				if (((_regionID == value) 
							== false))
				{
					this.OnRegionIDChanging(value);
					this.SendPropertyChanging();
					this._regionID = value;
					this.SendPropertyChanged("RegionID");
					this.OnRegionIDChanged();
				}
			}
		}
		
		[Column(Storage="_secureSessionID", Name="SecureSessionID", DbType="char(36)", AutoSync=AutoSync.Never, CanBeNull=false)]
		[DebuggerNonUserCode()]
		public string SecureSessionID
		{
			get
			{
				return this._secureSessionID;
			}
			set
			{
				if (((_secureSessionID == value) 
							== false))
				{
					this.OnSecureSessionIDChanging(value);
					this.SendPropertyChanging();
					this._secureSessionID = value;
					this.SendPropertyChanged("SecureSessionID");
					this.OnSecureSessionIDChanged();
				}
			}
		}
		
		[Column(Storage="_sessionID", Name="SessionID", DbType="char(36)", IsPrimaryKey=true, AutoSync=AutoSync.Never, CanBeNull=false)]
		[DebuggerNonUserCode()]
		public string SessionID
		{
			get
			{
				return this._sessionID;
			}
			set
			{
				if (((_sessionID == value) 
							== false))
				{
					this.OnSessionIDChanging(value);
					this.SendPropertyChanging();
					this._sessionID = value;
					this.SendPropertyChanged("SessionID");
					this.OnSessionIDChanged();
				}
			}
		}
		
		[Column(Storage="_userID", Name="UserID", DbType="varchar(255)", AutoSync=AutoSync.Never, CanBeNull=false)]
		[DebuggerNonUserCode()]
		public string UserID
		{
			get
			{
				return this._userID;
			}
			set
			{
				if (((_userID == value) 
							== false))
				{
					this.OnUserIDChanging(value);
					this.SendPropertyChanging();
					this._userID = value;
					this.SendPropertyChanged("UserID");
					this.OnUserIDChanged();
				}
			}
		}
		
		public event System.ComponentModel.PropertyChangingEventHandler PropertyChanging;
		
		public event System.ComponentModel.PropertyChangedEventHandler PropertyChanged;
		
		protected virtual void SendPropertyChanging()
		{
			System.ComponentModel.PropertyChangingEventHandler h = this.PropertyChanging;
			if ((h != null))
			{
				h(this, emptyChangingEventArgs);
			}
		}
		
		protected virtual void SendPropertyChanged(string propertyName)
		{
			System.ComponentModel.PropertyChangedEventHandler h = this.PropertyChanged;
			if ((h != null))
			{
				h(this, new System.ComponentModel.PropertyChangedEventArgs(propertyName));
			}
		}
	}
	
	[Table(Name="primitems")]
	public partial class primitems : System.ComponentModel.INotifyPropertyChanging, System.ComponentModel.INotifyPropertyChanged
	{
		
		private static System.ComponentModel.PropertyChangingEventArgs emptyChangingEventArgs = new System.ComponentModel.PropertyChangingEventArgs("");
		
		private string _assetID;
		
		private System.Nullable<int> _assetType;
		
		private System.Nullable<int> _basePermissions;
		
		private System.Nullable<long> _creationDate;
		
		private string _creatorID;
		
		private System.Nullable<int> _currentPermissions;
		
		private string _description;
		
		private System.Nullable<int> _everyonePermissions;
		
		private int _flags;
		
		private string _groupID;
		
		private System.Nullable<int> _groupPermissions;
		
		private System.Nullable<int> _invType;
		
		private string _itemID;
		
		private string _lastOwnerID;
		
		private string _name;
		
		private System.Nullable<int> _nextPermissions;
		
		private string _ownerID;
		
		private string _parentFolderID;
		
		private string _primID;
		
		#region Extensibility Method Declarations
		partial void OnCreated();
		
		partial void OnassetIDChanged();
		
		partial void OnassetIDChanging(string value);
		
		partial void OnassetTypeChanged();
		
		partial void OnassetTypeChanging(System.Nullable<int> value);
		
		partial void OnbasePermissionsChanged();
		
		partial void OnbasePermissionsChanging(System.Nullable<int> value);
		
		partial void OncreationDateChanged();
		
		partial void OncreationDateChanging(System.Nullable<long> value);
		
		partial void OncreatorIDChanged();
		
		partial void OncreatorIDChanging(string value);
		
		partial void OncurrentPermissionsChanged();
		
		partial void OncurrentPermissionsChanging(System.Nullable<int> value);
		
		partial void OndescriptionChanged();
		
		partial void OndescriptionChanging(string value);
		
		partial void OneveryonePermissionsChanged();
		
		partial void OneveryonePermissionsChanging(System.Nullable<int> value);
		
		partial void OnflagsChanged();
		
		partial void OnflagsChanging(int value);
		
		partial void OngroupIDChanged();
		
		partial void OngroupIDChanging(string value);
		
		partial void OngroupPermissionsChanged();
		
		partial void OngroupPermissionsChanging(System.Nullable<int> value);
		
		partial void OninvTypeChanged();
		
		partial void OninvTypeChanging(System.Nullable<int> value);
		
		partial void OnitemIDChanged();
		
		partial void OnitemIDChanging(string value);
		
		partial void OnlastOwnerIDChanged();
		
		partial void OnlastOwnerIDChanging(string value);
		
		partial void OnnameChanged();
		
		partial void OnnameChanging(string value);
		
		partial void OnnextPermissionsChanged();
		
		partial void OnnextPermissionsChanging(System.Nullable<int> value);
		
		partial void OnownerIDChanged();
		
		partial void OnownerIDChanging(string value);
		
		partial void OnparentFolderIDChanged();
		
		partial void OnparentFolderIDChanging(string value);
		
		partial void OnprimIDChanged();
		
		partial void OnprimIDChanging(string value);
		#endregion
		
		
		public primitems()
		{
			this.OnCreated();
		}
		
		[Column(Storage="_assetID", Name="assetID", DbType="char(36)", AutoSync=AutoSync.Never)]
		[DebuggerNonUserCode()]
		public string assetID
		{
			get
			{
				return this._assetID;
			}
			set
			{
				if (((_assetID == value) 
							== false))
				{
					this.OnassetIDChanging(value);
					this.SendPropertyChanging();
					this._assetID = value;
					this.SendPropertyChanged("assetID");
					this.OnassetIDChanged();
				}
			}
		}
		
		[Column(Storage="_assetType", Name="assetType", DbType="int", AutoSync=AutoSync.Never)]
		[DebuggerNonUserCode()]
		public System.Nullable<int> assetType
		{
			get
			{
				return this._assetType;
			}
			set
			{
				if ((_assetType != value))
				{
					this.OnassetTypeChanging(value);
					this.SendPropertyChanging();
					this._assetType = value;
					this.SendPropertyChanged("assetType");
					this.OnassetTypeChanged();
				}
			}
		}
		
		[Column(Storage="_basePermissions", Name="basePermissions", DbType="int", AutoSync=AutoSync.Never)]
		[DebuggerNonUserCode()]
		public System.Nullable<int> basePermissions
		{
			get
			{
				return this._basePermissions;
			}
			set
			{
				if ((_basePermissions != value))
				{
					this.OnbasePermissionsChanging(value);
					this.SendPropertyChanging();
					this._basePermissions = value;
					this.SendPropertyChanged("basePermissions");
					this.OnbasePermissionsChanged();
				}
			}
		}
		
		[Column(Storage="_creationDate", Name="creationDate", DbType="bigint(20)", AutoSync=AutoSync.Never)]
		[DebuggerNonUserCode()]
		public System.Nullable<long> creationDate
		{
			get
			{
				return this._creationDate;
			}
			set
			{
				if ((_creationDate != value))
				{
					this.OncreationDateChanging(value);
					this.SendPropertyChanging();
					this._creationDate = value;
					this.SendPropertyChanged("creationDate");
					this.OncreationDateChanged();
				}
			}
		}
		
		[Column(Storage="_creatorID", Name="creatorID", DbType="char(36)", AutoSync=AutoSync.Never)]
		[DebuggerNonUserCode()]
		public string creatorID
		{
			get
			{
				return this._creatorID;
			}
			set
			{
				if (((_creatorID == value) 
							== false))
				{
					this.OncreatorIDChanging(value);
					this.SendPropertyChanging();
					this._creatorID = value;
					this.SendPropertyChanged("creatorID");
					this.OncreatorIDChanged();
				}
			}
		}
		
		[Column(Storage="_currentPermissions", Name="currentPermissions", DbType="int", AutoSync=AutoSync.Never)]
		[DebuggerNonUserCode()]
		public System.Nullable<int> currentPermissions
		{
			get
			{
				return this._currentPermissions;
			}
			set
			{
				if ((_currentPermissions != value))
				{
					this.OncurrentPermissionsChanging(value);
					this.SendPropertyChanging();
					this._currentPermissions = value;
					this.SendPropertyChanged("currentPermissions");
					this.OncurrentPermissionsChanged();
				}
			}
		}
		
		[Column(Storage="_description", Name="description", DbType="varchar(255)", AutoSync=AutoSync.Never)]
		[DebuggerNonUserCode()]
		public string description
		{
			get
			{
				return this._description;
			}
			set
			{
				if (((_description == value) 
							== false))
				{
					this.OndescriptionChanging(value);
					this.SendPropertyChanging();
					this._description = value;
					this.SendPropertyChanged("description");
					this.OndescriptionChanged();
				}
			}
		}
		
		[Column(Storage="_everyonePermissions", Name="everyonePermissions", DbType="int", AutoSync=AutoSync.Never)]
		[DebuggerNonUserCode()]
		public System.Nullable<int> everyonePermissions
		{
			get
			{
				return this._everyonePermissions;
			}
			set
			{
				if ((_everyonePermissions != value))
				{
					this.OneveryonePermissionsChanging(value);
					this.SendPropertyChanging();
					this._everyonePermissions = value;
					this.SendPropertyChanged("everyonePermissions");
					this.OneveryonePermissionsChanged();
				}
			}
		}
		
		[Column(Storage="_flags", Name="flags", DbType="int", AutoSync=AutoSync.Never, CanBeNull=false)]
		[DebuggerNonUserCode()]
		public int flags
		{
			get
			{
				return this._flags;
			}
			set
			{
				if ((_flags != value))
				{
					this.OnflagsChanging(value);
					this.SendPropertyChanging();
					this._flags = value;
					this.SendPropertyChanged("flags");
					this.OnflagsChanged();
				}
			}
		}
		
		[Column(Storage="_groupID", Name="groupID", DbType="char(36)", AutoSync=AutoSync.Never)]
		[DebuggerNonUserCode()]
		public string groupID
		{
			get
			{
				return this._groupID;
			}
			set
			{
				if (((_groupID == value) 
							== false))
				{
					this.OngroupIDChanging(value);
					this.SendPropertyChanging();
					this._groupID = value;
					this.SendPropertyChanged("groupID");
					this.OngroupIDChanged();
				}
			}
		}
		
		[Column(Storage="_groupPermissions", Name="groupPermissions", DbType="int", AutoSync=AutoSync.Never)]
		[DebuggerNonUserCode()]
		public System.Nullable<int> groupPermissions
		{
			get
			{
				return this._groupPermissions;
			}
			set
			{
				if ((_groupPermissions != value))
				{
					this.OngroupPermissionsChanging(value);
					this.SendPropertyChanging();
					this._groupPermissions = value;
					this.SendPropertyChanged("groupPermissions");
					this.OngroupPermissionsChanged();
				}
			}
		}
		
		[Column(Storage="_invType", Name="invType", DbType="int", AutoSync=AutoSync.Never)]
		[DebuggerNonUserCode()]
		public System.Nullable<int> invType
		{
			get
			{
				return this._invType;
			}
			set
			{
				if ((_invType != value))
				{
					this.OninvTypeChanging(value);
					this.SendPropertyChanging();
					this._invType = value;
					this.SendPropertyChanged("invType");
					this.OninvTypeChanged();
				}
			}
		}
		
		[Column(Storage="_itemID", Name="itemID", DbType="char(36)", IsPrimaryKey=true, AutoSync=AutoSync.Never, CanBeNull=false)]
		[DebuggerNonUserCode()]
		public string itemID
		{
			get
			{
				return this._itemID;
			}
			set
			{
				if (((_itemID == value) 
							== false))
				{
					this.OnitemIDChanging(value);
					this.SendPropertyChanging();
					this._itemID = value;
					this.SendPropertyChanged("itemID");
					this.OnitemIDChanged();
				}
			}
		}
		
		[Column(Storage="_lastOwnerID", Name="lastOwnerID", DbType="char(36)", AutoSync=AutoSync.Never)]
		[DebuggerNonUserCode()]
		public string lastOwnerID
		{
			get
			{
				return this._lastOwnerID;
			}
			set
			{
				if (((_lastOwnerID == value) 
							== false))
				{
					this.OnlastOwnerIDChanging(value);
					this.SendPropertyChanging();
					this._lastOwnerID = value;
					this.SendPropertyChanged("lastOwnerID");
					this.OnlastOwnerIDChanged();
				}
			}
		}
		
		[Column(Storage="_name", Name="name", DbType="varchar(255)", AutoSync=AutoSync.Never)]
		[DebuggerNonUserCode()]
		public string name
		{
			get
			{
				return this._name;
			}
			set
			{
				if (((_name == value) 
							== false))
				{
					this.OnnameChanging(value);
					this.SendPropertyChanging();
					this._name = value;
					this.SendPropertyChanged("name");
					this.OnnameChanged();
				}
			}
		}
		
		[Column(Storage="_nextPermissions", Name="nextPermissions", DbType="int", AutoSync=AutoSync.Never)]
		[DebuggerNonUserCode()]
		public System.Nullable<int> nextPermissions
		{
			get
			{
				return this._nextPermissions;
			}
			set
			{
				if ((_nextPermissions != value))
				{
					this.OnnextPermissionsChanging(value);
					this.SendPropertyChanging();
					this._nextPermissions = value;
					this.SendPropertyChanged("nextPermissions");
					this.OnnextPermissionsChanged();
				}
			}
		}
		
		[Column(Storage="_ownerID", Name="ownerID", DbType="char(36)", AutoSync=AutoSync.Never)]
		[DebuggerNonUserCode()]
		public string ownerID
		{
			get
			{
				return this._ownerID;
			}
			set
			{
				if (((_ownerID == value) 
							== false))
				{
					this.OnownerIDChanging(value);
					this.SendPropertyChanging();
					this._ownerID = value;
					this.SendPropertyChanged("ownerID");
					this.OnownerIDChanged();
				}
			}
		}
		
		[Column(Storage="_parentFolderID", Name="parentFolderID", DbType="char(36)", AutoSync=AutoSync.Never)]
		[DebuggerNonUserCode()]
		public string parentFolderID
		{
			get
			{
				return this._parentFolderID;
			}
			set
			{
				if (((_parentFolderID == value) 
							== false))
				{
					this.OnparentFolderIDChanging(value);
					this.SendPropertyChanging();
					this._parentFolderID = value;
					this.SendPropertyChanged("parentFolderID");
					this.OnparentFolderIDChanged();
				}
			}
		}
		
		[Column(Storage="_primID", Name="primID", DbType="char(36)", AutoSync=AutoSync.Never)]
		[DebuggerNonUserCode()]
		public string primID
		{
			get
			{
				return this._primID;
			}
			set
			{
				if (((_primID == value) 
							== false))
				{
					this.OnprimIDChanging(value);
					this.SendPropertyChanging();
					this._primID = value;
					this.SendPropertyChanged("primID");
					this.OnprimIDChanged();
				}
			}
		}
		
		public event System.ComponentModel.PropertyChangingEventHandler PropertyChanging;
		
		public event System.ComponentModel.PropertyChangedEventHandler PropertyChanged;
		
		protected virtual void SendPropertyChanging()
		{
			System.ComponentModel.PropertyChangingEventHandler h = this.PropertyChanging;
			if ((h != null))
			{
				h(this, emptyChangingEventArgs);
			}
		}
		
		protected virtual void SendPropertyChanged(string propertyName)
		{
			System.ComponentModel.PropertyChangedEventHandler h = this.PropertyChanged;
			if ((h != null))
			{
				h(this, new System.ComponentModel.PropertyChangedEventArgs(propertyName));
			}
		}
	}
	
	[Table(Name="prims")]
	public partial class prims : System.ComponentModel.INotifyPropertyChanging, System.ComponentModel.INotifyPropertyChanged
	{
		
		private static System.ComponentModel.PropertyChangingEventArgs emptyChangingEventArgs = new System.ComponentModel.PropertyChangingEventArgs("");
		
		private System.Nullable<double> _accelerationX;
		
		private System.Nullable<double> _accelerationY;
		
		private System.Nullable<double> _accelerationZ;
		
		private sbyte _allowedDrop;
		
		private System.Nullable<double> _angularVelocityX;
		
		private System.Nullable<double> _angularVelocityY;
		
		private System.Nullable<double> _angularVelocityZ;
		
		private System.Nullable<int> _baseMask;
		
		private double _cameraAtOffsetX;
		
		private double _cameraAtOffsetY;
		
		private double _cameraAtOffsetZ;
		
		private double _cameraEyeOffsetX;
		
		private double _cameraEyeOffsetY;
		
		private double _cameraEyeOffsetZ;
		
		private sbyte _clickAction;
		
		private string _collisionSound;
		
		private double _collisionSoundVolume;
		
		private int _colorA;
		
		private int _colorB;
		
		private int _colorG;
		
		private int _colorR;
		
		private System.Nullable<int> _creationDate;
		
		private string _creatorID;
		
		private string _description;
		
		private sbyte _dieAtEdge;
		
		private System.Nullable<int> _everyoneMask;
		
		private sbyte _forceMouselook;
		
		private string _groupID;
		
		private System.Nullable<int> _groupMask;
		
		private System.Nullable<double> _groupPositionX;
		
		private System.Nullable<double> _groupPositionY;
		
		private System.Nullable<double> _groupPositionZ;
		
		private string _lastOwnerID;
		
		private int _linkNumber;
		
		private string _loopedSound;
		
		private double _loopedSoundGain;
		
		private sbyte _material;
		
		private string _name;
		
		private System.Nullable<int> _nextOwnerMask;
		
		private System.Nullable<int> _objectFlags;
		
		private double _omegaX;
		
		private double _omegaY;
		
		private double _omegaZ;
		
		private string _ownerID;
		
		private System.Nullable<int> _ownerMask;
		
		private byte[] _particleSystem;
		
		private sbyte _passTouches;
		
		private int _payButton1;
		
		private int _payButton2;
		
		private int _payButton3;
		
		private int _payButton4;
		
		private int _payPrice;
		
		private System.Nullable<double> _positionX;
		
		private System.Nullable<double> _positionY;
		
		private System.Nullable<double> _positionZ;
		
		private string _regionUuid;
		
		private System.Nullable<double> _rotationW;
		
		private System.Nullable<double> _rotationX;
		
		private System.Nullable<double> _rotationY;
		
		private System.Nullable<double> _rotationZ;
		
		private int _salePrice;
		
		private sbyte _saleType;
		
		private string _sceneGroupID;
		
		private int _scriptAccessPin;
		
		private string _sitName;
		
		private System.Nullable<double> _sitTargetOffsetX;
		
		private System.Nullable<double> _sitTargetOffsetY;
		
		private System.Nullable<double> _sitTargetOffsetZ;
		
		private System.Nullable<double> _sitTargetOrientW;
		
		private System.Nullable<double> _sitTargetOrientX;
		
		private System.Nullable<double> _sitTargetOrientY;
		
		private System.Nullable<double> _sitTargetOrientZ;
		
		private string _text;
		
		private byte[] _textureAnimation;
		
		private string _touchName;
		
		private string _uuid;
		
		private System.Nullable<double> _velocityX;
		
		private System.Nullable<double> _velocityY;
		
		private System.Nullable<double> _velocityZ;
		
		#region Extensibility Method Declarations
		partial void OnCreated();
		
		partial void OnAccelerationXChanged();
		
		partial void OnAccelerationXChanging(System.Nullable<double> value);
		
		partial void OnAccelerationYChanged();
		
		partial void OnAccelerationYChanging(System.Nullable<double> value);
		
		partial void OnAccelerationZChanged();
		
		partial void OnAccelerationZChanging(System.Nullable<double> value);
		
		partial void OnAllowedDropChanged();
		
		partial void OnAllowedDropChanging(sbyte value);
		
		partial void OnAngularVelocityXChanged();
		
		partial void OnAngularVelocityXChanging(System.Nullable<double> value);
		
		partial void OnAngularVelocityYChanged();
		
		partial void OnAngularVelocityYChanging(System.Nullable<double> value);
		
		partial void OnAngularVelocityZChanged();
		
		partial void OnAngularVelocityZChanging(System.Nullable<double> value);
		
		partial void OnBaseMaskChanged();
		
		partial void OnBaseMaskChanging(System.Nullable<int> value);
		
		partial void OnCameraAtOffsetXChanged();
		
		partial void OnCameraAtOffsetXChanging(double value);
		
		partial void OnCameraAtOffsetYChanged();
		
		partial void OnCameraAtOffsetYChanging(double value);
		
		partial void OnCameraAtOffsetZChanged();
		
		partial void OnCameraAtOffsetZChanging(double value);
		
		partial void OnCameraEyeOffsetXChanged();
		
		partial void OnCameraEyeOffsetXChanging(double value);
		
		partial void OnCameraEyeOffsetYChanged();
		
		partial void OnCameraEyeOffsetYChanging(double value);
		
		partial void OnCameraEyeOffsetZChanged();
		
		partial void OnCameraEyeOffsetZChanging(double value);
		
		partial void OnClickActionChanged();
		
		partial void OnClickActionChanging(sbyte value);
		
		partial void OnCollisionSoundChanged();
		
		partial void OnCollisionSoundChanging(string value);
		
		partial void OnCollisionSoundVolumeChanged();
		
		partial void OnCollisionSoundVolumeChanging(double value);
		
		partial void OnColorAChanged();
		
		partial void OnColorAChanging(int value);
		
		partial void OnColorBChanged();
		
		partial void OnColorBChanging(int value);
		
		partial void OnColorGChanged();
		
		partial void OnColorGChanging(int value);
		
		partial void OnColorRChanged();
		
		partial void OnColorRChanging(int value);
		
		partial void OnCreationDateChanged();
		
		partial void OnCreationDateChanging(System.Nullable<int> value);
		
		partial void OnCreatorIDChanged();
		
		partial void OnCreatorIDChanging(string value);
		
		partial void OnDescriptionChanged();
		
		partial void OnDescriptionChanging(string value);
		
		partial void OnDieAtEdgeChanged();
		
		partial void OnDieAtEdgeChanging(sbyte value);
		
		partial void OnEveryoneMaskChanged();
		
		partial void OnEveryoneMaskChanging(System.Nullable<int> value);
		
		partial void OnForceMouselookChanged();
		
		partial void OnForceMouselookChanging(sbyte value);
		
		partial void OnGroupIDChanged();
		
		partial void OnGroupIDChanging(string value);
		
		partial void OnGroupMaskChanged();
		
		partial void OnGroupMaskChanging(System.Nullable<int> value);
		
		partial void OnGroupPositionXChanged();
		
		partial void OnGroupPositionXChanging(System.Nullable<double> value);
		
		partial void OnGroupPositionYChanged();
		
		partial void OnGroupPositionYChanging(System.Nullable<double> value);
		
		partial void OnGroupPositionZChanged();
		
		partial void OnGroupPositionZChanging(System.Nullable<double> value);
		
		partial void OnLastOwnerIDChanged();
		
		partial void OnLastOwnerIDChanging(string value);
		
		partial void OnLinkNumberChanged();
		
		partial void OnLinkNumberChanging(int value);
		
		partial void OnLoopedSoundChanged();
		
		partial void OnLoopedSoundChanging(string value);
		
		partial void OnLoopedSoundGainChanged();
		
		partial void OnLoopedSoundGainChanging(double value);
		
		partial void OnMaterialChanged();
		
		partial void OnMaterialChanging(sbyte value);
		
		partial void OnNameChanged();
		
		partial void OnNameChanging(string value);
		
		partial void OnNextOwnerMaskChanged();
		
		partial void OnNextOwnerMaskChanging(System.Nullable<int> value);
		
		partial void OnObjectFlagsChanged();
		
		partial void OnObjectFlagsChanging(System.Nullable<int> value);
		
		partial void OnOmegaXChanged();
		
		partial void OnOmegaXChanging(double value);
		
		partial void OnOmegaYChanged();
		
		partial void OnOmegaYChanging(double value);
		
		partial void OnOmegaZChanged();
		
		partial void OnOmegaZChanging(double value);
		
		partial void OnOwnerIDChanged();
		
		partial void OnOwnerIDChanging(string value);
		
		partial void OnOwnerMaskChanged();
		
		partial void OnOwnerMaskChanging(System.Nullable<int> value);
		
		partial void OnParticleSystemChanged();
		
		partial void OnParticleSystemChanging(byte[] value);
		
		partial void OnPassTouchesChanged();
		
		partial void OnPassTouchesChanging(sbyte value);
		
		partial void OnPayButton1Changed();
		
		partial void OnPayButton1Changing(int value);
		
		partial void OnPayButton2Changed();
		
		partial void OnPayButton2Changing(int value);
		
		partial void OnPayButton3Changed();
		
		partial void OnPayButton3Changing(int value);
		
		partial void OnPayButton4Changed();
		
		partial void OnPayButton4Changing(int value);
		
		partial void OnPayPriceChanged();
		
		partial void OnPayPriceChanging(int value);
		
		partial void OnPositionXChanged();
		
		partial void OnPositionXChanging(System.Nullable<double> value);
		
		partial void OnPositionYChanged();
		
		partial void OnPositionYChanging(System.Nullable<double> value);
		
		partial void OnPositionZChanged();
		
		partial void OnPositionZChanging(System.Nullable<double> value);
		
		partial void OnRegionUUIDChanged();
		
		partial void OnRegionUUIDChanging(string value);
		
		partial void OnRotationWChanged();
		
		partial void OnRotationWChanging(System.Nullable<double> value);
		
		partial void OnRotationXChanged();
		
		partial void OnRotationXChanging(System.Nullable<double> value);
		
		partial void OnRotationYChanged();
		
		partial void OnRotationYChanging(System.Nullable<double> value);
		
		partial void OnRotationZChanged();
		
		partial void OnRotationZChanging(System.Nullable<double> value);
		
		partial void OnSalePriceChanged();
		
		partial void OnSalePriceChanging(int value);
		
		partial void OnSaleTypeChanged();
		
		partial void OnSaleTypeChanging(sbyte value);
		
		partial void OnSceneGroupIDChanged();
		
		partial void OnSceneGroupIDChanging(string value);
		
		partial void OnScriptAccessPinChanged();
		
		partial void OnScriptAccessPinChanging(int value);
		
		partial void OnSitNameChanged();
		
		partial void OnSitNameChanging(string value);
		
		partial void OnSitTargetOffsetXChanged();
		
		partial void OnSitTargetOffsetXChanging(System.Nullable<double> value);
		
		partial void OnSitTargetOffsetYChanged();
		
		partial void OnSitTargetOffsetYChanging(System.Nullable<double> value);
		
		partial void OnSitTargetOffsetZChanged();
		
		partial void OnSitTargetOffsetZChanging(System.Nullable<double> value);
		
		partial void OnSitTargetOrientWChanged();
		
		partial void OnSitTargetOrientWChanging(System.Nullable<double> value);
		
		partial void OnSitTargetOrientXChanged();
		
		partial void OnSitTargetOrientXChanging(System.Nullable<double> value);
		
		partial void OnSitTargetOrientYChanged();
		
		partial void OnSitTargetOrientYChanging(System.Nullable<double> value);
		
		partial void OnSitTargetOrientZChanged();
		
		partial void OnSitTargetOrientZChanging(System.Nullable<double> value);
		
		partial void OnTextChanged();
		
		partial void OnTextChanging(string value);
		
		partial void OnTextureAnimationChanged();
		
		partial void OnTextureAnimationChanging(byte[] value);
		
		partial void OnTouchNameChanged();
		
		partial void OnTouchNameChanging(string value);
		
		partial void OnUUIDChanged();
		
		partial void OnUUIDChanging(string value);
		
		partial void OnVelocityXChanged();
		
		partial void OnVelocityXChanging(System.Nullable<double> value);
		
		partial void OnVelocityYChanged();
		
		partial void OnVelocityYChanging(System.Nullable<double> value);
		
		partial void OnVelocityZChanged();
		
		partial void OnVelocityZChanging(System.Nullable<double> value);
		#endregion
		
		
		public prims()
		{
			this.OnCreated();
		}
		
		[Column(Storage="_accelerationX", Name="AccelerationX", DbType="double", AutoSync=AutoSync.Never)]
		[DebuggerNonUserCode()]
		public System.Nullable<double> AccelerationX
		{
			get
			{
				return this._accelerationX;
			}
			set
			{
				if ((_accelerationX != value))
				{
					this.OnAccelerationXChanging(value);
					this.SendPropertyChanging();
					this._accelerationX = value;
					this.SendPropertyChanged("AccelerationX");
					this.OnAccelerationXChanged();
				}
			}
		}
		
		[Column(Storage="_accelerationY", Name="AccelerationY", DbType="double", AutoSync=AutoSync.Never)]
		[DebuggerNonUserCode()]
		public System.Nullable<double> AccelerationY
		{
			get
			{
				return this._accelerationY;
			}
			set
			{
				if ((_accelerationY != value))
				{
					this.OnAccelerationYChanging(value);
					this.SendPropertyChanging();
					this._accelerationY = value;
					this.SendPropertyChanged("AccelerationY");
					this.OnAccelerationYChanged();
				}
			}
		}
		
		[Column(Storage="_accelerationZ", Name="AccelerationZ", DbType="double", AutoSync=AutoSync.Never)]
		[DebuggerNonUserCode()]
		public System.Nullable<double> AccelerationZ
		{
			get
			{
				return this._accelerationZ;
			}
			set
			{
				if ((_accelerationZ != value))
				{
					this.OnAccelerationZChanging(value);
					this.SendPropertyChanging();
					this._accelerationZ = value;
					this.SendPropertyChanged("AccelerationZ");
					this.OnAccelerationZChanged();
				}
			}
		}
		
		[Column(Storage="_allowedDrop", Name="AllowedDrop", DbType="tinyint(4)", AutoSync=AutoSync.Never, CanBeNull=false)]
		[DebuggerNonUserCode()]
		public sbyte AllowedDrop
		{
			get
			{
				return this._allowedDrop;
			}
			set
			{
				if ((_allowedDrop != value))
				{
					this.OnAllowedDropChanging(value);
					this.SendPropertyChanging();
					this._allowedDrop = value;
					this.SendPropertyChanged("AllowedDrop");
					this.OnAllowedDropChanged();
				}
			}
		}
		
		[Column(Storage="_angularVelocityX", Name="AngularVelocityX", DbType="double", AutoSync=AutoSync.Never)]
		[DebuggerNonUserCode()]
		public System.Nullable<double> AngularVelocityX
		{
			get
			{
				return this._angularVelocityX;
			}
			set
			{
				if ((_angularVelocityX != value))
				{
					this.OnAngularVelocityXChanging(value);
					this.SendPropertyChanging();
					this._angularVelocityX = value;
					this.SendPropertyChanged("AngularVelocityX");
					this.OnAngularVelocityXChanged();
				}
			}
		}
		
		[Column(Storage="_angularVelocityY", Name="AngularVelocityY", DbType="double", AutoSync=AutoSync.Never)]
		[DebuggerNonUserCode()]
		public System.Nullable<double> AngularVelocityY
		{
			get
			{
				return this._angularVelocityY;
			}
			set
			{
				if ((_angularVelocityY != value))
				{
					this.OnAngularVelocityYChanging(value);
					this.SendPropertyChanging();
					this._angularVelocityY = value;
					this.SendPropertyChanged("AngularVelocityY");
					this.OnAngularVelocityYChanged();
				}
			}
		}
		
		[Column(Storage="_angularVelocityZ", Name="AngularVelocityZ", DbType="double", AutoSync=AutoSync.Never)]
		[DebuggerNonUserCode()]
		public System.Nullable<double> AngularVelocityZ
		{
			get
			{
				return this._angularVelocityZ;
			}
			set
			{
				if ((_angularVelocityZ != value))
				{
					this.OnAngularVelocityZChanging(value);
					this.SendPropertyChanging();
					this._angularVelocityZ = value;
					this.SendPropertyChanged("AngularVelocityZ");
					this.OnAngularVelocityZChanged();
				}
			}
		}
		
		[Column(Storage="_baseMask", Name="BaseMask", DbType="int", AutoSync=AutoSync.Never)]
		[DebuggerNonUserCode()]
		public System.Nullable<int> BaseMask
		{
			get
			{
				return this._baseMask;
			}
			set
			{
				if ((_baseMask != value))
				{
					this.OnBaseMaskChanging(value);
					this.SendPropertyChanging();
					this._baseMask = value;
					this.SendPropertyChanged("BaseMask");
					this.OnBaseMaskChanged();
				}
			}
		}
		
		[Column(Storage="_cameraAtOffsetX", Name="CameraAtOffsetX", DbType="double", AutoSync=AutoSync.Never, CanBeNull=false)]
		[DebuggerNonUserCode()]
		public double CameraAtOffsetX
		{
			get
			{
				return this._cameraAtOffsetX;
			}
			set
			{
				if ((_cameraAtOffsetX != value))
				{
					this.OnCameraAtOffsetXChanging(value);
					this.SendPropertyChanging();
					this._cameraAtOffsetX = value;
					this.SendPropertyChanged("CameraAtOffsetX");
					this.OnCameraAtOffsetXChanged();
				}
			}
		}
		
		[Column(Storage="_cameraAtOffsetY", Name="CameraAtOffsetY", DbType="double", AutoSync=AutoSync.Never, CanBeNull=false)]
		[DebuggerNonUserCode()]
		public double CameraAtOffsetY
		{
			get
			{
				return this._cameraAtOffsetY;
			}
			set
			{
				if ((_cameraAtOffsetY != value))
				{
					this.OnCameraAtOffsetYChanging(value);
					this.SendPropertyChanging();
					this._cameraAtOffsetY = value;
					this.SendPropertyChanged("CameraAtOffsetY");
					this.OnCameraAtOffsetYChanged();
				}
			}
		}
		
		[Column(Storage="_cameraAtOffsetZ", Name="CameraAtOffsetZ", DbType="double", AutoSync=AutoSync.Never, CanBeNull=false)]
		[DebuggerNonUserCode()]
		public double CameraAtOffsetZ
		{
			get
			{
				return this._cameraAtOffsetZ;
			}
			set
			{
				if ((_cameraAtOffsetZ != value))
				{
					this.OnCameraAtOffsetZChanging(value);
					this.SendPropertyChanging();
					this._cameraAtOffsetZ = value;
					this.SendPropertyChanged("CameraAtOffsetZ");
					this.OnCameraAtOffsetZChanged();
				}
			}
		}
		
		[Column(Storage="_cameraEyeOffsetX", Name="CameraEyeOffsetX", DbType="double", AutoSync=AutoSync.Never, CanBeNull=false)]
		[DebuggerNonUserCode()]
		public double CameraEyeOffsetX
		{
			get
			{
				return this._cameraEyeOffsetX;
			}
			set
			{
				if ((_cameraEyeOffsetX != value))
				{
					this.OnCameraEyeOffsetXChanging(value);
					this.SendPropertyChanging();
					this._cameraEyeOffsetX = value;
					this.SendPropertyChanged("CameraEyeOffsetX");
					this.OnCameraEyeOffsetXChanged();
				}
			}
		}
		
		[Column(Storage="_cameraEyeOffsetY", Name="CameraEyeOffsetY", DbType="double", AutoSync=AutoSync.Never, CanBeNull=false)]
		[DebuggerNonUserCode()]
		public double CameraEyeOffsetY
		{
			get
			{
				return this._cameraEyeOffsetY;
			}
			set
			{
				if ((_cameraEyeOffsetY != value))
				{
					this.OnCameraEyeOffsetYChanging(value);
					this.SendPropertyChanging();
					this._cameraEyeOffsetY = value;
					this.SendPropertyChanged("CameraEyeOffsetY");
					this.OnCameraEyeOffsetYChanged();
				}
			}
		}
		
		[Column(Storage="_cameraEyeOffsetZ", Name="CameraEyeOffsetZ", DbType="double", AutoSync=AutoSync.Never, CanBeNull=false)]
		[DebuggerNonUserCode()]
		public double CameraEyeOffsetZ
		{
			get
			{
				return this._cameraEyeOffsetZ;
			}
			set
			{
				if ((_cameraEyeOffsetZ != value))
				{
					this.OnCameraEyeOffsetZChanging(value);
					this.SendPropertyChanging();
					this._cameraEyeOffsetZ = value;
					this.SendPropertyChanged("CameraEyeOffsetZ");
					this.OnCameraEyeOffsetZChanged();
				}
			}
		}
		
		[Column(Storage="_clickAction", Name="ClickAction", DbType="tinyint(4)", AutoSync=AutoSync.Never, CanBeNull=false)]
		[DebuggerNonUserCode()]
		public sbyte ClickAction
		{
			get
			{
				return this._clickAction;
			}
			set
			{
				if ((_clickAction != value))
				{
					this.OnClickActionChanging(value);
					this.SendPropertyChanging();
					this._clickAction = value;
					this.SendPropertyChanged("ClickAction");
					this.OnClickActionChanged();
				}
			}
		}
		
		[Column(Storage="_collisionSound", Name="CollisionSound", DbType="char(36)", AutoSync=AutoSync.Never, CanBeNull=false)]
		[DebuggerNonUserCode()]
		public string CollisionSound
		{
			get
			{
				return this._collisionSound;
			}
			set
			{
				if (((_collisionSound == value) 
							== false))
				{
					this.OnCollisionSoundChanging(value);
					this.SendPropertyChanging();
					this._collisionSound = value;
					this.SendPropertyChanged("CollisionSound");
					this.OnCollisionSoundChanged();
				}
			}
		}
		
		[Column(Storage="_collisionSoundVolume", Name="CollisionSoundVolume", DbType="double", AutoSync=AutoSync.Never, CanBeNull=false)]
		[DebuggerNonUserCode()]
		public double CollisionSoundVolume
		{
			get
			{
				return this._collisionSoundVolume;
			}
			set
			{
				if ((_collisionSoundVolume != value))
				{
					this.OnCollisionSoundVolumeChanging(value);
					this.SendPropertyChanging();
					this._collisionSoundVolume = value;
					this.SendPropertyChanged("CollisionSoundVolume");
					this.OnCollisionSoundVolumeChanged();
				}
			}
		}
		
		[Column(Storage="_colorA", Name="ColorA", DbType="int", AutoSync=AutoSync.Never, CanBeNull=false)]
		[DebuggerNonUserCode()]
		public int ColorA
		{
			get
			{
				return this._colorA;
			}
			set
			{
				if ((_colorA != value))
				{
					this.OnColorAChanging(value);
					this.SendPropertyChanging();
					this._colorA = value;
					this.SendPropertyChanged("ColorA");
					this.OnColorAChanged();
				}
			}
		}
		
		[Column(Storage="_colorB", Name="ColorB", DbType="int", AutoSync=AutoSync.Never, CanBeNull=false)]
		[DebuggerNonUserCode()]
		public int ColorB
		{
			get
			{
				return this._colorB;
			}
			set
			{
				if ((_colorB != value))
				{
					this.OnColorBChanging(value);
					this.SendPropertyChanging();
					this._colorB = value;
					this.SendPropertyChanged("ColorB");
					this.OnColorBChanged();
				}
			}
		}
		
		[Column(Storage="_colorG", Name="ColorG", DbType="int", AutoSync=AutoSync.Never, CanBeNull=false)]
		[DebuggerNonUserCode()]
		public int ColorG
		{
			get
			{
				return this._colorG;
			}
			set
			{
				if ((_colorG != value))
				{
					this.OnColorGChanging(value);
					this.SendPropertyChanging();
					this._colorG = value;
					this.SendPropertyChanged("ColorG");
					this.OnColorGChanged();
				}
			}
		}
		
		[Column(Storage="_colorR", Name="ColorR", DbType="int", AutoSync=AutoSync.Never, CanBeNull=false)]
		[DebuggerNonUserCode()]
		public int ColorR
		{
			get
			{
				return this._colorR;
			}
			set
			{
				if ((_colorR != value))
				{
					this.OnColorRChanging(value);
					this.SendPropertyChanging();
					this._colorR = value;
					this.SendPropertyChanged("ColorR");
					this.OnColorRChanged();
				}
			}
		}
		
		[Column(Storage="_creationDate", Name="CreationDate", DbType="int", AutoSync=AutoSync.Never)]
		[DebuggerNonUserCode()]
		public System.Nullable<int> CreationDate
		{
			get
			{
				return this._creationDate;
			}
			set
			{
				if ((_creationDate != value))
				{
					this.OnCreationDateChanging(value);
					this.SendPropertyChanging();
					this._creationDate = value;
					this.SendPropertyChanged("CreationDate");
					this.OnCreationDateChanged();
				}
			}
		}
		
		[Column(Storage="_creatorID", Name="CreatorID", DbType="char(36)", AutoSync=AutoSync.Never)]
		[DebuggerNonUserCode()]
		public string CreatorID
		{
			get
			{
				return this._creatorID;
			}
			set
			{
				if (((_creatorID == value) 
							== false))
				{
					this.OnCreatorIDChanging(value);
					this.SendPropertyChanging();
					this._creatorID = value;
					this.SendPropertyChanged("CreatorID");
					this.OnCreatorIDChanged();
				}
			}
		}
		
		[Column(Storage="_description", Name="Description", DbType="varchar(255)", AutoSync=AutoSync.Never)]
		[DebuggerNonUserCode()]
		public string Description
		{
			get
			{
				return this._description;
			}
			set
			{
				if (((_description == value) 
							== false))
				{
					this.OnDescriptionChanging(value);
					this.SendPropertyChanging();
					this._description = value;
					this.SendPropertyChanged("Description");
					this.OnDescriptionChanged();
				}
			}
		}
		
		[Column(Storage="_dieAtEdge", Name="DieAtEdge", DbType="tinyint(4)", AutoSync=AutoSync.Never, CanBeNull=false)]
		[DebuggerNonUserCode()]
		public sbyte DieAtEdge
		{
			get
			{
				return this._dieAtEdge;
			}
			set
			{
				if ((_dieAtEdge != value))
				{
					this.OnDieAtEdgeChanging(value);
					this.SendPropertyChanging();
					this._dieAtEdge = value;
					this.SendPropertyChanged("DieAtEdge");
					this.OnDieAtEdgeChanged();
				}
			}
		}
		
		[Column(Storage="_everyoneMask", Name="EveryoneMask", DbType="int", AutoSync=AutoSync.Never)]
		[DebuggerNonUserCode()]
		public System.Nullable<int> EveryoneMask
		{
			get
			{
				return this._everyoneMask;
			}
			set
			{
				if ((_everyoneMask != value))
				{
					this.OnEveryoneMaskChanging(value);
					this.SendPropertyChanging();
					this._everyoneMask = value;
					this.SendPropertyChanged("EveryoneMask");
					this.OnEveryoneMaskChanged();
				}
			}
		}
		
		[Column(Storage="_forceMouselook", Name="ForceMouselook", DbType="tinyint(4)", AutoSync=AutoSync.Never, CanBeNull=false)]
		[DebuggerNonUserCode()]
		public sbyte ForceMouselook
		{
			get
			{
				return this._forceMouselook;
			}
			set
			{
				if ((_forceMouselook != value))
				{
					this.OnForceMouselookChanging(value);
					this.SendPropertyChanging();
					this._forceMouselook = value;
					this.SendPropertyChanged("ForceMouselook");
					this.OnForceMouselookChanged();
				}
			}
		}
		
		[Column(Storage="_groupID", Name="GroupID", DbType="char(36)", AutoSync=AutoSync.Never)]
		[DebuggerNonUserCode()]
		public string GroupID
		{
			get
			{
				return this._groupID;
			}
			set
			{
				if (((_groupID == value) 
							== false))
				{
					this.OnGroupIDChanging(value);
					this.SendPropertyChanging();
					this._groupID = value;
					this.SendPropertyChanged("GroupID");
					this.OnGroupIDChanged();
				}
			}
		}
		
		[Column(Storage="_groupMask", Name="GroupMask", DbType="int", AutoSync=AutoSync.Never)]
		[DebuggerNonUserCode()]
		public System.Nullable<int> GroupMask
		{
			get
			{
				return this._groupMask;
			}
			set
			{
				if ((_groupMask != value))
				{
					this.OnGroupMaskChanging(value);
					this.SendPropertyChanging();
					this._groupMask = value;
					this.SendPropertyChanged("GroupMask");
					this.OnGroupMaskChanged();
				}
			}
		}
		
		[Column(Storage="_groupPositionX", Name="GroupPositionX", DbType="double", AutoSync=AutoSync.Never)]
		[DebuggerNonUserCode()]
		public System.Nullable<double> GroupPositionX
		{
			get
			{
				return this._groupPositionX;
			}
			set
			{
				if ((_groupPositionX != value))
				{
					this.OnGroupPositionXChanging(value);
					this.SendPropertyChanging();
					this._groupPositionX = value;
					this.SendPropertyChanged("GroupPositionX");
					this.OnGroupPositionXChanged();
				}
			}
		}
		
		[Column(Storage="_groupPositionY", Name="GroupPositionY", DbType="double", AutoSync=AutoSync.Never)]
		[DebuggerNonUserCode()]
		public System.Nullable<double> GroupPositionY
		{
			get
			{
				return this._groupPositionY;
			}
			set
			{
				if ((_groupPositionY != value))
				{
					this.OnGroupPositionYChanging(value);
					this.SendPropertyChanging();
					this._groupPositionY = value;
					this.SendPropertyChanged("GroupPositionY");
					this.OnGroupPositionYChanged();
				}
			}
		}
		
		[Column(Storage="_groupPositionZ", Name="GroupPositionZ", DbType="double", AutoSync=AutoSync.Never)]
		[DebuggerNonUserCode()]
		public System.Nullable<double> GroupPositionZ
		{
			get
			{
				return this._groupPositionZ;
			}
			set
			{
				if ((_groupPositionZ != value))
				{
					this.OnGroupPositionZChanging(value);
					this.SendPropertyChanging();
					this._groupPositionZ = value;
					this.SendPropertyChanged("GroupPositionZ");
					this.OnGroupPositionZChanged();
				}
			}
		}
		
		[Column(Storage="_lastOwnerID", Name="LastOwnerID", DbType="char(36)", AutoSync=AutoSync.Never)]
		[DebuggerNonUserCode()]
		public string LastOwnerID
		{
			get
			{
				return this._lastOwnerID;
			}
			set
			{
				if (((_lastOwnerID == value) 
							== false))
				{
					this.OnLastOwnerIDChanging(value);
					this.SendPropertyChanging();
					this._lastOwnerID = value;
					this.SendPropertyChanged("LastOwnerID");
					this.OnLastOwnerIDChanged();
				}
			}
		}
		
		[Column(Storage="_linkNumber", Name="LinkNumber", DbType="int", AutoSync=AutoSync.Never, CanBeNull=false)]
		[DebuggerNonUserCode()]
		public int LinkNumber
		{
			get
			{
				return this._linkNumber;
			}
			set
			{
				if ((_linkNumber != value))
				{
					this.OnLinkNumberChanging(value);
					this.SendPropertyChanging();
					this._linkNumber = value;
					this.SendPropertyChanged("LinkNumber");
					this.OnLinkNumberChanged();
				}
			}
		}
		
		[Column(Storage="_loopedSound", Name="LoopedSound", DbType="char(36)", AutoSync=AutoSync.Never, CanBeNull=false)]
		[DebuggerNonUserCode()]
		public string LoopedSound
		{
			get
			{
				return this._loopedSound;
			}
			set
			{
				if (((_loopedSound == value) 
							== false))
				{
					this.OnLoopedSoundChanging(value);
					this.SendPropertyChanging();
					this._loopedSound = value;
					this.SendPropertyChanged("LoopedSound");
					this.OnLoopedSoundChanged();
				}
			}
		}
		
		[Column(Storage="_loopedSoundGain", Name="LoopedSoundGain", DbType="double", AutoSync=AutoSync.Never, CanBeNull=false)]
		[DebuggerNonUserCode()]
		public double LoopedSoundGain
		{
			get
			{
				return this._loopedSoundGain;
			}
			set
			{
				if ((_loopedSoundGain != value))
				{
					this.OnLoopedSoundGainChanging(value);
					this.SendPropertyChanging();
					this._loopedSoundGain = value;
					this.SendPropertyChanged("LoopedSoundGain");
					this.OnLoopedSoundGainChanged();
				}
			}
		}
		
		[Column(Storage="_material", Name="Material", DbType="tinyint(4)", AutoSync=AutoSync.Never, CanBeNull=false)]
		[DebuggerNonUserCode()]
		public sbyte Material
		{
			get
			{
				return this._material;
			}
			set
			{
				if ((_material != value))
				{
					this.OnMaterialChanging(value);
					this.SendPropertyChanging();
					this._material = value;
					this.SendPropertyChanged("Material");
					this.OnMaterialChanged();
				}
			}
		}
		
		[Column(Storage="_name", Name="Name", DbType="varchar(255)", AutoSync=AutoSync.Never)]
		[DebuggerNonUserCode()]
		public string Name
		{
			get
			{
				return this._name;
			}
			set
			{
				if (((_name == value) 
							== false))
				{
					this.OnNameChanging(value);
					this.SendPropertyChanging();
					this._name = value;
					this.SendPropertyChanged("Name");
					this.OnNameChanged();
				}
			}
		}
		
		[Column(Storage="_nextOwnerMask", Name="NextOwnerMask", DbType="int", AutoSync=AutoSync.Never)]
		[DebuggerNonUserCode()]
		public System.Nullable<int> NextOwnerMask
		{
			get
			{
				return this._nextOwnerMask;
			}
			set
			{
				if ((_nextOwnerMask != value))
				{
					this.OnNextOwnerMaskChanging(value);
					this.SendPropertyChanging();
					this._nextOwnerMask = value;
					this.SendPropertyChanged("NextOwnerMask");
					this.OnNextOwnerMaskChanged();
				}
			}
		}
		
		[Column(Storage="_objectFlags", Name="ObjectFlags", DbType="int", AutoSync=AutoSync.Never)]
		[DebuggerNonUserCode()]
		public System.Nullable<int> ObjectFlags
		{
			get
			{
				return this._objectFlags;
			}
			set
			{
				if ((_objectFlags != value))
				{
					this.OnObjectFlagsChanging(value);
					this.SendPropertyChanging();
					this._objectFlags = value;
					this.SendPropertyChanged("ObjectFlags");
					this.OnObjectFlagsChanged();
				}
			}
		}
		
		[Column(Storage="_omegaX", Name="OmegaX", DbType="double", AutoSync=AutoSync.Never, CanBeNull=false)]
		[DebuggerNonUserCode()]
		public double OmegaX
		{
			get
			{
				return this._omegaX;
			}
			set
			{
				if ((_omegaX != value))
				{
					this.OnOmegaXChanging(value);
					this.SendPropertyChanging();
					this._omegaX = value;
					this.SendPropertyChanged("OmegaX");
					this.OnOmegaXChanged();
				}
			}
		}
		
		[Column(Storage="_omegaY", Name="OmegaY", DbType="double", AutoSync=AutoSync.Never, CanBeNull=false)]
		[DebuggerNonUserCode()]
		public double OmegaY
		{
			get
			{
				return this._omegaY;
			}
			set
			{
				if ((_omegaY != value))
				{
					this.OnOmegaYChanging(value);
					this.SendPropertyChanging();
					this._omegaY = value;
					this.SendPropertyChanged("OmegaY");
					this.OnOmegaYChanged();
				}
			}
		}
		
		[Column(Storage="_omegaZ", Name="OmegaZ", DbType="double", AutoSync=AutoSync.Never, CanBeNull=false)]
		[DebuggerNonUserCode()]
		public double OmegaZ
		{
			get
			{
				return this._omegaZ;
			}
			set
			{
				if ((_omegaZ != value))
				{
					this.OnOmegaZChanging(value);
					this.SendPropertyChanging();
					this._omegaZ = value;
					this.SendPropertyChanged("OmegaZ");
					this.OnOmegaZChanged();
				}
			}
		}
		
		[Column(Storage="_ownerID", Name="OwnerID", DbType="char(36)", AutoSync=AutoSync.Never)]
		[DebuggerNonUserCode()]
		public string OwnerID
		{
			get
			{
				return this._ownerID;
			}
			set
			{
				if (((_ownerID == value) 
							== false))
				{
					this.OnOwnerIDChanging(value);
					this.SendPropertyChanging();
					this._ownerID = value;
					this.SendPropertyChanged("OwnerID");
					this.OnOwnerIDChanged();
				}
			}
		}
		
		[Column(Storage="_ownerMask", Name="OwnerMask", DbType="int", AutoSync=AutoSync.Never)]
		[DebuggerNonUserCode()]
		public System.Nullable<int> OwnerMask
		{
			get
			{
				return this._ownerMask;
			}
			set
			{
				if ((_ownerMask != value))
				{
					this.OnOwnerMaskChanging(value);
					this.SendPropertyChanging();
					this._ownerMask = value;
					this.SendPropertyChanged("OwnerMask");
					this.OnOwnerMaskChanged();
				}
			}
		}
		
		[Column(Storage="_particleSystem", Name="ParticleSystem", DbType="blob", AutoSync=AutoSync.Never)]
		[DebuggerNonUserCode()]
		public byte[] ParticleSystem
		{
			get
			{
				return this._particleSystem;
			}
			set
			{
				if (((_particleSystem == value) 
							== false))
				{
					this.OnParticleSystemChanging(value);
					this.SendPropertyChanging();
					this._particleSystem = value;
					this.SendPropertyChanged("ParticleSystem");
					this.OnParticleSystemChanged();
				}
			}
		}
		
		[Column(Storage="_passTouches", Name="PassTouches", DbType="tinyint(4)", AutoSync=AutoSync.Never, CanBeNull=false)]
		[DebuggerNonUserCode()]
		public sbyte PassTouches
		{
			get
			{
				return this._passTouches;
			}
			set
			{
				if ((_passTouches != value))
				{
					this.OnPassTouchesChanging(value);
					this.SendPropertyChanging();
					this._passTouches = value;
					this.SendPropertyChanged("PassTouches");
					this.OnPassTouchesChanged();
				}
			}
		}
		
		[Column(Storage="_payButton1", Name="PayButton1", DbType="int", AutoSync=AutoSync.Never, CanBeNull=false)]
		[DebuggerNonUserCode()]
		public int PayButton1
		{
			get
			{
				return this._payButton1;
			}
			set
			{
				if ((_payButton1 != value))
				{
					this.OnPayButton1Changing(value);
					this.SendPropertyChanging();
					this._payButton1 = value;
					this.SendPropertyChanged("PayButton1");
					this.OnPayButton1Changed();
				}
			}
		}
		
		[Column(Storage="_payButton2", Name="PayButton2", DbType="int", AutoSync=AutoSync.Never, CanBeNull=false)]
		[DebuggerNonUserCode()]
		public int PayButton2
		{
			get
			{
				return this._payButton2;
			}
			set
			{
				if ((_payButton2 != value))
				{
					this.OnPayButton2Changing(value);
					this.SendPropertyChanging();
					this._payButton2 = value;
					this.SendPropertyChanged("PayButton2");
					this.OnPayButton2Changed();
				}
			}
		}
		
		[Column(Storage="_payButton3", Name="PayButton3", DbType="int", AutoSync=AutoSync.Never, CanBeNull=false)]
		[DebuggerNonUserCode()]
		public int PayButton3
		{
			get
			{
				return this._payButton3;
			}
			set
			{
				if ((_payButton3 != value))
				{
					this.OnPayButton3Changing(value);
					this.SendPropertyChanging();
					this._payButton3 = value;
					this.SendPropertyChanged("PayButton3");
					this.OnPayButton3Changed();
				}
			}
		}
		
		[Column(Storage="_payButton4", Name="PayButton4", DbType="int", AutoSync=AutoSync.Never, CanBeNull=false)]
		[DebuggerNonUserCode()]
		public int PayButton4
		{
			get
			{
				return this._payButton4;
			}
			set
			{
				if ((_payButton4 != value))
				{
					this.OnPayButton4Changing(value);
					this.SendPropertyChanging();
					this._payButton4 = value;
					this.SendPropertyChanged("PayButton4");
					this.OnPayButton4Changed();
				}
			}
		}
		
		[Column(Storage="_payPrice", Name="PayPrice", DbType="int", AutoSync=AutoSync.Never, CanBeNull=false)]
		[DebuggerNonUserCode()]
		public int PayPrice
		{
			get
			{
				return this._payPrice;
			}
			set
			{
				if ((_payPrice != value))
				{
					this.OnPayPriceChanging(value);
					this.SendPropertyChanging();
					this._payPrice = value;
					this.SendPropertyChanged("PayPrice");
					this.OnPayPriceChanged();
				}
			}
		}
		
		[Column(Storage="_positionX", Name="PositionX", DbType="double", AutoSync=AutoSync.Never)]
		[DebuggerNonUserCode()]
		public System.Nullable<double> PositionX
		{
			get
			{
				return this._positionX;
			}
			set
			{
				if ((_positionX != value))
				{
					this.OnPositionXChanging(value);
					this.SendPropertyChanging();
					this._positionX = value;
					this.SendPropertyChanged("PositionX");
					this.OnPositionXChanged();
				}
			}
		}
		
		[Column(Storage="_positionY", Name="PositionY", DbType="double", AutoSync=AutoSync.Never)]
		[DebuggerNonUserCode()]
		public System.Nullable<double> PositionY
		{
			get
			{
				return this._positionY;
			}
			set
			{
				if ((_positionY != value))
				{
					this.OnPositionYChanging(value);
					this.SendPropertyChanging();
					this._positionY = value;
					this.SendPropertyChanged("PositionY");
					this.OnPositionYChanged();
				}
			}
		}
		
		[Column(Storage="_positionZ", Name="PositionZ", DbType="double", AutoSync=AutoSync.Never)]
		[DebuggerNonUserCode()]
		public System.Nullable<double> PositionZ
		{
			get
			{
				return this._positionZ;
			}
			set
			{
				if ((_positionZ != value))
				{
					this.OnPositionZChanging(value);
					this.SendPropertyChanging();
					this._positionZ = value;
					this.SendPropertyChanged("PositionZ");
					this.OnPositionZChanged();
				}
			}
		}
		
		[Column(Storage="_regionUuid", Name="RegionUUID", DbType="char(36)", AutoSync=AutoSync.Never)]
		[DebuggerNonUserCode()]
		public string RegionUUID
		{
			get
			{
				return this._regionUuid;
			}
			set
			{
				if (((_regionUuid == value) 
							== false))
				{
					this.OnRegionUUIDChanging(value);
					this.SendPropertyChanging();
					this._regionUuid = value;
					this.SendPropertyChanged("RegionUUID");
					this.OnRegionUUIDChanged();
				}
			}
		}
		
		[Column(Storage="_rotationW", Name="RotationW", DbType="double", AutoSync=AutoSync.Never)]
		[DebuggerNonUserCode()]
		public System.Nullable<double> RotationW
		{
			get
			{
				return this._rotationW;
			}
			set
			{
				if ((_rotationW != value))
				{
					this.OnRotationWChanging(value);
					this.SendPropertyChanging();
					this._rotationW = value;
					this.SendPropertyChanged("RotationW");
					this.OnRotationWChanged();
				}
			}
		}
		
		[Column(Storage="_rotationX", Name="RotationX", DbType="double", AutoSync=AutoSync.Never)]
		[DebuggerNonUserCode()]
		public System.Nullable<double> RotationX
		{
			get
			{
				return this._rotationX;
			}
			set
			{
				if ((_rotationX != value))
				{
					this.OnRotationXChanging(value);
					this.SendPropertyChanging();
					this._rotationX = value;
					this.SendPropertyChanged("RotationX");
					this.OnRotationXChanged();
				}
			}
		}
		
		[Column(Storage="_rotationY", Name="RotationY", DbType="double", AutoSync=AutoSync.Never)]
		[DebuggerNonUserCode()]
		public System.Nullable<double> RotationY
		{
			get
			{
				return this._rotationY;
			}
			set
			{
				if ((_rotationY != value))
				{
					this.OnRotationYChanging(value);
					this.SendPropertyChanging();
					this._rotationY = value;
					this.SendPropertyChanged("RotationY");
					this.OnRotationYChanged();
				}
			}
		}
		
		[Column(Storage="_rotationZ", Name="RotationZ", DbType="double", AutoSync=AutoSync.Never)]
		[DebuggerNonUserCode()]
		public System.Nullable<double> RotationZ
		{
			get
			{
				return this._rotationZ;
			}
			set
			{
				if ((_rotationZ != value))
				{
					this.OnRotationZChanging(value);
					this.SendPropertyChanging();
					this._rotationZ = value;
					this.SendPropertyChanged("RotationZ");
					this.OnRotationZChanged();
				}
			}
		}
		
		[Column(Storage="_salePrice", Name="SalePrice", DbType="int", AutoSync=AutoSync.Never, CanBeNull=false)]
		[DebuggerNonUserCode()]
		public int SalePrice
		{
			get
			{
				return this._salePrice;
			}
			set
			{
				if ((_salePrice != value))
				{
					this.OnSalePriceChanging(value);
					this.SendPropertyChanging();
					this._salePrice = value;
					this.SendPropertyChanged("SalePrice");
					this.OnSalePriceChanged();
				}
			}
		}
		
		[Column(Storage="_saleType", Name="SaleType", DbType="tinyint(4)", AutoSync=AutoSync.Never, CanBeNull=false)]
		[DebuggerNonUserCode()]
		public sbyte SaleType
		{
			get
			{
				return this._saleType;
			}
			set
			{
				if ((_saleType != value))
				{
					this.OnSaleTypeChanging(value);
					this.SendPropertyChanging();
					this._saleType = value;
					this.SendPropertyChanged("SaleType");
					this.OnSaleTypeChanged();
				}
			}
		}
		
		[Column(Storage="_sceneGroupID", Name="SceneGroupID", DbType="char(36)", AutoSync=AutoSync.Never)]
		[DebuggerNonUserCode()]
		public string SceneGroupID
		{
			get
			{
				return this._sceneGroupID;
			}
			set
			{
				if (((_sceneGroupID == value) 
							== false))
				{
					this.OnSceneGroupIDChanging(value);
					this.SendPropertyChanging();
					this._sceneGroupID = value;
					this.SendPropertyChanged("SceneGroupID");
					this.OnSceneGroupIDChanged();
				}
			}
		}
		
		[Column(Storage="_scriptAccessPin", Name="ScriptAccessPin", DbType="int", AutoSync=AutoSync.Never, CanBeNull=false)]
		[DebuggerNonUserCode()]
		public int ScriptAccessPin
		{
			get
			{
				return this._scriptAccessPin;
			}
			set
			{
				if ((_scriptAccessPin != value))
				{
					this.OnScriptAccessPinChanging(value);
					this.SendPropertyChanging();
					this._scriptAccessPin = value;
					this.SendPropertyChanged("ScriptAccessPin");
					this.OnScriptAccessPinChanged();
				}
			}
		}
		
		[Column(Storage="_sitName", Name="SitName", DbType="varchar(255)", AutoSync=AutoSync.Never)]
		[DebuggerNonUserCode()]
		public string SitName
		{
			get
			{
				return this._sitName;
			}
			set
			{
				if (((_sitName == value) 
							== false))
				{
					this.OnSitNameChanging(value);
					this.SendPropertyChanging();
					this._sitName = value;
					this.SendPropertyChanged("SitName");
					this.OnSitNameChanged();
				}
			}
		}
		
		[Column(Storage="_sitTargetOffsetX", Name="SitTargetOffsetX", DbType="double", AutoSync=AutoSync.Never)]
		[DebuggerNonUserCode()]
		public System.Nullable<double> SitTargetOffsetX
		{
			get
			{
				return this._sitTargetOffsetX;
			}
			set
			{
				if ((_sitTargetOffsetX != value))
				{
					this.OnSitTargetOffsetXChanging(value);
					this.SendPropertyChanging();
					this._sitTargetOffsetX = value;
					this.SendPropertyChanged("SitTargetOffsetX");
					this.OnSitTargetOffsetXChanged();
				}
			}
		}
		
		[Column(Storage="_sitTargetOffsetY", Name="SitTargetOffsetY", DbType="double", AutoSync=AutoSync.Never)]
		[DebuggerNonUserCode()]
		public System.Nullable<double> SitTargetOffsetY
		{
			get
			{
				return this._sitTargetOffsetY;
			}
			set
			{
				if ((_sitTargetOffsetY != value))
				{
					this.OnSitTargetOffsetYChanging(value);
					this.SendPropertyChanging();
					this._sitTargetOffsetY = value;
					this.SendPropertyChanged("SitTargetOffsetY");
					this.OnSitTargetOffsetYChanged();
				}
			}
		}
		
		[Column(Storage="_sitTargetOffsetZ", Name="SitTargetOffsetZ", DbType="double", AutoSync=AutoSync.Never)]
		[DebuggerNonUserCode()]
		public System.Nullable<double> SitTargetOffsetZ
		{
			get
			{
				return this._sitTargetOffsetZ;
			}
			set
			{
				if ((_sitTargetOffsetZ != value))
				{
					this.OnSitTargetOffsetZChanging(value);
					this.SendPropertyChanging();
					this._sitTargetOffsetZ = value;
					this.SendPropertyChanged("SitTargetOffsetZ");
					this.OnSitTargetOffsetZChanged();
				}
			}
		}
		
		[Column(Storage="_sitTargetOrientW", Name="SitTargetOrientW", DbType="double", AutoSync=AutoSync.Never)]
		[DebuggerNonUserCode()]
		public System.Nullable<double> SitTargetOrientW
		{
			get
			{
				return this._sitTargetOrientW;
			}
			set
			{
				if ((_sitTargetOrientW != value))
				{
					this.OnSitTargetOrientWChanging(value);
					this.SendPropertyChanging();
					this._sitTargetOrientW = value;
					this.SendPropertyChanged("SitTargetOrientW");
					this.OnSitTargetOrientWChanged();
				}
			}
		}
		
		[Column(Storage="_sitTargetOrientX", Name="SitTargetOrientX", DbType="double", AutoSync=AutoSync.Never)]
		[DebuggerNonUserCode()]
		public System.Nullable<double> SitTargetOrientX
		{
			get
			{
				return this._sitTargetOrientX;
			}
			set
			{
				if ((_sitTargetOrientX != value))
				{
					this.OnSitTargetOrientXChanging(value);
					this.SendPropertyChanging();
					this._sitTargetOrientX = value;
					this.SendPropertyChanged("SitTargetOrientX");
					this.OnSitTargetOrientXChanged();
				}
			}
		}
		
		[Column(Storage="_sitTargetOrientY", Name="SitTargetOrientY", DbType="double", AutoSync=AutoSync.Never)]
		[DebuggerNonUserCode()]
		public System.Nullable<double> SitTargetOrientY
		{
			get
			{
				return this._sitTargetOrientY;
			}
			set
			{
				if ((_sitTargetOrientY != value))
				{
					this.OnSitTargetOrientYChanging(value);
					this.SendPropertyChanging();
					this._sitTargetOrientY = value;
					this.SendPropertyChanged("SitTargetOrientY");
					this.OnSitTargetOrientYChanged();
				}
			}
		}
		
		[Column(Storage="_sitTargetOrientZ", Name="SitTargetOrientZ", DbType="double", AutoSync=AutoSync.Never)]
		[DebuggerNonUserCode()]
		public System.Nullable<double> SitTargetOrientZ
		{
			get
			{
				return this._sitTargetOrientZ;
			}
			set
			{
				if ((_sitTargetOrientZ != value))
				{
					this.OnSitTargetOrientZChanging(value);
					this.SendPropertyChanging();
					this._sitTargetOrientZ = value;
					this.SendPropertyChanged("SitTargetOrientZ");
					this.OnSitTargetOrientZChanged();
				}
			}
		}
		
		[Column(Storage="_text", Name="Text", DbType="varchar(255)", AutoSync=AutoSync.Never)]
		[DebuggerNonUserCode()]
		public string Text
		{
			get
			{
				return this._text;
			}
			set
			{
				if (((_text == value) 
							== false))
				{
					this.OnTextChanging(value);
					this.SendPropertyChanging();
					this._text = value;
					this.SendPropertyChanged("Text");
					this.OnTextChanged();
				}
			}
		}
		
		[Column(Storage="_textureAnimation", Name="TextureAnimation", DbType="blob", AutoSync=AutoSync.Never)]
		[DebuggerNonUserCode()]
		public byte[] TextureAnimation
		{
			get
			{
				return this._textureAnimation;
			}
			set
			{
				if (((_textureAnimation == value) 
							== false))
				{
					this.OnTextureAnimationChanging(value);
					this.SendPropertyChanging();
					this._textureAnimation = value;
					this.SendPropertyChanged("TextureAnimation");
					this.OnTextureAnimationChanged();
				}
			}
		}
		
		[Column(Storage="_touchName", Name="TouchName", DbType="varchar(255)", AutoSync=AutoSync.Never)]
		[DebuggerNonUserCode()]
		public string TouchName
		{
			get
			{
				return this._touchName;
			}
			set
			{
				if (((_touchName == value) 
							== false))
				{
					this.OnTouchNameChanging(value);
					this.SendPropertyChanging();
					this._touchName = value;
					this.SendPropertyChanged("TouchName");
					this.OnTouchNameChanged();
				}
			}
		}
		
		[Column(Storage="_uuid", Name="UUID", DbType="char(36)", IsPrimaryKey=true, AutoSync=AutoSync.Never, CanBeNull=false)]
		[DebuggerNonUserCode()]
		public string UUID
		{
			get
			{
				return this._uuid;
			}
			set
			{
				if (((_uuid == value) 
							== false))
				{
					this.OnUUIDChanging(value);
					this.SendPropertyChanging();
					this._uuid = value;
					this.SendPropertyChanged("UUID");
					this.OnUUIDChanged();
				}
			}
		}
		
		[Column(Storage="_velocityX", Name="VelocityX", DbType="double", AutoSync=AutoSync.Never)]
		[DebuggerNonUserCode()]
		public System.Nullable<double> VelocityX
		{
			get
			{
				return this._velocityX;
			}
			set
			{
				if ((_velocityX != value))
				{
					this.OnVelocityXChanging(value);
					this.SendPropertyChanging();
					this._velocityX = value;
					this.SendPropertyChanged("VelocityX");
					this.OnVelocityXChanged();
				}
			}
		}
		
		[Column(Storage="_velocityY", Name="VelocityY", DbType="double", AutoSync=AutoSync.Never)]
		[DebuggerNonUserCode()]
		public System.Nullable<double> VelocityY
		{
			get
			{
				return this._velocityY;
			}
			set
			{
				if ((_velocityY != value))
				{
					this.OnVelocityYChanging(value);
					this.SendPropertyChanging();
					this._velocityY = value;
					this.SendPropertyChanged("VelocityY");
					this.OnVelocityYChanged();
				}
			}
		}
		
		[Column(Storage="_velocityZ", Name="VelocityZ", DbType="double", AutoSync=AutoSync.Never)]
		[DebuggerNonUserCode()]
		public System.Nullable<double> VelocityZ
		{
			get
			{
				return this._velocityZ;
			}
			set
			{
				if ((_velocityZ != value))
				{
					this.OnVelocityZChanging(value);
					this.SendPropertyChanging();
					this._velocityZ = value;
					this.SendPropertyChanged("VelocityZ");
					this.OnVelocityZChanged();
				}
			}
		}
		
		public event System.ComponentModel.PropertyChangingEventHandler PropertyChanging;
		
		public event System.ComponentModel.PropertyChangedEventHandler PropertyChanged;
		
		protected virtual void SendPropertyChanging()
		{
			System.ComponentModel.PropertyChangingEventHandler h = this.PropertyChanging;
			if ((h != null))
			{
				h(this, emptyChangingEventArgs);
			}
		}
		
		protected virtual void SendPropertyChanged(string propertyName)
		{
			System.ComponentModel.PropertyChangedEventHandler h = this.PropertyChanged;
			if ((h != null))
			{
				h(this, new System.ComponentModel.PropertyChangedEventArgs(propertyName));
			}
		}
	}
	
	[Table(Name="primshapes")]
	public partial class primshapes : System.ComponentModel.INotifyPropertyChanging, System.ComponentModel.INotifyPropertyChanged
	{
		
		private static System.ComponentModel.PropertyChangingEventArgs emptyChangingEventArgs = new System.ComponentModel.PropertyChangingEventArgs("");
		
		private byte[] _extraParams;
		
		private System.Nullable<int> _pathBegin;
		
		private System.Nullable<int> _pathCurve;
		
		private System.Nullable<int> _pathEnd;
		
		private System.Nullable<int> _pathRadiusOffset;
		
		private System.Nullable<int> _pathRevolutions;
		
		private System.Nullable<int> _pathScaleX;
		
		private System.Nullable<int> _pathScaleY;
		
		private System.Nullable<int> _pathShearX;
		
		private System.Nullable<int> _pathShearY;
		
		private System.Nullable<int> _pathSkew;
		
		private System.Nullable<int> _pathTaperX;
		
		private System.Nullable<int> _pathTaperY;
		
		private System.Nullable<int> _pathTwist;
		
		private System.Nullable<int> _pathTwistBegin;
		
		private System.Nullable<int> _pcOde;
		
		private System.Nullable<int> _profileBegin;
		
		private System.Nullable<int> _profileCurve;
		
		private System.Nullable<int> _profileEnd;
		
		private System.Nullable<int> _profileHollow;
		
		private double _scaleX;
		
		private double _scaleY;
		
		private double _scaleZ;
		
		private System.Nullable<int> _shape;
		
		private System.Nullable<int> _state;
		
		private byte[] _texture;
		
		private string _uuid;
		
		#region Extensibility Method Declarations
		partial void OnCreated();
		
		partial void OnExtraParamsChanged();
		
		partial void OnExtraParamsChanging(byte[] value);
		
		partial void OnPathBeginChanged();
		
		partial void OnPathBeginChanging(System.Nullable<int> value);
		
		partial void OnPathCurveChanged();
		
		partial void OnPathCurveChanging(System.Nullable<int> value);
		
		partial void OnPathEndChanged();
		
		partial void OnPathEndChanging(System.Nullable<int> value);
		
		partial void OnPathRadiusOffsetChanged();
		
		partial void OnPathRadiusOffsetChanging(System.Nullable<int> value);
		
		partial void OnPathRevolutionsChanged();
		
		partial void OnPathRevolutionsChanging(System.Nullable<int> value);
		
		partial void OnPathScaleXChanged();
		
		partial void OnPathScaleXChanging(System.Nullable<int> value);
		
		partial void OnPathScaleYChanged();
		
		partial void OnPathScaleYChanging(System.Nullable<int> value);
		
		partial void OnPathShearXChanged();
		
		partial void OnPathShearXChanging(System.Nullable<int> value);
		
		partial void OnPathShearYChanged();
		
		partial void OnPathShearYChanging(System.Nullable<int> value);
		
		partial void OnPathSkewChanged();
		
		partial void OnPathSkewChanging(System.Nullable<int> value);
		
		partial void OnPathTaperXChanged();
		
		partial void OnPathTaperXChanging(System.Nullable<int> value);
		
		partial void OnPathTaperYChanged();
		
		partial void OnPathTaperYChanging(System.Nullable<int> value);
		
		partial void OnPathTwistChanged();
		
		partial void OnPathTwistChanging(System.Nullable<int> value);
		
		partial void OnPathTwistBeginChanged();
		
		partial void OnPathTwistBeginChanging(System.Nullable<int> value);
		
		partial void OnPCodeChanged();
		
		partial void OnPCodeChanging(System.Nullable<int> value);
		
		partial void OnProfileBeginChanged();
		
		partial void OnProfileBeginChanging(System.Nullable<int> value);
		
		partial void OnProfileCurveChanged();
		
		partial void OnProfileCurveChanging(System.Nullable<int> value);
		
		partial void OnProfileEndChanged();
		
		partial void OnProfileEndChanging(System.Nullable<int> value);
		
		partial void OnProfileHollowChanged();
		
		partial void OnProfileHollowChanging(System.Nullable<int> value);
		
		partial void OnScaleXChanged();
		
		partial void OnScaleXChanging(double value);
		
		partial void OnScaleYChanged();
		
		partial void OnScaleYChanging(double value);
		
		partial void OnScaleZChanged();
		
		partial void OnScaleZChanging(double value);
		
		partial void OnShapeChanged();
		
		partial void OnShapeChanging(System.Nullable<int> value);
		
		partial void OnStateChanged();
		
		partial void OnStateChanging(System.Nullable<int> value);
		
		partial void OnTextureChanged();
		
		partial void OnTextureChanging(byte[] value);
		
		partial void OnUUIDChanged();
		
		partial void OnUUIDChanging(string value);
		#endregion
		
		
		public primshapes()
		{
			this.OnCreated();
		}
		
		[Column(Storage="_extraParams", Name="ExtraParams", DbType="longblob", AutoSync=AutoSync.Never)]
		[DebuggerNonUserCode()]
		public byte[] ExtraParams
		{
			get
			{
				return this._extraParams;
			}
			set
			{
				if (((_extraParams == value) 
							== false))
				{
					this.OnExtraParamsChanging(value);
					this.SendPropertyChanging();
					this._extraParams = value;
					this.SendPropertyChanged("ExtraParams");
					this.OnExtraParamsChanged();
				}
			}
		}
		
		[Column(Storage="_pathBegin", Name="PathBegin", DbType="int", AutoSync=AutoSync.Never)]
		[DebuggerNonUserCode()]
		public System.Nullable<int> PathBegin
		{
			get
			{
				return this._pathBegin;
			}
			set
			{
				if ((_pathBegin != value))
				{
					this.OnPathBeginChanging(value);
					this.SendPropertyChanging();
					this._pathBegin = value;
					this.SendPropertyChanged("PathBegin");
					this.OnPathBeginChanged();
				}
			}
		}
		
		[Column(Storage="_pathCurve", Name="PathCurve", DbType="int", AutoSync=AutoSync.Never)]
		[DebuggerNonUserCode()]
		public System.Nullable<int> PathCurve
		{
			get
			{
				return this._pathCurve;
			}
			set
			{
				if ((_pathCurve != value))
				{
					this.OnPathCurveChanging(value);
					this.SendPropertyChanging();
					this._pathCurve = value;
					this.SendPropertyChanged("PathCurve");
					this.OnPathCurveChanged();
				}
			}
		}
		
		[Column(Storage="_pathEnd", Name="PathEnd", DbType="int", AutoSync=AutoSync.Never)]
		[DebuggerNonUserCode()]
		public System.Nullable<int> PathEnd
		{
			get
			{
				return this._pathEnd;
			}
			set
			{
				if ((_pathEnd != value))
				{
					this.OnPathEndChanging(value);
					this.SendPropertyChanging();
					this._pathEnd = value;
					this.SendPropertyChanged("PathEnd");
					this.OnPathEndChanged();
				}
			}
		}
		
		[Column(Storage="_pathRadiusOffset", Name="PathRadiusOffset", DbType="int", AutoSync=AutoSync.Never)]
		[DebuggerNonUserCode()]
		public System.Nullable<int> PathRadiusOffset
		{
			get
			{
				return this._pathRadiusOffset;
			}
			set
			{
				if ((_pathRadiusOffset != value))
				{
					this.OnPathRadiusOffsetChanging(value);
					this.SendPropertyChanging();
					this._pathRadiusOffset = value;
					this.SendPropertyChanged("PathRadiusOffset");
					this.OnPathRadiusOffsetChanged();
				}
			}
		}
		
		[Column(Storage="_pathRevolutions", Name="PathRevolutions", DbType="int", AutoSync=AutoSync.Never)]
		[DebuggerNonUserCode()]
		public System.Nullable<int> PathRevolutions
		{
			get
			{
				return this._pathRevolutions;
			}
			set
			{
				if ((_pathRevolutions != value))
				{
					this.OnPathRevolutionsChanging(value);
					this.SendPropertyChanging();
					this._pathRevolutions = value;
					this.SendPropertyChanged("PathRevolutions");
					this.OnPathRevolutionsChanged();
				}
			}
		}
		
		[Column(Storage="_pathScaleX", Name="PathScaleX", DbType="int", AutoSync=AutoSync.Never)]
		[DebuggerNonUserCode()]
		public System.Nullable<int> PathScaleX
		{
			get
			{
				return this._pathScaleX;
			}
			set
			{
				if ((_pathScaleX != value))
				{
					this.OnPathScaleXChanging(value);
					this.SendPropertyChanging();
					this._pathScaleX = value;
					this.SendPropertyChanged("PathScaleX");
					this.OnPathScaleXChanged();
				}
			}
		}
		
		[Column(Storage="_pathScaleY", Name="PathScaleY", DbType="int", AutoSync=AutoSync.Never)]
		[DebuggerNonUserCode()]
		public System.Nullable<int> PathScaleY
		{
			get
			{
				return this._pathScaleY;
			}
			set
			{
				if ((_pathScaleY != value))
				{
					this.OnPathScaleYChanging(value);
					this.SendPropertyChanging();
					this._pathScaleY = value;
					this.SendPropertyChanged("PathScaleY");
					this.OnPathScaleYChanged();
				}
			}
		}
		
		[Column(Storage="_pathShearX", Name="PathShearX", DbType="int", AutoSync=AutoSync.Never)]
		[DebuggerNonUserCode()]
		public System.Nullable<int> PathShearX
		{
			get
			{
				return this._pathShearX;
			}
			set
			{
				if ((_pathShearX != value))
				{
					this.OnPathShearXChanging(value);
					this.SendPropertyChanging();
					this._pathShearX = value;
					this.SendPropertyChanged("PathShearX");
					this.OnPathShearXChanged();
				}
			}
		}
		
		[Column(Storage="_pathShearY", Name="PathShearY", DbType="int", AutoSync=AutoSync.Never)]
		[DebuggerNonUserCode()]
		public System.Nullable<int> PathShearY
		{
			get
			{
				return this._pathShearY;
			}
			set
			{
				if ((_pathShearY != value))
				{
					this.OnPathShearYChanging(value);
					this.SendPropertyChanging();
					this._pathShearY = value;
					this.SendPropertyChanged("PathShearY");
					this.OnPathShearYChanged();
				}
			}
		}
		
		[Column(Storage="_pathSkew", Name="PathSkew", DbType="int", AutoSync=AutoSync.Never)]
		[DebuggerNonUserCode()]
		public System.Nullable<int> PathSkew
		{
			get
			{
				return this._pathSkew;
			}
			set
			{
				if ((_pathSkew != value))
				{
					this.OnPathSkewChanging(value);
					this.SendPropertyChanging();
					this._pathSkew = value;
					this.SendPropertyChanged("PathSkew");
					this.OnPathSkewChanged();
				}
			}
		}
		
		[Column(Storage="_pathTaperX", Name="PathTaperX", DbType="int", AutoSync=AutoSync.Never)]
		[DebuggerNonUserCode()]
		public System.Nullable<int> PathTaperX
		{
			get
			{
				return this._pathTaperX;
			}
			set
			{
				if ((_pathTaperX != value))
				{
					this.OnPathTaperXChanging(value);
					this.SendPropertyChanging();
					this._pathTaperX = value;
					this.SendPropertyChanged("PathTaperX");
					this.OnPathTaperXChanged();
				}
			}
		}
		
		[Column(Storage="_pathTaperY", Name="PathTaperY", DbType="int", AutoSync=AutoSync.Never)]
		[DebuggerNonUserCode()]
		public System.Nullable<int> PathTaperY
		{
			get
			{
				return this._pathTaperY;
			}
			set
			{
				if ((_pathTaperY != value))
				{
					this.OnPathTaperYChanging(value);
					this.SendPropertyChanging();
					this._pathTaperY = value;
					this.SendPropertyChanged("PathTaperY");
					this.OnPathTaperYChanged();
				}
			}
		}
		
		[Column(Storage="_pathTwist", Name="PathTwist", DbType="int", AutoSync=AutoSync.Never)]
		[DebuggerNonUserCode()]
		public System.Nullable<int> PathTwist
		{
			get
			{
				return this._pathTwist;
			}
			set
			{
				if ((_pathTwist != value))
				{
					this.OnPathTwistChanging(value);
					this.SendPropertyChanging();
					this._pathTwist = value;
					this.SendPropertyChanged("PathTwist");
					this.OnPathTwistChanged();
				}
			}
		}
		
		[Column(Storage="_pathTwistBegin", Name="PathTwistBegin", DbType="int", AutoSync=AutoSync.Never)]
		[DebuggerNonUserCode()]
		public System.Nullable<int> PathTwistBegin
		{
			get
			{
				return this._pathTwistBegin;
			}
			set
			{
				if ((_pathTwistBegin != value))
				{
					this.OnPathTwistBeginChanging(value);
					this.SendPropertyChanging();
					this._pathTwistBegin = value;
					this.SendPropertyChanged("PathTwistBegin");
					this.OnPathTwistBeginChanged();
				}
			}
		}
		
		[Column(Storage="_pcOde", Name="PCode", DbType="int", AutoSync=AutoSync.Never)]
		[DebuggerNonUserCode()]
		public System.Nullable<int> PCode
		{
			get
			{
				return this._pcOde;
			}
			set
			{
				if ((_pcOde != value))
				{
					this.OnPCodeChanging(value);
					this.SendPropertyChanging();
					this._pcOde = value;
					this.SendPropertyChanged("PCode");
					this.OnPCodeChanged();
				}
			}
		}
		
		[Column(Storage="_profileBegin", Name="ProfileBegin", DbType="int", AutoSync=AutoSync.Never)]
		[DebuggerNonUserCode()]
		public System.Nullable<int> ProfileBegin
		{
			get
			{
				return this._profileBegin;
			}
			set
			{
				if ((_profileBegin != value))
				{
					this.OnProfileBeginChanging(value);
					this.SendPropertyChanging();
					this._profileBegin = value;
					this.SendPropertyChanged("ProfileBegin");
					this.OnProfileBeginChanged();
				}
			}
		}
		
		[Column(Storage="_profileCurve", Name="ProfileCurve", DbType="int", AutoSync=AutoSync.Never)]
		[DebuggerNonUserCode()]
		public System.Nullable<int> ProfileCurve
		{
			get
			{
				return this._profileCurve;
			}
			set
			{
				if ((_profileCurve != value))
				{
					this.OnProfileCurveChanging(value);
					this.SendPropertyChanging();
					this._profileCurve = value;
					this.SendPropertyChanged("ProfileCurve");
					this.OnProfileCurveChanged();
				}
			}
		}
		
		[Column(Storage="_profileEnd", Name="ProfileEnd", DbType="int", AutoSync=AutoSync.Never)]
		[DebuggerNonUserCode()]
		public System.Nullable<int> ProfileEnd
		{
			get
			{
				return this._profileEnd;
			}
			set
			{
				if ((_profileEnd != value))
				{
					this.OnProfileEndChanging(value);
					this.SendPropertyChanging();
					this._profileEnd = value;
					this.SendPropertyChanged("ProfileEnd");
					this.OnProfileEndChanged();
				}
			}
		}
		
		[Column(Storage="_profileHollow", Name="ProfileHollow", DbType="int", AutoSync=AutoSync.Never)]
		[DebuggerNonUserCode()]
		public System.Nullable<int> ProfileHollow
		{
			get
			{
				return this._profileHollow;
			}
			set
			{
				if ((_profileHollow != value))
				{
					this.OnProfileHollowChanging(value);
					this.SendPropertyChanging();
					this._profileHollow = value;
					this.SendPropertyChanged("ProfileHollow");
					this.OnProfileHollowChanged();
				}
			}
		}
		
		[Column(Storage="_scaleX", Name="ScaleX", DbType="double", AutoSync=AutoSync.Never, CanBeNull=false)]
		[DebuggerNonUserCode()]
		public double ScaleX
		{
			get
			{
				return this._scaleX;
			}
			set
			{
				if ((_scaleX != value))
				{
					this.OnScaleXChanging(value);
					this.SendPropertyChanging();
					this._scaleX = value;
					this.SendPropertyChanged("ScaleX");
					this.OnScaleXChanged();
				}
			}
		}
		
		[Column(Storage="_scaleY", Name="ScaleY", DbType="double", AutoSync=AutoSync.Never, CanBeNull=false)]
		[DebuggerNonUserCode()]
		public double ScaleY
		{
			get
			{
				return this._scaleY;
			}
			set
			{
				if ((_scaleY != value))
				{
					this.OnScaleYChanging(value);
					this.SendPropertyChanging();
					this._scaleY = value;
					this.SendPropertyChanged("ScaleY");
					this.OnScaleYChanged();
				}
			}
		}
		
		[Column(Storage="_scaleZ", Name="ScaleZ", DbType="double", AutoSync=AutoSync.Never, CanBeNull=false)]
		[DebuggerNonUserCode()]
		public double ScaleZ
		{
			get
			{
				return this._scaleZ;
			}
			set
			{
				if ((_scaleZ != value))
				{
					this.OnScaleZChanging(value);
					this.SendPropertyChanging();
					this._scaleZ = value;
					this.SendPropertyChanged("ScaleZ");
					this.OnScaleZChanged();
				}
			}
		}
		
		[Column(Storage="_shape", Name="Shape", DbType="int", AutoSync=AutoSync.Never)]
		[DebuggerNonUserCode()]
		public System.Nullable<int> Shape
		{
			get
			{
				return this._shape;
			}
			set
			{
				if ((_shape != value))
				{
					this.OnShapeChanging(value);
					this.SendPropertyChanging();
					this._shape = value;
					this.SendPropertyChanged("Shape");
					this.OnShapeChanged();
				}
			}
		}
		
		[Column(Storage="_state", Name="State", DbType="int", AutoSync=AutoSync.Never)]
		[DebuggerNonUserCode()]
		public System.Nullable<int> State
		{
			get
			{
				return this._state;
			}
			set
			{
				if ((_state != value))
				{
					this.OnStateChanging(value);
					this.SendPropertyChanging();
					this._state = value;
					this.SendPropertyChanged("State");
					this.OnStateChanged();
				}
			}
		}
		
		[Column(Storage="_texture", Name="Texture", DbType="longblob", AutoSync=AutoSync.Never)]
		[DebuggerNonUserCode()]
		public byte[] Texture
		{
			get
			{
				return this._texture;
			}
			set
			{
				if (((_texture == value) 
							== false))
				{
					this.OnTextureChanging(value);
					this.SendPropertyChanging();
					this._texture = value;
					this.SendPropertyChanged("Texture");
					this.OnTextureChanged();
				}
			}
		}
		
		[Column(Storage="_uuid", Name="UUID", DbType="char(36)", IsPrimaryKey=true, AutoSync=AutoSync.Never, CanBeNull=false)]
		[DebuggerNonUserCode()]
		public string UUID
		{
			get
			{
				return this._uuid;
			}
			set
			{
				if (((_uuid == value) 
							== false))
				{
					this.OnUUIDChanging(value);
					this.SendPropertyChanging();
					this._uuid = value;
					this.SendPropertyChanged("UUID");
					this.OnUUIDChanged();
				}
			}
		}
		
		public event System.ComponentModel.PropertyChangingEventHandler PropertyChanging;
		
		public event System.ComponentModel.PropertyChangedEventHandler PropertyChanged;
		
		protected virtual void SendPropertyChanging()
		{
			System.ComponentModel.PropertyChangingEventHandler h = this.PropertyChanging;
			if ((h != null))
			{
				h(this, emptyChangingEventArgs);
			}
		}
		
		protected virtual void SendPropertyChanged(string propertyName)
		{
			System.ComponentModel.PropertyChangedEventHandler h = this.PropertyChanged;
			if ((h != null))
			{
				h(this, new System.ComponentModel.PropertyChangedEventArgs(propertyName));
			}
		}
	}
	
	[Table(Name="regionban")]
	public partial class regionban
	{
		
		private string _bannedIp;
		
		private string _bannedIpHostMask;
		
		private string _bannedUuid;
		
		private string _regionUuid;
		
		#region Extensibility Method Declarations
		partial void OnCreated();
		
		partial void OnbannedIpChanged();
		
		partial void OnbannedIpChanging(string value);
		
		partial void OnbannedIpHostMaskChanged();
		
		partial void OnbannedIpHostMaskChanging(string value);
		
		partial void OnbannedUUIDChanged();
		
		partial void OnbannedUUIDChanging(string value);
		
		partial void OnregionUUIDChanged();
		
		partial void OnregionUUIDChanging(string value);
		#endregion
		
		
		public regionban()
		{
			this.OnCreated();
		}
		
		[Column(Storage="_bannedIp", Name="bannedIp", DbType="varchar(16)", AutoSync=AutoSync.Never, CanBeNull=false)]
		[DebuggerNonUserCode()]
		public string bannedIp
		{
			get
			{
				return this._bannedIp;
			}
			set
			{
				if (((_bannedIp == value) 
							== false))
				{
					this.OnbannedIpChanging(value);
					this._bannedIp = value;
					this.OnbannedIpChanged();
				}
			}
		}
		
		[Column(Storage="_bannedIpHostMask", Name="bannedIpHostMask", DbType="varchar(16)", AutoSync=AutoSync.Never, CanBeNull=false)]
		[DebuggerNonUserCode()]
		public string bannedIpHostMask
		{
			get
			{
				return this._bannedIpHostMask;
			}
			set
			{
				if (((_bannedIpHostMask == value) 
							== false))
				{
					this.OnbannedIpHostMaskChanging(value);
					this._bannedIpHostMask = value;
					this.OnbannedIpHostMaskChanged();
				}
			}
		}
		
		[Column(Storage="_bannedUuid", Name="bannedUUID", DbType="varchar(36)", AutoSync=AutoSync.Never, CanBeNull=false)]
		[DebuggerNonUserCode()]
		public string bannedUUID
		{
			get
			{
				return this._bannedUuid;
			}
			set
			{
				if (((_bannedUuid == value) 
							== false))
				{
					this.OnbannedUUIDChanging(value);
					this._bannedUuid = value;
					this.OnbannedUUIDChanged();
				}
			}
		}
		
		[Column(Storage="_regionUuid", Name="regionUUID", DbType="varchar(36)", AutoSync=AutoSync.Never, CanBeNull=false)]
		[DebuggerNonUserCode()]
		public string regionUUID
		{
			get
			{
				return this._regionUuid;
			}
			set
			{
				if (((_regionUuid == value) 
							== false))
				{
					this.OnregionUUIDChanging(value);
					this._regionUuid = value;
					this.OnregionUUIDChanged();
				}
			}
		}
	}
	
	[Table(Name="regions")]
	public partial class regions : System.ComponentModel.INotifyPropertyChanging, System.ComponentModel.INotifyPropertyChanged
	{
		
		private static System.ComponentModel.PropertyChangingEventArgs emptyChangingEventArgs = new System.ComponentModel.PropertyChangingEventArgs("");
		
		private System.Nullable<uint> _access;
		
		private System.Nullable<long> _eastOverrideHandle;
		
		private int _flags;
		
		private int _lastseen;
		
		private System.Nullable<uint> _locX;
		
		private System.Nullable<uint> _locY;
		
		private System.Nullable<uint> _locZ;
		
		private System.Nullable<long> _northOverrideHandle;
		
		private string _originUuid;
		
		private string _owneruuid;
		
		private string _principalID;
		
		private string _regionAssetRecvKey;
		
		private string _regionAssetSendKey;
		
		private string _regionAssetUri;
		
		private string _regionDataUri;
		
		private long _regionHandle;
		
		private string _regionMapTexture;
		
		private string _regionName;
		
		private string _regionRecvKey;
		
		private string _regionSecret;
		
		private string _regionSendKey;
		
		private string _regionUserRecvKey;
		
		private string _regionUserSendKey;
		
		private string _regionUserUri;
		
		private string _scopeID;
		
		private System.Nullable<int> _serverHttpPort;
		
		private string _serverIp;
		
		private System.Nullable<uint> _serverPort;
		
		private System.Nullable<int> _serverRemotingPort;
		
		private string _serverUri;
		
		private int _sizeX;
		
		private int _sizeY;
		
		private System.Nullable<long> _southOverrideHandle;
		
		private string _token;
		
		private string _uuid;
		
		private System.Nullable<long> _westOverrideHandle;
		
		#region Extensibility Method Declarations
		partial void OnCreated();
		
		partial void OnaccessChanged();
		
		partial void OnaccessChanging(System.Nullable<uint> value);
		
		partial void OneastOverrideHandleChanged();
		
		partial void OneastOverrideHandleChanging(System.Nullable<long> value);
		
		partial void OnflagsChanged();
		
		partial void OnflagsChanging(int value);
		
		partial void OnlastseenChanged();
		
		partial void OnlastseenChanging(int value);
		
		partial void OnlocXChanged();
		
		partial void OnlocXChanging(System.Nullable<uint> value);
		
		partial void OnlocYChanged();
		
		partial void OnlocYChanging(System.Nullable<uint> value);
		
		partial void OnlocZChanged();
		
		partial void OnlocZChanging(System.Nullable<uint> value);
		
		partial void OnnorthOverrideHandleChanged();
		
		partial void OnnorthOverrideHandleChanging(System.Nullable<long> value);
		
		partial void OnoriginUUIDChanged();
		
		partial void OnoriginUUIDChanging(string value);
		
		partial void OnowneruuidChanged();
		
		partial void OnowneruuidChanging(string value);
		
		partial void OnPrincipalIDChanged();
		
		partial void OnPrincipalIDChanging(string value);
		
		partial void OnregionAssetRecvKeyChanged();
		
		partial void OnregionAssetRecvKeyChanging(string value);
		
		partial void OnregionAssetSendKeyChanged();
		
		partial void OnregionAssetSendKeyChanging(string value);
		
		partial void OnregionAssetURIChanged();
		
		partial void OnregionAssetURIChanging(string value);
		
		partial void OnregionDataURIChanged();
		
		partial void OnregionDataURIChanging(string value);
		
		partial void OnregionHandleChanged();
		
		partial void OnregionHandleChanging(long value);
		
		partial void OnregionMapTextureChanged();
		
		partial void OnregionMapTextureChanging(string value);
		
		partial void OnregionNameChanged();
		
		partial void OnregionNameChanging(string value);
		
		partial void OnregionRecvKeyChanged();
		
		partial void OnregionRecvKeyChanging(string value);
		
		partial void OnregionSecretChanged();
		
		partial void OnregionSecretChanging(string value);
		
		partial void OnregionSendKeyChanged();
		
		partial void OnregionSendKeyChanging(string value);
		
		partial void OnregionUserRecvKeyChanged();
		
		partial void OnregionUserRecvKeyChanging(string value);
		
		partial void OnregionUserSendKeyChanged();
		
		partial void OnregionUserSendKeyChanging(string value);
		
		partial void OnregionUserURIChanged();
		
		partial void OnregionUserURIChanging(string value);
		
		partial void OnScopeIDChanged();
		
		partial void OnScopeIDChanging(string value);
		
		partial void OnserverHttpPortChanged();
		
		partial void OnserverHttpPortChanging(System.Nullable<int> value);
		
		partial void OnserverIPChanged();
		
		partial void OnserverIPChanging(string value);
		
		partial void OnserverPortChanged();
		
		partial void OnserverPortChanging(System.Nullable<uint> value);
		
		partial void OnserverRemotingPortChanged();
		
		partial void OnserverRemotingPortChanging(System.Nullable<int> value);
		
		partial void OnserverURIChanged();
		
		partial void OnserverURIChanging(string value);
		
		partial void OnsizeXChanged();
		
		partial void OnsizeXChanging(int value);
		
		partial void OnsizeYChanged();
		
		partial void OnsizeYChanging(int value);
		
		partial void OnsouthOverrideHandleChanged();
		
		partial void OnsouthOverrideHandleChanging(System.Nullable<long> value);
		
		partial void OnTokenChanged();
		
		partial void OnTokenChanging(string value);
		
		partial void OnuuidChanged();
		
		partial void OnuuidChanging(string value);
		
		partial void OnwestOverrideHandleChanged();
		
		partial void OnwestOverrideHandleChanging(System.Nullable<long> value);
		#endregion
		
		
		public regions()
		{
			this.OnCreated();
		}
		
		[Column(Storage="_access", Name="access", DbType="int unsigned", AutoSync=AutoSync.Never)]
		[DebuggerNonUserCode()]
		public System.Nullable<uint> access
		{
			get
			{
				return this._access;
			}
			set
			{
				if ((_access != value))
				{
					this.OnaccessChanging(value);
					this.SendPropertyChanging();
					this._access = value;
					this.SendPropertyChanged("access");
					this.OnaccessChanged();
				}
			}
		}
		
		[Column(Storage="_eastOverrideHandle", Name="eastOverrideHandle", DbType="bigint(20) unsigned", AutoSync=AutoSync.Never)]
		[DebuggerNonUserCode()]
		public System.Nullable<long> eastOverrideHandle
		{
			get
			{
				return this._eastOverrideHandle;
			}
			set
			{
				if ((_eastOverrideHandle != value))
				{
					this.OneastOverrideHandleChanging(value);
					this.SendPropertyChanging();
					this._eastOverrideHandle = value;
					this.SendPropertyChanged("eastOverrideHandle");
					this.OneastOverrideHandleChanged();
				}
			}
		}
		
		[Column(Storage="_flags", Name="flags", DbType="int", AutoSync=AutoSync.Never, CanBeNull=false)]
		[DebuggerNonUserCode()]
		public int flags
		{
			get
			{
				return this._flags;
			}
			set
			{
				if ((_flags != value))
				{
					this.OnflagsChanging(value);
					this.SendPropertyChanging();
					this._flags = value;
					this.SendPropertyChanged("flags");
					this.OnflagsChanged();
				}
			}
		}
		
		[Column(Storage="_lastseen", Name="last_seen", DbType="int", AutoSync=AutoSync.Never, CanBeNull=false)]
		[DebuggerNonUserCode()]
		public int lastseen
		{
			get
			{
				return this._lastseen;
			}
			set
			{
				if ((_lastseen != value))
				{
					this.OnlastseenChanging(value);
					this.SendPropertyChanging();
					this._lastseen = value;
					this.SendPropertyChanged("lastseen");
					this.OnlastseenChanged();
				}
			}
		}
		
		[Column(Storage="_locX", Name="locX", DbType="int unsigned", AutoSync=AutoSync.Never)]
		[DebuggerNonUserCode()]
		public System.Nullable<uint> locX
		{
			get
			{
				return this._locX;
			}
			set
			{
				if ((_locX != value))
				{
					this.OnlocXChanging(value);
					this.SendPropertyChanging();
					this._locX = value;
					this.SendPropertyChanged("locX");
					this.OnlocXChanged();
				}
			}
		}
		
		[Column(Storage="_locY", Name="locY", DbType="int unsigned", AutoSync=AutoSync.Never)]
		[DebuggerNonUserCode()]
		public System.Nullable<uint> locY
		{
			get
			{
				return this._locY;
			}
			set
			{
				if ((_locY != value))
				{
					this.OnlocYChanging(value);
					this.SendPropertyChanging();
					this._locY = value;
					this.SendPropertyChanged("locY");
					this.OnlocYChanged();
				}
			}
		}
		
		[Column(Storage="_locZ", Name="locZ", DbType="int unsigned", AutoSync=AutoSync.Never)]
		[DebuggerNonUserCode()]
		public System.Nullable<uint> locZ
		{
			get
			{
				return this._locZ;
			}
			set
			{
				if ((_locZ != value))
				{
					this.OnlocZChanging(value);
					this.SendPropertyChanging();
					this._locZ = value;
					this.SendPropertyChanged("locZ");
					this.OnlocZChanged();
				}
			}
		}
		
		[Column(Storage="_northOverrideHandle", Name="northOverrideHandle", DbType="bigint(20) unsigned", AutoSync=AutoSync.Never)]
		[DebuggerNonUserCode()]
		public System.Nullable<long> northOverrideHandle
		{
			get
			{
				return this._northOverrideHandle;
			}
			set
			{
				if ((_northOverrideHandle != value))
				{
					this.OnnorthOverrideHandleChanging(value);
					this.SendPropertyChanging();
					this._northOverrideHandle = value;
					this.SendPropertyChanged("northOverrideHandle");
					this.OnnorthOverrideHandleChanged();
				}
			}
		}
		
		[Column(Storage="_originUuid", Name="originUUID", DbType="varchar(36)", AutoSync=AutoSync.Never)]
		[DebuggerNonUserCode()]
		public string originUUID
		{
			get
			{
				return this._originUuid;
			}
			set
			{
				if (((_originUuid == value) 
							== false))
				{
					this.OnoriginUUIDChanging(value);
					this.SendPropertyChanging();
					this._originUuid = value;
					this.SendPropertyChanged("originUUID");
					this.OnoriginUUIDChanged();
				}
			}
		}
		
		[Column(Storage="_owneruuid", Name="owner_uuid", DbType="varchar(36)", AutoSync=AutoSync.Never, CanBeNull=false)]
		[DebuggerNonUserCode()]
		public string owneruuid
		{
			get
			{
				return this._owneruuid;
			}
			set
			{
				if (((_owneruuid == value) 
							== false))
				{
					this.OnowneruuidChanging(value);
					this.SendPropertyChanging();
					this._owneruuid = value;
					this.SendPropertyChanged("owneruuid");
					this.OnowneruuidChanged();
				}
			}
		}
		
		[Column(Storage="_principalID", Name="PrincipalID", DbType="char(36)", AutoSync=AutoSync.Never, CanBeNull=false)]
		[DebuggerNonUserCode()]
		public string PrincipalID
		{
			get
			{
				return this._principalID;
			}
			set
			{
				if (((_principalID == value) 
							== false))
				{
					this.OnPrincipalIDChanging(value);
					this.SendPropertyChanging();
					this._principalID = value;
					this.SendPropertyChanged("PrincipalID");
					this.OnPrincipalIDChanged();
				}
			}
		}
		
		[Column(Storage="_regionAssetRecvKey", Name="regionAssetRecvKey", DbType="varchar(128)", AutoSync=AutoSync.Never)]
		[DebuggerNonUserCode()]
		public string regionAssetRecvKey
		{
			get
			{
				return this._regionAssetRecvKey;
			}
			set
			{
				if (((_regionAssetRecvKey == value) 
							== false))
				{
					this.OnregionAssetRecvKeyChanging(value);
					this.SendPropertyChanging();
					this._regionAssetRecvKey = value;
					this.SendPropertyChanged("regionAssetRecvKey");
					this.OnregionAssetRecvKeyChanged();
				}
			}
		}
		
		[Column(Storage="_regionAssetSendKey", Name="regionAssetSendKey", DbType="varchar(128)", AutoSync=AutoSync.Never)]
		[DebuggerNonUserCode()]
		public string regionAssetSendKey
		{
			get
			{
				return this._regionAssetSendKey;
			}
			set
			{
				if (((_regionAssetSendKey == value) 
							== false))
				{
					this.OnregionAssetSendKeyChanging(value);
					this.SendPropertyChanging();
					this._regionAssetSendKey = value;
					this.SendPropertyChanged("regionAssetSendKey");
					this.OnregionAssetSendKeyChanged();
				}
			}
		}
		
		[Column(Storage="_regionAssetUri", Name="regionAssetURI", DbType="varchar(255)", AutoSync=AutoSync.Never)]
		[DebuggerNonUserCode()]
		public string regionAssetURI
		{
			get
			{
				return this._regionAssetUri;
			}
			set
			{
				if (((_regionAssetUri == value) 
							== false))
				{
					this.OnregionAssetURIChanging(value);
					this.SendPropertyChanging();
					this._regionAssetUri = value;
					this.SendPropertyChanged("regionAssetURI");
					this.OnregionAssetURIChanged();
				}
			}
		}
		
		[Column(Storage="_regionDataUri", Name="regionDataURI", DbType="varchar(255)", AutoSync=AutoSync.Never)]
		[DebuggerNonUserCode()]
		public string regionDataURI
		{
			get
			{
				return this._regionDataUri;
			}
			set
			{
				if (((_regionDataUri == value) 
							== false))
				{
					this.OnregionDataURIChanging(value);
					this.SendPropertyChanging();
					this._regionDataUri = value;
					this.SendPropertyChanged("regionDataURI");
					this.OnregionDataURIChanged();
				}
			}
		}
		
		[Column(Storage="_regionHandle", Name="regionHandle", DbType="bigint(20) unsigned", AutoSync=AutoSync.Never, CanBeNull=false)]
		[DebuggerNonUserCode()]
		public long regionHandle
		{
			get
			{
				return this._regionHandle;
			}
			set
			{
				if ((_regionHandle != value))
				{
					this.OnregionHandleChanging(value);
					this.SendPropertyChanging();
					this._regionHandle = value;
					this.SendPropertyChanged("regionHandle");
					this.OnregionHandleChanged();
				}
			}
		}
		
		[Column(Storage="_regionMapTexture", Name="regionMapTexture", DbType="varchar(36)", AutoSync=AutoSync.Never)]
		[DebuggerNonUserCode()]
		public string regionMapTexture
		{
			get
			{
				return this._regionMapTexture;
			}
			set
			{
				if (((_regionMapTexture == value) 
							== false))
				{
					this.OnregionMapTextureChanging(value);
					this.SendPropertyChanging();
					this._regionMapTexture = value;
					this.SendPropertyChanged("regionMapTexture");
					this.OnregionMapTextureChanged();
				}
			}
		}
		
		[Column(Storage="_regionName", Name="regionName", DbType="varchar(32)", AutoSync=AutoSync.Never)]
		[DebuggerNonUserCode()]
		public string regionName
		{
			get
			{
				return this._regionName;
			}
			set
			{
				if (((_regionName == value) 
							== false))
				{
					this.OnregionNameChanging(value);
					this.SendPropertyChanging();
					this._regionName = value;
					this.SendPropertyChanged("regionName");
					this.OnregionNameChanged();
				}
			}
		}
		
		[Column(Storage="_regionRecvKey", Name="regionRecvKey", DbType="varchar(128)", AutoSync=AutoSync.Never)]
		[DebuggerNonUserCode()]
		public string regionRecvKey
		{
			get
			{
				return this._regionRecvKey;
			}
			set
			{
				if (((_regionRecvKey == value) 
							== false))
				{
					this.OnregionRecvKeyChanging(value);
					this.SendPropertyChanging();
					this._regionRecvKey = value;
					this.SendPropertyChanged("regionRecvKey");
					this.OnregionRecvKeyChanged();
				}
			}
		}
		
		[Column(Storage="_regionSecret", Name="regionSecret", DbType="varchar(128)", AutoSync=AutoSync.Never)]
		[DebuggerNonUserCode()]
		public string regionSecret
		{
			get
			{
				return this._regionSecret;
			}
			set
			{
				if (((_regionSecret == value) 
							== false))
				{
					this.OnregionSecretChanging(value);
					this.SendPropertyChanging();
					this._regionSecret = value;
					this.SendPropertyChanged("regionSecret");
					this.OnregionSecretChanged();
				}
			}
		}
		
		[Column(Storage="_regionSendKey", Name="regionSendKey", DbType="varchar(128)", AutoSync=AutoSync.Never)]
		[DebuggerNonUserCode()]
		public string regionSendKey
		{
			get
			{
				return this._regionSendKey;
			}
			set
			{
				if (((_regionSendKey == value) 
							== false))
				{
					this.OnregionSendKeyChanging(value);
					this.SendPropertyChanging();
					this._regionSendKey = value;
					this.SendPropertyChanged("regionSendKey");
					this.OnregionSendKeyChanged();
				}
			}
		}
		
		[Column(Storage="_regionUserRecvKey", Name="regionUserRecvKey", DbType="varchar(128)", AutoSync=AutoSync.Never)]
		[DebuggerNonUserCode()]
		public string regionUserRecvKey
		{
			get
			{
				return this._regionUserRecvKey;
			}
			set
			{
				if (((_regionUserRecvKey == value) 
							== false))
				{
					this.OnregionUserRecvKeyChanging(value);
					this.SendPropertyChanging();
					this._regionUserRecvKey = value;
					this.SendPropertyChanged("regionUserRecvKey");
					this.OnregionUserRecvKeyChanged();
				}
			}
		}
		
		[Column(Storage="_regionUserSendKey", Name="regionUserSendKey", DbType="varchar(128)", AutoSync=AutoSync.Never)]
		[DebuggerNonUserCode()]
		public string regionUserSendKey
		{
			get
			{
				return this._regionUserSendKey;
			}
			set
			{
				if (((_regionUserSendKey == value) 
							== false))
				{
					this.OnregionUserSendKeyChanging(value);
					this.SendPropertyChanging();
					this._regionUserSendKey = value;
					this.SendPropertyChanged("regionUserSendKey");
					this.OnregionUserSendKeyChanged();
				}
			}
		}
		
		[Column(Storage="_regionUserUri", Name="regionUserURI", DbType="varchar(255)", AutoSync=AutoSync.Never)]
		[DebuggerNonUserCode()]
		public string regionUserURI
		{
			get
			{
				return this._regionUserUri;
			}
			set
			{
				if (((_regionUserUri == value) 
							== false))
				{
					this.OnregionUserURIChanging(value);
					this.SendPropertyChanging();
					this._regionUserUri = value;
					this.SendPropertyChanged("regionUserURI");
					this.OnregionUserURIChanged();
				}
			}
		}
		
		[Column(Storage="_scopeID", Name="ScopeID", DbType="char(36)", AutoSync=AutoSync.Never, CanBeNull=false)]
		[DebuggerNonUserCode()]
		public string ScopeID
		{
			get
			{
				return this._scopeID;
			}
			set
			{
				if (((_scopeID == value) 
							== false))
				{
					this.OnScopeIDChanging(value);
					this.SendPropertyChanging();
					this._scopeID = value;
					this.SendPropertyChanged("ScopeID");
					this.OnScopeIDChanged();
				}
			}
		}
		
		[Column(Storage="_serverHttpPort", Name="serverHttpPort", DbType="int(10)", AutoSync=AutoSync.Never)]
		[DebuggerNonUserCode()]
		public System.Nullable<int> serverHttpPort
		{
			get
			{
				return this._serverHttpPort;
			}
			set
			{
				if ((_serverHttpPort != value))
				{
					this.OnserverHttpPortChanging(value);
					this.SendPropertyChanging();
					this._serverHttpPort = value;
					this.SendPropertyChanged("serverHttpPort");
					this.OnserverHttpPortChanged();
				}
			}
		}
		
		[Column(Storage="_serverIp", Name="serverIP", DbType="varchar(64)", AutoSync=AutoSync.Never)]
		[DebuggerNonUserCode()]
		public string serverIP
		{
			get
			{
				return this._serverIp;
			}
			set
			{
				if (((_serverIp == value) 
							== false))
				{
					this.OnserverIPChanging(value);
					this.SendPropertyChanging();
					this._serverIp = value;
					this.SendPropertyChanged("serverIP");
					this.OnserverIPChanged();
				}
			}
		}
		
		[Column(Storage="_serverPort", Name="serverPort", DbType="int unsigned", AutoSync=AutoSync.Never)]
		[DebuggerNonUserCode()]
		public System.Nullable<uint> serverPort
		{
			get
			{
				return this._serverPort;
			}
			set
			{
				if ((_serverPort != value))
				{
					this.OnserverPortChanging(value);
					this.SendPropertyChanging();
					this._serverPort = value;
					this.SendPropertyChanged("serverPort");
					this.OnserverPortChanged();
				}
			}
		}
		
		[Column(Storage="_serverRemotingPort", Name="serverRemotingPort", DbType="int(10)", AutoSync=AutoSync.Never)]
		[DebuggerNonUserCode()]
		public System.Nullable<int> serverRemotingPort
		{
			get
			{
				return this._serverRemotingPort;
			}
			set
			{
				if ((_serverRemotingPort != value))
				{
					this.OnserverRemotingPortChanging(value);
					this.SendPropertyChanging();
					this._serverRemotingPort = value;
					this.SendPropertyChanged("serverRemotingPort");
					this.OnserverRemotingPortChanged();
				}
			}
		}
		
		[Column(Storage="_serverUri", Name="serverURI", DbType="varchar(255)", AutoSync=AutoSync.Never)]
		[DebuggerNonUserCode()]
		public string serverURI
		{
			get
			{
				return this._serverUri;
			}
			set
			{
				if (((_serverUri == value) 
							== false))
				{
					this.OnserverURIChanging(value);
					this.SendPropertyChanging();
					this._serverUri = value;
					this.SendPropertyChanged("serverURI");
					this.OnserverURIChanged();
				}
			}
		}
		
		[Column(Storage="_sizeX", Name="sizeX", DbType="int", AutoSync=AutoSync.Never, CanBeNull=false)]
		[DebuggerNonUserCode()]
		public int sizeX
		{
			get
			{
				return this._sizeX;
			}
			set
			{
				if ((_sizeX != value))
				{
					this.OnsizeXChanging(value);
					this.SendPropertyChanging();
					this._sizeX = value;
					this.SendPropertyChanged("sizeX");
					this.OnsizeXChanged();
				}
			}
		}
		
		[Column(Storage="_sizeY", Name="sizeY", DbType="int", AutoSync=AutoSync.Never, CanBeNull=false)]
		[DebuggerNonUserCode()]
		public int sizeY
		{
			get
			{
				return this._sizeY;
			}
			set
			{
				if ((_sizeY != value))
				{
					this.OnsizeYChanging(value);
					this.SendPropertyChanging();
					this._sizeY = value;
					this.SendPropertyChanged("sizeY");
					this.OnsizeYChanged();
				}
			}
		}
		
		[Column(Storage="_southOverrideHandle", Name="southOverrideHandle", DbType="bigint(20) unsigned", AutoSync=AutoSync.Never)]
		[DebuggerNonUserCode()]
		public System.Nullable<long> southOverrideHandle
		{
			get
			{
				return this._southOverrideHandle;
			}
			set
			{
				if ((_southOverrideHandle != value))
				{
					this.OnsouthOverrideHandleChanging(value);
					this.SendPropertyChanging();
					this._southOverrideHandle = value;
					this.SendPropertyChanged("southOverrideHandle");
					this.OnsouthOverrideHandleChanged();
				}
			}
		}
		
		[Column(Storage="_token", Name="Token", DbType="varchar(255)", AutoSync=AutoSync.Never, CanBeNull=false)]
		[DebuggerNonUserCode()]
		public string Token
		{
			get
			{
				return this._token;
			}
			set
			{
				if (((_token == value) 
							== false))
				{
					this.OnTokenChanging(value);
					this.SendPropertyChanging();
					this._token = value;
					this.SendPropertyChanged("Token");
					this.OnTokenChanged();
				}
			}
		}
		
		[Column(Storage="_uuid", Name="uuid", DbType="varchar(36)", IsPrimaryKey=true, AutoSync=AutoSync.Never, CanBeNull=false)]
		[DebuggerNonUserCode()]
		public string uuid
		{
			get
			{
				return this._uuid;
			}
			set
			{
				if (((_uuid == value) 
							== false))
				{
					this.OnuuidChanging(value);
					this.SendPropertyChanging();
					this._uuid = value;
					this.SendPropertyChanged("uuid");
					this.OnuuidChanged();
				}
			}
		}
		
		[Column(Storage="_westOverrideHandle", Name="westOverrideHandle", DbType="bigint(20) unsigned", AutoSync=AutoSync.Never)]
		[DebuggerNonUserCode()]
		public System.Nullable<long> westOverrideHandle
		{
			get
			{
				return this._westOverrideHandle;
			}
			set
			{
				if ((_westOverrideHandle != value))
				{
					this.OnwestOverrideHandleChanging(value);
					this.SendPropertyChanging();
					this._westOverrideHandle = value;
					this.SendPropertyChanged("westOverrideHandle");
					this.OnwestOverrideHandleChanged();
				}
			}
		}
		
		public event System.ComponentModel.PropertyChangingEventHandler PropertyChanging;
		
		public event System.ComponentModel.PropertyChangedEventHandler PropertyChanged;
		
		protected virtual void SendPropertyChanging()
		{
			System.ComponentModel.PropertyChangingEventHandler h = this.PropertyChanging;
			if ((h != null))
			{
				h(this, emptyChangingEventArgs);
			}
		}
		
		protected virtual void SendPropertyChanged(string propertyName)
		{
			System.ComponentModel.PropertyChangedEventHandler h = this.PropertyChanged;
			if ((h != null))
			{
				h(this, new System.ComponentModel.PropertyChangedEventArgs(propertyName));
			}
		}
	}
	
	[Table(Name="regionsettings")]
	public partial class regionsettings : System.ComponentModel.INotifyPropertyChanging, System.ComponentModel.INotifyPropertyChanged
	{
		
		private static System.ComponentModel.PropertyChangingEventArgs emptyChangingEventArgs = new System.ComponentModel.PropertyChangingEventArgs("");
		
		private int _agentlimit;
		
		private int _allowdamage;
		
		private int _allowlandjoindivide;
		
		private int _allowlandresell;
		
		private int _blockfly;
		
		private int _blockshowinsearch;
		
		private int _blockterraform;
		
		private string _covenant;
		
		private int _disablecollisions;
		
		private int _disablephysics;
		
		private int _disablescripts;
		
		private double _elevation1ne;
		
		private double _elevation1nw;
		
		private double _elevation1se;
		
		private double _elevation1sw;
		
		private double _elevation2ne;
		
		private double _elevation2nw;
		
		private double _elevation2se;
		
		private double _elevation2sw;
		
		private int _fixedsun;
		
		private uint _loadedcreationdatetime;
		
		private string _loadedcreationid;
		
		private string _maptileID;
		
		private int _maturity;
		
		private double _objectbonus;
		
		private string _regionUuid;
		
		private int _restrictpushing;
		
		private sbyte _sandbox;
		
		private double _sunposition;
		
		private double _sunvectorx;
		
		private double _sunvectory;
		
		private double _sunvectorz;
		
		private double _terrainlowerlimit;
		
		private double _terrainraiselimit;
		
		private string _terraintexture1;
		
		private string _terraintexture2;
		
		private string _terraintexture3;
		
		private string _terraintexture4;
		
		private int _useestatesun;
		
		private double _waterheight;
		
		#region Extensibility Method Declarations
		partial void OnCreated();
		
		partial void OnagentlimitChanged();
		
		partial void OnagentlimitChanging(int value);
		
		partial void OnallowdamageChanged();
		
		partial void OnallowdamageChanging(int value);
		
		partial void OnallowlandjoindivideChanged();
		
		partial void OnallowlandjoindivideChanging(int value);
		
		partial void OnallowlandresellChanged();
		
		partial void OnallowlandresellChanging(int value);
		
		partial void OnblockflyChanged();
		
		partial void OnblockflyChanging(int value);
		
		partial void OnblockshowinsearchChanged();
		
		partial void OnblockshowinsearchChanging(int value);
		
		partial void OnblockterraformChanged();
		
		partial void OnblockterraformChanging(int value);
		
		partial void OncovenantChanged();
		
		partial void OncovenantChanging(string value);
		
		partial void OndisablecollisionsChanged();
		
		partial void OndisablecollisionsChanging(int value);
		
		partial void OndisablephysicsChanged();
		
		partial void OndisablephysicsChanging(int value);
		
		partial void OndisablescriptsChanged();
		
		partial void OndisablescriptsChanging(int value);
		
		partial void Onelevation1neChanged();
		
		partial void Onelevation1neChanging(double value);
		
		partial void Onelevation1nwChanged();
		
		partial void Onelevation1nwChanging(double value);
		
		partial void Onelevation1seChanged();
		
		partial void Onelevation1seChanging(double value);
		
		partial void Onelevation1swChanged();
		
		partial void Onelevation1swChanging(double value);
		
		partial void Onelevation2neChanged();
		
		partial void Onelevation2neChanging(double value);
		
		partial void Onelevation2nwChanged();
		
		partial void Onelevation2nwChanging(double value);
		
		partial void Onelevation2seChanged();
		
		partial void Onelevation2seChanging(double value);
		
		partial void Onelevation2swChanged();
		
		partial void Onelevation2swChanging(double value);
		
		partial void OnfixedsunChanged();
		
		partial void OnfixedsunChanging(int value);
		
		partial void OnloadedcreationdatetimeChanged();
		
		partial void OnloadedcreationdatetimeChanging(uint value);
		
		partial void OnloadedcreationidChanged();
		
		partial void OnloadedcreationidChanging(string value);
		
		partial void OnmaptileIDChanged();
		
		partial void OnmaptileIDChanging(string value);
		
		partial void OnmaturityChanged();
		
		partial void OnmaturityChanging(int value);
		
		partial void OnobjectbonusChanged();
		
		partial void OnobjectbonusChanging(double value);
		
		partial void OnregionUUIDChanged();
		
		partial void OnregionUUIDChanging(string value);
		
		partial void OnrestrictpushingChanged();
		
		partial void OnrestrictpushingChanging(int value);
		
		partial void OnSandboxChanged();
		
		partial void OnSandboxChanging(sbyte value);
		
		partial void OnsunpositionChanged();
		
		partial void OnsunpositionChanging(double value);
		
		partial void OnsunvectorxChanged();
		
		partial void OnsunvectorxChanging(double value);
		
		partial void OnsunvectoryChanged();
		
		partial void OnsunvectoryChanging(double value);
		
		partial void OnsunvectorzChanged();
		
		partial void OnsunvectorzChanging(double value);
		
		partial void OnterrainlowerlimitChanged();
		
		partial void OnterrainlowerlimitChanging(double value);
		
		partial void OnterrainraiselimitChanged();
		
		partial void OnterrainraiselimitChanging(double value);
		
		partial void Onterraintexture1Changed();
		
		partial void Onterraintexture1Changing(string value);
		
		partial void Onterraintexture2Changed();
		
		partial void Onterraintexture2Changing(string value);
		
		partial void Onterraintexture3Changed();
		
		partial void Onterraintexture3Changing(string value);
		
		partial void Onterraintexture4Changed();
		
		partial void Onterraintexture4Changing(string value);
		
		partial void OnuseestatesunChanged();
		
		partial void OnuseestatesunChanging(int value);
		
		partial void OnwaterheightChanged();
		
		partial void OnwaterheightChanging(double value);
		#endregion
		
		
		public regionsettings()
		{
			this.OnCreated();
		}
		
		[Column(Storage="_agentlimit", Name="agent_limit", DbType="int", AutoSync=AutoSync.Never, CanBeNull=false)]
		[DebuggerNonUserCode()]
		public int agentlimit
		{
			get
			{
				return this._agentlimit;
			}
			set
			{
				if ((_agentlimit != value))
				{
					this.OnagentlimitChanging(value);
					this.SendPropertyChanging();
					this._agentlimit = value;
					this.SendPropertyChanged("agentlimit");
					this.OnagentlimitChanged();
				}
			}
		}
		
		[Column(Storage="_allowdamage", Name="allow_damage", DbType="int", AutoSync=AutoSync.Never, CanBeNull=false)]
		[DebuggerNonUserCode()]
		public int allowdamage
		{
			get
			{
				return this._allowdamage;
			}
			set
			{
				if ((_allowdamage != value))
				{
					this.OnallowdamageChanging(value);
					this.SendPropertyChanging();
					this._allowdamage = value;
					this.SendPropertyChanged("allowdamage");
					this.OnallowdamageChanged();
				}
			}
		}
		
		[Column(Storage="_allowlandjoindivide", Name="allow_land_join_divide", DbType="int", AutoSync=AutoSync.Never, CanBeNull=false)]
		[DebuggerNonUserCode()]
		public int allowlandjoindivide
		{
			get
			{
				return this._allowlandjoindivide;
			}
			set
			{
				if ((_allowlandjoindivide != value))
				{
					this.OnallowlandjoindivideChanging(value);
					this.SendPropertyChanging();
					this._allowlandjoindivide = value;
					this.SendPropertyChanged("allowlandjoindivide");
					this.OnallowlandjoindivideChanged();
				}
			}
		}
		
		[Column(Storage="_allowlandresell", Name="allow_land_resell", DbType="int", AutoSync=AutoSync.Never, CanBeNull=false)]
		[DebuggerNonUserCode()]
		public int allowlandresell
		{
			get
			{
				return this._allowlandresell;
			}
			set
			{
				if ((_allowlandresell != value))
				{
					this.OnallowlandresellChanging(value);
					this.SendPropertyChanging();
					this._allowlandresell = value;
					this.SendPropertyChanged("allowlandresell");
					this.OnallowlandresellChanged();
				}
			}
		}
		
		[Column(Storage="_blockfly", Name="block_fly", DbType="int", AutoSync=AutoSync.Never, CanBeNull=false)]
		[DebuggerNonUserCode()]
		public int blockfly
		{
			get
			{
				return this._blockfly;
			}
			set
			{
				if ((_blockfly != value))
				{
					this.OnblockflyChanging(value);
					this.SendPropertyChanging();
					this._blockfly = value;
					this.SendPropertyChanged("blockfly");
					this.OnblockflyChanged();
				}
			}
		}
		
		[Column(Storage="_blockshowinsearch", Name="block_show_in_search", DbType="int", AutoSync=AutoSync.Never, CanBeNull=false)]
		[DebuggerNonUserCode()]
		public int blockshowinsearch
		{
			get
			{
				return this._blockshowinsearch;
			}
			set
			{
				if ((_blockshowinsearch != value))
				{
					this.OnblockshowinsearchChanging(value);
					this.SendPropertyChanging();
					this._blockshowinsearch = value;
					this.SendPropertyChanged("blockshowinsearch");
					this.OnblockshowinsearchChanged();
				}
			}
		}
		
		[Column(Storage="_blockterraform", Name="block_terraform", DbType="int", AutoSync=AutoSync.Never, CanBeNull=false)]
		[DebuggerNonUserCode()]
		public int blockterraform
		{
			get
			{
				return this._blockterraform;
			}
			set
			{
				if ((_blockterraform != value))
				{
					this.OnblockterraformChanging(value);
					this.SendPropertyChanging();
					this._blockterraform = value;
					this.SendPropertyChanged("blockterraform");
					this.OnblockterraformChanged();
				}
			}
		}
		
		[Column(Storage="_covenant", Name="covenant", DbType="char(36)", AutoSync=AutoSync.Never)]
		[DebuggerNonUserCode()]
		public string covenant
		{
			get
			{
				return this._covenant;
			}
			set
			{
				if (((_covenant == value) 
							== false))
				{
					this.OncovenantChanging(value);
					this.SendPropertyChanging();
					this._covenant = value;
					this.SendPropertyChanged("covenant");
					this.OncovenantChanged();
				}
			}
		}
		
		[Column(Storage="_disablecollisions", Name="disable_collisions", DbType="int", AutoSync=AutoSync.Never, CanBeNull=false)]
		[DebuggerNonUserCode()]
		public int disablecollisions
		{
			get
			{
				return this._disablecollisions;
			}
			set
			{
				if ((_disablecollisions != value))
				{
					this.OndisablecollisionsChanging(value);
					this.SendPropertyChanging();
					this._disablecollisions = value;
					this.SendPropertyChanged("disablecollisions");
					this.OndisablecollisionsChanged();
				}
			}
		}
		
		[Column(Storage="_disablephysics", Name="disable_physics", DbType="int", AutoSync=AutoSync.Never, CanBeNull=false)]
		[DebuggerNonUserCode()]
		public int disablephysics
		{
			get
			{
				return this._disablephysics;
			}
			set
			{
				if ((_disablephysics != value))
				{
					this.OndisablephysicsChanging(value);
					this.SendPropertyChanging();
					this._disablephysics = value;
					this.SendPropertyChanged("disablephysics");
					this.OndisablephysicsChanged();
				}
			}
		}
		
		[Column(Storage="_disablescripts", Name="disable_scripts", DbType="int", AutoSync=AutoSync.Never, CanBeNull=false)]
		[DebuggerNonUserCode()]
		public int disablescripts
		{
			get
			{
				return this._disablescripts;
			}
			set
			{
				if ((_disablescripts != value))
				{
					this.OndisablescriptsChanging(value);
					this.SendPropertyChanging();
					this._disablescripts = value;
					this.SendPropertyChanged("disablescripts");
					this.OndisablescriptsChanged();
				}
			}
		}
		
		[Column(Storage="_elevation1ne", Name="elevation_1_ne", DbType="double", AutoSync=AutoSync.Never, CanBeNull=false)]
		[DebuggerNonUserCode()]
		public double elevation1ne
		{
			get
			{
				return this._elevation1ne;
			}
			set
			{
				if ((_elevation1ne != value))
				{
					this.Onelevation1neChanging(value);
					this.SendPropertyChanging();
					this._elevation1ne = value;
					this.SendPropertyChanged("elevation1ne");
					this.Onelevation1neChanged();
				}
			}
		}
		
		[Column(Storage="_elevation1nw", Name="elevation_1_nw", DbType="double", AutoSync=AutoSync.Never, CanBeNull=false)]
		[DebuggerNonUserCode()]
		public double elevation1nw
		{
			get
			{
				return this._elevation1nw;
			}
			set
			{
				if ((_elevation1nw != value))
				{
					this.Onelevation1nwChanging(value);
					this.SendPropertyChanging();
					this._elevation1nw = value;
					this.SendPropertyChanged("elevation1nw");
					this.Onelevation1nwChanged();
				}
			}
		}
		
		[Column(Storage="_elevation1se", Name="elevation_1_se", DbType="double", AutoSync=AutoSync.Never, CanBeNull=false)]
		[DebuggerNonUserCode()]
		public double elevation1se
		{
			get
			{
				return this._elevation1se;
			}
			set
			{
				if ((_elevation1se != value))
				{
					this.Onelevation1seChanging(value);
					this.SendPropertyChanging();
					this._elevation1se = value;
					this.SendPropertyChanged("elevation1se");
					this.Onelevation1seChanged();
				}
			}
		}
		
		[Column(Storage="_elevation1sw", Name="elevation_1_sw", DbType="double", AutoSync=AutoSync.Never, CanBeNull=false)]
		[DebuggerNonUserCode()]
		public double elevation1sw
		{
			get
			{
				return this._elevation1sw;
			}
			set
			{
				if ((_elevation1sw != value))
				{
					this.Onelevation1swChanging(value);
					this.SendPropertyChanging();
					this._elevation1sw = value;
					this.SendPropertyChanged("elevation1sw");
					this.Onelevation1swChanged();
				}
			}
		}
		
		[Column(Storage="_elevation2ne", Name="elevation_2_ne", DbType="double", AutoSync=AutoSync.Never, CanBeNull=false)]
		[DebuggerNonUserCode()]
		public double elevation2ne
		{
			get
			{
				return this._elevation2ne;
			}
			set
			{
				if ((_elevation2ne != value))
				{
					this.Onelevation2neChanging(value);
					this.SendPropertyChanging();
					this._elevation2ne = value;
					this.SendPropertyChanged("elevation2ne");
					this.Onelevation2neChanged();
				}
			}
		}
		
		[Column(Storage="_elevation2nw", Name="elevation_2_nw", DbType="double", AutoSync=AutoSync.Never, CanBeNull=false)]
		[DebuggerNonUserCode()]
		public double elevation2nw
		{
			get
			{
				return this._elevation2nw;
			}
			set
			{
				if ((_elevation2nw != value))
				{
					this.Onelevation2nwChanging(value);
					this.SendPropertyChanging();
					this._elevation2nw = value;
					this.SendPropertyChanged("elevation2nw");
					this.Onelevation2nwChanged();
				}
			}
		}
		
		[Column(Storage="_elevation2se", Name="elevation_2_se", DbType="double", AutoSync=AutoSync.Never, CanBeNull=false)]
		[DebuggerNonUserCode()]
		public double elevation2se
		{
			get
			{
				return this._elevation2se;
			}
			set
			{
				if ((_elevation2se != value))
				{
					this.Onelevation2seChanging(value);
					this.SendPropertyChanging();
					this._elevation2se = value;
					this.SendPropertyChanged("elevation2se");
					this.Onelevation2seChanged();
				}
			}
		}
		
		[Column(Storage="_elevation2sw", Name="elevation_2_sw", DbType="double", AutoSync=AutoSync.Never, CanBeNull=false)]
		[DebuggerNonUserCode()]
		public double elevation2sw
		{
			get
			{
				return this._elevation2sw;
			}
			set
			{
				if ((_elevation2sw != value))
				{
					this.Onelevation2swChanging(value);
					this.SendPropertyChanging();
					this._elevation2sw = value;
					this.SendPropertyChanged("elevation2sw");
					this.Onelevation2swChanged();
				}
			}
		}
		
		[Column(Storage="_fixedsun", Name="fixed_sun", DbType="int", AutoSync=AutoSync.Never, CanBeNull=false)]
		[DebuggerNonUserCode()]
		public int fixedsun
		{
			get
			{
				return this._fixedsun;
			}
			set
			{
				if ((_fixedsun != value))
				{
					this.OnfixedsunChanging(value);
					this.SendPropertyChanging();
					this._fixedsun = value;
					this.SendPropertyChanged("fixedsun");
					this.OnfixedsunChanged();
				}
			}
		}
		
		[Column(Storage="_loadedcreationdatetime", Name="loaded_creation_datetime", DbType="int unsigned", AutoSync=AutoSync.Never, CanBeNull=false)]
		[DebuggerNonUserCode()]
		public uint loadedcreationdatetime
		{
			get
			{
				return this._loadedcreationdatetime;
			}
			set
			{
				if ((_loadedcreationdatetime != value))
				{
					this.OnloadedcreationdatetimeChanging(value);
					this.SendPropertyChanging();
					this._loadedcreationdatetime = value;
					this.SendPropertyChanged("loadedcreationdatetime");
					this.OnloadedcreationdatetimeChanged();
				}
			}
		}
		
		[Column(Storage="_loadedcreationid", Name="loaded_creation_id", DbType="varchar(64)", AutoSync=AutoSync.Never)]
		[DebuggerNonUserCode()]
		public string loadedcreationid
		{
			get
			{
				return this._loadedcreationid;
			}
			set
			{
				if (((_loadedcreationid == value) 
							== false))
				{
					this.OnloadedcreationidChanging(value);
					this.SendPropertyChanging();
					this._loadedcreationid = value;
					this.SendPropertyChanged("loadedcreationid");
					this.OnloadedcreationidChanged();
				}
			}
		}
		
		[Column(Storage="_maptileID", Name="map_tile_ID", DbType="char(36)", AutoSync=AutoSync.Never, CanBeNull=false)]
		[DebuggerNonUserCode()]
		public string maptileID
		{
			get
			{
				return this._maptileID;
			}
			set
			{
				if (((_maptileID == value) 
							== false))
				{
					this.OnmaptileIDChanging(value);
					this.SendPropertyChanging();
					this._maptileID = value;
					this.SendPropertyChanged("maptileID");
					this.OnmaptileIDChanged();
				}
			}
		}
		
		[Column(Storage="_maturity", Name="maturity", DbType="int", AutoSync=AutoSync.Never, CanBeNull=false)]
		[DebuggerNonUserCode()]
		public int maturity
		{
			get
			{
				return this._maturity;
			}
			set
			{
				if ((_maturity != value))
				{
					this.OnmaturityChanging(value);
					this.SendPropertyChanging();
					this._maturity = value;
					this.SendPropertyChanged("maturity");
					this.OnmaturityChanged();
				}
			}
		}
		
		[Column(Storage="_objectbonus", Name="object_bonus", DbType="double", AutoSync=AutoSync.Never, CanBeNull=false)]
		[DebuggerNonUserCode()]
		public double objectbonus
		{
			get
			{
				return this._objectbonus;
			}
			set
			{
				if ((_objectbonus != value))
				{
					this.OnobjectbonusChanging(value);
					this.SendPropertyChanging();
					this._objectbonus = value;
					this.SendPropertyChanged("objectbonus");
					this.OnobjectbonusChanged();
				}
			}
		}
		
		[Column(Storage="_regionUuid", Name="regionUUID", DbType="char(36)", IsPrimaryKey=true, AutoSync=AutoSync.Never, CanBeNull=false)]
		[DebuggerNonUserCode()]
		public string regionUUID
		{
			get
			{
				return this._regionUuid;
			}
			set
			{
				if (((_regionUuid == value) 
							== false))
				{
					this.OnregionUUIDChanging(value);
					this.SendPropertyChanging();
					this._regionUuid = value;
					this.SendPropertyChanged("regionUUID");
					this.OnregionUUIDChanged();
				}
			}
		}
		
		[Column(Storage="_restrictpushing", Name="restrict_pushing", DbType="int", AutoSync=AutoSync.Never, CanBeNull=false)]
		[DebuggerNonUserCode()]
		public int restrictpushing
		{
			get
			{
				return this._restrictpushing;
			}
			set
			{
				if ((_restrictpushing != value))
				{
					this.OnrestrictpushingChanging(value);
					this.SendPropertyChanging();
					this._restrictpushing = value;
					this.SendPropertyChanged("restrictpushing");
					this.OnrestrictpushingChanged();
				}
			}
		}
		
		[Column(Storage="_sandbox", Name="Sandbox", DbType="tinyint(4)", AutoSync=AutoSync.Never, CanBeNull=false)]
		[DebuggerNonUserCode()]
		public sbyte Sandbox
		{
			get
			{
				return this._sandbox;
			}
			set
			{
				if ((_sandbox != value))
				{
					this.OnSandboxChanging(value);
					this.SendPropertyChanging();
					this._sandbox = value;
					this.SendPropertyChanged("Sandbox");
					this.OnSandboxChanged();
				}
			}
		}
		
		[Column(Storage="_sunposition", Name="sun_position", DbType="double", AutoSync=AutoSync.Never, CanBeNull=false)]
		[DebuggerNonUserCode()]
		public double sunposition
		{
			get
			{
				return this._sunposition;
			}
			set
			{
				if ((_sunposition != value))
				{
					this.OnsunpositionChanging(value);
					this.SendPropertyChanging();
					this._sunposition = value;
					this.SendPropertyChanged("sunposition");
					this.OnsunpositionChanged();
				}
			}
		}
		
		[Column(Storage="_sunvectorx", Name="sunvectorx", DbType="double", AutoSync=AutoSync.Never, CanBeNull=false)]
		[DebuggerNonUserCode()]
		public double sunvectorx
		{
			get
			{
				return this._sunvectorx;
			}
			set
			{
				if ((_sunvectorx != value))
				{
					this.OnsunvectorxChanging(value);
					this.SendPropertyChanging();
					this._sunvectorx = value;
					this.SendPropertyChanged("sunvectorx");
					this.OnsunvectorxChanged();
				}
			}
		}
		
		[Column(Storage="_sunvectory", Name="sunvectory", DbType="double", AutoSync=AutoSync.Never, CanBeNull=false)]
		[DebuggerNonUserCode()]
		public double sunvectory
		{
			get
			{
				return this._sunvectory;
			}
			set
			{
				if ((_sunvectory != value))
				{
					this.OnsunvectoryChanging(value);
					this.SendPropertyChanging();
					this._sunvectory = value;
					this.SendPropertyChanged("sunvectory");
					this.OnsunvectoryChanged();
				}
			}
		}
		
		[Column(Storage="_sunvectorz", Name="sunvectorz", DbType="double", AutoSync=AutoSync.Never, CanBeNull=false)]
		[DebuggerNonUserCode()]
		public double sunvectorz
		{
			get
			{
				return this._sunvectorz;
			}
			set
			{
				if ((_sunvectorz != value))
				{
					this.OnsunvectorzChanging(value);
					this.SendPropertyChanging();
					this._sunvectorz = value;
					this.SendPropertyChanged("sunvectorz");
					this.OnsunvectorzChanged();
				}
			}
		}
		
		[Column(Storage="_terrainlowerlimit", Name="terrain_lower_limit", DbType="double", AutoSync=AutoSync.Never, CanBeNull=false)]
		[DebuggerNonUserCode()]
		public double terrainlowerlimit
		{
			get
			{
				return this._terrainlowerlimit;
			}
			set
			{
				if ((_terrainlowerlimit != value))
				{
					this.OnterrainlowerlimitChanging(value);
					this.SendPropertyChanging();
					this._terrainlowerlimit = value;
					this.SendPropertyChanged("terrainlowerlimit");
					this.OnterrainlowerlimitChanged();
				}
			}
		}
		
		[Column(Storage="_terrainraiselimit", Name="terrain_raise_limit", DbType="double", AutoSync=AutoSync.Never, CanBeNull=false)]
		[DebuggerNonUserCode()]
		public double terrainraiselimit
		{
			get
			{
				return this._terrainraiselimit;
			}
			set
			{
				if ((_terrainraiselimit != value))
				{
					this.OnterrainraiselimitChanging(value);
					this.SendPropertyChanging();
					this._terrainraiselimit = value;
					this.SendPropertyChanged("terrainraiselimit");
					this.OnterrainraiselimitChanged();
				}
			}
		}
		
		[Column(Storage="_terraintexture1", Name="terrain_texture_1", DbType="char(36)", AutoSync=AutoSync.Never, CanBeNull=false)]
		[DebuggerNonUserCode()]
		public string terraintexture1
		{
			get
			{
				return this._terraintexture1;
			}
			set
			{
				if (((_terraintexture1 == value) 
							== false))
				{
					this.Onterraintexture1Changing(value);
					this.SendPropertyChanging();
					this._terraintexture1 = value;
					this.SendPropertyChanged("terraintexture1");
					this.Onterraintexture1Changed();
				}
			}
		}
		
		[Column(Storage="_terraintexture2", Name="terrain_texture_2", DbType="char(36)", AutoSync=AutoSync.Never, CanBeNull=false)]
		[DebuggerNonUserCode()]
		public string terraintexture2
		{
			get
			{
				return this._terraintexture2;
			}
			set
			{
				if (((_terraintexture2 == value) 
							== false))
				{
					this.Onterraintexture2Changing(value);
					this.SendPropertyChanging();
					this._terraintexture2 = value;
					this.SendPropertyChanged("terraintexture2");
					this.Onterraintexture2Changed();
				}
			}
		}
		
		[Column(Storage="_terraintexture3", Name="terrain_texture_3", DbType="char(36)", AutoSync=AutoSync.Never, CanBeNull=false)]
		[DebuggerNonUserCode()]
		public string terraintexture3
		{
			get
			{
				return this._terraintexture3;
			}
			set
			{
				if (((_terraintexture3 == value) 
							== false))
				{
					this.Onterraintexture3Changing(value);
					this.SendPropertyChanging();
					this._terraintexture3 = value;
					this.SendPropertyChanged("terraintexture3");
					this.Onterraintexture3Changed();
				}
			}
		}
		
		[Column(Storage="_terraintexture4", Name="terrain_texture_4", DbType="char(36)", AutoSync=AutoSync.Never, CanBeNull=false)]
		[DebuggerNonUserCode()]
		public string terraintexture4
		{
			get
			{
				return this._terraintexture4;
			}
			set
			{
				if (((_terraintexture4 == value) 
							== false))
				{
					this.Onterraintexture4Changing(value);
					this.SendPropertyChanging();
					this._terraintexture4 = value;
					this.SendPropertyChanged("terraintexture4");
					this.Onterraintexture4Changed();
				}
			}
		}
		
		[Column(Storage="_useestatesun", Name="use_estate_sun", DbType="int", AutoSync=AutoSync.Never, CanBeNull=false)]
		[DebuggerNonUserCode()]
		public int useestatesun
		{
			get
			{
				return this._useestatesun;
			}
			set
			{
				if ((_useestatesun != value))
				{
					this.OnuseestatesunChanging(value);
					this.SendPropertyChanging();
					this._useestatesun = value;
					this.SendPropertyChanged("useestatesun");
					this.OnuseestatesunChanged();
				}
			}
		}
		
		[Column(Storage="_waterheight", Name="water_height", DbType="double", AutoSync=AutoSync.Never, CanBeNull=false)]
		[DebuggerNonUserCode()]
		public double waterheight
		{
			get
			{
				return this._waterheight;
			}
			set
			{
				if ((_waterheight != value))
				{
					this.OnwaterheightChanging(value);
					this.SendPropertyChanging();
					this._waterheight = value;
					this.SendPropertyChanged("waterheight");
					this.OnwaterheightChanged();
				}
			}
		}
		
		public event System.ComponentModel.PropertyChangingEventHandler PropertyChanging;
		
		public event System.ComponentModel.PropertyChangedEventHandler PropertyChanged;
		
		protected virtual void SendPropertyChanging()
		{
			System.ComponentModel.PropertyChangingEventHandler h = this.PropertyChanging;
			if ((h != null))
			{
				h(this, emptyChangingEventArgs);
			}
		}
		
		protected virtual void SendPropertyChanged(string propertyName)
		{
			System.ComponentModel.PropertyChangedEventHandler h = this.PropertyChanged;
			if ((h != null))
			{
				h(this, new System.ComponentModel.PropertyChangedEventArgs(propertyName));
			}
		}
	}
	
	[Table(Name="terrain")]
	public partial class terrain
	{
		
		private byte[] _heightfield;
		
		private string _regionUuid;
		
		private System.Nullable<int> _revision;
		
		#region Extensibility Method Declarations
		partial void OnCreated();
		
		partial void OnHeightfieldChanged();
		
		partial void OnHeightfieldChanging(byte[] value);
		
		partial void OnRegionUUIDChanged();
		
		partial void OnRegionUUIDChanging(string value);
		
		partial void OnRevisionChanged();
		
		partial void OnRevisionChanging(System.Nullable<int> value);
		#endregion
		
		
		public terrain()
		{
			this.OnCreated();
		}
		
		[Column(Storage="_heightfield", Name="Heightfield", DbType="longblob", AutoSync=AutoSync.Never)]
		[DebuggerNonUserCode()]
		public byte[] Heightfield
		{
			get
			{
				return this._heightfield;
			}
			set
			{
				if (((_heightfield == value) 
							== false))
				{
					this.OnHeightfieldChanging(value);
					this._heightfield = value;
					this.OnHeightfieldChanged();
				}
			}
		}
		
		[Column(Storage="_regionUuid", Name="RegionUUID", DbType="varchar(255)", AutoSync=AutoSync.Never)]
		[DebuggerNonUserCode()]
		public string RegionUUID
		{
			get
			{
				return this._regionUuid;
			}
			set
			{
				if (((_regionUuid == value) 
							== false))
				{
					this.OnRegionUUIDChanging(value);
					this._regionUuid = value;
					this.OnRegionUUIDChanged();
				}
			}
		}
		
		[Column(Storage="_revision", Name="Revision", DbType="int", AutoSync=AutoSync.Never)]
		[DebuggerNonUserCode()]
		public System.Nullable<int> Revision
		{
			get
			{
				return this._revision;
			}
			set
			{
				if ((_revision != value))
				{
					this.OnRevisionChanging(value);
					this._revision = value;
					this.OnRevisionChanged();
				}
			}
		}
	}
	
	[Table(Name="tokens")]
	public partial class tokens : System.ComponentModel.INotifyPropertyChanging, System.ComponentModel.INotifyPropertyChanged
	{
		
		private static System.ComponentModel.PropertyChangingEventArgs emptyChangingEventArgs = new System.ComponentModel.PropertyChangingEventArgs("");
		
		private string _token;
		
		private string _uuid;
		
		private System.DateTime _validity;
		
		#region Extensibility Method Declarations
		partial void OnCreated();
		
		partial void OntokenChanged();
		
		partial void OntokenChanging(string value);
		
		partial void OnUUIDChanged();
		
		partial void OnUUIDChanging(string value);
		
		partial void OnvalidityChanged();
		
		partial void OnvalidityChanging(System.DateTime value);
		#endregion
		
		
		public tokens()
		{
			this.OnCreated();
		}
		
		[Column(Storage="_token", Name="token", DbType="varchar(255)", IsPrimaryKey=true, AutoSync=AutoSync.Never, CanBeNull=false)]
		[DebuggerNonUserCode()]
		public string token
		{
			get
			{
				return this._token;
			}
			set
			{
				if (((_token == value) 
							== false))
				{
					this.OntokenChanging(value);
					this.SendPropertyChanging();
					this._token = value;
					this.SendPropertyChanged("token");
					this.OntokenChanged();
				}
			}
		}
		
		[Column(Storage="_uuid", Name="UUID", DbType="char(36)", IsPrimaryKey=true, AutoSync=AutoSync.Never, CanBeNull=false)]
		[DebuggerNonUserCode()]
		public string UUID
		{
			get
			{
				return this._uuid;
			}
			set
			{
				if (((_uuid == value) 
							== false))
				{
					this.OnUUIDChanging(value);
					this.SendPropertyChanging();
					this._uuid = value;
					this.SendPropertyChanged("UUID");
					this.OnUUIDChanged();
				}
			}
		}
		
		[Column(Storage="_validity", Name="validity", DbType="datetime", AutoSync=AutoSync.Never, CanBeNull=false)]
		[DebuggerNonUserCode()]
		public System.DateTime validity
		{
			get
			{
				return this._validity;
			}
			set
			{
				if ((_validity != value))
				{
					this.OnvalidityChanging(value);
					this.SendPropertyChanging();
					this._validity = value;
					this.SendPropertyChanged("validity");
					this.OnvalidityChanged();
				}
			}
		}
		
		public event System.ComponentModel.PropertyChangingEventHandler PropertyChanging;
		
		public event System.ComponentModel.PropertyChangedEventHandler PropertyChanged;
		
		protected virtual void SendPropertyChanging()
		{
			System.ComponentModel.PropertyChangingEventHandler h = this.PropertyChanging;
			if ((h != null))
			{
				h(this, emptyChangingEventArgs);
			}
		}
		
		protected virtual void SendPropertyChanged(string propertyName)
		{
			System.ComponentModel.PropertyChangedEventHandler h = this.PropertyChanged;
			if ((h != null))
			{
				h(this, new System.ComponentModel.PropertyChangedEventArgs(propertyName));
			}
		}
	}
	
	[Table(Name="UserAccounts")]
	public partial class useraccounts : System.ComponentModel.INotifyPropertyChanging, System.ComponentModel.INotifyPropertyChanged
	{
		
		private static System.ComponentModel.PropertyChangingEventArgs emptyChangingEventArgs = new System.ComponentModel.PropertyChangingEventArgs("");
		
		private System.Nullable<int> _created;
		
		private string _email;
		
		private string _firstName;
		
		private string _lastName;
		
		private string _principalID;
		
		private string _scopeID;
		
		private string _serviceUrlS;
		
		private int _userFlags;
		
		private int _userLevel;
		
		private string _userTitle;
		
		#region Extensibility Method Declarations
		partial void OnCreated();
		
		partial void OnCreatedChanged();
		
		partial void OnCreatedChanging(System.Nullable<int> value);
		
		partial void OnEmailChanged();
		
		partial void OnEmailChanging(string value);
		
		partial void OnFirstNameChanged();
		
		partial void OnFirstNameChanging(string value);
		
		partial void OnLastNameChanged();
		
		partial void OnLastNameChanging(string value);
		
		partial void OnPrincipalIDChanged();
		
		partial void OnPrincipalIDChanging(string value);
		
		partial void OnScopeIDChanged();
		
		partial void OnScopeIDChanging(string value);
		
		partial void OnServiceURLsChanged();
		
		partial void OnServiceURLsChanging(string value);
		
		partial void OnUserFlagsChanged();
		
		partial void OnUserFlagsChanging(int value);
		
		partial void OnUserLevelChanged();
		
		partial void OnUserLevelChanging(int value);
		
		partial void OnUserTitleChanged();
		
		partial void OnUserTitleChanging(string value);
		#endregion
		
		
		public useraccounts()
		{
			this.OnCreated();
		}
		
		[Column(Storage="_created", Name="Created", DbType="int", AutoSync=AutoSync.Never)]
		[DebuggerNonUserCode()]
		public System.Nullable<int> Created
		{
			get
			{
				return this._created;
			}
			set
			{
				if ((_created != value))
				{
					this.OnCreatedChanging(value);
					this.SendPropertyChanging();
					this._created = value;
					this.SendPropertyChanged("Created");
					this.OnCreatedChanged();
				}
			}
		}
		
		[Column(Storage="_email", Name="Email", DbType="varchar(64)", AutoSync=AutoSync.Never)]
		[DebuggerNonUserCode()]
		public string Email
		{
			get
			{
				return this._email;
			}
			set
			{
				if (((_email == value) 
							== false))
				{
					this.OnEmailChanging(value);
					this.SendPropertyChanging();
					this._email = value;
					this.SendPropertyChanged("Email");
					this.OnEmailChanged();
				}
			}
		}
		
		[Column(Storage="_firstName", Name="FirstName", DbType="varchar(64)", AutoSync=AutoSync.Never, CanBeNull=false)]
		[DebuggerNonUserCode()]
		public string FirstName
		{
			get
			{
				return this._firstName;
			}
			set
			{
				if (((_firstName == value) 
							== false))
				{
					this.OnFirstNameChanging(value);
					this.SendPropertyChanging();
					this._firstName = value;
					this.SendPropertyChanged("FirstName");
					this.OnFirstNameChanged();
				}
			}
		}
		
		[Column(Storage="_lastName", Name="LastName", DbType="varchar(64)", AutoSync=AutoSync.Never, CanBeNull=false)]
		[DebuggerNonUserCode()]
		public string LastName
		{
			get
			{
				return this._lastName;
			}
			set
			{
				if (((_lastName == value) 
							== false))
				{
					this.OnLastNameChanging(value);
					this.SendPropertyChanging();
					this._lastName = value;
					this.SendPropertyChanged("LastName");
					this.OnLastNameChanged();
				}
			}
		}
		
		[Column(Storage="_principalID", Name="PrincipalID", DbType="char(36)", IsPrimaryKey=true, AutoSync=AutoSync.Never, CanBeNull=false)]
		[DebuggerNonUserCode()]
		public string PrincipalID
		{
			get
			{
				return this._principalID;
			}
			set
			{
				if (((_principalID == value) 
							== false))
				{
					this.OnPrincipalIDChanging(value);
					this.SendPropertyChanging();
					this._principalID = value;
					this.SendPropertyChanged("PrincipalID");
					this.OnPrincipalIDChanged();
				}
			}
		}
		
		[Column(Storage="_scopeID", Name="ScopeID", DbType="char(36)", AutoSync=AutoSync.Never, CanBeNull=false)]
		[DebuggerNonUserCode()]
		public string ScopeID
		{
			get
			{
				return this._scopeID;
			}
			set
			{
				if (((_scopeID == value) 
							== false))
				{
					this.OnScopeIDChanging(value);
					this.SendPropertyChanging();
					this._scopeID = value;
					this.SendPropertyChanged("ScopeID");
					this.OnScopeIDChanged();
				}
			}
		}
		
		[Column(Storage="_serviceUrlS", Name="ServiceURLs", DbType="text", AutoSync=AutoSync.Never)]
		[DebuggerNonUserCode()]
		public string ServiceURLs
		{
			get
			{
				return this._serviceUrlS;
			}
			set
			{
				if (((_serviceUrlS == value) 
							== false))
				{
					this.OnServiceURLsChanging(value);
					this.SendPropertyChanging();
					this._serviceUrlS = value;
					this.SendPropertyChanged("ServiceURLs");
					this.OnServiceURLsChanged();
				}
			}
		}
		
		[Column(Storage="_userFlags", Name="UserFlags", DbType="int", AutoSync=AutoSync.Never, CanBeNull=false)]
		[DebuggerNonUserCode()]
		public int UserFlags
		{
			get
			{
				return this._userFlags;
			}
			set
			{
				if ((_userFlags != value))
				{
					this.OnUserFlagsChanging(value);
					this.SendPropertyChanging();
					this._userFlags = value;
					this.SendPropertyChanged("UserFlags");
					this.OnUserFlagsChanged();
				}
			}
		}
		
		[Column(Storage="_userLevel", Name="UserLevel", DbType="int", AutoSync=AutoSync.Never, CanBeNull=false)]
		[DebuggerNonUserCode()]
		public int UserLevel
		{
			get
			{
				return this._userLevel;
			}
			set
			{
				if ((_userLevel != value))
				{
					this.OnUserLevelChanging(value);
					this.SendPropertyChanging();
					this._userLevel = value;
					this.SendPropertyChanged("UserLevel");
					this.OnUserLevelChanged();
				}
			}
		}
		
		[Column(Storage="_userTitle", Name="UserTitle", DbType="varchar(64)", AutoSync=AutoSync.Never, CanBeNull=false)]
		[DebuggerNonUserCode()]
		public string UserTitle
		{
			get
			{
				return this._userTitle;
			}
			set
			{
				if (((_userTitle == value) 
							== false))
				{
					this.OnUserTitleChanging(value);
					this.SendPropertyChanging();
					this._userTitle = value;
					this.SendPropertyChanged("UserTitle");
					this.OnUserTitleChanged();
				}
			}
		}
		
		public event System.ComponentModel.PropertyChangingEventHandler PropertyChanging;
		
		public event System.ComponentModel.PropertyChangedEventHandler PropertyChanged;
		
		protected virtual void SendPropertyChanging()
		{
			System.ComponentModel.PropertyChangingEventHandler h = this.PropertyChanging;
			if ((h != null))
			{
				h(this, emptyChangingEventArgs);
			}
		}
		
		protected virtual void SendPropertyChanged(string propertyName)
		{
			System.ComponentModel.PropertyChangedEventHandler h = this.PropertyChanged;
			if ((h != null))
			{
				h(this, new System.ComponentModel.PropertyChangedEventArgs(propertyName));
			}
		}
	}
}
