# Courses API Documentation

## Base URL
```
https://yourdomain.com/api/courses
```

## Authentication
All endpoints require JWT authentication via Bearer token or access_token cookie.

---

## Endpoints

### 1. Get All Courses
**GET** `/api/courses`

Returns paginated list of courses with filtering and sorting options.

#### Query Parameters
| Parameter | Type | Required | Description | Example |
|-----------|------|----------|-------------|---------|
| `page` | integer | No | Page number (default: 1) | `?page=2` |
| `size` | integer | No | Items per page (1-100, default: 15) | `?size=20` |
| `sort` | string | No | Sort field and direction | `?sort=title,desc` |
| `title` | string | No | Filter by title (partial match) | `?title=programming` |
| `owner` | integer | No | Filter by owner ID | `?owner=5` |
| `createdAfter` | date | No | Filter by creation date (YYYY-MM-DD) | `?createdAfter=2024-01-01` |
| `createdBefore` | date | No | Filter by creation date (YYYY-MM-DD) | `?createdBefore=2024-12-31` |

#### Sort Fields
- `title`
- `created_at`

#### Response
```json
{
  "data": [
    {
      "id": 1,
      "title": "PHP Programming",
      "description": "Learn PHP from scratch",
      "isPublished": true,
      "publishedAt": "2024-01-15T10:30:00.000000Z",
      "ownerId": 5,
      "createdBy": 5,
      "updatedBy": 5
    }
  ],
  "meta": {
    "currentPage": 1,
    "perPage": 15,
    "total": 50,
    "lastPage": 4,
    "from": 1,
    "to": 15
  }
}
```

---

### 2. Get Course by ID
**GET** `/api/courses/{id}`

Returns a specific course by its ID.

#### Path Parameters
| Parameter | Type | Required | Description |
|-----------|------|----------|-------------|
| `id` | integer | Yes | Course ID |

#### Response
```json
{
  "id": 1,
  "title": "PHP Programming",
  "description": "Learn PHP from scratch",
  "isPublished": true,
  "publishedAt": "2024-01-15T10:30:00.000000Z",
  "ownerId": 5,
  "createdBy": 5,
  "updatedBy": 5
}
```

---

### 3. Create Course
**POST** `/api/courses`

Creates a new course.

#### Request Body
```json
{
  "title": "New Course Title",
  "description": "Course description",
  "isPublished": false
}
```

#### Validation Rules
- `title`: required, string, max:255
- `description`: optional, string, max:500
- `isPublished`: optional, boolean

#### Response
```json
{
  "id": 10,
  "title": "New Course Title",
  "description": "Course description",
  "isPublished": false,
  "publishedAt": null,
  "ownerId": 5,
  "createdBy": 5,
  "updatedBy": null
}
```

---

### 4. Update Course (Partial)
**PATCH** `/api/courses/{id}`

Partially updates a course.

#### Path Parameters
| Parameter | Type | Required | Description |
|-----------|------|----------|-------------|
| `id` | integer | Yes | Course ID |

#### Request Body
```json
{
  "title": "Updated Title",
  "description": "Updated description",
  "isPublished": true
}
```

#### Validation Rules
- `title`: required if present, filled, string, max:255
- `description`: optional, string, max:500
- `isPublished`: optional, boolean

#### Response
```json
{
  "id": 1,
  "title": "Updated Title",
  "description": "Updated description",
  "isPublished": true,
  "publishedAt": "2024-01-20T14:45:00.000000Z",
  "ownerId": 5,
  "createdBy": 5,
  "updatedBy": 5
}
```

---

### 5. Restore Course
**PATCH** `/api/courses/{id}/restore`

Restores a soft-deleted course.

#### Path Parameters
| Parameter | Type | Required | Description |
|-----------|------|----------|-------------|
| `id` | integer | Yes | Course ID |

#### Response
```json
{
  "id": 1,
  "title": "Restored Course",
  "description": "Course description",
  "isPublished": false,
  "publishedAt": null,
  "ownerId": 5,
  "createdBy": 5,
  "updatedBy": 5
}
```

---

### 6. Soft Delete Course
**DELETE** `/api/courses/{id}/soft`

Soft deletes a course (moves to trash).

