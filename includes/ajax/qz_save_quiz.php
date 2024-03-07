<?php
// Save Quiz
if (isset($_POST['action']) && $_POST['action'] == PLUGIN_PREFIX . '_save_quiz') {
    global $wpdb;
    $tablename = PLUGIN_PREFIX . '_entries';
    $return = [
        'msg' => 'Something went wrong!',
        'status' => false
    ];

    $res = $wpdb->insert(
        $tablename,
        array(
            'data' => maybe_serialize($_POST['ques_data']),
            'created_at' => date('Y-m-d H:i:s')
        ),
        array('%s', '%s')
    );

    if ($res) {
        $return['msg'] = 'Inserted';
        $return['status'] = true;
    }

    print_r(json_encode($return));
    exit;
}
