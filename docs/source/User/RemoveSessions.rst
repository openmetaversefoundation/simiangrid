RemoveSessions Method
=====================

Remove one or more login sessions.

Request Format
--------------

+-----------------+-------------------------------+--------+------------+
| *Parameter*     | *Description*                 | *Type* | *Required* |
+=================+===============================+========+============+
| `RequestMethod` | RemoveSessions                | String | Yes        |
+-----------------+-------------------------------+--------+------------+
| `SceneID`       | Remove all sessions currently | UUID   | Yes        |
|                 | residing in this scene        |        |            |
+-----------------+-------------------------------+--------+------------+

Sample request: ::

    RequestMethod=RemoveSessions
    &SceneID=efb00dbb-d4ab-46dc-aebc-4ba83288c3c0


Response Format
---------------

+-------------+--------------------------------------------+---------+
| *Parameter* | *Description*                              | *Type*  |
+=============+============================================+=========+
| `Success`   | True if all requested sessions were erased | Boolean |
|             | or there were no sessions to erase, False  |         |
|             | if a Message was returned                  |         |
+-------------+--------------------------------------------+---------+
| `Message`   | Error message                              | String  |
+-------------+--------------------------------------------+---------+

Success: ::

    {
        "Success":true,
    }


Failure: ::

    {
        "Success":false,
        "Message":"An unknown error occurred"
    }

