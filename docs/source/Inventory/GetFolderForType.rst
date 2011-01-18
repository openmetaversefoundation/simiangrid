GetFolderForType Method
=======================

Retrieve an inventory folder associated with a given content type.


Request Format
--------------

+-----------------+-------------------------------+-------------+------------+
| *Parameter*     | *Description*                 | *Type*      | *Required* |
+=================+===============================+=============+============+
| `RequestMethod` | GetFolderForType              | String      | Yes        |
+-----------------+-------------------------------+-------------+------------+
| `OwnerID`       | UUID of the item or folder    | UUID        | Yes        |
|                 | owner                         |             |            |
+-----------------+-------------------------------+-------------+------------+
| `ContentType`   | The first folder with this    | ContentType | Yes        |
|                 | content type will be returned |             |            |
+-----------------+-------------------------------+-------------+------------+

Sample request: ::

    RequestMethod=GetFolderForType
    &OwnerID=9ffd5a95-b8bd-4d91-bbed-ded4c80ba151
    &ContentType=image%2Ftga

|

Response Format
---------------

+-------------+--------------------------------------------------+-----------+
| *Parameter* | *Description*                                    | *Type*    |
+=============+==================================================+===========+
| `Success`   | True if a folder was returned, False if a        | Boolean   |
|             | Message was returned                             |           |
+-------------+--------------------------------------------------+-----------+
| `Folder`    | The first matching folder                        | See below |
+-------------+--------------------------------------------------+-----------+
| `Message`   | Error message                                    | String    |
+-------------+--------------------------------------------------+-----------+

Inventory Folder Format
-----------------------

+----------------+---------------------------------------------+-------------+
| *Parameter*    | *Description*                               | *Type*      |
+================+=============================================+=============+
| `Type`         | Inventory node type, will always be         | String      |
|                | "Folder"                                    |             |
+----------------+---------------------------------------------+-------------+
| `ID`           | UUID of the folder                          | UUID        |
+----------------+---------------------------------------------+-------------+
| `ParentID`     | UUID of the parent folder, or UUID.Zero if  | UUID        |
|                | this is the root inventory folder           |             |
+----------------+---------------------------------------------+-------------+
| `OwnerID`      | UUID of the folder owner                    | UUID        |
+----------------+---------------------------------------------+-------------+
| `Name`         | Folder name                                 | String      |
+----------------+---------------------------------------------+-------------+
| `CreationDate` | UTC timestamp this folder was created       | Integer     |
+----------------+---------------------------------------------+-------------+
| `ContentType`  | Preferred content type for the folder.      | ContentType |
|                | Default is application/octet-stream         |             |
+----------------+---------------------------------------------+-------------+
| `ExtraData`    | Free-form JSON data associated with this    | JSON        |
|                | folder                                      |             |
+----------------+---------------------------------------------+-------------+
| `ChildCount`   | Total number of children in this folder     | Integer     |
+----------------+---------------------------------------------+-------------+
| `Version`      | Version number of the folder, incremented   | Integer     |
|                | each time children are added or removed     |             |
+----------------+---------------------------------------------+-------------+


Success: ::

    {
        "Success":true,
        "Folder":
        {
            "Version":2,
            "ChildCount":12,
            "ID":"cbee00bb-1f26-414a-a0aa-9c5a7156fe64",
            "ParentID":"ddac4f40-1cc0-11df-8a39-0800200c9a66",
            "OwnerID":"9ffd5a95-b8bd-4d91-bbed-ded4c80ba151",
            "Name":"TGA Textures",
            "ContentType":"image/tga",
            "ExtraData":{},
            "CreationDate":1261042614,
            "Type":"Folder"
        }
    }


Failure: ::


    {
        "Success":false,
        "Message":"No matching folder found"
    }

