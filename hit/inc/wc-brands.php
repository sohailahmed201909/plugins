<?php

$product_brand = $brand;
$brand_word = ">";
if (strpos($product_brand, $brand_word) !== false)
{

    $exp_brand = explode(">", $product_brand);
    $brand_id = array();

    $i = 1;
    foreach ($exp_brand as $exp_brands)
    {

        //echo $product_id . $exp_categorys . "<br/>";
        

        $brand_name = trim($exp_brands);
        $taxonomy = 'product_brands';

        $catbrand = get_term_by('name', $brand_name, $taxonomy);

        if ($catbrand == false)
        {

            $catbrand_ins = wp_insert_term($brand_name, $taxonomy, array(
                'description' => '',
                'slug' => ''
            ));

            $brand_id[$i] = $catbrand_ins['term_id'];
			
			$logger->info("Brand Added $brand_name $catbrand_ins");

        }
        else
        {

            $brand_id[$i] = $catbrand->term_id;
			
			$logger->info("Brand Assign $brand_id[$i]");

        }

        $i++;
    }

    $terms = "";
    $terms = get_the_terms($product_id, 'product_brands');
    $product_brand_id = array();
    foreach ($terms as $term)
    {
        $product_brand_id[] = $term->term_id;
    }

    wp_set_object_terms($product_id, array_merge($brand_id, $product_brand_id) , $taxonomy);

}
else
{

    $brand_id = array();

    $i = 1;

    $brand_name = trim($product_brand);
    $taxonomy = 'product_brands';

    $catbrand = get_term_by('name', $brand_name, $taxonomy);

    if ($catbrand == false)
    {

        $cat_ins = wp_insert_term($brand_name, $taxonomy, array(
            'description' => '',
            'slug' => ''
        ));

        $brand_id[$i] = $cat_ins['term_id'];
		
		$logger->info("Brand Created $brand_id[$i]");

    }
    else
    {
        $brand_id[$i] = $catbrand->term_id;		
		$logger->info("Brand Assign $brand_id[$i]");
    }

    $terms = "";
    $terms = get_the_terms($product_id, 'product_brands');
    $product_brand_id = array();
    foreach ($terms as $term)
    {
        $product_brand_id[] = $term->term_id;
    }

    wp_set_object_terms($product_id, array_merge($brand_id, $product_brand_id) , $taxonomy);

}

?>