#### Path Parameters
| Parameter | Type | Required | Description |
|-----------|------|----------|-------------|
| `id` | integer | Yes | Course ID |

#### Response
```
204 No Content
```

---

### 7. Hard Delete Course
**DELETE** `/api/courses/{id}/force`

Permanently deletes a course.

#### Path Parameters
| Parameter | Type | Required | Description |
|-----------|------|----------|-------------|
| `id` | integer | Yes | Course ID |

#### Response
```
204 No Content
```

---

## Error Responses

### 400 Bad Request
```json
{
  "message": "Validation failed",
  "errors": {
    "title": ["The title field is required."],
    "description": ["The description may not be greater than 500 characters."]
  }
}
```

### 401 Unauthorized
```json
{
  "message": "Unauthenticated."
}
```

### 403 Forbidden
```json
{
  "message": "This action is unauthorized."
}
```

### 404 Not Found
```json
{
  "message": "No query results for model [Modules\\Core\\Models\\Course] {id}"
}
```

### 422 Unprocessable Entity
```json
{
  "message": "The given data was invalid.",
  "errors": {
    "sort": ["Поле сортировки `invalid_field` не разрешено"]
  }
}
```

---

## Permissions Required

| Endpoint | Required Permissions |
|----------|---------------------|
| GET /courses | `view any courses` or `view courses` |
| GET /courses/{id} | `view` permission for specific course |
| POST /courses | `create courses` |
| PATCH /courses/{id} | `update` permission for specific course |
| PATCH /courses/{id}/restore | `restore` permission for specific course |
| DELETE /courses/{id}/soft | `delete` permission for specific course |
| DELETE /courses/{id}/force | `force delete courses` |

---
# Lessons API Documentation

## Base URL
```
https://yourdomain.com/api/lessons
```

## Authentication
All endpoints require JWT authentication via Bearer token or access_token cookie.

---

## Endpoints

### 1. Get All Lessons
**GET** `/api/lessons`

Returns paginated list of lessons with filtering and sorting options.

#### Query Parameters
| Parameter | Type | Required | Description | Example |
|-----------|------|----------|-------------|---------|
| `page` | integer | No | Page number (default: 1) | `?page=2` |
| `size` | integer | No | Items per page (1-100, default: 15) | `?size=20` |
| `sort` | string | No | Sort field and direction | `?sort=title,desc` |
| `title` | string | No | Filter by title (partial match) | `?title=programming` |
| `course` | integer | No | Filter by course ID | `?course=5` |
| `createdAfter` | date | No | Filter by creation date (YYYY-MM-DD) | `?createdAfter=2024-01-01` |
| `createdBefore` | date | No | Filter by creation date (YYYY-MM-DD) | `?createdBefore=2024-12-31` |

#### Sort Fields
- `title`
- `created_at`

#### Response
```json
{
  "data": [
    {
      "id": 1,
      "title": "Introduction to PHP",
      "content": "PHP is a popular general-purpose scripting language...",
      "isPublished": true,
      "publishedAt": "2024-01-15T10:30:00.000000Z",
      "courseId": 5,
      "createdBy": 5,
      "updatedBy": 5
    }
  ],
  "meta": {
    "currentPage": 1,
    "perPage": 15,
    "total": 50,
    "lastPage": 4,
    "from": 1,
    "to": 15
  }
}
```

---

### 2. Get Lesson by ID
**GET** `/api/lessons/{id}`

Returns a specific lesson by its ID.

#### Path Parameters
| Parameter | Type | Required | Description |
|-----------|------|----------|-------------|
| `id` | integer | Yes | Lesson ID |

#### Response
```json
{
  "id": 1,
  "title": "Introduction to PHP",
  "content": "PHP is a popular general-purpose scripting language...",
  "isPublished": true,
  "publishedAt": "2024-01-15T10:30:00.000000Z",
  "courseId": 5,
  "createdBy": 5,
  "updatedBy": 5
}
```

---

### 3. Create Lesson
**POST** `/api/lessons`

Creates a new lesson. Course ID should be provided in the request body.

#### Request Body
```json
{
  "title": "New Lesson Title",
  "content": "Lesson content here...",
  "isPublished": false,
  "courseId": 5
}
```

