RemoveUserCapabilities Method
=============================

Removes all of the capabilities associated with a UserID.


Request Format
--------------

+-----------------+---------------------------------+--------+------------+
| *Parameter*     | *Description*                   | *Type* | *Required* |
+=================+=================================+========+============+
| `RequestMethod` | RemoveUserCapabilities          | String | Yes        |
+-----------------+---------------------------------+--------+------------+
| `OwnerID`       | UUID of the owner to remove all | UUID   | Yes        |
|                 | capabilities for                |        |            |
+-----------------+---------------------------------+--------+------------+

Sample request: ::

    RequestMethod=RemoveUserCapabilities
    &OwnerID=efb00dbb-d4ab-46dc-aebc-4ba83288c3c0


Response Format
---------------

+-------------+-------------------------------------------+---------+
| *Parameter* | *Description*                             | *Type*  |
+=============+===========================================+=========+
| `Success`   | True if user capabilities were removed or | Boolean |
|             | the user had no capabilities, False if a  |         |
|             | Message was returned                      |         |
+-------------+-------------------------------------------+---------+
| `Message`   | Error message                             | String  |
+-------------+-------------------------------------------+---------+

Success: ::

    {
        "Success":true,
    }


Failure: ::

    {
        "Success":false,
        "Message":"User does not exist"
    }

