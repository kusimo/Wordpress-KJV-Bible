<?php
/* Create book passage table */
class BibeliPassageData {

    public $bibeli_db_version;
    public $version = '1.0';

   public function __constructor() {
    //register_activation_hook( PLUGIN_DIR,  [ $this, 'bibeli_install_passage' ] );
   
    }

    public function bibeli_install_passage () {
        global $wpdb;
        $this->bibeli_db_version = $this->version;
     
        $table_name = $wpdb->prefix . "bibeli_book"; 
        $charset_collate = $wpdb->get_charset_collate();

        $sql = "CREATE TABLE IF NOT EXISTS $table_name (
            `bible_number` int(11) NOT NULL,
            `short` char(4) CHARACTER SET latin1 NOT NULL DEFAULT '',
            `fullname` varchar(30) CHARACTER SET latin1 NOT NULL DEFAULT '',
            `title` text CHARACTER SET latin1 NOT NULL,
            `chapters` int(11) NOT NULL,
            PRIMARY KEY (`bible_number`)
           ) $charset_collate;"; 
           
            require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
            dbDelta( $sql );

            add_option( 'bibeli_book_db_version', $this->bibeli_db_version );

     }

     public function bibeli_install_data_bibeli() {
        global $wpdb;

        $table_name = $wpdb->prefix . "bibeli_book";
        $rowcount = $wpdb->get_var("SELECT COUNT(*) FROM $table_name");

        if($rowcount < 1)  {
        
        require_once( PLUGIN_PATH . 'bible_data/BibeliPassageData.php' );
            foreach($book_passage as $bible) {
                $wpdb->insert (
                    $table_name, $bible
                );
            } //endforeach
        }
    }
}