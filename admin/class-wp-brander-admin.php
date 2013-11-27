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
 
    private $settings_api;
 
    /**
     * Initialize the plugin by loading admin scripts & styles and adding a
     * settings page and menu.
     *
     * @since     1.0.0.1
     */
    private function __construct() {
             
            require_once(ABSPATH.'wp-admin/includes/plugin.php');
 
            $plugin = WP_Brander::get_instance();
 
            $this->settings_api = new Generate_Option( 'custom-menu_page_elephas-wp-brander' );
 
            $this->plugin_slug = $plugin->get_plugin_slug();
            $this->parent_slug = $plugin->get_parent_slug();
 
            
 
            // Add the options page and menu item.
            add_action( 'admin_menu', array( $this, 'add_plugin_admin_menu' ) );
 
 
 
            // Add an action link pointing to the options page.
            $plugin_basename = plugin_basename( plugin_dir_path( __DIR__ ) . 'wp-brander.php' );
            add_filter( 'plugin_action_links_' . $plugin_basename, array( $this, 'add_action_links' ) );
 
            //Action
            add_action( 'admin_init', array( $this, 'settings_api_init' ) );
 
            add_action('admin_enqueue_scripts', array( $this,'helper_pointers') );

            // Load admin style sheet and JavaScript.
            add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_styles' ) );
            add_action( 'admin_footer', array( $this, 'enqueue_admin_scripts' ) );
 
            //Filter
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
 
                    //Load Latest media manager if exists
                    if( !function_exists('wp_enqueue_media') && version_compare( self::get_wordpress_version(), '3.5', '<=' ) ) {
                        wp_enqueue_style('thickbox');
                    }
                     
 
                    wp_enqueue_style( 'wp-color-picker' );
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
            wp_enqueue_script('wp-color-picker');
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
                    }
 
                    wp_enqueue_script( $this->plugin_slug . '-admin-script', plugins_url( 'assets/js/wp-brander-admin.js', __FILE__ ), array( 'jquery', 'wp-pointer' ), WP_Brander::VERSION );
            }
 
    }
 
    /**
     * Register the administration menu for this plugin into the WordPress Dashboard menu.
     *
     * @since    1.0.0.1
     */
    public function add_plugin_admin_menu() {
 
            $topmenu_exists = $this->toplevel_menu_exists();
 
            if( !$topmenu_exists ){
 
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
 
            echo '<div class="wrap">';
 
            $this->settings_api->show_navigation();
            $this->settings_api->show_forms();
 
            echo '</div>';
 
            $seoboosterpropluginfo = get_plugin_data( WP_PLUGIN_DIR . '/wp-brander/wp-brander.php');
            $version = $seoboosterpropluginfo['Version'];
 
            echo $version;
             
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
     *
     * @since    1.0.0.1
     */
    public function settings_api_init() {
            //set the settings
            $this->settings_api->set_sections( $this->get_settings_sections() );
            $this->settings_api->set_fields( $this->get_settings_fields() );
 
            //initialize settings
            $this->settings_api->initialize();
    }
 
 
    function get_settings_sections() {
        $sections = array(
            array(
                'id' => 'wp_brander_favicons',
                'title' => __( 'Favicons', $this->plugin_slug )
            ),
            array(
                'id' => 'wp_brander_login',
                'title' => __( 'Login Screen', $this->plugin_slug )
            )
        );
        return $sections;
    }
 
    /**
     * Returns all the settings fields
     *
     * @return array settings fields
     */
    function get_settings_fields() {
        $settings_fields = array(
            'wp_brander_favicons' => array(
                array(
                    'name' => 'text_val',
                    'label' => __( 'Text Input (integer validation)', 'wedevs' ),
                    'desc' => __( 'Text input description', 'wedevs' ),
                    'type' => 'text',
                    'default' => 'Title',
                    'sanitize_callback' => 'intval'
                ),
                array(
                    'name' => 'textarea',
                    'label' => __( 'Textarea Input', 'wedevs' ),
                    'desc' => __( 'Textarea description', 'wedevs' ),
                    'type' => 'textarea'
                ),
                array(
                    'name' => 'checkbox',
                    'label' => __( 'Checkbox', 'wedevs' ),
                    'desc' => __( 'Checkbox Label', 'wedevs' ),
                    'type' => 'checkbox'
                ),
                array(
                    'name' => 'radio',
                    'label' => __( 'Radio Button', 'wedevs' ),
                    'desc' => __( 'A radio button', 'wedevs' ),
                    'type' => 'radio',
                    'options' => array(
                        'yes' => 'Yes',
                        'no' => 'No'
                    )
                ),
                array(
                    'name' => 'multicheck',
                    'label' => __( 'Multile checkbox', 'wedevs' ),
                    'desc' => __( 'Multi checkbox description', 'wedevs' ),
                    'type' => 'multicheck',
                    'options' => array(
                        'one' => 'One',
                        'two' => 'Two',
                        'three' => 'Three',
                        'four' => 'Four'
                    )
                ),
                array(
                    'name' => 'selectbox',
                    'label' => __( 'A Dropdown', 'wedevs' ),
                    'desc' => __( 'Dropdown description', 'wedevs' ),
                    'type' => 'select',
                    'default' => 'no',
                    'options' => array(
                        'yes' => 'Yes',
                        'no' => 'No'
                    )
                ),
                array(
                    'name' => 'password',
                    'label' => __( 'Password', 'wedevs' ),
                    'desc' => __( 'Password description', 'wedevs' ),
                    'type' => 'password',
                    'default' => ''
                ),
                array(
                    'name' => 'file',
                    'label' => __( 'File', 'wedevs' ),
                    'desc' => __( 'File description', 'wedevs' ),
                    'type' => 'file',
                    'default' => ''
                )
            ),
            'wp_brander_login' => array(
                array(
                    'name' => 'text',
                    'label' => __( 'Text Input', 'wedevs' ),
                    'desc' => __( 'Text input description', 'wedevs' ),
                    'type' => 'text',
                    'default' => 'Title'
                ),
                array(
                    'name' => 'textarea',
                    'label' => __( 'Textarea Input', 'wedevs' ),
                    'desc' => __( 'Textarea description', 'wedevs' ),
                    'type' => 'textarea'
                ),
                array(
                    'name' => 'checkbox',
                    'label' => __( 'Checkbox', 'wedevs' ),
                    'desc' => __( 'Checkbox Label', 'wedevs' ),
                    'type' => 'checkbox'
                ),
                array(
                    'name' => 'radio',
                    'label' => __( 'Radio Button', 'wedevs' ),
                    'desc' => __( 'A radio button', 'wedevs' ),
                    'type' => 'radio',
                    'default' => 'no',
                    'options' => array(
                        'yes' => 'Yes',
                        'no' => 'No'
                    )
                ),
                array(
                    'name' => 'multicheck',
                    'label' => __( 'Multile checkbox', 'wedevs' ),
                    'desc' => __( 'Multi checkbox description', 'wedevs' ),
                    'type' => 'multicheck',
                    'default' => array('one' => 'one', 'four' => 'four'),
                    'options' => array(
                        'one' => 'One',
                        'two' => 'Two',
                        'three' => 'Three',
                        'four' => 'Four'
                    )
                ),
                array(
                    'name' => 'selectbox',
                    'label' => __( 'A Dropdown', 'wedevs' ),
                    'desc' => __( 'Dropdown description', 'wedevs' ),
                    'type' => 'select',
                    'options' => array(
                        'yes' => 'Yes',
                        'no' => 'No'
                    )
                ),
                array(
                    'name' => 'password',
                    'label' => __( 'Password', 'wedevs' ),
                    'desc' => __( 'Password description', 'wedevs' ),
                    'type' => 'password',
                    'default' => ''
                ),
                array(
                    'name' => 'file',
                    'label' => __( 'File', 'wedevs' ),
                    'desc' => __( 'File description', 'wedevs' ),
                    'type' => 'file',
                    'default' => ''
                )
            )
        );
 
        return $settings_fields;
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
   protected function get_wordpress_version(){
 
            global $wp_version;
 
            return $wp_version;
 
    }
 
    /**
    * Check admin top level menu exists
    */
    protected function toplevel_menu_exists(){
 
            global $menu;
 
            $menu_exist = false;
 
            foreach($menu as $item) {
                if(strtolower($item[0]) == strtolower('Custom menu')) {
                    $menu_exist = true;
                }
            }
 
            return $menu_exist;
 
    }
 
    public function helper_pointers(){
 
        $pointers = array(
                        array(
                            'id' => '540',   // unique id for this pointer
                            'screen' => 'custom-menu_page_elephas-wp-brander', // this is the page hook we want our pointer to show on
                            'target' => '.media-uploader-field', // the css selector for the pointer to be tied to, best to use ID's
                            'title' => 'My ToolTip',
                            'content' => 'My tooltips Description',
                            'position' => array( 
                                               'edge' => 'top', //top, bottom, left, right
                                               'align' => 'middle' //top, bottom, left, right, middle
                                               )
                            ),
                        array(
                            'id' => '555',
                            'screen' => 'custom-menu_page_elephas-wp-brander', 
                            'target' => '#wp_brander_favicons-textarea',
                            'title' => 'Second Tooltip',
                            'content' => 'My tooltips Description',
                            'position' => array( 
                                               'edge' => 'top', //top, bottom, left, right
                                               'align' => 'middle' //top, bottom, left, right, middle
                                               )
                            )
                         );
       //Now we instantiate the class and pass our pointer array to the constructor 
       $myPointers = new WP_Help_Pointer($pointers);
       
    }
 
}