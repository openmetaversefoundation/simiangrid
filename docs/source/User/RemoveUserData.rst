RemoveUserData Method
=====================

Remove user account information.

Request Format
--------------

+-----------------+---------------------------------+--------+------------+
| *Parameter*     | *Description*                   | *Type* | *Required* | 
+=================+=================================+========+============+
| `RequestMethod` | RemoveUserData                  | String | Yes        |
+-----------------+---------------------------------+--------+------------+
| `UserID`        | UUID of the user for which info | UUID   | Yes        |
|                 | should be deleted               |        |            |
+-----------------+---------------------------------+--------+------------+
| `Key`           | Key to remove                   | String | Yes        |
+-----------------+---------------------------------+--------+------------+

Sample request: ::

    RequestMethod=RemoveUserData
    &UserID=913180a1-4d79-49a3-9576-05b13896a5f2
    &Key=Suspended

Response Format
---------------

+-------------+----------------------------------------+---------+
| *Parameter* | *Description*                          | *Type*  |
+=============+========================================+=========+
| `Success`   | True if key was erased or there was no | Boolean |
|             | key to erase, False if a Message was   |         |
|             | returned                               |         |
+-------------+----------------------------------------+---------+
| `Message`   | Error message                          | String  |
+-------------+----------------------------------------+---------+

Success: ::

    {
        "Success":true,
    }


Failure: ::

    {
        "Success":false,
        "Message":"An unknown error occurred"
    }

