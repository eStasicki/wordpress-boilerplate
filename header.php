<!DOCTYPE html>
<html <?php echo language_attributes(); ?> class="no-js">

<head>
	<title><?php wp_title('•', true, 'right'); ?></title>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<?php wp_head(); ?>
</head>

<body <?php if(is_front_page()): ?><?php body_class(); ?><?php else: ?><?php body_class('theme-subpage'); ?><?php endif; ?>>

	<header>
		<?php $menuTop = array(
				'theme_location'  => 'menu-top',
				'menu'            => '',
				'container'       => 'ul',
				'container_class' => '',
				'container_id'    => '',
				'menu_class'      => '',
				'menu_id'         => '',
				'echo'            => true,
				'fallback_cb'     => 'wp_page_menu',
				'before'          => '',
				'after'           => '',
				'link_before'     => '<span>',
				'link_after'      => '</span>',
				'items_wrap'      => '<nav id="menu-top" class="page-menu clear"><ul>%3$s</ul></nav>',
				'depth'           => 0,
				'walker'          => ''
			);
			wp_nav_menu($menuTop);
		?>
	</header>