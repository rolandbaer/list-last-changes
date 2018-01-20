<?php
/**
 * Plugin Name: List Last Changes
 * Plugin URI: http://www.rolandbaer.ch/software/wordpress/plugin-last-changes/
 * Description: Shows a list of the last changes of a WordPress site.
 * Version: 0.6.0
 * Author: Roland Bär
 * Author URI: http://www.rolandbaer.ch/
 * Text Domain: list-last-changes
 * License: GPLv2
 */
 
 /*  Copyright 2013-2018  Roland Bär  (email : info@rolandbaer.ch)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as 
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

	function ListLastChangesWidget() {
	    $widget_ops = array('classname' => 'widget_list_last_changes', 'description' => __('Shows a list of the last changes of a WordPress site.', 'list-last-changes') );
         
        $this->WP_Widget('list-last-changes-widget', __('List Last Changes', 'list-last-changes'), $widget_ops);
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
		$title = empty($instance['title']) ? ' ' : apply_filters('widget_title', $instance['title']);
		$number = empty($instance['number']) ? ' ' : $instance['number'];
		$showpages = isset( $instance['showpages'] ) ? (bool) $instance['showpages'] : true;
		$showposts = isset( $instance['showposts'] ) ? (bool) $instance['showposts'] : false;

		echo '<aside id="list_last_changes_widget_1" class="widget list_last_changes_widget">' . "\n";
		echo ' <h3 class="widget-title">' . $title . '</h3>' . "\n";
		echo " <ul>\n";
		
		$excludePages = $this->wp_get_pages(array('meta_key' => 'list_last_changes_ignore', 'meta_value' => 'true', 'hierarchical' => 0));
		$excludePageIds = "";
		for($i = 0; $i < count($excludePages); $i++) {
			if($i > 0) {
				$excludePageIds = $excludePageIds . ",";
			}
			$excludePageIds = $excludePageIds . $excludePages[$i]->ID;
		}			

		$excludePosts = get_posts(array('meta_key' => 'list_last_changes_ignore', 'meta_value' => 'true'));
		$excludePostIds = "";
		for($i = 0; $i < count($excludePosts); $i++) {
			if($i > 0) {
				$excludePostIds = $excludePostIds . ",";
			}
			$excludePostIds = $excludePostIds . $excludePosts[$i]->ID;
		}			

		$mypages = $showpages ? $this->wp_get_pages(array('sort_column' => 'post_modified', 'sort_order' => 'asc', 'show_date' => 'modified', 'hierarchical' => 0, 'exclude' => $excludePageIds)) : array();
		usort($mypages, 'sort_pages_by_date_desc');
		$myposts = $showposts ? get_posts(array('sort_column' => 'post_modified', 'sort_order' => 'asc', 'show_date' => 'modified', 'exclude' => $excludePostIds)) : array();
		usort($mypages, 'sort_pages_by_date_desc');
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
			echo '  <li class="list_last_changes_title">'. "\n" . '   <a href="' . get_page_link( $post->ID ) .'">' . $post->post_title . "</a>\n";
			echo '   <span class="list_last_changes_date">' . date_i18n(get_option('date_format') ,strtotime($post->post_modified)) . "</span>\n  </li>\n";
		}
		
		echo " </ul>\n";
		echo "</aside>\n";	
	}

	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title'] = strip_tags($new_instance['title']);
		$instance['number'] = strip_tags($new_instance['number']);
		$instance['showpages'] = isset( $new_instance['showpages'] ) ? (bool) $new_instance['showpages'] : false;
		$instance['showposts'] = isset( $new_instance['showposts'] ) ? (bool) $new_instance['showposts'] : false;
	 
		return $instance;
	}

	function form( $instance ) {
		//  Assigns values
		$instance = wp_parse_args( (array) $instance, array( 'title' => 'Last Changes', 'number' => '5', 'showpages' => true, 'showposts' => false ) );
		$title = strip_tags($instance['title']);
		$number = strip_tags($instance['number']);
		$showpages = strip_tags($instance['showpages']);
		$showposts = strip_tags($instance['showposts']);
		?>
			<p><label for="<?php echo $this->get_field_id('title'); ?>"><?php echo __('Title', 'list-last-changes'); ?>: <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo attribute_escape($title); ?>" /></label></p>
			<p><label for="<?php echo $this->get_field_id('number'); ?>"><?php echo __('Number of shown changes', 'list-last-changes'); ?>: <input class="widefat" id="<?php echo $this->get_field_id('number'); ?>" name="<?php echo $this->get_field_name('number'); ?>" type="text" value="<?php echo attribute_escape($number); ?>" /></label></p>
			<p><input class="checkbox" id="<?php echo $this->get_field_id('showpages'); ?>" name="<?php echo $this->get_field_name('showpages'); ?>" type="checkbox" <?php checked( $showpages ); ?> /><label for="<?php echo $this->get_field_id('showpages'); ?>"><?php echo __('Show changed Pages', 'list-last-changes'); ?></label></p>
			<p><input class="checkbox" id="<?php echo $this->get_field_id('showposts'); ?>" name="<?php echo $this->get_field_name('showposts'); ?>" type="checkbox" <?php checked( $showposts ); ?> /><label for="<?php echo $this->get_field_id('showposts'); ?>"><?php echo __('Show changed Posts', 'list-last-changes'); ?></label></p>
		<?php
	}
	
	/**
	 * Get Pages
	 */
	function wp_get_pages($args = '') {
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