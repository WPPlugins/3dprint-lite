<?php 
if ($db_coatings && count($db_coatings)>0) {
?>
	<div <?php if ($settings['show_coatings']!='on') echo 'style="display:none;"';?> class="p3dlite-info">
		<fieldset id="coating_fieldset" class="p3dlite-fieldset">
			<legend><?php _e( 'Coating', '3dprint-lite' );?></legend>
			<ul class="p3dlite-list">
<?php
		for ( $i=0;$i<count( $db_coatings );$i++ ) {
			echo '<li data-color=\''.$db_coatings[$i]['color'].'\' data-shininess=\''.(isset($db_coatings[$i]['shininess']) ? $db_coatings[$i]['shininess'] : 'none').'\' data-glow=\''.(isset($db_coatings[$i]['glow']) ? $db_coatings[$i]['glow'] : '0').'\' data-transparency=\''.(isset($db_coatings[$i]['transparency']) ? $db_coatings[$i]['transparency'] : 'none').'\' data-name="'.esc_attr( $db_coatings[$i]['name'] ).'" onclick="p3dliteSelectCoating(this);"><input id="p3dlite_coating_'.$i.'" class="p3dlite-control" autocomplete="off" type="radio" data-id="'.$i.'"  data-materials="'.(count($db_coatings[$i]['materials']) ? implode(',', $db_coatings[$i]['materials'] ) : '').'" data-price="'.esc_attr( $db_coatings[$i]['price'] ).'" data-price_type="'.esc_attr( $db_coatings[$i]['price_type'] ).'" name="product_coating" ><div style="background-color:'.$db_coatings[$i]['color'].'" class="color-sample"></div>'.__($db_coatings[$i]['name'], '3dprint-lite').'</li>';
		}
?>
			</ul>
		</fieldset>
	</div>
<?php
}
?>