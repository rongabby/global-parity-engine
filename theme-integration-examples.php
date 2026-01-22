<?php
/**
 * Example Theme Integration for Global Parity Engine
 * 
 * This file shows various examples of how to use the Global Parity Engine
 * plugin in your WordPress theme or custom plugins.
 * 
 * @package GlobalParityEngine
 */

// Example 1: Display all parity data in a custom template
function gpe_display_parity_list() {
	$args = array(
		'post_type'      => 'parity_data',
		'posts_per_page' => 10,
		'orderby'        => 'date',
		'order'          => 'DESC',
	);
	
	$query = new WP_Query( $args );
	
	if ( $query->have_posts() ) {
		echo '<div class="parity-list">';
		while ( $query->have_posts() ) {
			$query->the_post();
			$post_id = get_the_ID();
			
			// Get custom fields
			$parity_score = get_post_meta( $post_id, '_parity_score', true );
			$current_value = get_post_meta( $post_id, '_current_value', true );
			$target_value = get_post_meta( $post_id, '_target_value', true );
			$unit = get_post_meta( $post_id, '_measurement_unit', true );
			?>
			<article class="parity-entry">
				<h2><?php the_title(); ?></h2>
				<div class="parity-score">
					<span class="label">Parity Score:</span>
					<span class="value"><?php echo esc_html( $parity_score ); ?></span>
				</div>
				<div class="parity-values">
					<span>Current: <?php echo esc_html( $current_value . ' ' . $unit ); ?></span>
					<span>Target: <?php echo esc_html( $target_value . ' ' . $unit ); ?></span>
				</div>
				<div class="parity-content">
					<?php the_excerpt(); ?>
				</div>
				<a href="<?php the_permalink(); ?>" class="read-more">Read More</a>
			</article>
			<?php
		}
		echo '</div>';
		wp_reset_postdata();
	}
}

// Example 2: Display parity data by region
function gpe_display_parity_by_region( $region_slug ) {
	$args = array(
		'post_type'      => 'parity_data',
		'posts_per_page' => -1,
		'tax_query'      => array(
			array(
				'taxonomy' => 'parity_region',
				'field'    => 'slug',
				'terms'    => $region_slug,
			),
		),
	);
	
	$query = new WP_Query( $args );
	
	if ( $query->have_posts() ) {
		echo '<div class="parity-region-data">';
		while ( $query->have_posts() ) {
			$query->the_post();
			// Display post content
			the_title( '<h3>', '</h3>' );
			the_excerpt();
		}
		echo '</div>';
		wp_reset_postdata();
	}
}

// Example 3: Calculate average parity score
function gpe_get_average_score( $category_slug = '' ) {
	$args = array(
		'post_type'      => 'parity_data',
		'posts_per_page' => -1,
		'post_status'    => 'publish',
	);
	
	if ( ! empty( $category_slug ) ) {
		$args['tax_query'] = array(
			array(
				'taxonomy' => 'parity_category',
				'field'    => 'slug',
				'terms'    => $category_slug,
			),
		);
	}
	
	$query = new WP_Query( $args );
	$total = 0;
	$count = 0;
	
	if ( $query->have_posts() ) {
		while ( $query->have_posts() ) {
			$query->the_post();
			$score = get_post_meta( get_the_ID(), '_parity_score', true );
			if ( $score ) {
				$total += floatval( $score );
				$count++;
			}
		}
		wp_reset_postdata();
	}
	
	return $count > 0 ? round( $total / $count, 2 ) : 0;
}

// Example 4: Shortcode to display parity statistics
function gpe_statistics_shortcode( $atts ) {
	$atts = shortcode_atts(
		array(
			'region'   => '',
			'category' => '',
		),
		$atts,
		'parity_statistics'
	);
	
	$average = gpe_get_average_score( $atts['category'] );
	
	ob_start();
	?>
	<div class="parity-statistics">
		<h3>Parity Statistics</h3>
		<p>Average Score: <strong><?php echo esc_html( $average ); ?></strong></p>
	</div>
	<?php
	return ob_get_clean();
}
add_shortcode( 'parity_statistics', 'gpe_statistics_shortcode' );

// Example 5: Widget to display latest parity data
class GPE_Recent_Parity_Widget extends WP_Widget {
	
	public function __construct() {
		parent::__construct(
			'gpe_recent_parity',
			__( 'Recent Parity Data', 'global-parity-engine' ),
			array( 'description' => __( 'Display recent parity data entries', 'global-parity-engine' ) )
		);
	}
	
	public function widget( $args, $instance ) {
		echo $args['before_widget'];
		
		if ( ! empty( $instance['title'] ) ) {
			echo $args['before_title'] . apply_filters( 'widget_title', $instance['title'] ) . $args['after_title'];
		}
		
		$query_args = array(
			'post_type'      => 'parity_data',
			'posts_per_page' => isset( $instance['number'] ) ? intval( $instance['number'] ) : 5,
			'orderby'        => 'date',
			'order'          => 'DESC',
		);
		
		$query = new WP_Query( $query_args );
		
		if ( $query->have_posts() ) {
			echo '<ul class="recent-parity-list">';
			while ( $query->have_posts() ) {
				$query->the_post();
				$score = get_post_meta( get_the_ID(), '_parity_score', true );
				?>
				<li>
					<a href="<?php the_permalink(); ?>">
						<?php the_title(); ?>
						<?php if ( $score ) : ?>
							<span class="score">(<?php echo esc_html( $score ); ?>)</span>
						<?php endif; ?>
					</a>
				</li>
				<?php
			}
			echo '</ul>';
			wp_reset_postdata();
		}
		
		echo $args['after_widget'];
	}
	
