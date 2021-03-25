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
	 * @var      Js_Package[] $packages   Array of packages to match against.
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

		$loadable_packages = array(
		    array(
			    'id'   => 'jquery-min',
                'name' => 'jQuery',
                'versions' => array(
                    '3.5.1' => 'https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js',
                    '3.6.0' => 'https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js',
                ),
            ),
            array(
                'id'    => 'flickity',
                'name'  => 'Flickity.io',
                'versions' => array(
                    '1.1.0' => 'https://cdnjs.cloudflare.com/ajax/libs/flickity/1.1.0/flickity.pkgd.min.js',
	                '2.2.1' => 'https://cdnjs.cloudflare.com/ajax/libs/flickity/2.2.1/flickity.pkgd.min.js',
                    '2.2.2' => 'https://cdnjs.cloudflare.com/ajax/libs/flickity/2.2.2/flickity.pkgd.min.js',
                ),
            ),
            array(
                'id'    => 'moment-with-locales',
                'name'  => 'Moment',
                'versions' => array(
                    '2.10.6' => 'https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.10.6/moment-with-locales.min.js',
                    '2.11.0' => 'https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.11.0/moment-with-locales.min.js',
                    '2.29.0' => 'https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.0/moment-with-locales.min.js',
                    '2.29.1' => 'https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment-with-locales.min.js',
                ),
            ),
        );
		foreach( $loadable_packages as $package ) {
		    $pkg = new Js_Package( $package['id'], $package['name'] );
		    foreach( $package['versions'] as $ver => $src ) {
			    $pkg->add( $ver, $src );
		    }
		    $this->packages[] = $pkg;
		}

		/**
         * jQuery min
         * Version 3.5.1
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

		// Check that the user is allowed to update options
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( __( 'You do not have sufficient permissions to access this page.', '' ) );
		}

		/** @var Js_Package_Version[] $packages */
		$packages = array();
		$scripts = array();
		add_settings_section( 'js_package_manager_settings', 'JS Package Manager Settings', null, 'js_package_manager_plugin' );
		/** @var _WP_Dependency $registered_script */
		foreach ( $wp_scripts->registered as $registered_script ) {
			if ( ! property_exists( $registered_script, 'src' ) || empty( $registered_script->src ) ) {
				continue 1;
			}
			$script_file = $registered_script->src;
			if ( filter_var( $script_file, FILTER_VALIDATE_URL ) ) {
				if ( strpos( $script_file, $wp_scripts->base_url ) === 0 ) {
					$script_file = ABSPATH . ltrim( substr( $script_file, strlen( $wp_scripts->base_url ) ), '/' );
				}
			} else {
				$script_file = ABSPATH . ltrim( $script_file, '/' );
			}
			$found_packages = $this->find_packages( $script_file );
			if( !empty( $found_packages ) ) {
			    $parts = array();
				foreach ( $found_packages as $found_package ) {
					$packages[ $found_package->package->get_name() ] = $found_package->package_version;
					$parts = array('start' => $found_package->get_pos(), 'id' => $found_package->package->get_id(), 'ver' => $found_package->package_version->get_ver() );
				}
				$scripts[$registered_script->handle] = $parts;
			}
		}

		register_setting( 'js_package_manager_plugin_options', 'jspkgmgr_scripts' );
		update_option( 'jspkgmgr_scripts', $scripts );
		register_setting( 'js_package_manager_plugin_options', 'jspkgmgr_settings' );

		foreach( $packages as $package_ver ) {
		    $options = array(
		            array( 'value' => '', 'title' => 'Current (' . $package_ver->get_ver() . ')' ),
                    array( 'value' => '0', 'title' => 'Latest' ),
            );
		    foreach( $package_ver->package->get_versions() as $package_version ) {
		        $options[] = array( 'value' => $package_version, 'title' => $package_version );
            }
			add_settings_field(
				'js_package_manager_' . $package_ver->package->get_id(),
				$package_ver->package->get_name(),
				[ $this, 'package_selector_field' ],
				'js_package_manager_plugin',
				'js_package_manager_settings',
				[
					'label_for' => $package_ver->package->get_id(),
					'type'      => 'select',
					'options'   => $options,
				] );
        }

		$this->save_settings( $packages );
	}

	public function save_settings( $packages ) {
	    $settings = [];
	    foreach( $packages as $package_ver ) {
	        if( isset( $_POST[$package_ver->package->get_id()] ) ) {
		        $settings[$package_ver->package->get_id()] = $_POST[ $package_ver->package->get_id() ];
	        }
        }
	    if( isset( $_POST['option_page'] ) && strpos( $_POST['option_page'], 'js_package_manager_plugin_options' ) === 0 ) {
	        update_option( 'jspkgmgr_settings', $settings );
        }
    }

	/**
     * Find package and return their identified parts and positions in the script.
     *
	 * @param $script_file
	 *
	 * @return Js_Package_Part[]
	 */
	public function find_packages( $script_file ) {
		$found = array();
		$script = file_get_contents( $script_file );
		foreach( $this->packages as $package ) {
			$part = $package->find_in_script( $script );
			if( $part instanceof Js_Package_Part ) {
			    // Cut the found package out of the string so we don't match against it again.
			    $script = substr( $script, 0, $part->get_pos() ) . substr( $script, $part->get_pos() + $part->package_version->get_len() );
				$found[] = $part;
			}
		}
		return $found;
	}

	public function package_selector_field( $args ) {
		global $wp_settings_fields;
		$settings = get_option('jspkgmgr_settings');
		switch($args['type']) {
			case 'select':
			    echo "<select name=\"" . $args['label_for'] . "\" id=\"" . $args['label_for'] . "\">";
			    foreach( $args['options'] as $option ) {
			        $selected = '';
			        if( key_exists( $args['label_for'], $settings ) && $settings[ $args['label_for'] ] === $option['value'] ) {
			            $selected = ' selected="selected"';
                    }
			        echo "<option value=\"" . $option['value'] . "\"" . $selected . ">" . $option['title'] . "</option>";
                }
			    echo "</select>";
				break;
            case 'text':
	            echo "<input name=\"" . $args['label_for'] . "\" type=\"" . $args['type'] . "\" id=\"" . $args['label_for'] . "\" value=\"\" class=\"regular-text\">";
	            break;
		}
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
		$this->register_settings();
		?>
		<div class="wrap">
			<h1><?php echo esc_html(get_admin_page_title()) ?></h1>
			<?php settings_errors(); ?>
			<form action="" method="post">
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
