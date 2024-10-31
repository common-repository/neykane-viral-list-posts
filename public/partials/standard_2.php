<?php

if ( ! isset( $skipHeaderAndFooter ) ) {
	$skipHeaderAndFooter = false;
}

if ( ! $skipHeaderAndFooter ) {
	get_header();
}

$itemsCount = (int) get_post_meta(
	$post->ID,
	'_' . Neykane_Viral_List_Posts_Admin::$id_prefix . '_items_count',
	true
);

$item = max( (int) get_query_var( Neykane_Viral_List_Posts_Admin::$query_var_slide ), 1 );

if ( $item > $itemsCount ) {
	$item = $itemsCount;
}

$thisSlideNoLink = get_permalink();

$nextSlideNoLink       = false;
$prevSlideNoLink       = false;
$nextSlideNoLinkHidden = '';
$prevSlideNoLinkHidden = '';

if ( $item > 1 ) {
	$prevSlideNoLink = $thisSlideNoLink . ( '?nlp_slide_no=' . ( $item - 1 ) );
} else {
	$prevSlideNoLinkHidden = 'd-none';
}

if ( $item < $itemsCount ) {
	$nextSlideNoLink = $thisSlideNoLink . ( '?nlp_slide_no=' . ( $item + 1 ) );
} else {
	$nextSlideNoLinkHidden = 'd-none';
}

$intro = get_post_meta(
	$post->ID,
	'_' . Neykane_Viral_List_Posts_Admin::$id_prefix . '_intro',
	true
);

$sidebarEnabled = (int) get_post_meta(
	$post->ID,
	'_' . Neykane_Viral_List_Posts_Admin::$id_prefix . '_enable_sidebar',
	true
);

$sidebar = is_active_sidebar( Neykane_Viral_List_Posts_Admin::$text_domain );

$firstColClass = 'col-12';

if ( $sidebarEnabled && $sidebar ) {
	$firstColClass = 'col-lg-9 col-md-12';
}

$containerClass = Neykane_Viral_List_Posts_Admin::$text_domain . '__container';

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

if ( $item === 1 ) {
	echo <<<HTML
                    <div class="row my-3">
                        <div class="col-12">
                            $intro
                        </div>
                    </div>
HTML;
}

$htmlKey     = '_neykane_viral_list_posts_items_' . $item . '_html';
$imageUrlKey = '_neykane_viral_list_posts_items_' . $item . '_image_url';
$titleKey    = '_neykane_viral_list_posts_items_' . $item . '_title';

$html      = $post->$htmlKey;
$title     = $item . '. ' . esc_html( $post->$titleKey );
$imageUrl  = esc_url( $post->$imageUrlKey );
$itemId    = Neykane_Viral_List_Posts_Admin::$post_type . '_' . $item;
$itemClass = Neykane_Viral_List_Posts_Admin::$text_domain . '__item';

echo <<<HTML
                <div class="row my-3">
                    <div class="col-12">
                        <div class="card shadow $itemClass" id="$itemId">
                            <div class="position-relative">
                                <img class="card-img-top $itemClass--img" src="$imageUrl">
                                <a href="$prevSlideNoLink" class="btn btn-dark position-absolute $itemClass--img-prev-btn $prevSlideNoLinkHidden"><</a>
                                <a href="$nextSlideNoLink" class="btn btn-dark position-absolute $itemClass--img-next-btn $nextSlideNoLinkHidden">></a>
                            </div>
                            <div class="card-body $itemClass--body">
                                <h5 class="card-title $itemClass--title">$title</h5>
                                <div class="py-1 $itemClass--content">
                                    $html
                                    <br/>
                                    <a href="$prevSlideNoLink" class="btn btn-dark float-start my-2 $itemClass--content-prev-btn $prevSlideNoLinkHidden"><</a>
                                    <a href="$nextSlideNoLink" class="btn btn-dark float-end my-2 $itemClass--content-next-btn $nextSlideNoLinkHidden">></a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
HTML;

echo <<<HTML
                </div>
HTML;

if ( $sidebarEnabled && $sidebar ) {
	echo '<div class="col-lg-3 col-md-12">';
	dynamic_sidebar( Neykane_Viral_List_Posts_Admin::$text_domain );
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