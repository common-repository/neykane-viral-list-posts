<?php

/**
 *
 * @link              https://www.neykane.com/
 * @since             1.0.0
 * @package           Neykane_Viral_List_Posts
 *
 * @wordpress-plugin
 * Plugin Name:       Neykane Viral List Posts
 * Plugin URI:        https://www.neykane.com/products/viral-list-posts
 * Description:       Adds a new "List Posts" Custom Post Type to help you create memorable, well-organized, and highly-shareable list posts.
 * Version:           1.1.1
 * Author:            Neykane
 * Author URI:        https://www.neykane.com/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       neykane-viral-list-posts
 * Domain Path:       /languages
 */

if ( ! defined( 'WPINC' ) ) {
	die;
}

const NEYKANE_VIRAL_LIST_POSTS_VERSION = '1.1.1';

function activate_neykane_viral_list_posts() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-neykane-viral-list-posts-activator.php';
	Neykane_Viral_List_Posts_Activator::activate();
}

function deactivate_neykane_viral_list_posts() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-neykane-viral-list-posts-deactivator.php';
	Neykane_Viral_List_Posts_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_neykane_viral_list_posts' );
register_deactivation_hook( __FILE__, 'deactivate_neykane_viral_list_posts' );

require plugin_dir_path( __FILE__ ) . 'includes/class-neykane-viral-list-posts.php';

function run_neykane_viral_list_posts() {
	$plugin = new Neykane_Viral_List_Posts();
	$plugin->run();
}

run_neykane_viral_list_posts();
