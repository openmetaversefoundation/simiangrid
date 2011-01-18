AddUserData Method
==================

Create or update user account information.


Request Format
--------------

+---------------------+-------------------------------+--------+------------+
| *Parameter*         | *Description*                 | *Type* | *Required* |
+=====================+===============================+========+============+
| `RequestMethod`     | AddUserData                   | String | Yes        |
+---------------------+-------------------------------+--------+------------+
| `UserID`            | UUID of the user to create or | UUID   | Yes        |
|                     | update information for        |        |            |
+---------------------+-------------------------------+--------+------------+
| *`Variable Fields`* | Free form key value pairs^1^  | `*`^1^ | Yes        |
+---------------------+-------------------------------+--------+------------+

  * ^1^Values are stored as free form strings, but applications should enforce more strict type checking
  * Do not use this method to store login credentials such as a password hash. Use the AddIdentity method instead
  * The field names "UserID", "Name", and "Email" are reserved


Common Fields
-------------

+-----------------+---------------------------------+---------------+
| *Parameter*     | *Description*                   |  *Type*       | 
+=================+=================================+===============+
| `CreationDate`  | UTC timestamp when this account | UTC Timestamp |
|                 | was created                     |               |
+-----------------+---------------------------------+---------------+
| `LastLoginDate` | UTC timestamp when this account | UTC Timestamp |
|                 | last logged in                  |               | 
+-----------------+---------------------------------+---------------+
| `HomeLocation`  | Uri describing this account's   | Uri           |
|                 | home location                   |               |
+-----------------+---------------------------------+---------------+
| `LastLocation`  | Uri describing this account's   | Uri           |
|                 | last known location             |               | 
+-----------------+---------------------------------+---------------+
| `Suspended`     | True if this account is         | Boolean       |
|                 | currently suspended             |               | 
+-----------------+---------------------------------+---------------+

Sample request: ::

    RequestMethod=AddUserData
    &UserID=efb00dbb-d4ab-46dc-aebc-4ba83288c3c0
    &LastLoginDate=1265252882
    &Suspended=1


Response Format
---------------

+-------------+-----------------------------------------+---------+
| *Parameter* | *Description*                           | *Type*  | 
+=============+=========================================+=========+
| `Success`   | True if the request succeeded, False if | Boolean |
|             | a Message was returned                  |         | 
+-------------+-----------------------------------------+---------+
| `Message`   | Error message                           | String  | 
+-------------+-----------------------------------------+---------+

Success: ::

    {
        "Success":true,
    }


Failure: ::

    {
        "Success":false,
        "Message":"Field name is reserved"
    }

