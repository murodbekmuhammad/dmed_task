# DMED TASK

## 📌 Features

- **JWT Authentication**: Secured endpoints using `php-open-source-saver/jwt-auth` with Bearer token header authorization.
- **SHA-256 Image Deduplication**: Computes SHA-256 hash of incoming uploaded files to prevent storing identical images multiple times on disk and in database.
- **WebP Optimization**: Converts uploaded images to standard high-efficiency WebP format using `Intervention/image-laravel` with GD driver.
- **Asynchronous Queue Processing**: Handles heavy image reading, hash computation, WebP conversion, and storage asynchronously via Laravel Queues and `ProcessImageJob`.
- **Repository & Service Pattern**: Clean, maintainable software architecture using `BaseService` / `ImageService` and `BaseRepository` / `ImageRepository` for separation of business logic and data access.
- **User-Scoped Ownership & Shared Deletion**: Tracks user-image relationships via pivot (`syncWithoutDetaching`). Safely detaches image records on user delete and deletes physical storage files only when no user reference remains.
- **Custom Authorization Policies**: Protects image access and deletion using Laravel Policies (`can:view,image`, `can:delete,image`).
- **Standardized Response Macros**: Custom response macros (`Response::successJson` and `Response::errorJson`) providing uniform API response structures.
- **Automatic Failure & Temporary File Cleanup**: Automatically cleans up temporary upload paths upon job completion or failure.

---

## ⚙️ Installation & Setup

### Prerequisites
- **PHP**: `^8.2`
- **Composer**
- **Database**: MySQL

### Steps

1. **Clone the Repository**
   ```bash
   git clone https://github.com/murodbekmuhammad/dmed_task
   cd dmed-task
   ```

2. **Install PHP**
   ```bash
   composer install
   ```

3. **Configure env**
   ```bash
   cp .env.example .env
   ```
4. **Generate Application Key & JWT Secret**
   ```bash
   php artisan key:generate
   php artisan jwt:secret
   ```

5. **Run Migrations & Seeders**
   ```bash
   php artisan migrate
   php artisan db:seed
   ```

6. **Create Storage Symbolic Link**
   ```bash
   php artisan storage:link
   ```

---

## 🚀 Running the Application

Alternatively, start the server and queue worker manually:
```bash
php artisan serve
php artisan queue:work
```
---

## 📖 API Documentation

All API requests expecting JSON responses should include the header:
```http
Accept: application/json
```

### 🔑 Authentication

#### 1. User Login
- **URL**: `/api/login`
- **Method**: `POST`
- **Auth**: None
- **Body (`application/json`)**:
  | Parameter | Type | Required | Description |
  |---|---|---|---|
  | `email` | `string` | Yes | Valid user email address |
  | `password` | `string` | Yes | User password |

- **Success Response (200)**:
  ```json
  {
    "success": true,
    "message": "Login successful",
    "data": {
      "access_token": "<jwt-token>",
      "token_type": "bearer",
      "expires_in": 3600
    }
  }
  ```

---

### 🖼️ Images Management

> **Note**: All routes below require the header:
> `Authorization: Bearer <access_token>`

#### 2. Get User Images (Paginated)
- **URL**: `/api/images`
- **Method**: `GET`
- **Auth**: Bearer Token
- **Success Response (200)**: Returns a paginated list of uploaded images owned by the user.
 ```json 
{
    "success": true,
    "message": "Success",
    "data": {
        "current_page": 1,
        "data": [
            {
                "id": 14,
                "path": "images/7ec7ee9e33bed730f4df0d53ab35fe3d53aeb84a77d3fc1ffd46d4e621f89a1a.webp",
                "original_name": "2026-07-29 17.36.23.jpg",
                "hash": "7ec7ee9e33bed730f4df0d53ab35fe3d53aeb84a77d3fc1ffd46d4e621f89a1a",
                "size": "2624",
                "status": "completed",
                "created_at": "2026-08-10T03:55:44.000000Z",
                "updated_at": "2026-08-10T03:55:44.000000Z",
                "pivot": {
                    "user_id": 9,
                    "image_id": 14,
                    "created_at": "2026-08-10T03:55:44.000000Z",
                    "updated_at": "2026-08-10T03:55:44.000000Z"
                }
            }
        ],
        "first_page_url": "http://127.0.0.1:8000/api/images?page=1",
        "from": 1,
        "last_page": 1,
        "last_page_url": "http://127.0.0.1:8000/api/images?page=1",
        "links": [
            {
                "url": null,
                "label": "&laquo; Previous",
                "page": null,
                "active": false
            },
            {
                "url": "http://127.0.0.1:8000/api/images?page=1",
                "label": "1",
                "page": 1,
                "active": true
            },
            {
                "url": null,
                "label": "Next &raquo;",
                "page": null,
                "active": false
            }
        ],
        "next_page_url": null,
        "path": "http://127.0.0.1:8000/api/images",
        "per_page": 15,
        "prev_page_url": null,
        "to": 1,
        "total": 1
    }
}
```
      
#### 3. Upload New Image
- **URL**: `/api/images`
- **Method**: `POST`
- **Auth**: Bearer Token
- **Body (`multipart/form-data`)**:
  | Parameter | Type | Required | Rules |
  |---|---|---|---|
  | `image` | `file` | Yes | `jpeg`, `jpg`, `png` (Max: 5MB) |

- **Success Response (200)**:
  ```json
  {
    "success": true,
    "data": {
      "message": "Image upload received.",
      "status": "processing"
    }
  }
  ```

#### 4. Get Specific Image Details
- **URL**: `/api/images/{id}`
- **Method**: `GET`
- **Auth**: Bearer Token
- **Success Response (200)**: Returns image details if user is authorized.
```json 
{
  "success": true,
  "message": "Success",
  "data": {
      "id": 14,
      "path": "images/7ec7ee9e33bed730f4df0d53ab35fe3d53aeb84a77d3fc1ffd46d4e621f89a1a.webp",
      "original_name": "2026-07-29 17.36.23.jpg",
      "hash": "7ec7ee9e33bed730f4df0d53ab35fe3d53aeb84a77d3fc1ffd46d4e621f89a1a",
      "size": "2624",
      "status": "completed",
      "created_at": "2026-08-10T03:55:44.000000Z",
      "updated_at": "2026-08-10T03:55:44.000000Z"
      }
  }
```

#### 5. Delete Image
- **URL**: `/api/images/{id}`
- **Method**: `DELETE`
- **Auth**: Bearer Token
- **Success Response (200)**:
  ```json
  {
    "success": true,
    "data": {
      "message": "The image has been deleted successfully"
    }
  }
  ```

---