	public function form( $instance ) {
		$title = ! empty( $instance['title'] ) ? $instance['title'] : __( 'Recent Parity Data', 'global-parity-engine' );
		$number = ! empty( $instance['number'] ) ? $instance['number'] : 5;
		?>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>">
				<?php esc_html_e( 'Title:', 'global-parity-engine' ); ?>
			</label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" 
				name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" 
				type="text" value="<?php echo esc_attr( $title ); ?>">
		</p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'number' ) ); ?>">
				<?php esc_html_e( 'Number of posts:', 'global-parity-engine' ); ?>
			</label>
			<input class="tiny-text" id="<?php echo esc_attr( $this->get_field_id( 'number' ) ); ?>" 
				name="<?php echo esc_attr( $this->get_field_name( 'number' ) ); ?>" 
				type="number" step="1" min="1" value="<?php echo esc_attr( $number ); ?>" size="3">
		</p>
		<?php
	}
	
	public function update( $new_instance, $old_instance ) {
		$instance = array();
		$instance['title'] = ( ! empty( $new_instance['title'] ) ) ? sanitize_text_field( $new_instance['title'] ) : '';
		$instance['number'] = ( ! empty( $new_instance['number'] ) ) ? absint( $new_instance['number'] ) : 5;
		return $instance;
	}
}

// Register the widget
function gpe_register_widgets() {
	register_widget( 'GPE_Recent_Parity_Widget' );
}
add_action( 'widgets_init', 'gpe_register_widgets' );

// Example 6: AJAX endpoint to fetch parity data
function gpe_ajax_get_parity_data() {
	check_ajax_referer( 'gpe_ajax_nonce', 'nonce' );
	
	$post_id = isset( $_POST['post_id'] ) ? intval( $_POST['post_id'] ) : 0;
	
	if ( ! $post_id ) {
		wp_send_json_error( 'Invalid post ID' );
	}
	
	$post = get_post( $post_id );
	
	if ( ! $post || 'parity_data' !== $post->post_type ) {
		wp_send_json_error( 'Post not found' );
	}
	
	$data = array(
		'id'               => $post_id,
		'title'            => get_the_title( $post_id ),
		'parity_score'     => floatval( get_post_meta( $post_id, '_parity_score', true ) ),
		'parity_index'     => floatval( get_post_meta( $post_id, '_parity_index', true ) ),
		'current_value'    => floatval( get_post_meta( $post_id, '_current_value', true ) ),
		'target_value'     => floatval( get_post_meta( $post_id, '_target_value', true ) ),
		'measurement_unit' => get_post_meta( $post_id, '_measurement_unit', true ),
	);
	
	wp_send_json_success( $data );
}
add_action( 'wp_ajax_gpe_get_parity_data', 'gpe_ajax_get_parity_data' );
add_action( 'wp_ajax_nopriv_gpe_get_parity_data', 'gpe_ajax_get_parity_data' );

// Example 7: Custom query to get top performing entries
function gpe_get_top_parity_entries( $limit = 10 ) {
	global $wpdb;
	
	$query = "
		SELECT p.ID, p.post_title, pm.meta_value as parity_score
		FROM {$wpdb->posts} p
		INNER JOIN {$wpdb->postmeta} pm ON p.ID = pm.post_id
		WHERE p.post_type = 'parity_data'
		AND p.post_status = 'publish'
		AND pm.meta_key = '_parity_score'
		ORDER BY CAST(pm.meta_value AS DECIMAL(10,2)) DESC
		LIMIT %d
	";
	
	$results = $wpdb->get_results( $wpdb->prepare( $query, $limit ) );
	
	return $results;
}

// Example 8: Filter to modify REST API response
function gpe_add_custom_rest_field() {
	register_rest_field(
		'parity_data',
		'progress_percentage',
		array(
			'get_callback' => function( $object ) {
				$current = floatval( get_post_meta( $object['id'], '_current_value', true ) );
				$target = floatval( get_post_meta( $object['id'], '_target_value', true ) );
				
				if ( $target > 0 ) {
					return round( ( $current / $target ) * 100, 2 );
				}
				return 0;
			},
			'schema' => array(
				'description' => 'Progress towards target as percentage',
				'type'        => 'number',
			),
		)
	);
}
add_action( 'rest_api_init', 'gpe_add_custom_rest_field' );

// Example 9: Enqueue JavaScript for frontend
function gpe_enqueue_frontend_scripts() {
	if ( is_singular( 'parity_data' ) || is_post_type_archive( 'parity_data' ) ) {
		wp_enqueue_script(
			'gpe-frontend',
			get_template_directory_uri() . '/js/gpe-frontend.js',
			array( 'jquery' ),
			'1.0.0',
			true
		);
		
		wp_localize_script(
			'gpe-frontend',
			'gpeData',
			array(
				'ajaxurl' => admin_url( 'admin-ajax.php' ),
				'nonce'   => wp_create_nonce( 'gpe_ajax_nonce' ),
				'apiUrl'  => rest_url( 'global-parity/v1/' ),
			)
		);
	}
}
add_action( 'wp_enqueue_scripts', 'gpe_enqueue_frontend_scripts' );

/**
 * Usage in template files:
 * 
 * 1. Display all parity data:
 *    <?php gpe_display_parity_list(); ?>
 * 
 * 2. Display by region:
 *    <?php gpe_display_parity_by_region( 'north-america' ); ?>
 * 
 * 3. Show average score:
 *    Average Score: <?php echo gpe_get_average_score(); ?>
 * 
 * 4. Use shortcode in content:
 *    [parity_statistics category="gender"]
 * 
 * 5. Get top entries:
 *    <?php
 *    $top_entries = gpe_get_top_parity_entries( 5 );
 *    foreach ( $top_entries as $entry ) {
 *        echo $entry->post_title . ': ' . $entry->parity_score;
 *    }
 *    ?>
 */
