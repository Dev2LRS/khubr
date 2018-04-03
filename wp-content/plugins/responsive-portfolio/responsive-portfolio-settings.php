<?php
/**
 * Load Saved Image Gallery settings
 */
$WL_RG_Settings  = unserialize( get_option("WRP_Portfolio_Settings") );
if(count($WL_RG_Settings)) {
	$WL_Hover_Animation		= $WL_RG_Settings['WL_Hover_Animation'];
	$WL_Gallery_Layout		= $WL_RG_Settings['WL_Gallery_Layout'];
	$WL_Image_Border		= $WL_RG_Settings['WL_Image_Border'];
	$WL_Font_Style			= $WL_RG_Settings['WL_Font_Style'];
	$WL_Masonry_Layout		= $WL_RG_Settings['WL_Masonry_Layout'];
	$WL_Gallery_Title		= $WL_RG_Settings['WL_Gallery_Title'];
	$WL_Zoom_Animation		= $WL_RG_Settings['WL_Zoom_Animation'];
	$WL_Image_Border_Color	= $WL_RG_Settings['WL_Image_Border_Color'];
	
} else {
	$WL_Hover_Animation	= "fade";
	$WL_Gallery_Layout	= "col-md-6";
	$WL_Image_Border	= "yes";
	$WL_Font_Style		= "Arial";
	$WL_Masonry_Layout	= "no";
	$WL_Gallery_Title	= "yes";
	$WL_Zoom_Animation	= "yes";
	$WL_Image_Border_Color = "#ffffff";
}
?>

