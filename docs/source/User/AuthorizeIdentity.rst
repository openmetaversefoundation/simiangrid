AuthorizeIdentity Method
========================

Validate an identifier and credential, resolving it to a UserID.


Request Format
--------------

+-----------------+---------------------------+--------+------------+
| *Parameter*     | *Description*             | *Type* | *Required* |
+=================+===========================+========+============+
| `RequestMethod` | AuthorizeIdentity         | String | Yes        | 
+-----------------+---------------------------+--------+------------+
| `Identifier`    | Identifier, for example a | String | Yes        |
|                 | login name                |        |            |
+-----------------+---------------------------+--------+------------+
| `Credential`    | Credential, for example a | String | Yes        |
|                 | hashed password           |        |            |
+-----------------+---------------------------+--------+------------+
| `Type`          | Identity type, such as    | String | Yes        |
|                 | "md5hash"                 |        |            | 
+-----------------+---------------------------+--------+------------+

Sample request: ::

    RequestMethod=AuthorizeIdentity
    &Identifier=loginname
    &Credential=%241%245f4dcc3b5aa765d61d8327deb882cf99
    &Type=md5hash


Response Format
---------------

+-------------+------------------------------------------+---------+
| *Parameter* | *Description*                            | *Type*  |
+=============+==========================================+=========+
| `Success`   | True if a UserID was returned, False if  | Boolean |
|             | a Message was returned                   |         |
+-------------+------------------------------------------+---------+
| `UserID`    | UUID of the successfully authorized user | UUID    | 
+-------------+------------------------------------------+---------+
| `Message`   | Error message                            | String  | 
+-------------+------------------------------------------+---------+

Success: ::

    {
        "Success":true,
        "UserID":"c006082b-80eb-4d17-90ff-224412c574ea"
    }


Failure: ::

    {
        "Success":false,
        "Message":"Missing identity or invalid credentials"
    }

