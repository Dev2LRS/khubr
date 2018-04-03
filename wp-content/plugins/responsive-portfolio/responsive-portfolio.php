<?php
/**
 * Plugin Name: Responsive Portfolio Gallery
 * Version: 2.6.18
 * Description: Responsive Portfolio Allow You To Add Unlimited Images To Your Portfolio Integrated With Light Box Preview And Masonry Effect.
 * Author: Weblizar
 * Author URI: http://weblizar.com/plugins/responsive-portfolio/
 * Plugin URI: http://weblizar.com/plugins/responsive-portfolio/
 */

/**
 * Constant Variable
 */
define("WEBLIZAR_RP_TEXT_DOMAIN","weblizar_rpg" );
define("WEBLIZAR_RP_PLUGIN_URL", plugin_dir_url(__FILE__));

// Run 'Install' script on plugin activation
register_activation_hook( __FILE__, 'DefaultSettingsPortfolio' );
function DefaultSettingsPortfolio(){
    $DefaultSettingsArray = serialize( array(
        'WL_Hover_Animation' 		=> "fade",
        'WL_Gallery_Layout' 		=> "col-md-6",
        'WL_Image_Border' 			=> "yes",
        'WL_Hover_Color_Opacity' 	=> 0.5,
        'WL_Font_Style' 			=> "Arial",
        'WL_Masonry_Layout' 		=> "no",
		'WL_Gallery_Title' 			=> "yes",
		'WL_Zoom_Animation' 		=> "yes",
		'WL_Image_Border_Color'		=> "#ffffff",	
    ) );
    add_option("WRP_Portfolio_Settings", $DefaultSettingsArray);
}

function admin_content_rpp_144936() {
	if(get_post_type()=="responsive-portfolio") { ?>
		<style>
		.wlTBlock{
			background: linear-gradient( rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0.5) ), url('<?php echo WEBLIZAR_RP_PLUGIN_URL.'/images/bg2.jpg'; ?>') no-repeat fixed;
			background-position: 50% 0 !important;
			padding: 27px 0 23px 0;
			margin-left: -20px;
			font-family: Myriad Pro ;
			cursor: pointer;
			text-align: center;
		}
		.wlTBlock .wlTBig{
			color: white;
			font-size: 30px;
			font-weight: bolder;
			padding: 0 0 15px 0;
		}
		.wlTBlock .wlTBig .dashicons{
			font-size: 40px;
			position: absolute;
			margin-left: -45px;
			margin-top: -10px;
		}
		.wlTBlock .WlTSmall{
			font-weight: bolder;
			color: white;
			font-size: 18px;
			padding: 0 0 15px 15px;
		}
		.wlTBlock a{
			text-decoration: none;
		}
		@media screen and ( max-width: 600px ) {
			.wlTBlock{ padding-top: 60px; margin-bottom: -50px; }
			.wlTBlock .WlTSmall { display: none; }
		}
		</style>
		<div class="wlTBlock ">
			<a href="https://weblizar.com/plugins/responsive-portfolio-pro/" target="_new">
				<div class="wlTBig"><span class="dashicons dashicons-cart"></span>Get Pro version</div>
				<div class="WlTSmall">with PRO version you get more advanced functionality and even more flexibility in settings </div>
			</a>
		</div>
	<?php  
	} 
}
add_action('in_admin_header','admin_content_rpp_144936');

//Plugin Translation
add_action('plugins_loaded', 'GetReadyTranslationPortfolio');
function GetReadyTranslationPortfolio() {
	load_plugin_textdomain(WEBLIZAR_RP_TEXT_DOMAIN, FALSE, dirname( plugin_basename(__FILE__)).'/languages/' );
}

