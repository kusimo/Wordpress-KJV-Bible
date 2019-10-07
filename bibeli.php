<?php
/* 
Plugin Name: Bibeli Bible
Author: Abidemi Kusimo
Text Domain: bibeli
Version: 1.0
License: GPLv2
Author URI: http://basichow.com
Description: Bibeli Bible is a KJV of the bible in English. Output bible shortcode by chapter and verse numbers. Example [bibeli book="psalm" chapter="91" verse="1-3"][/bibeli]
*/


// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
    die;
}

define( 'PLUGIN_DIR', __FILE__ );
define( 'PLUGIN_PATH', plugin_dir_path( __FILE__ ) );
define('BIBELI_CORE_CSS',plugins_url( 'assets/css/', __FILE__ ));
define('BIBELI_CORE_JS',plugins_url( 'assets/js/', __FILE__ ));

// loading step
require_once('class/sortcode.class.php');
require_once( 'class/kjvinstall.class.php');
require_once( 'class/kjvinstallpassage.class.php');
require_once( 'class/bibeliwidget.class.php');
require_once( 'core-init.php');

$bsBible = new Bibeli( __FILE__ );
$bsBibelidata = new BibeliDatabase( __FILE__ );
$bsBibelipassage = new BibeliPassageData( __FILE__ );

// execution step
$bsBible = new Bibeli;
$bsBibelidata = new BibeliDatabase;
$bsBibelipassage = new BibeliPassageData;


register_activation_hook( __FILE__, array( $bsBibelidata, 'bibeli_install' ) );
register_activation_hook(__FILE__, array($bsBibelidata,'bibeli_install_data'));
register_activation_hook( __FILE__, array( $bsBibelipassage, 'bibeli_install_passage' ) );
register_activation_hook(__FILE__, array($bsBibelipassage,'bibeli_install_data_bibeli'));

//delete transient on deactivation 
register_deactivation_hook( __FILE__, 'bibeli_delete_transient' );
function bibeli_delete_transient() {
    global $wpdb;
    $wpdb->query( "DELETE FROM `wp_options` WHERE `option_name` LIKE ('_transient%_bibeli_%')" );

}




