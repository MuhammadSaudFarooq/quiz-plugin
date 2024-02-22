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


// Custom Field
function add_custom_fields_to_faq()
{
    add_meta_box(PLUGIN_PREFIX . '_question_type', __('Question Type', 'textdomain'), 'render_question_type_meta_box', 'qz-questions', 'normal', 'high');
}
add_action('add_meta_boxes', 'add_custom_fields_to_faq');

function render_question_type_meta_box($post)
{
    $post_id = $post->ID;
    // Retrieve existing values for the custom fields
    $question_type = get_post_meta($post_id, 'question_type', true);
?>
    <div id="question-type">
        <label for="question_type_single">
            <span>True/False:</span>
            <input type="radio" id="question_type_single" name="question_type" value="single" <?php echo (isset($question_type) && $question_type == 'single') ? 'checked' : ''; ?> />
        </label>
        <label for="question_type_multiple">
            <span>Multiple Option - Single Select:</span>
            <input type="radio" id="question_type_multiple" name="question_type" value="multiple" <?php echo (isset($question_type) && $question_type == 'multiple') ? 'checked' : ''; ?> />
        </label>
    </div>
<?php
}

function save_custom_fields_data($post_id)
{
    // Save custom field data when the post is saved
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
    if (isset($_POST['question_type']) && $_POST['question_type'] != '') {
        update_post_meta($post_id, 'question_type', sanitize_text_field($_POST['question_type']));

        if ($_POST['question_type'] === 'multiple') {
        }
    }
}
add_action('save_post', 'save_custom_fields_data');
