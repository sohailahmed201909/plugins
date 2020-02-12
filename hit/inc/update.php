<?php

global $wpdb;

if ($product_type == 'simple'){
	
	$custom_id_query = "SELECT id FROM wp_posts where post_title ='" . trim($post_title) . "'";
	$custom_id_query_id = $wpdb->get_var($wpdb->prepare($custom_id_query));
   
    $logger->info($post_title . " Simple Product Update Start ...");
	
	$sku = check_sku($sku);
	    
		if (!empty($post_meta_array)){

			foreach ($post_meta_array as $key => $value)
			{

				$value_key = explode("|", $value);
				$value = $value_key[0];
				$value_key = $value_key[1];

				$get_title_headers = strtolower(clean($get_the_header_title[$value_key]));
				$get_title_headers = rtrim($get_title_headers, "_");

				$logger->info($custom_id_query_id . " Post meta value " . $get_title_headers . "->" . $value);

				update_post_meta($custom_id_query_id, $get_title_headers, $value);
			}
        }
						
		if(!empty($post_title)){
			updatepost($custom_id_query_id,$post_title,'post_title');
			$logger->info("Update Post Title " . $post_title );
		}
			
		if(!empty($post_content)){
			updatepost($custom_id_query_id,$post_content,'post_content');
			$logger->info("Update Post Content " . $post_content );
		}
		
		if(!empty($post_status)){			
			updatepost($custom_id_query_id,$post_status,'post_status');
			$logger->info("Update Post Post Status " . $post_status );
		}
		
		if(!empty($sku)){
			updatedata($custom_id_query_id,'_sku',$sku);
			$logger->info("Update Post SKU " . $sku );
		}
		
		if(!empty($weight)){
			updatedata($custom_id_query_id,'_weight',$weight);
			$logger->info("Update Post weight " . $weight );
		}
		
		if(!empty($length)){
			updatedata($custom_id_query_id,'_length',$length);
			$logger->info("Update Post length " . $length );
		}
		
		if(!empty($width)){
			updatedata($custom_id_query_id,'_width',$width);
			$logger->info("Update Post width " . $width );
		}
		
		if(!empty($height)){
			updatedata($custom_id_query_id,'_height',$height);
			$logger->info("Update Post height " . $height );
		}
		
		if(!empty($regular_price)){
			updatedata($custom_id_query_id,'_regular_price',$regular_price);
			$logger->info("Update Post regular_price " . $regular_price );
		}
		
		if(!empty($sale_price)){
			updatedata($custom_id_query_id,'_sale_price',$sale_price);
			$logger->info("Update Post sale_price " . $sale_price );
		}
		
		if(!empty($stock)){
			updatedata($custom_id_query_id,'_stock',$stock);
			$logger->info("Update Post stock " . $stock );
		}
		
		if(!empty($stock_status)){
			updatedata($custom_id_query_id,'_stock_status',$stock_status);
			$logger->info("Update Post stock_status " . $stock_status );
		}
		
		if(!empty($manage_stock)){
			updatedata($custom_id_query_id,'_manage_stock',$manage_stock);
			$logger->info("Update Post manage_stock " . $manage_stock );
		}
		
				
		$product_id = $custom_id_query_id;
		
		
		if(isset($image_array)){
			
			include 'wc-upload.php';
		}
		
		if (!empty($categories)){	

		
			$exp_category = '';
			$exp_category = explode(">", $categories);
			
			$terms = '';
			$terms = get_the_terms($product_id, 'product_cat');
			 
			foreach ($terms as $term)
			{  
			  if (!in_array($term->name, $exp_category)) 
			  { 
				wp_remove_object_terms( $product_id, $term->term_id, 'product_cat' );
			  }				
			}
			 
			 include 'wc-category.php';
		}
		
		if (!empty($brand)){
			
			$exp_brands = '';
			$exp_brands = explode(">", $brand);
			
			$terms = '';
			$terms = get_the_terms($product_id, 'product_brands');
			 
			foreach ($terms as $term)
			{  
			  if (!in_array($term->name, $exp_brands)) 
			  { 
				wp_remove_object_terms( $product_id, $term->term_id, 'product_brands' );
			  }				
			}
			
		   include 'wc-brands.php';
		}

		//if (!empty($product_attribute)){
		if (!empty($attr_array)){
			include 'wc-attributes.php';
		}
		
		 $logger->info($product_id . " Simple Product Update Completed ");
				
}else if ($product_type == 'variable'){
	
	$custom_id_query_main    = "SELECT id FROM wp_posts where post_title ='" . trim($post_title) . "'";
	$custom_id_query_id_main = $wpdb->get_var($wpdb->prepare($custom_id_query_main));
	
	$custom_id_query_sub     = "SELECT post_id FROM wp_postmeta where meta_key = 'customid' and meta_value ='$csvData[$get_the_custom_id]'";
	$custom_id_query_id_sub  = $wpdb->get_var($wpdb->prepare($custom_id_query_sub));
	
	
	$logger->info("Variable Product Update Start ...");
	
	$sku = check_sku($sku);
	    
	if (!empty($post_meta_array)){

		foreach ($post_meta_array as $key => $value)
		{

			$value_key = explode("|", $value);
			$value = $value_key[0];
			$value_key = $value_key[1];

			$get_title_headers = strtolower(clean($get_the_header_title[$value_key]));
			$get_title_headers = rtrim($get_title_headers, "_");

			$logger->info("Variable Product Postmeta Update " . $get_title_headers . "->" . $value );
			
			update_post_meta($custom_id_query_id_sub, $get_title_headers, $value);
		}
	}
	
	
	
	if(!empty($post_title)){
		updatepost($custom_id_query_id_main,$post_title,'post_title');
		$logger->info("Variable Product Update post_title " .$post_title );
	}
		
	if(!empty($post_content)){
		updatepost($custom_id_query_id_main,$post_content,'post_content');
		$logger->info("Variable Product Update post_content " .$post_content );
	}
	
	if(!empty($post_status)){
		updatepost($custom_id_query_id_main,$post_status,'post_status');
		
		$logger->info("Variable Product Update post_status " .$post_status );
	}
	
	if(!empty($sku)){
			updatedata($custom_id_query_id_sub,'_sku',$sku);
			$logger->info("Variable Product Update sku " .$sku );
		}
		
		if(!empty($weight)){
			updatedata($custom_id_query_id_sub,'_weight',$weight);
			$logger->info("Variable Product Update _weight " .$weight );
		}
		
		if(!empty($length)){
			updatedata($custom_id_query_id_sub,'_length',$length);
			$logger->info("Variable Product Update _length " .$length );
		}
		
		if(!empty($width)){
			updatedata($custom_id_query_id_sub,'_width',$width);
			$logger->info("Variable Product Update width " .$width );
		}
		
		if(!empty($height)){
			updatedata($custom_id_query_id_sub,'_height',$height);
			$logger->info("Variable Product Update height " .$height );
		}
		
		if(!empty($regular_price)){
			updatedata($custom_id_query_id_sub,'_regular_price',$regular_price);
			$logger->info("Variable Product Update regular_price " .$regular_price );
		}
		
		if(!empty($sale_price)){
			updatedata($custom_id_query_id_sub,'_sale_price',$sale_price);
			$logger->info("Variable Product Update sale_price " .$sale_price );
		}
		
		if(!empty($stock)){
			updatedata($custom_id_query_id_sub,'_stock',$stock);
			$logger->info("Variable Product Update stock " .$stock );
		}
		
		if(!empty($stock_status)){
			updatedata($custom_id_query_id_sub,'_stock_status',$stock_status);
			$logger->info("Variable Product Update stock_status " .$stock_status );
		}
		
		if(!empty($manage_stock)){
			updatedata($custom_id_query_id_sub,'_manage_stock',$manage_stock);
			$logger->info("Variable Product Update manage_stock " .$manage_stock );
		}
		
		
		
		
		
		$product_id = $custom_id_query_id_main;
		
		
		
		if(isset($image_array)){
			include 'wc-upload.php';
		}
		
		if (!empty($categories)){			 
			$exp_category = '';
			$exp_category = explode(">", $categories);
			
			$terms = '';
			$terms = get_the_terms($product_id, 'product_cat');
			 
			foreach ($terms as $term)
			{  
			  if (!in_array($term->name, $exp_category)) 
			  { 
				wp_remove_object_terms( $product_id, $term->term_id, 'product_cat' );
			  }				
			}
			 
			 include 'wc-category.php';
		}
		
		if (!empty($brand)){
			
			$exp_brands = '';
			$exp_brands = explode(">", $brand);
			
			$terms = '';
			$terms = get_the_terms($product_id, 'product_brands');
			 
			foreach ($terms as $term)
			{  
			  if (!in_array($term->name, $exp_brands)) 
			  { 
				wp_remove_object_terms( $product_id, $term->term_id, 'product_brands' );
			  }				
			}
			
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
	
	    $logger->info($product_id . " Variable Product Update Completed ");
	
}


	

?>