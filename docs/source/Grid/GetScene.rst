GetScene Method
===============

Returns information about a scene.

Request Format
--------------

+-----------------+--------------------------------------+----------+-------------+
| *Parameter*     | *Description*                        | *Type*   | *Required*  |
+=================+======================================+==========+=============+
| `RequestMethod` | GetScene                             | String   | Yes         |
+-----------------+--------------------------------------+----------+-------------+
| `SceneID`       | UUID of the scene to retrieve        | UUID     | Optional^1^ |
|                 | information for                      |          |             | 
+-----------------+--------------------------------------+----------+-------------+
| `Position`      | Position vector to start searching   | Vector3d | Optional^1^ |
|                 | for a nearby scene                   |          |             |
+-----------------+--------------------------------------+----------+-------------+
| `FindClosest`   | If True and `Position` is specified, | Boolean  | Optional,   |
|                 | will search for the closest scene    |          | defaults    |
|                 | instead of a scene overlapping the   |          | to False    |
|                 | given position                       |          |             |
+-----------------+--------------------------------------+----------+-------------+
| `Enabled`       | If True, only Enabled scenes will be | Boolean  | Optional,   |
|                 | searched                             |          | defaults    |
|                 |                                      |          | to false    |
+-----------------+--------------------------------------+----------+-------------+

  * ^1^Either `SceneID` or `Position` must be specified

Sample request: ::

    RequestMethod=GetScene
    &SceneID=efb00dbb-d4ab-46dc-aebc-4ba83288c3c0


Response Format
---------------

+---------------+-----------------------------------------+----------+
| *Parameter*   | *Description*                           | *Type*   |
+===============+=========================================+==========+
| `Success`     | True if scene information was returned, | Boolean  |
|               | False if a Message was returned         |          |
+---------------+-----------------------------------------+----------+
| `SceneID`     | UUID of the scene                       | UUID     |
+---------------+-----------------------------------------+----------+
| `Name`        | Scene name                              | String   |
+---------------+-----------------------------------------+----------+
| `Enabled`     | True if the scene is online and able to | Boolean  |
|               | receive communication                   |          |
+---------------+-----------------------------------------+----------+
| `MinPosition` | Bottom southwest corner of the scene's  | Vector3d |
|               | bounding box                            |          |
+---------------+-----------------------------------------+----------+
| `MaxPosition` | Top northeast corner of the scene's     | Vector3d |
|               | bounding box                            |          | 
+---------------+-----------------------------------------+----------+
| `Address`     | URL that is used for service            | Uri      |
|               | communication with the scene            |          | 
+---------------+-----------------------------------------+----------+
| `ExtraData`   | Free form JSON data associated with the | JSON     |
|               | scene                                   |          | 
+---------------+-----------------------------------------+----------+
| `Message`     | Error message                           | String   |
+---------------+-----------------------------------------+----------+

Success: ::

    {
        "Success":true,
        "SceneID":"efb00dbb-d4ab-46dc-aebc-4ba83288c3c0",
        "Name":"Test Scene",
        "Enabled":true,
        "MinPosition":[256, 256, 25],
        "MaxPosition":[512, 512, 50],
        "Address":"http://simulator.example.com:12035/scenes/Test%20Scene",
        "ExtraData":{ "UserKey":"Sample Value" }
    }


Failure: ::

    {
        "Success":false,
        "Message":"No matching scene found"
    }

