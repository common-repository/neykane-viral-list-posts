<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://www.neykane.com/
 * @since      1.0.0
 *
 * @package    Neykane_Viral_List_Posts
 * @subpackage Neykane_Viral_List_Posts/admin/partials
 */

$adminPageTitle = esc_html( get_admin_page_title() );
$adminPageUrl   = admin_url( 'edit.php?post_type=' . esc_attr__( Neykane_Viral_List_Posts_Admin::$post_type,
		esc_attr( Neykane_Viral_List_Posts_Admin::$text_domain ) ) . '&page=' . esc_attr__( Neykane_Viral_List_Posts_Admin::$settings_page_slug,
		esc_attr( Neykane_Viral_List_Posts_Admin::$text_domain ) ) );

$activeTab = isset( $_GET['tab'] )
	? sanitize_text_field( $_GET['tab'] )
	: 'general';

$isActiveGeneral  = '';
$isActiveAdvanced = '';
$isHiddenGeneral  = '';
$isHiddenAdvanced = '';

if ( $activeTab === 'general' ) {
	$isActiveGeneral  = 'nav-tab-active';
	$isActiveAdvanced = '';
	$isHiddenGeneral  = '';
	$isHiddenAdvanced = 'hidden';
} else {
	if ( $activeTab === 'advanced' ) {
		$isActiveGeneral  = '';
		$isActiveAdvanced = 'nav-tab-active';
		$isHiddenGeneral  = 'hidden';
		$isHiddenAdvanced = '';
	}
}

$generalString  = esc_html__( 'General', 'neykane-viral-list-posts' );
$advancedString = esc_html__( 'Advanced', 'neykane-viral-list-posts' );

// start wrapper
echo <<<HTML
<div class="wrap">
    <h2>$adminPageTitle</h2>
    <h2 class="nav-tab-wrapper">
        <a href="$adminPageUrl&tab=general" class="nav-tab $isActiveGeneral">$generalString</a>
        <a href="$adminPageUrl&tab=advanced" class="nav-tab $isActiveAdvanced hidden">$advancedString</a>
    </h2>
    <form method="post" action="options.php">
    <div class="$isHiddenGeneral">
HTML;

// since this file is included in the context of the Neykane_Viral_List_Posts_Admin class, $this references the instance
// of that class
do_settings_sections( $this->plugin_name . '_settings_general' );

echo <<<HTML
    </div>
    <div class="$isHiddenAdvanced">
HTML;

do_settings_sections( $this->plugin_name . '_settings_advanced' );

echo <<<HTML
    </div>
HTML;

settings_fields( $this->plugin_name . '_settings' );
submit_button();

// end wrapper
echo <<<HTML
</form></div>
HTML;
