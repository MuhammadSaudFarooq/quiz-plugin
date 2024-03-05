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

    $pages_args = array(
        'post_type' => 'page',
        'post_status' => 'publish',
        'posts_per_page' => -1,
        'orderby' => 'title',
        'order' => 'ASC',
    );
    $pages_loop = new WP_Query($pages_args);
    $pages_loop = $pages_loop->posts;

    // $all_quiz_meta = get_post_meta($post_id);
    $quiz_data = get_post_meta($post_id, 'quiz_data', true);

    // function starts_with_qs($key)
    // {
    //     return strpos($key, 'qs_') === 0;
    // }


    if ($quiz_data) {

        // $count = 1;
        // $newArray = array();

        // foreach ($all_quiz_meta as $key => $value) {
        //     if (strpos($key, 'qs_') === 0) {
        //         $newArray[$key] = $value[0];
        //     }
        // }

        // if (!empty($question_loop)) {
        // echo '<select class="question-select" name="qs_1" data-index="1" data-posttype="qz-questions" required>';
        // foreach ($question_loop as $ql_key => $ql_val) {
        //     echo '<option value="' . $ql_val->ID . '" ' . ((isset($check_qs) && $check_qs == $ql_val->ID) ? "selected" : "") . '>' . $ql_val->post_title . '</option>';
        // }
        // echo '</select>';
        // }

        // foreach ($all_quiz_meta as $key => $value) {
        // if (starts_with_qs($key)) {
        // echo $key . " -> " . $value[0] . "<br>";


        // for ($i = 1; $i <= 6; $i++) {
        //     $qs_cd_key = "qs_1_cd_" . $i;
        //     // echo $qs_cd_key;
        //     if ($qs_cd_key == $key) {
        //         echo $key . " -> " . $value[0] . "<br>";
        //     }
        // }
        // }
        // }

        if (!empty($question_loop)) {

            $single_ques_opt = [
                'true',
                'false'
            ];

            $conditions = [
                'qz-questions' => 'Question',
                'page' => 'Page'
            ];


            $template = '<div id="quiz-questions">';
            $template = '<div>';
            // $template .= '<select class="question-select" name="qs_1" data-index="1" data-posttype="' . $post_type . '" required>';
            $template .= '<select class="question-select" name="data[qs_1][id]" data-index="1" data-posttype="' . $post_type . '" required>';
            $template .= '<option value="">Select question...</option>';
            foreach ($question_loop as $key => $value) {
                $template .= '<option value="' . $value->ID . '" ' . ((isset($quiz_data['qs_1']['id']) && $quiz_data['qs_1']['id'] == $value->ID) ? "selected" : "") . '>' . $value->post_title . '</option>';
            }
            $template .= '</select>';

            if (isset($quiz_data['qs_1']['condition']) && count($quiz_data['qs_1']['condition']) > 0) {
                $template .= '<div class="conditions">';
                if (get_post_meta($quiz_data['qs_1']['id'], 'question_type', true) === 'single') {

                    // foreach ($single_ques_opt as $ques_opt_val) {
                    //     $template .= '<div>';
                    //     $template .= '<span>' . $ques_opt_val . '</span>';
                    //     $template .= '<select class="conditions-select" data-cd_index="" required>';

                    //     $template .= '<option value="">Select condition...</option>';
                    //     foreach ($conditions as $c_key => $c_value) {
                    //         $template .= '<option value="' . $c_key . '">' . $c_value . '</option>';
                    //     }

                    //     $template .= '</select>';
                    //     $template .= '</div>';
                    // }

                    $template .= single_type_qs($single_ques_opt, $conditions);
                }
                $template .= '</div>';
            }
            // echo count($quiz_data['qs_1']['condition']);

            $template .= '</div>';
            $template .= '</div>';
            echo $template;
        }

        echo "<pre>";
        print_r($quiz_data['qs_1']);
        echo "</pre>";
    } else {
        if (!empty($question_loop)) {
            $template = '<div id="quiz-questions">';
            $template = '<div>';
            // $template .= '<select class="question-select" name="qs_1" data-index="1" data-posttype="' . $post_type . '" required>';
            $template .= '<select class="question-select" name="data[qs_1][id]" data-index="1" data-posttype="' . $post_type . '" required>';
            $template .= '<option value="">Select question...</option>';
            foreach ($question_loop as $key => $value) {
                $template .= '<option value="' . $value->ID . '">' . $value->post_title . '</option>';
            }
            $template .= '</select>';
            $template .= '</div>';
            $template .= '</div>';
            echo $template;
        }
    }
}


function single_type_qs($single_ques_opt, $conditions)
{
    $template = '';
    // $template .= '<div class="conditions">';
    foreach ($single_ques_opt as $ques_opt_val) {
        $template .= '<div>';
        $template .= '<span>' . $ques_opt_val . '</span>';
        $template .= '<select class="conditions-select" data-name="" data-cd_index="" required>';

        $template .= '<option value="">Select condition...</option>';
        foreach ($conditions as $c_key => $c_value) {
            $template .= '<option value="' . $c_key . '">' . $c_value . '</option>';
        }

        $template .= '</select>';
        $template .= '</div>';
    }
    // $template .= '</div>';
    return $template;
}

function quiz_question_rendering()
{
    $value = '';
    return $value;
}

function save_quiz_question_data($post_id)
{
    // Save custom field data when the post is saved
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
    if (isset($_POST)) {
        // $filteredKeys = array();
        // foreach ($_POST as $key => $value) {
        //     if (strpos($key, 'qs_') === 0) {
        //         $filteredKeys[$key] = $value;
        //     }
        // }

        // echo "<pre>";
        // print_r($_POST);
        // foreach ($filteredKeys as $key => $value) {
        // update_post_meta($post_id, $key, $value);

        // print_r(explode('__', $key));
        // $arr1 = explode('__', $key);
        // foreach ($arr1 as $v) {
        //     $arr2 = explode('-', $v);
        //     // print_r($arr2);
        //     $arr1[] = $arr2;
        // }
        // print_r($value);
        // }
        // echo "</pre>";
        // function json_create()
        // {
        //     $array = [];
        //     return $array;
        // }
        // exit;

        update_post_meta($post_id, 'quiz_data', $_POST['data']);
    }
}

add_action('add_meta_boxes', 'add_questions_to_quiz');
add_action('save_post', 'save_quiz_question_data');
