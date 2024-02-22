<?php
// Register Custom Post Type Questions
$labels = array(
    'name' => _x('Questions', 'Post Type General Name', 'textdomain'),
    'singular_name' => _x('Questions', 'Post Type Singular Name', 'textdomain'),
    'menu_name' => _x('Questions', 'Admin Menu text', 'textdomain'),
    'name_admin_bar' => _x('Questions', 'Add New on Toolbar', 'textdomain'),
    'archives' => __('Questions Archives', 'textdomain'),
    'attributes' => __('Questions Attributes', 'textdomain'),
    'parent_item_colon' => __('Parent Questions:', 'textdomain'),
    'all_items' => __('All Questions', 'textdomain'),
    'add_new_item' => __('Add New Questions', 'textdomain'),
    'add_new' => __('Add New', 'textdomain'),
    'new_item' => __('New Questions', 'textdomain'),
    'edit_item' => __('Edit Questions', 'textdomain'),
    'update_item' => __('Update Questions', 'textdomain'),
    'view_item' => __('View Questions', 'textdomain'),
    'view_items' => __('View Questions', 'textdomain'),
    'search_items' => __('Search Questions', 'textdomain'),
    'not_found' => __('Not found', 'textdomain'),
    'not_found_in_trash' => __('Not found in Trash', 'textdomain'),
    'featured_image' => __('Featured Image', 'textdomain'),
    'set_featured_image' => __('Set featured image', 'textdomain'),
    'remove_featured_image' => __('Remove featured image', 'textdomain'),
    'use_featured_image' => __('Use as featured image', 'textdomain'),
    'insert_into_item' => __('Insert into Questions', 'textdomain'),
    'uploaded_to_this_item' => __('Uploaded to this Questions', 'textdomain'),
    'items_list' => __('Questions list', 'textdomain'),
    'items_list_navigation' => __('Questions list navigation', 'textdomain'),
    'filter_items_list' => __('Filter Questions list', 'textdomain'),
);
$args = array(
    'label' => __('Questions', 'textdomain'),
    'description' => __('', 'textdomain'),
    'labels' => $labels,
    'menu_icon' => 'dashicons-clipboard',
    'supports' => array('title'),
    'taxonomies' => array(),
    'public' => true,
    'show_ui' => true,
    'show_in_menu' => true,
    'menu_position' => 5,
    'show_in_admin_bar' => true,
    'show_in_nav_menus' => true,
    'can_export' => true,
    'has_archive' => true,
    'hierarchical' => false,
    'exclude_from_search' => false,
    'show_in_rest' => true,
    'publicly_queryable' => true,
    'capability_type' => 'post',
);
register_post_type('qz-questions', $args);