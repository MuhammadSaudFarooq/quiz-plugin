<?php
// Register Custom Post Type Questions
$labels = array(
    'name' => _x('Quiz', 'Post Type General Name', 'textdomain'),
    'singular_name' => _x('Quiz', 'Post Type Singular Name', 'textdomain'),
    'menu_name' => _x('Quiz', 'Admin Menu text', 'textdomain'),
    'name_admin_bar' => _x('Quiz', 'Add New on Toolbar', 'textdomain'),
    'archives' => __('Quiz Archives', 'textdomain'),
    'attributes' => __('Quiz Attributes', 'textdomain'),
    'parent_item_colon' => __('Parent Quiz:', 'textdomain'),
    'all_items' => __('All Quiz', 'textdomain'),
    'add_new_item' => __('Add New Quiz', 'textdomain'),
    'add_new' => __('Add New', 'textdomain'),
    'new_item' => __('New Quiz', 'textdomain'),
    'edit_item' => __('Edit Quiz', 'textdomain'),
    'update_item' => __('Update Quiz', 'textdomain'),
    'view_item' => __('View Quiz', 'textdomain'),
    'view_items' => __('View Quiz', 'textdomain'),
    'search_items' => __('Search Quiz', 'textdomain'),
    'not_found' => __('Not found', 'textdomain'),
    'not_found_in_trash' => __('Not found in Trash', 'textdomain'),
    'featured_image' => __('Featured Image', 'textdomain'),
    'set_featured_image' => __('Set featured image', 'textdomain'),
    'remove_featured_image' => __('Remove featured image', 'textdomain'),
    'use_featured_image' => __('Use as featured image', 'textdomain'),
    'insert_into_item' => __('Insert into Quiz', 'textdomain'),
    'uploaded_to_this_item' => __('Uploaded to this Quiz', 'textdomain'),
    'items_list' => __('Quiz list', 'textdomain'),
    'items_list_navigation' => __('Quiz list navigation', 'textdomain'),
    'filter_items_list' => __('Filter Quiz list', 'textdomain'),
);
$args = array(
    'label' => __('Quiz', 'textdomain'),
    'description' => __('', 'textdomain'),
    'labels' => $labels,
    'menu_icon' => 'dashicons-book',
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
register_post_type('qz-quiz', $args);

/**
 * Quiz Category functionality
 */
function add_category_to_quiz()
{
    add_meta_box(PLUGIN_PREFIX . '_quiz', __('Quiz Category', 'textdomain'), 'render_quiz_category_meta_box', PLUGIN_PREFIX . '-quiz', 'side', 'default');
}

function render_quiz_category_meta_box($post)
{
    $post_id = $post->ID;
    // Retrieve existing values for the custom fields
    $quizCategories = get_option(PLUGIN_PREFIX . "_quiz_categories");
    $quizCatRelation = get_option(PLUGIN_PREFIX . "_quiz_cat_relation");

    $count = 1;
    $template = '<div id="quiz-category">';
    if ($quizCatRelation) {
        foreach ($quizCategories as $key => $value) {
            $template .= '<label for="category-' . $count . '" title="' . (isset($quizCatRelation['category-' . $count]) ? get_the_title($quizCatRelation['category-' . $count]) : "") . '">';

            if (isset($quizCatRelation['category-' . $count]) && $quizCatRelation['category-' . $count] == $post_id) {
                $template .= '<input type="radio" id="category-' . $count . '" name="quiz_category" value="category-' . $count . '" required checked>';
            } else if (isset($quizCatRelation['category-' . $count])) {
                $template .= '<input type="radio" id="category-' . $count . '" name="quiz_category" value="category-' . $count . '" required disabled>';
            } else {
                $template .= '<input type="radio" id="category-' . $count . '" name="quiz_category" value="category-' . $count . '" required>';
            }

            $template .= '<span>' . $value . '</span>';
            $template .= '</label>';
            $count++;
        }
    } else {
        foreach ($quizCategories as $key => $value) {
            $template .= '<label for="category-' . $count . '">';
            $template .= '<input type="radio" id="category-' . $count . '" name="quiz_category" value="category-' . $count . '" required>';
            $template .= '<span>' . $value . '</span>';
            $template .= '</label>';
            $count++;
        }
    }
    $template .= '</div>';
    echo $template;
}

function save_quiz_category_data($post_id)
{
    // Save custom field data when the post is saved
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
    if (isset($_POST['quiz_category']) && $_POST['quiz_category'] != '') {
        $quizCatRelation = get_option(PLUGIN_PREFIX . "_quiz_cat_relation");
        if ($quizCatRelation) {
            $quizCatRelation[sanitize_text_field($_POST['quiz_category'])] = $post_id;
            update_option(PLUGIN_PREFIX . "_quiz_cat_relation", $quizCatRelation);
            update_post_meta($post_id, 'quiz_category', sanitize_text_field($_POST['quiz_category']));
        } else {
            $quizCatRelation = [];
            $quizCatRelation[sanitize_text_field($_POST['quiz_category'])] = $post_id;
            add_option(PLUGIN_PREFIX . "_quiz_cat_relation", $quizCatRelation);
            update_post_meta($post_id, 'quiz_category', sanitize_text_field($_POST['quiz_category']));
        }
    }
}

add_action('admin_menu', 'add_category_to_quiz');
add_action('save_post', 'save_quiz_category_data');


/**
 * Quiz question functionality
 */

function add_questions_to_quiz()
{
    add_meta_box(PLUGIN_PREFIX . '_quiz_question', __('Questions', 'textdomain'), 'render_quiz_question_meta_box', PLUGIN_PREFIX . '-quiz', 'normal', 'default');
}

function render_quiz_question_meta_box($post)
{
    $post_id = $post->ID;
    $post_type = PLUGIN_PREFIX . '-questions';
    $question_args = array(
        'post_type' => $post_type,
        'post_status' => 'publish',
        'posts_per_page' => -1,
        'orderby' => 'title',
        'order' => 'ASC',
    );
    $question_loop = new WP_Query($question_args);
    $question_loop = $question_loop->posts;

    if (!empty($question_loop)) {
        $template = '<div id="quiz-questions">';
        $template = '<div>';
        $template .= '<select class="question-select" data-posttype="' . $post_type . '" required>';
        $template .= '<option value="" selected disabled>Select question...</option>';
        foreach ($question_loop as $key => $value) {
            $template .= '<option value="' . $value->ID . '">' . $value->post_title . '</option>';
        }
        $template .= '</select>';
        $template .= '</div>';
        $template .= '</div>';
        echo $template;
    }
}

function save_quiz_question_data()
{
}

add_action('add_meta_boxes', 'add_questions_to_quiz');
add_action('save_post', 'save_quiz_question_data');
