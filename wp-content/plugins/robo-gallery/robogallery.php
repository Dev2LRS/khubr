<?php
/*
Plugin Name: Robo Gallery
Plugin URI: https://robosoft.co/wordpress-gallery-plugin
Description: Gallery modes photo gallery, images gallery, video gallery, Polaroid gallery, gallery lighbox, portfolio gallery, responsive gallery
Version: 2.7.8
Author: RoboSoft
Author URI: https://robosoft.co/wordpress-gallery-plugin
License: GPLv3 or later
Text Domain: robo-gallery
Domain Path: /languages
*/

if(!defined('WPINC'))die;
if(!defined("ABSPATH"))exit;


define("ROBO_GALLERY", 1); 
define("ROBO_GALLERY_VERSION", '2.7.8'); 

if( !defined("ROBO_GALLERY_PATH") ) define("ROBO_GALLERY_PATH", plugin_dir_path( __FILE__ ));

define("ROBO_GALLERY_SPECIAL", 0); 
define("ROBO_GALLERY_EVENT_DATE", '2016-12-08'); 
define("ROBO_GALLERY_EVENT_HOUR", 20); 

add_action( 'plugins_loaded', 'rbs_gallery_load_textdomain' );
function rbs_gallery_load_textdomain() {
  load_plugin_textdomain( 'robo-gallery', false, dirname(plugin_basename( __FILE__ )) . '/languages' ); 
}


/*add_action('wp', array($this, 'buffer_start'), 1000000);
*/

/*add_action( 'plugins_loaded', 'robo_cache_check', 1000000);
function robo_cache_check(){
	if ( !is_admin() ) { 
		if(
			isset($_SERVER) &&
			isset($_SERVER['HTTP_HOST']) &&
			isset($_SERVER['REQUEST_URI']) 
		) {

			$fileCacheName = md5($_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']);
			$fileCacheFullName = ROBO_GALLERY_PATH.'/cache/html/'.$fileCacheName;

			if(file_exists($fileCacheFullName)){
				$cacheContent = file_get_contents($fileCacheFullName);
				echo $cacheContent;
				die();
			}
		}

		ob_start('robo_cache_html_compress');
	}
}

add_action('shutdown',  'robo_cache_write', 1000000);
add_action( 'wp_loaded', 'robo_cache_write', 1000000);
function robo_cache_write() {
    if ( !is_admin() ) { 
        ob_start('robo_cache_html_compress');
    }
}

function robo_cache_html_compress( $html ) {
	if(
		isset($_SERVER) &&
		isset($_SERVER['HTTP_HOST']) &&
		isset($_SERVER['REQUEST_URI']) 
	) {
		$fileCacheName = md5($_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']);
		$fileCacheFullName = ROBO_GALLERY_PATH.'/cache/html/'.$fileCacheName;
		if(!file_exists($fileCacheFullName)){
			$htmlComment = '<!-- read from cache file '.$fileCacheName.' url '.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'].' -->';
			file_put_contents( $fileCacheFullName, $html.$htmlComment);		
		}
	}
	return $html;
}*/


if(!function_exists('rbs_gallery_pro_check')){
	function rbs_gallery_pro_check(){
		$proPath 	= '';
		$key_dir  	= 'robogallerykey';
		$key_file 	= $key_dir.'.php';
		$proPath = ROBO_GALLERY_PATH.$key_file;
		if( file_exists($proPath) ) return $proPath;
		for($i=-1;$i<6;$i++){ 
			$proPath = WP_PLUGIN_DIR.'/'.$key_dir.($i!=-1?'-'.$i:'').'/'.$key_file;
			if ( file_exists($proPath) ) return $proPath;
		}
		return false;
	}
}

if( $keyResult=rbs_gallery_pro_check() ){
	define("ROBO_GALLERY_PRO", 1);
	define("ROBO_GALLERY_KEY_PATH", $keyResult );
	include_once( ROBO_GALLERY_KEY_PATH );
} else {
	define("ROBO_GALLERY_PRO", 0);
}

define("ROBO_GALLERY_INCLUDES_PATH", 	ROBO_GALLERY_PATH.'includes/');
define("ROBO_GALLERY_FRONTEND_PATH", 	ROBO_GALLERY_INCLUDES_PATH.'frontend/');
define("ROBO_GALLERY_FRONTEND_EXT_PATH",ROBO_GALLERY_FRONTEND_PATH.'extensions/');


define("ROBO_GALLERY_OPTIONS_PATH", 	ROBO_GALLERY_INCLUDES_PATH.'options/');
define("ROBO_GALLERY_EXTENSIONS_PATH", 	ROBO_GALLERY_INCLUDES_PATH.'extensions/');
define("ROBO_GALLERY_CMB_PATH", 		ROBO_GALLERY_PATH.'cmb2/');
define("ROBO_GALLERY_CMB_FILEDS_PATH", 	ROBO_GALLERY_CMB_PATH.'fields/');

define("ROBO_GALLERY_URL", 				plugin_dir_url( __FILE__ ));

function activateRoboGallery() {
	require_once ROBO_GALLERY_INCLUDES_PATH.'rbs_class_activator.php';
	Robo_Gallery_Activator::activate();
}
register_activation_hook( __FILE__, 'activateRoboGallery' );

function deactivateRoboGallery() {
	require_once ROBO_GALLERY_INCLUDES_PATH.'rbs_class_activator.php';
	Robo_Gallery_Activator::deactivate();
}
register_deactivation_hook( __FILE__, 'deactivateRoboGallery' );

if( file_exists(ROBO_GALLERY_INCLUDES_PATH.'rbs_gallery_init.php') )  
		require_once ROBO_GALLERY_INCLUDES_PATH.'rbs_gallery_init.php';