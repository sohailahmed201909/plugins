<?php

if($signi == "insert"){
	
	
	
	$attributes = array();

	foreach($attr_array as $key => $value){	
		foreach($value as $key => $value){
			
			$att_break = explode("|",$value);
			
			$att_break_value = $att_break[0];
			
			$att_break_title = ucfirst($att_break[1]);
			
			
			
			array_push($attributes,array("name"=>$att_break_title,"options"=>array(trim($att_break_value)),"position"=>1,"visible"=>1,"variation"=>1));
			
			/*
			$color_args = array(
			'name'         => "Color",
			'slug'         => "color",
			'order_by'     => "menu_order",
			'has_archives' => "",
			);

			$size_args = array(
			'name'         => "Size",
			'slug'         => "size",
			'order_by'     => "menu_order",
			'has_archives' => "",
			);

			wc_create_attribute($color_args);
			wc_create_attribute($size_args);
			*/
			
			$logger->info("Product " . $product_id . " Attribute $att_break_title Add $att_break_value");
			
			
			
			
		}	
	}

		
	if($attributes){
		$productAttributes=array();
		foreach($attributes as $attribute){
			$attr = wc_sanitize_taxonomy_name(stripslashes($attribute["name"]));
			$attr = 'pa_'.$attr;
			if($attribute["options"]){
				foreach($attribute["options"] as $option){
					wp_set_object_terms($product_id,$option,$attr,true);
				}
			}
			$productAttributes[sanitize_title($attr)] = array(
				'name' => sanitize_title($attr),
				'value' => $attribute["options"],
				'position' => $attribute["position"],
				'is_visible' => $attribute["visible"],
				'is_variation' => $attribute["variation"],
				'is_taxonomy' => '1'
			);
		}
		update_post_meta($product_id,'_product_attributes',$productAttributes);
	}

}



if($signi == "update"){
	
	foreach($attr_array as $key => $value){	
		foreach($value as $key => $value){
			
			$att_break = explode("|",$value);
			
			$att_break_value = $att_break[0];
			
			$att_break_title = "pa_". strtolower($att_break[1]);
			
			wp_set_object_terms( $product_id, $att_break_value, $att_break_title , true);
			
			$logger->info("Product " . $product_id . " Attribute $att_break_title Update $att_break_value");
			
			//array_push($attributes,array("name"=>$att_break_title,"options"=>array(trim($att_break_value)),"position"=>1,"visible"=>1,"variation"=>1));
		}	
	}
	
}

?>
