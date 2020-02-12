<?php
//$product_categories = $categories;



$product_categories = explode(",",$categories);
$category_word = ">";



foreach($product_categories as $key => $value){

$category_word = ">";
if(strpos($value, $category_word) !== false){

    $exp_category = explode(">", $value);
    $categoryian_id = array();

    $i = 1;
    foreach ($exp_category as $exp_categorys)
    {
        $cat_name = trim($exp_categorys);
        $taxonomy = 'product_cat';

        $cat = get_term_by('name', $cat_name, $taxonomy);

        if ($cat == false)
        {

            $cat_ins = wp_insert_term($cat_name, $taxonomy, array(
                'description' => '',
                'slug' => ''
            ));
			
			$logger->info("Catergory Added $cat_name $cat_ins");

            $categoryian_id[$i] = $cat_ins['term_id'];

        }
        else
        {

            $categoryian_id[$i] = $cat->term_id;
			
			$logger->info("Pre Created Catergory Assign $categoryian_id[$i]");

        }
		
		if($i == 2){
			wp_update_term($categoryian_id[2], 'product_cat', array(
					  'parent'=> $categoryian_id[1]
					));
					
			$logger->info("Update Catergory $categoryian_id[2] Assin Parent $categoryian_id[1]");		
					
		}
		
		if($i == 3){
			wp_update_term($categoryian_id[3], 'product_cat', array(
					  'parent'=> $categoryian_id[2]
					));
			$logger->info("Update Catergory $categoryian_id[3] Assin Parent $categoryian_id[2]");
		}
		
		if($i == 4){
			wp_update_term($categoryian_id[4], 'product_cat', array(
					  'parent'=> $categoryian_id[3]
					));
			$logger->info("Update Catergory $categoryian_id[4] Assin Parent $categoryian_id[3]");
		}
		
		if($i == 5){
			wp_update_term($categoryian_id[5], 'product_cat', array(
					  'parent'=> $categoryian_id[4]
					));
			$logger->info("Update Catergory $categoryian_id[5] Assin Parent $categoryian_id[4]");
		}
		
		if($i == 6){
			wp_update_term($categoryian_id[6], 'product_cat', array(
					  'parent'=> $categoryian_id[5]
					));
			$logger->info("Update Catergory $categoryian_id[6] Assin Parent $categoryian_id[5]");
		}

        $i++;
    }

	global $wpdb;

	$uncat_id = $wpdb->get_var( "SELECT t.*, tt.* FROM $wpdb->terms AS t
		INNER JOIN $wpdb->term_taxonomy AS tt ON t.term_id = tt.term_id
		WHERE tt.taxonomy = 'product_cat' AND t.name = 'uncategorized'"
	);
	
    $terms = get_the_terms($product_id, 'product_cat');
    $product_cat_id = array();
    foreach ($terms as $term)
    {
		if($term->term_id == $uncat_id){
			continue;
		}
		
        $product_cat_id[] = $term->term_id;
    }
    wp_set_object_terms($product_id, array_merge($categoryian_id, $product_cat_id) , $taxonomy);
	
	
}
else
{

    $categoryian_id = array();

    $i = 1;

    $cat_name = trim($value);
    $taxonomy = 'product_cat';

    $cat = get_term_by('name', $cat_name, $taxonomy);

    if ($cat == false)
    {

        $cat_ins = wp_insert_term($cat_name, $taxonomy, array(
            'description' => '',
            'slug' => ''
        ));

        $categoryian_id[$i] = $cat_ins['term_id'];
		
		$logger->info("Category Created" . $categoryian_id[$i]);

    }
    else
    {

        $categoryian_id[$i] = $cat->term_id;
		$logger->info("Category Assigned" . $categoryian_id[$i]);
    }
	
	
	global $wpdb;

	$uncat_id = $wpdb->get_var( "SELECT t.*, tt.* FROM $wpdb->terms AS t
		INNER JOIN $wpdb->term_taxonomy AS tt ON t.term_id = tt.term_id
		WHERE tt.taxonomy = 'product_cat' AND t.name = 'uncategorized'"
	);

    $terms = get_the_terms($product_id, 'product_cat');
    $product_cat_id = array();
    foreach ($terms as $term)
    {
		
		if($term->term_id == $uncat_id){
			continue;
		}
		
		$product_cat_id[] = $term->term_id;
		
    }



    wp_set_object_terms($product_id, array_merge($product_cat_id, $categoryian_id) , $taxonomy);
	
	
}



}

?>