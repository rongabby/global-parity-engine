<?php
/**
 * Plugin Name: Global Parity Engine
 * Plugin URI: https://github.com/rongabby/global-parity-engine
 * Description: A WordPress plugin for tracking and analyzing global parity data across different metrics and regions.
 * Version: 1.0.0
 * Author: Global Parity Team
 * Author URI: https://github.com/rongabby
 * License: GPL v2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: global-parity-engine
 * Domain Path: /languages
 *
 * @package GlobalParityEngine
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Define plugin constants.
define( 'GPE_VERSION', '1.0.0' );
define( 'GPE_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'GPE_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
define( 'GPE_PLUGIN_BASENAME', plugin_basename( __FILE__ ) );

/**
 * Main Global Parity Engine class.
 */
class Global_Parity_Engine {

	/**
	 * Instance of this class.
	 *
	 * @var object
	 */
	protected static $instance = null;

	/**
	 * Initialize the plugin.
	 */
	private function __construct() {
		// Load plugin text domain.
		add_action( 'init', array( $this, 'load_plugin_textdomain' ) );

		// Register custom post type.
		add_action( 'init', array( $this, 'register_parity_post_type' ) );

		// Register custom taxonomies.
		add_action( 'init', array( $this, 'register_taxonomies' ) );

		// Add meta boxes.
		add_action( 'add_meta_boxes', array( $this, 'add_parity_meta_boxes' ) );

		// Save meta box data.
		add_action( 'save_post_parity_data', array( $this, 'save_parity_meta' ), 10, 2 );

		// Register REST API fields.
		add_action( 'rest_api_init', array( $this, 'register_rest_api_fields' ) );

		// Add custom REST API endpoints.
		add_action( 'rest_api_init', array( $this, 'register_rest_routes' ) );
	}

