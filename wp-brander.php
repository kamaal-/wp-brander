<?php
/**
 * ﷽ 
 * WordPress Brander.
 *
 * A Wordpress plugin for custom favicon & custom admin login/register logo.
 *
 * @package   wp-brander
 * @author    Kamaal Aboothalib <kamaal@kamaal.me>
 * @license   GPL-2.0+
 * @link      http://kamaal.me/wp-brander
 * @copyright 2013 Kamaal Aboothalib
 *
 * @wordpress-plugin
 * Plugin Name:       WordPress Brander
 * Plugin URI:        http://kamaal.me/wp-brander
 * Description:       A Wordpress plugin for custom favicon & custom admin login/register logo.
 * Version:           1.0.0.1
 * Author:            Kamaal Aboothalib
 * Author URI:        http://kamaal.me/
 * Text Domain:       elephas-wp-brander
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Domain Path:       /languages
 * GitHub Plugin URI: https://github.com/kamaal-/wp-brander
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {

        die;

}

require_once( plugin_dir_path( __FILE__ ) . 'public/class-wp-brander.php' );

add_action( 'plugins_loaded', array( 'WP_Brander', 'get_instance' ) );
/*
 * The code below is intended to to give the lightest footprint possible.
 */
if ( is_admin() ) {

		require_once( plugin_dir_path( __FILE__ ) . 'admin/inc/class-wp-brander-settings.php' );
        require_once( plugin_dir_path( __FILE__ ) . 'admin/class-wp-brander-admin.php' );

        add_action( 'plugins_loaded', array( 'Wordpress_Brander_Admin', 'get_instance' ) );

}


/*----------------------------------------------------------------------------*
 * Dashboard and Administrative Functionality
 *----------------------------------------------------------------------------*/



/*----------------------------------------------------------------------------*
 * Activation hook
 *----------------------------------------------------------------------------*/
register_activation_hook( __FILE__, 'myplugin_activate' );

function myplugin_activate(){
	
}