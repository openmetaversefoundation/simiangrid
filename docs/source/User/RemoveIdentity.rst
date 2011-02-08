RemoveIdentity Method
=====================

Remove an identity.


Request Format
--------------

+-----------------+-------------------------------+--------+------------+
| *Parameter*     | *Description*                 | *Type* | *Required* |
+=================+===============================+========+============+
| `RequestMethod` | RemoveIdentity                | String | Yes        |
+-----------------+-------------------------------+--------+------------+
| `Identifier`    | Identifier to remove          | String | Yes        |
+-----------------+-------------------------------+--------+------------+
| `Type`          | Identity type to remove, such | String | Yes        |
|                 | as "md5hash"                  |        |            |
+-----------------+-------------------------------+--------+------------+

Sample request: ::

    RequestMethod=RemoveIdentity
    &Identifier=loginname
    &Type=md5hash


Response Format
---------------

+-------------+---------------------------------------------+---------+
| *Parameter* | *Description*                               | *Type*  |
+=============+=============================================+=========+
| `Success`   | True if the identity was removed or did not | Boolean |
|             | exist, False if a Message was returned      |         |
+-------------+---------------------------------------------+---------+
| `Message`   | Error message                               | String  |
+-------------+---------------------------------------------+---------+

Success: ::

    {
        "Success":true,
    }


Failure: ::

    {
        "Success":false,
        "Message":"An unknown error occurred"
    }

