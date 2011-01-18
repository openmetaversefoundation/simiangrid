GetCapability Method
====================

Fetch information about a capability.

Request Format
--------------

+-----------------+---------------------------------+--------+------------+
| *Parameter*     | *Description*                   | *Type* | *Required* |
+=================+=================================+========+============+
| `RequestMethod` | GetCapability                   | String | Yes        |
+-----------------+---------------------------------+--------+------------+
| `CapabilityID`  | UUID of the capability to fetch | UUID   | Yes        |
+-----------------+---------------------------------+--------+------------+

Sample request: ::

    RequestMethod=GetCapability
    &CapabilityID=c006082b-80eb-4d17-90ff-224412c574ea


Response Format
---------------

+----------------+-----------------------------------+-----------+
| *Parameter*    | *Description*                     | *Type*    |
+================+===================================+===========+
| `Success`      | True if the capability was found, | Boolean   |
|                | False if a Message was returned   |           |
+----------------+-----------------------------------+-----------+
| `CapabilityID` | Same UUID that was passed in the  | UUID      |
|                | request                           |           |
+----------------+-----------------------------------+-----------+
| `OwnerID`      | UUID of the owner of this         | UUID      |
|                | capability                        |           |
+----------------+-----------------------------------+-----------+
| `Resource`     | Name of the protected resource    | String    |
|                | this capability maps to           |           |
+----------------+-----------------------------------+-----------+
| `Expiration`   | UTC timestamp when this           | UTC       |
|                | capability will expire            | Timestamp |
+----------------+-----------------------------------+-----------+
| `Message`      | Error message                     | String    |
+----------------+-----------------------------------+-----------+

Success: ::

    {
        "Success":true,
        "CapabilityID":"c006082b-80eb-4d17-90ff-224412c574ea",
        "OwnerID":"efb00dbb-d4ab-46dc-aebc-4ba83288c3c0",
        "Resource":"login",
        "Expiration":1264451975,
    }


Failure: ::

    {
        "Success":false,
        "Message":"Capability not found"
    }

