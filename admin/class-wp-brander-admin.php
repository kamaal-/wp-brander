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
	        
	        $plugin = WP_Brander::get_instance();
	        $this->plugin_slug = $plugin->get_plugin_slug();
	        $this->parent_slug = $plugin->get_parent_slug();

	        // Load admin style sheet and JavaScript.
	        add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_styles' ) );
	        add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_scripts' ) );

	        // Add the options page and menu item.
	        add_action( 'admin_menu', array( $this, 'add_plugin_admin_menu' ) );

	        // Add an action link pointing to the options page.
	        $plugin_basename = plugin_basename( plugin_dir_path( __DIR__ ) . 'wp-brander.php' );
	        add_filter( 'plugin_action_links_' . $plugin_basename, array( $this, 'add_action_links' ) );

	        /*
	         * Define custom functionality.
	         *
	         * Read more about actions and filters:
	         * http://codex.wordpress.org/Plugin_API#Hooks.2C_Actions_and_Filters
	         */
	        add_action( '@TODO', array( $this, 'action_method_name' ) );
	        add_filter( '@TODO', array( $this, 'filter_method_name' ) );

    }

    /**
     * Return an instance of this class.
     *
     * @since     1.0.0.1
     *
     * @return    object    A single instance of this class.
     */
    public static function get_instance() {

	        /*
	         *
	         * - Uncomment following lines if the admin class should only be available for super admins
	         */
	        /* if( ! is_super_admin() ) {
	                return;
	        } */

	        // If the single instance hasn't been set, set it now.
	        if ( null == self::$instance ) {
	                self::$instance = new self;
	        }

	        return self::$instance;
    }

    /**
     * Register and enqueue admin-specific style sheet.
     *
     *
     * @since     1.0.0.1
     *
     * @return    null    Return early if no settings page is registered.
     */
    public function enqueue_admin_styles() {

            if ( ! isset( $this->plugin_screen_hook_suffix ) ) {
                    return;
            }

            $screen = get_current_screen();
            if ( $this->plugin_screen_hook_suffix == $screen->id ) {
                    wp_enqueue_style( $this->plugin_slug .'-admin-styles', plugins_url( 'assets/css/wp-brander-admin.css', __FILE__ ), array(), WP_Brander::VERSION );
            }

    }

    /**
     * Register and enqueue admin-specific JavaScript.
     *
     *
     * @since     1.0.0.1
     *
     * @return    null    Return early if no settings page is registered.
     */
    public function enqueue_admin_scripts() {

            if ( ! isset( $this->plugin_screen_hook_suffix ) ) {
                    return;
            }

            $screen = get_current_screen();
            if ( $this->plugin_screen_hook_suffix == $screen->id ) {

            		//Load Latest media manager if exists
            		if( function_exists('wp_enqueue_media') && version_compare( self::get_wordpress_version(), '3.5', '>=' ) ) {
				        //call for new media manager
				        wp_enqueue_media();
				    }
				    //Or old WP < 3.5
				    else {
				        wp_enqueue_script('media-upload');
				        wp_enqueue_script('thickbox');
				        wp_enqueue_style('thickbox');
				    }

                    wp_enqueue_script( $this->plugin_slug . '-admin-script', plugins_url( 'assets/js/wp-brander-admin.js', __FILE__ ), array( 'jquery' ), WP_Brander::VERSION );
            }

    }

    /**
     * Register the administration menu for this plugin into the WordPress Dashboard menu.
     *
     * @since    1.0.0.1
     */
    public function add_plugin_admin_menu() {

    		$topmen_exists = $this->toplevel_menu_exists();

    		if(!$topmen_exists){

    			add_menu_page(
	                    __( 'Custom Settings', $this->plugin_slug ),
	                    __( 'Custom menu', $this->parent_slug ),
	                    'remove_users',
	                    $this->parent_slug,
	                    array( $this, 'display_plugin_admin_page' )
	            );

    			$this->plugin_screen_hook_suffix = add_submenu_page( 
    					$this->parent_slug , 
    					'Wordpress Brander Settings', 
    					'Wordpress Brander', 
    					'remove_users', 
    					$this->plugin_slug, 
    					array( $this, 'display_plugin_admin_page' ) );

    		}else{

    			$this->plugin_screen_hook_suffix = add_submenu_page( 
    					$this->parent_slug , 
    					'Wordpress Brander Settings', 
    					'Wordpress Brander', 
    					'remove_users', 
    					$this->plugin_slug, 
    					array( $this, 'display_plugin_admin_page' ) );

    		}
            


    }

    /**
     * Render the settings page for this plugin.
     *
     * @since    1.0.0.1
     */
    public function display_plugin_admin_page() {

        	include_once( 'views/admin.php' );
    
    }

    /**
     * Add settings action link to the plugins page.
     *
     * @since    1.0.0
     */
    public function add_action_links( $links ) {

            return array_merge(
                    array(
                            'settings' => '<a href="' . admin_url( 'options-general.php?page=' . $this->plugin_slug ) . '">' . __( 'Settings', $this->plugin_slug ) . '</a>'
                    ),
                    $links
            );

    }

    /**
     * NOTE:     Actions are points in the execution of a page or process
     *           lifecycle that WordPress fires.
     *
     *           Actions:    http://codex.wordpress.org/Plugin_API#Actions
     *           Reference:  http://codex.wordpress.org/Plugin_API/Action_Reference
     *
     * @since    1.0.0.1
     */
    public function action_method_name() {
            // @TODO: Define your action hook callback here
    }

    /**
     * NOTE:     Filters are points of execution in which WordPress modifies data
     *           before saving it or sending it to the browser.
     *
     *           Filters: http://codex.wordpress.org/Plugin_API#Filters
     *           Reference:  http://codex.wordpress.org/Plugin_API/Filter_Reference
     *
     * @since    1.0.0.1
     */
    public function filter_method_name() {
            // @TODO: Define your filter hook callback here
    }
    /**
	* Gettin wordpress version
    */
    private function get_wordpress_version(){
	    	global $wp_version;
	   		return $wp_version;
    }

    /**
	* Check admin top level menu exists
    */
    private function toplevel_menu_exists(){
	    	global $menu;
			$menu_exist = false;
			foreach($menu as $item) {
			    if(strtolower($item[0]) == strtolower('Custom menu')) {
			        $menu_exist = true;
			    }
			}
			return $menu_exist;
    }

}