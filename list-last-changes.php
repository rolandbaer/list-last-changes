<?php
/**
 * Plugin Name: List Last Changes
 * Plugin URI: http://www.rolandbaer.ch/software/wordpress/plugin-last-changes/
 * Description: Shows a list of the last changes of a WordPress site.
 * Version: 1.0.4
 * Author: Roland Bär
 * Author URI: http://www.rolandbaer.ch/
 * Text Domain: list-last-changes
 * License: GPLv3
 */

/*  Copyright 2013-2023  Roland Bär  (email : info@rolandbaer.ch)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 3, as 
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

class ListLastChangesWidget extends WP_Widget {

	function __construct() {
		$widget_ops = array('classname' => 'widget_list_last_changes', 'description' => __('Shows a list of the last changes of a WordPress site.', 'list-last-changes') );

		parent::__construct('list-last-changes-widget', __('List Last Changes', 'list-last-changes'), $widget_ops);
	}

	/**
	 * Front-end display of widget.
	 *
	 * @see WP_Widget::widget()
	 *
	 * @param array $args     Widget arguments.
	 * @param array $instance Saved values from database.
	 */
	function widget( $args, $instance ) {
		extract($args);

		//  Get the title of the widget and the specified number of elements
		$title = empty($instance['title']) ? ' ' : apply_filters('widget_title', $instance['title'], $instance, $this->id_base);
		$number = empty($instance['number']) ? ' ' : $instance['number'];
		$showpages = isset( $instance['showpages'] ) ? (bool) $instance['showpages'] : true;
		$showposts = isset( $instance['showposts'] ) ? (bool) $instance['showposts'] : false;
		$showauthor = isset( $instance['showauthor'] ) ? (bool) $instance['showauthor'] : false;
		$template = empty($instance['template']) ? list_last_changes_default_template($showauthor) : $instance['template'];

		echo $args['before_widget'] . "\n";
		if ( $title ) {
			echo $args['before_title'] . $title . $args['after_title'] . "\n";
		}
		echo ListLastChangesWidget::generate_list($number, $showpages, $showposts, $template);
		echo $args['after_widget'] . "\n";;
	}

	public static function generate_list($number, $showpages, $showposts, $template) {
		$content = " <ul>\n";

		$loop = new WP_Query(array('post_type' => 'page', 'meta_key' => 'list_last_changes_ignore', 'meta_value' => 'true', 'numberposts' => -1));
		$excludePageIds = "";
		while( $loop->have_posts() ) {
			$loop->the_post();
			$excludePageIds = $excludePageIds . get_the_ID() . ",";
		}

		$loop = new WP_Query(array('post_type' => 'post', 'meta_key' => 'list_last_changes_ignore', 'meta_value' => 'true', 'numberposts' => -1));
		$excludePostIds = "";
		while( $loop->have_posts() ) {
			$loop->the_post();
			$excludePostIds = $excludePostIds . get_the_ID() . ",";
		}

		$mypages = $showpages ? ListLastChangesWidget::wp_get_pages(array('sort_column' => 'post_modified', 'sort_order' => 'asc', 'show_date' => 'modified', 'hierarchical' => 0, 'exclude' => $excludePageIds)) : array();
		usort($mypages, 'sort_pages_by_date_desc');
		$myposts = $showposts ? get_posts(array('numberposts' => $number, 'orderby' => 'modified', 'exclude' => $excludePostIds)) : array();
		usort($myposts, 'sort_pages_by_date_desc');
		$count = min(count($mypages) + count($myposts), $number);
		$pagePos = 0;
		$postPos = 0;
		for($i = 0; $i < $count; $i++) {
			$pageMod = $pagePos < count($mypages) ? $mypages[$pagePos]->post_modified : date("YYYY-MM-DD HH:MM:SS", 0);
			$postMod = $postPos < count($myposts) ? $myposts[$postPos]->post_modified : date("YYYY-MM-DD HH:MM:SS", 0);
			if($pageMod > $postMod) {
				$post = $mypages[$pagePos];
				$pagePos++;
			}
			else
			{
				$post = $myposts[$postPos];
				$postPos++;
			}
			setup_postdata($post);
			$transitions = array(
				"{title}" => '<a href="' . get_permalink( $post->ID ) .'">' . $post->post_title . "</a>",
				"{change_date}" => '<span class="list_last_changes_date">' . date_i18n(get_option('date_format') ,strtotime($post->post_modified)) . "</span>",
				"{author}" => '<span class="list_last_changes_author">' . get_the_author_meta( 'display_name' , $post->post_author ) . "</span>");
			$entry = strtr($template, $transitions);
			$content = $content . '  <li class="list_last_changes_title">'. "\n" ;
			$content = $content . $entry;
			$content = $content . "  </li>\n";
		}

		$content = $content . " </ul>\n";

		wp_reset_postdata();

		return $content;
	}

	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title'] = strip_tags($new_instance['title']);
		$instance['number'] = strip_tags($new_instance['number']);
		$instance['showpages'] = isset( $new_instance['showpages'] ) ? (bool) $new_instance['showpages'] : false;
		$instance['showposts'] = isset( $new_instance['showposts'] ) ? (bool) $new_instance['showposts'] : false;
		$instance['template'] = strip_tags($new_instance['template']);
		unset($instance['showauthor']);

		return $instance;
	}

	function form( $instance ) {
		//  Assigns values
		$instance = wp_parse_args( (array) $instance, array( 'title' => 'Last Changes', 'number' => '5', 'showpages' => true, 'showposts' => false, 'showauthor' => false ) );
		$showauthor = strip_tags($instance['showauthor']);
		$instance = wp_parse_args( (array) $instance, array( 'template' => list_last_changes_default_template($showauthor) ) );
		$title = strip_tags($instance['title']);
		$number = strip_tags($instance['number']);
		$showpages = strip_tags($instance['showpages']);
		$showposts = strip_tags($instance['showposts']);
		$template = strip_tags($instance['template']);
		?>
			<p><label for="<?php echo $this->get_field_id('title'); ?>"><?php echo __('Title', 'list-last-changes'); ?>: <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" /></label></p>
			<p><label for="<?php echo $this->get_field_id('number'); ?>"><?php echo __('Number of shown changes', 'list-last-changes'); ?>: <input class="widefat" id="<?php echo $this->get_field_id('number'); ?>" name="<?php echo $this->get_field_name('number'); ?>" type="text" value="<?php echo esc_attr($number); ?>" /></label></p>
			<p><input class="checkbox" id="<?php echo $this->get_field_id('showpages'); ?>" name="<?php echo $this->get_field_name('showpages'); ?>" type="checkbox" <?php checked( $showpages ); ?> /><label for="<?php echo $this->get_field_id('showpages'); ?>"><?php echo __('Show changed Pages', 'list-last-changes'); ?></label></p>
			<p><input class="checkbox" id="<?php echo $this->get_field_id('showposts'); ?>" name="<?php echo $this->get_field_name('showposts'); ?>" type="checkbox" <?php checked( $showposts ); ?> /><label for="<?php echo $this->get_field_id('showposts'); ?>"><?php echo __('Show changed Posts', 'list-last-changes'); ?></label></p>
			<p><label for="<?php echo $this->get_field_id('template'); ?>"><?php echo __('Template', 'list-last-changes'); ?>: <input class="widefat" id="<?php echo $this->get_field_id('template'); ?>" name="<?php echo $this->get_field_name('template'); ?>" type="text" value="<?php echo esc_attr($template); ?>" /></label></p>
		<?php
	}

	/**
	 * Get Pages
	 */
	static function wp_get_pages($args = '') {
		if ( is_array($args) )
			$r =  &$args;
		else
			parse_str($args, $r);

		$defaults = array('depth' => 0, 'show_date' => '', 'date_format' => get_option('date_format'),
			'child_of' => 0, 'exclude' => "", 'title_li' => 'Pages', 'echo' => 1, 'authors' => '', 'sort_column' => 'menu_order, post_title');
		$r = array_merge($defaults, $r);

		$output = '';
		$current_page = 0;

		// sanitize, mostly to keep spaces out
		$r['exclude'] = preg_replace('[^0-9,]', '', $r['exclude']);

		// Allow plugins to filter an array of excluded pages
		$r['exclude'] = implode(',', apply_filters('wp_list_pages_excludes', explode(',', $r['exclude'])));

		// Query pages.
		$pages = get_pages($r);

		return $pages;
	}
}