<h2><?php _e("Responsive Portfolio Settings", WEBLIZAR_RP_TEXT_DOMAIN); ?></h2>
<form action="?post_type=responsive-portfolio&page=image-portfolio-settings" method="post">
    <input type="hidden" id="wrp_action" name="wrp_action" value="wrp-save-settings">
    <table class="form-table">
        <tbody>
            <tr>
                <th scope="row"><label><?php _e("Image Hover Animation", WEBLIZAR_RP_TEXT_DOMAIN); ?></label></th>
                <td>
                    <select name="wl-hover-animation" id="wl-hover-animation">
                        <optgroup label="Select Animation">
                            <option value="fade" <?php if($WL_Hover_Animation == 'fade') echo "selected=selected"; ?>><?php _e("Hover From Top", WEBLIZAR_RP_TEXT_DOMAIN); ?></option>
                        </optgroup>
                    </select>
                    <p class="description"><?php _e("Choose an animation effect apply on mouse hover.", WEBLIZAR_RP_TEXT_DOMAIN); ?></p>
                </td>
            </tr>

            <tr>
                <th scope="row"><label><?php _e("Gallery Layout", WEBLIZAR_RP_TEXT_DOMAIN); ?></label></th>
                <td>
                    <select name="wl-gallery-layout" id="wl-gallery-layout">
                        <optgroup label="Select Gallery Layout">
                            <option value="col-md-6" <?php if($WL_Gallery_Layout == 'col-md-6') echo "selected=selected"; ?>><?php _e("Two Column", WEBLIZAR_RP_TEXT_DOMAIN); ?></option>
                            <option value="col-md-4" <?php if($WL_Gallery_Layout == 'col-md-4') echo "selected=selected"; ?>><?php _e("Three Column", WEBLIZAR_RP_TEXT_DOMAIN); ?></option>
                        </optgroup>
                    </select>
                    <p class="description"><?php _e("Choose a column layout for image gallery.", WEBLIZAR_RP_TEXT_DOMAIN); ?></p>
                </td>
            </tr>
			
			<tr>
                <th scope="row"><label><?php _e("Display Gallery Title", WEBLIZAR_RP_TEXT_DOMAIN); ?></label></th>
                <td>
                    <input type="radio" name="wl-gallery-title" id="wl-gallery-title" value="yes" <?php if($WL_Gallery_Title == 'yes' ) { echo "checked"; } ?>> Yes
                    <input type="radio" name="wl-gallery-title" id="wl-gallery-title" value="no" <?php if($WL_Gallery_Title == 'no' ) { echo "checked"; } ?>> No
                    <p class="description"><?php _e("Select yes if you want show gallery title .", WEBLIZAR_RP_TEXT_DOMAIN); ?></p>
                </td>
            </tr>
			<tr>
                <th scope="row"><label><?php _e("Masonry Layout", WEBLIZAR_RP_TEXT_DOMAIN); ?></label></th>
                <td>
                    <input type="radio" name="wl-masonry-layout" id="wl-masonry-layout" value="yes"  <?php if($WL_Masonry_Layout == 'yes' ) { echo "checked"; } ?>> <?php _e("Yes", WEBLIZAR_RP_TEXT_DOMAIN); ?>
                    <input type="radio" name="wl-masonry-layout" id="wl-masonry-layout" value="no" <?php if($WL_Masonry_Layout == 'no' ) { echo "checked"; } ?>> <?php _e("No", WEBLIZAR_RP_TEXT_DOMAIN); ?>
                    <p class="description"><?php _e("Choose Yes if you want to hide margin between images ", WEBLIZAR_RP_TEXT_DOMAIN); ?></p>
                </td>
            </tr>
			
            <tr>
                <th scope="row"><label><?php _e("Image Border", WEBLIZAR_RP_TEXT_DOMAIN); ?></label></th>
                <td>
                    <input type="radio" name="wl-image-border" id="wl-image-border" value="yes" <?php if($WL_Image_Border == 'yes' ) { echo "checked"; } ?>> <?php _e("Yes", WEBLIZAR_RP_TEXT_DOMAIN); ?>
                    <input type="radio" name="wl-image-border" id="wl-image-border" value="no" <?php if($WL_Image_Border == 'no' ) { echo "checked"; } ?>> <?php _e("No", WEBLIZAR_RP_TEXT_DOMAIN); ?>
                    <p class="description"><?php _e("Select yes if you want to show image border ( NOTE : If Masonry Layout is selectd then image border is not visible )", WEBLIZAR_RP_TEXT_DOMAIN); ?></p>
                </td>
            </tr>
			
			<tr>
				<th scope="row"><label><?php _e('Image Border Color', WEBLIZAR_RP_TEXT_DOMAIN); ?></label></th>
				<td>
					<?php if($WL_Image_Border_Color == "") $WL_Image_Border_Color = "#FFFFFF"; ?>
					<input id="wl-image-border-color" name="wl-image-border-color" type="text" value="<?php echo $WL_Image_Border_Color; ?>" class="my-color-field" data-default-color="#ffffff" />
					
				</td>
			</tr>
		
			<tr>
                <th scope="row"><label><?php _e("Image Zoom Animation", WEBLIZAR_RP_TEXT_DOMAIN); ?></label></th>
                <td>
                    <input type="radio" name="wl-zoom-animation" id="wl-zoom-animation" value="yes"  <?php if($WL_Zoom_Animation == 'yes' ) { echo "checked"; } ?>> <?php _e("Yes", WEBLIZAR_RP_TEXT_DOMAIN); ?>
                    <input type="radio" name="wl-zoom-animation" id="wl-zoom-animation" value="no" <?php if($WL_Zoom_Animation == 'no' ) { echo "checked"; } ?>> <?php _e("No", WEBLIZAR_RP_TEXT_DOMAIN); ?>
                    <p class="description"><?php _e("Choose Yes if you want to zoom image on hover ", WEBLIZAR_RP_TEXT_DOMAIN); ?></p>
                </td>
            </tr>
			
            <tr>
                <th scope="row"><label><?php _e("Caption Font Style", WEBLIZAR_RP_TEXT_DOMAIN); ?></label></th>
                <td>
                    <select  name="wl-font-style" class="standard-dropdown" id="wl-font-style">
                        <optgroup label="Default Fonts">
                            <option value="arial"           <?php if($WL_Font_Style == 'arial' ) { echo "selected"; } ?>>Arial</option>
                            <option value="Arial Black"    	<?php if($WL_Font_Style == 'Arial Black' ) { echo "selected"; } ?>>Arial Black</option>
                            <option value="Courier New"     <?php if($WL_Font_Style == 'Courier New' ) { echo "selected"; } ?>>Courier New</option>
                            <option value="Georgia"         <?php if($WL_Font_Style == 'Georgia' ) { echo "selected"; } ?>>Georgia</option>
                            <option value="Helvetica Neue" 	<?php if($WL_Font_Style == 'Helvetica Neue' ) { echo "selected"; } ?>>Helvetica Neue</option>
                            <option value="Impact"         	<?php if($WL_Font_Style == 'Impact' ) { echo "selected"; } ?>>Impact</option>
                            <option value="Lucida Console"  <?php if($WL_Font_Style == 'Lucida Console' ) { echo "selected"; } ?>>Lucida</option>
                            <option value="Lucida Grande"   <?php if($WL_Font_Style == 'Lucida Grande' ) { echo "selected"; } ?>>Lucida Grande</option>
                            <option value="OpenSansBold"   <?php if($WL_Font_Style == 'OpenSansBold' ) { echo "selected"; } ?>>OpenSansBold</option>
                            <option value="Palatino"       <?php if($WL_Font_Style == 'Palatino' ) { echo "selected"; } ?>>Palatino</option>
                            <option value="sans"           <?php if($WL_Font_Style == 'sans' ) { echo "selected"; } ?>>Sans</option>
                            <option value="sans-serif"     <?php if($WL_Font_Style == 'sans-serif' ) { echo "selected"; } ?>>Sans-Serif</option>
                            <option value="Tahoma"         <?php if($WL_Font_Style == 'Tahoma' ) { echo "selected"; } ?>>Tahoma</option>
                            <option value="Times New Roman" <?php if($WL_Font_Style == 'Times New Roman' ) { echo "selected"; } ?>>Times New Roman</option>
                            <option value="Trebuchet"      <?php if($WL_Font_Style == 'Trebuchet' ) { echo "selected"; } ?>>Trebuchet</option>
                            <option value="Verdana"        <?php if($WL_Font_Style == 'Verdana' ) { echo "selected"; } ?>>Verdana</option>
                        </optgroup>
                    </select>
                    <p class="description"><?php _e("Choose a caption font style.", WEBLIZAR_RP_TEXT_DOMAIN); ?></p>
                </td>
            </tr>

			<tr>
				
			</tr>

        </tbody>
    </table>
    <p class="submit">
        <input type="submit" value="<?php _e("Save Changes", WEBLIZAR_RP_TEXT_DOMAIN); ?>" class="button button-primary" id="submit" name="submit">
    </p>
