# Global Parity Engine - API Usage Examples

## Overview
This document provides comprehensive examples of how to use the Global Parity Engine WordPress plugin API.

## Base URLs

- WordPress Standard API: `/wp-json/wp/v2/`
- Custom API: `/wp-json/global-parity/v1/`

## Authentication

For creating, updating, or deleting data, you'll need to authenticate using one of these methods:
- Application Passwords (WordPress 5.6+)
- OAuth
- Basic Authentication (development only)

## Standard WordPress REST API Endpoints

### 1. Get All Parity Data

```bash
GET /wp-json/wp/v2/parity-data
```

**Query Parameters:**
- `per_page`: Number of items per page (default: 10, max: 100)
- `page`: Page number
- `search`: Search term
- `parity_category`: Filter by category ID
- `parity_region`: Filter by region ID

**Example Response:**
```json
[
  {
    "id": 1,
    "title": {
      "rendered": "Gender Parity in Technology"
    },
    "content": {
      "rendered": "Analysis of gender parity in the technology sector..."
    },
    "parity_score": 72.5,
    "parity_index": 0.725,
    "current_value": 45,
    "target_value": 50,
    "measurement_unit": "%",
    "data_source": "World Economic Forum",
    "collection_date": "2024-01-15",
    "year": 2024,
    "sample_size": 10000,
    "confidence_level": 95,
    "link": "https://example.com/parity-data/gender-parity-tech/"
  }
]
```

### 2. Get Single Parity Data Entry

```bash
GET /wp-json/wp/v2/parity-data/{id}
```

**Example:**
```bash
curl https://example.com/wp-json/wp/v2/parity-data/1
```

### 3. Create New Parity Data

```bash
POST /wp-json/wp/v2/parity-data
Content-Type: application/json
Authorization: Bearer {your-token}
```

**Request Body:**
```json
{
  "title": "Wage Parity Analysis 2024",
  "content": "Comprehensive analysis of wage parity across industries",
  "status": "publish",
  "parity_score": 68.3,
  "parity_index": 0.683,
  "current_value": 82000,
  "target_value": 100000,
  "measurement_unit": "USD",
  "data_source": "Bureau of Labor Statistics",
  "collection_date": "2024-01-01",
  "year": 2024,
  "sample_size": 50000,
  "confidence_level": 99,
  "parity_category": [1, 2],
  "parity_region": [3]
}
```

**Example with curl:**
```bash
curl -X POST https://example.com/wp-json/wp/v2/parity-data \
  -H "Content-Type: application/json" \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -d '{
    "title": "Wage Parity Analysis 2024",
    "status": "publish",
    "parity_score": 68.3,
    "current_value": 82000,
    "target_value": 100000,
    "measurement_unit": "USD",
    "year": 2024
  }'
```

### 4. Update Parity Data

```bash
PUT /wp-json/wp/v2/parity-data/{id}
Content-Type: application/json
Authorization: Bearer {your-token}
```

**Request Body:**
```json
{
  "parity_score": 75.0,
  "parity_index": 0.75,
  "current_value": 90000
}
```

### 5. Delete Parity Data

```bash
DELETE /wp-json/wp/v2/parity-data/{id}
Authorization: Bearer {your-token}
```

## Custom API Endpoints

### 1. Get Overall Statistics

```bash
GET /wp-json/global-parity/v1/statistics
```

**Response:**
```json
{
  "total_entries": 156,
  "average_score": 71.5,
  "highest_score": 95.2,
  "lowest_score": 42.1,
  "regions_covered": 12,
  "categories_count": 8
}
```

**Example:**
```bash
curl https://example.com/wp-json/global-parity/v1/statistics
```

### 2. Get Parity Data by Region

```bash
GET /wp-json/global-parity/v1/by-region/{region-slug}
```

**Example:**
```bash
curl https://example.com/wp-json/global-parity/v1/by-region/north-america
```

**Response:**
```json
{
  "region": "north-america",
  "count": 23,
  "data": [
    {
      "id": 1,
      "title": "Gender Parity in Technology",
      "parity_score": 72.5,
      "parity_index": 0.725,
      "current_value": 45,
      "target_value": 50,
      "measurement_unit": "%",
      "year": 2024,
      "link": "https://example.com/parity-data/gender-parity-tech/"
    }
  ]
}
```

### 3. Compare Multiple Parity Entries

```bash
GET /wp-json/global-parity/v1/compare?ids=1,2,3
```

**Example:**
```bash
curl "https://example.com/wp-json/global-parity/v1/compare?ids=1,2,3"
```

