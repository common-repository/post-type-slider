<?php
/**
 * Plugin Name: Post Type Slider
 * Plugin URI:  https://www.primisdigital.com/wordpress-plugins/
 * Description: Making fullwidth and carousil slider from post type
 * Version:     1.1
 * Author:      Primis Digital
 * Author URI:  https://www.primisdigital.com/
 * License:     GPL2
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: 
 * Domain Path: 
 */

// Language Directory
load_plugin_textdomain('post-type-slider', false, dirname(plugin_basename(__FILE__)) . '/languages/');

// Some constant defintion
define("PTS_DIR", plugin_dir_path(__FILE__), FALSE);
define("PTS_DIR_URL", plugin_dir_url(__FILE__), FALSE);

// image url constant 
define("PTS_IMG_DIR", PTS_DIR. 'assets\img\\' );
define("PTS_IMG_URL", PTS_DIR_URL. 'assets/img/' ); 

// js url constant 
define("PTS_JS_DIR", PTS_DIR. 'assets\js\\' );
define("PTS_JS_URL", PTS_DIR_URL. 'assets/js/' ); 

// css url constant 
define("PTS_CSS_DIR", PTS_DIR. 'assets\css\\' );
define("PTS_CSS_URL", PTS_DIR_URL. 'assets/css/' ); 

//Plugin Activation code
register_activation_hook(__FILE__, 'pts_activation');
function pts_activation()
{
}
// adding style sheet in admin area
add_action('admin_enqueue_scripts', function()
{
	wp_enqueue_style( 'myscript', PTS_CSS_URL.'/myscript.css');
});

// Register Deactivation Hook here
register_deactivation_hook(__FILE__, 'pts_deactivation');
function pts_deactivation()
{
}
// Register Uninstall Hook here
register_uninstall_hook(__FILE__, 'pts_uninstall');

function pts_uninstall()
{
}
// Plugin post type loading : Post Type Slider
include("lib/pts-settings.php");