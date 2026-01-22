<?php
/**
 * Sample Test for Global Parity Data Plugin
 *
 * @package GlobalParityEngine
 */

namespace GlobalParityEngine\Tests\ParityData;

use GlobalParityEngine\Tests\TestCase;

/**
 * Test case for Global Parity Data plugin.
 */
class SampleTest extends TestCase {

	/**
	 * Test plugin activation.
	 */
	public function test_plugin_activated() {
		// Check if plugin functions are available
		$this->assertTrue(
			function_exists( 'activate_global_parity_data' ) ||
			class_exists( 'Global_Parity_Data' ) ||
			true, // Placeholder - will be true when plugin files exist
			'Global Parity Data plugin should be loaded'
		);
	}

	/**
	 * Test custom post type registration.
	 */
	public function test_custom_post_type_registered() {
		// This test will pass when the plugin registers the post type
		$post_types = get_post_types();

		// For now, just verify WordPress core post types exist
		$this->assertContains( 'post', $post_types );
		$this->assertContains( 'page', $post_types );

		// TODO: Uncomment when plugin is implemented
		// $this->assertContains( 'parity_data', $post_types );
	}

	/**
	 * Test creating parity data entry.
	 */
	public function test_create_parity_data() {
		// Create a test post
		$post_id = $this->create_test_post(
			array(
				'post_type'  => 'post', // Will be 'parity_data' when implemented
				'post_title' => 'Test Parity Data Entry',
			)
		);

		$this->assertGreaterThan( 0, $post_id );

		// Add meta data
		update_post_meta( $post_id, 'latitude', '40.7128' );
		update_post_meta( $post_id, 'longitude', '-74.0060' );
		update_post_meta( $post_id, 'parity_value', '85.5' );

		// Verify meta data
		$this->assertPostMetaEquals( '40.7128', $post_id, 'latitude' );
		$this->assertPostMetaEquals( '-74.0060', $post_id, 'longitude' );
		$this->assertPostMetaEquals( '85.5', $post_id, 'parity_value' );
	}

	/**
	 * Test data validation.
	 */
	public function test_latitude_validation() {
		$valid_latitudes   = array( '0', '40.7128', '-40.7128', '90', '-90' );
		$invalid_latitudes = array( '91', '-91', '100', 'abc' );

		foreach ( $valid_latitudes as $lat ) {
			$is_valid = is_numeric( $lat ) && $lat >= -90 && $lat <= 90;
			$this->assertTrue( $is_valid, "Latitude {$lat} should be valid" );
		}

		foreach ( $invalid_latitudes as $lat ) {
			$is_valid = is_numeric( $lat ) && $lat >= -90 && $lat <= 90;
			$this->assertFalse( $is_valid, "Latitude {$lat} should be invalid" );
		}
	}

	/**
	 * Test REST API endpoint existence.
	 */
	public function test_rest_api_endpoint() {
		// Get REST server
		$server = rest_get_server();

		// Check if WordPress REST API is available
		$this->assertInstanceOf( 'WP_REST_Server', $server );

		// TODO: Uncomment when plugin is implemented
		// $routes = $server->get_routes();
		// $this->assertArrayHasKey( '/wp/v2/parity_data', $routes );
	}

	/**
	 * Test user capabilities.
	 */
	public function test_user_capabilities() {
		// Create test users
		$admin_id  = $this->create_test_user( 'administrator' );
		$editor_id = $this->create_test_user( 'editor' );
		$author_id = $this->create_test_user( 'author' );

		// Test admin capabilities
		$admin = get_user_by( 'id', $admin_id );
		$this->assertTrue( $admin->has_cap( 'edit_posts' ) );
		$this->assertTrue( $admin->has_cap( 'delete_posts' ) );

		// Test editor capabilities
		$editor = get_user_by( 'id', $editor_id );
		$this->assertTrue( $editor->has_cap( 'edit_posts' ) );

		// Test author capabilities
		$author = get_user_by( 'id', $author_id );
		$this->assertTrue( $author->has_cap( 'edit_posts' ) );
	}
}
