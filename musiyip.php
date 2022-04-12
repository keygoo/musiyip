<?php
/**
 * @package Musiyip
 */
/*
Plugin Name: Musiyip    
Plugin URI: https://github.com/retorica-puzzle/musiyip
Description: simple music player on wordpress
Version: 1.0
Author: Retorica Puzzle
Author URI: https://github.com/retorica-puzzle/musiyip
License: MIT
Text Domain: musiyip
*/

/*
MIT License

Copyright (c) 2022 Retorica Puzzle

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all
copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
SOFTWARE.
*/

defined( 'ABSPATH' ) or die( 'You cannot entering this zoness' );

if ( !class_exists( 'Musiyip' ) ) {

	class Musiyip
	{

		public $plugin;

		function __construct() {
			$this->plugin = plugin_basename( __FILE__ );
		}

		function register() {
			add_action( 'admin_enqueue_scripts', array( $this, 'enqueue' ) );

			add_action( 'admin_menu', array( $this, 'add_admin_pages' ) );

			add_filter( "plugin_action_links_$this->plugin", array( $this, 'settings_link' ) );
		}

		public function settings_link( $links ) {
			$settings_link = '<a href="admin.php?page=musiyip_admin">Settings</a>';
			array_push( $links, $settings_link );
			return $links;
		}

		public function add_admin_pages() {
			add_menu_page( 'Musiyip', 'Musiyip', 'manage_options', 'musiyip_plugin', array( $this, 'admin_index' ), 'dashicons-store', 110 );
		}

		public function admin_index() {
			require_once plugin_dir_path( __FILE__ ) . 'views/musiyip.php';
		}

		protected function create_post_type() {
			add_action( 'init', array( $this, 'custom_post_type' ) );
		}

		function custom_post_type() {
			register_post_type( 'book', ['public' => true, 'label' => 'Books'] );
		}

		function enqueue() {
			// enqueue all our scripts
			wp_enqueue_style( 'mypluginstyle', plugins_url( '/public/css/mystyle.css', __FILE__ ) );
			wp_enqueue_script( 'mypluginscript', plugins_url( '/public/css/myscript.js', __FILE__ ) );
		}

		function activate() {
			require_once plugin_dir_path( __FILE__ ) . 'inc/Musiyip-plugin-activate.php';
			MusiyipPluginActivate::activate();
		}
	}

	$musiyip = new Musiyip();
	$musiyip->register();
	register_activation_hook( __FILE__, array( $musiyip, 'activate' ) );
	require_once plugin_dir_path( __FILE__ ) . 'inc/Musiyip-plugin-deactivate.php';
	register_deactivation_hook( __FILE__, array( 'MusiyipPluginDeactivate', 'deactivate' ) );

}