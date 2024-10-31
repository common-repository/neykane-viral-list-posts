<?php /** @noinspection PhpUnused */

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://www.neykane.com/
 * @since      1.0.0
 *
 * @package    Neykane_Viral_List_Posts
 * @subpackage Neykane_Viral_List_Posts/public
 */

/**
 *
 * @package    Neykane_Viral_List_Posts
 * @subpackage Neykane_Viral_List_Posts/public
 * @author     Neykane <info@neykane.com>
 */
class Neykane_Viral_List_Posts_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string $plugin_name The ID of this plugin.
	 */
	private string $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string $version The current version of this plugin.
	 */
	private string $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @param string $plugin_name The name of the plugin.
	 * @param string $version The version of this plugin.
	 *
	 * @since    1.0.0
	 */
	public function __construct( string $plugin_name, string $version ) {
		$this->plugin_name = $plugin_name;
		$this->version     = $version;
	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {
		global $post_type;

		// these styles are only loaded on our custom post type
		if ( $post_type === esc_attr__( Neykane_Viral_List_Posts_Admin::$post_type,
				esc_attr( Neykane_Viral_List_Posts_Admin::$text_domain ) ) ) {
			wp_enqueue_style(
				$this->plugin_name . '-bootstrap-wrapper',
				plugin_dir_url( __FILE__ ) . 'css/neykane.bootstrap.5.wrapper.min.css',
				array(),
				$this->version
			);
			wp_enqueue_style(
				$this->plugin_name,
				plugin_dir_url( __FILE__ ) . 'css/' . esc_attr( Neykane_Viral_List_Posts_Admin::$text_domain ) . '-public.css',
				array(),
				$this->version
			);
			// otherwise, we'll just register them (not load them) so we can selective load them if we need to, say when a
			// shortcode is used
		} else {
			wp_register_style(
				$this->plugin_name . '-bootstrap-wrapper',
				plugin_dir_url( __FILE__ ) . 'css/neykane.bootstrap.5.wrapper.min.css',
				array(),
				$this->version
			);
			wp_register_style(
				$this->plugin_name,
				plugin_dir_url( __FILE__ ) . 'css/' . Neykane_Viral_List_Posts_Admin::$text_domain . '-public.css',
				array(),
				$this->version
			);
		}
	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {
		global $post_type;

		// these scripts are only loaded on our custom post type screens
		if ( $post_type === esc_attr__( Neykane_Viral_List_Posts_Admin::$post_type,
				esc_attr( Neykane_Viral_List_Posts_Admin::$text_domain ) ) ) {
			wp_enqueue_script(
				$this->plugin_name,
				plugin_dir_url( __FILE__ ) . 'js/' . esc_attr( Neykane_Viral_List_Posts_Admin::$text_domain ) . '-public.js',
				array(
					'jquery',
					$this->plugin_name . '-bootstrap.bundle',
				),
				$this->version
			);
			wp_enqueue_script(
				$this->plugin_name . '-bootstrap.bundle',
				plugin_dir_url( __FILE__ ) . 'js/bootstrap.bundle.min.js',
				$this->version,
				true
			);
			// otherwise, we'll just register them (not load them) so we can selective load them if we need to, say when a
			// shortcode is used
		} else {
			wp_register_script(
				$this->plugin_name,
				plugin_dir_url( __FILE__ ) . 'js/' . esc_attr( Neykane_Viral_List_Posts_Admin::$text_domain ) . '-public.js',
				array(
					'jquery',
					$this->plugin_name . '-bootstrap.bundle',
				),
				$this->version
			);
			wp_register_script(
				$this->plugin_name . '-bootstrap.bundle',
				plugin_dir_url( __FILE__ ) . 'js/bootstrap.bundle.min.js',
				$this->version,
				true
			);
		}
	}

	/**
	 * Handle our frontend template
	 *
	 * @param string $single The initial single template
	 *
	 * @return   string    The template used for the page
	 * @since    1.0.0
	 *
	 */
	public function enable_neykane_viral_list_posts_templates( string $single ): string {
		global $post;
		if ( $post->post_type === esc_attr__( Neykane_Viral_List_Posts_Admin::$post_type,
				esc_attr( Neykane_Viral_List_Posts_Admin::$text_domain ) ) ) {
			$template = $this->render_neykane_viral_list_posts_templates( $post, 'full' );
			if ( $template ) {
				$single = $template;
			}
		}

		return $single;
	}

	/**
	 * Render our frontend template
	 *
	 * @param string $mode Either 'full' or 'shortcode'
	 *
	 * @return   string The template used for the page
	 * @since    1.0.0
	 *
	 */
	public function render_neykane_viral_list_posts_templates( $post, string $mode ): string {
		$postMeta = get_post_meta( $post->ID );
		$template = $postMeta[ '_' . esc_attr__( Neykane_Viral_List_Posts_Admin::$id_prefix,
			esc_attr( Neykane_Viral_List_Posts_Admin::$text_domain ) ) . '_template' ][0];
		// if we found template information, proceed based on the template requested
		if ( $template ) {
			switch ( $template ) {
				case "standard_2":
				case "standard_1":
					$templatePath = plugin_dir_path( dirname( __FILE__ ) ) . 'public/partials/' . $template . '.php';
					if ( file_exists( $templatePath ) ) {
						if ( $mode == 'shortcode' ) {
							wp_enqueue_style( $this->plugin_name . '-bootstrap-wrapper' );
							wp_enqueue_style( $this->plugin_name );
							wp_enqueue_script( $this->plugin_name . '-bootstrap.bundle' );
							wp_enqueue_script( $this->plugin_name );
							$skipHeaderAndFooter = true;
							ob_start();
							require( $templatePath );
							echo ob_get_clean();
						} else {
							return $templatePath;
						}
					}
					break;
			}
			// otherwise proceed with a default template
		} else {
			$templatePath = plugin_dir_path( dirname( __FILE__ ) ) . 'public/partials/standard_1.php';
			if ( file_exists( $templatePath ) ) {
				if ( $mode == 'shortcode' ) {
					wp_enqueue_style( $this->plugin_name . '-bootstrap-wrapper' );
					wp_enqueue_style( $this->plugin_name );
					wp_enqueue_script( $this->plugin_name . '-bootstrap.bundle' );
					wp_enqueue_script( $this->plugin_name );
					$skipHeaderAndFooter = true;
					ob_start();
					require( $templatePath );
					echo ob_get_clean();
				} else {
					return $templatePath;
				}
			}
		}

		return '';
	}

	/**
	 * Handle shortcode
	 *
	 * @param array $attr The attributes passed into the shortcode call
	 *
	 * @since    1.0.0
	 *
	 */
	public function shortcode_neykane_viral_list_post( array $attr ) {
		// only proceed if a post id was provided
		if ( is_array( $attr ) && key_exists( 'id', $attr ) ) {
			$post = get_post( $attr['id'] );
			if ( $post && $post->post_type === esc_attr__( Neykane_Viral_List_Posts_Admin::$post_type,
					esc_attr( Neykane_Viral_List_Posts_Admin::$text_domain ) ) && $post->post_status === 'publish' ) {
				// based on https://codex.wordpress.org/Function_Reference/add_shortcode shortcodes "should never
				// produce output of any kind. Shortcode functions should return the text that is to be used to replace
				// the shortcode".
				ob_start();
				$this->render_neykane_viral_list_posts_templates( $post, 'shortcode' );

				return ob_get_clean();
			}
		}

		return '';
	}

	/**
	 * Add query vars.
	 *
	 * @param array $qvars The global query vars
	 *
	 * @since    1.1.0
	 *
	 */
	public function query_vars_neykane_viral_list_post( array $qvars ): array {
		$qvars[] = esc_attr__( Neykane_Viral_List_Posts_Admin::$query_var_slide,
			esc_attr( Neykane_Viral_List_Posts_Admin::$text_domain ) );

		return $qvars;
	}

}
