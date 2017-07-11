	<nav <?php if ($settings['show_materials']!='on') echo 'style="display:none;"';?> class="applePie p3dlite-info">
		<div style="display:none;" class="menubtn"><?php _e( 'Material', '3dprint-lite' );?></div>
		<ul class="nav">
			<li class="p3dlite-dropdown-li"><a id="p3dlite-material-name" href="javascript:void(0)"><?php _e( 'Material', '3dprint-lite' );?> : <?php echo $db_materials[0]['name'];?></a>
				<ul>
<?php
		for ( $i=0;$i<count( $db_materials );$i++ ) {
			if (!in_array($i, $assigned_materials)) continue;
			echo '<li data-color=\''.$db_materials[$i]['color'].'\' data-shininess=\''.(isset($db_materials[$i]['shininess']) ? $db_materials[$i]['shininess'] : 'plastic').'\' data-glow=\''.(isset($db_materials[$i]['glow']) ? $db_materials[$i]['glow'] : '0').'\' data-transparency=\''.(isset($db_materials[$i]['transparency']) ? $db_materials[$i]['transparency'] : 'opaque').'\'  onclick="p3dliteSelectFilament(this);"><input style="display:none;" id="p3dlite_material_'.$i.'" class="p3dlite-control" autocomplete="off" type="radio" data-id="'.$i.'" data-density="'.esc_attr( $db_materials[$i]['density'] ).'" data-name="'.esc_attr( $db_materials[$i]['name'] ).'" data-color=\''.$db_materials[$i]['color'].'\' data-price="'.esc_attr( $db_materials[$i]['price'] ).'" data-price_type="'.$db_materials[$i]['price_type'].'" name="product_filament" ><a class="p3dlite-dropdown-item" href="javascript:void(0)"><div style="background-color:'.$db_materials[$i]['color'].'" class="color-sample"></div>'.__($db_materials[$i]['name'],'3dprint-lite').'</a></li>';
		}
?>
				</ul>
		</ul>
	</nav>