#### Validation Rules
- `title`: required, string, max:255
- `content`: required, string, max:2000
- `isPublished`: optional, boolean
- `courseId`: required, exists in courses table

#### Response
```json
{
  "id": 10,
  "title": "New Lesson Title",
  "content": "Lesson content here...",
  "isPublished": false,
  "publishedAt": null,
  "courseId": 5,
  "createdBy": 5,
  "updatedBy": null
}
```

---

### 4. Update Lesson (Partial)
**PATCH** `/api/lessons/{id}`

Partially updates a lesson.

#### Path Parameters
| Parameter | Type | Required | Description |
|-----------|------|----------|-------------|
| `id` | integer | Yes | Lesson ID |

#### Request Body
```json
{
  "title": "Updated Lesson Title",
  "content": "Updated content...",
  "isPublished": true
}
```

#### Validation Rules
- `title`: sometimes required, filled, string, max:255
- `content`: sometimes required, string, max:2000
- `isPublished`: optional, boolean

#### Response
```json
{
  "id": 1,
  "title": "Updated Lesson Title",
  "content": "Updated content...",
  "isPublished": true,
  "publishedAt": "2024-01-20T14:45:00.000000Z",
  "courseId": 5,
  "createdBy": 5,
  "updatedBy": 5
}
```

---

### 5. Ask Question about Lesson
**POST** `/api/lessons/{id}/ask`

Ask an AI question about the lesson content.

#### Path Parameters
| Parameter | Type | Required | Description |
|-----------|------|----------|-------------|
| `id` | integer | Yes | Lesson ID |

#### Request Body
```json
{
  "question": "What are the main topics covered in this lesson?"
}
```

#### Validation Rules
- `question`: required, string, max:255

#### Response
```json
{
  "answer": "This lesson covers PHP basics including variables, data types, and control structures. The main topics are: 1. PHP syntax and tags 2. Variables and data types 3. Conditional statements 4. Loops and iterations"
}
```

---

### 6. Restore Lesson
**PATCH** `/api/lessons/{id}/restore`

Restores a soft-deleted lesson.

#### Path Parameters
| Parameter | Type | Required | Description |
|-----------|------|----------|-------------|
| `id` | integer | Yes | Lesson ID |

#### Response
```json
{
  "id": 1,
  "title": "Restored Lesson",
  "content": "Lesson content...",
  "isPublished": false,
  "publishedAt": null,
  "courseId": 5,
  "createdBy": 5,
  "updatedBy": 5
}
```

---

### 7. Soft Delete Lesson
**DELETE** `/api/lessons/{id}/soft`

Soft deletes a lesson (moves to trash).

#### Path Parameters
| Parameter | Type | Required | Description |
|-----------|------|----------|-------------|
| `id` | integer | Yes | Lesson ID |

#### Response
```
204 No Content
```

---

### 8. Hard Delete Lesson
**DELETE** `/api/lessons/{id}/force`

Permanently deletes a lesson.

#### Path Parameters
| Parameter | Type | Required | Description |
|-----------|------|----------|-------------|
| `id` | integer | Yes | Lesson ID |

#### Response
```
204 No Content
```

---

### 9. Generate Task for Lesson
**GET** `/api/lessons/{id}/generate-task`

Generates a task/exercise based on the lesson content.

#### Path Parameters
| Parameter | Type | Required | Description |
|-----------|------|----------|-------------|
| `id` | integer | Yes | Lesson ID |

#### Response
```json
{
  "taskId": 15,
  "question": "Write a PHP function that calculates the factorial of a number",
  "type": "coding",
  "difficulty": "beginner",
  "hint": "Remember that factorial of n is n * (n-1) * (n-2) * ... * 1"
}
```

---

### 10. Answer Task
**POST** `/api/tasks/{id}/answer`

Submit an answer to a generated task.

#### Path Parameters
| Parameter | Type | Required | Description |
|-----------|------|----------|-------------|
| `id` | integer | Yes | Task ID |

#### Request Body
```json
{
  "answer": "function factorial($n) {\n    if ($n <= 1) return 1;\n    return $n * factorial($n - 1);\n}"
}
```

#### Response
```json
{
  "isCorrect": true,
  "feedback": "Great job! Your recursive solution is correct and efficient.",
  "score": 100
}
```

