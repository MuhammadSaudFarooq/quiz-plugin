<?php
class QuizPluginIntegration
{
    private $wpdb;
    private $pluginFileName = null;
    private $pluginDirectoryURL = null;
    private $pluginDirectoryPath = null;

    private const PLUGIN_PREFIX = 'qz';

    public function __construct($pluginFileName)
    {
        global $wpdb;
        $this->wpdb = $wpdb;
        $this->pluginFileName = $pluginFileName;
        $this->pluginDirectoryURL = plugin_dir_url($this->pluginFileName);
        $this->pluginDirectoryPath = plugin_dir_path($this->pluginFileName);
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
        require_once $this->getPostTypeUrl($this::PLUGIN_PREFIX . '_questions.php');
        require_once $this->getPostTypeUrl($this::PLUGIN_PREFIX . '_quiz.php');
    }

    public function enqueueAdminScripts()
    {
        wp_enqueue_script($this::PLUGIN_PREFIX . '_admin_js', $this->getScriptUrl($this::PLUGIN_PREFIX . '_admin_js.js'), array(), null, false);
        wp_localize_script($this::PLUGIN_PREFIX . '_admin_js', 'urls', array('ajax_url' => admin_url('admin-ajax.php'), 'site_url' => site_url()));
    }

    public function enqueueAdminStyles()
    {
        wp_enqueue_style($this::PLUGIN_PREFIX . '_admin_css', $this->getStyleUrl($this::PLUGIN_PREFIX . '_admin_css.css'), array(), null, 'all');
    }

    public function enqueueFrontScripts()
    {
        wp_enqueue_script($this::PLUGIN_PREFIX . '_front_js', $this->getScriptUrl($this::PLUGIN_PREFIX . '_front_js.js'), array(), null, false);
        wp_localize_script($this::PLUGIN_PREFIX . '_front_js', 'urls', array('ajax_url' => admin_url('admin-ajax.php'), 'site_url' => site_url()));
    }

    public function enqueueFrontStyles()
    {
        wp_enqueue_style($this::PLUGIN_PREFIX . '_front_css', $this->getStyleUrl($this::PLUGIN_PREFIX . '_front_css.css'), array(), null, 'all');
    }

    public function uninstallAction()
    {
        if (!current_user_can('activate_plugins')) {
            return;
        }
    }

    private function getPostTypeUrl($postTypeName)
    {
        return $this->pluginDirectoryPath . '/public/post-types/' . $postTypeName;
    }

    private function getScriptUrl($scriptName)
    {
        return $this->pluginDirectoryURL . 'public/js/' . $scriptName;
    }

    private function getStyleUrl($styleName)
    {
        return $this->pluginDirectoryURL . 'public/css/' . $styleName;
    }
}