	/**
	 * Return an instance of this class.
	 *
	 * @return object A single instance of this class.
	 */
	public static function get_instance() {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	/**
	 * Load the plugin text domain for translation.
	 */
	public function load_plugin_textdomain() {
		load_plugin_textdomain(
			'global-parity-engine',
			false,
			dirname( GPE_PLUGIN_BASENAME ) . '/languages/'
		);
	}

	/**
	 * Register custom post type for parity data.
	 */
	public function register_parity_post_type() {
		$labels = array(
			'name'                  => _x( 'Parity Data', 'Post Type General Name', 'global-parity-engine' ),
			'singular_name'         => _x( 'Parity Data', 'Post Type Singular Name', 'global-parity-engine' ),
			'menu_name'             => __( 'Parity Engine', 'global-parity-engine' ),
			'name_admin_bar'        => __( 'Parity Data', 'global-parity-engine' ),
			'archives'              => __( 'Parity Archives', 'global-parity-engine' ),
			'attributes'            => __( 'Parity Attributes', 'global-parity-engine' ),
			'parent_item_colon'     => __( 'Parent Parity:', 'global-parity-engine' ),
			'all_items'             => __( 'All Parity Data', 'global-parity-engine' ),
			'add_new_item'          => __( 'Add New Parity Data', 'global-parity-engine' ),
			'add_new'               => __( 'Add New', 'global-parity-engine' ),
			'new_item'              => __( 'New Parity Data', 'global-parity-engine' ),
			'edit_item'             => __( 'Edit Parity Data', 'global-parity-engine' ),
			'update_item'           => __( 'Update Parity Data', 'global-parity-engine' ),
			'view_item'             => __( 'View Parity Data', 'global-parity-engine' ),
			'view_items'            => __( 'View Parity Data', 'global-parity-engine' ),
			'search_items'          => __( 'Search Parity Data', 'global-parity-engine' ),
			'not_found'             => __( 'Not found', 'global-parity-engine' ),
			'not_found_in_trash'    => __( 'Not found in Trash', 'global-parity-engine' ),
			'featured_image'        => __( 'Featured Image', 'global-parity-engine' ),
			'set_featured_image'    => __( 'Set featured image', 'global-parity-engine' ),
			'remove_featured_image' => __( 'Remove featured image', 'global-parity-engine' ),
			'use_featured_image'    => __( 'Use as featured image', 'global-parity-engine' ),
			'insert_into_item'      => __( 'Insert into parity data', 'global-parity-engine' ),
			'uploaded_to_this_item' => __( 'Uploaded to this parity data', 'global-parity-engine' ),
			'items_list'            => __( 'Parity data list', 'global-parity-engine' ),
			'items_list_navigation' => __( 'Parity data list navigation', 'global-parity-engine' ),
			'filter_items_list'     => __( 'Filter parity data list', 'global-parity-engine' ),
		);

		$args = array(
			'label'               => __( 'Parity Data', 'global-parity-engine' ),
			'description'         => __( 'Global parity tracking and analysis', 'global-parity-engine' ),
			'labels'              => $labels,
			'supports'            => array( 'title', 'editor', 'author', 'thumbnail', 'excerpt', 'custom-fields', 'revisions' ),
			'taxonomies'          => array( 'parity_category', 'parity_region' ),
			'hierarchical'        => false,
			'public'              => true,
			'show_ui'             => true,
			'show_in_menu'        => true,
			'menu_position'       => 20,
			'menu_icon'           => 'dashicons-chart-line',
			'show_in_admin_bar'   => true,
			'show_in_nav_menus'   => true,
			'can_export'          => true,
			'has_archive'         => true,
			'exclude_from_search' => false,
			'publicly_queryable'  => true,
			'capability_type'     => 'post',
			'show_in_rest'        => true,
			'rest_base'           => 'parity-data',
			'rest_controller_class' => 'WP_REST_Posts_Controller',
		);

		register_post_type( 'parity_data', $args );
	}

	/**
	 * Register custom taxonomies.
	 */
	public function register_taxonomies() {
		// Register Parity Category taxonomy.
		$category_labels = array(
			'name'              => _x( 'Parity Categories', 'taxonomy general name', 'global-parity-engine' ),
			'singular_name'     => _x( 'Parity Category', 'taxonomy singular name', 'global-parity-engine' ),
			'search_items'      => __( 'Search Categories', 'global-parity-engine' ),
			'all_items'         => __( 'All Categories', 'global-parity-engine' ),
			'parent_item'       => __( 'Parent Category', 'global-parity-engine' ),
			'parent_item_colon' => __( 'Parent Category:', 'global-parity-engine' ),
			'edit_item'         => __( 'Edit Category', 'global-parity-engine' ),
			'update_item'       => __( 'Update Category', 'global-parity-engine' ),
			'add_new_item'      => __( 'Add New Category', 'global-parity-engine' ),
			'new_item_name'     => __( 'New Category Name', 'global-parity-engine' ),
			'menu_name'         => __( 'Categories', 'global-parity-engine' ),
		);

		register_taxonomy(
			'parity_category',
			array( 'parity_data' ),
			array(
				'hierarchical'      => true,
				'labels'            => $category_labels,
				'show_ui'           => true,
				'show_admin_column' => true,
				'query_var'         => true,
				'show_in_rest'      => true,
				'rest_base'         => 'parity-categories',
				'rewrite'           => array( 'slug' => 'parity-category' ),
			)
		);

		// Register Parity Region taxonomy.
		$region_labels = array(
			'name'              => _x( 'Regions', 'taxonomy general name', 'global-parity-engine' ),
			'singular_name'     => _x( 'Region', 'taxonomy singular name', 'global-parity-engine' ),
			'search_items'      => __( 'Search Regions', 'global-parity-engine' ),
			'all_items'         => __( 'All Regions', 'global-parity-engine' ),
			'parent_item'       => __( 'Parent Region', 'global-parity-engine' ),
			'parent_item_colon' => __( 'Parent Region:', 'global-parity-engine' ),
			'edit_item'         => __( 'Edit Region', 'global-parity-engine' ),
			'update_item'       => __( 'Update Region', 'global-parity-engine' ),
			'add_new_item'      => __( 'Add New Region', 'global-parity-engine' ),
			'new_item_name'     => __( 'New Region Name', 'global-parity-engine' ),
			'menu_name'         => __( 'Regions', 'global-parity-engine' ),
		);

		register_taxonomy(
			'parity_region',
			array( 'parity_data' ),
			array(
				'hierarchical'      => true,
				'labels'            => $region_labels,
				'show_ui'           => true,
				'show_admin_column' => true,
				'query_var'         => true,
				'show_in_rest'      => true,
				'rest_base'         => 'parity-regions',
				'rewrite'           => array( 'slug' => 'region' ),
			)
		);
	}

	/**
	 * Add meta boxes for parity data.
	 */
	public function add_parity_meta_boxes() {
		add_meta_box(
			'parity_metrics',
			__( 'Parity Metrics', 'global-parity-engine' ),
			array( $this, 'render_parity_metrics_meta_box' ),
			'parity_data',
			'normal',
			'high'
		);

		add_meta_box(
			'parity_statistics',
			__( 'Statistical Data', 'global-parity-engine' ),
			array( $this, 'render_parity_statistics_meta_box' ),
			'parity_data',
			'normal',
			'default'
		);
	}

	/**
	 * Render parity metrics meta box.
	 *
	 * @param WP_Post $post The post object.
	 */
	public function render_parity_metrics_meta_box( $post ) {
		// Add nonce for security.
		wp_nonce_field( 'parity_metrics_nonce', 'parity_metrics_nonce_field' );

		// Get existing values.
		$parity_score = get_post_meta( $post->ID, '_parity_score', true );
		$parity_index = get_post_meta( $post->ID, '_parity_index', true );
		$target_value = get_post_meta( $post->ID, '_target_value', true );
		$current_value = get_post_meta( $post->ID, '_current_value', true );
		$measurement_unit = get_post_meta( $post->ID, '_measurement_unit', true );
		?>
		<table class="form-table">
			<tr>
				<th><label for="parity_score"><?php esc_html_e( 'Parity Score:', 'global-parity-engine' ); ?></label></th>
				<td>
					<input type="number" id="parity_score" name="parity_score" value="<?php echo esc_attr( $parity_score ); ?>" step="0.01" min="0" max="100" class="regular-text" />
					<p class="description"><?php esc_html_e( 'Overall parity score (0-100)', 'global-parity-engine' ); ?></p>
				</td>
			</tr>
			<tr>
				<th><label for="parity_index"><?php esc_html_e( 'Parity Index:', 'global-parity-engine' ); ?></label></th>
				<td>
					<input type="number" id="parity_index" name="parity_index" value="<?php echo esc_attr( $parity_index ); ?>" step="0.001" class="regular-text" />
					<p class="description"><?php esc_html_e( 'Normalized parity index value', 'global-parity-engine' ); ?></p>
				</td>
			</tr>
			<tr>
				<th><label for="current_value"><?php esc_html_e( 'Current Value:', 'global-parity-engine' ); ?></label></th>
				<td>
					<input type="number" id="current_value" name="current_value" value="<?php echo esc_attr( $current_value ); ?>" step="0.01" class="regular-text" />
					<p class="description"><?php esc_html_e( 'Current measured value', 'global-parity-engine' ); ?></p>
				</td>
			</tr>
			<tr>
				<th><label for="target_value"><?php esc_html_e( 'Target Value:', 'global-parity-engine' ); ?></label></th>
				<td>
					<input type="number" id="target_value" name="target_value" value="<?php echo esc_attr( $target_value ); ?>" step="0.01" class="regular-text" />
					<p class="description"><?php esc_html_e( 'Target value for parity', 'global-parity-engine' ); ?></p>
				</td>
			</tr>
			<tr>
				<th><label for="measurement_unit"><?php esc_html_e( 'Measurement Unit:', 'global-parity-engine' ); ?></label></th>
				<td>
					<input type="text" id="measurement_unit" name="measurement_unit" value="<?php echo esc_attr( $measurement_unit ); ?>" class="regular-text" />
					<p class="description"><?php esc_html_e( 'Unit of measurement (e.g., %, USD, points)', 'global-parity-engine' ); ?></p>
				</td>
			</tr>
		</table>
		<?php
	}

	/**
	 * Render parity statistics meta box.
	 *
	 * @param WP_Post $post The post object.
	 */
	public function render_parity_statistics_meta_box( $post ) {
		// Add nonce for security.
		wp_nonce_field( 'parity_statistics_nonce', 'parity_statistics_nonce_field' );

		// Get existing values.
		$data_source = get_post_meta( $post->ID, '_data_source', true );
		$collection_date = get_post_meta( $post->ID, '_collection_date', true );
		$sample_size = get_post_meta( $post->ID, '_sample_size', true );
		$confidence_level = get_post_meta( $post->ID, '_confidence_level', true );
		$year = get_post_meta( $post->ID, '_year', true );
		?>
		<table class="form-table">
			<tr>
				<th><label for="data_source"><?php esc_html_e( 'Data Source:', 'global-parity-engine' ); ?></label></th>
				<td>
					<input type="text" id="data_source" name="data_source" value="<?php echo esc_attr( $data_source ); ?>" class="regular-text" />
					<p class="description"><?php esc_html_e( 'Source of the data', 'global-parity-engine' ); ?></p>
				</td>
			</tr>
			<tr>
				<th><label for="collection_date"><?php esc_html_e( 'Collection Date:', 'global-parity-engine' ); ?></label></th>
				<td>
					<input type="date" id="collection_date" name="collection_date" value="<?php echo esc_attr( $collection_date ); ?>" class="regular-text" />
					<p class="description"><?php esc_html_e( 'Date when data was collected', 'global-parity-engine' ); ?></p>
				</td>
			</tr>
			<tr>
				<th><label for="year"><?php esc_html_e( 'Year:', 'global-parity-engine' ); ?></label></th>
				<td>
					<input type="number" id="year" name="year" value="<?php echo esc_attr( $year ); ?>" min="1900" max="2100" class="regular-text" />
					<p class="description"><?php esc_html_e( 'Reference year for the data', 'global-parity-engine' ); ?></p>
				</td>
			</tr>
			<tr>
				<th><label for="sample_size"><?php esc_html_e( 'Sample Size:', 'global-parity-engine' ); ?></label></th>
				<td>
					<input type="number" id="sample_size" name="sample_size" value="<?php echo esc_attr( $sample_size ); ?>" min="0" class="regular-text" />
					<p class="description"><?php esc_html_e( 'Number of samples in the dataset', 'global-parity-engine' ); ?></p>
				</td>
			</tr>
			<tr>
				<th><label for="confidence_level"><?php esc_html_e( 'Confidence Level:', 'global-parity-engine' ); ?></label></th>
				<td>
					<input type="number" id="confidence_level" name="confidence_level" value="<?php echo esc_attr( $confidence_level ); ?>" step="0.01" min="0" max="100" class="regular-text" />
					<p class="description"><?php esc_html_e( 'Statistical confidence level (%)', 'global-parity-engine' ); ?></p>
				</td>
			</tr>
		</table>
		<?php
	}

	/**
	 * Save parity meta data.
	 *
	 * @param int     $post_id Post ID.
	 * @param WP_Post $post    Post object.
	 */
	public function save_parity_meta( $post_id, $post ) {
		// Check if it's an autosave.
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return;
		}

		// Check user permissions.
		if ( ! current_user_can( 'edit_post', $post_id ) ) {
			return;
		}

		// Save parity metrics.
		if ( isset( $_POST['parity_metrics_nonce_field'] ) && wp_verify_nonce( $_POST['parity_metrics_nonce_field'], 'parity_metrics_nonce' ) ) {
			$fields = array( 'parity_score', 'parity_index', 'target_value', 'current_value', 'measurement_unit' );
			foreach ( $fields as $field ) {
				if ( isset( $_POST[ $field ] ) ) {
					update_post_meta( $post_id, '_' . $field, sanitize_text_field( $_POST[ $field ] ) );
				}
			}
		}

		// Save parity statistics.
		if ( isset( $_POST['parity_statistics_nonce_field'] ) && wp_verify_nonce( $_POST['parity_statistics_nonce_field'], 'parity_statistics_nonce' ) ) {
			$fields = array( 'data_source', 'collection_date', 'sample_size', 'confidence_level', 'year' );
			foreach ( $fields as $field ) {
				if ( isset( $_POST[ $field ] ) ) {
					update_post_meta( $post_id, '_' . $field, sanitize_text_field( $_POST[ $field ] ) );
				}
			}
		}
	}

