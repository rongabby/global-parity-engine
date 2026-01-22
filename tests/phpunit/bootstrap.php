<?php
/**
 * PHPUnit Bootstrap File
 *
 * @package GlobalParityEngine
 */

// Composer autoloader
require_once dirname( dirname( __DIR__ ) ) . '/vendor/autoload.php';

// WordPress tests directory
$_tests_dir = getenv( 'WP_TESTS_DIR' );

if ( ! $_tests_dir ) {
	$_tests_dir = rtrim( sys_get_temp_dir(), '/\\' ) . '/wordpress-tests-lib';
}

// WordPress core directory
$_core_dir = getenv( 'WP_CORE_DIR' );

if ( ! $_core_dir ) {
	$_core_dir = rtrim( sys_get_temp_dir(), '/\\' ) . '/wordpress';
}

// Forward custom PHPUnit Polyfills configuration to PHPUnit bootstrap file
$_phpunit_polyfills_path = getenv( 'WP_TESTS_PHPUNIT_POLYFILLS_PATH' );
if ( false !== $_phpunit_polyfills_path ) {
	define( 'WP_TESTS_PHPUNIT_POLYFILLS_PATH', $_phpunit_polyfills_path );
}

// Check if WordPress tests are available
if ( ! file_exists( "{$_tests_dir}/includes/functions.php" ) ) {
	echo "Could not find {$_tests_dir}/includes/functions.php\n";
	echo "Please run: bash tests/bin/install-wp-tests.sh wordpress_test root root localhost latest\n";
	exit( 1 );
}

// Give access to tests_add_filter() function
require_once "{$_tests_dir}/includes/functions.php";

/**
 * Manually load the plugins being tested.
 */
function _manually_load_plugins() {
	// Load Global Parity Data plugin
	if ( file_exists( dirname( dirname( __DIR__ ) ) . '/plugins/global-parity-data/global-parity-data.php' ) ) {
		require dirname( dirname( __DIR__ ) ) . '/plugins/global-parity-data/global-parity-data.php';
	}

	// Load Google Earth Integration plugin
	if ( file_exists( dirname( dirname( __DIR__ ) ) . '/plugins/google-earth-integration/google-earth-integration.php' ) ) {
		require dirname( dirname( __DIR__ ) ) . '/plugins/google-earth-integration/google-earth-integration.php';
	}
}

tests_add_filter( 'muplugins_loaded', '_manually_load_plugins' );

// Start up the WP testing environment
require "{$_tests_dir}/includes/bootstrap.php";

// Load test case classes
require_once __DIR__ . '/includes/TestCase.php';
