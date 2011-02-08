RemoveInventoryNode Method
==========================

Remove an inventory node

Request Format
--------------

+-----------------+-------------------------------+--------+------------+
| *Parameter*     | *Description*                 | *Type* | *Required* |
+=================+===============================+========+============+
| `RequestMethod` | RemoveInventoryNode           | String | Yes        |
+-----------------+-------------------------------+--------+------------+
| `OwnerID`       | UUID of the inventory owner   | UUID   | Yes        |
+-----------------+-------------------------------+--------+------------+
| `ItemID`        | UUID of the inventory node to | UUID   | Yes        |
|                 | remove                        |        |            |
+-----------------+-------------------------------+--------+------------+

Sample request: ::

    RequestMethod=RemoveInventoryNode
    &OwnerID=a187cee3-a21d-4ff1-8a48-30df0c15ce00
    &ItemID=ceb3c0c7-c610-49cb-b89f-479b67f1fcbe


Response Format
---------------

+-------------+-------------------------------------------------+---------+
| *Parameter* | *Description*                                   | *Type*  |
+=============+=================================================+=========+
| `Success`   | True if the node was removed or did not exist,  | Boolean |
|             | False if a Message was returned                 |         | 
+-------------+-------------------------------------------------+---------+
| `Message`   | Error message                                   | String  | 
+-------------+-------------------------------------------------+---------+

Success: ::

    {
        "Success":true,
    }


Failure: ::

    {
        "Success":false,
        "Message":"An unknown error occurred"
    }