	/**
	 * Register REST API custom fields.
	 */
	public function register_rest_api_fields() {
		// Register parity metrics fields.
		$metrics_fields = array(
			'parity_score'     => array(
				'type'        => 'number',
				'description' => __( 'Overall parity score (0-100)', 'global-parity-engine' ),
			),
			'parity_index'     => array(
				'type'        => 'number',
				'description' => __( 'Normalized parity index value', 'global-parity-engine' ),
			),
			'current_value'    => array(
				'type'        => 'number',
				'description' => __( 'Current measured value', 'global-parity-engine' ),
			),
			'target_value'     => array(
				'type'        => 'number',
				'description' => __( 'Target value for parity', 'global-parity-engine' ),
			),
			'measurement_unit' => array(
				'type'        => 'string',
				'description' => __( 'Unit of measurement', 'global-parity-engine' ),
			),
		);

		foreach ( $metrics_fields as $field_name => $schema ) {
			register_rest_field(
				'parity_data',
				$field_name,
				array(
					'get_callback'    => function( $object ) use ( $field_name ) {
						$value = get_post_meta( $object['id'], '_' . $field_name, true );
						if ( in_array( $field_name, array( 'parity_score', 'parity_index', 'current_value', 'target_value' ), true ) ) {
							return $value ? floatval( $value ) : null;
						}
						return $value ? $value : null;
					},
					'update_callback' => function( $value, $object ) use ( $field_name ) {
						if ( in_array( $field_name, array( 'parity_score', 'parity_index', 'current_value', 'target_value' ), true ) ) {
							return update_post_meta( $object->ID, '_' . $field_name, floatval( $value ) );
						}
						return update_post_meta( $object->ID, '_' . $field_name, sanitize_text_field( $value ) );
					},
					'schema'          => array(
						'type'        => $schema['type'],
						'description' => $schema['description'],
						'context'     => array( 'view', 'edit' ),
					),
				)
			);
		}

		// Register statistical fields.
		$statistics_fields = array(
			'data_source'      => array(
				'type'        => 'string',
				'description' => __( 'Source of the data', 'global-parity-engine' ),
			),
			'collection_date'  => array(
				'type'        => 'string',
				'format'      => 'date',
				'description' => __( 'Date when data was collected', 'global-parity-engine' ),
			),
			'year'             => array(
				'type'        => 'integer',
				'description' => __( 'Reference year for the data', 'global-parity-engine' ),
			),
			'sample_size'      => array(
				'type'        => 'integer',
				'description' => __( 'Number of samples in the dataset', 'global-parity-engine' ),
			),
			'confidence_level' => array(
				'type'        => 'number',
				'description' => __( 'Statistical confidence level (%)', 'global-parity-engine' ),
			),
		);

		foreach ( $statistics_fields as $field_name => $schema ) {
			register_rest_field(
				'parity_data',
				$field_name,
				array(
					'get_callback'    => function( $object ) use ( $field_name ) {
						$value = get_post_meta( $object['id'], '_' . $field_name, true );
						if ( 'year' === $field_name || 'sample_size' === $field_name ) {
							return $value ? intval( $value ) : null;
						} elseif ( 'confidence_level' === $field_name ) {
							return $value ? floatval( $value ) : null;
						}
						return $value ? $value : null;
					},
					'update_callback' => function( $value, $object ) use ( $field_name ) {
						if ( 'year' === $field_name || 'sample_size' === $field_name ) {
							return update_post_meta( $object->ID, '_' . $field_name, intval( $value ) );
						} elseif ( 'confidence_level' === $field_name ) {
							return update_post_meta( $object->ID, '_' . $field_name, floatval( $value ) );
						}
						return update_post_meta( $object->ID, '_' . $field_name, sanitize_text_field( $value ) );
					},
					'schema'          => array_merge(
						array(
							'type'        => $schema['type'],
							'description' => $schema['description'],
							'context'     => array( 'view', 'edit' ),
						),
						isset( $schema['format'] ) ? array( 'format' => $schema['format'] ) : array()
					),
				)
			);
		}
	}

