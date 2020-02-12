<?php

if($signi == "insert"){
	
$variations = array();

$variation_post_id = "";

global $wpdb;
$sku = check_sku($sku);


$color_value = $size_value = '';

$attributesvar = array();

foreach($attr_array as $key => $value){	
	foreach($value as $key => $value){
		
		$att_break = explode("|",$value);
		
		$att_break_value = $att_break[0];
		
		$att_break_title = ucfirst($att_break[1]);
		
		array_push($attributesvar,array("name"=>$att_break_title,"options"=>array(trim($att_break_value))));
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
					 "attributes"=>array( $attributesvar )));
					
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
			
			/*foreach($variation["attributes"] as $vattribute){
				$taxonomy = "pa_".wc_sanitize_taxonomy_name(stripslashes($vattribute["name"])); 
				$attr_val_slug =  wc_sanitize_taxonomy_name(stripslashes($vattribute["option"]));
				$var_attributes[$taxonomy]=$attr_val_slug;
			}*/
			
			foreach($attr_array as $key => $value){	
				foreach($value as $key => $value){
					
					$att_break = explode("|",$value);
					
					$att_break_value = $att_break[0];
					
					$att_break_title = ucfirst($att_break[1]);
					
					//array_push($attributesvar,array("name"=>$att_break_title,"options"=>array(trim($att_break_value))));
					
					$taxonomy = "pa_".wc_sanitize_taxonomy_name(stripslashes($att_break_title)); 
					$attr_val_slug =  wc_sanitize_taxonomy_name(stripslashes($att_break_value));
					$var_attributes[$taxonomy]=$attr_val_slug;
					
				}	
			}
			
			
			$objVariation->set_attributes($var_attributes);
			$variation_post_id = $objVariation->save();
			
			$logger->info( "Product Variation " . $variations);
			$logger->info( "Product Variation Created $variation_post_id");
			
			if(!empty($image_variation)){
						
						$file_headers = @get_headers($image_variation);
						
						if($file_headers[0] == 'HTTP/1.1 404 Not Found') {
							$exists = false;
							$logger->info( "Image Variation Not Exist $image_variation ");
						}
						else {
							$exists = true;
						}
						
						if($exists){
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
					
					$logger->info($variation_post_id . "Post Meta Added Tile:" . $get_title_headers . "Value:" . $value );
					
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
				
				$att_break = explode("|",$value);
				
				$att_break_value = $att_break[0];				
				$att_break_title = $att_break[1];
				
				$att_break_value  = strtolower(str_replace("'", '', $att_break_value));
				$att_break_value  = strtolower(str_replace(" ", '-', $att_break_value));
				$att_break_value  = strtolower(str_replace("'", '', $att_break_value));
				
				$attr_var_key = 'attribute_pa_' . strtolower($att_break_title);
				
				//echo $attr_var_key . " " . $att_break_value . "<br/>";
				
				update_post_meta($variations_product_id, $attr_var_key,$att_break_value);
				
				$logger->info($variations_product_id . "Variation Updated Tile:" . $attr_var_key . "Value:" . $att_break_value );
			}	
		}		
	}
}


?>