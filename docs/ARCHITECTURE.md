# Architecture Documentation

## Overview

The Global Parity Engine is built on a modular, plugin-based architecture that separates concerns between data management and visualization. This document outlines the system architecture, design decisions, and technical implementation details.

## System Architecture

### High-Level Architecture

```
┌─────────────────────────────────────────────────────────────┐
│                     WordPress Core                           │
│                   (5.8+ Required)                            │
└─────────────────────────────────────────────────────────────┘
                            │
                            ├─────────────────────┐
                            │                     │
┌───────────────────────────▼───┐   ┌────────────▼────────────┐
│  Global Parity Data Plugin    │   │ Google Earth Integration│
│  (Core Data Management)       │◄─►│    Plugin (Visualization)│
│                               │   │                          │
│  - Custom Post Type           │   │  - Map Rendering         │
│  - REST API                   │   │  - Markers               │
│  - Admin Interface            │   │  - Shortcodes            │
│  - Data Validation            │   │  - User Interface        │
└───────────────────────────────┘   └──────────────────────────┘
                            │
                            ▼
┌─────────────────────────────────────────────────────────────┐
│                     Database Layer                           │
│   (MySQL 5.7+ / MariaDB 10.2+)                              │
└─────────────────────────────────────────────────────────────┘
```

## Plugin Structure

### 1. Global Parity Data Plugin

**Purpose**: Core data management, storage, and API layer

#### Directory Structure

```
plugins/global-parity-data/
├── global-parity-data.php     # Main plugin file
├── src/                        # Source files (PSR-4)
│   ├── PostType/              # Custom post type definitions
│   ├── RestAPI/               # REST API endpoints
│   ├── Admin/                 # Admin interface
│   └── Validation/            # Data validation
├── includes/                   # WordPress-specific includes
│   ├── class-activator.php    # Plugin activation
│   ├── class-deactivator.php  # Plugin deactivation
│   └── functions.php          # Utility functions
├── admin/                      # Admin UI
│   ├── css/
│   ├── js/
│   └── partials/
└── public/                     # Public-facing functionality
    ├── css/
    └── js/
```

#### Key Components

**Custom Post Type**
- Post Type: `parity_data`
- Supports: title, editor, custom-fields, thumbnail
- Taxonomies: categories, tags
- Capabilities: Follows WordPress capability system

**REST API Endpoints**
```
/wp-json/wp/v2/parity_data
/wp-json/parity/v1/summary
/wp-json/parity/v1/statistics
```

**Data Model**
```php
parity_data {
    ID: int
    post_title: string
    post_content: text
    post_status: string
    post_type: 'parity_data'
    meta: {
        latitude: float
        longitude: float
        parity_value: float
        data_source: string
        collection_date: datetime
        region: string
        category: string
    }
}
```

### 2. Google Earth Integration Plugin

**Purpose**: Geographic visualization and map rendering

#### Directory Structure

```
plugins/google-earth-integration/
├── google-earth-integration.php  # Main plugin file
├── src/                           # Source files (PSR-4)
│   ├── Maps/                     # Map rendering logic
│   ├── Markers/                  # Marker management
│   ├── Shortcodes/               # Shortcode handlers
│   └── Settings/                 # Plugin settings
├── includes/                      # WordPress-specific includes
├── assets/                        # Frontend assets
│   ├── css/
│   │   └── maps.css
│   ├── js/
│   │   └── google-maps.js
│   └── images/
│       └── markers/
└── templates/                     # Map templates
    └── map-container.php
```

#### Key Components

**Shortcode System**
```php
[parity_map]
[parity_map lat="40.7128" lng="-74.0060" zoom="10"]
[parity_map category="region-1" limit="50"]
```

**Map Configuration**
- Default center: [0, 0]
- Default zoom: 3
- Marker clustering: Enabled for 50+ markers
- Map styles: Standard, Satellite, Terrain, Hybrid

## Database Schema

### WordPress Tables Used

#### wp_posts
Stores parity data entries as custom post type

```sql
wp_posts
├── ID (Primary Key)
├── post_author
├── post_date
├── post_content
├── post_title
├── post_status
├── post_type = 'parity_data'
└── ...
```

#### wp_postmeta
Stores custom field data

```sql
wp_postmeta
├── meta_id (Primary Key)
├── post_id (Foreign Key → wp_posts.ID)
├── meta_key (indexed)
│   ├── 'latitude'
│   ├── 'longitude'
│   ├── 'parity_value'
│   ├── 'data_source'
│   ├── 'collection_date'
│   ├── 'region'
│   └── 'category'
└── meta_value
```

### Indexes

```sql
-- For efficient location queries
CREATE INDEX idx_latitude ON wp_postmeta(meta_key, meta_value) 
  WHERE meta_key = 'latitude';
CREATE INDEX idx_longitude ON wp_postmeta(meta_key, meta_value) 
  WHERE meta_key = 'longitude';

-- For category filtering
CREATE INDEX idx_category ON wp_postmeta(meta_key, meta_value) 
  WHERE meta_key = 'category';
```

## API Design

### REST API Architecture

#### Authentication
- WordPress native authentication (cookies, application passwords)
- JWT tokens (optional, requires additional plugin)
- OAuth 2.0 (optional, requires additional plugin)

#### Endpoints

**List Parity Data**
```
GET /wp-json/wp/v2/parity_data
Query Parameters:
  - per_page: int (default: 10, max: 100)
  - page: int (default: 1)
  - category: int
  - search: string
  - latitude: float
  - longitude: float
  - radius: float (km)
```

**Get Single Entry**
```
GET /wp-json/wp/v2/parity_data/{id}
```