---

## Error Responses

### 400 Bad Request
```json
{
  "message": "Validation failed",
  "errors": {
    "title": ["The title field is required."],
    "content": ["The content may not be greater than 2000 characters."],
    "question": ["The question field is required."]
  }
}
```

### 401 Unauthorized
```json
{
  "message": "Unauthenticated."
}
```

### 403 Forbidden
```json
{
  "message": "This action is unauthorized."
}
```

### 404 Not Found
```json
{
  "message": "No query results for model [Modules\\Core\\Models\\Lesson] {id}"
}
```

### 422 Unprocessable Entity
```json
{
  "message": "The given data was invalid.",
  "errors": {
    "sort": ["Поле сортировки `invalid_field` не разрешено"]
  }
}
```

---

## Permissions Required

| Endpoint | Required Permissions |
|----------|---------------------|
| GET /lessons | `view any lessons` or `view lessons` |
| GET /lessons/{id} | `view` permission for specific lesson |
| POST /lessons | `create lessons` and access to the course |
| PATCH /lessons/{id} | `update` permission for specific lesson |
| POST /lessons/{id}/ask | `askQuestion` permission for specific lesson |
| GET /lessons/{id}/generate-task | `generateTask` permission for specific lesson |
| POST /tasks/{id}/answer | `answerTask` permission for specific task |
| PATCH /lessons/{id}/restore | `restore` permission for specific lesson |
| DELETE /lessons/{id}/soft | `delete` permission for specific lesson |
| DELETE /lessons/{id}/force | `force delete lessons` |

---

# User Courses & Lessons API Documentation

## Base URL
```
https://yourdomain.com/api/users
```

## Authentication
All endpoints require JWT authentication via Bearer token or access_token cookie.

---

## User Courses Endpoints

### 1. Get User Courses
**GET** `/api/users/courses`

Returns paginated list of user-course relationships with filtering options.

#### Query Parameters
| Parameter | Type | Required | Description | Example |
|-----------|------|----------|-------------|---------|
| `page` | integer | No | Page number (default: 1) | `?page=2` |
| `size` | integer | No | Items per page (1-100, default: 15) | `?size=20` |
| `sort` | string | No | Sort field and direction | `?sort=created_at,desc` |
| `user` | integer | No | Filter by user ID | `?user=5` |
| `course` | integer | No | Filter by course ID | `?course=10` |

#### Sort Fields
- `created_at`

#### Response
```json
{
  "data": [
    {
      "id": 1,
      "userId": 5,
      "courseId": 10
    }
  ],
  "meta": {
    "currentPage": 1,
    "perPage": 15,
    "total": 50,
    "lastPage": 4,
    "from": 1,
    "to": 15
  }
}
```

### 2. Enroll User to Course
**POST** `/api/users/{userId}/courses/{courseId}`

Enrolls a user to a specific course.

#### Path Parameters
| Parameter | Type | Required | Description |
|-----------|------|----------|-------------|
| `userId` | integer | Yes | User ID |
| `courseId` | integer | Yes | Course ID |

#### Response
```json
{
  "id": 1,
  "userId": 5,
  "courseId": 10
}
```

### 3. Restore User-Course Relationship
**PATCH** `/api/users/{userId}/courses/{courseId}/restore`

Restores a soft-deleted user-course relationship.

#### Path Parameters
| Parameter | Type | Required | Description |
|-----------|------|----------|-------------|
| `userId` | integer | Yes | User ID |
| `courseId` | integer | Yes | Course ID |

#### Response
```json
{
  "id": 1,
  "userId": 5,
  "courseId": 10
}
```

### 4. Soft Delete User-Course Relationship
**DELETE** `/api/users/{userId}/courses/{courseId}/soft`

Soft deletes a user-course relationship (unenrolls user).

#### Path Parameters
| Parameter | Type | Required | Description |
|-----------|------|----------|-------------|
| `userId` | integer | Yes | User ID |
| `courseId` | integer | Yes | Course ID |

#### Response
```
204 No Content
```

### 5. Hard Delete User-Course Relationship
**DELETE** `/api/users/{userId}/courses/{courseId}/force`