// CPT
function ResponsivePortfolio() {
    $labels = array(
        'name'                => _x( 'Responsive Portfolio', 'Responsive Portfolio', WEBLIZAR_RP_TEXT_DOMAIN ),
        'singular_name'       => _x( 'Responsive Portfolio', 'Responsive Portfolio', WEBLIZAR_RP_TEXT_DOMAIN ),
        'menu_name'           => __( 'Responsive Portfolio', WEBLIZAR_RP_TEXT_DOMAIN ),
        'parent_item_colon'   => __( 'Parent Item:', WEBLIZAR_RP_TEXT_DOMAIN ),
        'all_items'           => __( 'All Portfolio', WEBLIZAR_RP_TEXT_DOMAIN ),
        'view_item'           => __( 'View Portfolio', WEBLIZAR_RP_TEXT_DOMAIN ),
        'add_new_item'        => __( 'Add New Portfolio', WEBLIZAR_RP_TEXT_DOMAIN ),
        'add_new'             => __( 'Add New Portfolio', WEBLIZAR_RP_TEXT_DOMAIN ),
        'edit_item'           => __( 'Edit Portfolio', WEBLIZAR_RP_TEXT_DOMAIN ),
        'update_item'         => __( 'Update Portfolio', WEBLIZAR_RP_TEXT_DOMAIN ),
        'search_items'        => __( 'Search Portfolio', WEBLIZAR_RP_TEXT_DOMAIN ),
        'not_found'           => __( 'No Gallery Found', WEBLIZAR_RP_TEXT_DOMAIN ),
        'not_found_in_trash'  => __( 'No Gallery found in Trash', WEBLIZAR_RP_TEXT_DOMAIN ),
    );
    $args = array(
        'label'               => __( 'Responsive Portfolio', WEBLIZAR_RP_TEXT_DOMAIN ),
        'description'         => __( 'Responsive Portfolio', WEBLIZAR_RP_TEXT_DOMAIN ),
        'labels'              => $labels,
        'supports'            => array( 'title', '', '', '', '', '', '', '', '', '', '', ),
        //'taxonomies'        => array( 'category', 'post_tag' ),
         'hierarchical'       => false,
        'public'              => false,
        'show_ui'             => true,
        'show_in_menu'        => true,
        'show_in_nav_menus'   => false,
        'show_in_admin_bar'   => false,
        'menu_position'       => 5,
        'menu_icon'           => 'dashicons-format-gallery',
        'can_export'          => true,
        'has_archive'         => true,
        'exclude_from_search' => false,
        'publicly_queryable'  => false,
        'capability_type'     => 'page',
    );
    register_post_type( 'responsive-portfolio', $args );
}

// Hook into the 'init' action
add_action( 'init', 'ResponsivePortfolio', 0 );

/**
 * Add Meta Box & load required CSS and JS for interface
 */
add_action('admin_init','ResponsivePortfolio_init');
function ResponsivePortfolio_init() {
    add_meta_box('ResponsivePortfolio_meta', __('Add New Images', WEBLIZAR_RP_TEXT_DOMAIN), 'responsive_portfolio_function', 'responsive-portfolio', 'normal', 'high');
    add_action('save_post','responsive_portfolio_meta_save');
	add_meta_box(__('Plugin Shortcode', WEBLIZAR_RP_TEXT_DOMAIN) , __('Plugin Shortcode', WEBLIZAR_RP_TEXT_DOMAIN), 'rp_plugin_shortcode', 'responsive-portfolio', 'side', 'low');
	add_meta_box(__('Show us some love, Rate Us', WEBLIZAR_RP_TEXT_DOMAIN) , __('Show us some love, Rate Us', WEBLIZAR_RP_TEXT_DOMAIN), 'Rate_us_meta_box_function_portfolio', 'responsive-portfolio', 'side', 'low');
	add_meta_box(__('Upgrade To Pro Version', WEBLIZAR_RP_TEXT_DOMAIN) , __('Upgrade To Pro Version', WEBLIZAR_RP_TEXT_DOMAIN), 'wrp_upgrade_to_pro_function', 'responsive-portfolio', 'side', 'low');
	add_meta_box(__('Pro Features', WEBLIZAR_RP_TEXT_DOMAIN) , __('Pro Features', WEBLIZAR_RP_TEXT_DOMAIN), 'wrp_pro_features', 'responsive-portfolio', 'side', 'low');
	//wp_enqueue_script('media-upload');
	wp_enqueue_script('theme-preview');
    wp_enqueue_script('rpg-media-uploads',WEBLIZAR_RP_PLUGIN_URL.'js/rpg-media-upload-script.js',array('media-upload','thickbox','jquery'));
    wp_enqueue_style('dashboard');
    wp_enqueue_style('rpg-meta-css', WEBLIZAR_RP_PLUGIN_URL.'css/rpg-meta.css');
    wp_enqueue_style('thickbox');
	
	//color-picker css n js
	wp_enqueue_style( 'wp-color-picker' );
	wp_enqueue_script( 'rpgp-color-picker-script1', plugins_url('js/wl-color-picker.js', __FILE__ ), array( 'wp-color-picker' ), false, true );
}

/**
 * Rate Us Meta Box
 */
