<?php
/**
 * Plugin Name: List Last Changes
 * Plugin URI: http://www.rolandbaer.ch
 * Description: Shows a list of the last changes of a wordpress installation.
 * Version: 0.2.0
 * Author: Roland Bär
 * Author URI: http://www.rolandbaer.ch
 * License: GPLv2
 */
 
 /*  Copyright 2013  Roland Bär  (email : info@rolandbaer.ch)

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
 
/**
 * Output of widget
 */
function list_last_changes_widget_init_sidebarwidget() {
	echo '<aside id="list_last_changes_widget_1" class="widget list_last_changes_widget">' . "\n";
	echo ' <h3 class="widget-title">Letzte &Auml;nderungen</h3>' . "\n";
	echo " <ul>\n";
	
	$mypages = wp_get_pages(array('sort_column' => 'post_modified', 'sort_order' => 'asc', 'show_date' => 'modified'/*, 'number' => '5'*/ ));
	usort($mypages, 'sort_pages_by_date_desc');
	$count = min(count($mypages), 5);
	for($i = 0; $i < $count; $i++) { 
		$post = $mypages[$i];
		setup_postdata($post);	
		echo '  <li class="list_last_changes_title">'. "\n" . '   <a href="' . get_page_link( $post->ID ) .'">' . $post->post_title . "</a>\n";
		echo '   <span class="list_last_changes_date">' . date_i18n(get_option('date_format') ,strtotime($post->post_modified)) . "</span>\n  </li>\n";
	}
	
	echo " </ul>\n";
	echo "</aside>\n";	
}

/**
 * Sort by modified date ascending
 */
function sort_pages_by_date($a, $b){
    if ($a->post_modified == $b->post_modified)
        return 0;
    if ($a->post_modified < $b->post_modified)
        return -1;
    return 1;
}

/**
 * Sort by modified date descending
 */
function sort_pages_by_date_desc($a, $b){
    return sort_pages_by_date($a, $b) * -1;
}

/**
 * Init Widget
 */ 
function list_last_changes_widget_init()
{
   wp_register_sidebar_widget('1',__('List Last Changes'), 'list_last_changes_widget_init_sidebarwidget');
}

/**
 * Register style sheet.
 */
function list_last_changes_register_plugin_styles() {
	wp_register_style( 'list-last-changes', plugins_url( 'list-last-changes/css/list-last-changes.css' ) );
	wp_enqueue_style( 'list-last-changes' );
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
		'child_of' => 0, 'exclude' => "", 'title_li' => __('Pages'), 'echo' => 1, 'authors' => '', 'sort_column' => 'menu_order, post_title');
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

/**
 * Add Actions
 */
 
/**
 * Initialize Plugin
 */
add_action("plugins_loaded", "list_last_changes_widget_init");

/**
 * Register style sheet.
 */
add_action( 'wp_enqueue_scripts', 'list_last_changes_register_plugin_styles' );
