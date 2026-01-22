<?php
/**
 * Base Test Case
 *
 * @package GlobalParityEngine
 */

namespace GlobalParityEngine\Tests;

use WP_UnitTestCase;

/**
 * Base test case class for Global Parity Engine tests.
 */
abstract class TestCase extends WP_UnitTestCase {

	/**
	 * Setup test environment.
	 */
	public function setUp(): void {
		parent::setUp();

		// Set up test environment
		$this->set_up_environment();
	}

	/**
	 * Tear down test environment.
	 */
	public function tearDown(): void {
		// Clean up test data
		$this->clean_up_environment();

		parent::tearDown();
	}

	/**
	 * Set up test environment.
	 */
	protected function set_up_environment() {
		// Override in child classes if needed
	}

	/**
	 * Clean up test environment.
	 */
	protected function clean_up_environment() {
		// Override in child classes if needed
	}

	/**
	 * Create a test post.
	 *
	 * @param array $args Post arguments.
	 * @return int Post ID.
	 */
	protected function create_test_post( $args = array() ) {
		$defaults = array(
			'post_type'   => 'post',
			'post_title'  => 'Test Post',
			'post_status' => 'publish',
		);

		$args = wp_parse_args( $args, $defaults );

		return $this->factory->post->create( $args );
	}

	/**
	 * Create a test user.
	 *
	 * @param string $role User role.
	 * @return int User ID.
	 */
	protected function create_test_user( $role = 'subscriber' ) {
		return $this->factory->user->create( array( 'role' => $role ) );
	}

	/**
	 * Assert that a post meta value exists.
	 *
	 * @param int    $post_id  Post ID.
	 * @param string $meta_key Meta key.
	 */
	protected function assertPostMetaExists( $post_id, $meta_key ) {
		$this->assertTrue(
			metadata_exists( 'post', $post_id, $meta_key ),
			"Post meta '{$meta_key}' does not exist for post {$post_id}"
		);
	}

	/**
	 * Assert that a post meta value equals expected value.
	 *
	 * @param mixed  $expected    Expected value.
	 * @param int    $post_id     Post ID.
	 * @param string $meta_key    Meta key.
	 * @param bool   $single      Whether to return single value.
	 */
	protected function assertPostMetaEquals( $expected, $post_id, $meta_key, $single = true ) {
		$actual = get_post_meta( $post_id, $meta_key, $single );
		$this->assertEquals(
			$expected,
			$actual,
			"Post meta '{$meta_key}' does not equal expected value"
		);
	}
}
