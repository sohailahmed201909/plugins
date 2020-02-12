<?php

	global $wpdb;
	$sku = check_sku($sku);

	if ($product_type == 'simple'){
			
			
		$simple_insert_query = "SELECT id FROM $wpdb->posts WHERE post_title ='" . trim($post_title) . "'";
		$get_the_post_title_id = $wpdb->get_var($wpdb->prepare($simple_insert_query));

		if($get_the_post_title_id <= 0){
		  
			$objProduct = '';
            $objProduct = new WC_Product();
			
			$logger->info($post_title . " Simple Product Creation Start ...");
			
			if(!empty($post_title)){				
				$objProduct->set_name($post_title);
				$logger->info($post_title);
			}

			if (!empty($post_content)){
				$objProduct->set_description(wp_specialchars($post_content));
				$logger->info($post_content);
			}

			if(!empty($sku)){
				$objProduct->set_sku($sku);
				$logger->info($sku);
			}
			
			if(!empty($weight)){
				$objProduct->set_weight($weight);
				$logger->info($weight);
			}
			
			if(!empty($length)){
				$objProduct->set_length($length);
				$logger->info($length);
			}
			
			if(!empty($width)){
				$objProduct->set_width($width);
				$logger->info($width);
			}
			
			if(!empty($height)){
				$objProduct->set_height($height);
				$logger->info($height);
			}
			
			if(!empty($regular_price)){
				$objProduct->set_regular_price($regular_price);
				$logger->info($regular_price);
			}

			if(!empty($sale_price)){
				$objProduct->set_sale_price($sale_price);
				$logger->info($sale_price);
			}
			
			if(!empty($stock)){
				$objProduct->set_stock_quantity($stock);
				$logger->info($stock);
			}
			
			if(!empty($stock_status)){
				$objProduct->set_stock_status($stock_status);
				$logger->info($stock_status);
			}
			
			if(!empty($manage_stock)){
				$objProduct->set_manage_stock($manage_stock);
				$logger->info($manage_stock);
			}

			if(!empty($visibility)){				
				$objProduct->set_catalog_visibility($visibility);
				$logger->info($visibility);				
			}
			
			if(!empty($post_status)){	  
				$objProduct->set_status($post_status);
				$logger->info($post_status);
			}
			
			if(!empty($backorders)){
				$objProduct->set_backorders($backorders); 
				$logger->info($backorders);
			} 
			
			$product_id = '';
			$product_id = $objProduct->save();
			
			$logger->info("PRODUCT CREATED" . $product_id);
			
			if(isset($image_array)){
				include 'wc-upload.php';
			}

			if (!empty($categories)){
				 include 'wc-category.php';
			}

			if (!empty($brand)){
				include 'wc-brands.php';
			}

			if (!empty($attr_array)){
				include 'wc-attributes.php';
			}
			
			if (!empty($post_meta_array)){

				foreach ($post_meta_array as $key => $value)
				{

					$value_key = explode("|", $value);
					$value = $value_key[0];
					$value_key = $value_key[1];

					$get_title_headers = strtolower(clean($get_the_header_title[$value_key]));
					$get_title_headers = rtrim($get_title_headers, "_");

					update_post_meta($product_id, $get_title_headers, $value);
					
					$logger->info($product_id . "Post Meta Added Tile:" . $get_title_headers . "Value:" . $value );
				}
			}
		  
		  
		}else{
		  return "";
		}     
		
		$logger->info($product_id . " Simple Product Insert Completed ");
	}
                
				
				
		if ($product_type == 'variable'){			
		
		
		$get_the_post_title_id = "";
		$simple_insert_query = "SELECT ID FROM $wpdb->posts WHERE post_type = 'product' AND post_title ='" . trim($post_title) . "'";
		$get_the_post_title_id = $wpdb->get_var($wpdb->prepare($simple_insert_query));

		if($get_the_post_title_id > 0 AND !empty($get_the_post_title_id)){
		
		$get_the_customid = "";
		
		    $query_mid =   "SELECT * FROM wp_posts 
						INNER JOIN wp_postmeta
						ON wp_posts.id = wp_postmeta.post_id
						WHERE wp_posts.post_type = 'product_variation'
						AND
						meta_key='customid' 
						AND 
						meta_value ='$csvData[$get_the_custom_id]'";
		
			$get_the_customid = $wpdb->get_var($wpdb->prepare($query_mid));
		}
		
		if($get_the_post_title_id <= 0){
		  
		    $logger->info($post_title . " Variable Product Creation Start ...");
			
			$objProduct = '';
            $objProduct = new WC_Product_Variable();
			
			if(isset($post_title) && !empty($post_title)){				
				$objProduct->set_name($post_title);
				$logger->info($post_title);
			}

			if (isset($post_content) && !empty($post_content)){
				$objProduct->set_description(wp_specialchars($post_content));
				$logger->info($post_content);
			}

			if(isset($sku)){
				$objProduct->set_sku($sku);
				$logger->info($sku);
			}
			
			if(!empty($weight)){
				$objProduct->set_weight($weight);
				$logger->info($weight);
			}
			
			if(!empty($length)){
				$objProduct->set_length($length);
				$logger->info($length);
			}
			
			if(!empty($width)){
				$objProduct->set_width($width);
				$logger->info($width);
			}
			
			if(!empty($height)){
				$objProduct->set_height($height);
				$logger->info($height);
			}
			
			if(!empty($regular_price)){
				$objProduct->set_regular_price($regular_price);
				$logger->info($regular_price);
			}

			if(!empty($sale_price)){
				$objProduct->set_sale_price($sale_price);
				$logger->info($sale_price);
			}
			
			if(!empty($stock)){
				$objProduct->set_stock_quantity($stock);
				$logger->info($stock);
			}
			
			if(!empty($stock_status)){
				$objProduct->set_stock_status($stock_status);
				$logger->info($stock_status);
			}
			
			if(!empty($manage_stock)){
				$objProduct->set_manage_stock($manage_stock);
				$logger->info($manage_stock);
			}

			if(!empty($visibility)){				
				$objProduct->set_catalog_visibility($visibility);
				$logger->info($visibility);				
			}
			
			if(!empty($post_status)){	  
				$objProduct->set_status($post_status);
				$logger->info($post_status);
			}
			
			if(!empty($backorders)){
				$objProduct->set_backorders($backorders);  
				$logger->info($backorders);
			} 
			
			$product_id = '';
			$product_id = $objProduct->save();
			
			$logger->info("Product Created $product_id");
			
			if(isset($image_array)){
				include 'wc-upload.php';
			}

			if (!empty($categories)){
				 include 'wc-category.php';
			}

			if (!empty($brand)){
				include 'wc-brands.php';
			}
			
			//if (!empty($product_attribute)){
					if (!empty($attr_array)){
						include 'wc-attributes.php';
					}
					
					//if (!empty($product_attribute)){
					if (!empty($attr_array)){
						include 'wc-variations.php';
					}
			
			/*
			if(!empty($post_meta_array)){

				foreach ($post_meta_array as $key => $value)
				{

					$value_key = explode("|", $value);
					$value = $value_key[0];
					$value_key = $value_key[1];

					$get_title_headers = strtolower(clean($get_the_header_title[$value_key]));
					$get_title_headers = rtrim($get_title_headers, "_");

					update_post_meta($product_id, $get_title_headers, $value);
				}
			}
			*/
		  
		  
		}else if($get_the_post_title_id > 0){
					
				if(empty($get_the_customid) OR $get_the_customid <= 0){					
					
					$product_id = '';
					$product_id = $get_the_post_title_id;
					
					if(isset($image_array)){
						include 'wc-upload.php';
					}
					
					//if (!empty($product_attribute)){
					if (!empty($attr_array)){				
						include 'wc-attributes.php';
					}
					
					//if (!empty($product_attribute)){
					if (!empty($attr_array)){
						include 'wc-variations.php';
					}
					
				}				
		}

			$logger->info($product_id . " Variable Product Insert Completed ");
}
?>