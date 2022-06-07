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
<tr><th><code>api.???.???</code></th></tr>
</tbody>
</table>
</div>

# Authentication <a name="authentication"></a>

**BSSJ API** will use **access tokens** to authenticate user requests. The access token can be used by attaching a client's request header `Authorization: Bearer <your access token here>` with `Authorization` as the header key and `Bearer <your token here>` as the value. The access token can be obtained when the user registered for an account or logged in to their account. As such, **BSSJ API** will force its users to use HTTPS.

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

# Core Resources <a name="core"></a>

## Users <a name="users"></a>

Users represents user accounts registered to **BSSJ** mobile app. Guest of the **BSSJ** mobile app can register for an account, account holders can edit and delete their account, while admins can create, read, update, and delete all registered accounts in the system.

### Authentication Walls

**BSSJ API** will have 2 different backend authentication wall whose terminology will be used for the rest of this documentation. The first wall, `auth:user`, will be owned by any registered clients on the site, while the second wall, `auth:admin`, will be owned by registered admins.

### Endpoints Type

-   [Authenticate account login](#end-auth)
-   [Register an account](#end-register)
-   [Logout / destroy access token](#end-logout)
-   [Get profile](#end-getprofile)
-   [Edit Profile](#end-editprofile)
-   [`admin-only` Retrieve all user profiles](#end-getuserprofile)
-   [`admin-only` Retrieve a user profile by ID / username](#end-getuserprofileby)
-   [`admin-only` Edit a user profile by ID / username](#end-edituserprofileby)
-   Helpers:
    -   Login and admin check
    -   API status

### Endpoints

| Method | URI                         | Note                               |
| ------ | --------------------------- | ---------------------------------- |
| `POST` | `/login`                    | `guest-only` Authenticate          |
| `POST` | `/register`                 | `guest-only` Register              |
| `GET`  | `/logout`                   | `user-only` Logout / Destroy       |
| `GET`  | `/profile`                  | `user-only` Get profile            |
| `POST` | `/profile/update`           | `user-only` Update profile         |
| `GET`  | `/admin/profile/get/all`    | `admin-only` Get all user profiles |
| `GET`  | `/admin/profile/get/:id`    | `admin-only` Get a user profile    |
| `POST` | `/admin/profile/update/:id` | `admin-only` Edit a user profile   |
| `GET`  | `/`                         | API status                         |
| `GET`  | `/logged`                   | Login status                       |
| `GET`  | `/admin`                    | Admin status                       |

### The User Object

The **User** object, that is, the entry on the database representing a single user, is defined below:

```json
{
    "id": 1,
    "username": "jondoe",
    "fullname": "John Doe",
    "address": "Upper Manhattan, New York",
    "phone": "085151515151",
    "email": "john.doe@mymail.com",
    "password": "$2y$10$11WHQ6ykJvmUPzDDenT...",
    "is_admin": false,
    "is_active": true
}
```

#### Attributes

| Name        | Data Type | Note                           |
| ----------- | --------- | ------------------------------ |
| `id`        | `int`     | `unique`                       |
| `username`  | `string`  | `unique`                       |
| `fullname`  | `string`  |                                |
| `address`   | `string`  |                                |
| `phone`     | `string`  |                                |
| `email`     | `string`  |                                |
| `password`  | `string`  | Password-hashed using `bcrypt` |
| `is_admin`  | `boolean` | Tags a user as an admin        |
| `is_active` | `boolean` | Use description TBD            |

### Authenticate Account Login <a name="end-auth"></a>

Authenticates credentials provided by the user, and if matches database, returns an access token that can be used by the client. The access token **CANNOT** be shown again, so it's up to the mobile app's developer to store this token securely.

| Method | URI      |
| ------ | -------- |
| `POST` | `/login` |

#### Request Parameter

| Key        | Value Type | Note       |
| ---------- | ---------- | ---------- |
| `username` | `string`   | `required` |
| `password` | `string`   | `required` |

#### Failure Condition

-   Wrong `username` or `password`

#### Example Request

```json
{
    "username": "jdoe",
    "password": "jdoeloveicecream"
}
```

#### Example Success Response

```json
{
    "status": {
        "code": 200
    },
    "response": {
        "message": "Authentication successful",
        "token": "<your access token here>"
    }
}
```

#### Example Failed Response (Wrong `username` or `password`)

```json
{
    "status": {
        "code": 401,
        "message": "Unauthorized"
    },
    "response": "Wrong password or username"
}
```

### Register an Account <a name="end-register"></a>

Validates credentials provided by the user, and then storing it in the database if passed.

| Method | URI         |
| ------ | ----------- |
| `POST` | `/register` |

#### Request Parameter

| Key        | Value Type     | Note                    |
| ---------- | -------------- | ----------------------- |
| `username` | `string`       | `required`              |
| `fullname` | `string`       | `required`              |
| `address`  | `string`       | `required`              |
| `phone`    | `default(int)` | `required`              |
| `email`    | `string`       | `required`              |
| `password` | `string`       | `required` Raw password |

#### Failure Condition

-   Semantic failures, such as:
    -   Attribute(s) already exists
    -   Attribute(s) incomplete
-   Validation failures, with the rules:
    -   `username`: 3-255 characters, alphanumeric with dashes
    -   `fullname`: 3-255 characters
    -   `address`: 3-255 characters
    -   `phone`: max 12 numerical characters
    -   `email`: valid email format and DNS, max 255 characters

#### Example Request

```json
{
    "username": "jdoe",
    "fullname": "John Doe",
    "address": "Mountain View, California",
    "phone": 775655142,
    "email": "johnny@examplemail.org",
    "password": "johndoeisthebest"
}
```

#### Example Success Response

```json
{
    "status": {
        "code": 200
    },
    "response": {
        "message": "User succesfully registered",
        "username": "jdoe",
        "token": "<your access token here>"
    }
}
```

#### Example Failed Response (username already exists)

```json
{
    "status": {
        "code": 422,
        "message": "Unprocessable content"
    },
    "response": {
        "username": ["The username has already been taken."]
    }
}
```

#### Example Failed Response (username not provided)

```json
{
    "status": {
        "code": 422,
        "message": "Unprocessable content"
    },
    "response": {
        "username": ["The username field is required."]
    }
}
```

#### Example Failed Response (Validation failures)

```json
{
    "status": {
        "code": 422,
        "message": "Unprocessable content"
    },
    "response": {
        "username": ["The username must be at least 3 characters."],
        "phone": ["The phone must be a number."]
    }
}
```

### Logout / Destroy Access Token <a name="end-logout"></a>

Destroys access token of the corresponding user.

| Method | URI       |
| ------ | --------- |
| `get`  | `/logout` |

#### Request Parameter

None.

#### Failure Condition

None.

#### Example Request

```json
{}
```

#### Example Success Response

```json
{
    "status": {
        "code": 200
    },
    "response": {
        "message": "Token successfully invalidated"
    }
}
```

### Get Profile <a name="end-getprofile"></a>

Retrieves profile of the corresponding user.

| Method | URI        |
| ------ | ---------- |
| `GET`  | `/profile` |

#### Request Parameter

None.

#### Failure Condition

None.

#### Example Request

```json
{}
```

#### Example Success Response

```json
{
    "status": {
        "code": 200
    },
    "response": {
        "logged_in": true,
        "user_info": {
            "id": 1,
            "username": "jdoe",
            "fullname": "John Doe",
            "address": "Mountain View, California",
            "phone": "775655142",
            "email": "johnny@examplemail.org",
            "is_admin": 0,
            "is_active": 0,
            "created_at": "2022-05-12T09:25:39.000000Z",
            "updated_at": "2022-05-13T06:39:54.000000Z"
        }
    }
}
```

### Edit Profile <a name="end-editprofile"></a>

Edits profile of the corresponding user.

| Method | URI               |
| ------ | ----------------- |
| `POST` | `/profile/update` |

#### Request Parameter

At least one of the following attibutes should be provided.

| Key        | Value Type     | Note |
| ---------- | -------------- | ---- |
| `username` | `string`       |      |
| `fullname` | `string`       |      |
| `address`  | `string`       |      |
| `phone`    | `default(int)` |      |
| `email`    | `string`       |      |
| `password` | `string`       |      |

#### Failure Condition

-   None of the attributes is provided
-   Validation failures with the same rules as the [register](#end-register) endpoint.

#### Example Request

```json
{
    "fullname": "Charlotte",
    "address": "Upper Manhattan"
}
```

#### Example Success Response

```json
{
    "status": {
        "code": 200
    },
    "response": {
        "new_profile": {
            "id": 5,
            "username": "jdoe",
            "fullname": "Charlotte",
            "address": "Upper Manhattan",
            "phone": "+16369462368",
            "email": "jdoe@examplemail.org",
            "is_admin": 0,
            "is_active": 0,
            "created_at": "2022-05-12T16:03:26.000000Z",
            "updated_at": "2022-06-07T13:20:35.000000Z"
        }
    }
}
```

#### Example Failed Response (None of the attributes is provided)

```json
{
    "status": {
        "code": 422,
        "message": "Unprocessable content"
    },
    "response": {
        "username": ["Atleast one attribute should be edited!"],
        "fullname": ["Atleast one attribute should be edited!"],
        "address": ["Atleast one attribute should be edited!"],
        "phone": ["Atleast one attribute should be edited!"],
        "email": ["Atleast one attribute should be edited!"],
        "password": ["Atleast one attribute should be edited!"]
    }
}
```

### `admin-only` Retrieve All User Profiles <a name="end-getuserprofile"></a>

Retrieves all user profiles from the database. The result **will always be** paginated, and the default items per page is 10 if not specified.

| Method | URI              |
| ------ | ---------------- |
| `GET`  | `/admin/profile` |

#### Request Parameter

| Key          | Value Type     | Note                         |
| ------------ | -------------- | ---------------------------- |
| `page_limit` | `default(int)` | `optional`, defaults to 10   |
| `page`       | `default(int)` | Access specified page number |

#### Failure Condition

<!-- Is it? Try empty users table? -->

None.

#### Example Request

```json
{
    "page_limit": 5,
    "page": 2
}
```

#### Example Success Response

```json
{
    "status": {
        "code": 200
    },
    "response": {
        "total_items": 42,
        "items_per_page": 5,
        "last_page": 9,
        "current_page": 2,
        "first_id_in_page": 6,
        "last_id_in_page": 10,
        "data": [
            {
                "id": 6,
                "username": "purdy.frederik",
                "fullname": "Turner Williamson",
                "address": "570 Harley Stream\nNorth Alexandrechester, CA 71636-6164",
                "phone": "+18508561766",
                "email": "hermiston.holly@example.com",
                "password": "$2y$10$MXd.KVlY7xBLblRzMZj25OOimt3.ucS0NHR6L4KV/BFzppNv3C7vG",
                "is_admin": 0,
                "is_active": 0,
                "created_at": "2022-05-12T16:03:26.000000Z",
                "updated_at": "2022-05-12T16:03:26.000000Z"
            },
            {
                "id": 7,
                "username": "sschneider",
                "fullname": "Lysanne Dare",
                "address": "778 Whitney Place\nNew Jamal, DE 06344",
                "phone": "+17817860973",
                "email": "margarete92@example.org",
                "password": "$2y$10$a5o.13TuzwQ5L2Kck8.A9OluIZXXDLkAna/W1PS2tf44cuCLRBPbe",
                "is_admin": 0,
                "is_active": 0,
                "created_at": "2022-05-12T16:03:26.000000Z",
                "updated_at": "2022-05-12T16:03:26.000000Z"
            }
            // ...
        ]
    }
}
```

### `admin-only` Retrieve a User Profile by ID / Username <a name="end-getuserprofileby"></a>

Retrieves a single user specified by username or user ID provided.

| Method | URI                           |
| ------ | ----------------------------- |
| `GET`  | `/admin/profile/get/:segment` |

#### Request Parameter

None. This endpoint can be used in 2 ways, such as `localhost/admin/profile/get/2` to get the user profile of a user with user ID `2`, or `localhost/admin/profile/get/jdoe` to get the user profile of a user with username `jdoe`.

#### Failure Condition

-   User does not exist

#### Example Request

URI: `localhost/admin/profile/get/jdoe`

```json
{}
```

#### Example Success Response

```json
{
    "status": {
        "code": 200
    },
    "response": {
        "user": {
            "id": 1,
            "username": "jdoe",
            "fullname": "John Doe",
            "address": "Mountain View, California",
            "phone": "0851551225647",
            "email": "jdoe@examplemail.com",
            "password": "$2y$10$11W...",
            "is_admin": 0,
            "is_active": 0,
            "created_at": "2022-05-12T09:25:39.000000Z",
            "updated_at": "2022-05-13T06:39:54.000000Z"
        }
    }
}
```

#### Example Failed Response (User does not exist)

```json
{
    "status": {
        "code": 404,
        "message": "User not found"
    },
    "response": "User not found"
}
```

### `admin-only` Edit a User Profile by ID / Username <a name="end-edituserprofileby"></a>

Edits a user profile of a user by ID / username specified in the **URI**.

| Method | URI                                       |
| ------ | ----------------------------------------- |
| `POST` | `localhost/admin/profile/update/:segment` |

#### Request Parameter

At least one of the following attibutes should be provided.

| Key        | Value Type     | Note |
| ---------- | -------------- | ---- |
| `username` | `string`       |      |
| `fullname` | `string`       |      |
| `address`  | `string`       |      |
| `phone`    | `default(int)` |      |
| `email`    | `string`       |      |
| `password` | `string`       |      |

#### Failure Condition

-   None of the attributes is provided
-   Validation failures with the same rules as the [register](#end-register) endpoint.

#### Example Request

URI: `localhost/admin/profile/update/2`

```json
{
    "fullname": "Pippa"
}
```

#### Example Success Response

```json
{
    "status": {
        "code": 200
    },
    "response": {
        "new_profile": {
            "id": 2,
            "username": "jdoe",
            "fullname": "Pippa",
            "address": "Mountain View, California",
            "phone": "16185309184",
            "email": "jdoee@example.net",
            "password": "$2y$10$qBlRcjF9R/WitjEUeIS0t...",
            "is_admin": 0,
            "is_active": 0,
            "created_at": "2022-05-12T16:02:43.000000Z",
            "updated_at": "2022-06-07T13:49:09.000000Z"
        }
    }
}
```

#### Example Failed Response (None of the attributes is provided)

```json
{
    "status": {
        "code": 422,
        "message": "Unprocessable content"
    },
    "response": {
        "username": ["Atleast one attribute should be edited!"],
        "fullname": ["Atleast one attribute should be edited!"],
        "address": ["Atleast one attribute should be edited!"],
        "phone": ["Atleast one attribute should be edited!"],
        "email": ["Atleast one attribute should be edited!"],
        "password": ["Atleast one attribute should be edited!"]
    }
}
```

<hr />
<div align="center">
<h3><a href="#top">Back to top</a></h3>
</div>
