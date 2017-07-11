<?php 
if ($db_coatings && count($db_coatings)>0) {
?>
	<div <?php if ($settings['show_coatings']!='on') echo 'style="display:none;"';?> class="p3dlite-info">
		<fieldset id="coating_fieldset" class="p3dlite-fieldset">
			<legend id="p3dlite-coating-name"><?php _e( 'Coating', '3dprint-lite' );?></legend>
			<ul class="p3dlite-list p3dlite-colors">
<?php

		for ( $i=0;$i<count( $db_coatings );$i++ ) {
			echo '<li class="p3dlite-color-item" data-color=\''.$db_coatings[$i]['color'].'\' data-shininess=\''.(isset($db_coatings[$i]['shininess']) ? $db_coatings[$i]['shininess'] : 'none').'\' data-glow=\''.(isset($db_coatings[$i]['glow']) ? $db_coatings[$i]['glow'] : '0').'\' data-transparency=\''.(isset($db_coatings[$i]['transparency']) ? $db_coatings[$i]['transparency'] : 'none').'\' data-name="'.esc_attr( $db_coatings[$i]['name'] ).'" onclick="p3dliteSelectCoating(this);"><input style="display:none;" id="p3dlite_coating_'.$i.'" class="p3dlite-control" autocomplete="off" type="radio" data-color=\''.$db_coatings[$i]['color'].'\' data-name="'.esc_attr( $db_coatings[$i]['name'] ).'" data-id="'.$i.'"  data-materials="'.((isset($db_coatings[$i]['materials']) && count($db_coatings[$i]['materials'])) ? implode(',', $db_coatings[$i]['materials'] ) : '').'" data-price="'.esc_attr( $db_coatings[$i]['price'] ).'" data-price_type="'.esc_attr( $db_coatings[$i]['price_type'] ).'" name="product_coating" ><div style="background-color:'.$db_coatings[$i]['color'].'" class="color-sample"></div></li>';
		}
?>
			</ul>
		</fieldset>
	</div>
<?php
}
?>