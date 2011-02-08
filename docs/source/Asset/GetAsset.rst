GetAsset Method
===============

Retrieve an asset from the asset service.


Request Format
--------------

Sample request: ::

    GET /assets/e1d3e830-36cd-4232-aa8c-01325320e533 HTTP/1.1


Response Format
---------------

+----------------------+--------------------------+--------------------------+
| *Header*             | *Description*            | *Type*                   |
+======================+==========================+==========================+
| `ETag`               | The SHA-256 hash of the  | 64-character hexadecimal |
|                      | asset                    | string                   |
+----------------------+--------------------------+--------------------------+
| `Last-Modified`      | RFC870 timestamp         | RFC870 Timestamp         |
+----------------------+--------------------------+--------------------------+
| `X-Asset-Creator-Id` | UUID of the asset's      | UUID                     |
|                      | creator                  |                          |
+----------------------+--------------------------+--------------------------+
| `Content-Type`       | The content type of the  | ContentType              |
|                      | asset                    |                          |
+----------------------+--------------------------+--------------------------+

Sample response: ::

    HTTP/1.1 200 OK
    Date: Wed, 09 Dec 2009 07:48:17 GMT
    Server: Apache/2.2.10 (Linux/SUSE)
    ETag: 50d858e0985ecc7f60418aaf0cc5ab587f42c2570a884095a9e8ccacd0f6545c
    Last-Modified: Wednesday, 09-Dec-09 07:48:17 GMT
    X-Asset-Creator-Id: b9f9048e-2af0-4dba-ae24-9e23d5511ace
    Transfer-Encoding: chunked
    Content-Type: image/tga

    (raw asset data)


Supported Response Codes
^^^^^^^^^^^^^^^^^^^^^^^^

 * 200: Asset data follows
 * 403: Asset data is not publicly accessible
 * 404: Asset was not found
 * 500: Internal server error occurred
