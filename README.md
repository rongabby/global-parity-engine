# Global Parity Engine

A WordPress application that integrates with Google Earth to visualize geographic markers for global parity analysis.

## Overview

This project provides a WordPress plugin that calculates and displays specific geographic coordinates based on international boundaries, water rights, and airspace considerations.

## Features

- **WordPress Plugin**: Fully integrated WordPress plugin for easy deployment
- **Google Earth Integration**: Export markers as KML files for Google Earth
- **Interactive Maps**: Display markers on Google Maps with detailed information
- **Geographic Calculations**: Automated calculation of coordinates based on specifications
- **Admin Interface**: WordPress admin panel for managing and exporting markers

## Quick Start

1. **Install WordPress**: Set up a WordPress installation on your server
2. **Install Plugin**: Copy the `wp-content/plugins/google-earth-integration` folder to your WordPress plugins directory
3. **Activate**: Activate the plugin from the WordPress admin panel
4. **Configure**: Add your Google Maps API key in the plugin file
5. **Use**: Add `[google_earth_map]` shortcode to any page to display the map

## Geographic Markers

The system calculates the following markers:

1. **North Pole**: Starting point at 90° N, 0° E
2. **West of Japan**: 130° E (respecting Japan's water rights and airspace)
3. **Marker 1**: -110° W (120° east from Japan west, rounded)
4. **Marker 2**: 10° E (another 120° east)
5. **Japan (Tokyo)**: Final marker at 139.65° E

These coordinates account for cultural and political factors, including international boundaries and territorial rights.

## Documentation

See the [Plugin README](wp-content/plugins/google-earth-integration/README.md) for detailed installation and usage instructions.

## Google Account

Project Google ID: **rongabby@gmail.com**

## License

GPL v2 or later

## Is This Doable?

**Yes!** This project is fully implemented and includes:
- ✅ WordPress plugin structure
- ✅ Google Earth KML file generation
- ✅ Interactive map display with Google Maps API
- ✅ Automated geographic calculations
- ✅ Consideration for water rights and airspace
- ✅ Cultural and political factor adjustments
- ✅ Admin interface for management and export
- ✅ Shortcode support for easy embedding

The plugin is ready to use with a standard WordPress installation.
