<?php
define('WP_USE_THEMES', false);
require('../wc/wp-load.php');



		global $wpdb;

		$get_id = 4;   // Pass ARGV
		
		
		$result = $wpdb->get_results ("SELECT * FROM  wp_process_list WHERE id=$get_id");

		$db_file = $result[0]->upload_file;
		$db_log_file = $result[0]->log_file;
		$db_action = $result[0]->action;
		$db_settings = $result[0]->csv_import_settings;
		$db_state = $result[0]->state;
		$db_template_name = $result[0]->template_name;

		$extwpfoldername = "wc";

		date_default_timezone_set("Asia/Karachi");

		require 'vendor/autoload.php';

		define('ALLOW_INLINE_LINE_BREAKS', true);

		use Monolog\Logger;
		use Monolog\Handler\StreamHandler;
		use Monolog\Formatter\LineFormatter;
		use Monolog\Processor\PsrLogMessageProcessor;
		
		$formatter = new LineFormatter(null, null, ALLOW_INLINE_LINE_BREAKS);
		
		
		
		if(isset($db_log_file)){
			
			$get_dir_name =  basename(dirname(__FILE__));			
			$get_path = explode("wp-content",$db_log_file);
			$get_path =  str_replace($get_dir_name,$extwpfoldername,getcwd()) . "/wp-content" . $get_path[1];
			$handler = new StreamHandler($get_path);
		}else{
			$track = getcwd() . "/default_log.txt";
			$handler = new StreamHandler($track);
		}
		
		$handler->setFormatter($formatter);
		
		if (isset($db_template_name)){
			$logger = new Logger($db_template_name);
		}else{
			$logger = new Logger("default");
		}
		
		$logger->pushHandler($handler);
		$logger->pushProcessor(new PsrLogMessageProcessor);
		
		$filenamecsv = $db_file;
		$count_csv_rows = substr_count(file_get_contents($filenamecsv), "\r\n");

		if($count_csv_rows > 0){
			$count_csv_rows = $count_csv_rows -1;
		}

		global $logger;
		
		$get_the_file_name = $db_file;		
		
        $post_title  = $sale_price = $post_status = $visibility = $post_content = $sku = $regular_price = $sale_price = $manage_stock = $stock = $stock_status = $backorders = $weight = $length = $width = $height = $post_meta = $product_attribute = $image = $image_variation = $brand = $product_type = $categories = $tax_class = $sale_price = '';

        $get_the_temp = '';

        if (isset($db_template_name))
        {
            $get_the_temp = $db_template_name;
        }

        // File extension
        //////$extension = pathinfo($_FILES['product_csv_file']['name'], PATHINFO_EXTENSION);
		$extension = pathinfo($get_the_file_name, PATHINFO_EXTENSION);
		
		
		//$extension = "csv";
		

        // If file extension is 'csv'
        //////if (!empty($_FILES['product_csv_file']['name']) && $extension == 'csv')
		if (!empty($get_the_file_name) && $extension == 'csv')
        {
			

            // Open file in read mode
            //////$csvFile = fopen($_FILES['product_csv_file']['tmp_name'], 'r');
			$csvFile = fopen($get_the_file_name, 'r');			
				
            $get_the_header_title = fgetcsv($csvFile); // Skipping header row
            
			$get_the_custom_id = '';
			foreach($get_the_header_title as $header_key => $header_value){
				if(trim($header_value) == 'Custom ID'){
					$get_the_custom_id = $header_key;
				}
			}
			
			$check_value_list = array();
				
			$k++;	
            // Read file
            while (($csvData = fgetcsv($csvFile)) !== false)
            {
				

                $csvData = array_map("utf8_encode", $csvData);
				
                // Row column length
                $dataLen = count($csvData);

                $attr_array = array();
                $variation_array = array();
                $image_variation_array = array();
                $post_meta_array = array();
                $image_array = array();
                $product_variation = array();
                $category_array = array();
				$selector = "";
				$test_array = array();
				
				
				//// here we require command line operation also request actiontrigger  MUST
                $get_mapped_fields = unserialize(get_option('checklist_frontend' . $get_the_temp));
				
				
				
				//// here we will check the state if cancel so we will break it  MUST
				

                foreach ($get_mapped_fields as $get_mapped_fields_key => $get_mapped_fields_value)
                {

                    $get_mapped_fields_key = explode("|", $get_mapped_fields_key);
                    $get_mapped_fields_key = $get_mapped_fields_key[0];

                    $get_mapped_fields_value = explode("_", $get_mapped_fields_value);
                    $get_mapped_fields_value = $get_mapped_fields_value[0];

                    // Dynamic variable created as per header title
                    ${$get_mapped_fields_key} = $csvData[$get_mapped_fields_value];
					
										
					$test_array[] = array($get_mapped_fields_key => $get_mapped_fields_value );

                    $breakdown = explode("_",$get_mapped_fields_key);
					$breakdown_left  = $breakdown[0];
					$breakdown_right = $breakdown[1];
					
					$attribute_set = "";
					if($breakdown_left == "attribute"){
						
						global $wpdb;
												
						$get_all_attribute_lists = get_option('_transient_wc_attribute_taxonomies');
						
						if(empty($get_all_attribute_lists)){

							$args = array(
								'name'         => ucfirst($breakdown_right),
								'slug'         => strtolower($breakdown_right),
								'order_by'     => "menu_order",
								'has_archives' => "",
							);
								
							wc_create_attribute($args);
						}
						
						$temp_array = array();
						
						
						if (is_array($get_all_attribute_lists) || is_object($get_all_attribute_lists))
						{
							foreach($get_all_attribute_lists as $key => $value){								
								$temp_array[$key]  = $value->attribute_name;							
							}
						}
						
						
						if (!in_array(strtolower($breakdown_right), $temp_array))
						{
							$args = array(
								'name'         => ucfirst($breakdown_right),
								'slug'         => strtolower($breakdown_right),
								'order_by'     => "menu_order",
								'has_archives' => "",
							);
							
						    //wc_create_attribute($args);
							//$logger->error( "Attribute Transient Created " . $breakdown_right);
							//logger->info(ucfirst($breakdown_right) . " Attribute Created");
						}
						
						unset($temp_array);
						unset($args);
						
						$attribute_set = "yes";
						
						array_push($attr_array, array(
                            $get_mapped_fields_value => $csvData[$get_mapped_fields_value] . "|" . $breakdown_right
                        ));
					}
					
					if ($get_mapped_fields_key == 'product_attributeTTT')
                    {
                        array_push($attr_array, array(
                            $get_mapped_fields_value => $csvData[$get_mapped_fields_value]
                        ));
                    }

                    if ($get_mapped_fields_key == 'product_variation')
                    {
                        array_push($variation_array, array(
                            $get_mapped_fields_value => $csvData[$get_mapped_fields_value]
                        ));
                    }

                    if ($get_mapped_fields_key == 'categories')
                    {
                        array_push($category_array, array(
                            $get_mapped_fields_value => $csvData[$get_mapped_fields_value]
                        ));
                    }

                    if ($get_mapped_fields_key == 'image_variation')
                    {						
                        array_push($image_variation_array, $csvData[$get_mapped_fields_value]);
                    }

                    if ($get_mapped_fields_key == 'image')
                    {
                        array_push($image_array, $csvData[$get_mapped_fields_value]);
                    }

                    if ($get_mapped_fields_key == 'post_meta')
                    {
                        array_push($post_meta_array, $csvData[$get_mapped_fields_value] . "|" . $get_mapped_fields_value);
                    }
					
					
				}
				
				
				
					
					if($db_template_name != 'null'){
					
										
					$ignore_list_fields = get_option('checklist_ignore'.$db_template_name);
					
					
					foreach($ignore_list_fields as $ignore_list_key => $ignore_list_value){
						$get_save_checked_fields = explode("|",$ignore_list_value);
						$get_save_checked_field_right_side = $get_save_checked_fields[1];
						$get_save_checked_field_left_side =  $get_save_checked_fields[0];
						
						   //trim($get_save_checked_field_left_side);
							//$$get_save_checked_field_left_side = "";
						
						if(trim($get_save_checked_field_right_side) == "yes"){
							trim($get_save_checked_field_left_side);
							$$get_save_checked_field_left_side = "";
						}
					}
					
				}
				
				
				
				
					$signi      = "";
					$selector   = "";
					$counter_id =  "";
					
					
					if($product_type == "variable"){
						global $wpdb;
						 
						 $query =   "SELECT id FROM wp_posts
										INNER JOIN wp_postmeta
										ON wp_posts.id = wp_postmeta.post_id
										WHERE wp_posts.post_title = '$post_title'
										AND
										wp_posts.post_type = 'product_variation'
										AND
										wp_postmeta.meta_value = '$csvData[$get_the_custom_id]'";
						 
						  $counter_id = $wpdb->get_var($wpdb->prepare($query));
						
						
					}
					
					if($product_type == "simple"){
						global $wpdb;
						 
						$query =   "SELECT id FROM wp_posts
										INNER JOIN wp_postmeta
										ON wp_posts.id = wp_postmeta.post_id
										WHERE wp_posts.post_title = '$post_title'
										AND
										wp_posts.post_type = 'product'
										AND
										wp_postmeta.meta_value = '$csvData[$get_the_custom_id]'";
						 
						 $counter_id =  $wpdb->get_var($wpdb->prepare($query));
					}	

					
					if(isset($db_action) AND $db_action == "insertupdate"){
					
					
						if( $counter_id  > 0){
							$selector = "update";
							$signi	  = $selector;
						}else{
							$selector = "insert";
							$signi	  = $selector;
						}
						
						
						
						if($selector == "insert"){						
							include 'inc/insert.php';
						}else if($selector == "update"){						
							include 'inc/update.php';
						}
					
					}
					
					if(isset($db_action) AND $db_action == "insert"){
													
							$selector = "insert";
							$signi	  = $selector;
							include 'inc/insert.php';							
						
					}
					
					if(isset($db_action) AND $db_action == "update"){

						
							$selector = "update";
							$signi	  = $selector;
							include 'inc/update.php';						
					}
            
			
			$k++;
			}

        }
        else
        {
            echo "<center><h3 style='color: red;'>Invalid Extension</h3></center>";
        }

		$logger->info("Total $k Number of Products Completed Out of " . $count_csv_rows . "->" . " $k / $count_csv_rows");
  
  
  
  
  
  function clean($string)
    {
        $string = str_replace(' ', '_', $string); // Replaces all spaces with hyphens.
        return preg_replace('/[^A-Za-z0-9\-]/', '', $string); // Removes special chars.
        
    }
	
	function check_sku($sku){
		global $logger;
		
		if($sku == "" OR empty($sku)){
			return "";
		}
		
		//$sku = str_replace(' ', '-', $sku);;
		
		
		global $wpdb;
		$sku_check = $wpdb->get_var($wpdb->prepare("SELECT COUNT(*) AS sku_count FROM $wpdb->postmeta WHERE meta_key = '_sku' AND meta_value = '$sku' "));
		
				
		if($sku_check <= 0){
			$logger->info("SKU " . $sku);
			return $sku;
		}else{
			$logger->info("SKU exist" . $sku);
			return  "";
		}
	}
	
	function delete_variation($variation_post_id,$post_main_id){
		global $wpdb;
		$sku_check = $wpdb->query($wpdb->prepare("DELETE FROM $wpdb->posts WHERE post_parent = $post_main_id AND post_type = 'product_variation' AND id = $variation_post_id "));
	}

    function uploadMedia($image_url)
    {

        require_once (ABSPATH . 'wp-admin/includes/image.php');
        require_once (ABSPATH . 'wp-admin/includes/file.php');
        require_once (ABSPATH . 'wp-admin/includes/media.php');

        $media = media_sideload_image($image_url, 0);
        $attachments = get_posts(array(
            'post_type' => 'attachment',
            'post_status' => null,
            'post_parent' => 0,
            'orderby' => 'post_date',
            'order' => 'DESC'
        ));
        return $attachments[0]->ID;
    }
	
	function wcproduct_set_attributes($post_id, $attributes){
		$i = 0;
		// Loop through the attributes array
		foreach ($attributes as $name => $value) {
			$product_attributes[$i] = array (
				'name' => htmlspecialchars( stripslashes( $name ) ), // set attribute name
				'value' => $value, // set attribute value
				'position' => 1,
				'is_visible' => 1,
				'is_variation' => 1,
				'is_taxonomy' => 0
			);

			$i++;
		}

		// Now update the post with its new attributes
		update_post_meta($post_id, '_product_attributes', $product_attributes);
	}
	
	function updatedata($post_id, $key, $value){
		update_post_meta($post_id, $key, $value);
	}

	function updatepost($post_id,$value,$identifier){
		
		global $wpdb;
		
				
		if($identifier == "post_title"){
			$wpdb->get_var($wpdb->prepare("UPDATE wp_posts SET post_title='$value' WHERE id=$post_id"));
			
			$logger->info("Post title updated $post_id $value");
		}
		
		if($identifier == "post_content"){
			$wpdb->get_var($wpdb->prepare("UPDATE wp_posts SET post_content='$value' WHERE id=$post_id"));
			$logger->info("Post content updated $post_id $value");
		}
		
		if($identifier == "post_status"){
			$wpdb->get_var($wpdb->prepare("UPDATE wp_posts SET post_status='$value' WHERE id=$post_id"));
			$logger->info("Post status updated $post_id $value");
		}
		
	}	


	function attach_product_thumbnail($post_id, $url, $flag){
			global $logger;
			try{
			$image_url = $url;
			$url_array = explode('/',$url);
			$image_name = $url_array[count($url_array)-1];
			$image_data = @file_get_contents($image_url, true); // Get image data
		 
		    if($image_data){
		    $upload_dir = wp_upload_dir(); // Set upload folder
			$unique_file_name = wp_unique_filename( $upload_dir['path'], $image_name ); //    Generate unique name
			$filename = basename( $unique_file_name ); // Create image file name
			// Check folder permission and define file location
			if( wp_mkdir_p( $upload_dir['path'] ) ) {
				$file = $upload_dir['path'] . '/' . $filename;
			} else {
				$file = $upload_dir['basedir'] . '/' . $filename;
			}
			// Create the image file on the server
			file_put_contents( $file, $image_data );
			// Check image file type
			$wp_filetype = wp_check_filetype( $filename, null );
			// Set attachment data
			$attachment = array(
				'post_mime_type' => $wp_filetype['type'],
				'post_title' => sanitize_file_name( $filename ),
				'post_content' => '',
				'post_status' => 'inherit'
			);
			// Create the attachment
			$attach_id = wp_insert_attachment( $attachment, $file, $post_id );
			
			$logger->info("Product ID " . $post_id . " Image URL " . $url . " " . $flag . " Attach ID " . $attach_id);
			
			// Include image.php
			require_once(ABSPATH . 'wp-admin/includes/image.php');
			// Define attachment metadata
			$attach_data = wp_generate_attachment_metadata( $attach_id, $file );
			// Assign metadata to attachment
			wp_update_attachment_metadata( $attach_id, $attach_data );
			// asign to feature image
			if( $flag == 0){
				// And finally assign featured image to post
				set_post_thumbnail( $post_id, $attach_id );
				$logger->info("Product ID " . $post_id . "Assign Featured Image to Product. Atatch ID " . $attach_id);
			}
			// assign to the product gallery
			if( $flag == 1 ){
				// Add gallery image to product
				$attach_id_array = get_post_meta($post_id,'_product_image_gallery', true);
				$attach_id_array .= ','.$attach_id;
				update_post_meta($post_id,'_product_image_gallery',$attach_id_array);
				$logger->info("Product ID " . $post_id . "Assign Image Gallery to Product. Atatch ID " . $attach_id);
			}
			
			if( $flag == 2 ){
				// Add gallery image to product
				//$attach_id_array = get_post_meta($post_id,'_thumbnail_id', true);
				//$attach_id_array .= ','.$attach_id;
				//update_post_meta($post_id,'_thumbnail_id',$attach_id_array);
				set_post_thumbnail( $post_id, $attach_id );
				$logger->info("Product ID " . $post_id . "Assign Variation Image to Product. Atatch ID " . $attach_id);
			}
			
			
			}
			
			}catch(Exception $ex){
				$logger->info($post_id . " " . $url . " " . $flag);				
				$logger->error($ex);
			}
		}






?>