function Rate_us_meta_box_function_portfolio() { ?>
	<style>
	.fag-rate-us span.dashicons{
		width: 30px;
		height: 30px;
	}
	.fag-rate-us span.dashicons-star-filled:before {
		content: "\f155";
		font-size: 30px;
	}
	</style>
	<div align="center">
		<p>Please Review & Rate Us On WordPress</p>
		<a class="upgrade-to-pro-demo .fag-rate-us" style=" text-decoration: none; height: 40px; width: 40px;" href="http://wordpress.org/plugins/responsive-portfolio/" target="_blank">
			<span class="dashicons dashicons-star-filled"></span>
			<span class="dashicons dashicons-star-filled"></span>
			<span class="dashicons dashicons-star-filled"></span>
			<span class="dashicons dashicons-star-filled"></span>
			<span class="dashicons dashicons-star-filled"></span>
		</a>
	</div>
	<div class="upgrade-to-pro-demo" style="text-align:center;margin-bottom:10px;margin-top:10px;">
		<a href="http://wordpress.org/plugins/responsive-portfolio/" target="_blank" class="button button-primary button-hero">RATE US</a>
	</div>
	<?php
}
function wrp_upgrade_to_pro_function() { ?>
	<div class="upgrade-to-pro-demo" style="text-align:center;margin-bottom:10px;margin-top:10px;">
		<a href="http://demo.weblizar.com/responsive-portfolio-pro/"  target="_new" class="button button-primary button-hero">View Live Demo</a>
	</div>
	<div class="upgrade-to-pro-admin-demo" style="text-align:center;margin-bottom:10px;">
		<a href="http://weblizar.com/plugins/responsive-portfolio-pro/" target="_new" class="button button-primary button-hero">View Admin Demo</a>
	</div>
	<div class="upgrade-to-pro" style="text-align:center;margin-bottom:10px;">
		<a href="http://weblizar.com/plugins/responsive-portfolio-pro/" target="_new" class="button button-primary button-hero">Upgarde To Pro</a>
	</div>
	<?php
}

function wrp_pro_features() { ?>
	<ul style="">
		<li class="plan-feature">Responsive Design</li>
		<li class="plan-feature">8 Gallery Layout</li>
		<li class="plan-feature">Unlimited Hover Color</li>
		<li class="plan-feature">10 Types of Hover Color Opacity</li>
		<li class="plan-feature">Isotope/Masonry Effects</li>
		<li class="plan-feature">Album View Gallery</li>
		<li class="plan-feature">Youtube/Vimeo Video</li>
		<li class="plan-feature">Extrenal Link Portfolio</li>
		<li class="plan-feature">Carousel Slider</li>
		<li class="plan-feature">Gallery Pagination</li>
		<li class="plan-feature">All Gallery Shortcode</li>
		<li class="plan-feature">Each Gallery has Unique Shortcode</li>
		<li class="plan-feature">8 Types of Hover Animation</li>
		<li class="plan-feature">5 Types of Gallery Design Layout</li>
		<li class="plan-feature">500+ of Font Style</li>
		<li class="plan-feature">4 types Of Lightbox Integrated</li>
		<li class="plan-feature">Drag and Drop image Position</li>
		<li class="plan-feature">Multiple Image uploader</li>
		<li class="plan-feature">Shortcode Button on post or page</li>
		<li class="plan-feature">Unique settings for each gallery</li>
		<li class="plan-feature">Hide/Show gallery Title and label</li>
		<li class="plan-feature">Font icon Customization</li>
		<li class="plan-feature">Google Fonts</li>
	</ul>
	<?php 
}
 
/**
 *plugin shortcode
 */
function rp_plugin_shortcode() { ?>
	<p>Use below shortcode in any Page/Post to publish your Responsive Portfolio</p>
	<input readonly="readonly" type="text" value="<?php echo "[WRP id=".get_the_ID()."]"; ?>"> 
	<?php
} 

/**
 * Meta box interface design
 */
