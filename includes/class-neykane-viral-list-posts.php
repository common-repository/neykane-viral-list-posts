<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://www.neykane.com/
 * @since      1.0.0
 *
 * @package    Neykane_Viral_List_Posts
 * @subpackage Neykane_Viral_List_Posts/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Neykane_Viral_List_Posts
 * @subpackage Neykane_Viral_List_Posts/includes
 * @author     Neykane <info@neykane.com>
 */
class Neykane_Viral_List_Posts {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Neykane_Viral_List_Posts_Loader $loader Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string $plugin_name The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string $version The current version of the plugin.
	 */
	protected $version;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {
		if ( defined( 'NEYKANE_VIRAL_LIST_POSTS_VERSION' ) ) {
			$this->version = NEYKANE_VIRAL_LIST_POSTS_VERSION;
		} else {
			$this->version = '1.0.0';
		}
		$this->plugin_name = 'neykane-viral-list-posts';

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();
	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Neykane_Viral_List_Posts_Loader. Orchestrates the hooks of the plugin.
	 * - Neykane_Viral_List_Posts_i18n. Defines internationalization functionality.
	 * - Neykane_Viral_List_Posts_Admin. Defines all hooks for the admin area.
	 * - Neykane_Viral_List_Posts_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {
		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-neykane-viral-list-posts-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-neykane-viral-list-posts-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-neykane-viral-list-posts-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-neykane-viral-list-posts-public.php';

		$this->loader = new Neykane_Viral_List_Posts_Loader();
	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Neykane_Viral_List_Posts_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {
		$plugin_i18n = new Neykane_Viral_List_Posts_i18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );
	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {
		$plugin_admin    = new Neykane_Viral_List_Posts_Admin( $this->get_plugin_name(), $this->get_version() );
		$plugin_basename = plugin_basename( plugin_dir_path( __DIR__ ) . $this->plugin_name . '.php' );

		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );
		$this->loader->add_action( 'init', $plugin_admin, 'new_cpt_neykane_viral_list_posts' );
		$this->loader->add_action( 'add_meta_boxes', $plugin_admin, 'register_neykane_viral_list_posts_meta_boxes' );
		$this->loader->add_action( 'save_post', $plugin_admin, 'save_neykane_viral_list_posts_meta_boxes' );
		$this->loader->add_action( 'wp_ajax_render_neykane_viral_list_posts_item',
			$plugin_admin,
			'render_neykane_viral_list_posts_item' );
		$this->loader->add_filter( 'plugin_action_links_' . $plugin_basename,
			$plugin_admin,
			'add_plugin_settings_link' );
		$this->loader->add_action( 'admin_menu', $plugin_admin, 'add_plugin_admin_menu' );
		$this->loader->add_action( 'admin_init', $plugin_admin, 'register_plugin_settings' );
		$this->loader->add_action( 'widgets_init', $plugin_admin, 'neykane_viral_list_posts_sidebar' );
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @return    string    The name of the plugin.
	 * @since     1.0.0
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @return    string    The version number of the plugin.
	 * @since     1.0.0
	 */
	public function get_version() {
		return $this->version;
	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {
		$plugin_public = new Neykane_Viral_List_Posts_Public( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );
		$this->loader->add_filter( 'single_template', $plugin_public, 'enable_neykane_viral_list_posts_templates' );
		$this->loader->add_shortcode( 'neykane_viral_list_post', $plugin_public, 'shortcode_neykane_viral_list_post' );
		$this->loader->add_filter( 'query_vars', $plugin_public, 'query_vars_neykane_viral_list_post' );
	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @return    Neykane_Viral_List_Posts_Loader    Orchestrates the hooks of the plugin.
	 * @since     1.0.0
	 */
	public function get_loader() {
		return $this->loader;
	}

}
