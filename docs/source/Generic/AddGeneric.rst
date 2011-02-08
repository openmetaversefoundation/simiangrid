AddGeneric Method
=================

Add a generic key/value pair with a type and owner.

Request Format
--------------

+-----------------+--------------------------------------+--------+------------+
| *Parameter*     | *Description*                        | *Type* | *Required* | 
+=================+======================================+========+============+
| `RequestMethod` | AddGeneric                           | String | Yes        |
+-----------------+--------------------------------------+--------+------------+
| `OwnerID`       | UUID of the user this entry will     | UUID   | Yes        |
|                 | belong to                            |        |            |
+-----------------+--------------------------------------+--------+------------+
| `Type`          | Describes the type of key/value pair | String | Yes        |
|                 | being stored                         |        |            |
+-----------------+--------------------------------------+--------+------------+
| `Key`           | The key to store. The combination of | String | Yes        |
|                 | `OwnerID`, `Type`, and `Key` form a  |        |            |
|                 | unique index                         |        |            |
+-----------------+--------------------------------------+--------+------------+
| `Value`         | A free-form string containing the    | String | Yes        |
|                 | value to store                       |        |            | 
+-----------------+--------------------------------------+--------+------------+

Sample request: ::

    RequestMethod=AddGeneric
    &OwnerID=e4709e21-9a3f-4276-b3cc-22224bdf0199
    &Type=Friend
    &Key=990ea180-2828-11df-8a39-0800200c9a66
    &Value=%7B%22MyFlags%22%3A0%2C%22TheirFlags%22%3A38%7D


Response Format
---------------

+-------------+-------------------------------------------+---------+
| *Parameter* | *Description*                             | *Type*  |
+=============+===========================================+=========+
| `Success`   | True if the entry was created or updated, | Boolean |
|             | False if a Message was returned           |         | 
+-------------+-------------------------------------------+---------+
| `Message`   | Error message                             | String  |
+-------------+-------------------------------------------+---------+

Success: ::

    {
        "Success":true
    }


Failure: ::

    {
        "Success":false,
        "Message":"An unknown error occurred"
    }

