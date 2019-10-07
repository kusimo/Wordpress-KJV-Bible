<?php if ( ! defined( 'WPINC' ) ) {
    die;
}

// The widget class
class Bibeli_Widget extends WP_Widget {
	// Main constructor
	public function __construct() {
		parent::__construct(
			'Bibeli_Widget',
			__( 'Bibeli Widget', 'bibeli' ),
			array(
				'customize_selective_refresh' => true,
			)
		);
	}
	// The widget form (for the backend )
	public function form( $instance ) {
		// Set widget defaults
		$defaults = array(
			'title'    => '',
			'text'     => '',
			'textarea' => '',
		);
		
		// Parse current settings with defaults
        extract( wp_parse_args( ( array ) $instance, $defaults ) ); ?>

        <?php // Widget Title ?>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php _e( 'Title:', 'bibeli' ); ?></label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
		</p>

        
        <?php // Text Field ?>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'book' ) ); ?>"><?php _e( 'Book:', 'bibeli' ); ?></label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'book' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'book' ) ); ?>" type="text" value="<?php echo esc_attr( $book ); ?>" placeholder="John" />
		</p>

		
		<?php // Text Field ?>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'chapter' ) ); ?>"><?php _e( 'Chapter:', 'bibeli' ); ?></label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'chapter' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'chapter' ) ); ?>" type="text" value="<?php echo esc_attr( $chapter ); ?>" placeholder="3" />
		</p>

		<?php // Text Field ?>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'verse' ) ); ?>"><?php _e( 'Verse:', 'bibeli' ); ?></label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'verse' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'verse' ) ); ?>" type="text" value="<?php echo esc_attr( $verse ); ?>" placeholder="16-19" />
		</p>


	<?php }
	// Update widget settings
	public function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title']    = isset( $new_instance['title'] ) ? wp_strip_all_tags( $new_instance['title'] ) : '';
        $instance['book']     = isset( $new_instance['book'] ) ? wp_strip_all_tags( $new_instance['book'] ) : '';
        $instance['chapter']     = isset( $new_instance['chapter'] ) ? wp_strip_all_tags( $new_instance['chapter'] ) : '';
        $instance['verse']     = isset( $new_instance['verse'] ) ? wp_strip_all_tags( $new_instance['verse'] ) : '';
		
		return $instance;
	}
	// Display the widget
	public function widget( $args, $instance ) {
		extract( $args );
		// Check the widget options
		$title    = isset( $instance['title'] ) ? apply_filters( 'widget_title', $instance['title'] ) : '';
        $book     = isset( $instance['book'] ) ? $instance['book'] : '';
        $chapter     = isset( $instance['chapter'] ) ? $instance['chapter'] : '';
        $verse     = isset( $instance['verse'] ) ? $instance['verse'] : '';
		
		// WordPress core before_widget hook (always include )
		echo $before_widget;
        // Display the widget
        
		echo '<div class="widget-text wp-bibeli-widget-container">';
            // Display widget title if defined
            $output1 = '';
            $output = '';
            $total_chapters;
            $verse_number;
            $bible_passage;
            $bible_name;

            if($verse) {
                if(strpos($verse,'-')) {
                    $verse_number = explode('-', $verse);
                $verse_number = range($verse_number[0], $verse_number[1]);
                } else {
                    $verse_number = array($verse);
                }
            }
            
            if($book) {
        
                global $wpdb;
               
                $table_name1 = $wpdb->prefix . "bibeli"; 
                $table_name2 = $wpdb->prefix . "bibeli_book"; 
                $table_search_value = $wpdb->prefix . "bibeli_book.fullname";
        
                $passage = $wpdb->get_results("SELECT fullname, title, chapters, bible_number FROM wp_bibeli_book WHERE 
                $table_search_value LIKE '".$book."%'  LIMIT 1 ");
                
                if($passage) {
                    foreach($passage as $p) {
                    $bible_passage = $p->bible_number;
                    $total_chapters = $p->chapters;
                    $bible_name = $p->fullname;
                    $output1 .= $bible_passage;
                    }
        
                } else {
                    $output1 = 'no result';
                }
               

                foreach($verse_number as $v) {
                    $result = $wpdb->get_results("SELECT bible_text, bible_verse, fullname, bible_number, chapter, book FROM $table_name1, $table_name2 WHERE 
                    wp_bibeli.book = $bible_passage AND wp_bibeli.bible_verse = $v AND chapter = $chapter LIMIT 1 ");
        
                    if($result) { 
                       
                       foreach($result as $b) {                          
                          //f($verse_number[1] <= $total_chapters) {
                           $output .='<p class="verse"><b>'.$v.'</b> '.$b->bible_text.'</p>';
                           $bible_chapter = $b->chapter;
                           //}
                        }
                           
                    }else {
                        
                        $output ='No result. Please try  using different search.';
                        break;
                    }
                }
           

            }
			if ( $title ) {
				echo $before_title . $title . $after_title;
			}
			// Display text field
            if('' != $book && '' != $chapter && '' != $verse ) {
                echo '<div class="bibeli-widget-area">';
                echo '<div class="bibeli-book">';
                echo '<p class="passage">'.$bible_name. ' '.$bible_chapter.':'.$verse.'</p>';
                echo $output;     
                echo '</div>';
                echo '<div><span class="bibeli-copyright">Crown Copyright UK. KJV</span></div>';
                echo '</div>';
            }

              
			
		echo '</div>';
		// WordPress core after_widget hook (always include )
		echo $after_widget;
	}
}