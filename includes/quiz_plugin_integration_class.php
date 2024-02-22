<?php
class QuizPluginIntegration
{
    private $wpdb;
    private $pluginFileName = null;

    public function __construct($pluginFileName)
    {
        global $wpdb;
        $this->wpdb = $wpdb;
        $this->pluginFileName = $pluginFileName;
    }

    public function registerActions()
    {
        register_uninstall_hook($this->pluginFileName, [$this, 'uninstallAction']);
        add_action('init', [$this, 'registerPostTypes']);
        add_action('admin_enqueue_scripts', [$this, 'enqueueAdminScripts']);
        add_action('admin_enqueue_scripts', [$this, 'enqueueAdminStyles']);
        add_action('wp_enqueue_scripts', [$this, 'enqueueFrontScripts']);
        add_action('wp_enqueue_scripts', [$this, 'enqueueFrontStyles']);
    }

    public function registerPostTypes()
    {
        require_once $this->getPostTypeUrl(PLUGIN_PREFIX . '_questions.php');
        require_once $this->getPostTypeUrl(PLUGIN_PREFIX . '_quiz.php');
    }

    public function enqueueAdminScripts()
    {
        wp_enqueue_script(PLUGIN_PREFIX . '_admin_js', $this->getScriptUrl(PLUGIN_PREFIX . '_admin_js.js'), array(), null, false);
        wp_localize_script(PLUGIN_PREFIX . '_admin_js', 'urls', array('ajax_url' => admin_url('admin-ajax.php'), 'site_url' => site_url()));
    }

    public function enqueueAdminStyles()
    {
        wp_enqueue_style(PLUGIN_PREFIX . '_admin_css', $this->getStyleUrl(PLUGIN_PREFIX . '_admin_css.css'), array(), null, 'all');
    }

    public function enqueueFrontScripts()
    {
        wp_enqueue_script(PLUGIN_PREFIX . '_front_js', $this->getScriptUrl(PLUGIN_PREFIX . '_front_js.js'), array(), null, false);
        wp_localize_script(PLUGIN_PREFIX . '_front_js', 'urls', array('ajax_url' => admin_url('admin-ajax.php'), 'site_url' => site_url()));
    }

    public function enqueueFrontStyles()
    {
        wp_enqueue_style(PLUGIN_PREFIX . '_front_css', $this->getStyleUrl(PLUGIN_PREFIX . '_front_css.css'), array(), null, 'all');
    }

    public function uninstallAction()
    {
        if (!current_user_can('activate_plugins')) {
            return;
        }
    }

    private function getPostTypeUrl($postTypeName)
    {
        return PLUGIN_DIR_PATH . '/public/post-types/' . $postTypeName;
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
