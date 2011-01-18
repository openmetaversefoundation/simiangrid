UpdateSession Method
====================

Add or update data attached to a login session.


Request Format
--------------

+-------------------+---------------------------------+----------+------------+
| *Parameter*       | *Description*                   | *Type*   | *Required* |
+===================+=================================+==========+============+
| `RequestMethod`   | UpdateSession                   | String   | Yes        |
+-------------------+---------------------------------+----------+------------+
| `SessionID`       | UUID of the session to update   | UUID     | Yes        |
+-------------------+---------------------------------+----------+------------+
| `SecureSessionID` | Secure identifier to use for    | UUID     | Optional   |
|                   | this session.  Must only be     |          |            |
|                   | transmitted across secure       |          |            |
|                   | channels                        |          |            |
+-------------------+---------------------------------+----------+------------+
| `SceneID`         | UUID of the scene that the      | UUID     | Optional   |
|                   | avatar is currently in          |          |            |
+-------------------+---------------------------------+----------+------------+
| `ScenePosition`   | Scene-relative current position | Vector3d | Optional   |
|                   | of the avatar                   |          |            |
+-------------------+---------------------------------+----------+------------+
| `SceneLookAt`     | Normalized direction vector     | Vector3d | Optional   |
|                   | where the avatar is currently   |          |            |
|                   | looking                         |          |            | 
+-------------------+---------------------------------+----------+------------+
| `ExtraData`       | Free form JSON data attached to | JSON     | Optional   |
|                   | the session                     |          |            | 
+-------------------+---------------------------------+----------+------------+

Sample request: ::

    RequestMethod=UpdateSession
    &SessionID=b4b37915-2394-42e3-9649-f33df30cb4f4
    &SceneID=f60ea75b-6473-42fa-aa05-e29090e6b0f7
    &ScenePosition=%3C128%2C%20128%2C%2025%3E
    &SceneLookAt=%3C1%2C%200%2C%200%3E


Response Format
---------------

+-------------+--------------------------------------+---------+
| *Parameter* | *Description*                        | *Type*  |
+=============+======================================+=========+
| `Success`   | True if a session was updated, False | Boolean |
|             | if a Message was returned            |         |
+-------------+--------------------------------------+---------+
| `Message`   | Error message                        | String  |
+-------------+--------------------------------------+---------+

Success: ::

    {
        "Success":true
    }


Failure: ::

    {
        "Success":false,
        "Message":"Session does not exist"
    }

