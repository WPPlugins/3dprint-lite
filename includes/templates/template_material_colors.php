	<div <?php if ($settings['show_materials']!='on') echo 'style="display:none;"';?> class="p3dlite-info">
		<fieldset id="material_fieldset" class="p3dlite-fieldset">
			<legend id="p3dlite-material-name"><?php _e( 'Material', '3dprint-lite' );?></legend>
			<ul class="p3dlite-list p3dlite-colors">
<?php
		for ( $i=0;$i<count( $db_materials );$i++ ) {
			if (!in_array($i, $assigned_materials)) continue;
			echo '<li class="p3dlite-color-item" data-color=\''.$db_materials[$i]['color'].'\' data-shininess=\''.(isset($db_materials[$i]['shininess']) ? $db_materials[$i]['shininess'] : 'plastic').'\' data-glow=\''.(isset($db_materials[$i]['glow']) ? $db_materials[$i]['glow'] : '0').'\' data-transparency=\''.(isset($db_materials[$i]['transparency']) ? $db_materials[$i]['transparency'] : 'opaque').'\' data-name="'.esc_attr( $db_materials[$i]['name'] ).'" onclick="p3dliteSelectFilament(this);"><input style="display:none;" id="p3dlite_material_'.$i.'" class="p3dlite-control" autocomplete="off" type="radio" data-id="'.$i.'" data-color=\''.$db_materials[$i]['color'].'\' data-name="'.esc_attr( $db_materials[$i]['name'] ).'" data-density="'.esc_attr( $db_materials[$i]['density'] ).'" data-price="'.esc_attr( $db_materials[$i]['price'] ).'" data-price_type="'.$db_materials[$i]['price_type'].'" name="product_filament" ><div style="background-color:'.$db_materials[$i]['color'].'" class="color-sample"></div></li>';
		}
?>
			</ul>
		</fieldset>
	</div>