<?php
/**
 *
 * @package WP_Help_Pointer
 * @version 0.1
 * @author Tim Debo <tim@rawcreativestudios.com>
 * @copyright Copyright (c) 2012, Raw Creative Studios
 * @link https://github.com/rawcreative/wp-help-pointers
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 */

if (!class_exists('WP_Help_Pointer')) {
    
    class WP_Help_Pointer {
     
        public $screen_id;
        public $valid;
        public $pointers;
        
        public function __construct( $pntrs = array() ) {
           
            // Don't run on WP < 3.3
            if ( get_bloginfo( 'version' ) < '3.3' )
                return;
        

        
            $screen = get_current_screen();
            $this->screen_id = $screen->id;
           
            $this->register_pointers($pntrs);
     
            add_action( 'admin_enqueue_scripts', array( &$this, 'add_pointers' ), 1000 );
           
            //add_action( 'admin_footer', array( &$this, 'add_scripts' ) );
        }
     
        public function register_pointers( $pntrs ) {
     
            $pointers = $this->pointers;
            
            foreach( $pntrs as $ptr ) {

                $clese_txt = array_key_exists( 'close_btn', $ptr )  ?  $ptr['close_btn'] : 'Close';
                     
                if( $ptr['screen'] == $this->screen_id ) {
                    
                    $pointers[$ptr['id']] = array(
                        'screen' => $ptr['screen'],
                        'target' => $ptr['target'],
                        'closeBtn' => $clese_txt,
                        'options' => array(
                            'content' => sprintf( '<h3> %s </h3> <p> %s </p>',
                                __( $ptr['title'] , 'plugindomain' ),
                                __( $ptr['content'], 'plugindomain' )
                            ),
                            'position' => $ptr['position']
                        )
                    );
                     
                }
            }
     
            $this->pointers = $pointers;
     
        }
     
        public function add_pointers() {
                    
            $pointers = $this->pointers;
     
            if ( ! $pointers || ! is_array( $pointers ) )
                return;
            
            // Get dismissed pointers
            $dismissed = explode( ',', (string) get_user_meta( get_current_user_id(), 'dismissed_wp_pointers', true ) );
            $valid_pointers = array();
     
            // Check pointers and remove dismissed ones.
            foreach ( $pointers as $pointer_id => $pointer ) {
     
                // Make sure we have pointers & check if they have been dismissed
                if ( in_array( $pointer_id, $dismissed ) || empty( $pointer )  || empty( $pointer_id ) || empty( $pointer['target'] ) || empty( $pointer['options'] ) )
                    continue;
     
                $pointer['pointer_id'] = $pointer_id;
     
                // Add the pointer to $valid_pointers array
                $valid_pointers['pointers'][] =  $pointer;
            }
     
            // No valid pointers? Stop here.
            if ( empty( $valid_pointers ) )
                return;
     
            $this->valid = $valid_pointers;
     
            wp_enqueue_style( 'wp-pointer' );
            wp_enqueue_script( 'wp-pointer' );
            wp_localize_script( 'jquery', 'alpha', $this->_gen_pionter_object() );
        }
        
        private function _gen_pionter_object() {

            $pointers = $this->valid;
           
            if( empty( $pointers ) ) 
                return;
     
            //$pointers = json_encode( $pointers );
            
            return array( 
                'pointers' => $pointers,
                'ajaxurl' => admin_url('admin-ajax.php')
            );
             
        }
     
    } // end class
} //END if