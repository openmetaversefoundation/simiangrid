RemoveUser Method
=================

Remove a user account (dangerous).

*WARNING:* This method should only be used in special circumstances
where it is known that no content in any scenes or services references
the UserID that will be removed.

Request Format
--------------

+-----------------+----------------------------+--------+------------+
| *Parameter*     | *Description*              | *Type* | *Required* |
+-----------------+----------------------------+--------+------------+
| `RequestMethod` | RemoveUser                 | String | Yes        |
+-----------------+----------------------------+--------+------------+
| `UserID`        | UUID of the user to remove | UUID   | Yes        |
+-----------------+----------------------------+--------+------------+

Sample request: ::

    RequestMethod=RemoveUser
    &UserID=efb00dbb-d4ab-46dc-aebc-4ba83288c3c0


Response Format
---------------

+-------------+------------------------------------------------+---------+
| *Parameter* | *Description*                                  | *Type*  |
+=============+================================================+=========+
| `Success`   | True if the user and all user data fields were | Boolean |
|             | removed (or the user already did not exist),   |         |
|             | False if a Message was returned                |         |
+-------------+------------------------------------------------+---------+
| `Message`   | Error message                                  | String  |
+-------------+------------------------------------------------+---------+


Success: ::

    {
        "Success":true,
    }


Failure: ::

    {
        "Success":false,
        "Message":"This service does not allow user deletion"
    }

