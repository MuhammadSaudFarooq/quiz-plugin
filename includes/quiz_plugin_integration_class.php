<?php
class QuizPluginIntegration
{
    private $wpdb;
    private $pluginFileName = null;
    private $categoryKeyName = null;

    public function __construct($pluginFileName)
    {
        global $wpdb;
        $this->wpdb = $wpdb;
        $this->pluginFileName = $pluginFileName;
        $this->categoryKeyName = PLUGIN_PREFIX . "_quiz_categories";
    }

    public function registerActions()
    {
        register_uninstall_hook($this->pluginFileName, [$this, 'uninstallAction']);
        add_action('init', [$this, 'registerPostTypes']);
        add_action('admin_enqueue_scripts', [$this, 'enqueueAdminScripts']);
        add_action('admin_enqueue_scripts', [$this, 'enqueueAdminStyles']);
        add_action('wp_enqueue_scripts', [$this, 'enqueueFrontScripts']);
        add_action('wp_enqueue_scripts', [$this, 'enqueueFrontStyles']);
        add_action('wp_ajax_' . PLUGIN_PREFIX . '_get_questions', [$this, 'ajaxGetQuestions']);
        add_action('wp_ajax_' . PLUGIN_PREFIX . '_condition_options', [$this, 'ajaxConditionOptions']);
        add_shortcode('quizzes', [$this, 'quizzesShortcode']);

        if (!get_option($this->categoryKeyName)) {
            $quizCategories = [
                'category-1'    => 'Shoulder',
                'category-2'    => 'Neck',
                'category-3'    => 'Arm',
                'category-4'    => 'Head',
                'category-5'    => 'Legs',
                'category-6'    => 'Chest',
                'category-7'    => 'Calves',
                'category-8'    => 'Backbone',
                'category-9'    => 'Lower Back',
                'category-10'   => 'Traps',
                'category-11'   => 'Abs',
                'category-12'   => 'Forearms'
            ];
            add_option($this->categoryKeyName, $quizCategories);
        }
    }

    public function registerPostTypes()
    {
        require_once $this->getPostTypeUrl(PLUGIN_PREFIX . '_questions.php');
        require_once $this->getPostTypeUrl(PLUGIN_PREFIX . '_quiz.php');
    }

    public function enqueueAdminScripts()
    {
        wp_enqueue_script('font_awesome_js', $this->getScriptUrl('all.min.js'), array(), null, false);
        wp_enqueue_script(PLUGIN_PREFIX . '_admin_js', $this->getScriptUrl(PLUGIN_PREFIX . '_admin_js.js'), array(), null, false);
        wp_localize_script(PLUGIN_PREFIX . '_admin_js', 'URLs', array('AJAX_URL' => admin_url('admin-ajax.php'), 'SITE_URL' => site_url(), 'PLUGIN_PREFIX' => PLUGIN_PREFIX));
    }

    public function enqueueAdminStyles()
    {
        wp_enqueue_style('font_awesome_css', $this->getStyleUrl('all.min.css'), array(), null, 'all');
        wp_enqueue_style(PLUGIN_PREFIX . '_admin_css', $this->getStyleUrl(PLUGIN_PREFIX . '_admin_css.css'), array(), null, 'all');
    }

    public function enqueueFrontScripts()
    {
        wp_enqueue_script('jquery', 'https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js', array(), '3.6.0', true);
        wp_enqueue_script('font_awesome_js', $this->getScriptUrl('all.min.js'), array(), null, false);
        wp_enqueue_script(PLUGIN_PREFIX . '_front_js', $this->getScriptUrl(PLUGIN_PREFIX . '_front_js.js'), array(), null, false);
        wp_localize_script(PLUGIN_PREFIX . '_front_js', 'URLs', array('AJAX_URL' => admin_url('admin-ajax.php'), 'SITE_URL' => site_url(), 'PLUGIN_PREFIX' => PLUGIN_PREFIX));
    }

    public function enqueueFrontStyles()
    {
        wp_enqueue_style('font_awesome_css', $this->getStyleUrl('all.min.css'), array(), null, 'all');
        wp_enqueue_style(PLUGIN_PREFIX . '_front_css', $this->getStyleUrl(PLUGIN_PREFIX . '_front_css.css'), array(), null, 'all');
    }

    public function uninstallAction()
    {
        if (!current_user_can('activate_plugins')) {
            return;
        }
    }

    public function ajaxGetQuestions()
    {
        require_once PLUGIN_DIR_PATH . "/includes/ajax/" . PLUGIN_PREFIX . "_get_questions.php";
    }

    public function ajaxConditionOptions()
    {
        require_once PLUGIN_DIR_PATH . "/includes/ajax/" . PLUGIN_PREFIX . "_condition_options.php";
    }

    public function quizzesShortcode()
    {
        require_once PLUGIN_DIR_PATH . "/includes/templates/" . PLUGIN_PREFIX . "_view.php";
    }

    private function getPostTypeUrl($postTypeName)
    {
        return PLUGIN_DIR_PATH . '/includes/post-types/' . $postTypeName;
    }

    private function getScriptUrl($scriptName)
    {
        return PLUGIN_DIR_URL . 'public/js/' . $scriptName;
    }

    private function getStyleUrl($styleName)
    {
        return PLUGIN_DIR_URL . 'public/css/' . $styleName;
    }
}
