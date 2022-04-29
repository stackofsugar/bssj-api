<h1 align="center" name="top">bssj-api</h1>
<div align="center">
<a href="https://choosealicense.com/licenses/mit/" target="_blank">
<img src="https://img.shields.io/badge/License-MIT-green"></img></a>
<a href="#!" target="_blank">
<img src="https://img.shields.io/badge/project-planning-yellow"></img></a>
<a href="#!" target="_blank">
<img src="https://img.shields.io/badge/build-%3F-red"></img></a>
<a href="#!" target="_blank">
<img src="https://img.shields.io/badge/deployment-%3F-red"></img></a>
</div>

### Table of Contents

-   [Introduction](#introduction)
-   [Authentication](#authentication)
-   [Errors](#errors)
    -   [List of Status Codes](#sttlist)
    -   [List of Error Codes](#errlist)
-   [Core Resources](#core)
    -   [Users](#users)

# Introduction <a name="introduction"></a>

**BSSJ API** is a [REST](en.wikipedia.org/wiki/Representational_State_Transfer) API intended to be used for our [Bank Sampah](#!) mobile application, written in [Flutter](https://flutter.dev/). Our API will have method-oriented endpoints, returning JSON-encoded responses. **BSSJ API** will use stardard HTTP verbs, terminologies, and response codes.

<div align="center">
<table>
<thead>
<tr><th>Base URL</th></tr>
</thead>
<tbody>
<tr><th><code>api.?banksampah?.???</code></th></tr>
</tbody>
</table>
</div>

# Authentication <a name="authentication"></a>

**BSSJ API** will use API keys to authenticate connections to the server. Individual mobile instances can acquire said API by internal handshake operations. As the API keys carries many privilleges, it's important to keep it secure in the internal mobile app system.

For production, our **BSSJ API** will force users to use HTTPS. All calls from HTTP will fail and return a failure message. This is also the case for wrong/nonexistent API Key.

# Errors <a name="errors"></a>

As we have introduced earlier, **BSSJ API** will use conventional HTTP response codes for familiarity, indicating the status of an API request. In general, as the HTTP response convention states, codes of `2xx` range indicates success, `4xx` indicates failure, and although rare, `5xx` range indicates server failure.

All of the errors in the `4xx` range will contain an error code. A non-zero error code means that this error can usually be handled by the program, or recognized and returned to the user. The error code will be accompanied by an error message for human-readability.

## List of Status Codes <a name="sttlist"></a>

| HTTP Status Code | Status Name       | Status Description                                                           |
| ---------------- | ----------------- | ---------------------------------------------------------------------------- |
| `200`            | OK                | Everything works as intended                                                 |
| `400`            | Bad Request       | The server can't accept the request, usually due to an incomplete parameters |
| `401`            | Unauthorized      | The API key provided is invalid                                              |
| `402`            | Request Failed    | Parameters were valid, but the request fail                                  |
| `403`            | Forbidden         | The API key provided is not of enough privillege to execute actions          |
| `404`            | Not Found         | The requested URI doesn't exist                                              |
| `429`            | Too Many Requests | Too many request are made, client is rate limited                            |
| `5xx`            | Server Errors     | Something's wrong on the server's end                                        |

## List of Error Codes <a name="errlist"></a>

<hr />
<h3 align="center">[ Under Construction ]</h3>
<hr />

# Core Resources <a name="core"></a>

## Users <a name="users"></a>

Users represents user accounts registered to **BSSJ** mobile app. Guest of the **BSSJ** mobile app can register for an account, account holders can edit and delete their account, while admins can create, read, activate, update, and delete all registered accounts in the system.

### Endpoints Type

-   [Retrieve all `user`s](#allusers)
-   [Retrieve a specific `user`](#oneuser)
-   [Authenticate `user` login](#authlogin)
-   [Register a `user`](#usrreg)
-   [Update `user`'s account profile](#userupdt)
-   [Delete `user`'s account](#usrdel)

### Endpoints

| Method   | URI                    | Note         |
| -------- | ---------------------- | ------------ |
| `GET`    | `/v1/users/all`        | `admin`-only |
| `GET`    | `/v1/users/by/:id`     |              |
| `GET`    | `/v1/users/auth`       |              |
| `POST`   | `/v1/users/register`   |              |
| `PATCH`  | `/v1/users/update/:id` |              |
| `DELETE` | `/v1/users/delete/:id` |              |

### The `user` Object

```json
{
    "id": 1,
    "username": "John Doe",
    "address": "Upper Manhattan, New York",
    "phone": "085151515151",
    "email": "john.doe@mymail.com",
    "password": "892f6100b1bd47...",
    "is_admin": false,
    "is_active": true
}
```

#### Attributes

| Name        | Data Type | Description                                         |
| ----------- | --------- | --------------------------------------------------- |
| `id`        | `integer` | **Unique** identifier for the object                |
| `username`  | `string`  | **Unique** username for each user                   |
| `fullname`  | `string`  | Full name of each user                              |
| `address`   | `string`  | Arbitrary address of each user                      |
| `phone`     | `string`  | Phone number of each user                           |
| `email`     | `string`  | Email addresses of each user                        |
| `password`  | `string`  | SHA256/MD5-encrypted password of each user          |
| `is_admin`  | `boolean` | A boolean value indicating each user's privilege    |
| `is_active` | `boolean` | A boolean value indicating user's activation status |

### Retrieve all `user`s <a name="allusers"></a>

Used by `admin` to retrieve all registered `user`.

| Method | URI             | Note         |
| ------ | --------------- | ------------ |
| `GET`  | `/v1/users/all` | `admin`-only |

#### Request Parameters

| Key     | Value Type | Required | Description                                      |
| ------- | ---------- | -------- | ------------------------------------------------ |
| `key`   | `string`   | `true`   | Your API key                                     |
| `page`  | `int`      | `false`  | The number of records retrieved in a single page |
| `limit` | `int`      | `false`  | Limit of the retrieved data                      |

This section of the API allows the use of pagination. User can opt-in to the pagination by assigning value to the `page` key, which will serve as the number of records one page can show. This is not required. If you don't want to use pagination, meaning all of the data will be shown in one response, you can opt-out from assigning value to this key.

#### Example JSON Request

```json
{
    "key": "383thuy4b34iu3y",
    "page": 2
}
```

#### Response

```json
{
    "status": {
        "code": 200
    },
    "page": {
        "paginated": true,
        "page_num": 1,
        "out_of": 5
    },
    "response": {
        "records": "10",
        "users": [
            {
                "id": 1,
                "username": "jdoe",
                "fullname": "John Doe",
                "address": "Upper Manhattan, New York",
                "phone": "085151515151",
                "email": "john.doe@mymail.com",
                "password": "892f6100b1bd47...",
                "is_admin": false,
                "is_active": true
            },
            {
                "id": 2,
                "username": "dengklexz",
                "fullname": "Pak Dengklek",
                "address": "Sleman, Yogyakarta",
                "phone": "085151515151",
                "email": "dengklek@mymail.com",
                "password": "c3f0a43bb3823...",
                "is_admin": true,
                "is_active": true
            }
        ]
    }
}
```

> If there is no account in the system, the status code will still be `200`, and `users` will be an empty array.

### Retrieve a specific `user` <a name="oneuser"></a>

Used to retrieve a specific `user` by it's `id`

| Method | URI                |
| ------ | ------------------ |
| `GET`  | `/v1/users/by/:id` |

#### Request Parameters

| Key   | Value Type | Required | Description            |
| ----- | ---------- | -------- | ---------------------- |
| `key` | `string`   | `true`   | Your API key           |
| `id`  | `int`      | `true`   | The `id` of the `user` |

#### Example JSON Request

```json
{
    "key": "383thuy4b34iu3y",
    "id": 2
}
```

#### Response

```json
{
    "status": {
        "code": 200
    },
    "response": {
        "id": 2,
        "username": "dengklexz",
        "fullname": "Pak Dengklek",
        "address": "Sleman, Yogyakarta",
        "phone": "085151515151",
        "email": "dengklek@mymail.com",
        "password": "c3f0a43bb3823...",
        "is_admin": true,
        "is_active": true
    }
}
```

#### Response if `user` does not exist

```json
{
    "status": {
        "code": 402,
        "message": "User not found"
    },
    "response": {}
}
```

### Authenticate `user` login <a name="authlogin"></a>

Used to authenticate logins in the mobile app.

| Method | URI              |
| ------ | ---------------- |
| `GET`  | `/v1/users/auth` |

#### Request Parameters

| Key        | Value Type | Required | Description                       |
| ---------- | ---------- | -------- | --------------------------------- |
| `key`      | `string`   | `true`   | Your API key                      |
| `username` | `string`   | `true`   | The `username` of the `user`      |
| `password` | `string`   | `true`   | The hashed password of the `user` |

#### Example JSON Request

```json
{
    "key": "383thuy4b34iu3y",
    "username": "dengklexz",
    "password": "c3f0a43bb3823..."
}
```

#### Response, if credentials match

```json
{
    "status": {
        "code": 200
    },
    "response": {
        "id": 2,
        "username": "dengklexz",
        "fullname": "Pak Dengklek",
        "address": "Sleman, Yogyakarta",
        "phone": "085151515151",
        "email": "dengklek@mymail.com",
        "password": "c3f0a43bb3823...",
        "is_admin": true,
        "is_active": true
    }
}
```

#### Response, if credentials don't match

```json
{
    "status": {
        "code": 402,
        "message": "Login doesn't match record"
    },
    "response": {}
}
```

### Register a `user` <a name="usrreg"></a>

Used to register new accounts.

| Method | URI                  |
| ------ | -------------------- |
| `POST` | `/v1/users/register` |

#### Request Parameters

| Key        | Value Type | Required | Description                           |
| ---------- | ---------- | -------- | ------------------------------------- |
| `key`      | `string`   | `true`   | Your API key                          |
| `username` | `string`   | `true`   | Self-explanatory                      |
| `fullname` | `string`   | `true`   | Self-explanatory                      |
| `address`  | `string`   | `true`   | Self-explanatory                      |
| `phone`    | `string`   | `true`   | Self-explanatory                      |
| `email`    | `string`   | `true`   | Self-explanatory                      |
| `password` | `string`   | `true`   | The **hashed** password of the `user` |

#### Example JSON Request

```json
{
    "key": "383thuy4b34iu3y",
    "username": "tommy1234",
    "fullname": "Tom Anderson",
    "address": "Staten Island, New Jersey",
    "phone": "678455321147",
    "email": "tommy@staten.gov.uk",
    "password": "7f9a63cb34..."
}
```

#### Response, if no error occurs

```json
{
    "status": {
        "code": 200
    },
    "response": {
        "success": true,
        "username": "tommy1234"
    }
}
```

#### Possible error scenarios

```json
{
    "status": {
        "code": 402,
        "errcode":  ,
        "message":  ,
    }
}
```

| Message                    | Error Code |
| -------------------------- | ---------- |
| Duplicate `email` found    | `0101`     |
| Duplicate `username` found | `0102`     |

### Update `user`'s account profile <a name="userupdt"></a>

Used to update accounts.

| Method  | URI                    |
| ------- | ---------------------- |
| `PATCH` | `/v1/users/update/:id` |

#### Request Parameters

| Key         | Value Type | Required | Description                           |
| ----------- | ---------- | -------- | ------------------------------------- |
| `key`       | `string`   | `true`   | Your API key                          |
| `id`        | `int`      | `true`   | The `id` of user to be updated        |
| `username`  | `string`   | `false`  | Self-explanatory                      |
| `fullname`  | `string`   | `false`  | Self-explanatory                      |
| `address`   | `string`   | `false`  | Self-explanatory                      |
| `phone`     | `string`   | `false`  | Self-explanatory                      |
| `email`     | `string`   | `false`  | Self-explanatory                      |
| `password`  | `string`   | `false`  | The **hashed** password of the `user` |
| `is_admin`  | `string`   | `false`  | Self-explanatory                      |
| `is_active` | `string`   | `false`  | Self-explanatory                      |

#### Example JSON Request

```json
{
    "key": "383thuy4b34iu3y",
    "id": 3,
    "username": "tommy1234",
    "fullname": "Tom Anderson",
    "address": "Staten Island, New Jersey",
    "phone": "678455321147",
    "email": "tommy@staten.gov.uk",
    "password": "7f9a63cb34...",
    "is_active": true
}
```

#### Response, if no error occurs

```json
{
    "status": {
        "code": 200
    },
    "response": {
        "success": true
    }
}
```

#### Possible error scenarios

```json
{
    "status": {
        "code": 402,
        "errcode":  ,
        "message":  ,
    },
    "response": {}
}
```

| Message                    | Error Code |
| -------------------------- | ---------- |
| Duplicate `email` found    | `0201`     |
| Duplicate `username` found | `0202`     |

### Delete `user`'s account <a name="usrdel"></a>

Used to delete existing accounts.

| Method   | URI                    |
| -------- | ---------------------- |
| `DELETE` | `/v1/users/delete/:id` |

#### Request Parameters

| Key   | Value Type | Required | Description      |
| ----- | ---------- | -------- | ---------------- |
| `key` | `string`   | `true`   | Your API key     |
| `id`  | `int`      | `true`   | Self-explanatory |

#### Example JSON Request

```json
{
    "key": "383thuy4b34iu3y",
    "id": 3
}
```

#### Response, if no error occurs

```json
{
    "status": {
        "code": 200
    },
    "response": {
        "success": true
    }
}
```

#### Possible error scenarios

```json
{
    "status": {
        "code": 402,
        "errcode":  ,
        "message":  ,
    }
}
```

| Message                             | Error Code |
| ----------------------------------- | ---------- |
| The requested `user` can't be found | `0301`     |
| The requested `user` is an `admin`  | `0302`     |

<hr />
<a href="#top" align="center"><h3>Back to top</h3></a>
