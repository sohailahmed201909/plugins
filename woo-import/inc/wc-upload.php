<?php

$default_img = '';

if($signi == "insert" || $signi == "update"){
	
try{

if(!empty($featured_image)){
	
		
	$file_headers = @get_headers($featured_image);
	
	if($file_headers[0] == 'HTTP/1.1 404 Not Found') {
		$exists = false;
		$logger->error($featured_image . "Image Not Exist or Accessable");
	}
	else {
		$exists = true;
	}
	
	if($exists){
		
		if($signi == "update"){
		 $key_generate = $product_id . '_thumbnail_id';
		 if(!in_array($key_generate, $check_value_list)){
			 update_post_meta($product_id, '_thumbnail_id','' );
			 array_push($check_value_list,$key_generate);
		 }
		}
		
		attach_product_thumbnail($product_id, $featured_image, 0);
	}
}

/*
if(!empty($image_variation)){
	
						echo "<br/>";
							ECHO "teeeetthgulaab" . $image_variation;
							echo "<br/>";
							
						echo $image_variation . "$image_variation" . "<br/>";
						
						$file_headers = @get_headers($image_variation);
						
						if($file_headers[0] == 'HTTP/1.1 404 Not Found') {
							$exists = false;
						}
						else {
							$exists = true;
						}
						
						if($exists){
							
							if($signi == "update"){
							
							attach_product_thumbnail($variation_post_id, $image_variation, 2);
							
							
						}
					}

*/


if(!empty($image_array)){
	
	
	
	foreach($image_array as $screen_images){
		
		$file_headers = @get_headers($screen_images);
		if($file_headers[0] == 'HTTP/1.1 404 Not Found') {
			$exists = false;
		}
		else {
			$exists = true;
		}	
			
		if($exists){
			
			if($signi == "update"){
				$key_generate = $product_id . '_product_image_gallery';
				if(!in_array($key_generate, $check_value_list)){
					update_post_meta($product_id, '_product_image_gallery','' );
					array_push($check_value_list,$key_generate);
				}
			}
			
			attach_product_thumbnail($product_id, $screen_images, 1);
		}
	}
}


}catch(Exception $ex){
}

}
?>




