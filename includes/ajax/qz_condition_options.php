<?php

$return = [
    'data' => [],
    'status' => false,
    'msg' => 'No data found.'
];

if (isset($_POST['action']) && $_POST['action'] === PLUGIN_PREFIX . '_condition_options') {
    $value = $_POST['value'];

    if ($value === PLUGIN_PREFIX . '-questions') {
        $question_args = array(
            'post_type' => PLUGIN_PREFIX . '-questions',
            'post_status' => 'publish',
            'posts_per_page' => -1,
            'orderby' => 'title',
            'order' => 'ASC',
        );
        $question_loop = new WP_Query($question_args);
        $question_loop = $question_loop->posts;
        if (!empty($question_loop)) {
            foreach ($question_loop as $key => $value) {
                $return['data'][] = [
                    'id' => $value->ID,
                    'title' => $value->post_title
                ];
            }
            $return['status'] = true;
            $return['msg'] = 'true';
        }
    }
}
print_r(json_encode($return));
exit;
