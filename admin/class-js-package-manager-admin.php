<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://profiles.wordpress.org/mbezuidenhout/
 * @since      1.0.0
 *
 * @package    Js_Package_Manager
 * @subpackage Js_Package_Manager/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Js_Package_Manager
 * @subpackage Js_Package_Manager/admin
 * @author     Marius Bezuidenhout <marius dot bezuidenhout at gmail dot com>
 */
class Js_Package_Manager_Admin {

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
	 * JavaScript packages class objects.
	 *
     * @since    1.0.0
     * @access   private
	 * @var      array     $packages   Array of packages to match against.
	 */
	private $packages;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

		$this->packages[] = new Js_Package( 'jquery-min', 'jQuery', '3.5.1', 'https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js' );

		/**
         * jQuery min
         * start: /*! jQuery
         * length: 89476
         * fingerprint: dc5e7f18c8d36ac1d3d4753a87c98d0a
         * end: $=S),S});\n
		 */
	}

	/**
	 * Register the stylesheets for the admin area.
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

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/js-package-manager-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
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

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/js-package-manager-admin.js', array( 'jquery' ), $this->version, false );

	}

	/**
	 * Register plugin settings
	 *
	 * @since    1.0.0
	 */
	public function register_settings() {
		global $wp_scripts;
		add_settings_section( 'js_package_manager_settings', 'JS Package Manager Settings', [ $this, 'plugin_section_text' ], 'js_package_manager_plugin' );
		foreach ( $wp_scripts->registered as $registered_script ) {
		    if( !property_exists( $registered_script, 'src' ) ) {
		        continue 1;
		    }
            $script_file = $registered_script->src;
			if ( filter_var ($script_file, FILTER_VALIDATE_URL ) ) {
				if ( strpos( $script_file, $wp_scripts->base_url ) === 0 ) {
					$script_file = ABSPATH . ltrim( substr( $script_file, strlen( $wp_scripts->base_url ) ), '/' );
				}
			} else {
				$script_file = ABSPATH . ltrim( $script_file, '/' );
			}
			$matched_parts = $this->js_package_match( $script_file );
			if ( count( $matched_parts ) ) {
				foreach ( $matched_parts as $part ) {
					add_settings_field(
						'js_package_manager_' . $part->package->get_id(),
						$part->package->get_name(),
						[ $this, 'package_selector_field' ],
						'js_package_manager_plugin',
						'js_package_manager_settings',
						[
							'label_for' => $part->package->get_id(),
							'type'      => 'select',
                            'options'   => array(
                                    array( 'value' => '', 'title' => 'Current (' . $part->package->get_ver() . ')' ),
                                    array( 'value' => '0', 'title' => 'Latest' ),
	                                array( 'value' => '3.5.0', 'title' => '3.5.0' ),
	                                array( 'value' => '3.5.1', 'title' => '3.5.1' ),
                                    array( 'value' => '3.6.0', 'title' => '3.6.0' ),
                            ),
						] );

				}
            }
            // Search for js packages in registered scripts
		}
	}

	public function package_selector_field( $args ) {
		global $wp_settings_fields;
		$options = get_option($args['label_for']);
		switch($args['type']) {
			case 'select':
			    echo "<select name=\"" . $args['label_for'] . "\" id=\"" . $args['label_for'] . "\">";
			    foreach( $args['options'] as $option ) {
			        echo "<option>" . $option['title'] . "</option>";
                }
			    echo "</select>";
				break;
            case 'text':
	            echo "<input name=\"" . $args['label_for'] . "\" type=\"" . $args['type'] . "\" id=\"" . $args['label_for'] . "\" value=\"\" class=\"regular-text\">";
	            break;
		}
	}

	/**
     * Search the script for JavaScript packages and return their position in the script.
     *
	 * @param $script_file
	 *
	 * @return array
	 */
	private function js_package_match( $script_file ) {
		$found = array();
		$script = file_get_contents( $script_file );
		for ( $i = 0; $i < count($this->packages); $i++ ) {
		    if (strlen($script) < $this->packages[$i]->get_len()) {
		        continue;
            }
            $pos = $this->packages[$i]->pos($script);
		    if ( $pos !== false ) {
                $found[] = new Js_Package_Part( $pos, $this->packages[$i] );
			    $script = substr($script, 0, $pos) . substr($script, $pos + $this->packages[$i]->get_len());
		    }
        }
		return $found;
    }

	public function plugin_section_text( $section ) {
		$test = 1;
	}

	/**
	 * Add plugin settings page.
	 *
	 * @since    1.0.0
	 */
	public function add_settings_page() {
		add_options_page( __('JS Package Manager Settings'), __('JS Package Manager'), 'manage_options', 'js-package-manager-settings', [ $this, 'render_settings_page' ] );
	}

	public function render_settings_page( $args ) {
		global $wp_settings_sections;
		?>
		<div class="wrap">
			<h1><?php echo esc_html(get_admin_page_title()) ?></h1>
			<form action="options.php" method="post">
				<?php
				settings_fields( 'js_package_manager_plugin_options' );
				do_settings_sections( 'js_package_manager_plugin' );
				submit_button();
				?>
			</form>
		</div>
		<?php
	}

	/**
	 * Add a direct link from the plugin page to the settings page for this plugin.
	 *
	 * @param array $links An array of links to display in the WordPress plugin list.
	 *
	 * @return array
	 */
	public function plugin_links( $links ) {
		$settings_url = add_query_arg(
			array(
				'page'    => 'js-package-manager-settings',
			),
			admin_url( 'admin.php' )
		);

		$plugin_links = array(
			'<a href="' . esc_url( $settings_url ) . '">' . __( 'Settings' ) . '</a>',
		);

		return array_merge( $plugin_links, $links );
	}

}
