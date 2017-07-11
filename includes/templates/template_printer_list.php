	<div <?php if ($settings['show_printers']!='on') echo 'style="display:none;"';?> class="p3dlite-info">
		<fieldset id="printer_fieldset" class="p3dlite-fieldset">
			<legend><?php _e( 'Printer', '3dprint-lite' );?></legend>
			<ul class="p3dlite-list">
<?php
		for ( $i=0;$i<count( $db_printers );$i++ ) {
			echo '<li onclick="p3dliteSelectPrinter(this);" data-name="'.esc_attr( $db_printers[$i]['name'] ).'"><input id="p3dlite_printer_'.$i.'" class="p3dlite-control" autocomplete="off" data-full_color="'.esc_attr( isset($db_printers[$i]['full_color']) ? $db_printers[$i]['full_color'] : '1' ).'" data-platform_shape="'.esc_attr( isset($db_printers[$i]['platform_shape']) ? $db_printers[$i]['platform_shape'] : 'rectangle' ).'" data-diameter="'.(float)$db_printers[$i]['diameter'].'" data-width="'.(float)$db_printers[$i]['width'].'" data-length="'.(float)$db_printers[$i]['length'].'" data-height="'.(float)$db_printers[$i]['height'].'" data-min_side="'.(float)$db_printers[$i]['min_side'].'" data-id="'.$i.'" data-materials="'.(count($db_printers[$i]['materials']) ? implode(',', $db_printers[$i]['materials'] ) : '').'" data-price="'.esc_attr( $db_printers[$i]['price'] ).'" data-price_type="'.$db_printers[$i]['price_type'].'" type="radio" name="product_printer">'.__($db_printers[$i]['name'], '3dprint-lite').'</li>';
		}
?>
		  	</ul>
	  	</fieldset>
	</div>