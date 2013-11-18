<?php
/**
 * WordPress Brander.
 *
 * @package   wp-brander
 * @author    Kamaal Aboothalib <kamaal@kamaal.me>
 * @license   GPL-2.0+
 * @link      http://kamaal.me/wp-brander
 * @copyright 2013 Kamaal Aboothalib
 */

/**
 * Singleton class. 
 * administrative side of the WordPress site.
 *
 *
 * @package wp-brander-admin
 * @author  Kamaal Aboothalib <kamaal@kamaal.me>
 */
class Wordpress_Brander_Admin{

	/**
     * Instance of this class.
     *
     * @since    1.0.0.1
     *
     * @var      object
     */
    protected static $instance = null;

    /**
     * Slug of the plugin screen.
     *
     * @since    1.0.0.1
     *
     * @var      string
     */
    protected $plugin_screen_hook_suffix = null;


    /**
     * Initialize the plugin by loading admin scripts & styles and adding a
     * settings page and menu.
     *
     * @since     1.0.0.1
     */
    private function __construct() {

    }

	public static function get_instance(){

		// If the single instance hasn't been set, set it now.
        if ( null == self::$instance ) {
                self::$instance = new self;
        }

        return self::$instance;

	}

}