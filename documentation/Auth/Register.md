# User Registration


## API url:
   
    http://api.fitgalaxyy.com/v1/auth/register


## Request Headers:

    POST /v1/auth/register HTTP/1.1
    Content-Type: application/json
    Host: api.fitgalaxyy.com
    Connection: close
    User-Agent: Paw/2.2.9 (Macintosh; OS X/10.11.2) GCDHTTPRequest
    Content-Length: 123


## Request Data:

|Variable Name| Type | Sample Data | Format |
|-----|----|-----|----|
|first_name|string|Mandeep||
|last_name|string|Singh||
|email|string|im@msingh.me||
|password|string|1q2w3e4r5t||
|location|string|Delhi||

    {
        "first_name":   "Mandeep",
        "last_name":    "Singh",
        "email":        "im@msingh.me",
        "password":     "1q2w3e4r5t",
        "location":     "Delhi"
    }


Success Response:
========================================

    HTTP/1.1 201 Created
    Server: nginx/1.9.9
    Content-Type: text/html; charset=UTF-8
    Transfer-Encoding: chunked
    Connection: close
    Cache-Control: private, must-revalidate
    ETag: "d41d8cd98f00b204e9800998ecf8427e"
    Date: Tue, 26 Jan 2016 10:47:17 GMT


Error Response:
========================================

    HTTP/1.1 422 Unprocessable Entity
    Server: nginx/1.9.9
    Content-Type: application/json
    Transfer-Encoding: chunked
    Connection: close
    Cache-Control: no-cache
    Date: Tue, 26 Jan 2016 12:08:21 GMT


Error Response Data:
========================================

    {
        "message":"Could not create new user.",
        "errors":   {
            "email":    [ "The email has already been taken." ],
            "location": [ "The location field is required." ]
        },
        "status_code":422
    }

