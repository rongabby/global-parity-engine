# Global Parity Data Plugin

Core data management plugin for tracking global parity data with REST API support.

## Description

This plugin provides the core data management functionality for the Global Parity Engine. It includes:

- Custom post type for parity data entries
- REST API endpoints for data management
- Admin interface for data entry
- Data validation and sanitization

## Features

- **Custom Post Type**: `parity_data` post type with custom fields
- **REST API**: Full CRUD operations via WordPress REST API
- **Admin Interface**: User-friendly interface for data management
- **Data Validation**: Input validation and sanitization
- **Meta Fields**: Support for location, values, and categories

## Installation

This plugin is part of the Global Parity Engine suite. See the main [repository README](../../README.md) for installation instructions.

## Usage

### Creating Parity Data

```php
$post_id = wp_insert_post(array(
    'post_type' => 'parity_data',
    'post_title' => 'Data Entry Title',
    'post_content' => 'Description',
    'post_status' => 'publish',
));

// Add metadata
update_post_meta($post_id, 'latitude', '40.7128');
update_post_meta($post_id, 'longitude', '-74.0060');
update_post_meta($post_id, 'parity_value', '85.5');
```

### REST API

```bash
# List all parity data
curl https://yoursite.com/wp-json/wp/v2/parity_data

# Create new entry
curl -X POST https://yoursite.com/wp-json/wp/v2/parity_data \
  -H "Content-Type: application/json" \
  -d '{"title": "New Entry", "meta": {"latitude": "40.7128"}}'
```

## Development

This plugin follows WordPress coding standards and uses PSR-4 autoloading for namespaced classes.

## License

MIT License - see [LICENSE](../../LICENSE) file for details.
