<?php
class BibeliDatabase {

    public $bibeli_db_version;
    public $version = '1.0';

   public function __constructor() {
    //register_activation_hook(__DIR__.'/bibeli.php',array($this,'bibeli_install'));
   
    }

    public function bibeli_install () {
        global $wpdb;
        $this->bibeli_db_version = $this->version;
     
        $table_name = $wpdb->prefix . "bibeli"; 
        $charset_collate = $wpdb->get_charset_collate();

        
        $sql = "CREATE TABLE IF NOT EXISTS $table_name (
            `index` int(11) NOT NULL DEFAULT '0',
            `book` int(2) NOT NULL DEFAULT '0',
            `chapter` int(3) NOT NULL DEFAULT '0',
            `bible_verse` int(3) NOT NULL DEFAULT '0',
            `bible_text` text CHARACTER SET latin1 NOT NULL,
            KEY `index` (`index`),
            KEY `book` (`book`),
            KEY `chapter` (`chapter`),
            KEY `verse` (`bible_verse`)
           )  $charset_collate;"; 

            require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
            dbDelta( $sql );

            add_option( 'bibeli_db_version', $this->bibeli_db_version );

     }

     public function bibeli_install_data() {
        global $wpdb;
        
        $table_name = $wpdb->prefix . 'bibeli';

        $rowcount = $wpdb->get_var("SELECT COUNT(*) FROM $table_name");
        if($rowcount < 1) {
            require_once( PLUGIN_PATH . 'bible_data/BibeliKJV.php' );

            foreach($bible_kjv as $bible) {
                $wpdb->insert (
                    $table_name, $bible
                );
            } //endforeach
        }

    }
}