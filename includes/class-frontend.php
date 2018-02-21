<?php
namespace App;

/**
 * Frontend Pages Handler
 */
class Frontend {

    public function __construct() {
        add_shortcode( 'vue-app', [ $this, 'render_frontend' ] );
    }

    /**
     * Render frontend app
     *
     * @param  array $atts
     * @param  string $content
     *
     * @return string
     */
    public function render_frontend( $atts, $content = '' ) {
        wp_enqueue_style( 'baseplugin-frontend' );
        wp_enqueue_script( 'baseplugin-frontend' );

        $content .= '<div id="vue-frontend-app"></div>';

        return $content;
    }
}
