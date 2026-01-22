# Changelog

All notable changes to the Global Parity Engine WordPress plugin will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [1.0.0] - 2024-01-22

### Added
- Initial release of Global Parity Engine WordPress plugin
- Custom post type `parity_data` for managing parity information
- Two custom taxonomies:
  - `parity_category` - Hierarchical taxonomy for categorizing parity data
  - `parity_region` - Hierarchical taxonomy for regional classification
- Comprehensive custom fields:
  - Parity Metrics: score, index, current value, target value, measurement unit
  - Statistical Data: data source, collection date, year, sample size, confidence level
- Full REST API integration:
  - Standard WordPress REST API endpoints for parity data
  - All custom fields exposed via REST API with proper schema
  - Custom REST API endpoints:
    - `/wp-json/global-parity/v1/statistics` - Aggregate statistics
    - `/wp-json/global-parity/v1/by-region/{region}` - Filter by region
    - `/wp-json/global-parity/v1/compare?ids=1,2,3` - Compare multiple entries
- Admin interface:
  - Custom meta boxes for data entry
  - User-friendly form fields with descriptions
  - Proper validation and sanitization
- WordPress coding standards compliance
- Internationalization ready with text domain
- Activation and deactivation hooks
- Comprehensive documentation:
  - Plugin readme.txt
  - API usage examples
  - Code documentation

### Security
- Nonce verification for meta box saves
- Proper capability checks
- Input sanitization and validation
- Secure REST API field callbacks

[1.0.0]: https://github.com/rongabby/global-parity-engine/releases/tag/v1.0.0
