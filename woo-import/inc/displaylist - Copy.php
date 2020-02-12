<style>
.showfields {
  font-family: "Trebuchet MS", Arial, Helvetica, sans-serif;
  border-collapse: collapse;
  width: 100%;
}


select.op, select.wp{
	 width: 100%;
}

.showfields td, .showfields th {
  border: 1px solid #ddd;
  padding: 8px;
}

.showfields tr:nth-child(even){background-color: #f2f2f2;}

.showfields tr:hover {background-color: #ddd;}

.showfields th {
  padding-top: 12px;
  padding-bottom: 12px;
  text-align: left;
  background-color: #4CAF50;
  color: white;
}

.tbleft{
	width:50%;
	text-align: center;
}

.tbright{
	width:50%;
	text-align: center;
}

.showfields th{
	text-align: center;
    font-size: 18px;
}

.form_submit{
	text-align:center;	
}

.form_submit{
	font-size: 25px;
    border-radius: 5px;
    padding: 12px;
    width: 30%;
    background: #4CAF50;
    color: #fff;
    /* text-align: right;
}

.btnsub{
	text-align-right;
	margin-right:20px;	
	cursor:pointer;
}

input.form_submit {
    cursor: pointer;
}

</style>

<h2>Upload CSV</h2>

<?php
 $tempalate = get_option('template_name_save');
 $tempalate = explode(",",$tempalate);
 $csv_import = "";
 
 if(isset($_REQUEST['templates_in'])){
	 update_option('csv_import',$_REQUEST['templates_in']);
	
 }
  
 $csv_import = get_option('csv_import');
 
 
 $checkbox = isset( $_POST['checkmake'] ) ? 'yes' : 'no';
 update_option('_checkboxcv', $checkbox );
 
?>

<form method='post' action='<?= $_SERVER['REQUEST_URI']; ?>' enctype='multipart/form-data'>
<table class="showfields"> 
<tr>
<th>File</th>
<th>CSV</th>
</tr>
<tr>
<td>
	<input type="file" name="import_file" />
</td>
<td>
	<input type="submit" name="butimport" value="Load CSV">
</td>
</tr>
</table> 
</form>

<hr/><br/>


<?php
	
	$get_the_temp = '';

	if(isset($_REQUEST['templates_in'])){
		$get_the_temp = $_REQUEST['templates_in'];
	}
	
	if(isset($_REQUEST['get_the_temp'])){
		$get_the_temp = $_REQUEST['get_the_temp'];
	}
	
		  
    // File extension
    $extension = pathinfo($_FILES['import_file']['name'], PATHINFO_EXTENSION);

	  
 if( ( !empty($_FILES['import_file']['name']) && $extension == 'csv' ) OR isset( $_POST['submit_button_press'] ) ){
	
		
if(isset($_POST['submit_button_press']) && (isset($_POST['adding_template_name']) && $_POST['adding_template_name'] != ''  && !empty($_POST['adding_template_name']))){
				
				
		$get_the_woo_title = '';
		$c = array();
		$d = array();
		
		
		
		for($i=0; $i < $_REQUEST['submit_button_press']; $i++){	
			
			$wootitle = 'woo_title_' . $i;
			$get_the_woo_title = $_REQUEST[$wootitle];		
			
			$csv_headers = 'csv_headers_' . $i;
			$get_the_csv_title = $_REQUEST[$csv_headers];
			
			$c[$get_the_woo_title] = $_REQUEST[$csv_headers];						
							
			if (!empty($get_the_woo_title)){						
				$d[$get_the_woo_title] = $get_the_woo_title;
			}
			
			if (!empty($get_the_csv_title)){
				$e[$get_the_csv_title] = $get_the_csv_title;
			}
			
		}
								 
		if(isset($_POST['adding_template_name']) && ! empty($_POST['adding_template_name'])){
			
			$tempalate = ''; 
			$tempalate = get_option('template_name_save');
			
			if(empty($tempalate)){
				$tempalate = $_POST['adding_template_name'] . ",";
				update_option('template_name_save',$tempalate);	
			}else{
				
				$tempalate = $tempalate . $_POST['adding_template_name'] . ",";
				
				update_option('template_name_save',$tempalate);
			}
			
		}
		
		$csvFile = fopen('http://dev.wc/wp-content/uploads/2020/02/sample_csv_file.csv', 'r');
		$get_the_headers = fgetcsv($csvFile);	
		
		
		$fetch_data = $d;
		
		$fetch_data_keys = array_values($fetch_data);
		
		$unserialize_csv      = $e;
		
		$unserialize_csv_keys = array_values($unserialize_csv);
			
		$count_headers = count($get_the_headers);
		
	}else{
		
		// Original Steps
		
				
        if(isset($_POST['submit_button_press'])){
			$csvFile = fopen('http://dev.wc/wp-content/uploads/2020/02/sample_csv_file.csv', 'r');
		}else{
			$csvFile = fopen($_FILES['import_file']['tmp_name'], 'r');
		}
		
		$get_the_headers = fgetcsv($csvFile);	
		
		
		
		if(isset($_POST['templates_in']) && $_POST['templates_in'] != 'null'){
			
			//$get_temp_selection = get_option('_checkboxcv');
			
			//if($get_temp_selection == "yes"){
			
			$unserialize = get_option('checklist_backend_woo_fields'. $get_the_temp);
			$fetch_data = unserialize($unserialize);
			
			$fetch_data_keys = array_values($fetch_data);
			
			$unserialize_csv      = get_option('checklist_backend_csv_fields'. $get_the_temp);
			
			$unserialize_csv_keys = array_values($unserialize_csv);
			
			//}
			
		}
			
		$count_headers = count($get_the_headers);
	
	}
	
	
	
	
	
	
	
	$woo_titles = array('post_title',
						'post_content',
						'post_status',
						'post_content',
						'sku',
						'virtual',
						'visibility',
						'stock',
						'stock_status',
						'backorders',
						'manage_stock',
						'regular_price',
						'sale_price',
						'weight',
						'length',
						'width',
						'height',
						'image',
						'image_variation',
						'brand',
						'categories',
						'product_type',
						'product_attribute',
						'product_variation',
						'tax_class',
						'gtin',
						'shape',
						'post_meta',
						'featured_image',
						'custom_id');
			
					
	$count_woo_titles = count($woo_titles);
	
	
	$output = '';
	
		for($j=0 ; $j < $count_headers ; $j++){
			$output .=  "<select name='csv_headers_".$j."' class='csv_headers_".$j." op'>";
			
			
			   $output .= "<option value='null'>PLEASE SELECT CSV FIELD ...</option>";	
			
			
			for($i=0 ; $i < $count_headers ; $i++){
				$selected = '';
				////if(!empty(get_option('checklist_backend_csv_fields'. $get_the_temp))){
										
					if(isset($unserialize_csv_keys[$j]) && $unserialize_csv_keys[$j] == $i."_".$j){
							$selected = 'selected=selected';
					}else{
						$selected = '';
					}
				////}				
				$output .= "<option ".$selected." value='".$i . "_" . $j ."'>". $get_the_headers[$i]  ."</option>";
			}
			
			$output .= "</select><br/></br/>";
		}
		
		 
		
	$woo_output = '';
	
		for($j=0 ; $j < $count_headers ; $j++){
			
			$woo_output .=  "<select name='woo_title_".$j."' class='woo_title_".$j." wp'>";
			
			
			   $woo_output .= "<option value='null'>PLEASE SELECT WOOCOMMERCE FIELD ...</option>";	
						
			
			for($i=0 ; $i < $count_woo_titles ; $i++){
				
				////if(!empty(get_option('checklist_backend_woo_fields'. $get_the_temp))){
				
					$woo_titles_key = $woo_titles[$i] . "|" . $j;
					
					if($woo_titles_key == $fetch_data_keys[$j]){			
										  
							$selected = 'selected=selected'; 
						
					}else{
							$selected = '';
					}
				
				////}
				
				//$output .= "<option ".$selected." value='".$i . "_" . $j ."'>". $get_the_headers[$i]  ."</option>";
				$woo_output .= "<option ". $selected." value='".$woo_titles[$i] . "|" . $j ."'>". $woo_titles[$i]  ."</option>";
			}
			
			$woo_output .= "</select><br/></br/>";			
		}
		
		
	
?>

<form class="dispfields" method="post" action='<?= $_SERVER['REQUEST_URI']; ?>'>
<table class="showfields">
  <tr>	
    <th>WooCommerce Field</th>
    <th>CSV Field</th>
  </tr>
  <tr>
    <td class="tbleft"><?php echo $woo_output;  ?></td>  
	<td class="tbright"><?php echo $output; ?></td> 
	<td></td>
  </tr>
  <tr>
	<td class="tbleft">	
	
					<table style="width: 100%;">
					<tr>
					<td>
					
					<?php
						 
						 woocommerce_wp_text_input( 
							array( 
								'id'          => 'adding_template_name', 
								'label'       => __( '', 'woocommerce' ), 
								'placeholder' => 'Please write the template name',
								'desc_tip'    => 'true',
								'description' => __( '', 'woocommerce' ),
								'value'       => ''
							)
						);
						
					?>	
					
					</td>
					
					<td>
					
					<?php		
						 woocommerce_wp_checkbox( 
							array( 
								'id'            => 'checkmake', 
								'label'         => __('Save Template! ', 'woocommerce' ), 
								'description'   => __( '', 'woocommerce' ),
								'value'         => get_option('_checkboxcv'), 
								)
						 );
					?>
					</td>
					<td>
					
					<select name="templates_in" class="templates_in">
						 <option value="null">Load Tempalate</option>
						
						<?php
						   
						   
						   $tempalate = get_option('template_name_save');
						   $tempalate = explode(",",$tempalate);
						   foreach($tempalate as $key => $value){
							    
								if($value == ""){
									continue;
								}
							   
								$selected = "";
								if($csv_import == $value ){
									$selected = "selected=selected";
								}else{
									$selected = "";
								}
								
								echo '<option '.$selected.' value="'.$value.'">'.strtoupper($value).'</option>';
						   }
						?>
					  </select>
					
					
					<input type="hidden" name="get_the_temp" value="<?php echo $_REQUEST['templates_in']; ?>"/>
					<input type="hidden" name="submit_button_press" value="<?php echo $count_headers; ?>"/>
					
					</td>
					</tr>
					</table>
	
	</td>
	<td class="btnsub tbright"><input type="submit" class="form_submit" name="submit" value="Submit" />
	
	</td> 
	<td></td>
  </tr>
</table>
</form>

<?php
}


$checkbox_check = get_option('_checkboxcv');



if(isset($_REQUEST['submit_button_press'])){
	

	

	
	//if($checkbox_check == "yes"){
if(isset($_POST['submit_button_press']) && (isset($_POST['adding_template_name']) && $_POST['adding_template_name'] != ''  && !empty($_POST['adding_template_name']))){	
	
			$get_the_temp = "";
			$get_the_temp = $_POST['adding_template_name'];
	
			$get_the_woo_title = '';
			$c = array();
			$d = array();
			
			for($i=0; $i < $_REQUEST['submit_button_press']; $i++){		
				
				$wootitle = 'woo_title_' . $i;
				$get_the_woo_title = $_REQUEST[$wootitle];		
				
				$csv_headers = 'csv_headers_' . $i;
				$get_the_csv_title = $_REQUEST[$csv_headers];
				
				$c[$get_the_woo_title] = $_REQUEST[$csv_headers];
								
								
				if (!empty($get_the_woo_title)){						
					$d[$get_the_woo_title] = $get_the_woo_title;
				}
				
				if (!empty($get_the_csv_title)){
					$e[$get_the_csv_title] = $get_the_csv_title;
				}
				
				
			}
			
			$c = serialize($c);
			$d = serialize($d);
					
			update_option('checklist_frontend'. $get_the_temp,$c);
			update_option('checklist_backend_woo_fields'. $get_the_temp,$d);
			update_option('checklist_backend_csv_fields'. $get_the_temp,$e);
	}
	
	$checkbox = isset( $_POST['checkmake'] ) ? 'yes' : 'no';
	update_option('_checkboxcv', $checkbox );
	
	
    if( isset($_POST['adding_template_name']) AND ! empty( $_POST['adding_template_name'] ) ) {
        update_option('_adding_template_name', esc_attr( $_POST['adding_template_name'] ) );
    }


}
	


?>