Permanently deletes a user-course relationship.

#### Path Parameters
| Parameter | Type | Required | Description |
|-----------|------|----------|-------------|
| `userId` | integer | Yes | User ID |
| `courseId` | integer | Yes | Course ID |

#### Response
```
204 No Content
```

---

## User Lessons Endpoints

### 6. Get User Lessons Progress
**GET** `/api/users/lessons`

Returns paginated list of user lesson progress with filtering options.

#### Query Parameters
| Parameter | Type | Required | Description | Example |
|-----------|------|----------|-------------|---------|
| `page` | integer | No | Page number (default: 1) | `?page=2` |
| `size` | integer | No | Items per page (1-100, default: 15) | `?size=20` |
| `sort` | string | No | Sort field and direction | `?sort=progress,desc` |
| `user` | integer | No | Filter by user ID | `?user=5` |
| `course` | integer | No | Filter by course ID | `?course=10` |
| `lesson` | integer | No | Filter by lesson ID | `?lesson=15` |
| `isCompleted` | boolean | No | Filter by completion status | `?isCompleted=true` |

#### Sort Fields
- `progress`
- `is_completed`
- `created_at`

#### Response
```json
{
  "data": [
    {
      "id": 1,
      "userId": 5,
      "lessonId": 15,
      "courseId": 10,
      "progress": 75,
      "isCompleted": false,
      "completedAt": null
    }
  ],
  "meta": {
    "currentPage": 1,
    "perPage": 15,
    "total": 50,
    "lastPage": 4,
    "from": 1,
    "to": 15
  }
}
```

### 7. Start User Lesson
**POST** `/api/users/{userId}/lessons/{lessonId}`

Starts a lesson for a user (creates progress record).

#### Path Parameters
| Parameter | Type | Required | Description |
|-----------|------|----------|-------------|
| `userId` | integer | Yes | User ID |
| `lessonId` | integer | Yes | Lesson ID |

#### Response
```json
{
  "id": 1,
  "userId": 5,
  "lessonId": 15,
  "courseId": 10,
  "progress": 0,
  "isCompleted": false,
  "completedAt": null
}
```

### 8. Complete User Lesson
**POST** `/api/users/{lessonId}/complete`

Marks a lesson as completed for the authenticated user.

#### Path Parameters
| Parameter | Type | Required | Description |
|-----------|------|----------|-------------|
| `lessonId` | integer | Yes | Lesson ID |

#### Response
```json
{
  "id": 1,
  "userId": 5,
  "lessonId": 15,
  "courseId": 10,
  "progress": 100,
  "isCompleted": true,
  "completedAt": "2024-01-20T14:45:00.000000Z"
}
```

### 9. Restore User Lesson Progress
**PATCH** `/api/users/{userId}/lessons/{lessonId}/restore`

Restores a soft-deleted user lesson progress.

#### Path Parameters
| Parameter | Type | Required | Description |
|-----------|------|----------|-------------|
| `userId` | integer | Yes | User ID |
| `lessonId` | integer | Yes | Lesson ID |

#### Response
```json
{
  "id": 1,
  "userId": 5,
  "lessonId": 15,
  "courseId": 10,
  "progress": 75,
  "isCompleted": false,
  "completedAt": null
}
```

### 10. Soft Delete User Lesson Progress
**DELETE** `/api/users/{userId}/lessons/{lessonId}/soft`

Soft deletes a user lesson progress.

#### Path Parameters
| Parameter | Type | Required | Description |
|-----------|------|----------|-------------|
| `userId` | integer | Yes | User ID |
| `lessonId` | integer | Yes | Lesson ID |

#### Response
```
204 No Content
```

### 11. Hard Delete User Lesson Progress
**DELETE** `/api/users/{userId}/lessons/{lessonId}/force`

Permanently deletes a user lesson progress.

#### Path Parameters
| Parameter | Type | Required | Description |
|-----------|------|----------|-------------|
| `userId` | integer | Yes | User ID |
| `lessonId` | integer | Yes | Lesson ID |

#### Response
```
204 No Content
```

---

## Tasks Endpoints

### 12. Get All Tasks
**GET** `/api/tasks`

Returns paginated list of tasks with filtering options.

