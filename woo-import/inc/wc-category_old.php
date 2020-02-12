<?php
$product_categories = $categories;

$category_word = ">";
if (strpos($product_categories, $category_word) !== false)
{

    $exp_category = explode(">", $product_categories);
    $categoryian_id = array();

    $i = 1;
    foreach ($exp_category as $exp_categorys)
    {

        //echo $product_id . $exp_categorys . "<br/>";
        

        $cat_name = trim($exp_categorys);
        $taxonomy = 'product_cat';

        $cat = get_term_by('name', $cat_name, $taxonomy);

        if ($cat == false)
        {

            $cat_ins = wp_insert_term($cat_name, $taxonomy, array(
                'description' => '',
                'slug' => ''
            ));
			
            $categoryian_id[$i] = $cat_ins['term_id'];

        }
        else
        {

            $categoryian_id[$i] = $cat->term_id;

        }

        $i++;
    }

    
    $product_cat_id = array();
    foreach ($terms as $term)
    {
		if($term->name != "uncategorized" OR $term->name != "Uncategorized"){
         $product_cat_id[] = $term->term_id;
		}
		
		
    }
	
	
	//echo "<pre>";
	//print_r(get_term_by('id', , 'product_cat'));

    wp_set_object_terms($product_id, array_merge($categoryian_id, $product_cat_id) , $taxonomy);

}
else
{

    $categoryian_id = array();

    $i = 1;

    $cat_name = trim($product_categories);
    $taxonomy = 'product_cat';

    $cat = get_term_by('name', $cat_name, $taxonomy);

    if ($cat == false)
    {

        $cat_ins = wp_insert_term($cat_name, $taxonomy, array(
            'description' => '',
            'slug' => ''
        ));

        $categoryian_id[$i] = $cat_ins['term_id'];

    }
    else
    {

        $categoryian_id[$i] = $cat->term_id;

    }

    $terms = get_the_terms($product_id, 'product_cat');
    $product_cat_id = array();
    foreach ($terms as $term)
    {		
		if($term->name != "uncategorized" OR $term->name != "Uncategorized"){
			$product_cat_id[] = $term->term_id;
		}
    }

    wp_set_object_terms($product_id, array_merge($product_cat_id, $categoryian_id) , $taxonomy);

}

?>