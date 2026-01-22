# API Documentation

Complete REST API reference for the Global Parity Engine.

## Table of Contents

- [Overview](#overview)
- [Authentication](#authentication)
- [Base URL](#base-url)
- [Endpoints](#endpoints)
- [Data Models](#data-models)
- [Error Handling](#error-handling)
- [Rate Limiting](#rate-limiting)
- [Examples](#examples)

## Overview

The Global Parity Engine provides a RESTful API built on top of the WordPress REST API. It follows REST conventions and returns JSON responses.

### API Version

Current version: **v2** (WordPress REST API v2)

### Content Type

All requests and responses use `application/json` content type.

## Authentication

The API supports multiple authentication methods:

### 1. WordPress Cookies (Browser)

Automatic when logged into WordPress admin.

### 2. Application Passwords

```bash
curl -u "username:application_password" \
  https://yoursite.com/wp-json/wp/v2/parity_data
```

To create an application password:
1. Go to **Users** → **Profile**
2. Scroll to **Application Passwords**
3. Enter name and click **Add New**
4. Copy the generated password

### 3. JWT Authentication (Optional)

Requires JWT Authentication plugin.

```bash
# Get token
curl -X POST https://yoursite.com/wp-json/jwt-auth/v1/token \
  -d "username=admin&password=yourpassword"

# Use token
curl -H "Authorization: Bearer YOUR_JWT_TOKEN" \
  https://yoursite.com/wp-json/wp/v2/parity_data
```

## Base URL

```
https://yoursite.com/wp-json
```

All endpoints are relative to this base URL.

## Endpoints

### Parity Data Endpoints

#### List All Parity Data

```
GET /wp/v2/parity_data
```

**Query Parameters:**

| Parameter | Type | Default | Description |
|-----------|------|---------|-------------|
| `page` | int | 1 | Current page |
| `per_page` | int | 10 | Items per page (max: 100) |
| `search` | string | - | Search in title and content |
| `category` | int | - | Filter by category ID |
| `orderby` | string | date | Order by field (date, title, id) |
| `order` | string | desc | Order direction (asc, desc) |
| `status` | string | publish | Post status filter |

**Example Request:**

```bash
curl https://yoursite.com/wp-json/wp/v2/parity_data?per_page=20&page=1
```

**Example Response:**

```json
[
  {
    "id": 123,
    "date": "2026-01-15T10:30:00",
    "modified": "2026-01-15T10:30:00",
    "slug": "sample-data-entry",
    "status": "publish",
    "type": "parity_data",
    "link": "https://yoursite.com/parity_data/sample-data-entry/",
    "title": {
      "rendered": "Sample Data Entry"
    },
    "content": {
      "rendered": "<p>This is a sample parity data entry.</p>",
      "protected": false
    },
    "author": 1,
    "meta": {
      "latitude": "40.7128",
      "longitude": "-74.0060",
      "parity_value": "100",
      "data_source": "Survey 2026",
      "collection_date": "2026-01-15",
      "region": "North America",
      "category": "Economic"
    },
    "_links": {
      "self": [
        {
          "href": "https://yoursite.com/wp-json/wp/v2/parity_data/123"
        }
      ],
      "collection": [
        {
          "href": "https://yoursite.com/wp-json/wp/v2/parity_data"
        }
      ]
    }
  }
]
```

**Response Headers:**

```
X-WP-Total: 150
X-WP-TotalPages: 8
Link: <https://yoursite.com/wp-json/wp/v2/parity_data?page=2>; rel="next"
```

#### Get Single Parity Data Entry

```
GET /wp/v2/parity_data/{id}
```

**Path Parameters:**

| Parameter | Type | Required | Description |
|-----------|------|----------|-------------|
| `id` | int | Yes | Parity data entry ID |

**Example Request:**

```bash
curl https://yoursite.com/wp-json/wp/v2/parity_data/123
```

**Example Response:**

```json
{
  "id": 123,
  "date": "2026-01-15T10:30:00",
  "modified": "2026-01-15T10:30:00",
  "slug": "sample-data-entry",
  "status": "publish",
  "type": "parity_data",
  "title": {
    "rendered": "Sample Data Entry"
  },
  "content": {
    "rendered": "<p>This is a sample parity data entry.</p>"
  },
  "meta": {
    "latitude": "40.7128",
    "longitude": "-74.0060",
    "parity_value": "100"
  }
}
```

#### Create Parity Data Entry

```
POST /wp/v2/parity_data
```

**Authentication Required**: Yes  
**Required Capability**: `edit_posts`

**Request Body:**

```json
{
  "title": "New Data Entry",
  "content": "Description of the data entry",
  "status": "publish",
  "meta": {
    "latitude": "40.7128",
    "longitude": "-74.0060",
    "parity_value": "85.5",
    "data_source": "Field Survey",
    "collection_date": "2026-01-20",
    "region": "New York",
    "category": "Social"
  }
}
```

**Example Request:**

```bash
curl -X POST https://yoursite.com/wp-json/wp/v2/parity_data \
  -u "username:password" \
  -H "Content-Type: application/json" \
  -d '{
    "title": "New Data Entry",
    "content": "Description here",
    "meta": {
      "latitude": "40.7128",
      "longitude": "-74.0060",
      "parity_value": "85.5"
    }
  }'
```

**Example Response:**

```json
{
  "id": 124,
  "date": "2026-01-22T15:45:00",
  "status": "publish",
  "title": {
    "raw": "New Data Entry",
    "rendered": "New Data Entry"
  },
  "meta": {
    "latitude": "40.7128",
    "longitude": "-74.0060",
    "parity_value": "85.5"
  }
}
```

#### Update Parity Data Entry

```
PUT /wp/v2/parity_data/{id}
PATCH /wp/v2/parity_data/{id}
```

**Authentication Required**: Yes  
**Required Capability**: `edit_post` (for this specific post)

**Example Request:**

```bash
curl -X PUT https://yoursite.com/wp-json/wp/v2/parity_data/123 \
  -u "username:password" \
  -H "Content-Type: application/json" \
  -d '{
    "title": "Updated Title",
    "meta": {
      "parity_value": "92.3"
    }
  }'
```

#### Delete Parity Data Entry

```
DELETE /wp/v2/parity_data/{id}
```

**Authentication Required**: Yes  
**Required Capability**: `delete_post`

**Query Parameters:**

| Parameter | Type | Default | Description |
|-----------|------|---------|-------------|
| `force` | bool | false | Bypass trash and permanently delete |

**Example Request:**

```bash
# Move to trash
curl -X DELETE https://yoursite.com/wp-json/wp/v2/parity_data/123 \
  -u "username:password"

# Permanently delete
curl -X DELETE "https://yoursite.com/wp-json/wp/v2/parity_data/123?force=true" \
  -u "username:password"
```

### Custom Endpoints

#### Get Parity Summary

```
GET /parity/v1/summary
```

Returns aggregated statistics for parity data.

**Query Parameters:**

| Parameter | Type | Default | Description |
|-----------|------|---------|-------------|
| `region` | string | - | Filter by region |
| `category` | string | - | Filter by category |
| `start_date` | string | - | Start date (YYYY-MM-DD) |
| `end_date` | string | - | End date (YYYY-MM-DD) |

**Example Request:**

```bash
curl https://yoursite.com/wp-json/parity/v1/summary?region=Europe
```

**Example Response:**

```json
{
  "total_entries": 1543,
  "average_value": 78.5,
  "median_value": 80.2,
  "min_value": 45.3,
  "max_value": 98.7,
  "by_region": {
    "Europe": 523,
    "Asia": 412,
    "Americas": 385,
    "Africa": 223
  },
  "by_category": {
    "Economic": 645,
    "Social": 532,
    "Political": 366
  },
  "date_range": {
    "start": "2025-01-01",
    "end": "2026-01-22"
  }
}
```

#### Get Geographic Data

```
GET /parity/v1/geo
```

Returns parity data with geographic coordinates for mapping.

**Query Parameters:**

| Parameter | Type | Default | Description |
|-----------|------|---------|-------------|
| `bounds` | string | - | Map bounds (sw_lat,sw_lng,ne_lat,ne_lng) |
| `limit` | int | 100 | Maximum results |

**Example Request:**

```bash
curl "https://yoursite.com/wp-json/parity/v1/geo?bounds=40.5,-74.5,41.0,-73.5&limit=50"
```

**Example Response:**

```json
{
  "type": "FeatureCollection",
  "features": [
    {
      "type": "Feature",
      "geometry": {
        "type": "Point",
        "coordinates": [-74.0060, 40.7128]
      },
      "properties": {
        "id": 123,
        "title": "Sample Data Entry",
        "value": 100,
        "category": "Economic",
        "date": "2026-01-15"
      }
    }
  ]
}
```

## Data Models

### Parity Data Object

```typescript
interface ParityData {
  id: number;
  date: string; // ISO 8601 format
  modified: string; // ISO 8601 format
  slug: string;
  status: 'publish' | 'draft' | 'pending' | 'private';
  type: 'parity_data';
  link: string;
  title: {
    rendered: string;
    raw?: string;
  };
  content: {
    rendered: string;
    raw?: string;
    protected: boolean;
  };
  author: number;
  meta: {
    latitude: string;
    longitude: string;
    parity_value: string;
    data_source?: string;
    collection_date?: string;
    region?: string;
    category?: string;
  };
  _links: Links;
}
```

### Meta Fields

| Field | Type | Required | Description |
|-------|------|----------|-------------|
| `latitude` | float | Yes | Latitude coordinate (-90 to 90) |
| `longitude` | float | Yes | Longitude coordinate (-180 to 180) |
| `parity_value` | float | Yes | Parity value (0-100) |
| `data_source` | string | No | Source of the data |
| `collection_date` | date | No | When data was collected |
| `region` | string | No | Geographic region |
| `category` | string | No | Data category |

## Error Handling

### Error Response Format

```json
{
  "code": "rest_invalid_param",
  "message": "Invalid parameter(s): latitude",
  "data": {
    "status": 400,
    "params": {
      "latitude": "latitude must be a number between -90 and 90"
    }
  }
}
```

### Common Error Codes

| HTTP Status | Code | Description |
|-------------|------|-------------|
| 400 | `rest_invalid_param` | Invalid request parameters |
| 401 | `rest_forbidden` | Authentication required |
| 403 | `rest_forbidden` | Insufficient permissions |
| 404 | `rest_post_invalid_id` | Resource not found |
| 500 | `rest_internal_error` | Server error |

### Error Examples

**Invalid Latitude:**

```json
{
  "code": "rest_invalid_param",
  "message": "Invalid parameter(s): latitude",
  "data": {
    "status": 400,
    "params": {
      "latitude": "latitude must be between -90 and 90"
    }
  }
}
```

**Unauthorized:**

```json
{
  "code": "rest_forbidden",
  "message": "Sorry, you are not allowed to create posts as this user.",
  "data": {
    "status": 401
  }
}
```

**Not Found:**

```json
{
  "code": "rest_post_invalid_id",
  "message": "Invalid post ID.",
  "data": {
    "status": 404
  }
}
```

## Rate Limiting

The API does not implement rate limiting by default, but it's recommended to:

- Implement caching for repeated requests
- Use pagination for large datasets
- Batch operations when possible
- Monitor API usage in production

### Best Practices

1. **Cache responses** when appropriate
2. **Use pagination** with `per_page` parameter
3. **Filter results** to reduce payload size
4. **Batch operations** instead of individual requests
5. **Handle errors** gracefully with retries

## Examples

### JavaScript (Fetch API)

```javascript
// List all parity data
fetch('https://yoursite.com/wp-json/wp/v2/parity_data')
  .then(response => response.json())
  .then(data => console.log(data))
  .catch(error => console.error('Error:', error));

// Create new entry
fetch('https://yoursite.com/wp-json/wp/v2/parity_data', {
  method: 'POST',
  headers: {
    'Content-Type': 'application/json',
    'Authorization': 'Basic ' + btoa('username:password')
  },
  body: JSON.stringify({
    title: 'New Entry',
    meta: {
      latitude: '40.7128',
      longitude: '-74.0060',
      parity_value: '85'
    }
  })
})
.then(response => response.json())
.then(data => console.log('Created:', data));
```

### PHP (WordPress)

```php
<?php
// Get parity data
$response = wp_remote_get('https://yoursite.com/wp-json/wp/v2/parity_data');
$data = json_decode(wp_remote_retrieve_body($response));

// Create entry
$response = wp_remote_post('https://yoursite.com/wp-json/wp/v2/parity_data', array(
    'headers' => array(
        'Content-Type' => 'application/json',
        'Authorization' => 'Basic ' . base64_encode('username:password')
    ),
    'body' => json_encode(array(
        'title' => 'New Entry',
        'meta' => array(
            'latitude' => '40.7128',
            'longitude' => '-74.0060',
            'parity_value' => '85'
        )
    ))
));
```

### Python (Requests)

```python
import requests
from requests.auth import HTTPBasicAuth

# List parity data
response = requests.get(
    'https://yoursite.com/wp-json/wp/v2/parity_data',
    params={'per_page': 20}
)
data = response.json()

# Create entry
response = requests.post(
    'https://yoursite.com/wp-json/wp/v2/parity_data',
    auth=HTTPBasicAuth('username', 'password'),
    json={
        'title': 'New Entry',
        'meta': {
            'latitude': '40.7128',
            'longitude': '-74.0060',
            'parity_value': '85'
        }
    }
)
created = response.json()
```

### cURL

```bash
# List with pagination
curl "https://yoursite.com/wp-json/wp/v2/parity_data?per_page=10&page=2"

# Search
curl "https://yoursite.com/wp-json/wp/v2/parity_data?search=economy"

# Create with authentication
curl -X POST https://yoursite.com/wp-json/wp/v2/parity_data \
  -u "username:password" \
  -H "Content-Type: application/json" \
  -d '{
    "title": "Economic Data Q1 2026",
    "content": "Quarterly economic parity analysis",
    "status": "publish",
    "meta": {
      "latitude": "51.5074",
      "longitude": "-0.1278",
      "parity_value": "78.5",
      "region": "Europe",
      "category": "Economic"
    }
  }'

# Update
curl -X PUT https://yoursite.com/wp-json/wp/v2/parity_data/123 \
  -u "username:password" \
  -H "Content-Type: application/json" \
  -d '{"meta": {"parity_value": "82.3"}}'

# Delete
curl -X DELETE https://yoursite.com/wp-json/wp/v2/parity_data/123 \
  -u "username:password"
```

## Webhooks (Future)

Webhooks are planned for future releases to enable real-time notifications:

```json
{
  "event": "parity_data.created",
  "timestamp": "2026-01-22T15:45:00Z",
  "data": {
    "id": 124,
    "title": "New Entry",
    "meta": {
      "latitude": "40.7128",
      "longitude": "-74.0060"
    }
  }
}
```

## Support

For API support:
- Open an issue: https://github.com/rongabby/global-parity-engine/issues
- See documentation: https://github.com/rongabby/global-parity-engine/docs

---

**API Version**: v2  
**Last Updated**: January 2026
