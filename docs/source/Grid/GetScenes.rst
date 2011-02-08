GetScenes Method
================

Returns information about scenes matching specified criteria.

Request Format
--------------

+-----------------+-------------------------------------+----------+-------------+
| *Parameter*     | *Description*                       | *Type*   | *Required*  | 
+=================+=====================================+==========+=============+
| `RequestMethod` | GetScenes                           | String   | Yes         | 
+-----------------+-------------------------------------+----------+-------------+
| `NameQuery`     | String to match against scene names | String   | Optional^1^ | 
+-----------------+-------------------------------------+----------+-------------+
| `MinPosition`   | Bottom southwest corner of the      | Vector3d | Optional^1^ |
|                 | bounding box to search for          |          |             | 
|                 | scenes inside of                    |          |             |
+-----------------+-------------------------------------+----------+-------------+
| `MaxPosition`   | Top northeast corner of the         | Vector3d | Optional^1^ |
|                 | bounding box to search for scenes   |          |             |
|                 | inside of                           |          |             |
+-----------------+-------------------------------------+----------+-------------+
| `Enabled`       | If True, only Enabled scenes will   | Boolean  | Optional,   |
|                 | be searched                         |          | defaults to | 
|                 |                                     |          | False       | 
+-----------------+-------------------------------------+----------+-------------+
| `MaxNumber`     | Maximum number of results to return | Integer  | Optional    | 
+-----------------+-------------------------------------+----------+-------------+

  * ^1^Either `NameQuery` or both `MinPosition` and `MaxPosition` must be specified

Sample request: ::

    RequestMethod=GetScenes
    &NameQuery=Test


Response Format
---------------

+-------------+-----------------------------------------+-----------+
| *Parameter* | *Description*                           | *Type*    |
+-------------+-----------------------------------------+-----------+
| `Success`   | True if scene information was returned, | Boolean   |
|             | False if a Message was returned         |           |
+-------------+-----------------------------------------+-----------+
| `Scenes`    | Array of scenes that matched the query  | Array,    |
|             |                                         | see below |
+-------------+-----------------------------------------+-----------+
| `Message`   | Error message                           | String    |
+-------------+-----------------------------------------+-----------+


Scene Information Format
------------------------

+---------------+---------------------------------+----------+
| *Parameter*   | *Description*                   | *Type*   |
+===============+=================================+==========+
| `SceneID`     | UUID of the scene               | UUID     |
+---------------+---------------------------------+----------+
| `Name`        | Scene name                      | String   |
+---------------+---------------------------------+----------+
| `Enabled`     | True if the scene is online and | Boolean  |
|               | able to receive communication   |          |
+---------------+---------------------------------+----------+
| `MinPosition` | Bottom southwest corner of the  | Vector3d |
|               | scene's bounding box            |          |
+---------------+---------------------------------+----------+
| `MaxPosition` | Top northeast corner of the     | Vector3d |
|               | scene's bounding box            |          | 
+---------------+---------------------------------+----------+
| `Address`     | URL that is used for service    | Uri      |
|               | communication with the scene    |          | 
+---------------+---------------------------------+----------+
| `ExtraData`   | Free form JSON data associated  | JSON     |
|               | with the scene                  |          | 
+---------------+---------------------------------+----------+

Success: ::

    {
        "Success":true,
        "Scenes":
        [
            {
                "SceneID":"efb00dbb-d4ab-46dc-aebc-4ba83288c3c0",
                "Name":"Test Scene",
                "Enabled":true,
                "MinPosition":[256, 256, 25],
                "MaxPosition":[512, 512, 50],
                "Address":"http://simulator.example.com:12035/scenes/Test%20Scene",
                "ExtraData":{}
            }
        ]
    }


Failure: ::

    {
        "Success":false,
        "Message":"Invalid bounding box"
    }

