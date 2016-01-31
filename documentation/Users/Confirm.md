API url:
========================================
http://api.fitgalaxyy.com/v1/users/{user-id}/confirm/{confirmation-code}


Request Headers:
========================================

GET /v1/users/1/confirm/$2y$10$LEhZnRULC6WL0ItVUlfVU.3erVnGWfi4fpMggMb9jJzwWaZ.pJAca HTTP/1.1
Host: api.fitgalaxyy.com
Connection: close
User-Agent: Paw/2.2.9 (Macintosh; OS X/10.11.2) GCDHTTPRequest


Request Data:
========================================
THIS IS A GET REQUEST. REPLACE THIS DATA IN URL.
{
    "user-id": 1,
    "confirmation-code": $2y$10$LEhZnRULC6WL0ItVUlfVU.3erVnGWfi4fpMggMb9jJzwWaZ.pJAca
}


Success Response:
========================================

HTTP/1.1 204 No Content
Server: nginx/1.9.9
Content-Type: text/html; charset=UTF-8
Connection: close
Cache-Control: private, must-revalidate
ETag: "d41d8cd98f00b204e9800998ecf8427e"
Date: Tue, 26 Jan 2016 10:47:44 GMT


Error Response:
========================================

HTTP/1.1 400 Bad Request
Server: nginx/1.9.9
Content-Type: application/json
Transfer-Encoding: chunked
Connection: close
Cache-Control: no-cache
Date: Tue, 26 Jan 2016 12:45:00 GMT


Error Response Data:
========================================

{
    "message":  "Wrong confirmation code!! ",
    "status_code":  400
}
