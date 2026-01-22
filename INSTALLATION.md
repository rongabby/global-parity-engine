# Installation Guide

## WordPress Installation

### Prerequisites
- WordPress 5.0 or higher
- PHP 7.4 or higher
- Google Maps API key (for interactive maps)
- Google Earth (desktop or web) for viewing KML files

### Step-by-Step Installation

#### 1. Install WordPress

If you don't have WordPress installed yet:

```bash
# Download WordPress
wget https://wordpress.org/latest.tar.gz

# Extract
tar -xzf latest.tar.gz

# Move to web directory
sudo mv wordpress /var/www/html/

# Set permissions
sudo chown -R www-data:www-data /var/www/html/wordpress
sudo chmod -R 755 /var/www/html/wordpress
```

Complete WordPress installation through web browser by navigating to your domain.

#### 2. Install the Plugin

**Method 1: Manual Installation**

```bash
# Copy plugin to WordPress plugins directory
cp -r wp-content/plugins/google-earth-integration /var/www/html/wordpress/wp-content/plugins/

# Set proper permissions
sudo chown -R www-data:www-data /var/www/html/wordpress/wp-content/plugins/google-earth-integration
```

**Method 2: Upload via WordPress Admin**

1. Compress the plugin folder: `zip -r google-earth-integration.zip wp-content/plugins/google-earth-integration/`
2. Log in to WordPress admin panel
3. Navigate to Plugins → Add New → Upload Plugin
4. Select the zip file and click "Install Now"
5. Click "Activate Plugin"

#### 3. Configure Google Maps API

1. Visit [Google Cloud Console](https://console.cloud.google.com/)
2. Create a new project or select existing
3. Enable "Maps JavaScript API"
4. Go to Credentials → Create Credentials → API Key
5. Copy your API key
6. Edit file: `wp-content/plugins/google-earth-integration/google-earth-integration.php`
7. Replace `YOUR_API_KEY_HERE` with your actual API key (around line 64)

```php
wp_enqueue_script(
    'google-maps',
    'https://maps.googleapis.com/maps/api/js?key=YOUR_ACTUAL_API_KEY',
    array(),
    null,
    true
);
```

#### 4. Activate the Plugin

1. Log in to WordPress admin panel
2. Navigate to Plugins → Installed Plugins
3. Find "Google Earth Integration"
4. Click "Activate"

#### 5. Use the Plugin

**Add to a Page:**

1. Create or edit a WordPress page
2. Add the shortcode: `[google_earth_map]`
3. Publish the page
4. View the page to see the interactive map

**Export to Google Earth:**

1. Navigate to WordPress Admin → Google Earth
2. Click "Download KML File"
3. Open the file with Google Earth

## Standalone Demo

If you want to test without WordPress:

1. Open `demo.html` in a web browser
2. To see the interactive map, edit `demo.html` and add your Google Maps API key
3. The KML download works without an API key

## Troubleshooting

### Map doesn't display
- Verify your Google Maps API key is valid
- Check browser console for JavaScript errors
- Ensure the API key has Maps JavaScript API enabled

### Plugin not found after installation
- Check file permissions: `sudo chmod -R 755 wp-content/plugins/google-earth-integration/`
- Verify plugin files are in correct directory
- Check WordPress compatibility (WordPress 5.0+)

### KML file doesn't open in Google Earth
- Ensure Google Earth is installed
- Verify file extension is `.kml`
- Try opening file with: File → Open in Google Earth

## Configuration Options

### Customize Map Display

Edit shortcode parameters:

```
[google_earth_map width="100%" height="800px"]
```

### Modify Marker Coordinates

Edit `google-earth-integration.php`, function `calculate_markers()` to adjust coordinates.

### Change Map Style

Edit `assets/css/style.css` to customize appearance.

## Support

For issues or questions:
- Check plugin README: `wp-content/plugins/google-earth-integration/README.md`
- Review main README: `README.md`
- Contact: rongabby@gmail.com

## Security Notes

- Keep WordPress and plugins updated
- Protect your Google Maps API key (restrict by domain)
- Use HTTPS for production sites
- Regular backups recommended
