AddInventoryFolder Method
=========================

Create or update an inventory folder.


Request Format
--------------

+-----------------+------------------------------+-------------+-------------+
|  *Parameter*    |  *Description*               |  *Type*     |  *Required* |
+=================+==============================+=============+=============+
| `RequestMethod` | AddInventoryFolder           | String      | Yes         |
+-----------------+------------------------------+-------------+-------------+
| `FolderID`      | UUID of the folder to create | UUID        | Optional    |
|                 | or update                    |             |             |
+-----------------+------------------------------+-------------+-------------+
| `ParentID`      | The Parent folder UUID       | UUID        | Yes         |
+-----------------+------------------------------+-------------+-------------+
| `OwnerID`       | UUID of the folder owner     | UUID        | Yes         |
+-----------------+------------------------------+-------------+-------------+
| `Name`          | Folder name                  | String      | Yes         |
+-----------------+------------------------------+-------------+-------------+
| `ContentType`   | Preferred content type for   | ContentType | Optional    |
|                 | this folder. Defaults to     |             |             |
|                 | application/octet-stream     |             |             |
+-----------------+------------------------------+-------------+-------------+
| `ExtraData`     | Free form JSON data          | JSON        | Optional    |
|                 | associated with this folder  |             |             |
+-----------------+------------------------------+-------------+-------------+


ID is only required for updating an existing folder

|

Sample request: ::

    RequestMethod=AddInventoryFolder
    &ParentID=40f36b6d-d239-4ade-b354-f0f5cb9ce8f8
    &OwnerID=8e21c2df-32b2-472c-b09b-88b909ba4c86
    &Name=Default+Appearance


Response Format
---------------

+-------------+----------------------------------------------------+---------+
| *Parameter* |  *Description*                                     | *Type*  |
+=============+====================================================+=========+
| `Success`   | True if a FolderID was returned, False if a        | Boolean |
|             | Message was returned                               |         |
+-------------+----------------------------------------------------+---------+
| `FolderID`  | UUID of the created or updated folder              | UUID    |
+-------------+----------------------------------------------------+---------+
| `Message`   | Error message                                      | String  |
+-------------+----------------------------------------------------+---------+

|

Success: ::

    {
        "Success":true,
        "FolderID":"2cf49939-b9f0-45af-ad21-b3441de85f52"
    }

|

Failure: ::

    {
        "Success":false,
        "Message":"Parent folder does not exist"
    }

