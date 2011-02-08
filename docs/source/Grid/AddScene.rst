AddScene Method
===============

Create or update grid service information about a scene.


Request Format
--------------

+-----------------+----------------------------------+----------+-------------+
| *Parameter*     | *Description*                    | *Type*   | *Required*  |
+=================+==================================+==========+=============+
| `RequestMethod` | AddScene                         | String   | Yes         |
+-----------------+----------------------------------+----------+-------------+
| `SceneID`       | UUID of the scene to add or      | UUID     | Yes         |
|                 | update                           |          |             |
+-----------------+----------------------------------+----------+-------------+
| `Enabled`       | True if this scene is online and | Boolean  | Yes         |
|                 | can accept service communication |          |             |
+-----------------+----------------------------------+----------+-------------+
| `Name`          | Scene name                       | String   | Optional^1^ |
+-----------------+----------------------------------+----------+-------------+
| `MinPosition`   | Bottom southwest corner of the   | Vector3d | Optional^1^ |
|                 | scene's bounding box             |          |             |
+-----------------+----------------------------------+----------+-------------+
| `MaxPosition`   | Top northeast corner of the      | Vector3d | Optional^1^ |
|                 | scene's bounding box             |          |             |
+-----------------+----------------------------------+----------+-------------+
| `Address`       | URL that is used for service     | Uri      | Optional^1^ |
|                 | communication with the scene     |          |             |
+-----------------+----------------------------------+----------+-------------+
| `ExtraData`     | Free form JSON data associated   | JSON     | Optional    |
|                 | with this scene                  |          |             | 
+-----------------+----------------------------------+----------+-------------+

  * ^1^These parameters are required if Enabled is True

Sample request: ::

    RequestMethod=AddScene
    &SceneID=65d9cd99-8552-44a3-8f3f-73ac76121144
    &Enabled=1
    &Name=Test+Scene
    &MinPosition=%3C128%2C+128%2C+25%3E
    &MaxPosition=%3C512%2C+512%2C+50%3E
    &Address=http://simulator.example.com:12035/scenes/Test%20Scene


Response Format
---------------

+-------------+---------------------------------------------+---------+
| *Parameter* | *Description*                               | *Type*  |
+=============+=============================================+=========+
| `Success`   | True if the scene was successfully added or | Boolean |
|             | updated, False if a Message was returned    |         |
+-------------+---------------------------------------------+---------+
| `Message`   | Error message                               | String  |
+-------------+---------------------------------------------+---------+

Success: ::

    {
        "Success":true
    }


Failure: ::

    {
        "Success":false,
        "Message":"Bounding box collides with existing scene"
    }

