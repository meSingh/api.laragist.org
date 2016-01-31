API url:
========================================
http://api.fitgalaxyy.com//v1/users/{user-id}/step/2


URL Parameters:
========================================
user-id: Users ID


Request Headers:
========================================
POST /v1/users/1/step/2 HTTP/1.1
Content-Type: application/json
Host: api.fitgalaxyy.com
Connection: close
User-Agent: Paw/2.2.9 (Macintosh; OS X/10.11.2) GCDHTTPRequest
Content-Length: 221


Request Data:
========================================
{
    "gender":       "male",
    "birthdate":    "1990/06/20",
    "weight":       80.5,
    "weight_type":  "KG",
    "height":       5.09,
    "height_type":  "INCHES",
    "weight_goal":  70,
    "weight_goal_type": "KG",
    "height_goal":      6.2,
    "height_goal_type": "INCHES",
    "activity_level_id":    1
}


Success Response:
========================================
HTTP/1.1 204 No Content
Server: nginx/1.9.9
Content-Type: text/html; charset=UTF-8
Connection: close
Cache-Control: private, must-revalidate
ETag: "d41d8cd98f00b204e9800998ecf8427e"
Date: Tue, 26 Jan 2016 13:42:55 GMT


Error Response:
========================================
HTTP/1.1 422 Unprocessable Entity
Server: nginx/1.9.9
Content-Type: application/json
Transfer-Encoding: chunked
Connection: close
Cache-Control: no-cache
Date: Tue, 26 Jan 2016 14:03:41 GMT


Error Response Data:
========================================
{
    "message":  "Could not update user info.",
    "errors":   {
        "height_goal":  [   "The height goal field is required."    ]
    },
    "status_code":  422
}
