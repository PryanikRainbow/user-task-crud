# User & Task CRUD (without authorization). RESTful API

## Used Technologies:
- Laravel 12
- Docker
- nginx:1.25.3
- php 8.2
- MySQL 8.0
- Composer
- Postman (for testing)

## Laravel Features and Patterns:

| Feature       | File Path                                      |
|---------------|------------------------------------------------|
| **Routes**    | `routes/api.php`                               |
| **Models**    | `app/Models/{Model}.php`                       |
| **Controllers** | `app/Http/Controllers/{Controller}.php`        |
| **Middleware** | `app/Http/Middleware/{Middleware}.php`         |
| **Services**  | `app/Services/{Service}.php`                   |
| **Exceptions** | `app/Exceptions/{Exception}.php`               |
| **Helpers**   | `app/Helpers/{Helper}.php`                     |
| **Requests**  | `app/Http/Requests/{Request}.php` `app/Http/Requests/{Model}/{Request}.php`                                                   |
| **Resources** | `app/Http/Resources/{Resource}.php`            |
| **Migrations** | `database/migrations/{YYYY_MM_DD_HHMMSS}_create_table_name.php` |


## Installation:

### clone git repository
```bash
git clone https://github.com/PryanikRainbow/user-task-crud.git
```

### go to the project directory
```bash
cd user-task-crud
```

### install environment
```bash
docker-compose up -d
```
### copy .env file
```bash
cp .env.example .env
```

### artisan commands are available in the php container
```bash
docker exec -it ut-php /bin/bash
```

### run migrations
```bash
php artisan migrate
```

## Usage

The project runs inside Docker and is accessible at:

### Local Address
http://localhost:8081 (considering the configured port)

### Configuration and Setup Files
- `docker/nginx/nginx.conf`
- `docker-compose.yaml`
- `.env`
- `Dockerfile`

# API Endpoints

| Method | Endpoint                                     | Description                                                                                           |
|--------|----------------------------------------------|-------------------------------------------------------------------------------------------------------|
| POST   | `/api/users/`                                | Create a new user.                                                                                    |
| GET    | `/api/users/`                                | Retrieve a list of users.                                                                             |
| GET    | `/api/users/{id}`                            | Retrieve details of a specific user by ID.                                                            |
| PUT    | `/api/users/{id}`                            | Update user information by ID.                                                                        |
| DELETE | `/api/users/{id}`                            | Delete a user by ID (along with all their tasks).                                                     |
| GET    | `/api/users/{id}/tasks`                      | Retrieve a list of tasks for a specific user.                                                         |
| POST   | `/api/users/{id}/tasks`                      | Create a new task for a user.                                                                         |
| GET    | `/api/users/{id}/tasks/{task-id}`            | Retrieve a specific task by task ID.                                                                 |
| PUT    | `/api/users/{id}/tasks/{task-id}`            | Update a task by task ID.                                                                            |
| DELETE | `/api/users/{id}/tasks/{task-id}`            | Delete a task by task ID (only if it is unprocessed).                                                 |
| DELETE | `/api/users/{id}/tasks`                      | Delete all unprocessed tasks for a user.                                                              |
| GET    | `/api/users/{id}/tasks/stats`                | Retrieve task statistics by status for a specific user.                                               |
| GET    | `/api/tasks/stats`                           | Retrieve task statistics by status across all users.                                                  |

## Create User
**POST** `/api/users/`
### Description
This endpoint allows the creation of a new user.
### Request Body
- **`login`** *(string, required)*: Minimum 4 characters, unique.
- **`password`** *(string, required)*: Minimum 6 characters, must contain letters, digits, and at least one symbol from `_`, `-`, `,`, `.`. .
- **`first_name`** *(string, required)*: Not empty, first letter must be uppercase..
- **`last_name`** *(string, required)*: Not empty, first letter must be uppercase..
- **`email`** *(string(email), required)*: unique.
#### Body Example (JSON)
```json
{
  "login": "MrJohn",
  "password": "22222A_123423",
  "first_name": "John",
  "last_name": "Doe",
  "email": "john.doe@example.com"
}
```
### Response
#### Success (201 Created)
```json
{
  "id": 1,
  "login": "MrJohn",
  "first_name": "John",
  "last_name": "Doe",
  "email": "john.doe@example.com",
  "registered_at": "31-03-2025 07:57"
}
```
### Notes
- The `registered_at` field follows the `DD-MM-YYYY HH:mm` format.


## Get Users List
**GET** `/api/users/`
### Description
This endpoint retrieves a paginated list of users with sorting options.
#### Query Parameters
- **`page`** *(integer, optional)*: The page number, must be at least `1`.
- **`per_page`** *(integer, optional)*: The number of users per page, must be at least `1`.
- **`order_by`** *(string, optional)*: The field by which to sort the results. Allowed values:
  - `first_name`
  - `last_name`
  - `email`
- **`order_dir`** *(string, optional)*: Sorting direction. Allowed values:
  - `asc` (ascending order)
  - `desc` (descending order)