**Create Entry**
```
POST /wp-json/wp/v2/parity_data
Body: {
  title: string (required)
  content: string
  meta: {
    latitude: float
    longitude: float
    parity_value: float
  }
}
```

**Update Entry**
```
PUT /wp-json/wp/v2/parity_data/{id}
```

**Delete Entry**
```
DELETE /wp-json/wp/v2/parity_data/{id}
```

#### Response Format

```json
{
  "id": 123,
  "title": {
    "rendered": "Data Entry Title"
  },
  "content": {
    "rendered": "<p>Description</p>"
  },
  "meta": {
    "latitude": 40.7128,
    "longitude": -74.0060,
    "parity_value": 100
  },
  "_links": {
    "self": [{"href": "..."}]
  }
}
```

## Security Considerations

### Input Validation
- All user input sanitized using WordPress sanitization functions
- Custom field validation before database storage
- Type checking for numeric values (latitude, longitude)

### Output Escaping
- All output escaped using WordPress escaping functions
- HTML entities encoded
- JavaScript data properly JSON-encoded

### Authentication & Authorization
- WordPress capability checks on all admin operations
- Nonce verification for forms
- REST API permission callbacks
- Role-based access control

### Data Protection
- Prepared statements for all database queries
- Environment variables for sensitive configuration
- No API keys in code or version control
- Secure communication (HTTPS recommended)

### Security Hardening
```php
// Disable file editing in admin
define('DISALLOW_FILE_EDIT', true);

// Force SSL for admin
define('FORCE_SSL_ADMIN', true);

// Limit post revisions
define('WP_POST_REVISIONS', 5);
```

## Performance Optimization

### Caching Strategy

**Object Caching**
```php
// Cache API responses
$cache_key = 'parity_data_' . $post_id;
$data = wp_cache_get($cache_key);
if (false === $data) {
    $data = get_parity_data($post_id);
    wp_cache_set($cache_key, $data, '', 3600);
}
```

**Transient API**
```php
// Cache expensive queries
$transient_key = 'parity_summary_' . md5(serialize($args));
$summary = get_transient($transient_key);
if (false === $summary) {
    $summary = calculate_parity_summary($args);
    set_transient($transient_key, $summary, HOUR_IN_SECONDS);
}
```

### Database Optimization
- Indexed meta queries for location searches
- Limit query results with pagination
- Use WP_Query efficiently with specific parameters
- Avoid expensive meta queries when possible

### Asset Optimization
- Minified CSS and JavaScript files
- Conditional loading (only load on pages that need it)
- Google Maps loaded asynchronously
- Image optimization for map markers

## Integration Points

### Plugin Communication

**Via WordPress Hooks**
```php
// Global Parity Data fires action when data is saved
do_action('parity_data_saved', $post_id, $data);

// Google Earth Integration listens
add_action('parity_data_saved', 'update_map_markers', 10, 2);
```

**Via REST API**
```javascript
// Fetch data via API
fetch('/wp-json/wp/v2/parity_data')
  .then(response => response.json())
  .then(data => updateMapMarkers(data));
```

### Third-Party Integrations

**Google Maps API**
- Maps JavaScript API for rendering
- Geocoding API for address → coordinates
- Places API for location search

**Potential Future Integrations**
- Elasticsearch for advanced search
- Redis for caching
- CDN for asset delivery
- Analytics platforms

## Extensibility

### Hooks and Filters

**Actions**
```php
// Before saving data
do_action('parity_data_before_save', $post_id, $data);

// After saving data
do_action('parity_data_after_save', $post_id, $data);

// Map initialization
do_action('google_earth_map_init', $map_id, $config);
```

**Filters**
```php
// Modify data before display
$data = apply_filters('parity_data_display', $data, $post_id);

// Customize map options
$options = apply_filters('google_earth_map_options', $options);

// Modify API response
$response = apply_filters('parity_api_response', $response, $request);
```

### Custom Implementations

**Adding Custom Fields**
```php
add_filter('parity_data_meta_fields', function($fields) {
    $fields['custom_field'] = array(
        'type' => 'text',
        'label' => 'Custom Field',
        'sanitize' => 'sanitize_text_field'
    );
    return $fields;
});
```

**Custom Map Markers**
```php
add_filter('google_earth_marker_icon', function($icon, $post_id) {
    // Return custom icon URL based on post data
    return get_custom_icon_url($post_id);
}, 10, 2);
```

## Testing Strategy

### Unit Tests
- Test individual functions and methods
- Mock WordPress functions
- Test data validation logic

### Integration Tests
- Test plugin interactions
- Test WordPress hooks/filters
- Test database operations

### API Tests
- Test REST endpoints
- Verify authentication
- Check response formats

### Browser Tests
- Test map rendering
- Verify marker interactions
- Check responsive design

## Deployment Considerations

### Requirements
- PHP 7.4+ with extensions: mysqli, json, mbstring
- MySQL 5.7+ or MariaDB 10.2+
- WordPress 5.8+
- Google Maps API key

### Installation Steps
1. Upload plugins to WordPress
2. Activate plugins
3. Configure API keys
4. Set up database indexes
5. Configure caching (if available)

### Monitoring
- WordPress debug log for errors
- API response times
- Database query performance
- User activity logs

## Future Architecture Considerations

### Scalability
- Implement full object caching (Redis/Memcached)
- Database replication for read-heavy loads
- CDN for static assets
- Queue system for async operations

### Microservices
- Separate data service from WordPress
- Independent map service
- API gateway pattern

### Progressive Enhancement
- Offline functionality with Service Workers
- Progressive Web App (PWA) support
- Real-time updates with WebSockets

---

This architecture provides a solid foundation for the Global Parity Engine while maintaining flexibility for future enhancements and scaling needs.
