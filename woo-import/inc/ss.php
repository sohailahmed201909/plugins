<?php
/*
Plugin Name: Woocommerce product Import
Plugin URI: #
Description: Plugin for importing csv file values new
Version: 1.1 Version
Copyright: Ematrix
Contact: Sohail
Author: Sohail
Date Published: Jan 2020
*/

//if (isset($_POST['form_submit_run_importer']))
	
if (isset($_REQUEST['form_submit_run_importer']))
{
	
	date_default_timezone_set("Asia/Karachi");

	require 'vendor/autoload.php';


	define('ALLOW_INLINE_LINE_BREAKS', true);

	use Monolog\Logger;
	use Monolog\Handler\StreamHandler;
	use Monolog\Formatter\LineFormatter;
	use Monolog\Processor\PsrLogMessageProcessor;

	$formatter = new LineFormatter(null, null, ALLOW_INLINE_LINE_BREAKS);
	
	if(isset($_REQUEST['logfilename'])){
		$handler = new StreamHandler($_REQUEST['logfilename']);
	}else{
		$track = time() . 'log.txt';
		$handler = new StreamHandler($track);
	}
	
	$handler->setFormatter($formatter);
	
	if (isset($_REQUEST['templates_in']))
		$logger = new Logger($_REQUEST['templates_in']);
	}
	
	$logger->pushHandler($handler);
	$logger->pushProcessor(new PsrLogMessageProcessor);
	
			
    function product_uploader()
    {
		global $logger;
		
		$get_the_file_name = $_REQUEST['uplodfilename'];
		
		
        $post_title  = $sale_price = $post_status = $visibility = $post_content = $sku = $regular_price = $sale_price = $manage_stock = $stock = $stock_status = $backorders = $weight = $length = $width = $height = $post_meta = $product_attribute = $image = $image_variation = $brand = $product_type = $categories = $tax_class = $sale_price = '';

        $get_the_temp = '';

        if (isset($_REQUEST['templates_in']))
        {
            $get_the_temp = $_REQUEST['templates_in'];
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
						
						$args = array(
								'name'         => ucfirst($breakdown_right),
								'slug'         => strtolower($breakdown_right),
								'order_by'     => "menu_order",
								'has_archives' => "",
								);

						wc_create_attribute($args);
						
						$logger->info(ucfirst($breakdown_right) . " Attribute Created");
						
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
				
				
				
								
				
				
				//if(isset($_REQUEST['submit_button_press']) && $_REQUEST['templates_in'] != 'null'){
					
					if($_REQUEST['templates_in'] != 'null'){
					
										
					$ignore_list_fields = get_option('checklist_ignore'.$_REQUEST['templates_in']);
					
					
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

					
					if(isset($_REQUEST['actiontrigger']) AND $_REQUEST['actiontrigger'] == "insertupdate"){
					
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
					
					if(isset($_REQUEST['actiontrigger']) AND $_REQUEST['actiontrigger'] == "insert"){
						
						if( $counter_id  <= 0){
							$selector = "insert";
							$signi	  = $selector;
							include 'inc/insert.php';							
						}
					}
					
					if(isset($_REQUEST['actiontrigger']) AND $_REQUEST['actiontrigger'] == "update"){
						
						
						//if( $counter_id  > 0){							
							$selector = "update";
							$signi	  = $selector;
							include 'inc/update.php';
						//}
					}
					
					/*
					if(isset($_REQUEST['get_action'])){
						$signi = $_REQUEST['get_action'];
						
						if($signi == "insert"){						
							include 'inc/insert.php';
						}else if($signi == "update"){						
							include 'inc/update.php';
						}
					}else{
						echo "<center>";
							echo "<h1 style='color: red;'>Please Select The Action</h1>";
						echo "</center>";
						exit();
					}
					*/
					
            }

        }
        else
        {
            echo "<center><h3 style='color: red;'>Invalid Extension</h3></center>";
        }

    }

    add_action('init', 'product_uploader');

    function clean($string)
    {
        $string = str_replace(' ', '_', $string); // Replaces all spaces with hyphens.
        return preg_replace('/[^A-Za-z0-9\-]/', '', $string); // Removes special chars.
        
    }
	
	function check_sku($sku){
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
		
		/*		
		if($identifier == "post_title"){
						
		  $kv_edited_post = array(
			  'ID'           => $post_id,
			  'post_title' => $value
		  );
		  
		  wp_update_post( $kv_edited_post);
			  
		}
		
		if($identifier == "post_content"){
						
			$kv_edited_post = array(
				  'ID'           => $post_id,
				  'post_content' => $value
			  );
			 
			wp_update_post( $kv_edited_post);
			
		}
		*/
		
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

}




function plugin_menu()
{
    add_menu_page("Woo Import", "Woo Import", "manage_options", "wooimport", "displayList");

    add_submenu_page('wooimport', 'Settings', //page title
    'Settings', //menu title
    'edit_themes', //capability,
    'settings', //menu slug
    'getSetting'
    //callback function
    );

    add_submenu_page('wooimport', 'Process List', //page title
    'Process List', //menu title
    'edit_themes', //capability,
    'process-list', //menu slug
    'get_process_list'
    //callback function
    );
}

add_action("admin_menu", "plugin_menu");

function displayList()
{
    include "inc/displaylist.php";
}

function getSetting()
{
    include "inc/settings.php";
}

function get_process_list()
{
    include "inc/process_list.php";
}




/////////// START FROM HERE ///////////


// Add Variation Settings
add_action( 'woocommerce_product_after_variable_attributes','variation_settings_fields', 10, 3 );

// Save Variation Settings
add_action( 'woocommerce_save_product_variation', 'save_variation_settings_fields', 10, 2 );

// Create new fields for variations
function variation_settings_fields( $loop, $variation_data, $variation ) {
    
	$variation_custom_field = get_option('variation_custom_field');
	$variation_custom_field = explode(",",$variation_custom_field);
	
	foreach($variation_custom_field as $key => $value){
		
		$value = trim($value);
		
		// Text Field
		woocommerce_wp_text_input( 
			array( 
				'id'          => $value . '[' . $variation->ID . ']',
				'label'       => __( $value , 'woocommerce' ), 
				'placeholder' => 'Enter the custom value here.',
				'desc_tip'    => 'true',
				'description' => __( 'Enter the custom value here.', 'woocommerce' ),
				'value'       => get_post_meta( $variation->ID, $value, true )
			)
		);
	}
}

// Save new fields for variations

function save_variation_settings_fields( $post_id ) {
    
	$variation_custom_field = get_option('variation_custom_field');
	$variation_custom_field = explode(",",$variation_custom_field);
	
	foreach($variation_custom_field as $key => $value){
		
		$value = trim($value);
	
		// Text Field
		$text_field = $_REQUEST[$value][ $post_id ];
		if( ! empty( $text_field ) ) {
			update_post_meta( $post_id, $value, esc_attr( $text_field ) );
		}
	
	}
	
}

add_action('init', 'process_tool_activate');
 
function process_tool_activate(){
  
		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
		global $wpdb;
		global $charset_collate;
		$db_table_name = $wpdb->prefix . 'process_list';
	
		$sql_create_table = "CREATE TABLE IF NOT EXISTS ".$db_table_name." (
				  id bigint(20) unsigned NOT NULL auto_increment,				  			  		  
				  log_file text,
				  upload_file text,
				  csv_import_settings text,
				  action varchar(20),
				  state varchar(20),
				  template_name varchar(255) default '0',				  				  
				  activity_date datetime NOT NULL default '0000-00-00 00:00:00',
				  PRIMARY KEY  (id)
			 ) $charset_collate; ";
		 
		dbDelta( $sql_create_table );
		
   
}

?>