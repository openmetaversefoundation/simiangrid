AddCapability Method
====================

Create or update a capability resource.

Request Format
--------------

+-----------------+------------------------------------+-----------+------------+
| *Parameter*     | *Description*                      | *Type*    | *Required* |
+=================+====================================+===========+============+
| `RequestMethod` | AddCapability                      | String    | Yes        |
+-----------------+------------------------------------+-----------+------------+
| `CapabilityID`  | UUID of the capability to create.  | UUID      | Optional   |
|                 | Can be used to update a capability |           |            |
+-----------------+------------------------------------+-----------+------------+
| `OwnerID`       | UUID of the owner of this          | UUID      | Yes        |
|                 | capability                         |           |            |
+-----------------+------------------------------------+-----------+------------+
| `Resource`      | Name of the protected resource     | String    | Yes        |
|                 | this capability maps to            |           |            |
+-----------------+------------------------------------+-----------+------------+
| `Expiration`    | UTC timestamp when this capability | UTC       | Yes        |
|                 | will expire                        | Timestamp |            |
+-----------------+------------------------------------+-----------+------------+

Sample request: ::

    RequestMethod=AddCapability
    &OwnerID=efb00dbb-d4ab-46dc-aebc-4ba83288c3c0
    &Resource=login
    &Expiration=1264451975


Response Format
---------------

+----------------+--------------------------------------+---------+
| *Parameter*    | *Description*                        | *Type*  | 
+================+======================================+=========+
| `Success`      | True if a CapabilityID was returned, | Boolean |
|                | False if a Message was returned      |         |
+----------------+--------------------------------------+---------+
| `CapabilityID` | UUID of the created or updated       | UUID    |
|                | capability                           |         |
+----------------+--------------------------------------+---------+
| `Message`      | Error message                        | String  |
+----------------+--------------------------------------+---------+

Success: ::

    {
        "Success":true,
        "CapabilityID":"c006082b-80eb-4d17-90ff-224412c574ea"
    }


Failure: ::

    {
        "Success":false,
        "Message":"Expiration date is in the past"
    }

