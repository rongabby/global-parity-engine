# Global Parity Engine - WordPress Plugin

A comprehensive WordPress plugin for tracking and analyzing global parity data across different metrics and regions.

## Overview

The Global Parity Engine is a powerful WordPress plugin designed to help organizations track, analyze, and visualize global parity data. It provides a robust framework for managing parity metrics with full REST API support.

## Features

- **Custom Post Type**: Dedicated `parity_data` post type for managing parity information
- **Custom Taxonomies**: 
  - Parity Categories (hierarchical)
  - Regions (hierarchical)
- **Rich Custom Fields**:
  - Parity Metrics (score, index, current/target values, units)
  - Statistical Data (source, dates, sample size, confidence levels)
- **Full REST API Integration**:
  - Standard WordPress REST API endpoints
  - Custom endpoints for statistics, regional filtering, and comparisons
  - All custom fields exposed via API with proper schema
- **Admin Interface**: User-friendly meta boxes for data entry
- **WordPress Standards**: Follows WordPress coding standards and best practices

## Installation

1. Clone or download this repository
2. Copy the entire directory to your WordPress `wp-content/plugins/` directory
3. Activate the plugin through the WordPress admin panel
4. Start adding parity data through the "Parity Engine" menu

## REST API Endpoints

### Standard Endpoints
- `GET /wp-json/wp/v2/parity-data` - List all parity data
- `POST /wp-json/wp/v2/parity-data` - Create new entry
- `GET /wp-json/wp/v2/parity-data/{id}` - Get single entry
- `PUT /wp-json/wp/v2/parity-data/{id}` - Update entry
- `DELETE /wp-json/wp/v2/parity-data/{id}` - Delete entry

### Custom Endpoints
- `GET /wp-json/global-parity/v1/statistics` - Get overall statistics
- `GET /wp-json/global-parity/v1/by-region/{region}` - Filter by region
- `GET /wp-json/global-parity/v1/compare?ids=1,2,3` - Compare entries

## Custom Fields

All entries support the following custom fields accessible via REST API:

**Parity Metrics:**
- `parity_score` (number, 0-100)
- `parity_index` (number)
- `current_value` (number)
- `target_value` (number)
- `measurement_unit` (string)

**Statistical Data:**
- `data_source` (string)
- `collection_date` (date)
- `year` (integer)
- `sample_size` (integer)
- `confidence_level` (number)

## Documentation

- [API Usage Examples](./API-USAGE.md) - Comprehensive API documentation with examples
- [Changelog](./CHANGELOG.md) - Version history and changes
- [readme.txt](./readme.txt) - WordPress plugin readme

## Requirements

- WordPress 5.0 or higher
- PHP 7.2 or higher

## License

GPL v2 or later - https://www.gnu.org/licenses/gpl-2.0.html

## Support

For issues and questions, please open an issue on GitHub.
