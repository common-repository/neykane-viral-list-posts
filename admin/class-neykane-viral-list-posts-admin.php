<?php
/** @noinspection PhpUnused */

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://www.neykane.com/
 * @since      1.0.0
 *
 * @package    Neykane_Viral_List_Posts
 * @subpackage Neykane_Viral_List_Posts/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Neykane_Viral_List_Posts
 * @subpackage Neykane_Viral_List_Posts/admin
 * @author     Neykane <info@neykane.com>
 */
class Neykane_Viral_List_Posts_Admin {

	public static string $plugin_pretty_name = "Neykane Viral List Posts";
	public static string $settings_page_slug = "settings";
	public static string $post_type = "list_posts";
	public static string $id_prefix = "neykane_viral_list_posts";
	public static string $text_domain = "neykane-viral-list-posts";
	public static string $js_object_name = "_neykaneViralListPosts";
	public static string $shortcode_name = "neykane_viral_list_post";
	public static string $query_var_slide = "nlp_slide_no";

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
	 * @param string $plugin_name The name of this plugin.
	 * @param string $version The version of this plugin.
	 *
	 * @since    1.0.0
	 */
	public function __construct( string $plugin_name, string $version ) {
		$this->plugin_name = $plugin_name;
		$this->version     = $version;
	}