#### Query Parameters
| Parameter | Type | Required | Description | Example |
|-----------|------|----------|-------------|---------|
| `page` | integer | No | Page number (default: 1) | `?page=2` |
| `size` | integer | No | Items per page (1-100, default: 15) | `?size=20` |
| `sort` | string | No | Sort field and direction | `?sort=created_at,desc` |
| `user` | integer | No | Filter by user ID | `?user=5` |
| `lesson` | integer | No | Filter by lesson ID | `?lesson=15` |
| `course` | integer | No | Filter by course ID | `?course=10` |
| `status` | string | No | Filter by task status | `?status=completed` |
| `createdAfter` | date | No | Filter by creation date | `?createdAfter=2024-01-01` |
| `createdBefore` | date | No | Filter by creation date | `?createdBefore=2024-12-31` |

#### Task Statuses
- `generating` - Task is being generated by AI
- `pending_solution` - Waiting for user solution
- `under_review` - Solution is under review
- `completed` - Task completed successfully
- `rejected` - Task solution was rejected

#### Sort Fields
- `created_at`

#### Response
```json
{
  "data": [
    {
      "id": 1,
      "userId": "5",
      "lessonId": "15",
      "courseId": "10",
      "status": "pending_solution",
      "createdAt": "2024-01-15T10:30:00.000000Z",
      "content": "Write a function that calculates factorial",
      "feedback": null,
      "lastAnswer": null
    }
  ],
  "meta": {
    "currentPage": 1,
    "perPage": 15,
    "total": 50,
    "lastPage": 4,
    "from": 1,
    "to": 15
  }
}
```

### 13. Answer Task
**POST** `/api/tasks/{taskId}/answer`

Submits an answer to a task.

#### Path Parameters
| Parameter | Type | Required | Description |
|-----------|------|----------|-------------|
| `taskId` | integer | Yes | Task ID |

#### Request Body
```json
{
  "answer": "function factorial(n) { return n <= 1 ? 1 : n * factorial(n - 1); }"
}
```

#### Validation Rules
- `answer`: required, string, max:255

#### Response
```json
{
  "id": 1,
  "userId": "5",
  "lessonId": "15",
  "courseId": "10",
  "status": "under_review",
  "createdAt": "2024-01-15T10:30:00.000000Z",
  "content": "Write a function that calculates factorial",
  "feedback": "Your solution is being reviewed...",
  "lastAnswer": "function factorial(n) { return n <= 1 ? 1 : n * factorial(n - 1); }"
}
```

---

## Error Responses

### 400 Bad Request
```json
{
  "message": "Validation failed",
  "errors": {
    "answer": ["The answer field is required."],
    "status": ["The selected status is invalid."]
  }
}
```

### 401 Unauthorized
```json
{
  "message": "Unauthenticated."
}
```

### 403 Forbidden
```json
{
  "message": "This action is unauthorized."
}
```

### 404 Not Found
```json
{
  "message": "No query results for model [App\\Models\\User] {id}"
}
```

### 422 Unprocessable Entity
```json
{
  "message": "The given data was invalid.",
  "errors": {
    "sort": ["Поле сортировки `invalid_field` не разрешено"]
  }
}
```

---

## Permissions Required

| Endpoint | Required Permissions |
|----------|---------------------|
| GET /users/courses | `view any user courses` |
| POST /users/{user}/courses/{course} | `enroll` permission for course |
| PATCH /users/{user}/courses/{course}/restore | `manage course users` |
| DELETE /users/{user}/courses/{course}/soft | `deleteSoft` permission |
| DELETE /users/{user}/courses/{course}/force | `deleteHard` permission |
| GET /users/lessons | `view any user lessons` |
| POST /users/{user}/lessons/{lesson} | `create` permission for user lesson |
| POST /users/{lesson}/complete | `complete` permission for lesson |
| PATCH /users/{user}/lessons/{lesson}/restore | `restore any user lessons` |
| DELETE /users/{user}/lessons/{lesson}/soft | `deleteSoft` permission |
| DELETE /users/{user}/lessons/{lesson}/force | `force delete user lessons` |
| GET /tasks | `view any tasks` or `view tasks` |
| POST /tasks/{task}/answer | `answerTask` permission for task |

---