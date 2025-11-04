# Notifications API

API reference for the notification routes defined in `routes/api.php`. Use the Postman collection at `docs/postman/notifications.postman_collection.json` to explore these endpoints quickly.

## Authentication

- All `/notifications` routes require a Sanctum bearer token (`Authorization: Bearer <token>`).
- `POST /test-notification` is unauthenticated by default but may be restricted in production; treat it as an internal tooling endpoint.

## Base URL

All examples assume the API root is `http://localhost/api`. Adjust the host and port to match your environment.

## Endpoints Overview

| Method | Route                                   | Controller Action                     | Notes |
| ------ | --------------------------------------- | ------------------------------------- | ----- |
| GET    | `/notifications`                        | `NotificationController@index`        | Paginated list of all notifications. |
| GET    | `/notifications/unread`                 | `NotificationController@unread`       | Paginated unread notifications. |
| GET    | `/notifications/read`                   | `NotificationController@read`         | Paginated read notifications. |
| GET    | `/notifications/{id}`                   | `NotificationController@show`         | Single notification by id (404 if not owned). |
| POST   | `/notifications/{id}/mark-as-read`      | `NotificationController@markAsRead`   | No body required. |
| POST   | `/notifications/mark-all-as-read`       | `NotificationController@markAllAsRead`| Marks every unread notification as read. |
| DELETE | `/notifications/{id}`                   | `NotificationController@destroy`      | Deletes a single notification. |
| DELETE | `/notifications`                        | `NotificationController@destroyAll`   | Deletes all notifications for the user. |
| GET    | `/notifications/count`                  | `NotificationController@count`        | Returns total count (`{"count": <int>}`). |
| GET    | `/notifications/unread-count`           | `NotificationController@unreadCount`  | Returns unread count. |
| GET    | `/notifications/read-count`             | `NotificationController@readCount`    | Returns read count. |
| POST   | `/test-notification`                    | `TestNotificationController`          | Sends a Firebase push notification to selected recipients. |

## Request & Response Notes

### Pagination

- The list endpoints (`/notifications`, `/notifications/unread`, `/notifications/read`) return Laravel's paginator JSON payload.
- Optional query params: `page` (int), `per_page` (if enabled in global paginator settings).

### Mark As Read

```http
POST /notifications/123/mark-as-read
Authorization: Bearer <token>
Accept: application/json
```

Response:

```json
{
    "message": "Notification marked as read."
}
```

### Mark All As Read

```http
POST /notifications/mark-all-as-read
Authorization: Bearer <token>
Accept: application/json
```

Response:

```json
{
    "message": "All notifications marked as read."
}
```

### Delete Notification

```http
DELETE /notifications/123
Authorization: Bearer <token>
Accept: application/json
```

Response:

```json
{
    "message": "Notification deleted."
}
```

### Count Endpoints

- `/notifications/count`, `/notifications/unread-count`, and `/notifications/read-count` all respond with:

```json
{
    "count": 5
}
```

### Test Notification Payload

`POST /test-notification` expects JSON that matches `App\Http\Requests\TestNotificationRequest`:

```json
{
    "title": "Test notification",
    "body": "Triggered from Postman",
    "data": {
        "channel": "demo"
    },
    "send_to_auth": false,
    "student_ids": [1, 2],
    "guardian_ids": [],
    "teacher_ids": []
}
```

- `title` and `body` are required strings.
- `data` is an optional associative array that becomes part of the push payload.
- Recipient arrays (`student_ids`, `guardian_ids`, `teacher_ids`) accept numeric IDs and are validated against their respective tables.
- If `send_to_auth` is true, the authenticated user is added to the recipient list (when available).

## Using the Postman Collection

1. Import `docs/postman/notifications.postman_collection.json` into Postman.
2. Set `base_url` (e.g. `http://localhost:8000/api`) and `sanctum_token` in the collection variables.
3. Run individual requests or use the folder to sequence common flows (e.g. list unread → mark-as-read → fetch counts).
4. The request descriptions contain inline comments that recap the controller action, expected inputs, and useful handling tips.
