=== Global Parity Engine ===
Contributors: globalparityteam
Tags: parity, analytics, data, rest-api, global
Requires at least: 5.0
Tested up to: 6.4
Stable tag: 1.0.0
Requires PHP: 7.2
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

A comprehensive WordPress plugin for tracking and analyzing global parity data across different metrics and regions.

== Description ==

Global Parity Engine is a powerful WordPress plugin designed to help organizations track, analyze, and visualize global parity data. The plugin provides a robust framework for managing parity metrics across different regions and categories.

= Features =

* **Custom Post Type**: Dedicated post type for parity data management
* **Custom Taxonomies**: Organize data by regions and categories
* **REST API Integration**: Full REST API support with custom endpoints
* **Custom Fields**: Comprehensive meta fields for parity metrics and statistics
* **Admin Interface**: User-friendly meta boxes for data entry
* **API Endpoints**: 
  - `/wp-json/global-parity/v1/statistics` - Get overall statistics
  - `/wp-json/global-parity/v1/by-region/{region}` - Filter by region
  - `/wp-json/global-parity/v1/compare?ids=1,2,3` - Compare multiple entries

= Parity Metrics =

The plugin tracks the following metrics:
* Parity Score (0-100)
* Parity Index
* Current Value
* Target Value
* Measurement Unit

= Statistical Data =

Each parity entry includes:
* Data Source
* Collection Date
* Reference Year
* Sample Size
* Confidence Level

= REST API Fields =

All custom fields are exposed through the WordPress REST API with proper schema validation:

* `parity_score` (number) - Overall parity score
* `parity_index` (number) - Normalized parity index
* `current_value` (number) - Current measured value
* `target_value` (number) - Target value for parity
* `measurement_unit` (string) - Unit of measurement
* `data_source` (string) - Source of the data
* `collection_date` (string, date format) - Data collection date
* `year` (integer) - Reference year
* `sample_size` (integer) - Sample size
* `confidence_level` (number) - Confidence level percentage

== Installation ==

1. Upload the `global-parity-engine` folder to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Navigate to 'Parity Engine' in the admin menu to start adding data
4. Configure regions and categories through the taxonomy menus

== Frequently Asked Questions ==

= How do I access the REST API? =

The plugin registers the custom post type with REST API support. You can access:
* Main endpoint: `/wp-json/wp/v2/parity-data`
* Statistics: `/wp-json/global-parity/v1/statistics`
* By region: `/wp-json/global-parity/v1/by-region/{region-slug}`
* Comparison: `/wp-json/global-parity/v1/compare?ids=1,2,3`

= What are the custom fields available? =

The plugin includes 10 custom fields divided into two categories:
- Parity Metrics: score, index, current value, target value, measurement unit
- Statistical Data: data source, collection date, year, sample size, confidence level

= Can I import data programmatically? =

Yes! Use the WordPress REST API to create and update parity data entries with all custom fields.

= How do I query parity data? =

You can query using standard WordPress query functions or through the REST API. Use taxonomies to filter by region or category.

== REST API Examples ==

= Get all parity data =
```
GET /wp-json/wp/v2/parity-data
```

= Get parity data with custom fields =
```
GET /wp-json/wp/v2/parity-data/123
```

Response includes all custom fields like `parity_score`, `parity_index`, etc.

= Create new parity data =
```
POST /wp-json/wp/v2/parity-data
{
  "title": "Gender Parity in Tech",
  "content": "Analysis of gender parity...",
  "status": "publish",
  "parity_score": 72.5,
  "parity_index": 0.725,
  "current_value": 45,
  "target_value": 50,
  "measurement_unit": "%",
  "year": 2024
}
```

= Get statistics =
```
GET /wp-json/global-parity/v1/statistics
```

Returns aggregate statistics including average score, highest/lowest scores, and counts.

= Filter by region =
```
GET /wp-json/global-parity/v1/by-region/north-america
```

= Compare entries =
```
GET /wp-json/global-parity/v1/compare?ids=1,2,3
```

== Changelog ==

= 1.0.0 =
* Initial release
* Custom post type for parity data
* Two custom taxonomies (regions and categories)
* Comprehensive meta fields for metrics and statistics
* Full REST API integration
* Custom REST API endpoints for statistics and comparison
* Admin meta boxes for data entry
* Activation/deactivation hooks

== Upgrade Notice ==

= 1.0.0 =
Initial release of Global Parity Engine.

== Screenshots ==

1. Parity Data admin interface
2. Parity Metrics meta box
3. Statistical Data meta box
4. REST API response example
5. Regions taxonomy
6. Categories taxonomy

== Technical Details ==

= Post Type =
* Slug: `parity_data`
* REST Base: `parity-data`
* Supports: title, editor, author, thumbnail, excerpt, custom-fields, revisions

= Taxonomies =
* Parity Categories (`parity_category`) - Hierarchical
* Regions (`parity_region`) - Hierarchical

= Custom Meta Fields =
All meta fields are prefixed with underscore and stored with proper data types.

= REST API Schema =
All custom fields include proper schema definitions with type validation and context support.