function responsive_portfolio_function() {
    $rpg_all_photos_details = unserialize(get_post_meta( get_the_ID(), 'rpg_all_photos_details', true));
    $TotalImages =  get_post_meta( get_the_ID(), 'rpg_total_images_count', true );
    $i = 1;
    ?>
	<style>
	#titlediv #title {
		margin-bottom:15px;
	}
	</style>
    <input type="hidden" id="count_total" name="count_total" value="<?php if($TotalImages==0){ echo 0; } else { echo $TotalImages; } ?>"/>
    <div style="clear:left;"></div>
    <?php
    /* load saved photos into gallery */
    if($TotalImages) {
        foreach($rpg_all_photos_details as $rpg_single_photos_detail) {
            $name = $rpg_single_photos_detail['rpg_image_label'];
            $url = $rpg_single_photos_detail['rpg_image_url'];
            ?>
                <div class="rpg-image-entry" id="rpg_img<?php echo $i; ?>">
					<a class="gallery_remove" href="#gallery_remove" id="rpg_remove_bt<?php echo $i; ?>"onclick="remove_meta_img(<?php echo $i; ?>)"><img src="<?php echo  WEBLIZAR_RP_PLUGIN_URL.'images/Close-icon.png'; ?>" /></a>
					<img src="<?php echo esc_url($url); ?>" class="rpg-meta-image" alt=""  style="">
					<input type="button" id="upload-background-<?php echo $i; ?>" name="upload-background-<?php echo $i; ?>" value="Upload Image" class="button-primary" onClick="weblizar_image('<?php echo $i; ?>')" />
					<input type="text" id="rpg_img_url<?php echo $i; ?>" name="rpg_img_url<?php echo $i; ?>" class="rpg_label_text"  value="<?php echo  $url; ?>"  readonly="readonly" style="display:none;" />
					<input type="text" id="image_label<?php echo $i; ?>" name="image_label<?php echo $i; ?>" placeholder="Enter Image Label" class="rpg_label_text" value="<?php echo $name; ?>">
                </div>
            <?php
            $i++;
        } // end of foreach
    } else {
        $TotalImages = 0;
    }
    ?>
	
    <div id="append_rpg_img">
    </div>
    <div class="rpg-image-entry add_rpg_new_image" onclick="add_rpg_meta_img()">
		<div class="dashicons dashicons-plus"></div>
		<p><?php _e('Add New Image', WEBLIZAR_RP_TEXT_DOMAIN); ?></p>
    </div>
    <div style="clear:left;"></div>
    <script>
    var rpg_i = parseInt(jQuery("#count_total").val());
    function add_rpg_meta_img() {
        rpg_i = rpg_i + 1;
        var rpg_output = '<div class="rpg-image-entry" id="rpg_img'+ rpg_i +'">'+
                            '<a class="gallery_remove" href="#gallery_remove" id="rpg_remove_bt' + rpg_i + '"onclick="remove_meta_img(' + rpg_i + ')"><img src="<?php echo  WEBLIZAR_RP_PLUGIN_URL.'images/Close-icon.png'; ?>" /></a>'+
                            '<img src="<?php echo  WEBLIZAR_RP_PLUGIN_URL.'images/rpg-default.jpg'; ?>" class="rpg-meta-image" alt=""  style="">'+
                            '<input type="button" id="upload-background-' + rpg_i + '" name="upload-background-' + rpg_i + '" value="Upload Image" class="button-primary" onClick="weblizar_image(' + rpg_i + ')" />'+
                            '<input type="text" id="rpg_img_url'+ rpg_i +'" name="rpg_img_url'+ rpg_i +'" class="rpg_label_text"  value="<?php echo  WEBLIZAR_RP_PLUGIN_URL.'images/rpg-default.jpg'; ?>"  readonly="readonly" style="display:none;" />'+
                            '<input type="text" id="image_label'+ rpg_i +'" name="image_label'+ rpg_i +'" placeholder="Enter Image Label" class="rpg_label_text"   >'+
                        '</div>';
        jQuery(rpg_output).hide().appendTo("#append_rpg_img").slideDown(500);
        jQuery("#count_total").val(rpg_i);
    }

    function remove_meta_img(id){
        jQuery("#rpg_img"+id).slideUp(600, function(){
            jQuery(this).remove();
        });

        count_total = jQuery("#count_total").val();
        count_total = count_total - 1;
        var id_i= id + 1;

        for(var i=id_i;i<=rpg_i;i++){
            var j = i-1;
            jQuery("#rpg_remove_bt"+i).attr('onclick','remove_meta_img('+j+')');
            jQuery("#rpg_remove_bt"+i).attr('id','rpg_remove_bt'+j);
            jQuery("#rpg_img_url"+i).attr('name','rpg_img_url'+j);
            jQuery("#image_label"+i).attr('name','image_label'+j);
            jQuery("#rpg_img_url"+i).attr('id','rpg_img_url'+j);
            jQuery("#image_label"+i).attr('id','image_label'+j);

            jQuery("#rpg_img"+i).attr('id','rpg_img'+j);
        }
        jQuery("#count_total").val(count_total);
        rpg_i = rpg_i - 1;
    }
    </script>
    <?php
}