### Validation Rules
- **`order_by`** must be one of: `first_name`, `last_name`, `email`.
- **`order_dir`** must be either `asc` or `desc`.
### Response
#### Success (200 OK)
```json
{
    "data": [
        {
            "id": 1,
            "login": "MrJohn",
            "first_name": "John",
            "last_name": "Doe",
            "email": "john.doe@example.com",
            "registered_at": "31-03-2025 07:56"
        },
        {
            "id": 2,
            "login": "MrJohn2",
            "first_name": "John",
            "last_name": "Doe",
            "email": "john.doe2@example.com",
            "registered_at": "31-03-2025 07:56"
        },
        {
            "id": 3,
            "login": "MrJohn3",
            "first_name": "John",
            "last_name": "Doe",
            "email": "john.doe3@example.com",
            "registered_at": "31-03-2025 07:56"
        }
    ],
    "meta": {
        "count": 3,
        "first": true,
        "last": false,
        "page": 1,
        "size": 3,
        "total_count": 20,
        "total_pages": 7
    }
}
```
### Notes
- The `meta` object provides pagination details.
- The `registered_at` field follows the `DD-MM-YYYY HH:mm` format.
- Sorting applies to the fields mentioned in `order_by`.

## Get User
**GET** `/api/users/{id}`
### Description
Retrieves details of a specific user by ID.
### Request Parameters
- **`id`**: *(integer, required)*: The ID of the user to retrieve.
### Response
**Success Response (200 OK):**
```json
{
    "id": 7,
    "login": "Mr John7",
    "first_name": "John",
    "last_name": "Doe",
    "email": "john.doe7@example.com",
    "registered_at": "31-03-2025 07:57"
}
```
### Notes
- The `registered_at` field follows the `DD-MM-YYYY HH:mm` format.

## Update User Information
**PUT** `/api/users/{id}`
### Description
Updates an existing user's information.
### Request Parameters
- **`id`**: *(integer, required)*: The ID of the user to update.
### Request Body
- **`login`** *(string, optional)*: Minimum 4 characters, unique.
- **`password`** *(string, optional)*: Minimum 6 characters, must contain letters, digits, and at least one symbol from `_`, `-`, `,`, `.`. .
- **`first_name`** *(string, optional)*: Not empty, first letter must be uppercase..
- **`last_name`** *(string, optional)*: Not empty, first letter must be uppercase..
- **`email`** *(string(email), optional)*: unique.
### Example Request
```json
{
    "login": "Mr John7",
    "first_name": "John",
    "last_name": "Doe",
    "password": "kjhghjk1_4",
    "email": "john.doe7@example.com"
}
```
### Response
**Success Response (200 OK):**
```json
{
    "id": 7,
    "login": "Mr John7",
    "first_name": "John",
    "last_name": "Doe",
    "email": "john.doe7@example.com",
    "registered_at": "31-03-2025 07:57"
}
```

## Delete User
**DELETE** `/api/users/{id}`
### Description
Delete User by ID.
### Request Parameters
- **`id`**: *(integer, required)*: The ID of the user to delete.
### Response
**Success Response (200 OK):**
```json
[]
```

## Create Task
**POST** `/api/users/{id}/tasks`
### Description
This endpoint allows the creation of a new task for a specific user.
### Request Parameters
- **`id`**: *(integer, required)*: The ID of the user.
### Request Body
- **`title`** *(string, required)*: The title of the task. Should not be empty.
- **`description`** *(string, required)*: A detailed description of the task. Should not be empty.
- **`status`** *(string, required: no)*: Available velue: New, In Progress, Finished, Failed.
- **`start_date_time`** *(string, required, datetime)*: The start date and time of the task. Format: `DD-MM-YYYY HH:mm`.
#### Body Example (JSON)
```json
{
  "title": "ew TASK",
  "description": "Some description",
  "start_date_time": "2025-03-30 14:00:00"
}
```
### Response
**Success Response (201 Сreated):**
```json
{
  "id": 29,
  "title": "New TASK",
  "description": "Some description",
  "status": "New",
  "start_date_time": "30-03-2025 14:00"
}
```
### Notes
- The start_date_time should follow the format DD-MM-YYYY HH:mm.
- The task status is automatically set to New upon creation.
- Tasks can only be created for existing users (based on the provided id).

## Get User Tasks List
**GET** `/api/users/{id}/tasks`
### Description
This endpoint retrieves a paginated list of tasks associated with a specific user.
### Path Parameters
- **`id`** *(integer, required)*: The ID of the user.
### Query Parameters
- **`page`** *(integer, optional)*: The page number, must be at least `1`.
- **`per_page`** *(integer, optional)*: The number of tasks per page, must be at least `1`.
- **`order_by`** *(string, optional)*: The field by which to sort the results. Allowed values:
  - `title`
  - `status`
- **`order_dir`** *(string, optional)*: Sorting direction. Allowed values:
  - `asc` (ascending order)
  - `desc` (descending order)
