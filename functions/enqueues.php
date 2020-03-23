<?php

function wordpress_enqueues() {
	$wp_admin = is_admin();
    $wp_customizer = is_customize_preview();
	// Custom Fonts
	//wp_register_style('font-awesome', 'https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css');
	//wp_enqueue_style('font-awesome');

	// Slick CSS
	wp_register_style('slick-theme', 'https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.9.0/slick-theme.css');
	wp_enqueue_style('slick-theme');
	wp_register_style('slick', 'https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.9.0/slick.min.css');
	wp_enqueue_style('slick');

	// iCheck
	// wp_register_style('icheck-styles-all', get_template_directory_uri() . '/js/icheck/skins/all.css');
	// wp_enqueue_style('icheck-styles-all');

	// Fancybox
	wp_register_style('fancybox', get_template_directory_uri() . '/js/fancybox/jquery.fancybox.min.css', false, 'v3.0.38', null);
	wp_enqueue_style('fancybox');

	// Theme SASS Compiled to CSS
	wp_register_style('theme-styles', get_template_directory_uri() . '/css/styles.css');
	wp_enqueue_style('theme-styles');

	/* JS Scripts */

	// jQuery
    if ( $wp_admin || $wp_customizer ) {
        // echo 'We are in the WP Admin or in the WP Customizer';
        return;
    }
    else {
        wp_deregister_script( 'jquery' );
        // Deregister WP jQuery
        wp_deregister_script( 'jquery-core' );
        // Deregister WP jQuery Migrate
        wp_deregister_script( 'jquery-migrate' );
        // Register jQuery in the head
        wp_register_script( 'jquery-core', 'https://code.jquery.com/jquery-3.3.1.js', array(), null, false );
        wp_register_script( 'jquery', false, array( 'jquery-core' ), null, false );
		wp_enqueue_script( 'jquery' );
		// Popper
		wp_register_script('popper', 'https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js');
		wp_enqueue_script('popper');
		// Bootstrap Core JS
		wp_register_script('bootstrap-js', 'https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js');
		wp_enqueue_script('bootstrap-js');
		// Slick
		wp_register_script('slick-js', 'https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.9.0/slick.min.js');
		wp_enqueue_script('slick-js');
		// iCheck
		// wp_register_script('icheck-js', get_template_directory_uri() . '/js/icheck/icheck.js');
		// wp_enqueue_script('icheck-js');
		// Fancybox
		wp_register_script('fancybox-js', get_template_directory_uri() . '/js/fancybox/jquery.fancybox.min.js', false, '1.0', true);
		wp_enqueue_script('fancybox-js');
    }

}
add_action('wp_enqueue_scripts', 'wordpress_enqueues', 100);