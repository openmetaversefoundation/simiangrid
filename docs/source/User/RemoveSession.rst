RemoveSession Method
====================

Remove a single login session.


Request Format
--------------

+-----------------+------------------------------+--------+------------+
| *Parameter*     | *Description*                | *Type* | *Required* |
+=================+==============================+========+============+
| `RequestMethod` | RemoveSession                | String | Yes        |
+-----------------+------------------------------+--------+------------+
| `SessionID`     | Session identifier to remove | UUID   | Yes        |
+-----------------+------------------------------+--------+------------+


Sample request: ::

    RequestMethod=RemoveSession
    &SessionID=efb00dbb-d4ab-46dc-aebc-4ba83288c3c0


Response Format
---------------

+-------------+-------------------------------------------+---------+
| *Parameter* | *Description*                             | *Type*  |
+=============+===========================================+=========+
| `Success`   | True if the requested session was removed | Boolean |
|             | or did not exist, False if a Message was  |         |
|             | returned                                  |         |
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
        "Message":"An unknown error occurred"
    }

