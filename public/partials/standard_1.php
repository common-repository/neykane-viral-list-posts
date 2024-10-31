<?php

if ( ! isset( $skipHeaderAndFooter ) ) {
	$skipHeaderAndFooter = false;
}

if ( ! $skipHeaderAndFooter ) {
	get_header();
}

$itemsCount = (int) get_post_meta(
	$post->ID,
	'_' . esc_attr__( Neykane_Viral_List_Posts_Admin::$id_prefix,
		esc_attr( Neykane_Viral_List_Posts_Admin::$text_domain ) ) . '_items_count',
	true
);

$intro = get_post_meta(
	$post->ID,
	'_' . esc_attr__( Neykane_Viral_List_Posts_Admin::$id_prefix,
		esc_attr( Neykane_Viral_List_Posts_Admin::$text_domain ) ) . '_intro',
	true
);

$sidebarEnabled = (int) get_post_meta(
	$post->ID,
	'_' . esc_attr__( Neykane_Viral_List_Posts_Admin::$id_prefix,
		esc_attr( Neykane_Viral_List_Posts_Admin::$text_domain ) ) . '_enable_sidebar',
	true
);

$sidebar = is_active_sidebar( esc_attr__( Neykane_Viral_List_Posts_Admin::$id_prefix,
	esc_attr( Neykane_Viral_List_Posts_Admin::$text_domain ) ) );

$firstColClass = 'col-12';

if ( $sidebarEnabled && $sidebar ) {
	$firstColClass = 'col-lg-9 col-md-12';
}

$containerClass = esc_attr__( Neykane_Viral_List_Posts_Admin::$id_prefix,
		esc_attr( Neykane_Viral_List_Posts_Admin::$text_domain ) ) . '__container';

echo <<<HTML
    <div class="nk__b5">
        <div class="container $containerClass">
            <div class="row">
                <div class="$firstColClass">
HTML;

if ( ! $skipHeaderAndFooter ) {
	the_title( '<h1 class="entry-title">', '</h1>' );

	echo <<<HTML
                    <div>
HTML;

	the_time( 'l, F jS, Y' );

	echo <<<HTML
                    </div>
HTML;
}

echo <<<HTML
                    <div class="row my-3">
                        <div class="col-12">
                            $intro
                        </div>
                    </div>
HTML;

for ( $item = 1; $item <= $itemsCount; $item ++ ) {
	$htmlKey     = '_neykane_viral_list_posts_items_' . $item . '_html';
	$imageUrlKey = '_neykane_viral_list_posts_items_' . $item . '_image_url';
	$titleKey    = '_neykane_viral_list_posts_items_' . $item . '_title';

	$html      = $post->$htmlKey;
	$title     = $item . '. ' . esc_html( $post->$titleKey );
	$imageUrl  = esc_url( $post->$imageUrlKey );
	$itemId    = esc_attr__( Neykane_Viral_List_Posts_Admin::$post_type,
			esc_attr( Neykane_Viral_List_Posts_Admin::$text_domain ) ) . '_' . $item;
	$itemClass = esc_attr( Neykane_Viral_List_Posts_Admin::$text_domain ) . '__item';

	echo <<<HTML
                    <div class="row my-3">
                        <div class="col-12">
                            <div class="card shadow $itemClass" id="$itemId">
                                <img class="card-img-top $itemClass--img" src="$imageUrl">
                                <div class="card-body $itemClass--body">
                                    <h5 class="card-title $itemClass--title">$title</h5>
                                    <div class="py-1 $itemClass--content">
                                        $html
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
HTML;
}

echo <<<HTML
                </div>
HTML;

if ( $sidebarEnabled && $sidebar ) {
	echo '<div class="col-lg-3 col-md-12">';
	dynamic_sidebar( esc_attr( Neykane_Viral_List_Posts_Admin::$text_domain ) );
	echo '</div>';
}

if ( ! $skipHeaderAndFooter ) {
	if ( comments_open() || get_comments_number() ) {
		echo '<div class="col-12">';
		comments_template();
		echo '</div>';
	}
}

echo <<<HTML
            </div>
        </div>
    </div>
HTML;

if ( ! $skipHeaderAndFooter ) {
	get_footer();
}