<?php 
add_shortcode( 'WRP', 'reponsive_portfolio_premium_short_code' );
function reponsive_portfolio_premium_short_code($Id) {
	ob_start();

	if(!isset($Id['id'])) {
	$Id['id'] = "";
	}
    /**
     * Load Responsive Gallery Settings
     */
    $WL_RG_Settings  = unserialize( get_option("WRP_Portfolio_Settings") );
    if(count($WL_RG_Settings)) {     
		
		$WL_Hover_Animation     = $WL_RG_Settings['WL_Hover_Animation'];
		$WL_Gallery_Layout      = $WL_RG_Settings['WL_Gallery_Layout'];
		$WL_Image_Border        = $WL_RG_Settings['WL_Image_Border'];
		$WL_Font_Style          = $WL_RG_Settings['WL_Font_Style'];
		$WL_Masonry_Layout     	= $WL_RG_Settings['WL_Masonry_Layout'];
		$WL_Gallery_Title       = $WL_RG_Settings['WL_Gallery_Title'];
		$WL_Zoom_Animation 		= $WL_RG_Settings['WL_Zoom_Animation'];
		$WL_Image_Border_Color  = $WL_RG_Settings['WL_Image_Border_Color'];
		$WL_Hover_Color_Opacity = 0.5;

    } else {
		$WL_Hover_Color_Opacity = 0.5;
		$WL_Hover_Animation     = "fade";
        $WL_Gallery_Layout      = "col-md-6";
        $WL_Image_Border        = "yes";
        $WL_Font_Style          = "Arial";
        $WL_Masonry_Layout     	= "no";
		$WL_Gallery_Title       = "yes";
		$WL_Zoom_Animation      = "yes";
		$WL_Image_Border_Color  = "#ffffff";
    }
	?>
    <script>
        jQuery.browser = {};
        (function () {
            jQuery.browser.msie = false;
            jQuery.browser.version = 0;
            if (navigator.userAgent.match(/MSIE ([0-9]+)\./)) {
                jQuery.browser.msie = true;
                jQuery.browser.version = RegExp.$1;
            }
        })();
    </script>
    <style>
	.modal-backdrop.in {
		display:none !important;
	}
   .weblizar-portfolio-gallery{
		padding:0px;
	<?php if($WL_Masonry_Layout =="no"){ ?>	
		box-shadow: 0 0 6px rgba(0,0,0,.7);
	<?php if($WL_Image_Border=="yes"){ ?>	
		padding:10px;
		background:<?php echo $WL_Image_Border_Color;?>;
	<?php }  }?>
	}
	.weblizar-portfolio-gallery a,
	.weblizar-portfolio-gallery a img {
		display: block;
		position: relative;
		border-radius:0px;
		-webkit-transition: all .55s linear;
		-moz-transition: all .55s linear;
		-o-transition: all .55s linear;
		transition: all .55s linear;
	}
	.weblizar-portfolio-gallery:hover a img{
		<?php if($WL_Zoom_Animation=="yes"){ ?>
		-moz-transform: scale(1.5) ;
		-webkit-transform: scale(1.5) ;
		-o-transform: scale(1.5) ;
		transform: scale(1.5) ;
		<?php } ?>
	}
	.weblizar-portfolio-gallery a img {
		display: block;
		position: relative;
		width:100%;
		height:auto;
		margin:0px;
		padding:0px;
	}
	 .weblizar-portfolio-gallery a {
		overflow: hidden;
	}
	 .weblizar-portfolio-gallery a div {
		position: absolute;

		background: rgba(0,0,0, <?php echo $WL_Hover_Color_Opacity; ?>);
		width: 100%;
		height: 100%;
		border:0px solid rgba(0,0,0,0.3);
		
	}
	 .weblizar-portfolio-gallery a div span {
		display: block;
		padding: 10px 0;
		/* margin: 40px 20px 20px 20px; */
		font-weight: normal;
		text-align:center;
		font-family: <?php echo $WL_Font_Style;?>;
		color: rgba(255,255,255,0.9);
		text-shadow: 1px 1px 1px rgba(0,0,0,0.2);
		display: flex !important;
		justify-content: center !important;
		align-items: center !important;
		height: 100% !important;

	}
	
		
	@media (min-width: 992px){
		.col-md-6 {
		width: 49.97% !important;
		}
		.col-md-4 {
		width: 33.30% !important;
		}
		.col-md-3 {
			width: 24.90% !important;
		}
	}

	.col-md-6 {
		padding:0px;
	}
	.col-md-4 {
		padding:0px;
	}
	.col-md-3 {
		padding:0px;
	}	
	.weblizar-portfolio-grid li {
	<?php if($WL_Masonry_Layout =="no"){ ?>
		padding:10px;
	<?php } ?>
	}
    </style>

    <?php
    /**
     * Load All Image Gallery Custom Post Type
     */
    $IG_CPT_Name = "responsive-portfolio";
    $IG_Taxonomy_Name = "category";
	$all_posts = wp_count_posts('responsive-portfolio')->publish;
    $AllGalleries = array('p' => $Id['id'],'post_type' => $IG_CPT_Name, 'orderby' => 'ASC','posts_per_page' =>$all_posts);
    $loop = new WP_Query( $AllGalleries );
    ?>
    <div  class="gal-container">
		<?php while ( $loop->have_posts() ) : $loop->the_post();?>
			<!--get the post id-->
			<?php $post_id = $Id['id'];  ?>
			
			<?php if($WL_Gallery_Title==""){ $WL_Gallery_Title == "yes"; } if($WL_Gallery_Title == "yes") { ?>
			<!-- gallery title-->
			<div style="font-weight: bolder; text-align:center;font-size:16px; padding-bottom:20px; border-bottom:2px solid #f1f1f1; margin-bottom: 20px;">
				<?php echo get_the_title($post_id); ?>
			</div>
			<?php } ?>
			<!-- gallery photos-->
			<ul class="weblizar-portfolio-grid effect-3" id="weblizar-portfolio-grid<?php echo $post_id; ?>">
				<?php
				/**
				 * Get All Photos from Gallery Post Meta
				 */
				$rpg_all_photos_details = unserialize(get_post_meta( get_the_ID(), 'rpg_all_photos_details', true));
				$TotalImages =  get_post_meta( get_the_ID(), 'rpg_total_images_count', true );
				$i = 1;

				foreach($rpg_all_photos_details as $rpg_single_photos_detail) {
					$name = $rpg_single_photos_detail['rpg_image_label'];
					$url = $rpg_single_photos_detail['rpg_image_url'];
					
					if($name == "") {
						// if slide title blank then
						global $wpdb;
						$post_table_prefix = $wpdb->prefix. "posts";
						if(count($attachment = $wpdb->get_col($wpdb->prepare("SELECT `post_title` FROM `$post_table_prefix` WHERE `guid` LIKE '%s'", $url)))) { 
							// attachment title as alt
							$slide_alt = $attachment[0];
							if(empty($attachment[0])) {
								// post title as alt
								$slide_alt = get_the_title( $post_id );
							}
						}
						if(empty($attachment[0])) {
								// post title as alt
								$slide_alt = get_the_title( $post_id );
							}
						
					} else {
						// slide title as alt
						$slide_alt = $name;
					}
					?>
					<li class="<?php echo $WL_Gallery_Layout; ?> col-sm-6 wl-gallery" >
						<div class="b-link-<?php echo $WL_Hover_Animation; ?> b-animate-go">
							<div  id="da-thumbs" >
								<div class="weblizar-portfolio-gallery">
									<a href="<?php echo esc_url($url); ?>" title="<?php echo esc_attr($name); ?>">
										<img src="<?php echo esc_url($url); ?>" class="gall-img-responsive" alt="<?php echo esc_attr($slide_alt); ?>">
										<div class=""><span><?php echo esc_html($name); ?></span></div>
									</a>
								</div>
							</div>
						</div>
					</li>					
					<?php
					$i++;
				}
				?>
			</ul>
		
		<script type="text/javascript">
		jQuery(function() {
			jQuery(' #da-thumbs > .weblizar-portfolio-gallery ').each( function() { jQuery(this).hoverdir_top({
					hoverDelay : 150,
					inverse : false
				}); 
			});
		});
		jQuery(document).ready(function(){
		new AnimOnScroll( document.getElementById( 'weblizar-portfolio-grid<?php echo $post_id; ?>' ), {
			minDuration : 0.4,
			maxDuration : 0.7,
			viewportFactor : 0.2
		});
		});
		</script>	
		<?php endwhile; ?>
    </div>	
    <?php wp_reset_query(); 
	return ob_get_clean();
}
?>