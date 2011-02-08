GetSceneStats Method
====================

Returns grid scene stats.

Request Format
--------------

+-----------------+---------------+--------+-------------+
| *Parameter*     | *Description* | *Type* | *Required*  | 
+=================+===============+========+=============+
| `RequestMethod` | GetSceneStats | String | Yes         | 
+-----------------+---------------+--------+-------------+

Sample request: ::

    RequestMethod=GetSceneStats


Response Format
---------------

+--------------+------------------------------------------+---------+
| *Parameter*  | *Description*                            | *Type*  |
+==============+==========================================+=========+
| `Success`    | True if a SceneCount was returned, False | Boolean |
|              | if a Message was returned                |         |
+--------------+------------------------------------------+---------+
| `SceneCount` | Total count of scenes                    | Integer |
+--------------+------------------------------------------+---------+
| `Message`    | Error message                            | String  |
+--------------+------------------------------------------+---------+

Success: ::

    {
        "Success":true,
        "SceneCount":42
    }


Failure: ::

    {
        "Success":false,
        "Message":"Database query error"
    }