/**
 * Save All Photo Gallery Images
 */
function responsive_portfolio_meta_save() {
    if(isset($_POST['post_ID'])) {
        $post_ID = $_POST['post_ID'];
        $post_type = get_post_type($post_ID);
        if($post_type == 'responsive-portfolio') {
            $TotalImages = $_POST['count_total'];
            $ImagesArray = array();
            if($TotalImages) {
                for($i=1; $i <= $TotalImages; $i++) {
                    $image_label = sanitize_text_field("image_label".$i);
                    $name = sanitize_text_field($_POST['image_label'.$i]);
                    $url = sanitize_text_field($_POST['rpg_img_url'.$i]);
                    $ImagesArray[] = array(
                        'rpg_image_label' => $name,
                        'rpg_image_url' => $url
                    );
                }
                update_post_meta($post_ID, 'rpg_all_photos_details', serialize($ImagesArray));
                update_post_meta($post_ID, 'rpg_total_images_count', $TotalImages);
            } else {
                $TotalImages = 0;
                update_post_meta($post_ID, 'rpg_total_images_count', $TotalImages);
                $ImagesArray = array();
                update_post_meta($post_ID, 'rpg_all_photos_details', serialize($ImagesArray));
            }
        }
    }
}

/**
 * Weblizar Responsive Gallery Short Code Detect Function
 */
function WeblizarResponsivePortfolioShortCodeDetect() {
    global $wp_query;
    $Posts = $wp_query->posts;
    $Pattern = get_shortcode_regex();
	foreach ($Posts as $Post) {
        if( preg_match_all( '/'. $Pattern .'/s', $Post->post_content, $Matches ) && array_key_exists( 2, $Matches ) && in_array( 'WRP', $Matches[2] ) ) {
			//js scripts
			wp_enqueue_script('jquery');
			wp_enqueue_script('jquery-litelighter',WEBLIZAR_RP_PLUGIN_URL.'js/jquery-litelighter.js', array('jquery'));
			wp_enqueue_script('jquery-rebox',WEBLIZAR_RP_PLUGIN_URL.'js/jquery-rebox.js', array('jquery'));

			wp_enqueue_script('modernizr.custom', WEBLIZAR_RP_PLUGIN_URL.'js/modernizr.custom.js', array('jquery'));
			wp_enqueue_script('imagesloaded11', WEBLIZAR_RP_PLUGIN_URL.'js/masonry.pkgd.min.js', array('jquery'));
			wp_enqueue_script('imagesloaded', WEBLIZAR_RP_PLUGIN_URL.'js/imagesloaded.js', array('jquery'));
			wp_enqueue_script('classie', WEBLIZAR_RP_PLUGIN_URL.'js/classie.js', array('jquery'));
			wp_enqueue_script('AnimOnScroll', WEBLIZAR_RP_PLUGIN_URL.'js/AnimOnScroll.js', array('jquery'));

			//css scripts
			wp_enqueue_style('rpgp-boot-strap-css', WEBLIZAR_RP_PLUGIN_URL.'css/bootstrap.css');
			wp_enqueue_style('rpgp-img-gallery-css', WEBLIZAR_RP_PLUGIN_URL.'css/img-gallery.css');
			wp_enqueue_style('component', WEBLIZAR_RP_PLUGIN_URL.'css/component.css');
			wp_enqueue_style('jquery-rebox-css', WEBLIZAR_RP_PLUGIN_URL.'css/jquery-rebox.css');

			//font awesome css
			wp_enqueue_script( 'hover-img', WEBLIZAR_RP_PLUGIN_URL.'js/jquery.hoverdir-top.js', array('jquery'), '', true );
			wp_enqueue_script( 'lightbox-script', WEBLIZAR_RP_PLUGIN_URL.'js/lightbox-script.js', array('jquery'), '', true );

            break;
        } //end of if
    } //end of foreach
}
add_action( 'wp_footer', 'WeblizarResponsivePortfolioShortCodeDetect' );

/**
 * Settings Page for Responsive Gallery
 */
add_action('admin_menu' , 'WRP_SettingsPage');
function WRP_SettingsPage() {
    add_submenu_page('edit.php?post_type=responsive-portfolio', __('Settings', WEBLIZAR_RP_TEXT_DOMAIN), __('Settings', WEBLIZAR_RP_TEXT_DOMAIN), 'administrator', 'image-portfolio-settings', 'image_portfolio_settings_page_function');
	add_submenu_page('edit.php?post_type=responsive-portfolio', 'Pro Screenshots', 'Pro Screenshots', 'administrator', 'get-responsive-portfolio-pro-plugin', 'get_portfolio_pro_page_function');
	add_submenu_page('edit.php?post_type=responsive-portfolio', 'recommendation', 'Plugin Recommendation', 'administrator', 'rp-plugin-recommendation', 'RP_plugin_recommendation');
 }

