<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://profiles.wordpress.org/mbezuidenhout/
 * @since      1.0.0
 *
 * @package    Js_Package_Manager
 * @subpackage Js_Package_Manager/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Js_Package_Manager
 * @subpackage Js_Package_Manager/public
 * @author     Marius Bezuidenhout <marius dot bezuidenhout at gmail dot com>
 */
class Js_Package_Manager_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Js_Package_Manager_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Js_Package_Manager_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/js-package-manager-public.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Js_Package_Manager_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Js_Package_Manager_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/js-package-manager-public.js', array( 'jquery' ), $this->version, false );

	}

	/**
	 * JavaScript packages are replaced here with links to our template.
	 *
	 * @since    1.0.0
	 */
	public function js_package_manager() {
		global $wp_scripts;
		foreach ($wp_scripts->registered as $registered_script) {
			if ($registered_script->handle == 'jquery-core'
			    && $registered_script->src == '/wp-includes/js/jquery/jquery.min.js'
			    && $registered_script->ver == '3.5.1' ) {
				$registered_script->src = JS_PACKAGE_MANAGER_TEMPLATE_PATH . '/js/jquery/jquery.min.js';
			}
		}
	}

	/**
	 * Filters the path of the current template before including it.
	 * If file is not found then it might be one of the files handled by this js package manager.
	 *
	 * @since    1.0.0
	 *
	 * @param string $template The path of the template to include.
	 * @return string
	 */
	public function maybe_set_template( $template ) {
		$packages = array(
			'/js/jquery/jquery.min.js',
		);
		$request_uri = $_SERVER['REQUEST_URI'];
		if ( strpos( $request_uri, JS_PACKAGE_MANAGER_TEMPLATE_PATH ) === false ) {
			return $template;
		}
		foreach( $packages as $package ) {
			if ( strpos ( $request_uri, $package ) == strlen( JS_PACKAGE_MANAGER_TEMPLATE_PATH ) ) {
				return plugin_dir_path( __DIR__ ) . 'public/partials/js-package-manager-public-display.php';
			}
		}
		return $template;
	}

}
