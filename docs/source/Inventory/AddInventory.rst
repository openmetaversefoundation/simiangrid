AddInventory Method
===================

Initialize a fresh inventory for the specified user.


Request Format
--------------

+-----------------+------------------------------------+--------+------------+
| *Parameter*     | *Description*                      | *Type* | *Required* |
+=================+====================================+========+============+
| `RequestMethod` | AddInventory                       | String | Yes        |
+-----------------+------------------------------------+--------+------------+
| `OwnerID`       | UUID of the user this inventory    | UUID   | Yes        |
|                 | will belong to                     |        |            |
+-----------------+------------------------------------+--------+------------+
| `AvatarType`    | String identifier of the template  | String | Optional   |
|                 | avatar inventory to clone,         |        |            |
|                 | defaults to "DefaultAvatar"        |        |            |
+-----------------+------------------------------------+--------+------------+

|

Sample request: ::

    RequestMethod=AddInventory
    &OwnerID=e4709e21-9a3f-4276-b3cc-22224bdf0199

Note that user inventories will always have a root folder that shares
the same UUID as the user. This is a design choice, to optimize away
calls to get a user's root inventory folder UUID.


Response Format
---------------

+-------------+----------------------------------------------------+---------+
| *Parameter* | *Description*                                      | *Type*  |
+=============+====================================================+=========+
| `Success`   | True if a FolderID was returned, False if a        | Boolean |
|             | Message was returned                               |         |
+-------------+----------------------------------------------------+---------+
| `FolderID`  | UUID of the root folder. This is always the same   | UUID    |
|             | UUID as the `OwnerID`                              |         |
+-------------+----------------------------------------------------+---------+
| `Message`   | Error message                                      | String  |
+-------------+----------------------------------------------------+---------+

|

Success: ::

    {
        "Success":true,
        "FolderID":"e4709e21-9a3f-4276-b3cc-22224bdf0199"
    }

|

Failure: ::

    {
        "Success":false,
        "Message":"User account not found"
    }

