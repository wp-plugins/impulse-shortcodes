<?php
/*
Plugin Name: Impulse Shortcodes
Plugin URI: https://www.impulse-themes.com/plugins/impulse-shortcodes
Description: A shortcode plugin for Bootstrap Components and FontAwesome shortcodes. 
Version: 0.5.3
Author: twoimpulse
Author URI: http://impulse-themes.com
*/

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
        } else {
            add_action( 'wp_enqueue_scripts', array(&$this, 'impulse_shortcodes_register_scripts') );
            add_action( 'wp_enqueue_scripts', array(&$this, 'impulse_shortcodes_register_styles')   );
        }



        if ( ! current_user_can('edit_posts') && ! current_user_can('edit_pages') ) {
            return;
        }

        if ( get_user_option('rich_editing') == 'true' ) {
            add_filter( 'mce_external_plugins', array(&$this, 'regplugins') );
            add_filter( 'mce_buttons', array(&$this, 'regbtns') );
        }



    }

    function impulse_shortcodes_register_scripts()
    {

        $handle = 'bootstrap.min.js';
        $list = 'enqueued';
        if (wp_script_is( $handle, $list )) {
            return;
        } else {
            wp_register_script('bootstrap', plugin_dir_url(__FILE__) . '/js/bootstrap.min.js');
            wp_enqueue_script('bootstrap');
        }


    }

    function impulse_shortcodes_register_styles()
    {

        $handle = 'bootstrap.min.css';
        $list = 'enqueued';
        if (wp_style_is( $handle, $list )) {
            return;
        } else {
            wp_register_style( 'bootstrap',  plugin_dir_url( __FILE__ ) . '/css/bootstrap.min.css');
            wp_enqueue_style('bootstrap' );
        }

        $handle = 'font-awesome.min.css';
        $list = 'enqueued';
        if (wp_style_is( $handle, $list )) {
            return;
        } else {
            wp_register_style( 'font-awesome', plugin_dir_url( __FILE__ ) . '/css/font-awesome.min.css', array(), null, 'all' );
            wp_enqueue_style('font-awesome' );
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