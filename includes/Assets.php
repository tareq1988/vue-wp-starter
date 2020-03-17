<?php

namespace Kapil\App;

/**
 * Scripts and Styles Class
 */
class Assets
{
    /**
     * Set WP env for debugging and vue js hot load
     * @var string
     */
    protected $WP_ENV = 'prod';

    /**
     * Assets constructor.
     */
    function __construct()
    {
        if (is_admin()) {
            add_action('admin_enqueue_scripts', [$this, 'register'], 5);
        } else {
            add_action('wp_enqueue_scripts', [$this, 'register'], 5);
        }
    }

    /**
     * Register our app scripts and styles
     *
     * @return void
     */
    public function register()
    {
        $this->register_scripts($this->get_scripts());
        $this->register_styles($this->get_styles());
    }

    /**
     * Register scripts
     *
     * @param  array $scripts
     *
     * @return void
     */
    private function register_scripts($scripts)
    {
        foreach ($scripts as $handle => $script) {
            $deps = isset($script['deps']) ? $script['deps'] : false;
            $in_footer = isset($script['in_footer']) ? $script['in_footer'] : false;
            $version = isset($script['version']) ? $script['version'] : BASEPLUGIN_VERSION;

            wp_register_script($handle, $script['src'], $deps, $version, $in_footer);
        }
    }

    /**
     * Register styles
     *
     * @param  array $styles
     *
     * @return void
     */
    public function register_styles($styles)
    {
        foreach ($styles as $handle => $style) {
            $deps = isset($style['deps']) ? $style['deps'] : false;

            wp_register_style($handle, $style['src'], $deps, BASEPLUGIN_VERSION);
        }
    }

    /**
     * Get all registered scripts
     *
     * @return array
     */
    public function get_scripts()
    {
        $prefix = defined('SCRIPT_DEBUG') && SCRIPT_DEBUG ? '.min' : '';
        $pluginJSAssetsPath = ($this->WP_ENV == 'dev') ? 'http://localhost:8080/' : BASEPLUGIN_ASSETS . '/js/';

        $scripts = [
            'baseplugin-runtime' => [
                'src' => $pluginJSAssetsPath . 'runtime.js',
                'version' => filemtime(BASEPLUGIN_PATH . '/assets/js/runtime.js'),
                'in_footer' => true
            ],
            'baseplugin-vendor' => [
                'src' => $pluginJSAssetsPath . 'vendors.js',
                'version' => filemtime(BASEPLUGIN_PATH . '/assets/js/vendors.js'),
                'in_footer' => true
            ],
            'baseplugin-frontend' => [
                'src' => $pluginJSAssetsPath . 'frontend.js',
                'deps' => ['jquery', 'baseplugin-vendor', 'baseplugin-runtime'],
                'version' => filemtime(BASEPLUGIN_PATH . '/assets/js/frontend.js'),
                'in_footer' => true
            ],
            'baseplugin-admin' => [
                'src' => $pluginJSAssetsPath . 'admin.js',
                'deps' => ['jquery', 'baseplugin-vendor', 'baseplugin-runtime'],
                'version' => filemtime(BASEPLUGIN_PATH . '/assets/js/admin.js'),
                'in_footer' => true
            ]
        ];

        return $scripts;
    }

    /**
     * Get registered styles
     *
     * @return array
     */
    public function get_styles()
    {
        $pluginJSAssetsPath = ($this->WP_ENV == 'dev') ? 'http://localhost:8080/' : BASEPLUGIN_ASSETS . '/css/';

        $styles = [
            'baseplugin-style' => [
                'src' => $pluginJSAssetsPath . '/style.css'
            ],
            'baseplugin-frontend' => [
                'src' => $pluginJSAssetsPath . '/frontend.css'
            ],
            'baseplugin-admin' => [
                'src' => $pluginJSAssetsPath . '/admin.css'
            ],
        ];

        return $styles;
    }

}