function list_last_changes_register_widgets() {
	register_widget( 'ListLastChangesWidget' );
}

add_action( 'widgets_init', 'list_last_changes_register_widgets' );
add_shortcode( 'list_last_changes', 'list_last_changes_shortcode_funct' );

/**
 * Sort by modified date ascending
 */
function sort_pages_by_date($a, $b) {
	if ($a->post_modified == $b->post_modified)
		return 0;
	if ($a->post_modified < $b->post_modified)
		return -1;
	return 1;
}

/**
 * Sort by modified date descending
 */
function sort_pages_by_date_desc($a, $b) {
	return sort_pages_by_date($a, $b) * -1;
}

/**
 * Register style sheet.
 */
add_action( 'wp_enqueue_scripts', 'list_last_changes_register_plugin_styles' );

/**
 * Register style sheet.
 */
function list_last_changes_register_plugin_styles() {
	wp_register_style( 'list-last-changes', plugins_url( 'list-last-changes/css/list-last-changes.css' ) );
	wp_enqueue_style( 'list-last-changes' );
}

function list_last_changes_shortcode_funct($atts ) {
	$a = shortcode_atts( array(
		'number' => '5',
		'showpages' => 'true',
		'showposts' => 'false',
		'showauthor' => 'false',
		'template' => '',
	), $atts );

	$number = empty($a['number']) ? ' ' : $a['number'];
	$showpages = $a['showpages'] === 'true';
	$showposts = $a['showposts'] === 'true';
	$showauthor = $a['showauthor'] === 'true';
	$template = empty($a['template']) ? list_last_changes_default_template($showauthor) : $a['template'];
	return ListLastChangesWidget::generate_list($number, $showpages, $showposts, $template);
}

