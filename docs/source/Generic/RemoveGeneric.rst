RemoveGeneric Method
====================

Remove a generic key/value pair.

Request Format
--------------

+-----------------+----------------------------+--------+------------+
| *Parameter*     | *Description*              | *Type* | *Required* |
+=================+============================+========+============+
| `RequestMethod` | RemoveGeneric              | String | Yes        |
+-----------------+----------------------------+--------+------------+
| `OwnerID`       | UUID of the user the entry | UUID   | Yes        |
|                 | belongs to                 |        |            |
+-----------------+----------------------------+--------+------------+
| `Type`          | Type of key/value pair to  | String | Yes        |
|                 | remove                     |        |            |
+-----------------+----------------------------+--------+------------+
| `Key`           | The key to remove          | String | Yes        |
+-----------------+----------------------------+--------+------------+

Sample request: ::

    RequestMethod=RemoveGeneric
    &OwnerID=e4709e21-9a3f-4276-b3cc-22224bdf0199
    &Type=Friend
    &Key=990ea180-2828-11df-8a39-0800200c9a66


Response Format
---------------

+-------------+-------------------------------------------------+---------+
| *Parameter* | *Description*                                   | *Type*  |
+=============+=================================================+=========+
| `Success`   | True if the entry was removed or did not exist, | Boolean |
|             | False if a Message was returned                 |         |
+-------------+-------------------------------------------------+---------+
| `Message`   | Error message                                   | String  |
+-------------+-------------------------------------------------+---------+

Success: ::

    {
        "Success":true
    }


Failure: ::

    {
        "Success":false,
        "Message":"An unknown error occurred"
    }

