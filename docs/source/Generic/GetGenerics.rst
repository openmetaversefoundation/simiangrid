GetGenerics Method
==================

Searches for key/value pairs in the generic store.

Request Format
--------------

+-----------------+---------------------------+--------+-------------+
| *Parameter*     | *Description*             | *Type* | *Required*  |
+=================+===========================+========+=============+
| `RequestMethod` | GetGenerics               | String | Yes         |
+-----------------+---------------------------+--------+-------------+
| `OwnerID`       | Owner UUID of the entries | String | Optional^1^ |
|                 | to search                 |        |             |
+-----------------+---------------------------+--------+-------------+
| `Type`          | Type of entries to search | String | Yes         |
|                 | for                       |        |             |
+-----------------+---------------------------+--------+-------------+
| `Key`           | Key to search for         | String | Optional^1^ |
+-----------------+---------------------------+--------+-------------+

  * ^1^Either `OwnerID` or `Key` must be given

Sample request: ::

    RequestMethod=GetGenerics
    &OwnerID=0dff5920-282a-11df-8a39-0800200c9a66
    &Type=Friend


Response Format
---------------

+-------------+-----------------------------------+-----------+
| *Parameter* | *Description*                     | *Type*    |
+=============+===================================+===========+
| `Success`   | True if zero or more entries were | Boolean   |
|             | returned, False if a Message was  |           |
|             | returned                          |           |
+-------------+-----------------------------------+-----------+
| `Entries`   | Array of generic entries that     | Array,    |
|             | matched the query                 | see below |
+-------------+-----------------------------------+-----------+
| `Message`   | Error message                     | String    |
+-------------+-----------------------------------+-----------+


Entries Format
--------------

+-------------+-------------------------+--------+
| *Parameter* | *Description*           | *Type* |
+=============+=========================+========+
| `OwnerID`   | Owner UUID of the entry | UUID   |
+-------------+-------------------------+--------+
| `Key`       | Key name                | String |
+-------------+-------------------------+--------+
| `Value`     | Free-form value string  | String |
+-------------+-------------------------+--------+

Success: ::

    {
        "Success":true,
        "Entries":
        [
            {
                "OwnerID":"0dff5920-282a-11df-8a39-0800200c9a66",
                "Key":"9d00ff70-282a-11df-8a39-0800200c9a66",
                "Value":"38"
            },
            {
                "OwnerID":"0dff5920-282a-11df-8a39-0800200c9a66",
                "Key":"c4bca8c0-282a-11df-8a39-0800200c9a66",
                "Value":"0"
            },
        ]
    }


Failure: ::

    {
        "Success":false,
        "Message":"An unknown error occurred"
    }

