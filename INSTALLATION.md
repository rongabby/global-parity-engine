# Installation Guide - Global Parity Engine

## Quick Start

### Installation Steps

1. **Download the Plugin**
   ```bash
   git clone https://github.com/rongabby/global-parity-engine.git
   ```

2. **Install in WordPress**
   - Copy the entire `global-parity-engine` directory to `wp-content/plugins/`
   - Or upload as a ZIP file through WordPress admin

3. **Activate the Plugin**
   - Log in to WordPress admin
   - Navigate to Plugins > Installed Plugins
   - Find "Global Parity Engine" and click "Activate"

4. **Verify Installation**
   - Look for "Parity Engine" in the admin menu
   - Visit `/wp-json/wp/v2/parity-data` to verify REST API is active

## Post-Installation Setup

### 1. Add Regions

Navigate to **Parity Engine > Regions** and add geographic regions:
- North America
- Europe
- Asia Pacific
- Latin America
- Africa
- Middle East

### 2. Add Categories

Navigate to **Parity Engine > Categories** and add parity categories:
- Gender
- Economic
- Education
- Healthcare
- Technology
- Employment

### 3. Create Your First Entry

1. Go to **Parity Engine > Add New**
2. Enter a title (e.g., "Gender Parity in Tech 2024")
3. Add content/description
4. Fill in the Parity Metrics:
   - Parity Score: 72.5
   - Parity Index: 0.725
   - Current Value: 45
   - Target Value: 50
   - Measurement Unit: %
5. Fill in Statistical Data:
   - Data Source: World Economic Forum
   - Collection Date: 2024-01-15
   - Year: 2024
   - Sample Size: 10000
   - Confidence Level: 95
6. Select Region(s) and Category/ies
7. Click "Publish"

## Testing the API

### Using cURL

```bash
# Get all parity data
curl https://your-site.com/wp-json/wp/v2/parity-data

# Get statistics
curl https://your-site.com/wp-json/global-parity/v1/statistics

# Get data by region
curl https://your-site.com/wp-json/global-parity/v1/by-region/north-america
```

### Using Browser

Visit these URLs in your browser:
- `https://your-site.com/wp-json/wp/v2/parity-data`
- `https://your-site.com/wp-json/global-parity/v1/statistics`

### Using JavaScript

```javascript
fetch('https://your-site.com/wp-json/global-parity/v1/statistics')
  .then(response => response.json())
  .then(data => console.log(data));
```

## Creating Data via API

### Prerequisites

You need authentication. The easiest way for testing:

1. Install "Application Passwords" plugin (or use WordPress 5.6+)
2. Go to Users > Your Profile
3. Scroll to "Application Passwords"
4. Create a new application password
5. Use it for API authentication

### Example: Create Entry via API

```bash
curl -X POST https://your-site.com/wp-json/wp/v2/parity-data \
  -H "Content-Type: application/json" \
  -u "username:application_password" \
  -d '{
    "title": "Wage Parity Study",
    "status": "publish",
    "parity_score": 68.5,
    "current_value": 85000,
    "target_value": 100000,
    "measurement_unit": "USD",
    "year": 2024
  }'
```

## Troubleshooting

### Plugin Not Showing in Menu

- Ensure plugin is activated
- Check if your user has proper permissions
- Try deactivating and reactivating

### REST API Not Working

- Check permalink settings (Settings > Permalinks)
- Ensure pretty permalinks are enabled
- Try resaving permalinks

### Custom Fields Not Appearing

- Deactivate and reactivate the plugin
- This triggers the activation hook which registers everything

### 404 Errors on API Endpoints

- Go to Settings > Permalinks
- Click "Save Changes" (even without changing anything)
- This flushes rewrite rules

## Advanced Configuration

### Custom Capabilities

By default, the plugin uses standard WordPress `post` capabilities. To customize:

```php
// In your theme's functions.php
add_filter('register_post_type_args', function($args, $post_type) {
    if ($post_type === 'parity_data') {
        $args['capability_type'] = array('parity_data', 'parity_data_items');
        $args['map_meta_cap'] = true;
    }
    return $args;
}, 10, 2);
```

### Modify REST API Response

```php
// Add custom field to REST response
add_action('rest_api_init', function() {
    register_rest_field('parity_data', 'custom_calculation', array(
        'get_callback' => function($object) {
            $score = get_post_meta($object['id'], '_parity_score', true);
            return $score * 2; // Your custom calculation
        },
        'schema' => array(
            'type' => 'number',
            'description' => 'Custom calculated value',
            'context' => array('view', 'edit')
        )
    ));
});
```

## Support

- [API Documentation](./API-USAGE.md)
- [GitHub Issues](https://github.com/rongabby/global-parity-engine/issues)
- [WordPress Plugin Documentation](./readme.txt)

## Next Steps

1. Populate your database with parity data
2. Create custom frontend views using the REST API
3. Build dashboards and visualizations
4. Export/import data as needed
5. Integrate with external data sources

## Security Best Practices

1. Use HTTPS for all API requests
2. Keep WordPress and the plugin updated
3. Use strong application passwords
4. Limit API access to necessary users only
5. Monitor API usage and logs
6. Use proper authentication for write operations

## Performance Tips

1. Use caching for API responses
2. Implement pagination for large datasets
3. Use the `_fields` parameter to limit response size
4. Consider CDN for static assets
5. Monitor database query performance
