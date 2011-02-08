AddUser Method
==============

Create or update a user account.


Request Format
--------------

+-----------------+-------------------------------+---------+------------+
| *Parameter*     | *Description*                 | *Type*  | *Required* |
+=================+===============================+=========+============+
| `RequestMethod` | AddUser                       | String  | Yes        |
+-----------------+-------------------------------+---------+------------+
| `UserID`        | UUID of the user to create or | UUID    | Yes        |
|                 | update                        |         |            |
+-----------------+-------------------------------+---------+------------+
| `Name`          | Account name                  | String  | Yes        |
+-----------------+-------------------------------+---------+------------+
| `Email`         | E-mail address associated     | String  | Yes        |
|                 | with this account             |         |            |
+-----------------+-------------------------------+---------+------------+
| `AccessLevel`   | Value from 0-255 indicating   | Integer | Optional,  |
|                 | the access level of this      |         | defaults   |
|                 | account (higher implies more  |         | to 0       |
|                 | access). More detail on the   |         |            |
|                 | AccessLevel page              |         |            | 
+-----------------+-------------------------------+---------+------------+

Sample request: ::

    RequestMethod=AddUser
    &UserID=efb00dbb-d4ab-46dc-aebc-4ba83288c3c0
    &Name=John+Doe
    &Email=john.doe%40email.com


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
        "Message":"An unknown error occurred"
    }