</form>
<div class="plan-name" style="margin-top:40px;">
	<h2 style="border-top: 5px solid #f9f9f9;padding-top: 20px;">Responsive Portfolio Pro</h2>
</div>
<div class="purchase_btn_div" style="margin-top:20px;">
	<a href="http://demo.weblizar.com/responsive-portfolio-pro" target="_new" class="button button-hero">Try Live Demo</a>
	<a href="http://demo.weblizar.com/responsive-portfolio-pro-admin-demo/admin-login/" target="_new" class="button button-hero">Try Admin Demo</a>
	<a href="http://weblizar.com/plugins/responsive-portfolio-pro/" target="_new" class="button button-primary button-hero">Upgrade To Pro</a>
</div>
<div class="plan-name" style="margin-top:40px;text-align: left;">
	<h2 style="font-weight: bold;font-size: 36px;padding-top: 30px;padding-bottom: 10px;">Responsive Portflio Pro Screenshot</h2>
	<h6 style="font-size: 22px;padding-top: 10px;padding-bottom: 10px;">A Perfect Responsive Portfolio Plugin for display content like images, vimeo/youtube video, Albums etc.</h6>
</div>
<!-- SLIDER-INTRO-->
<div class="col-md-12" style="width:90%;display:inline-block">
	<a href="http://weblizar.com/plugins/responsive-portfolio-pro/" target="_new"><h2 style="font-weight: bold;font-size: 26px;padding-top: 20px;padding-bottom: 20px; text-align:center">Responsive Portfolio Pro Demos</h2>
	<img class="img-responsive" style="width:100%;border: 1px solid #e3e3e3;background: #f7f7f7;padding:10px" src="<?php echo  WEBLIZAR_RP_PLUGIN_URL.'images/portfolio-pro-detial.png'; ?>" alt=""/>
	</a>
</div>

<?php
if(isset($_POST['wrp_action'])) {
    $Action = $_POST['wrp_action'];
    //save settings
    if($Action == "wrp-save-settings") {

        $WL_Hover_Animation     = sanitize_text_field($_POST['wl-hover-animation']);
        $WL_Gallery_Layout      = sanitize_text_field($_POST['wl-gallery-layout']);
        $WL_Image_Border        = sanitize_text_field($_POST['wl-image-border']);
		$WL_Image_Border_Color  = sanitize_text_field($_POST['wl-image-border-color']);
        $WL_Font_Style          = sanitize_text_field($_POST['wl-font-style']);
        $WL_Masonry_Layout     	= sanitize_text_field($_POST['wl-masonry-layout']);
		$WL_Gallery_Title		= sanitize_text_field($_POST['wl-gallery-title']);
		$WL_Zoom_Animation      = sanitize_text_field($_POST['wl-zoom-animation']);

        $SettingsArray = serialize( array(
            'WL_Hover_Animation' => $WL_Hover_Animation,
            'WL_Gallery_Layout' => $WL_Gallery_Layout,
            'WL_Image_Border' => $WL_Image_Border,
            'WL_Hover_Color_Opacity' => 1,
            'WL_Font_Style' => $WL_Font_Style,
            'WL_Masonry_Layout' => $WL_Masonry_Layout,
			'WL_Gallery_Title' =>	$WL_Gallery_Title,
			'WL_Zoom_Animation' => $WL_Zoom_Animation,
			'WL_Image_Border_Color' => $WL_Image_Border_Color,
        ) );

        update_option("WRP_Portfolio_Settings", $SettingsArray);
        echo "<script>location.href = location.href;</script>";
    }
}