	/**
	 * Creates a new custom post type
	 *
	 * @since 1.0.0
	 * @access public
	 * @uses register_post_type()
	 */
	public static function new_cpt_neykane_viral_list_posts(): void {
		$options = get_option( esc_attr( self::$text_domain ) . '_settings' );
		$slug    = $options['rewrite_slug'] ?? '';

		/** @noinspection SqlDialectInspection */
		/** @noinspection SqlNoDataSourceInspection */
		$labels = array(
			'name'                  => _x( 'Viral List Post',
				'Post Type General Name',
				esc_attr( self::$text_domain ) ),
			'singular_name'         => _x( 'Viral List Post',
				'Post Type Singular Name',
				esc_attr( self::$text_domain ) ),
			'menu_name'             => __( 'Viral List Posts', esc_attr( self::$text_domain ) ),
			'name_admin_bar'        => __( 'Viral List Post', esc_attr( self::$text_domain ) ),
			'archives'              => __( 'List Post Archives', esc_attr( self::$text_domain ) ),
			'attributes'            => __( 'List Post Attributes', esc_attr( self::$text_domain ) ),
			'parent_item_colon'     => __( 'Parent List Post:', esc_attr( self::$text_domain ) ),
			'all_items'             => __( 'All List Posts', esc_attr( self::$text_domain ) ),
			'add_new_item'          => __( 'Add New List Post', esc_attr( self::$text_domain ) ),
			'add_new'               => __( 'Add New', esc_attr( self::$text_domain ) ),
			'new_item'              => __( 'New List Post', esc_attr( self::$text_domain ) ),
			'edit_item'             => __( 'Edit List Post', esc_attr( self::$text_domain ) ),
			'update_item'           => __( 'Update List Post', esc_attr( self::$text_domain ) ),
			'view_item'             => __( 'View List Post', esc_attr( self::$text_domain ) ),
			'view_items'            => __( 'View List Posts', esc_attr( self::$text_domain ) ),
			'search_items'          => __( 'Search List Post', esc_attr( self::$text_domain ) ),
			'not_found'             => __( 'Not found', esc_attr( self::$text_domain ) ),
			'not_found_in_trash'    => __( 'Not found in Trash', esc_attr( self::$text_domain ) ),
			'featured_image'        => __( 'Featured Image', esc_attr( self::$text_domain ) ),
			'set_featured_image'    => __( 'Set featured image', esc_attr( self::$text_domain ) ),
			'remove_featured_image' => __( 'Remove featured image', esc_attr( self::$text_domain ) ),
			'use_featured_image'    => __( 'Use as featured image', esc_attr( self::$text_domain ) ),
			'insert_into_item'      => __( 'Insert into List Post', esc_attr( self::$text_domain ) ),
			'uploaded_to_this_item' => __( 'Uploaded to this list Post', esc_attr( self::$text_domain ) ),
			'items_list'            => __( 'List Post list', esc_attr( self::$text_domain ) ),
			'items_list_navigation' => __( 'List Post list navigation', esc_attr( self::$text_domain ) ),
			'filter_items_list'     => __( 'Filter List Post list', esc_attr( self::$text_domain ) ),
		);

		$args = array(
			'label'               => __( 'Viral List Posts', esc_attr( self::$text_domain ) ),
			'labels'              => $labels,
			'description'         => __( 'Viral List Posts are composed of items, each of which have associated media and text.',
				esc_attr( self::$text_domain ) ),
			'supports'            => array( 'title', 'thumbnail', 'revisions', 'comments' ),
			'taxonomies'          => array( 'category', 'post_tag' ),
			'hierarchical'        => true,
			'public'              => true,
			'show_ui'             => true,
			'show_in_menu'        => true,
			'menu_position'       => 20,
			'menu_icon'           => 'dashicons-images-alt2',
			'show_in_admin_bar'   => true,
			'show_in_nav_menus'   => true,
			'can_export'          => true,
			'has_archive'         => true,
			'exclude_from_search' => false,
			'publicly_queryable'  => true,
			'query_var'           => esc_attr__( self::$post_type, esc_attr( self::$text_domain ) ),
			'rewrite'             => array(
				'slug' => $slug ?: esc_attr__( self::$post_type, esc_attr( self::$text_domain ) ),
			),
			'capability_type'     => 'post',
			'show_in_rest'        => true,
		);

		register_post_type( self::$post_type, $args );
	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles(): void {
		global $post_type;

		if ( $post_type === esc_attr__( self::$post_type, esc_attr( self::$text_domain ) ) ) {
			wp_enqueue_style(
				$this->plugin_name . '-bootstrap-wrapper',
				plugin_dir_url( __FILE__ ) . 'css/neykane.bootstrap.5.wrapper.min.css',
				array(),
				$this->version
			);
			wp_enqueue_style(
				$this->plugin_name,
				plugin_dir_url( __FILE__ ) . 'css/' . esc_attr( self::$text_domain ) . '-admin.css',
				array(),
				$this->version
			);
		}
	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts(): void {
		global $post_type;

		// these scripts are only loaded on our custom post type screens
		if ( $post_type === esc_attr__( self::$post_type, esc_attr( self::$text_domain ) ) ) {
			wp_enqueue_script(
				$this->plugin_name,
				plugin_dir_url( __FILE__ ) . 'js/' . esc_attr( self::$text_domain ) . '-admin.js',
				array(
					'jquery',
				),
				$this->version
			);
			wp_enqueue_script(
				$this->plugin_name . '-bootstrap.bundle',
				plugin_dir_url( __FILE__ ) . 'js/bootstrap.bundle.min.js',
				$this->version
			);
			wp_enqueue_script(
				$this->plugin_name . '-draggable.bundle.legacy',
				plugin_dir_url( __FILE__ ) . 'js/draggable.bundle.legacy.min.js',
				$this->version
			);
			// expose a JavaScript object on the admin side
			wp_localize_script(
				$this->plugin_name,
				esc_attr__( self::$js_object_name, esc_attr( self::$text_domain ) ),
				array(
					'placeholderImageUrl' => esc_url( plugins_url( '../public/img/item_placeholder.png', __FILE__ ) ),
					'post_type'           => esc_attr__( self::$post_type, esc_attr( self::$text_domain ) ),
					'text_domain'         => esc_attr( self::$text_domain ),
					'id_prefix'           => esc_attr__( self::$id_prefix, esc_attr( self::$text_domain ) ),
				)
			);
		}
	}

	public function register_neykane_viral_list_posts_meta_boxes(): void {
		// add meta boxes for the intro
		add_meta_box(
			esc_attr( self::$text_domain ) . '-intro',
			esc_html__( "Intro", esc_attr( self::$text_domain ) ),
			array(
				$this,
				"render_" . esc_attr__( self::$id_prefix, esc_attr( self::$text_domain ) ) . "_meta_boxes_intro",
			),
			esc_attr__( self::$post_type, esc_attr( self::$text_domain ) ),
			"normal",
			"low"
		);
		// add meta boxes for the items themselves
		add_meta_box(
			esc_attr( self::$text_domain ) . '-items',
			esc_html__( "Items", esc_attr( self::$text_domain ) ),
			array( $this, "render_" . esc_attr__( self::$id_prefix, esc_attr( self::$text_domain ) ) . "_meta_boxes" ),
			esc_attr__( self::$post_type, esc_attr( self::$text_domain ) ),
			"normal",
			"low"
		);
		// add sidebar meta box for selecting the template
		add_meta_box(
			esc_attr( self::$text_domain ) . '-template',
			esc_html__( "Template", esc_attr( self::$text_domain ) ),
			array(
				$this,
				"render_" . esc_attr__( self::$id_prefix, esc_attr( self::$text_domain ) ) . "_sidebar_template",
			),
			esc_attr__( self::$post_type, esc_attr( self::$text_domain ) ),
			"side",
			"low"
		);
		// add sidebar meta box for enabling the sidebar
		add_meta_box(
			esc_attr( self::$text_domain ) . '-enable-sidebar',
			esc_html__( "Enable Sidebar", esc_attr( self::$text_domain ) ),
			array(
				$this,
				"render_" . esc_attr__( self::$id_prefix, esc_attr( self::$text_domain ) ) . "_sidebar_enable_sidebar",
			),
			esc_attr__( self::$post_type, esc_attr( self::$text_domain ) ),
			"side",
			"low"
		);
		// add sidebar meta box for copying the shortcode
		add_meta_box(
			esc_attr( self::$text_domain ) . '-shortcode',
			esc_html__( "Embed Shortcode", esc_attr( self::$text_domain ) ),
			array(
				$this,
				"render_" . esc_attr__( self::$id_prefix, esc_attr( self::$text_domain ) ) . "_sidebar_shortcode",
			),
			esc_attr__( self::$post_type, esc_attr( self::$text_domain ) ),
			"side",
			"high"
		);
	}

	public function save_neykane_viral_list_posts_meta_boxes(): void {
		global $post;
		// let WordPress core handle trash requests
		if ( is_array( $_GET ) && array_key_exists( 'action', $_GET ) && $_GET['action'] === 'trash' ) {
			return;
		}
		// our hook should only run if $post is defined...
		if ( ! $post ) {
			return;
		}

		// and has a property called 'post_type'...
		if ( ! property_exists( $post, 'post_type' ) ) {
			return;
			// and the post_type is ours
		}

		if ( $post->post_type !== self::$post_type ) {
			return;
		}
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return;
		}
		$nonce = wp_verify_nonce( $_POST[ '_' . esc_attr__( self::$id_prefix,
			esc_attr( self::$text_domain ) ) . '_items_nonce' ],
			'_' . esc_attr__( self::$id_prefix, esc_attr( self::$text_domain ) ) . '_items_submit' );

		if ( ! $nonce ) {
			wp_die( "Something went wrong -- we were unable to verify that this was a legitimate request." );
		}
		$slidesCount  = (int) $_POST[ '_' . esc_attr__( self::$id_prefix,
			esc_attr( self::$text_domain ) ) . '_items_count' ];
		$slidesFields = $this->neykane_viral_list_posts_custom_fields();
		$postMeta     = get_post_meta( $post->ID );
		// first, let's update the number of items
		$numberOfItems = sanitize_text_field( $_POST[ '_' . esc_attr__( self::$id_prefix,
			esc_attr( self::$text_domain ) ) . '_items_count' ] );
		$numberOfItems = is_numeric( $numberOfItems ) ? $numberOfItems : $slidesCount;
		update_post_meta( $post->ID,
			'_' . esc_attr__( self::$id_prefix, esc_attr( self::$text_domain ) ) . '_items_count',
			$numberOfItems );
		// next, lets update the template being used
		$itemTemplate = sanitize_text_field( $_POST[ '_' . esc_attr__( self::$id_prefix,
			esc_attr( self::$text_domain ) ) . '_template' ] );
		update_post_meta( $post->ID,
			'_' . esc_attr__( self::$id_prefix, esc_attr( self::$text_domain ) ) . '_template',
			$itemTemplate );
		// and if the sidebar is enabled
		$sidebarEnabled = sanitize_text_field( $_POST[ '_' . esc_attr__( self::$id_prefix,
				esc_attr( self::$text_domain ) ) . '_enable_sidebar' ] ?? '' );
		$sidebarEnabled = $sidebarEnabled ? 1 : 0;
		update_post_meta( $post->ID,
			'_' . esc_attr__( self::$id_prefix, esc_attr( self::$text_domain ) ) . '_enable_sidebar',
			$sidebarEnabled );
		// and the post's introduction
		$itemIntro = wp_filter_post_kses( $_POST[ '_' . esc_attr__( self::$id_prefix,
			esc_attr( self::$text_domain ) ) . '_intro' ] );
		update_post_meta( $post->ID,
			'_' . esc_attr__( self::$id_prefix, esc_attr( self::$text_domain ) ) . '_intro',
			$itemIntro );
		// now for each item, lets update the corresponding post meta fields
		for ( $i = 1; $i <= $slidesCount; $i ++ ) {
			foreach ( $slidesFields as $slidesField ) {
				$fieldName = '_' . esc_attr__( self::$id_prefix,
						esc_attr( self::$text_domain ) ) . '_items_' . $i . '_' . $slidesField;
				switch ( $slidesField ) {
					case 'html':
						$fieldValue = wp_filter_kses( $_POST[ '_' . esc_attr__( self::$id_prefix,
							esc_attr( self::$text_domain ) ) . '_items_' . $i . '_' . $slidesField ] );
						update_post_meta( $post->ID, $fieldName, $fieldValue );
						break;
					case 'image_url':
						$fieldValue = esc_url_raw( $_POST[ '_' . esc_attr__( self::$id_prefix,
							esc_attr( self::$text_domain ) ) . '_items_' . $i . '_' . $slidesField ] );
						update_post_meta( $post->ID, $fieldName, $fieldValue );
						break;
					case 'title':
						$fieldValue = esc_attr__( $_POST[ '_' . esc_attr__( self::$id_prefix,
							esc_attr( self::$text_domain ) ) . '_items_' . $i . '_' . $slidesField ] );
						update_post_meta( $post->ID, $fieldName, $fieldValue );
						break;
				}
			}
		}
		// finally, lets remove any metadata that corresponds to an item number that's higher than our number of
		// items (for instance, if someone deleted some items).
		$regex = "/^_" . esc_attr__( self::$id_prefix,
				esc_attr( self::$text_domain ) ) . "_items_([0-9]*)_" . $slidesFields[0] . "$/";
		foreach ( $postMeta as $key => $value ) {
			if ( preg_match( $regex, $key, $matches ) ) {
				$matchedIndex = (int) $matches[1];
				// if the matched index is higher than our total number of items, we should remove all corresponding
				// metadata for the matched index
				if ( $matchedIndex > $slidesCount ) {
					foreach ( $slidesFields as $slidesField ) {
						delete_post_meta( $post->ID,
							'_' . esc_attr__( self::$id_prefix,
								esc_attr( self::$text_domain ) ) . '_items_' . $matchedIndex . '_' . $slidesField );
					}
				}
			}
		}
	}

	public function neykane_viral_list_posts_custom_fields(): array {
		return array(
			'html',
			'image_url',
			'title',
		);
	}

	public function render_neykane_viral_list_posts_sidebar_template(): void {
		global $post;
		$name              = '_' . esc_attr__( self::$id_prefix, esc_attr( self::$text_domain ) ) . '_template';
		$template          = get_post_meta( $post->ID,
			'_' . esc_attr__( self::$id_prefix, esc_attr( self::$text_domain ) ) . '_template',
			true );
		$standard1Selected = $template === 'standard_1' ? 'selected' : '';
		$standard2Selected = $template === 'standard_2' ? 'selected' : '';
		$standard1Name     = esc_html__( 'Modern (vertical)', esc_attr( self::$text_domain ) );
		$standard2Name     = esc_html__( 'Modern (horizontal)', esc_attr( self::$text_domain ) );

		echo <<<HTML
        <select name="$name">
            <option $standard1Selected value="standard_1">$standard1Name</option>
            <option $standard2Selected value="standard_2">$standard2Name</option>
        </select>
HTML;
	}

	public function render_neykane_viral_list_posts_sidebar_shortcode(): void {
		global $post;
		$value = esc_attr__( self::$shortcode_name,
				esc_attr( self::$text_domain ) ) . ' ' . 'id=' . esc_attr__( $post->ID,
				esc_attr( self::$text_domain ) );
		echo <<<HTML
        <div class="nk__b5">
            <input class="form-control" type="text" readonly value="[$value]">
        </div>
HTML;
	}

	public function render_neykane_viral_list_posts_sidebar_enable_sidebar(): void {
		global $post;
		$name          = '_' . esc_attr__( self::$id_prefix, esc_attr( self::$text_domain ) ) . '_enable_sidebar';
		$enableSidebar = get_post_meta( $post->ID,
			'_' . esc_attr__( self::$id_prefix, esc_attr( self::$text_domain ) ) . '_enable_sidebar',
			true );
		$checked       = (int) $enableSidebar ? 'checked' : '';
		$description   = esc_html__( 'Enable sidebar', esc_attr( self::$text_domain ) );

		echo <<<HTML
        <input type="checkbox" value="1" name="$name" $checked/> $description <br>
HTML;
	}

	public function render_neykane_viral_list_posts_meta_boxes(): void {
		// generate a html wrapper for the items
		echo '<div class="nk__b5"><div class="container-fluid ' . esc_attr( self::$text_domain ) . '-items">';
		// get the number of items and output to a hidden input ( always make at least 1 item )
		$slidesCount = max( (int) $this->get_post_meta( '_' . esc_attr__( self::$id_prefix,
				esc_attr( self::$text_domain ) ) . '_items_count' ),
			1 );
		echo '<input type="hidden" class="' . esc_attr( self::$text_domain ) . '-count" name="_' . esc_attr__( self::$id_prefix,
				esc_attr( self::$text_domain ) ) . '_items_count" value="' . esc_attr__( $slidesCount,
				esc_attr( self::$text_domain ) ) . '">';
		// Create a nonce field for verification
		wp_nonce_field( '_' . esc_attr__( self::$id_prefix, esc_attr( self::$text_domain ) ) . '_items_submit',
			'_' . esc_attr__( self::$id_prefix, esc_attr( self::$text_domain ) ) . '_items_nonce' );
		// generate an 'add item' button
		echo '<input type="button" class="' . esc_attr( self::$text_domain ) . '-add-new-item button-primary m-2" value="Add a new item"/>';
		// generate each slide
		for ( $i = 1; $i <= $slidesCount; $i ++ ) {
			include plugin_dir_path( __FILE__ ) . 'partials/slide.php';
		}
		// close the html wrapper for the items
		echo '</div></div>';
	}

	public function get_post_meta( $meta ) {
		global $post;
		if ( $post ) {
			return get_post_meta( $post->ID, $meta, true );
		}

		return false;
	}

	public function render_neykane_viral_list_posts_meta_boxes_intro(): void {
		global $post;
		$html = get_post_meta( $post->ID,
			'_' . esc_attr__( self::$id_prefix, esc_attr( self::$text_domain ) ) . '_intro',
			true );
		wp_editor(
			htmlspecialchars_decode( $html ),
			esc_attr__( self::$id_prefix, esc_attr( self::$text_domain ) ) . '_intro',
			array(
				'textarea_name' => '_' . esc_attr__( self::$id_prefix, esc_attr( self::$text_domain ) ) . '_intro',
			)
		);
	}

	public function render_neykane_viral_list_posts_item(): void {
		if ( isset( $_POST['items'] ) ) {
			if ( is_array( $_POST['items'] ) ) {
				$items = rest_sanitize_array( $_POST['items'] );
			} else {
				$items = array();
			}
			$ajaxMode = true;
			foreach ( $items as $item ) {
				$i = sanitize_text_field( $item['index'] );
				$i = is_numeric( $i ) ? $i : '1';
				if ( array_key_exists( 'type', $item ) ) {
					$itemType = sanitize_text_field( $item['type'] );
					switch ( $itemType ) {
						case 'slide':
							include plugin_dir_path( __FILE__ ) . 'partials/slide.php';
							break;
					}
				}
			}
		}
		wp_die();
	}

	public function add_plugin_settings_link( $links ): array {
		$linkHref = admin_url( 'edit.php?post_type=' . self::$post_type . '&page=' . self::$settings_page_slug );
		$linkText = esc_html__( 'Settings', esc_attr( self::$text_domain ) );

		$link = "<a href=\"$linkHref\">$linkText</a>";

		return array_merge(
			$links,
			array( $link )
		);
	}

	public function add_plugin_admin_menu(): void {
		add_submenu_page(
			'edit.php?post_type=' . self::$post_type,
			esc_html__( self::$plugin_pretty_name, esc_attr( self::$text_domain ) ) . ' Settings',
			esc_html__( 'Settings', esc_attr( self::$text_domain ) ),
			'manage_options',
			esc_attr__( self::$settings_page_slug, esc_attr( self::$text_domain ) ),
			array( $this, 'display_plugin_settings_page' )
		);
	}

	public function display_plugin_settings_page(): void {
		//must check that the user has the required capability
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( esc_html__( 'You do not have sufficient permissions to access this page.',
				esc_attr( self::$text_domain ) ) );
		}
		include_once( 'partials/settings.php' );
	}

