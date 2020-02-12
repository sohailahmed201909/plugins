<?php

if($signi == "insert"){
	
$variations = array();

$variation_post_id = "";

global $wpdb;
$sku = check_sku($sku);


$color_value = $size_value = '';

foreach($attr_array as $key => $value){	
	foreach($value as $key => $value){		 
		 $get_title_headers = strtolower(clean($get_the_header_title[$key]));
		 $get_title_headers = rtrim($get_title_headers,"_");		 
		
		 
		 if($get_title_headers == 'attributevaluepacolor'){
			 $color_value = $value;
		 }
		
		if($get_title_headers == 'attributevaluepachoosethesizefeet'){
			$size_value = $value;
		}		
	}	
}

$weight 		 = (!empty($weight)) ? $weight : "";
$length			 = (!empty($length)) ? $length : "";
$width			 = (!empty($width)) ? $width : "";
$height          = (!empty($height)) ? $height : "";
$sku             = (!empty($sku)) ? $sku : "";
$regular_price   = (!empty($regular_price)) ? $regular_price : "";
$sale_price 	 = (!empty($sale_price)) ? $sale_price : "";
$manage_stock	 = (!empty($manage_stock)) ? $manage_stock : "";
$stock 			 = (!empty($stock)) ? $stock : "";
$stock_status	 = (!empty($stock_status)) ? $stock_status : "";
$color_value 	 = (!empty($color_value)) ? $color_value : "";
$size_value		 = (!empty($size_value)) ? $size_value : "";


array_push( $variations,
				array("weight"=>$weight,
					 "lenght"=>$length,
					 "width"=>$width,
					 "sku"=>$sku,
					 "height"=>$height,
					 "regular_price"=>$regular_price,
					 "sale_price"=>$sale_price,
					 "manage_stock"=>$manage_stock,
					 "stock_quantity"=> $stock,
					 "stock_status"=> $stock_status,
					 "attributes"=>array
					 (array("name"=>"Size","option"=>trim($size_value)), array("name"=>"Color","option"=>trim($color_value)) )));
					
if($variations){
	try{
		foreach($variations as $variation){
			$objVariation = new WC_Product_Variation();
			$objVariation->set_price($sale_price);
			$objVariation->set_regular_price($variation["regular_price"]);
			$objVariation->set_parent_id($product_id);
			if(isset($variation["sku"]) && $variation["sku"]){
				$objVariation->set_sku($variation["sku"]);
			}
			$objVariation->set_manage_stock($variation["manage_stock"]);
			$objVariation->set_stock_quantity($variation["stock_quantity"]);
			$objVariation->set_weight($variation["weight"]);
			$objVariation->set_length($variation["lenght"]);
			$objVariation->set_width($variation["width"]);
			$objVariation->set_height($variation["height"]);
			$objVariation->set_stock_status($variation["stock_status"]);
			
			$var_attributes = array();
			foreach($variation["attributes"] as $vattribute){
				$taxonomy = "pa_".wc_sanitize_taxonomy_name(stripslashes($vattribute["name"])); 
				$attr_val_slug =  wc_sanitize_taxonomy_name(stripslashes($vattribute["option"]));
				$var_attributes[$taxonomy]=$attr_val_slug;
			}
			$objVariation->set_attributes($var_attributes);
			$variation_post_id = $objVariation->save();
			
			if(!empty($image_variation)){
						
						$file_headers = @get_headers($image_variation);
						
						if($file_headers[0] == 'HTTP/1.1 404 Not Found') {
							$exists = false;
						}
						else {
							$exists = true;
						}
						
						if($exists){
							
							if($signi == "update"){
							/*
							echo  $key_generate = $custom_id_query_id_sub . '_thumbnail_id_variation';
							echo "<br/>";
							
							 if(!in_array($key_generate, $check_value_list)){
								 update_post_meta($custom_id_query_id_sub, '_thumbnail_id','' );
								 array_push($check_value_list,$key_generate);
							 }
							 */
							}
							
							
							
							//attach_product_thumbnail($custom_id_query_id_sub, $image_variation, 2);
							attach_product_thumbnail($variation_post_id, $image_variation, 2);
							
							
						}
					}
			
			if(!empty($post_meta_array)){
				
				foreach($post_meta_array as $key => $value){
					
					$value_key = explode("|",$value);
					$value     = $value_key[0];
					$value_key = $value_key[1];
					
					
					$get_title_headers = strtolower(clean($get_the_header_title[$value_key]));
					$get_title_headers = rtrim($get_title_headers,"_");
					
					update_post_meta( $variation_post_id, $get_title_headers, $value );
					
					
				}			 
			}
			
			
			
			
		}
	}
	catch(Exception $e){
		
	}
}






}



// UPDATE BEGINS FROM HERE


if($signi == "update"){
	
	global $wpdb;
	
	$variations_product_id = $wpdb->get_var($wpdb->prepare("SELECT post_id FROM $wpdb->postmeta WHERE meta_key='customid' AND meta_value ='$csvData[$get_the_custom_id]'"));
	
	if($variations_product_id > 0){
		foreach($attr_array as $key => $value){	
			foreach($value as $key => $value){
				$get_title_headers = strtolower(clean($get_the_header_title[$key]));
				$get_title_headers = rtrim($get_title_headers,"_");
				 
				if($get_title_headers == 'attributevaluepacolor'){				   
					$color_value  = strtolower(str_replace("'", '', $value));
					$color_value  = strtolower(str_replace(" ", '-', $value));
					update_post_meta($variations_product_id, 'attribute_pa_color', $color_value);
					
					
					//$get_color_value = get_post_meta( $variations_product_id, 'attribute_pa_color', true ); 
					//wp_remove_object_terms( $product_id, $get_color_value , '_product_attributes' );
					//wp_remove_object_terms( $product_id, strtolower($get_color_value) , '_product_attributes' );
					
				}
				 
				if($get_title_headers == 'attributevaluepachoosethesizefeet'){				
					$size_value  = strtolower(str_replace("'", '', $value));
					update_post_meta($variations_product_id, 'attribute_pa_size',$size_value);
					
					
					
					//$get_size_value = get_post_meta( $variations_product_id, 'attribute_pa_size', true ); 
					//wp_remove_object_terms( $product_id, $get_size_value , '_product_attributes' );
					//wp_remove_object_terms( $product_id, strtolower($get_size_value) , '_product_attributes' );
					
				}
				
			}	
		}
	}
	
	
	
	
	
	
	
	
	
	
}


?>