AddMapTile Method
=================

Create or replace a map tile.

Request Format
--------------

+-------------+---------------------------------------+---------+------------+
| *Parameter* | *Description*                         | *Type*  | *Required* |
+=============+=======================================+=========+============+
| `X`         | Offset of this scene from the western | Integer | Yes        |
|             | (left) edge of the world, in          |         |            |
|             | increments of 256 meters              |         |            | 
+-------------+---------------------------------------+---------+------------+
| `Y`         | Offset of this scene from the         | Integer | Yes        |
|             | southern (bottom) edge of the world,  |         |            |
|             | in increments of 256 meters           |         |            | 
+-------------+---------------------------------------+---------+------------+
| `Tile`      | Tile texture data, in PNG format      | Binary  | Yes        |
+-------------+---------------------------------------+---------+------------+

Sample request: ::

    POST /map/ HTTP/1.1
    Content-Length: 34927
    Content-Type: multipart/form-data; boundary=--AaB03x
    
    --AaB03x
    Content-Disposition: form-data; name="X"
    
    1000
    --AaB03x
    Content-Disposition: form-data; name="Y"
    
    1001
    --AaB03x
    Content-Disposition: form-data; name="Tile"; filename="tile.png"
    Content-Type: image/png
    
    (raw tile.png data)
    --AaB03x


  * Only PNG format is supported for uploaded map tiles
  * If a map tile with the same X and Y position already exists, it will be overwritten
  * Only one map tile upload is allowed per request

Response Format
---------------

+-------------+----------------------------------+---------+
| *Parameter* | *Description*                    | *Type*  |
+=============+==================================+=========+
| `Success`   | True if an AssetID was returned, | Boolean |
|             | False if a Message was returned  |         |
+-------------+----------------------------------+---------+
| `AssetID`   | UUID of the created or updated   | UUID    |
|             | asset                            |         |
+-------------+----------------------------------+---------+
| `Status`    | One of two values: "Created" or  | String  |
|             | "Updated"                        |         |
+-------------+----------------------------------+---------+
| `Message`   | Error message                    | String  |
+-------------+----------------------------------+---------+

Success: ::

    {
        "Success":true
    }


Failure: ::

    {
        "Success":false,
        "Message":"Invalid image format"
    }

