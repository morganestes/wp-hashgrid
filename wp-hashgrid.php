<?php
/*
Plugin Name: WP Hashgrid
Plugin URI: http://morganestes.me/wp-hashgrid
Description: A basic implementation of <a href="http://www.hashgrid.com/" title="#grid website" target="_blank">hashgrid.js</a> (#grid) for use in designing and developing WordPress themes.
Version: 0.1.1
Author: Morgan Estes
Author URI: http://morganestes.me
License: GPLv3
*/

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Class WP_Hashgrid
 */
class WP_Hashgrid {

	function __construct() {

		add_action( 'wp_enqueue_scripts', array( &$this, 'load_assets' ) );

		/*
		if ( is_admin() ) {
			add_action( 'admin_menu', array( $this, 'add_plugin_page' ) );
			add_action( 'admin_init', array( $this, 'page_init' ) );
		}
		*/
	}

	function load_assets() {
		/** @todo Make the CSS customizable via Admin page. */
		wp_enqueue_script( 'wp-hashgrid', plugins_url( 'assets/hashgrid.js', __FILE__ ), array( 'jquery' ), '9', true );
		wp_enqueue_style( 'wp-hashgrid', plugins_url( 'assets/hashgrid.css', __FILE__ ) );
	}

	public function add_plugin_page() {
		// This page will be under "Settings"
		add_options_page( 'Settings Admin', 'Hashgrid', 'manage_options', 'test-setting-admin', array( $this, 'create_admin_page' ) );
	}

	public function create_admin_page() {
		?>
		<div class="wrap">
			<?php screen_icon(); ?>
			<h2>WP Hashgrid Options</h2>

			<form method="post" action="options.php">
				<?php
				// This prints out all hidden setting fields
				settings_fields( 'test_option_group' );
				do_settings_sections( 'test-setting-admin' );
				?>
				<?php submit_button(); ?>
			</form>
		</div>
	<?php
	}

	public function page_init() {
		register_setting( 'test_option_group', 'array_key', array( $this, 'check_ID' ) );

		add_settings_section(
			'setting_section_id',
			'Setting',
			array( $this, 'print_section_info' ),
			'test-setting-admin'
		);

		add_settings_field(
			'some_id',
			'Some ID(Title)',
			array( $this, 'create_an_id_field' ),
			'test-setting-admin',
			'setting_section_id'
		);
	}

	public function check_ID( $input ) {
		if ( is_numeric( $input['some_id'] ) ) {
			$mid = $input['some_id'];
			if ( get_option( 'test_some_id' ) === false ) {
				add_option( 'test_some_id', $mid );
			}
			else {
				update_option( 'test_some_id', $mid );
			}
		}
		else {
			$mid = '';
		}

		return $mid;
	}

	public function print_section_info() {
		print 'Enter your setting below:';
	}

	public function create_an_id_field() {
		?><input type="text" id="input_whatever_unique_id_I_want" name="array_key[some_id]" value="<?php echo get_option( 'test_some_id' ); ?>" /><?php
	}
}

$hashgrid = new WP_Hashgrid;
