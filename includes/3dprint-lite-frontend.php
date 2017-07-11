<?php
/**
 *
 *
 * @author Sergey Burkov, http://www.wp3dprinting.com
 * @copyright 2015
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}


$p3dlite_email_status_message="";
add_action( 'plugins_loaded', 'p3dlite_request_price' );
function p3dlite_request_price() {
	global $p3dlite_email_status_message;
	if ( isset( $_POST['action'] ) && $_POST['action']=='request_price' ) {
		$product_id=(int)$_POST['p3dlite_product_id'];
		$printer_id=(int)$_POST['attribute_pa_p3dlite_printer'];
		$material_id=(int)$_POST['attribute_pa_p3dlite_material'];
		$coating_id=(int)$_POST['attribute_pa_p3dlite_coating'];
		$model_file= p3dlite_basename( $_POST['attribute_pa_p3dlite_model'] ) ;
		$email_address = sanitize_email( $_POST['email_address'] );
		$request_comment = sanitize_text_field( $_POST['request_comment'] );
		$scale=(float)$_POST['p3dlite_resize_scale'];
		if ($_REQUEST['attribute_pa_p3dlite_unit']=='inch')
			$unit='inch';
		else
			$unit='mm';


		$db_printers=get_option( 'p3dlite_printers' );
		$db_materials=get_option( 'p3dlite_materials' );
		$db_coatings=get_option( 'p3dlite_coatings' );
		$settings=get_option( 'p3dlite_settings' );
		$error=false;
		$upload_dir = wp_upload_dir();

		if ( strlen( $model_file )==0 || !file_exists( $upload_dir['basedir'].'/p3d/'.$model_file ) || strlen( $printer_id )==0 || strlen( $material_id )==0 ) {
			$error=true;
			$p3dlite_email_status_message='<span class="p3dlite-mail-error">'.__( 'Please upload your model and select all options.' , '3dprint-lite' ).'</span>';
		}
		if ( empty( $email_address ) ) {
			$error=true;
			$p3dlite_email_status_message='<span class="p3dlite-mail-error">'.__( 'Please enter valid email address.' , '3dprint-lite' ).'</span>';
		}
		if ( !$error ) {
			$product_key=$product_id.'_'.$printer_id.'_'.$material_id.'_'.$coating_id.'_'.$infill.'_'.$unit.'_'.$scale.'_'.$email_address.'_'.base64_encode( p3dlite_basename( $model_file ) );
			$p3dlite_price_requests=(array)get_option( 'p3dlite_price_requests' );
			$p3dlite_price_requests[$product_key]['printer'] = $db_printers[$printer_id]['name'];
			$p3dlite_price_requests[$product_key]['material'] = $db_materials[$material_id]['name'];
			$p3dlite_price_requests[$product_key]['coating'] = $db_coatings[$coating_id]['name'];
			foreach ( $_POST as $key => $value ) {
				if ( strpos( $key, 'attribute_' )===0 ) {
					if ( !strstr( $key, 'p3dlite_' ) ) $email_attrs[$key]=$value;

					$p3dlite_price_requests[$product_key]['attributes'][$key]=$value;
				}

			}


			$p3dlite_price_requests[$product_key]['price']='';
			$p3dlite_price_requests[$product_key]['estimated_price']=(float)$_POST['p3dlite_estimated_price'];
			$p3dlite_price_requests[$product_key]['resize_scale']=(float)$_POST['p3dlite_resize_scale'];
			$p3dlite_price_requests[$product_key]['scale_x']=(float)$_POST['p3dlite_scale_x'];
			$p3dlite_price_requests[$product_key]['scale_y']=(float)$_POST['p3dlite_scale_y'];
			$p3dlite_price_requests[$product_key]['scale_z']=(float)$_POST['p3dlite_scale_z'];
			$p3dlite_price_requests[$product_key]['email']=$email_address;
			$p3dlite_price_requests[$product_key]['request_comment']=$request_comment;



			update_option( "p3dlite_price_requests", $p3dlite_price_requests );

			// $request_comment
			$upload_dir = wp_upload_dir();
			$filepath = $upload_dir['basedir']."/p3d/$model_file";
			$original_file = p3dlite_get_original($model_file);
			$link = $upload_dir['baseurl'].'/p3d/'.rawurlencode( p3dlite_basename( $model_file ) );




			//todo: email template
			$subject=__( "Price enquiry from $email_address" , '3dprint-lite' );

			$message=__( "E-mail" , '3dprint-lite' ) ." : $email_address <br>";
			$message.=__( "Product ID" , '3dprint-lite' )." : $product_id <br>";
			$message.=__( "Printer" , '3dprint-lite' )." : ".$db_printers[$printer_id]['name']." <br>";
			$message.=__( "Material" , '3dprint-lite' )." : ".$db_materials[$material_id]['name']." <br>";
			$message.=__( "Coating" , '3dprint-lite' )." : ".$db_coatings[$coating_id]['name']." <br>";
//			$message.=__( "Model:" , '3dprint-lite' )." <a href='".$link."'>".$model_file."</a> <br>";
			if (file_exists($original_file) && p3dlite_basename($filepath) != p3dlite_basename($original_file)) {
				$original_link = $upload_dir['baseurl'] ."/p3d/". rawurlencode(p3dlite_basename($original_file)) ;
				$message.=__( "Model" , '3dprint-lite' )." : <a href='".$link."'>".$model_file."</a> <br>";
				$message.=__( 'Original file', '3dprint' ).' : <a target="_blank" href="'.$original_link.'">'.urldecode(urldecode(p3dlite_basename($original_file))).'</a> <br>';

			}
			else {
				$message.=__( "Model" , '3dprint' )." : <a href='".$link."'>".p3dlite_basename( $model_file )."</a><br>";
			}
			$message.= __('Dimensions', '3dprint-lite')."  : ".(float)$_POST['p3dlite_scale_x']." &times; ".(float)$_POST['p3dlite_scale_y']." &times; ".(float)$_POST['p3dlite_scale_z']." ".__('cm', '3dprint-lite')."<br>";
			$message.= __('Resize Scale', '3dprint-lite')."  : ".(float)$_POST['p3dlite_resize_scale']."<br>";
			$message.=__( "Estimated Price" , '3dprint-lite' )." : ".p3dlite_format_price($p3dlite_price_requests[$product_key]['estimated_price'], $settings['currency'], $settings['currency_position']).'<br>';

			if ( isset( $email_attrs ) && count( $email_attrs ) ) {
				foreach ( $email_attrs as $key=>$value ) {
					$message.="$key: $value<br>";
				}
			}
			$message.=__( "Comments" , '3dprint-lite' ) ." : $request_comment <br>";
			$message.=__( "Manage Price Requests" , '3dprint-lite' )." : <a href='".admin_url( 'admin.php?page=3dprint-lite#p3dlite_tabs-3' )."'>".admin_url( 'admin.php?page=3dprint-lite#p3dlite_tabs-3' )."</a> <br>";



			$headers = array( 'Content-Type: text/html; charset=UTF-8' );
			if ( wp_mail( $settings['email_address'], $subject, $message, $headers ) )
				$p3dlite_email_status_message='<span class="p3dlite-mail-success">'.__( 'Store owner has been notified about your request. You\'ll receive the email with the price shortly.' , '3dprint-lite' ).'</span>';
			else
				$p3dlite_email_status_message='<span class="p3dlite-mail-error">'.__( 'Could not send the email. Please try again later.' , '3dprint-lite' ).'</span>';

			p3dlite_clear_cookies();
			do_action( 'p3dlite_request_price' );
		}
	}
}

add_shortcode( '3dprint-lite', 'p3d_lite' );
function p3d_lite( $atts ) {
	global $p3dlite_email_status_message, $post;
	$db_printers=get_option( 'p3dlite_printers' );
	$db_materials=get_option( 'p3dlite_materials' );
	$db_coatings=get_option( 'p3dlite_coatings' );
	$settings=get_option( 'p3dlite_settings' );

	ob_start();
?>

<div class="p3dlite-images">
	<div id="prompt">
	  <!-- if IE without GCF, prompt goes here -->
	</div>


	<div id="p3dlite-viewer">
		<canvas id="p3dlite-cv" width="<?php echo $settings['canvas_width'];?>" height="<?php echo $settings['canvas_height'];?>" style="border: 1px solid;"></canvas>
		<div id="canvas-stats" style="<?php if ($settings['canvas_stats']!='on') echo 'display:none;';?>">
			<div class="canvas-stats" id="p3dlite-statistics">
			</div>
		</div>
		<div id="p3dlite-file-loading">
			<img alt="Loading file" src="<?php echo plugins_url( '3dprint-lite/images/ajax-loader.gif' ); ?>">
		</div>
		<div id="p3dlite-model-message">
			<p class="p3dlite-model-message" id="p3dlite-model-message-upload">
				<img alt="Upload" id="p3dlite-model-message-upload-icon" src="<?php echo plugins_url( '3dprint-lite/images/upload45.png'); ?>">
<?php 
				if (preg_match('/MSIE (.*?);/', $_SERVER['HTTP_USER_AGENT']) || preg_match('/Trident/', $_SERVER['HTTP_USER_AGENT'])) { //screw ie
?>
				<?php _e("Click here to upload.", '3dprint-lite');?>
<?php
				} else {
?>
				<?php _e("Click here to upload or drag and drop your model to the canvas.", '3dprint-lite');?>
<?php
				}
?>
			</p>
			<p class="p3dlite-model-message" id="p3dlite-model-message-scale"><?php _e("The model is too large and has been resized to fit in the printer's build tray.", '3dprint-lite');?>&nbsp;<span style="cursor:pointer;" onclick='jQuery(this).parent().hide(); return false;'><?php _e('[Hide]', '3dprint-lite');?></span></p>
			<p class="p3dlite-model-message" id="p3dlite-model-message-minside"><?php _e("The model is too small and has been upscaled.", '3dprint-lite');?>&nbsp;<span style="cursor:pointer;" onclick='jQuery(this).parent().hide(); return false;'><?php _e('[Hide]', '3dprint-lite');?></span></p>
			<p class="p3dlite-model-message" id="p3dlite-model-message-fullcolor"><?php _e( 'Warning: The selected printer can not print in full color', '3dprint-lite' );?>&nbsp;<span style="cursor:pointer;" onclick='jQuery(this).parent().hide(); return false;'><?php _e('[Hide]', '3dprint-lite');?></span></p>
			<p class="p3dlite-model-message" id="p3dlite-model-message-multiobj"><?php _e( 'Warning: obj models with multiple meshes are not yet supported', '3dprint-lite' );?>&nbsp;<span style="cursor:pointer;" onclick='jQuery(this).parent().hide(); return false;'><?php _e('[Hide]', '3dprint-lite');?></span></p>


		</div>

	</div>

	<br style="clear:both;">

	<div id="p3dlite-container" onclick="p3dliteDialogCheck();">

<?php
		if (preg_match('/MSIE (.*?);/', $_SERVER['HTTP_USER_AGENT']) || preg_match('/Trident/', $_SERVER['HTTP_USER_AGENT']) ) { 
?>
		<button id="p3dlite-pickfiles" style="<?php if ($settings['show_upload_button']!='on') echo 'display:none;';?>background-color:<?php echo $settings['button_color1']?>;" class="progress-button"><?php _e( 'Upload Model', '3dprint-lite' ); ?></button>
<?php
		}
		else {
?>
		<button id="p3dlite-pickfiles" style="<?php if ($settings['show_upload_button']!='on') echo 'display:none;';?>" class="progress-button" data-style="rotate-angle-bottom" data-perspective data-horizontal><?php _e( 'Upload Model', '3dprint-lite' ); ?></button>
<?php
		}

?>
	<div class="p3dlite-info" style="<?php if ($settings['show_unit']!='on') echo 'display:none;';?>">
	<?php _e( 'File Unit:', '3dprint-lite' );?>
		&nbsp;&nbsp;
		<input class="p3dlite-control" autocomplete="off" id="unit_mm" onclick="p3dliteSelectUnit(this);" type="radio" name="p3dlite_unit" value="mm">
		<span style="cursor:pointer;" onclick="p3dliteSelectUnit(jQuery('#unit_mm'));"><?php _e( 'mm', '3dprint-lite' );?></span>
		&nbsp;&nbsp;
		<input class="p3dlite-control" autocomplete="off" id="unit_inch" onclick="p3dliteSelectUnit(this);" type="radio" name="p3dlite_unit" value="inch">
		<span style="cursor:pointer;" onclick="p3dliteSelectUnit(jQuery('#unit_inch'));"><?php _e( 'inch', '3dprint-lite' );?></span>
	</div>
	<div class="p3dlite-info" style="white-space:nowrap;<?php if ($settings['show_scale']!='on') echo 'display:none;';?>">
		<div id="p3dlite-scale-text">
			<?php _e("Scale:", "3dprint-lite"); ?>   
		</div>
		<div id="p3dlite-scale-slider">
			<div id="p3dlite-scale" class="noUiSlider"></div>
		</div>
		<div id="p3dlite-scale-input">
			<input id="p3dlite-slider-range-value" type="text" size="3" autocomplete="off" onchange="p3dliteUpdateSliderValue(this.value)"> %
		</div>
	</div>
	<div class="p3dlite-info" style="white-space:nowrap;<?php if ($settings['show_scale']!='on') echo 'display:none;';?>">
		<div id="p3dlite-scale-text">
			&nbsp;
		</div>
		<div id="p3dlite-scale-dimensions">
			<input type="text" autocomplete="off" class="p3dlite-dim-input" size="3" value="0" id="scale_x" onchange="p3dliteUpdateDimensions(this);"> &times; 
			<input type="text" autocomplete="off" class="p3dlite-dim-input" size="3" value="0" id="scale_y" onchange="p3dliteUpdateDimensions(this);"> &times; 
			<input type="text" autocomplete="off" class="p3dlite-dim-input" size="3" value="0" id="scale_z" onchange="p3dliteUpdateDimensions(this);">&nbsp;<?php _e("cm", "3dprint-lite"); ?>
		</div>
	</div>

	</div>
	<div class="p3dlite-info">
		<pre id="p3dlite-console"></pre>
	</div>
	<div id="p3dlite-filelist"></div>
	<div class="p3dlite-info">
	  	<span id="p3dlite-error-message" class="error"></span>
	</div>

	<div class="p3dlite-info" style="<?php if ($settings['model_stats']!='on') echo 'display:none;';?>">     

		<table class="p3dlite-stats">
			<tr style="<?php if ($settings['show_model_stats_material_volume']!='on') echo 'display:none;';?>">
				<td>
					<?php _e('Material Volume', '3dprint-lite');?>:
				</td>
				<td>
					<span id="stats-material-volume"></span> <?php _e('cm3', '3dprint-lite');?>
				</td>
			</tr>
			<tr style="<?php if ($settings['show_model_stats_box_volume']!='on') echo 'display:none;';?>">
				<td>
					<?php _e('Box Volume', '3dprint-lite');?>:
				</td>
				<td>
					<span id="stats-box-volume"></span> <?php _e('cm3', '3dprint-lite');?>
				</td>
			</tr>
			<tr style="<?php if ($settings['show_model_stats_surface_area']!='on') echo 'display:none;';?>">
				<td>
					<?php _e('Surface Area', '3dprint-lite');?>:
				</td>
				<td>
					<span id="stats-surface-area"></span> <?php _e('cm2', '3dprint-lite');?>
				</td>
			</tr>
			<tr style="<?php if ($settings['show_model_stats_model_weight']!='on') echo 'display:none;';?>">
				<td>
					<?php _e('Model Weight', '3dprint-lite');?>:
				</td>
				<td>
					<span id="stats-weight"></span> <?php _e('g', '3dprint-lite');?>
				</td>
			</tr>
			<tr style="<?php if ($settings['show_model_stats_model_dimensions']!='on') echo 'display:none;';?>">
				<td>
					<?php _e('Model Dimensions', '3dprint-lite');?>:
				</td>
				<td>
					<span id="stats-length"></span> x <span id="stats-width"></span> x <span id="stats-height"></span>
					<?php _e('cm', '3dprint-lite');?>
				</td>
			</tr>

		</table>
	</div>
</div>
<div class="p3dlite-details">
	<div id="price-wrapper">
		<div id="price-container">
			<p class="price">
			        <?php if ( $settings['pricing']=='request_estimate' ) echo '<b>'.__( 'Estimated Price:', '3dprint-lite' ).'</b>';?>
				<span class="amount"></span>
			</p>
		</div>
	</div>

	<form action="" style="margin-bottom:0px;" class="variations_form cart" method="post" enctype='multipart/form-data' data-product_id="<?php echo $post->ID; ?>">
		<input type="hidden" name="p3dlite_product_id" value="<?php echo get_the_ID();?>">
		<input type="hidden" id="pa_p3dlite_printer" name="attribute_pa_p3dlite_printer" value="">
		<input type="hidden" id="pa_p3dlite_material" name="attribute_pa_p3dlite_material" value="">
		<input type="hidden" id="pa_p3dlite_coating" name="attribute_pa_p3dlite_coating" value="">
		<input type="hidden" id="pa_p3dlite_model" name="attribute_pa_p3dlite_model" value="">
		<input type="hidden" id="pa_p3dlite_unit" name="attribute_pa_p3dlite_unit" value="">
		<input type="hidden" id="p3dlite_estimated_price" name="p3dlite_estimated_price" value="">
		<input type="hidden" id="p3dlite-resize-scale" name="p3dlite_resize_scale" value="1">
		<input type="hidden" id="p3dlite-scale-x" name="p3dlite_scale_x" value="">
		<input type="hidden" id="p3dlite-scale-y" name="p3dlite_scale_y" value="">
		<input type="hidden" id="p3dlite-scale-z" name="p3dlite_scale_z" value="">
                <?php do_action( 'p3dlite_form' );?>
		<div id="p3dlite-quote-loading" class="p3dlite-info">
			<img alt="Loading price" src="<?php echo plugins_url( '3dprint-lite/images/ajax-loader.gif' ); ?>">
		</div>

<?php
	if ( !empty( $p3dlite_email_status_message ) ) echo '<div class="p3dlite-info">'.$p3dlite_email_status_message.'</div>';
?>
		<div id="add-cart-wrapper">
			<div id="add-cart-container">
				<div class="variations_button p3dlite-info">
					<input type="hidden" value="request_price" name="action">
					<input class="price-request-field" type="text" value="" placeholder="<?php _e( 'Enter Your E-mail', '3dprint-lite' );?>" name="email_address">
					<input class="price-request-field" type="text" value="" placeholder="<?php _e( 'Leave a comment', '3dprint-lite' );?>" name="request_comment"><br>
					<button style="float:left;" type="submit" class="button alt"><?php _e( 'Request a Quote', '3dprint-lite' ); ?></button>
				</div>
			</div>
		</div>
	</form>

<?php
	$db_printers=get_option( 'p3dlite_printers' );
	$db_materials=get_option( 'p3dlite_materials' );
	$db_coatings=get_option( 'p3dlite_coatings' );

$assigned_materials = p3dlite_get_assigned_materials($db_printers, $db_materials);
#foreach ($db_materials as $key => $material) {
#	if (!in_array($key, $assigned_materials)) unset($db_materials[$key]);
#}


switch ($settings['materials_layout']) {
	case 'lists':
		include('templates/template_material_list.php');
	break;
	case 'dropdowns':
		include('templates/template_material_dropdown.php');
	break;
	case 'colors':
		include('templates/template_material_colors.php');
	break;
	default:
		include('templates/template_material_list.php');
	break;
}

switch ($settings['coatings_layout']) {
	case 'lists':
		include('templates/template_coating_list.php');
	break;
	case 'dropdowns':
		include('templates/template_coating_dropdown.php');
	break;
	case 'colors':
		include('templates/template_coating_colors.php');
	break;
	default:
		include('templates/template_coating_list.php');
	break;
}

switch ($settings['printers_layout']) {
	case 'lists':
		include('templates/template_printer_list.php');
	break;
	case 'dropdowns':
		include('templates/template_printer_dropdown.php');
	break;
	default:
		include('templates/template_printer_list.php');
	break;
}


?>





</div>




<?php

	$content = ob_get_clean();

	return $content;
}
?>