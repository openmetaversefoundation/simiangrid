GetUsers Method
===============

Returns partial user account data for accounts matching search criteria.

Request Format
--------------

+-----------------+-------------------------------+---------+------------+
| *Parameter*     | *Description*                 | *Type*  | *Required* |
+=================+===============================+=========+============+
| `RequestMethod` | GetUsers                      | String  | Yes        |
+-----------------+-------------------------------+---------+------------+
| `NameQuery`     | Search query to match against | String  | Yes        |
|                 | account names                 |         |            |
+-----------------+-------------------------------+---------+------------+
| `MaxNumber`     | Maximum number of accounts to | Integer | Optional   |
|                 | return                        |         |            |
+-----------------+-------------------------------+---------+------------+

Sample request: ::

    RequestMethod=GetUsers
    &NameQuery=John


Response Format
---------------

+-------------+-------------------------------------+---------+
| *Parameter* | *Description*                       | *Type*  |
+=============+=====================================+=========+
| `Success`   | True if a Users array was returned, | Boolean |
|             | False if a Message was returned     |         |
+-------------+-------------------------------------+---------+
| `Users`     | Array of user objects, see below    | Array   |
+-------------+-------------------------------------+---------+
| `Message`   | Error message                       | String  |
+-------------+-------------------------------------+---------+


User Object
-----------

+---------------+-------------------------------------------------+---------+
| *Parameter*   | *Description*                                   | *Type*  |
+===============+=================================================+=========+
| `UserID`      | UUID for the user account                       | UUID    |
+---------------+-------------------------------------------------+---------+
| `Name`        | Account name                                    | String  |
+---------------+-------------------------------------------------+---------+
| `Email`       | Account e-mail address                          | String  |
+---------------+-------------------------------------------------+---------+
| `AccessLevel` | 0-255 value indicating the access level of this | Integer |
|               | user. Described in more detail on the           |         |
|               | AccessLevel page                                |         |
+---------------+-------------------------------------------------+---------+

  * Note that this does not return all metadata attached to an account like GetUser. Only the primary user account details are returned for each match.

Success: ::

    {
        "Success":true,
        "Users":
        [
            {
                "UserID":"efb00dbb-d4ab-46dc-aebc-4ba83288c3c0",
                "Name":"John Doe",
                "Email":"john.doe@email.com",
                "AccessLevel":0
            }
        ]
    }


Failure: ::


    {
        "Success":false,
        "Message":"An unknown error occurred"
    }

