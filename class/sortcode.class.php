<?php if ( ! defined( 'WPINC' ) ) {
    die;
}

class Bibeli
{
    public $book;
    public $chapter;
    public $verse;
    public $text;
   
    
    public function __construct()
    {
        add_shortcode('bibeli', array($this, 'bbVerse'));
    }


    public function bbVerse($attr, $content = null)
    {
        extract(shortcode_atts(array(
            'book' => '',
            'chapter' => '',
            'verse' => '',
            'text' => '',
            'caption' => true,
        ), $atts));

        $this->chapter = $attr['chapter'] ;
        $this->verse = $attr['verse'] ;
        $this->book = $attr['book'] ;

        if(strpos($this->verse,'-')) {
            $verse_number = explode('-', $this->verse);
        $verse_number = range($verse_number[0], $verse_number[1]);
        } else {
            $verse_number = array($this->verse);
        }
        
        $output = '';
        $query__output = '';

        $unique_id = $this->chapter.'-'.$this->verse.'-'.$this->book;
        $unique_id = sanitize_title_with_dashes($unique_id);

        if ( false === ( $bibeli_query_results = get_transient( 'bibeli_'.$unique_id ) ) ) {

        global $wpdb;
        $bible_chapter;
        $bible_name;

        $bible_passage;
        $bible_passage_number;
        $total_chapters;
       


        $table_name1 = $wpdb->prefix . "bibeli"; 
        $table_name2 = $wpdb->prefix . "bibeli_book"; 
        $table_search_value = $wpdb->prefix . "bibeli_book.fullname";


        $passage = $wpdb->get_results("SELECT fullname, title, chapters, bible_number FROM wp_bibeli_book WHERE 

        $table_search_value LIKE '".$attr['book']."%'  LIMIT 1 ");
        if($passage) {
            foreach($passage as $p) {
            $bible_passage = $p->bible_number;
            $total_chapters = $p->chapters;
            $bible_name = $p->fullname;
           
            }

        } else {
            $output .= '<p>No book of the bible find.</p>';
        }
    
       
        
    
        foreach($verse_number as $v) {
            $result = $wpdb->get_results("SELECT bible_text, bible_verse, fullname, bible_number, chapter, book FROM $table_name1, $table_name2 WHERE 
            wp_bibeli.book = $bible_passage AND wp_bibeli.bible_verse = $v AND chapter = $this->chapter LIMIT 1 ");

            if($result) {
               foreach($result as $b) {
                  //f($verse_number[1] <= $total_chapters) {
                   $output.='<p class="verse"><b>'.$v.'</b> '.$b->bible_text.'</p>';
                   $bible_chapter = $b->chapter;
                   //}
                }
                   
            } else {
                
                $output .='<p>Please check the chapter and verse numbers are correct.</p>';
                break;
            }
        }
     
        $query__output = '<div class="bibeli-chapter">
        <!--bibeli_query_results_'.$bible_passage.'-'.$unique_id.' --->
        <a class="bibeli-toggle" href="javascript:void(0);">'.$bible_name. ' '.$bible_chapter.':'.$this->verse.'</a>
        <div class="bibeli-inner" style="display: none;">
        ' . $output . ' <span class="bibeli-copyright">Crown Copyright UK. KJV</span>
        </div>
        </div>';
        set_transient( 'bibeli_'.$unique_id , $query__output, 60 * 43800 );
            
        return $query__output;

       
    } else {
        $query__output = $bibeli_query_results;
        return $query__output;
    }

    }
}