### Validation Rules
- **`order_by`** must be one of: `title`, `status`.
- **`order_dir`** must be either `asc` or `desc`.
### Response
#### Success (200 OK)
```json
{
    "data": [
        {
            "id": 8,
            "title": "New Task",
            "description": "Some description",
            "status": "In Progress",
            "start_date_time": "30-03-2025 14:00"
        },
        {
            "id": 9,
            "title": "New Task 2",
            "description": "Some description",
            "status": "New",
            "start_date_time": "30-03-2025 14:00"
        },
        {
            "id": 10,
            "title": "New Task 3",
            "description": "Some description 3",
            "status": "New",
            "start_date_time": "30-03-2025 14:00"
        }
    ],
    "meta": {
        "count": 3,
        "first": true,
        "last": false,
        "page": 1,
        "size": 3,
        "total_count": 4,
        "total_pages": 2
    }
}
```
### Notes
- The `meta` object provides pagination details.
- Sorting applies to the fields mentioned in `order_by`.

## Get Task
**GET** `/api/users/{id}/tasks/{task-id}`
### Description
This endpoint retrieves details of a specific task assigned to a user.
### Request Parameters
- **`id`** *(integer, required)*: The ID of the user.
- **`task-id`** *(integer, required)*: The ID of the task.
### Response
**Success Response (200 OK):**
```json
{
    "id": 25,
    "title": "ew TASK",
    "description": "Some description",
    "status": "New",
    "start_date_time": "30-03-2025 14:00"
}
```
### Notes
- The `start_date_time` is returned in the format `DD-MM-YYYY HH:mm`.
- The task must belong to the specified user (`id`).

## Update Task
**PUT** `/api/users/{id}/tasks/{task-id}`
### Description
This endpoint allows updating an existing task for a specific user.
### Request Parameters
- **`id`** *(integer, required)*: The ID of the user.
- **`task-id`** *(integer, required)*: The ID of the task.
### Request Body
- **`title`** *(string, optional)*: The title of the task. Should not be empty.
- **`description`** *(string, optional)*: A detailed description of the task. Should not be empty.
- **`status`** *(string, optional)*: Available values: New, In Progress, Finished, Failed.
- **`start_date_time`** *(string, optional, datetime)*: The start date and time of the task. Format: `DD-MM-YYYY HH:mm`.
#### Status Transition Rules
- Status can be changed only as follows:
  - `New` → `In Progress` → `Finished`
  - `New` → `In Progress` → `Failed`
#### Body Example (JSON)
```json
{
  "title": "Updated Task Title",
  "description": "Updated task description",
  "status": "In Progress",
  "start_date_time": "2025-04-01 10:00"
}
```
### Response
**Success Response (200 OK):**
```json
{
  "id": 6,
  "title": "Updated Task Title",
  "description": "Updated task description",
  "status": "In Progress",
  "start_date_time": "01-04-2025 10:00"
}
```
### Notes
- Status changes must follow the allowed transitions.
- Tasks can only be updated for existing users (based on the provided `id`).

## Delete All Unprocessed Tasks for a User
**DELETE** `/api/users/{id}/tasks`
### Description
Delete all unprocessed tasks (tasks with status "New") for a specific user.
### Request Parameters
- **`id`**: *(integer, required)*: The ID of the user.
### Response
**Success Response (200 OK):**
```json
    []
```
### Notes
- Only tasks with the status `New` can be deleted.

## Delete Task
**DELETE** `/api/users/{id}/tasks/{task-id}`
### Description
Delete a task by its ID (Only tasks with the status `New` can be deleted).
### Request Parameters
- **`id`**: *(integer, required)*: The ID of the user.
- **`task-id`**: *(integer, required)*: The ID of the task to delete.
### Response
**Success Response (200 OK):**
```json
    []
```

## Retrieve Task Statistics by Status for a Specific User
**GET** `/api/users/{id}/tasks/stats`
### Description
Retrieve the count of tasks grouped by their status for a specific user.
### Request Parameters
- **`id`**: *(integer, required)*: The ID of the user.
### Response
**Success Response (200 OK):**
```json
{
    "data": [
        {
            "status": "New",
            "count": 3
        },
        {
            "status": "In Progress",
            "count": 1
        },
        {
            "status": "Finished",
            "count": 0
        },
        {
            "status": "Failed",
            "count": 0
        }
    ]
}
```

## Retrieve Task Statistics by Status
**GET** `/api/users/tasks/stats`
### Description
Retrieve the count of tasks grouped by their status.
### Response
**Success Response (200 OK):**
```json
{
    "data": [
        {
            "status": "New",
            "count": 10
        },
        {
            "status": "In Progress",
            "count": 5
        },
        {
            "status": "Finished",
            "count": 5
        },
        {
            "status": "Failed",
            "count": 2
        }
    ]
}
```

## Possible Errors

### 404 Not Found
**Response Example:**
```json
{
    "message": "Not found"
}
```

### 422 Unprocessable Entity - Validation Error
**Response Example:**
```json
{
    "message": "Validation error",
    "errors": {
        "login": [
            "The login has already been taken."
        ],
        "password": [
            "The password must contain at least one of the following symbols: _ - , . "
        ],
        "last_name": [
            "The last name field is required."
        ]
    }
}
```

### 500 Internal Server Error
**Response Example:**
```json
{
    "status": "error",
    "message": "Something went wrong"
}
```