	/**
	 * Register custom REST API routes.
	 */
	public function register_rest_routes() {
		// Register route for parity statistics.
		register_rest_route(
			'global-parity/v1',
			'/statistics',
			array(
				'methods'             => 'GET',
				'callback'            => array( $this, 'get_parity_statistics' ),
				'permission_callback' => '__return_true',
			)
		);

		// Register route for parity by region.
		register_rest_route(
			'global-parity/v1',
			'/by-region/(?P<region>[a-zA-Z0-9-]+)',
			array(
				'methods'             => 'GET',
				'callback'            => array( $this, 'get_parity_by_region' ),
				'permission_callback' => '__return_true',
				'args'                => array(
					'region' => array(
						'validate_callback' => function( $param ) {
							return is_string( $param );
						},
					),
				),
			)
		);

		// Register route for parity comparison.
		register_rest_route(
			'global-parity/v1',
			'/compare',
			array(
				'methods'             => 'GET',
				'callback'            => array( $this, 'compare_parity_data' ),
				'permission_callback' => '__return_true',
				'args'                => array(
					'ids' => array(
						'required'          => true,
						'validate_callback' => function( $param ) {
							$ids = explode( ',', $param );
							foreach ( $ids as $id ) {
								if ( ! is_numeric( $id ) ) {
									return false;
								}
							}
							return true;
						},
					),
				),
			)
		);
	}