	public function register_plugin_settings(): void {
		// add settings
		register_setting(
			$this->plugin_name . '_settings',
			$this->plugin_name . '_settings',
			array( $this, 'validate_settings' )
		);
		// add setting sections
		add_settings_section(
			'general',
			'',
			array( $this, 'render_settings_general' ),
			$this->plugin_name . '_settings_general'
		);
		add_settings_section(
			'advanced',
			'',
			array( $this, 'render_settings_advanced' ),
			$this->plugin_name . '_settings_advanced'
		);
		// add general setting fields
		add_settings_field(
			'rewrite_slug',
			esc_html__( "Rewrite Slug", esc_attr( self::$text_domain ) ),
			array( $this, 'render_settings_general_rewrite_slug' ),
			$this->plugin_name . '_settings_general',
			'general'
		);
	}

	public function render_settings_general(): void {
	}

	public function render_settings_advanced(): void {
	}

	public function render_settings_general_rewrite_slug(): void {
		$options = get_option( $this->plugin_name . '_settings' );
		$name    = esc_attr__( $this->plugin_name . '_settings[rewrite_slug]' );
		$value   = esc_attr__( $options['rewrite_slug'] ?? self::$post_type );
		echo "<input name='$name' size='40' type='text' value='$value' />";
	}

	public function validate_settings( $input ): array {
		/***************************************************************************************************************
		 * PRE VALIDATION
		 **************************************************************************************************************/
		// if the input is not array, something completely unexpected has happened so let's just return an empty array.
		if ( ! is_array( $input ) ) {
			return array();
		}
		// get current settings
		$settings = get_option( $this->plugin_name . '_settings' );
		// get current rewrite slug
		$currentRewriteSlug = $settings['rewrite_slug'] ?? self::$post_type;

		/***************************************************************************************************************
		 * VALIDATE SETTINGS
		 **************************************************************************************************************/
		// rewrite_slug
		$input['rewrite_slug'] = trim( $input['rewrite_slug'] );
		if ( $input['rewrite_slug'] === '' ) {
			$input['rewrite_slug'] = $currentRewriteSlug;
		}

		/***************************************************************************************************************
		 * POST VALIDATION
		 **************************************************************************************************************/
		// if the rewrite slug has changed, flush rewrite rules
		if ( $currentRewriteSlug !== $input['rewrite_slug'] ) {
			delete_option( 'rewrite_rules' );
		}

		return $input;
	}

	public function neykane_viral_list_posts_sidebar(): void {
		register_sidebar(
			array(
				'name'          => esc_html__( self::$plugin_pretty_name, esc_attr( self::$text_domain ) ),
				'id'            => esc_attr( self::$text_domain ),
				'description'   => esc_html__( 'Appears on ' . self::$post_type . ' in the sidebar.',
					esc_attr( self::$text_domain ) ),
				'before_widget' => '<div class="widget-content">',
				'after_widget'  => "</div>",
				'before_title'  => '<h3 class="widget-title">',
				'after_title'   => '</h3>',
			)
		);
	}

}
