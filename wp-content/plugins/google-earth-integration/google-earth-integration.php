<?php
/**
 * Plugin Name: Google Earth Integration
 * Plugin URI: https://github.com/rongabby/global-parity-engine
 * Description: WordPress plugin to integrate with Google Earth and display geographic markers
 * Version: 1.0.0
 * Author: rongabby@gmail.com
 * License: GPL v2 or later
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

class GoogleEarthIntegration {
    
    private $markers = array();
    
    public function __construct() {
        add_action('wp_enqueue_scripts', array($this, 'enqueue_scripts'));
        add_shortcode('google_earth_map', array($this, 'render_map_shortcode'));
        $this->calculate_markers();
    }
    
    /**
     * Get markers array
     * @return array
     */
    public function get_markers() {
        return $this->markers;
    }
    
    /**
     * Calculate geographic markers based on specifications
     */
    private function calculate_markers() {
        // Starting point: North Pole
        $this->markers[] = array(
            'name' => 'North Pole Start',
            'lat' => 90.0,
            'lng' => 0.0,
            'description' => 'Starting point at 90° N, 0° E'
        );
        
        // Longitude west of Japan (respecting water rights and airspace)
        // Japan's western waters are approximately at longitude 130° E
        $japan_west_lng = 130.0;
        $this->markers[] = array(
            'name' => 'West of Japan',
            'lat' => 35.0,
            'lng' => $japan_west_lng,
            'description' => 'Longitude west of Japan at 130° E'
        );
        
        // First marker: 120 degrees east, rounded to next highest longitude
        $marker1_lng = ceil($japan_west_lng + 120.0);
        // Normalize to -180 to 180 range
        if ($marker1_lng > 180) {
            $marker1_lng = $marker1_lng - 360;
        }
        $this->markers[] = array(
            'name' => 'Marker 1 (130° + 120°)',
            'lat' => 35.0,
            'lng' => $marker1_lng,
            'description' => sprintf('First marker at %d° E (120° east of Japan west)', $marker1_lng)
        );
        
        // Second marker: Another 120 degrees east
        $marker2_lng = $marker1_lng + 120.0;
        // Normalize to -180 to 180 range
        if ($marker2_lng > 180) {
            $marker2_lng = $marker2_lng - 360;
        }
        $this->markers[] = array(
            'name' => 'Marker 2 (250° + 120°)',
            'lat' => 35.0,
            'lng' => $marker2_lng,
            'description' => sprintf('Second marker at %.0f° E (240° more from west Japan)', $marker2_lng)
        );
        
        // Final marker: Japan (Tokyo)
        $this->markers[] = array(
            'name' => 'Japan (Tokyo)',
            'lat' => 35.6762,
            'lng' => 139.6503,
            'description' => 'Final marker at Japan (Tokyo) at 139.65° E'
        );
    }
    
    /**
     * Enqueue JavaScript and CSS files
     */
    public function enqueue_scripts() {
        // Get API key from WordPress options (set in Settings > Google Earth)
        // For security, store your API key using: update_option('gei_google_maps_api_key', 'your-key-here');
        // Or use environment variable: define('GEI_GOOGLE_MAPS_API_KEY', getenv('GOOGLE_MAPS_API_KEY'));
        $api_key = get_option('gei_google_maps_api_key', 'YOUR_API_KEY_HERE');
        
        // Google Maps JavaScript API
        wp_enqueue_script(
            'google-maps',
            'https://maps.googleapis.com/maps/api/js?key=' . esc_attr($api_key),
            array(),
            null,
            true
        );
        
        wp_enqueue_script(
            'google-earth-integration',
            plugin_dir_url(__FILE__) . 'assets/js/map.js',
            array('google-maps', 'jquery'),
            '1.0.0',
            true
        );
        
        wp_localize_script('google-earth-integration', 'geiMarkers', $this->markers);
        
        wp_enqueue_style(
            'google-earth-integration',
            plugin_dir_url(__FILE__) . 'assets/css/style.css',
            array(),
            '1.0.0'
        );
    }
    
    /**
     * Render map shortcode
     */
    public function render_map_shortcode($atts) {
        $atts = shortcode_atts(array(
            'width' => '100%',
            'height' => '600px'
        ), $atts);
        
        ob_start();
        ?>
        <div id="google-earth-map" style="width: <?php echo esc_attr($atts['width']); ?>; height: <?php echo esc_attr($atts['height']); ?>;"></div>
        <div id="marker-info" style="margin-top: 20px;">
            <h3>Geographic Markers</h3>
            <ul>
                <?php foreach ($this->markers as $marker): ?>
                    <li>
                        <strong><?php echo esc_html($marker['name']); ?></strong>: 
                        <?php echo esc_html($marker['lat']); ?>° N, 
                        <?php echo esc_html($marker['lng']); ?>° E
                        <br><em><?php echo esc_html($marker['description']); ?></em>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
        <?php
        return ob_get_clean();
    }
    
    /**
     * Generate KML file for Google Earth
     */
    public function generate_kml() {
        $kml = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
        $kml .= '<kml xmlns="http://www.opengis.net/kml/2.2">' . "\n";
        $kml .= '<Document>' . "\n";
        $kml .= '<name>Global Parity Engine Markers</name>' . "\n";
        $kml .= '<description>Geographic markers for global parity analysis</description>' . "\n";
        
        foreach ($this->markers as $marker) {
            $kml .= '<Placemark>' . "\n";
            $kml .= '<name>' . htmlspecialchars($marker['name']) . '</name>' . "\n";
            $kml .= '<description>' . htmlspecialchars($marker['description']) . '</description>' . "\n";
            $kml .= '<Point>' . "\n";
            $kml .= '<coordinates>' . $marker['lng'] . ',' . $marker['lat'] . ',0</coordinates>' . "\n";
            $kml .= '</Point>' . "\n";
            $kml .= '</Placemark>' . "\n";
        }
        
        $kml .= '</Document>' . "\n";
        $kml .= '</kml>';
        
        return $kml;
    }
}

