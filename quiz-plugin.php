<?php

/**
 * Plugin Name: Quiz Plugin
 * Plugin URI: https://github.com/MuhammadSaudFarooq
 * Description: Make shortcode of Quiz Plugin --- Shortcode: [QUIZ]
 * Author: Muhammad Saud Farooque
 * Author URI: https://github.com/MuhammadSaudFarooq
 * Version: 1.0.0
 * Text Domain: https://github.com/MuhammadSaudFarooq
 * License: MIT
 **/

if (!defined('ABSPATH')) {
    exit;
}

$template = __DIR__ . DIRECTORY_SEPARATOR . 'includes' . DIRECTORY_SEPARATOR . 'template' . DIRECTORY_SEPARATOR;
require_once __DIR__ . DIRECTORY_SEPARATOR . 'includes' . DIRECTORY_SEPARATOR . 'quiz_plugin_integration_class.php';

$quizPlugin  = new QuizPluginIntegration(__FILE__);
$quizPlugin->registerActions();
