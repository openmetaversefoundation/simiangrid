GetUserStats Method
===================

Returns grid user stats.

Request Format
--------------

+-----------------+---------------+--------+------------+  
| *Parameter*     | *Description* | *Type* | *Required* | 
+=================+===============+========+============+  
| `RequestMethod` | GetUserStats  | String | Yes        | 
+-----------------+---------------+--------+------------+  

Sample request: ::

    RequestMethod=GetUserStats


Response Format
---------------

+-------------+------------------------------------+---------+
| *Parameter* | *Description*                      | *Type*  |
+=============+====================================+=========+
| `Success`   | True if a !UserCount was returned, | Boolean |
|             | False if a Message was returned    |         | 
+-------------+------------------------------------+---------+
| `UserCount` | Total count of user accounts       | Integer |
+-------------+------------------------------------+---------+
| `Message`   | Error message                      | String  |
+-------------+------------------------------------+---------+


Success: ::

    {
        "Success":true,
        "UserCount":42
    }


Failure: ::

    {
        "Success":false,
        "Message":"Database query error"
    }

