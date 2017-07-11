<?php 
if ($db_coatings && count($db_coatings)>0) {
?>
	<nav <?php if ($settings['show_coatings']!='on') echo 'style="display:none;"';?> class="applePie p3dlite-info">
		<div style="display:none;" class="menubtn"><?php _e( 'Coating', '3dprint-lite' );?></div>
		<ul class="nav">
			<li class="p3dlite-dropdown-li"><a id="p3dlite-coating-name" href="javascript:void(0)"><?php _e( 'Coating', '3dprint-lite' );?> : <?php echo $db_coatings[0]['name'];?></a>
				<ul>
<?php
		for ( $i=0;$i<count( $db_coatings );$i++ ) {
			echo '<li data-color=\''.$db_coatings[$i]['color'].'\' data-shininess=\''.(isset($db_coatings[$i]['shininess']) ? $db_coatings[$i]['shininess'] : 'none').'\' data-glow=\''.(isset($db_coatings[$i]['glow']) ? $db_coatings[$i]['glow'] : '0').'\' data-transparency=\''.(isset($db_coatings[$i]['transparency']) ? $db_coatings[$i]['transparency'] : 'none').'\' data-name="'.esc_attr( $db_coatings[$i]['name'] ).'" onclick="p3dliteSelectCoating(this);"><input style="display:none;" id="p3dlite_coating_'.$i.'" class="p3dlite-control" autocomplete="off" type="radio" data-id="'.$i.'"  data-color=\''.$db_coatings[$i]['color'].'\' data-name="'.esc_attr( $db_coatings[$i]['name'] ).'"  data-materials="'.(count($db_coatings[$i]['materials']) ? implode(',', $db_coatings[$i]['materials'] ) : '').'" data-price="'.esc_attr( $db_coatings[$i]['price'] ).'" data-price_type="'.esc_attr( $db_coatings[$i]['price_type'] ).'" name="product_coating" ><a class="p3dlite-dropdown-item" href="javascript:void(0)"><div style="background-color:'.$db_coatings[$i]['color'].'" class="color-sample"></div>'.__($db_coatings[$i]['name'],'3dprint-lite').'</a></li>';
		}
?>
			</ul>
	</nav>
<?php
}
?>