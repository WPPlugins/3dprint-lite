	<nav <?php if ($settings['show_printers']!='on') echo 'style="display:none;"';?> class="applePie p3dlite-info">
		<div style="display:none;" class="menubtn"><?php _e( 'Printer', '3dprint-lite' );?></div>
		<ul class="nav">
			<li class="p3dlite-dropdown-li"><a id="p3dlite-printer-name" href="javascript:void(0)"><?php _e( 'Printer', '3dprint-lite' );?> : <?php echo $db_printers[0]['name'];?></a>
				<ul>
<?php
		for ( $i=0;$i<count( $db_printers );$i++ ) {
			echo '<li onclick="p3dliteSelectPrinter(this);" data-name="'.esc_attr( $db_printers[$i]['name'] ).'"><input style="display:none;" id="p3dlite_printer_'.$i.'" class="p3dlite-control" autocomplete="off" data-full_color="'.esc_attr( isset($db_printers[$i]['full_color']) ? $db_printers[$i]['full_color'] : '1' ).'" data-platform_shape="'.esc_attr( isset($db_printers[$i]['platform_shape']) ? $db_printers[$i]['platform_shape'] : 'rectangle' ).'" data-diameter="'.(float)$db_printers[$i]['diameter'].'" data-width="'.(float)$db_printers[$i]['width'].'" data-length="'.(float)$db_printers[$i]['length'].'" data-height="'.(float)$db_printers[$i]['height'].'" data-min_side="'.(float)$db_printers[$i]['min_side'].'" data-id="'.$i.'" data-name="'.esc_attr( $db_printers[$i]['name'] ).'" data-materials="'.(count($db_printers[$i]['materials']) ? implode(',', $db_printers[$i]['materials'] ) : '').'" data-price="'.esc_attr( $db_printers[$i]['price'] ).'" data-price_type="'.$db_printers[$i]['price_type'].'" type="radio" name="product_printer" ><a class="p3dlite-dropdown-item" href="javascript:void(0)">'.__($db_printers[$i]['name'],'3dprint-lite').'</a></li>';
		}
?>
				</ul>
		</ul>
	</nav>