function list_last_changes_default_template($showauthor) {
	$template = "   {title}\n   {change_date}\n";
	if($showauthor) {
		$template = $template . "   {author}\n";
	}

	return $template;
}

/**
 * Renders the `plugins/list-last-changes` block on server.
 *
 * @param array $attributes The block attributes.
 *
 * @return string Returns the post content with the last changes added.
 */
function render_block_plugins_list_last_changes( $attributes ) {
	$args = array(
		'number'      => $attributes['number'],
		'showpages'   => $attributes['showpages'],
		'showposts'   => $attributes['showposts'],
		'showauthor'  => $attributes['showauthor'],
		'template'    => $attributes['template'],
	);

	$number = empty($args['number']) ? ' ' : $args['number'];
	$showpages = $args['showpages'];
	$showposts = $args['showposts'];
	$showauthor = $args['showauthor'];
	$template = empty($args['template']) ? list_last_changes_default_template($showauthor) : $args['template'];

	$recentChanges = ListLastChangesWidget::generate_list($number, $showpages, $showposts, $template);

	return $recentChanges;
}

/**
 * Registers all block assets so that they can be enqueued through Gutenberg in
 * the corresponding context.
 *
 * Passes translations to JavaScript.
 */
function list_last_changes_register_block() {
	if ( ! function_exists( 'register_block_type' ) ) {
		// Gutenberg is not active.
		return;
	}

	wp_register_script(
		'list-last-changes',
		plugins_url( 'block.js', __FILE__ ),
		array( 'wp-blocks', 'wp-block-editor', 'wp-i18n', 'wp-element'/*, 'wp-components'*/ ),
		filemtime( plugin_dir_path( __FILE__ ) . 'block.js' )
	);

	register_block_type( 'plugins/list-last-changes', array(
			'editor_script'   => 'list-last-changes',
			'attributes'      => array(
				'number'      => array(
					'type'    => 'number',
					'default' => 5,
				),
				'showpages'   => array(
					'type'    => 'boolean',
					'default' => true,
				),
				'showposts'   => array(
					'type'    => 'boolean',
					'default' => false,
				),
				'showauthor'  => array(
					'type'    => 'boolean',
					'default' => false,
				),
				'template'    => array(
					'type'    => 'string',
					'default' => "{title} {change_date}"
				),
			),
			'render_callback' => 'render_block_plugins_list_last_changes',
		)
	);

	if ( function_exists( 'wp_set_script_translations' ) ) {
		/**
		 * May be extended to wp_set_script_translations( 'my-handle', 'my-domain',
		 * plugin_dir_path( MY_PLUGIN ) . 'languages' ) ). For details see
		 * https://make.wordpress.org/core/2018/11/09/new-javascript-i18n-support-in-wordpress/
		 */
		wp_set_script_translations( 'list-last-changes', 'list-last-changes' );
	}
}

add_action('init', 'list_last_changes_register_block' );
