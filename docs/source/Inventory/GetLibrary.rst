GetLibrary Method
=================

Retrieve the root folder UUID and owner UUID for the shared inventory library.


Request Format
--------------

+-----------------+---------------+---------+------------+
| *Parameter*     | *Description* | *Type*  | *Required* |
+=================+===============+=========+============+
| `RequestMethod` | GetLibrary    | String  | Yes        |
+-----------------+---------------+---------+------------+

Sample request: ::

    RequestMethod=GetLibrary


Response Format
---------------

+-------------+-------------------------------------------------+---------+
| *Parameter* | *Description*                                   | *Type*  |
+=============+=================================================+=========+
| `Success`   | True if a FolderID and UserID were returned,    | Boolean |
|             | False if a Message was returned                 |         |
+-------------+-------------------------------------------------+---------+
| `FolderID`  | UUID of the library root folder                 | UUID    |
+-------------+-------------------------------------------------+---------+
| `OwnerID`   | UUID of the library owner                       | UUID    |
+-------------+-------------------------------------------------+---------+
| `Message`   | Error message                                   | String  |
+-------------+-------------------------------------------------+---------+


Success: ::

    {
        "Success":true,
        "FolderID":"99ffc001-40b6-47ee-92c1-d5be0bd71ea6",
        "OwnerID":"d52bbd77-8df6-4c4c-855f-d3fd1dda3600"
    }


Failure: ::

    {
        "Success":false,
        "Message":"Shared library does not exist"
    }

