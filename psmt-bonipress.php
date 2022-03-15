<?php
/**
 * Plugin Name: PS-Mediathek - BoniPress Addon
 * Plugin URI: https://n3rds.work
 * Version: 1.0.0
 * Author: WMS N@W
 * Author URI: https://n3rds.work
 * Description: Gib Benutzern Punkte für ihren Medien-Upload mit BoniPress
 * License: GPL
 * 
 */

require 'psource/psource-plugin-update/psource-plugin-updater.php';
$MyUpdateChecker = Puc_v4_Factory::buildUpdateChecker(
	'https://n3rds.work//wp-update-server/?action=get_metadata&slug=psmt-bonipress', 
	__FILE__, 
	'psmt-bonipress' 
);

/**
 * This class sets up the BoniPress type and  
 * loads the BoniPress action helper
 * 
 */
class PSMT_BoniPress_Helper {
	/**
	 * Singleton Instance
	 * 
	 * @var PSMT_BoniPress_Helper 
	 */
	private static $instance = null;
	
	private function __construct () {
		
		$this->setup_hooks();
	}
	/**
	 * Get the singleton instance
	 * 
	 * @return PSMT_BoniPress_Helper
	 */
	public static function get_instance() {
		
		if( is_null( self::$instance ) ) {
			self::$instance = new self();
		}
		
		return self::$instance;
		
	}
	/**
	 * Setup hooks 
	 */
	private function setup_hooks() {
		
		add_action( 'psmt_loaded', array( $this, 'load' ) );
		add_filter( 'bonipress_setup_hooks', array( $this, 'setup_bonipress_type' ) );
		
		$this->load_textdomain();
		
	}
	
	/**
	 * Load required files
	 * 
	 */
	public function load() {

		$path = plugin_dir_path( __FILE__ );

		if( class_exists( 'BoniPress_Hook' ) ) {	
			require_once  $path . 'core/actions.php';
		}
	}

	private function load_textdomain() {
		
		load_plugin_textdomain( 'psmt-bonipress', FALSE, basename( dirname( __FILE__ ) ) . '/languages/' );
		
	}
	
	public function setup_bonipress_type( $installed ) {

		$installed['mediapress'] = array(
			'title'       => __( 'PS-Mediathek', 'bonipress' ),
			'description' => __( 'Gewähren/Abziehen von %_plural% für Benutzer, die Galerien erstellen oder neue Medien hochladen.', 'psmt-bonipress' ),
			'callback'    => array( 'PSMT_BoniPress_Actions_Helper' )
		);
		
		return $installed;
	}
	
	
}

PSMT_BoniPress_Helper::get_instance();