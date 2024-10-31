<?php

/**
 * Fired during plugin activation
 *
 * @link       https://www.neykane.com/
 * @since      1.0.0
 *
 * @package    Neykane_Viral_List_Posts
 * @subpackage Neykane_Viral_List_Posts/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Neykane_Viral_List_Posts
 * @subpackage Neykane_Viral_List_Posts/includes
 * @author     Neykane <info@neykane.com>
 */
class Neykane_Viral_List_Posts_Activator {

	/**
	 * @since    1.0.0
	 */
	public static function activate() {
		// flush rewrite rules
		delete_option( 'rewrite_rules' );
	}

}
