RemoveScene Method
==================

Remove information about a scene from the grid service.

Request Format
--------------

+-----------------+-----------------------------+--------+------------+
| *Parameter*     | *Description*               | *Type* | *Required* |
+=================+=============================+========+============+
| `RequestMethod` | RemoveScene                 | String | Yes        |
+-----------------+-----------------------------+--------+------------+
| `SceneID`       | UUID of the scene to remove | UUID   | Yes        |
+-----------------+-----------------------------+--------+------------+

Sample request: ::

    RequestMethod=RemoveScene
    &SceneID=ceb3c0c7-c610-49cb-b89f-479b67f1fcbe


Response Format
---------------

+-------------+-------------------------------------------------+---------+
| *Parameter* | *Description*                                   | *Type*  |
+=============+=================================================+=========+
| `Success`   | True if the scene was removed or did not exist, | Boolean |
|             | False if a Message was returned                 |         |
+-------------+-------------------------------------------------+---------+
| `Message`   | Error message                                   | String  |
+-------------+-------------------------------------------------+---------+

Success: ::

    {
        "Success":true,
    }


Failure: ::

    {
        "Success":false,
        "Message":"An unknown error occurred"
    }