**Response:**
```json
{
  "count": 3,
  "comparison": [
    {
      "id": 1,
      "title": "Gender Parity in Technology",
      "parity_score": 72.5,
      "parity_index": 0.725,
      "current_value": 45,
      "target_value": 50,
      "measurement_unit": "%",
      "year": 2024,
      "regions": ["North America"],
      "categories": ["Gender", "Technology"]
    },
    {
      "id": 2,
      "title": "Wage Parity Analysis",
      "parity_score": 68.3,
      "parity_index": 0.683,
      "current_value": 82000,
      "target_value": 100000,
      "measurement_unit": "USD",
      "year": 2024,
      "regions": ["Europe"],
      "categories": ["Economic"]
    }
  ]
}
```

## Taxonomy Endpoints

### Get All Regions

```bash
GET /wp-json/wp/v2/parity-regions
```

### Get All Categories

```bash
GET /wp-json/wp/v2/parity-categories
```

### Create a New Region

```bash
POST /wp-json/wp/v2/parity-regions
Content-Type: application/json
Authorization: Bearer {your-token}
```

**Request Body:**
```json
{
  "name": "Southeast Asia",
  "slug": "southeast-asia",
  "description": "Southeast Asian region"
}
```

## JavaScript Examples

### Fetch Statistics

```javascript
async function getParityStatistics() {
  const response = await fetch('https://example.com/wp-json/global-parity/v1/statistics');
  const data = await response.json();
  console.log('Average Score:', data.average_score);
  return data;
}
```

### Create New Parity Data

```javascript
async function createParityData(data) {
  const response = await fetch('https://example.com/wp-json/wp/v2/parity-data', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json',
      'Authorization': 'Bearer YOUR_TOKEN'
    },
    body: JSON.stringify({
      title: data.title,
      status: 'publish',
      parity_score: data.score,
      current_value: data.currentValue,
      target_value: data.targetValue,
      measurement_unit: data.unit,
      year: data.year
    })
  });
  
  return await response.json();
}
```

### Fetch and Display Regional Data

```javascript
async function displayRegionalData(region) {
  const response = await fetch(`https://example.com/wp-json/global-parity/v1/by-region/${region}`);
  const data = await response.json();
  
  console.log(`Region: ${data.region}`);
  console.log(`Total entries: ${data.count}`);
  
  data.data.forEach(entry => {
    console.log(`${entry.title}: ${entry.parity_score}`);
  });
}
```

## Python Examples

### Using requests library

```python
import requests

# Get statistics
def get_statistics():
    url = "https://example.com/wp-json/global-parity/v1/statistics"
    response = requests.get(url)
    return response.json()

# Create new parity data
def create_parity_data(token):
    url = "https://example.com/wp-json/wp/v2/parity-data"
    headers = {
        "Content-Type": "application/json",
        "Authorization": f"Bearer {token}"
    }
    data = {
        "title": "Education Parity Study",
        "status": "publish",
        "parity_score": 81.2,
        "current_value": 87,
        "target_value": 100,
        "measurement_unit": "%",
        "year": 2024
    }
    response = requests.post(url, json=data, headers=headers)
    return response.json()

# Compare entries
def compare_entries(ids):
    url = f"https://example.com/wp-json/global-parity/v1/compare?ids={','.join(map(str, ids))}"
    response = requests.get(url)
    return response.json()
```

## Error Handling

The API returns standard HTTP status codes:

- `200` - Success
- `201` - Created
- `400` - Bad Request
- `401` - Unauthorized
- `403` - Forbidden
- `404` - Not Found
- `500` - Internal Server Error

**Error Response Example:**
```json
{
  "code": "rest_invalid_param",
  "message": "Invalid parameter(s): parity_score",
  "data": {
    "status": 400,
    "params": {
      "parity_score": "parity_score must be a number between 0 and 100"
    }
  }
}
```

## Rate Limiting

The WordPress REST API doesn't have built-in rate limiting, but your server may implement it. Check your server configuration for limits.

## Best Practices

1. **Always validate data** before sending to the API
2. **Use pagination** when fetching large datasets
3. **Cache responses** when appropriate
4. **Handle errors gracefully**
5. **Use HTTPS** for all API requests
6. **Keep authentication tokens secure**
7. **Use specific fields** with `_fields` parameter to reduce response size

## Additional Resources

- [WordPress REST API Handbook](https://developer.wordpress.org/rest-api/)
- [Plugin Documentation](./readme.txt)
- [WordPress Authentication](https://developer.wordpress.org/rest-api/using-the-rest-api/authentication/)
