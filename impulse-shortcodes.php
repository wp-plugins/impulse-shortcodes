<?php
/*
Plugin Name: Impulse Shortcodes
Plugin URI: https://www.impulse-themes.com/plugins/impulse-shortcodes
Description: A shortcode plugin for Bootstrap Components and FontAwesome shortcodes. 
Version: 0.5
Author: twoimpulse
Author URI: http://impulse-themes.com

*/

// Including Shortcodes here
require_once('font_awesome.php');
require_once('bootstrap.php');

class Impulse_Shortcodes{

    // add an item here following the name convention to add a plugin
    public $shortcodes = array(
        'bootstrap',
        'font_awesome'
    );



    public function __construct() {
        add_action( 'init' , array( &$this, 'init' ) );
        add_action( 'admin_print_scripts', 'admin_inline_js' );
    }


    function init() {
        if(is_admin()){
            wp_enqueue_style("bs_admin_style",  plugin_dir_url( __FILE__ ) .'/css/admin.css' );
            wp_enqueue_style("bs_shortcodes",  plugin_dir_url( __FILE__ ) .'/css/shortcodes.css' );
        }

        if ( ! current_user_can('edit_posts') && ! current_user_can('edit_pages') ) {
            return;
        }

        if ( get_user_option('rich_editing') == 'true' ) {
            add_filter( 'mce_external_plugins', array(&$this, 'regplugins') );
            add_filter( 'mce_buttons', array(&$this, 'regbtns') );
        }


    }

    function regbtns($buttons) {
        foreach ($this->shortcodes as &$shortcode) {
            array_push($buttons,'separator',$shortcode);
        }
        return $buttons;
    }

    function regplugins($plgs) {
        foreach ($this->shortcodes as &$shortcode) {
            $plugin_url =  plugin_dir_url( __FILE__ ). '/js/plugins/'. $shortcode . '.js';
            $plgs[$shortcode] = $plugin_url;
        }

        return $plgs;
    }


}

    function admin_inline_js(){
        $url = plugin_dir_url( __FILE__ );
        echo "<script type='text/javascript'>\n";
        echo 'var shortcode_url = "'.$url .'"';
        echo "\n</script>";
    }

$shortcodes = new Impulse_Shortcodes();
?>