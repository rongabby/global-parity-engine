# Google Earth Integration Plugin

Geographic visualization plugin with Google Earth/Maps integration for displaying parity data on interactive maps.

## Description

This plugin provides geographic visualization capabilities for the Global Parity Engine. It includes:

- Interactive Google Maps/Earth visualization
- Geographic marker placement and management
- Shortcode system for easy embedding
- Integration with Global Parity Data plugin

## Features

- **Interactive Maps**: Google Maps/Earth rendering
- **Geographic Markers**: Place and manage location markers
- **Customizable**: Configurable map styles and marker icons
- **Shortcodes**: Easy embedding with `[parity_map]`
- **Responsive**: Mobile-friendly map interface

## Installation

This plugin is part of the Global Parity Engine suite. See the main [repository README](../../README.md) for installation instructions.

## Usage

### Basic Shortcode

```php
[parity_map]
```

### Advanced Shortcodes

```php
// Map with specific location
[parity_map lat="40.7128" lng="-74.0060" zoom="10"]

// Map with data filtering
[parity_map category="region-1" limit="50"]

// Custom styled map
[parity_map style="dark" height="600px"]
```

### Configuration

Set your Google Maps API key in wp-config.php:

```php
define('GOOGLE_MAPS_API_KEY', 'your-api-key-here');
```

Or use the plugin settings page:
Settings → Google Earth Integration

## Requirements

- Google Maps API key
- Global Parity Data plugin (recommended for full functionality)

## Development

This plugin follows WordPress coding standards and uses PSR-4 autoloading for namespaced classes.

## License

MIT License - see [LICENSE](../../LICENSE) file for details.
