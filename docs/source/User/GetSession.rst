GetSession Method
================

Returns information about a session.


Request Format
--------------

+-----------------+-----------------------------------+--------+-------------+
| *Parameter*     | *Description*                     | *Type* | *Required*  |
+=================+===================================+========+=============+
| `RequestMethod` | GetSession                        | String | Yes         |
+-----------------+-----------------------------------+--------+-------------+
| `SessionID`     | UUID of the session to request    | UUID   | Optional^1^ |
+-----------------+-----------------------------------+--------+-------------+
| `UserID`        | UUID of the user account to check | UUID   | Optional^1^ |
|                 | for a session                     |        |             |
+-----------------+-----------------------------------+--------+-------------+

  * ^1^Either `SessionID` or `UserID` must be specified

Sample request: ::

    RequestMethod=GetSession
    &SessionID=efb00dbb-d4ab-46dc-aebc-4ba83288c3c0


Response Format
---------------

+-------------------+------------------------------------------+----------+
| *Parameter*       | *Description*                            | *Type*   |
+===================+==========================================+==========+
| `Success`         | True if session data was returned, False | Boolean  |
|                   | if a Message was returned                |          |
+-------------------+------------------------------------------+----------+
| `UserID`          | UUID of the user account this session    | UUID     |
|                   | belongs to                               |          |
+-------------------+------------------------------------------+----------+
| `SessionID`       | Current session identifier               | UUID     |
+-------------------+------------------------------------------+----------+
| `SecureSessionID` | Current session identifier that must     | UUID     |
|                   | only be transmitted across secure        |          |
|                   | channels                                 |          |
+-------------------+------------------------------------------+----------+
| `SceneID`         | UUID of the scene that the avatar is     | UUID     |
|                   | currently in                             |          |
+-------------------+------------------------------------------+----------+
| `ScenePosition`   | Scene-relative current position of the   | Vector3d |
|                   | avatar                                   |          |
+-------------------+------------------------------------------+----------+
| `SceneLookAt`     | Normalized direction vector where the    | Vector3d |
|                   | avatar is currently looking              |          |
+-------------------+------------------------------------------+----------+
| `ExtraData`       | Free form JSON data attached to this     | JSON     |
|                   | session                                  |          |
+-------------------+------------------------------------------+----------+
| `Message`         | Error message                            | String   |
+-------------------+------------------------------------------+----------+

Success: ::

    {
        "Success":true,
        "UserID":"736828f0-11f5-11df-8a39-0800200c9a66",
        "SessionID":"23e8bfa6-2123-4499-9670-9fe537e3e1f7",
        "SecureSessionID":"905e167a-0330-4b5c-9a1e-f940e2925303",
        "SceneID":"506e6610-1098-11df-8a39-0800200c9a66",
        "ScenePosition":[128,128,25],
        "SceneLookAt":[1,0,0],
        "ExtraData":{}
    }


Failure: ::

    {
        "Success":false,
        "Message":"Session not found"
    }

