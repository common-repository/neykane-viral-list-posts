<?php

if (class_exists('Neykane_Viral_List_Posts_Admin') && $i) {
    $id_prefix = esc_attr(Neykane_Viral_List_Posts_Admin::$id_prefix);
    $class_prefix = esc_attr(Neykane_Viral_List_Posts_Admin::$text_domain);
    // fetch the image associated with this item
    $slideImageUrl = esc_url($this->get_post_meta('_' . $id_prefix . '_items_' . $i . '_image_url'));
    // if the image doesn't exist, replace it with a default image
    if ($slideImageUrl) {
        $slideImageToDisplayUrl = $slideImageUrl;
    } else {
        $slideImageToDisplayUrl = esc_url(plugins_url('../../public/img/item_placeholder.png', __FILE__));
    }
    // fetch the html content associated with this item
    $slideHtml = $this->get_post_meta('_' . $id_prefix . '_items_' . $i . '_html');
    // fetch the title associated with this item
    $slideTitle = $this->get_post_meta('_' . $id_prefix . '_items_' . $i . '_title');
} else {
    return;
}

echo <<<HTML
<div class="{$class_prefix}__item-container" data-type="slide" data-index="$i">
    <div class="{$class_prefix}__item-handle bg-light rounded-lg ps-4 mx-5 text-end">
      <button type="button" class="{$class_prefix}__item-handle--delete-button btn btn-danger btn-sm mx-1">X</button>
      <button type="button" class="{$class_prefix}__item-handle--up-button btn btn-dark btn-sm mx-1">&ShortUpArrow;</button>
      <button type="button" class="{$class_prefix}__item-handle--down-button btn btn-dark btn-sm mx-1">&ShortDownArrow;</button>
      <button type="button" class="{$class_prefix}__item-handle--duplicate-button btn btn-info btn-sm mx-1"><span class="dashicons dashicons-admin-page"></span></button>
      <button type="button" class="{$class_prefix}__item-handle--add-button btn btn-success btn-sm mx-1">+</button>
    </div>
    <div class="bg-light ps-5 pe-5 pb-5 rounded-lg ms-3 pe-3 pb-3 {$class_prefix}__item-body row p-3 show">
        <div class="col-12 py-2">
            <input type="text" class="form-control" name="_{$id_prefix}_items_{$i}_title" value="$slideTitle">
        </div>
        <div class="{$class_prefix}__item-body--left col-xl-6 col-lg-12 position-relative">
            <img class="{$class_prefix}__item-body--left-img img img-fluid img-thumbnail rounded mx-auto d-block mb-2" src="$slideImageToDisplayUrl" alt="Item image">
            <div class="input-group position-absolute w-90-bottom-centered">
                <input type="text" class="form-control" placeholder="Select an image" name="_{$id_prefix}_items_{$i}_image_url" value="$slideImageToDisplayUrl">
                <div class="input-group-append">
                    <button class="$class_prefix-media-upload-btn btn btn-secondary" type="submit">Upload</button> 
                </div>
            </div>
        </div>
        <div class="{$class_prefix}__item-body--right col-xl-6 col-lg-12">
HTML;

wp_editor(
    $slideHtml,
    $id_prefix . '_items_' . $i . '_html',
    $settings = array(
        'textarea_name' => '_' . $id_prefix . '_items_' . $i . '_html',
    )
);

echo <<<HTML
        </div>
    </div>
</div>
HTML;
