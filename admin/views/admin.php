<?php
/**
 * WordPress Brander.
 * Represents the view for the administration dashboard.
 *
 * This includes the header, options, and other information that should provide
 * The User Interface to the end user.
 *
 * @package   wp-brander
 * @author    Kamaal Aboothalib <kamaal@kamaal.me>
 * @license   GPL-2.0+
 * @link      http://kamaal.me/wp-brander
 * @copyright 2013 Kamaal Aboothalib
 */
?>

<div class="wrap">

        <?php screen_icon(); ?>
        <h2><?php echo esc_html( get_admin_page_title() ); ?></h2>
        <?php settings_errors(); ?>
        <h2 class="nav-tab-wrapper">  
            <a href="#" class="nav-tab">Favicons</a>  
            <a href="#" class="nav-tab">Login Logo</a>  
        </h2>

        <!-- @TODO: Provide markup for your options page here. -->

</div>