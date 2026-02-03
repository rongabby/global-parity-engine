# Google Earth Integration Plugin

A WordPress plugin that integrates with Google Earth to display geographic markers for the Global Parity Engine project.

## Overview

This plugin calculates and displays geographic markers based on specific coordinates:
- **Starting Point**: North Pole (90° N, 0° E)
- **West of Japan**: 130° E (respecting Japan's water rights and airspace)
- **Marker 1**: 250° E (130° + 120°, normalized to -110° W)
- **Marker 2**: 370° E (250° + 120°, normalized to 10° E)
- **Final Marker**: Japan/Tokyo (35.6762° N, 139.6503° E)

## Installation

1. Copy the `google-earth-integration` folder to your WordPress `wp-content/plugins/` directory
2. Log in to your WordPress admin panel
3. Navigate to Plugins → Installed Plugins
4. Find "Google Earth Integration" and click "Activate"

## Configuration

### Google Maps API Key

To use the interactive map feature, you'll need a Google Maps API key:

1. Go to the [Google Cloud Console](https://console.cloud.google.com/)
2. Create a new project or select an existing one
3. Enable the Maps JavaScript API
4. Create credentials (API key)
5. Edit `google-earth-integration.php` and replace `YOUR_API_KEY_HERE` with your actual API key

## Usage

### Display Map on a Page

Add the following shortcode to any WordPress page or post:

```
[google_earth_map]
```

Optional parameters:
- `width`: Map width (default: 100%)
- `height`: Map height (default: 600px)

Example:
```
[google_earth_map width="800px" height="500px"]
```

### Export to Google Earth

1. Navigate to Google Earth in the WordPress admin menu
2. Click "Download KML File"
3. Open the downloaded file with Google Earth desktop or web application
4. View the markers in Google Earth

### Admin Interface

Access the admin interface at: WordPress Admin → Google Earth

Features:
- View all marker locations in a table
- Download KML file for Google Earth
- See usage instructions

## Geographic Calculations

The plugin calculates markers based on the following specifications:

1. **Start**: North Pole (90° N, 0° E)
2. **West of Japan**: Longitude 130° E, representing the western waters of Japan, respecting its territorial waters and airspace
3. **First Marker**: 120 degrees east of Japan's western point, rounded up to 250° E (or -110° W when normalized)
4. **Second Marker**: Another 120 degrees east, at 370° E (or 10° E when normalized)
5. **Final Marker**: Japan (Tokyo) at approximately 139.65° E

These calculations account for:
- International date line wrapping (normalizing longitudes to -180° to 180° range)
- Japan's water rights and airspace boundaries
- Cultural and political factors in coordinate selection

## Technical Details

### Files Structure

```
google-earth-integration/
├── google-earth-integration.php (Main plugin file)
├── assets/
│   ├── js/
│   │   └── map.js (Map initialization and marker display)
│   └── css/
│       └── style.css (Plugin styles)
└── README.md (This file)
```

### Features

- Interactive Google Maps display with markers
- Polyline connecting all markers
- Info windows with detailed marker information
- KML file generation for Google Earth compatibility
- WordPress admin interface
- Responsive design
- Shortcode support for easy embedding

## Google Earth Account

The project is associated with Google ID: **rongabby@gmail.com**

## License

GPL v2 or later

## Version

1.0.0

## Author

rongabby@gmail.com