	/**
	 * Get overall parity statistics.
	 *
	 * @param WP_REST_Request $request Request object.
	 * @return WP_REST_Response Response object.
	 */
	public function get_parity_statistics( $request ) {
		$args = array(
			'post_type'      => 'parity_data',
			'posts_per_page' => -1,
			'post_status'    => 'publish',
		);

		$query = new WP_Query( $args );
		$statistics = array(
			'total_entries'     => $query->found_posts,
			'average_score'     => 0,
			'highest_score'     => 0,
			'lowest_score'      => 100,
			'regions_covered'   => 0,
			'categories_count'  => 0,
		);

		$total_score = 0;
		$score_count = 0;

		if ( $query->have_posts() ) {
			while ( $query->have_posts() ) {
				$query->the_post();
				$score = get_post_meta( get_the_ID(), '_parity_score', true );
				if ( $score ) {
					$score = floatval( $score );
					$total_score += $score;
					$score_count++;
					$statistics['highest_score'] = max( $statistics['highest_score'], $score );
					$statistics['lowest_score'] = min( $statistics['lowest_score'], $score );
				}
			}
			wp_reset_postdata();
		}

		if ( $score_count > 0 ) {
			$statistics['average_score'] = round( $total_score / $score_count, 2 );
		}

		// Get region and category counts.
		$regions = get_terms( array( 'taxonomy' => 'parity_region', 'hide_empty' => true ) );
		$categories = get_terms( array( 'taxonomy' => 'parity_category', 'hide_empty' => true ) );
		$statistics['regions_covered'] = is_array( $regions ) ? count( $regions ) : 0;
		$statistics['categories_count'] = is_array( $categories ) ? count( $categories ) : 0;

		return new WP_REST_Response( $statistics, 200 );
	}

