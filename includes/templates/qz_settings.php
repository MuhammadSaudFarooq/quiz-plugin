<?php
// Get Builtin post types as List
// $get_custom_post_types = get_post_types(array(
//     'public'   => true,
//     '_builtin' => false
// ), 'names');
$get_all_post_types = get_post_types('', 'names');

if (isset($_POST) && isset($_POST['submit']) && $_POST['submit'] == 'save') {
    update_option(PLUGIN_PREFIX . '_conditional_post_type', $_POST['qz_post_types']);
}

function formatString($str)
{
    $formattedStr = str_replace(array('_', '-'), ' ', $str);
    $formattedStr = ucwords($formattedStr);
    return $formattedStr;
}
?>
<div id="quiz-settings">
    <div>
        <h1>Quiz Settings</h1>
    </div>
    <div class="shortcode">
        <h2>Shortcode</h2>
        <p>[quizzes]</p>
    </div>
    <div>
        <h2>Select Post type for redirection</h2>
        <?php
        $saved_post_type = get_option(PLUGIN_PREFIX . '_conditional_post_type');
        $count = 1;
        echo '<form action="" method="post" class="post-type-list">';
        echo '<select name="qz_post_types" required>';
        echo '<option value="" disabled selected>Select post type...</option>';
        foreach ($get_all_post_types as $key => $value) {
            // if ($value == 'post' || $value == 'page' || $value == 'conditions') {

            // echo '<label for="qz_post_types-' . $count . '">';
            // echo '<input type="radio" name="qz_post_types" id="qz_post_types-' . $count . '" value="' . $key . '" required ' . ((isset($saved_post_type) && $saved_post_type == $value) ? "checked" : "") . '>';
            // echo '<span>' . formatString($value) . '</span>';
            // echo '</label>';


            echo '<option value="' . $key . '" ' . ((isset($saved_post_type) && $saved_post_type == $value) ? "selected" : "") . '>' . formatString($value) . '</option>';

            $count++;
            // }
        }
        echo '</select>';
        echo '<div>';
        echo '<input type="submit" class="button button-primary button-large" name="submit" value="save"/>';
        echo '</div>';
        echo '</form>';
        ?>
    </div>
</div>