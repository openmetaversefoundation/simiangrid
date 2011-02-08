GetFriends
==========

Returns a list of friends for a user.


Request Format
--------------

+-----------------+----------------------------------+--------+------------+
| *Parameter*     | *Description*                    | *Type* | *Required* |
+=================+==================================+========+============+
| `RequestMethod` | GetFriends                       | String | Yes        | 
+-----------------+----------------------------------+--------+------------+
| `UserID`        | UUID of the user account to      | UUID   | Yes        |
|                 | retrieve friends for             |        |            |
+-----------------+----------------------------------+--------+------------+

Sample request: ::

    RequestMethod=GetFriends
    &UserID=efb00dbb-d4ab-46dc-aebc-4ba83288c3c0


Response Format
---------------

+-------------+---------------------------------------------+---------+
| *Parameter* | *Description*                               | *Type*  |
+=============+=============================================+=========+
| `Success`   | True if a Friends array was returned, False | Boolean |
|             | if a Message was returned                   |         |
+-------------+---------------------------------------------+---------+
| `Friends`   | Array of friend objects, see below          | Array   |
+-------------+---------------------------------------------+---------+
| `Message`   | Error message                               | String  |
+-------------+---------------------------------------------+---------+


Friend Object
-------------

+--------------------+------------------------------------------+--------+
| *Parameter*        | *Description*                            | *Type* |
+====================+==========================================+========+
| `UserID`           | UUID for the friend's user account       | UUID   | 
+--------------------+------------------------------------------+--------+
| `ExtraData`        | Free form JSON data that the user has    | JSON   |
|                    | associated with this friend              |        | 
+--------------------+------------------------------------------+--------+
| `FriendsExtraData` | Free form JSON data that this friend has | JSON   |
|                    | associated with the user                 |        | 
+--------------------+------------------------------------------+--------+

Success: ::

    {
        "Success":true,
        "Friends":
        [
            {
                "UserID":"153f5a45-8d4e-4b11-830a-133a966761fd",
                "ExtraData":{}
                "FriendsExtraData":{}
            },
            {
                "UserID":"dbe13051-f41d-4197-b600-db5fa00532e2",
                "ExtraData":{}
                "FriendsExtraData":{}
            },
        ]
    }


Failure: ::

    {
        "Success":false,
        "Message":"User not found"
    }