	/**
	 * Get parity data by region.
	 *
	 * @param WP_REST_Request $request Request object.
	 * @return WP_REST_Response Response object.
	 */
	public function get_parity_by_region( $request ) {
		$region_slug = $request['region'];

		$args = array(
			'post_type'      => 'parity_data',
			'posts_per_page' => -1,
			'post_status'    => 'publish',
			'tax_query'      => array(
				array(
					'taxonomy' => 'parity_region',
					'field'    => 'slug',
					'terms'    => $region_slug,
				),
			),
		);

		$query = new WP_Query( $args );
		$results = array();

		if ( $query->have_posts() ) {
			while ( $query->have_posts() ) {
				$query->the_post();
				$post_id = get_the_ID();
				$results[] = array(
					'id'               => $post_id,
					'title'            => get_the_title(),
					'parity_score'     => floatval( get_post_meta( $post_id, '_parity_score', true ) ),
					'parity_index'     => floatval( get_post_meta( $post_id, '_parity_index', true ) ),
					'current_value'    => floatval( get_post_meta( $post_id, '_current_value', true ) ),
					'target_value'     => floatval( get_post_meta( $post_id, '_target_value', true ) ),
					'measurement_unit' => get_post_meta( $post_id, '_measurement_unit', true ),
					'year'             => intval( get_post_meta( $post_id, '_year', true ) ),
					'link'             => get_permalink(),
				);
			}
			wp_reset_postdata();
		}

		return new WP_REST_Response(
			array(
				'region' => $region_slug,
				'count'  => count( $results ),
				'data'   => $results,
			),
			200
		);
	}