/**
 * Photo Gallery Settings Page
 */
function image_portfolio_settings_page_function() {
    //css
    wp_enqueue_style('wl-font-awesome-4', WEBLIZAR_RP_PLUGIN_URL.'css/font-awesome-4.0.3/css/font-awesome.min.css');
    require_once("responsive-portfolio-settings.php");
}
/**
 * Get Responsive Portfolio Pro Plugin Page
 */
function get_portfolio_pro_page_function() {
    //css
    wp_enqueue_style('wl-font-awesome-4', WEBLIZAR_RP_PLUGIN_URL.'css/font-awesome-4.0.3/css/font-awesome.min.css');
    wp_enqueue_style('wl-pricing-table-css', WEBLIZAR_RP_PLUGIN_URL.'css/pricing-table.css');
    wp_enqueue_style('wl-boot-strap-admin', WEBLIZAR_RP_PLUGIN_URL.'css/bootstrap-admin.css');
    require_once("get-responsive-gallery-pro.php");
}

function RP_plugin_recommendation(){
	//css
    wp_enqueue_style('rp-recom-css', WEBLIZAR_RP_PLUGIN_URL.'css/recom.css');
	require_once("recommendations.php");
}

// Review Notice Box
add_action("admin_notices","resp_admin_notice_resport");
function resp_admin_notice_resport(){
    $resp_screen = get_current_screen();
    if($resp_screen->post_type == "responsive-portfolio"){
        echo '<div class="notice notice-info is-dismissible review-notice">
		<p>Thanks for installing and using Responsive Portfolio plugin. If you love our plugin and plugin is really work for you.
		Then please share your feedback about this plugin. Your feedback will be helpful to make plugin more error free.</p>
		<p><a href="https://wordpress.org/support/plugin/responsive-portfolio/reviews/?filter=5" target="_blank" name="review" id="review" class="button button-primary">Review & Rate</a></p>
		</div>';
		?>
		<!---Admin Notice Style Start--->
		<style>
		.review-notice{
			background-color: #90afc5 !important;   
			color: #fff;
		}
		.review-notice p{
			font-size: 16px !important;
			font-weight: 500 !important;            
			font-style: normal !important; 
			text-shadow: 2px 2px 2px rgba(150, 150, 150, 1);
		}
		.review-notice #review{
			background-color: #ce5a57 !important;
			/*border-color: rgb(0, 115, 170) rgb(0, 103, 153) rgb(0, 103, 153);*/
			border-color: #ce5a57;
			-webkit-box-shadow: 0 1px 0 #b23831;
			box-shadow: 0 1px 0 #b23831;
			color: rgb(255, 255, 255);
			text-decoration: none;
			text-shadow: 0 -1px 1px #b23831, 1px 0 1px #b23831, 0 1px 1px #b23831, -1px 0 1px #b23831;
		}
		.notice-dismiss:before{
			color: #fff !important;
		}
		.review-notice #review:hover{
			background: rgb(238, 238, 238) !important;
			border-color: rgb(238, 238, 238) !important;
			color: rgb(255, 255, 255) !important;
		}
		.review-notice #review:focus{
			-webkit-box-shadow: 0 1px 0 #b13830, 0 0 2px 1px #b23831 !important;
			box-shadow: 0 1px 0 #b23831, 0 0 2px 1px #b23831 !important;
		}
		</style>
		<!---Admin Notice Style End--->
		<?php
    }    
}

// Add settings link on plugin page
$rport_plugin_name = plugin_basename(__FILE__); 
add_filter("plugin_action_links_$rport_plugin_name", 'rport_links' );
function rport_links($links) {
	$rportfolio_link = '<a href="https://weblizar.com/plugins/responsive-portfolio-pro/" target="_blank">Try Pro</a>';
	array_unshift($links, $rportfolio_link);	
	$rport_settings_link = '<a href="edit.php?post_type=responsive-portfolio">Settings</a>'; 
	array_unshift($links, $rport_settings_link);	
	return $links; 
}

/**
 * Responsive Gallery Short Code [WRG]
 */
require_once("responsive-portfolio-short-code.php");
?>