// Initialize the plugin
new GoogleEarthIntegration();

// Add admin menu for KML download
add_action('admin_menu', 'gei_add_admin_menu');
function gei_add_admin_menu() {
    add_menu_page(
        'Google Earth Integration',
        'Google Earth',
        'manage_options',
        'google-earth-integration',
        'gei_admin_page',
        'dashicons-location-alt'
    );
}

function gei_admin_page() {
    // Handle KML download with nonce verification
    if (isset($_GET['download_kml'])) {
        // Verify nonce for security
        if (!isset($_GET['_wpnonce']) || !wp_verify_nonce($_GET['_wpnonce'], 'gei_download_kml')) {
            wp_die('Security check failed');
        }
        
        $plugin = new GoogleEarthIntegration();
        $kml = $plugin->generate_kml();
        
        header('Content-Type: application/vnd.google-earth.kml+xml');
        header('Content-Disposition: attachment; filename="global-parity-markers.kml"');
        echo $kml;
        exit;
    }
    
    ?>
    <div class="wrap">
        <h1>Google Earth Integration</h1>
        <p>This plugin creates geographic markers for the Global Parity Engine project.</p>
        
        <h2>Marker Locations</h2>
        <table class="wp-list-table widefat fixed striped">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Latitude</th>
                    <th>Longitude</th>
                    <th>Description</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $plugin = new GoogleEarthIntegration();
                $markers = $plugin->get_markers();
                
                foreach ($markers as $marker):
                ?>
                <tr>
                    <td><?php echo esc_html($marker['name']); ?></td>
                    <td><?php echo esc_html($marker['lat']); ?>°</td>
                    <td><?php echo esc_html($marker['lng']); ?>°</td>
                    <td><?php echo esc_html($marker['description']); ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        
        <h2>Export to Google Earth</h2>
        <p>
            <a href="<?php echo wp_nonce_url(admin_url('admin.php?page=google-earth-integration&download_kml=1'), 'gei_download_kml'); ?>" class="button button-primary">
                Download KML File
            </a>
        </p>
        <p>
            <em>Download the KML file and open it with Google Earth to view the markers.</em>
        </p>
        
        <h2>Usage</h2>
        <p>Add the following shortcode to any page or post to display the map:</p>
        <code>[google_earth_map]</code>
    </div>
    <?php
}