	/**
	 * Compare multiple parity data entries.
	 *
	 * @param WP_REST_Request $request Request object.
	 * @return WP_REST_Response Response object.
	 */
	public function compare_parity_data( $request ) {
		$ids = explode( ',', $request['ids'] );
		$comparison = array();

		foreach ( $ids as $id ) {
			$id = intval( $id );
			$post = get_post( $id );

			if ( $post && 'parity_data' === $post->post_type ) {
				$comparison[] = array(
					'id'               => $id,
					'title'            => get_the_title( $id ),
					'parity_score'     => floatval( get_post_meta( $id, '_parity_score', true ) ),
					'parity_index'     => floatval( get_post_meta( $id, '_parity_index', true ) ),
					'current_value'    => floatval( get_post_meta( $id, '_current_value', true ) ),
					'target_value'     => floatval( get_post_meta( $id, '_target_value', true ) ),
					'measurement_unit' => get_post_meta( $id, '_measurement_unit', true ),
					'year'             => intval( get_post_meta( $id, '_year', true ) ),
					'regions'          => wp_get_post_terms( $id, 'parity_region', array( 'fields' => 'names' ) ),
					'categories'       => wp_get_post_terms( $id, 'parity_category', array( 'fields' => 'names' ) ),
				);
			}
		}

		return new WP_REST_Response(
			array(
				'count'      => count( $comparison ),
				'comparison' => $comparison,
			),
			200
		);
	}
}

/**
 * Plugin activation hook.
 */
function gpe_activate() {
	// Trigger plugin initialization to register post types.
	Global_Parity_Engine::get_instance();

	// Flush rewrite rules.
	flush_rewrite_rules();
}
register_activation_hook( __FILE__, 'gpe_activate' );

/**
 * Plugin deactivation hook.
 */
function gpe_deactivate() {
	// Flush rewrite rules.
	flush_rewrite_rules();
}
register_deactivation_hook( __FILE__, 'gpe_deactivate' );

// Initialize the plugin.
add_action( 'plugins_loaded', array( 'Global_Parity_Engine', 'get_instance' ) );
