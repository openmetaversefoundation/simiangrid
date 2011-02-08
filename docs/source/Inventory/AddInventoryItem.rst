AddInventoryItem Method
=======================

Create or update an inventory item.


Request Format
--------------

+-----------------+----------------------------+--------+--------------------+
| *Parameter*     | *Description*              | *Type* | *Required*         |
+=================+============================+========+====================+
| `RequestMethod` | AddInventoryItem           | String | Yes                |
+-----------------+----------------------------+--------+--------------------+
| `ItemID`        | UUID of the item to create | UUID   | Optional           |
|                 | or update                  |        |                    |
+-----------------+----------------------------+--------+--------------------+
| `AssetID`       | UUID of the asset          | UUID   | Yes                |
|                 | associated with this item  |        |                    |
+-----------------+----------------------------+--------+--------------------+
| `ParentID`      | UUID of the parent folder  | UUID   | Yes                | 
+-----------------+----------------------------+--------+--------------------+
| `OwnerID`       | UUID of the item owner     | UUID   | Yes                | 
+-----------------+----------------------------+--------+--------------------+
| `Name`          | Name of the item           | String | Yes                | 
+-----------------+----------------------------+--------+--------------------+
| `Description`   | Description of the item    | String | Optional, defaults |
|                 |                            |        | to an empty string | 
+-----------------+----------------------------+--------+--------------------+
| `CreatorID`     | UUID of the item creator,  | UUID   | Optional, defaults |
|                 | if different from the      |        | to the asset       |
|                 | asset creator              |        | creator            |
+-----------------+----------------------------+--------+--------------------+
| `ContentType`   | MIME type of the asset     | String | Optional, defaults |
|                 | this item points to        |        | to the asset       |
|                 |                            |        | content type       | 
+-----------------+----------------------------+--------+--------------------+
| `ExtraData`     | Free form JSON data        | JSON   | Optional           |
|                 | associated with this item  |        |                    |
+-----------------+----------------------------+--------+--------------------+


`ItemID` is only required for updating an existing item

Sample request: ::

    RequestMethod=AddInventoryItem
    &AssetID=1def56c1-6b83-492d-a7c1-2252b5545a82
    &ParentID=40f36b6d-d239-4ade-b354-f0f5cb9ce8f8
    &OwnerID=8e21c2df-32b2-472c-b09b-88b909ba4c86
    &Name=A+Picture

|

Response Format
---------------

+-------------+----------------------------------------------------+---------+
| *Parameter* | *Description*                                      | *Type*  |
+=============+====================================================+=========+
| `Success`   | True if an ItemID was returned, False if a Message | Boolean |
|             | was returned                                       |         |
+-------------+----------------------------------------------------+---------+
| `ItemID`    | UUID of the created or updated item                | UUID    |
+-------------+----------------------------------------------------+---------+
| `Message`   | Error message                                      | String  |
+-------------+----------------------------------------------------+---------+

Success: ::


    {
        "Success":true,
        "ItemID":"2cf49939-b9f0-45af-ad21-b3441de85f52"
    }

|

Failure: ::


    {
        "Success":false,
        "Message":"Invalid AssetID"
    }

