<?php
    
    /*
        All the functions are in the PHP pages in the `functions/` folder.
    */

    require get_template_directory() . '/functions/cleanup.php';
    require get_template_directory() . '/functions/setup.php';
    require get_template_directory() . '/functions/enqueues.php';
    require get_template_directory() . '/functions/widgets.php';
    require get_template_directory() . '/functions/search-widget.php';
    require get_template_directory() . '/functions/index-pagination.php';
    require get_template_directory() . '/functions/split-post-pagination.php';
    require get_template_directory() . '/functions/feedback.php';
    require get_template_directory() . '/functions/remove-query-string.php';
    require get_template_directory() . '/functions/remove-comments.php';

    // Register Custom Navigation Walker
    require get_template_directory() . '/functions/wp_bootstrap_pagination.php';

    // Register Custom Navigation Walker
    require get_template_directory() . '/functions/bs4navwalker.php';

    add_action('wp_default_scripts', function ($scripts) {
        if (!empty($scripts->registered['jquery'])) {
            $scripts->registered['jquery']->deps = array_diff($scripts->registered['jquery']->deps, ['jquery-migrate']);
        }
    });

    //
    // Menu register
    //
    function register_my_menus() {
        register_nav_menus(
            array(
                'main-menu'  =>  __( 'Main menu' )
            )
        );
    }
    add_action( 'init', 'register_my_menus' );

    //
    // Remove admin bar
    // 
    add_filter('show_admin_bar', '__return_false');

    //
    // Homepage page ID
    //

    function getFrontPageID() {
        $frontpage_id = get_option( 'page_on_front' );
        return $frontpage_id;
    }

    //
    //
    function has_children() {
      global $post;
      
      $pages = get_pages('child_of=' . $post->ID);
      
      return count($pages);
    }
    function is_top_level() {
      global $post, $wpdb;
      
      $current_page = $wpdb->get_var("SELECT post_parent FROM $wpdb->posts WHERE ID = " . $post->ID);
      
      return $current_page;
    }


    //
    //
    // Category Template as Parent (if exist!)

    function load_cat_parent_template($template) {

        $cat_ID = absint( get_query_var('cat') );
        $category = get_category( $cat_ID );

        $templates = array();

        if ( !is_wp_error($category) )
            $templates[] = "category-{$category->slug}.php";

        $templates[] = "category-$cat_ID.php";

        if ( !is_wp_error($category) ) {
            $category = $category->parent ? get_category($category->parent) : '';

            if( !empty($category) ) {
                if ( !is_wp_error($category) )
                    $templates[] = "category-{$category->slug}.php";

                $templates[] = "category-{$category->term_id}.php";
            }
        }

        $templates[] = "category.php";
        $template = locate_template($templates);

        return $template;
    }
    add_action('category_template', 'load_cat_parent_template');

    //
    //
    // Single Category Template (Post)

    add_filter('single_template', 'single_category_template');
        function single_category_template( $t ) {
        
        foreach( (array) get_the_category() as $cat ) { 
            
            if ( file_exists(TEMPLATEPATH . "/single-category-{$cat->slug}.php") ) return TEMPLATEPATH . "/single-category-{$cat->slug}.php"; 

            if($cat->parent) {
            
            $cat = get_the_category_by_ID( $cat->parent );
            
            if ( file_exists(TEMPLATEPATH . "/single-category-{$cat->slug}.php") ) return TEMPLATEPATH . "/single-category-{$cat->slug}.php";
            }
        }

        return $t;
    }

    //
    //
    // Add Category Title to Body Class

    add_filter('body_class','add_category_to_single');
    function add_category_to_single($classes) {
    if (is_single() ) {
      global $post;
      foreach((get_the_category($post->ID)) as $category) {
        $classes[] = $category->category_nicename;
      }
    }
    // return the $classes array
    return $classes;
    }

    // Check Child of Category
    function category_has_parent($catid){
        $category = get_category($catid);
        if ($category->category_parent > 0){
            return true;
        }
        return false;
    }

    // Custom Archive Order
    add_action( 'pre_get_posts', 'sort_order'); 
    function sort_order($query){
        if(is_archive()):
           $query->set( 'order', 'ASC' );
           $query->set( 'orderby', 'title' );
        endif;
    };

    /*
    * Define a constant path to our single template folder
    */
    define(SINGLE_PATH, TEMPLATEPATH . '/single');
 
    /**
    * Filter the single_template with our custom function
    */
    add_filter('single_template', 'my_single_template');
 
    /**
    * Single template function which will choose our template
    */
    function my_single_template($single) {
        global $wp_query, $post;
        
        /**
        * Checks for single template by category
        * Check by category slug and ID
        */
        foreach((array)get_the_category() as $cat) :
        
        if(file_exists(SINGLE_PATH . '/single-cat-' . $cat->slug . '.php'))
        return SINGLE_PATH . '/single-cat-' . $cat->slug . '.php';
        
        elseif(file_exists(SINGLE_PATH . '/single-cat-' . $cat->term_id . '.php'))
        return SINGLE_PATH . '/single-cat-' . $cat->term_id . '.php';
        
        endforeach;
    }

    /**
     * Disable the emoji's
     */
    function disable_emojis() {
        remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
        remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
        remove_action( 'wp_print_styles', 'print_emoji_styles' );
        remove_action( 'admin_print_styles', 'print_emoji_styles' );	
        remove_filter( 'the_content_feed', 'wp_staticize_emoji' );
        remove_filter( 'comment_text_rss', 'wp_staticize_emoji' );	
        remove_filter( 'wp_mail', 'wp_staticize_emoji_for_email' );
        
        // Remove from TinyMCE
        add_filter( 'tiny_mce_plugins', 'disable_emojis_tinymce' );
    }
    add_action( 'init', 'disable_emojis' );