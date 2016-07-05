<?php
// Show Errors
ini_set('log_errors', true);
ini_set('error_log', dirname(__FILE__).'/errors.log');
/**
 *
 * @link              https://pintopsolutions.com
 * @since             1.0
 * @package           Link_Timestamp
 *
 * @wordpress-plugin
 * Plugin Name:       Link Timestamp
 * Plugin URI:        https://pintopsolutions.com
 * Description:       This is a short description of what the plugin does. It's displayed in the WordPress admin area.
 * Version:           1.0
 * Author:            Arelthia Phillips
 * Author URI:        https://pintopsolutions.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       link-timestamp
 * Domain Path:       /languages
 */


if ( ! defined( 'WPINC' ) ) {
	die;
}
define('LINK_TIMESTAMP_NAME', 'link-timestamp');
define('LINK_TIMESTAMP_VERSION', '1.0');


function activate_link_timestamp() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-link-timestamp-activator.php';
	Link_Timestamp_Activator::activate();
}
register_activation_hook( __FILE__, 'activate_link_timestamp' );


require plugin_dir_path( __FILE__ ) . 'includes/class-link-timestamp.php';

/**
 * Begins execution of the plugin.
 *
 * @since    1.0
 */
function run_link_timestamp() {

	$plugin = new Link_Timestamp();
	$plugin->run();

}
run_link_timestamp();
