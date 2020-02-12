<style>
.showfields {
  font-family: "Trebuchet MS", Arial, Helvetica, sans-serif;
  border-collapse: collapse;
  width: 100%;
}

.form_submit_run_importer {
    font-size: 16px;
    border-radius: 5px;
    padding: 5px;
    width: 30%;
    background: #4CAF50;
    color: #fff;
    cursor: pointer;
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
  background-color: #3b5998;
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
	font-size: 16px;
    border-radius: 5px;
    padding: 5px;
    width: 30%;
    background: #3b5998;
    color: #fff;
    cursor: pointer;
}

.btnsub{
	text-align-right;
	margin-right:20px;	
	
}

input.form_submit {
    cursor: pointer;
}

</style>

<h2>Upload CSV</h2>




<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>

<script>
jQuery(document).ready(function(){
    
	
	jQuery( ".templates_in" ).change(function() {
	  //alert(jQuery( this ).val());
	  
		setTimeout(
		function() 
		{
		   jQuery(".form_submit").click();
		}, 500);
	  
	});
	
	
	
	
	  jQuery("p.skipfiledimport").toggle();
	  jQuery(".selectorclick").click(function(){
		jQuery("p.skipfiledimport").toggle();
	  });
	
	
});
</script>
</head>



<?php

    $array_ignore_list = array();
	
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
						'attribute_color',
						'attribute_size',
						'attribute_building',
						'tax_class',
						'gtin',
						'shape',
						'post_meta',
						'featured_image',
						'custom_id');
						
 $tempalate = get_option('template_name_save');
 $tempalate = explode(",",$tempalate);
 $csv_import = "";
 
 if(isset($_REQUEST['templates_in'])){
	 update_option('csv_import',$_REQUEST['templates_in']);
	
 }
  
 $csv_import = get_option('csv_import');
 
 
 $checkbox = isset( $_POST['checkmake'] ) ? 'yes' : 'no';
 update_option('_checkboxcv', $checkbox );
 
 $checkbox_ms = isset( $_POST['markstart'] ) ? 'yes' : 'no';
 update_option('markstart', $checkbox_ms );
 
 
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
    <input type="hidden" name="loadcsv" value="loadcsv" />
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

	  
if( ( !empty($_FILES['import_file']['name']) && $extension == 'csv' ) OR isset( $_POST['submit_button_press'] )){
	
		if(isset($_REQUEST['uplodfilename'])){
			$fnameupload = $_REQUEST['uplodfilename'];
		}
	
if(isset($_FILES['import_file']['tmp_name'])){

		//$directory =  base64_encode ( $_FILES['import_file']['tmp_name']);
		$directory =  time();
		
		$uploads_dir = trailingslashit( wp_upload_dir()['basedir'] ) . 'importfiles' . "/" . $directory;
		wp_mkdir_p( $uploads_dir );
		
		$file_storage = $uploads_dir . "/" . "uploadfile";
		wp_mkdir_p($file_storage);
		
		$log_storage = $uploads_dir . "/" . "logfile";
		wp_mkdir_p($log_storage);
		
		
		if (file_exists($file_storage)){
			
			move_uploaded_file($_FILES['import_file']['tmp_name'], $file_storage . "/" . basename($_FILES['import_file']['name']));
		}
		
		$FileName = $log_storage. "/" . "log.txt";
		$FileHandle = fopen($FileName, 'w') or die("can't open file");
		fclose($FileHandle);
		
		$logfilename    = "";
		$uploadfilename = "";
		
		$fnameupload = $file_storage . "/" . basename($_FILES['import_file']['name']);
		
		
		
		  $fnameupload = explode('wp-content',$fnameupload);
		  $fnameupload =  rtrim(get_site_url(), '/') . "/wp-content" .$fnameupload[1];
		  
		  $FileName    = explode('wp-content',$FileName);
		  $FileName    =  rtrim(get_site_url(), '/') . "/wp-content" . $FileName[1];
		
	}	
	
	
	
	
	
if(isset($_REQUEST['submit_button_press'])){

		$checkbox = isset( $_POST['checkmake'] ) ? 'yes' : 'no';
		update_option('_checkboxcv', $checkbox );		
		
		if( isset($_POST['adding_template_name']) AND ! empty( $_POST['adding_template_name'] ) ) {
			update_option('_adding_template_name', esc_attr( $_POST['adding_template_name'] ) );
		}
}
	


if(isset($_POST['submit_button_press']) && $_POST['templates_in'] != 'null'){
	
			$get_the_temp = "";
			$get_the_temp = $_POST['templates_in'];
	
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
			
			foreach($woo_titles as $tilte_key => $title_value){	
		    $check = "";
			$check = isset( $_REQUEST[$title_value] ) ? 'yes' : 'no';
			
				$array_ignore_list[] = $title_value . "|" . $check;
			
			}
				

			$get_temp_selection = get_option('_checkboxcv');
			
			if($get_temp_selection == "yes"){
				update_option('checklist_frontend'. $get_the_temp,$c);
				update_option('checklist_backend_woo_fields'. $get_the_temp,$d);
				update_option('checklist_backend_csv_fields'. $get_the_temp,$e);
				update_option('_checkboxcv', "no" );
				update_option('checklist_ignore'. $get_the_temp , $array_ignore_list);
				
				
			}
			
			$markstart = get_option('markstart');
			
			if($markstart == "yes"){
				
				global $wpdb;
				
				$checkbox_m = isset( $_REQUEST['markstart'] ) ? 'yes' : 'no';
				
				$set_marker = "";
				if($checkbox_m == "yes"){
					$set_marker = "register";
				}
				
				$action_triggered = "";
				if(isset($_REQUEST['actiontrigger'])){
					$action_triggered = $_REQUEST['actiontrigger'];
				}
				
				
				if(isset($_REQUEST['uplodfilename'])){
					$upload_file = "";
					$upload_file = $_REQUEST['uplodfilename'];
				}
				
				if(isset($_REQUEST['logfilename'])){
					$log_file = "";
					$log_file = $_REQUEST['logfilename'];
				}
				
				date_default_timezone_set("Asia/Karachi");
				
				$wpdb->insert($wpdb->prefix . 'process_list', array(
								'log_file' => $log_file,
								'upload_file' => $upload_file,
								'csv_import_settings' => 'checklist_frontend'. $get_the_temp,
								'action' => $action_triggered,
								'state' => $set_marker,
								'template_name' => $get_the_temp,
								'process_id' => '',
								'activity_date' => date('Y-m-d h:i:s'),								
							));
				
			}
			
	
}





if(isset($_POST['submit_button_press']) && $_POST['templates_in'] == 'null'){
	
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
			
			$get_temp_selection = "";
			$get_temp_selection = get_option('_checkboxcv');
			
			if($get_temp_selection == "yes"){			
				update_option('checklist_frontend'. $get_the_temp,$c);
				update_option('checklist_backend_woo_fields'. $get_the_temp,$d);
				update_option('checklist_backend_csv_fields'. $get_the_temp,$e);
				update_option('_checkboxcv', "no" );
			}
}


// end of update










		
if(isset($_POST['submit_button_press']) && $_POST['templates_in'] == 'null' && isset($_POST['adding_template_name']) && !empty($_POST['adding_template_name']) ){	
				
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
		
		if(isset($_REQUEST['uplodfilename'])){
			$csvFile = fopen($_REQUEST['uplodfilename'], 'r');
		}else{
			$csvFile = fopen($fnameupload, 'r');
		}
		
		$get_the_headers = fgetcsv($csvFile);	
		
		
		$fetch_data = $d;
		
		$fetch_data_keys = array_values($fetch_data);
		
		$unserialize_csv      = $e;
		
		$unserialize_csv_keys = array_values($unserialize_csv);
			
		$count_headers = count($get_the_headers);
		
	}
	
	
	if( (isset($_POST['submit_button_press']) && $_POST['templates_in'] != 'null') OR isset($_POST['loadcsv']) ){
		
		// Original Steps
		
		$get_the_template_name = "";
		$get_the_template_name = $_POST['templates_in'];
				
        if(isset($_POST['submit_button_press'])){
			
			
			if(isset($_REQUEST['uplodfilename'])){
				$csvFile = fopen($_REQUEST['uplodfilename'], 'r');
			}else{
				$csvFile = fopen($fnameupload, 'r');
			}
			
			//$csvFile = fopen('http://laravel.site/wp-content/uploads/2020/02/sample_csv_file.csv', 'r');
		
		
		}else{
			
			if(isset($_REQUEST['uplodfilename'])){
				$csvFile = fopen($_REQUEST['uplodfilename'], 'r');
			}else{
				$csvFile = fopen($fnameupload, 'r');
			}
			
			//$csvFile = fopen($_FILES['import_file']['tmp_name'], 'r');
			
			//$csvFile = fopen('http://laravel.site/wp-content/uploads/2020/02/sample_csv_file.csv', 'r');
		}
		
		$get_the_headers = fgetcsv($csvFile);
				
		$unserialize 	 = get_option('checklist_backend_woo_fields'. $get_the_temp);
		
		$fetch_data      = unserialize($unserialize);
		
		$fetch_data_keys = array_values($fetch_data);
		
		$unserialize_csv = get_option('checklist_backend_csv_fields'. $get_the_temp);
		
		$unserialize_csv_keys = array_values($unserialize_csv);		
			
		$count_headers = count($get_the_headers);	
	}	
	/*
	$array_ignore_list = array();
	
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
						'attribute_color',
						'attribute_size',
						'tax_class',
						'gtin',
						'shape',
						'post_meta',
						'featured_image',
						'custom_id');
		*/	
					
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
				$output .= "<option ".$selected." value='".$i . "_" . $j ."'>". cleans(strtolower($get_the_headers[$i]))  ."</option>";
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
				$woo_output .= "<option ". $selected." value='".$woo_titles[$i] . "|" . $j ."'>". cleans(strtolower($woo_titles[$i]))  ."</option>";
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
	<td colspan="3">
	<h3 class="selectorclick">Please Click Here To Skip Field From Import</h3>
	<p class="skipfiledimport">
	<?php
		$ignore_list = "";
		
		if(isset($_POST['templates_in'])){
			$ignore_list = get_option('checklist_ignore'.$_POST['templates_in']);
		}else{
			$ignore_list = "";
		}
		
		foreach($woo_titles as $tilte_key => $title_value){		
			$slectedcheckbox = "";
			$get_save_checked_fields = explode("|",$ignore_list[$tilte_key]);
			$get_save_checked_fields = $get_save_checked_fields[1];
			
			if(trim($get_save_checked_fields) == "yes"){
				$slectedcheckbox = "checked";
			}else{
				$slectedcheckbox = "";
			}
			
			echo "<input type='checkbox' class='checkbox' style='' name='$title_value'  $slectedcheckbox>";
			echo "<label for='checkmake'>$title_value</label>";
			echo "<br/>";
			//update_option('_checkboxcv', $checkbox );
		}
	?>
	<p>
	</td>
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
								'value'       => $get_the_template_name
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
						   
						   $csv_imports = "";
						   
						   if(isset($_REQUEST['templates_in'])){
							   $csv_imports = $_REQUEST['templates_in'];
						   }
						   
						   $tempalate = get_option('template_name_save');
						   $tempalate = explode(",",$tempalate);
						   foreach($tempalate as $key => $value){
							    
								if($value == ""){
									continue;
								}
							   
								$selected = "";
								if($csv_imports == $value ){
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
	<td class="btnsub tbright">
	
	<?php
		woocommerce_wp_checkbox( 
			array( 
				'id'            => 'markstart', 
				'label'         => __('Register Process! ', 'woocommerce' ), 
				'description'   => __( '', 'woocommerce' ),
				'value'         => get_option('markstart'), 
				)
		 );
		 
		 echo "<hr/>";
	?>
	
	<select name="actiontrigger" class="actiontrigger">
	    <option value="">Please Select Action</option>
		<option value="insert">Insert</option>
		<option value="update">Update</option>
		<option value="insertupdate">Insert/Update</option>
	</select>
	
	
	   <?php
	    if(empty($FileName)){
			$FileName = $_REQUEST['logfilename'];
		}
		
		if(empty($fnameupload)){
			$fnameupload = $_REQUEST['uplodfilename'];
		}
	   ?>
		
		<input type="hidden" name="logfilename" value="<?php echo $FileName; ?>" />
		<input type="hidden" name="uplodfilename" value="<?php echo $fnameupload; ?>" />

	
	<input style="display:none" type="submit" class="form_submit_run_importer" name="start_process" value="START" />	
	<input type="submit" class="form_submit_run_importer" name="form_submit_run_importer" value="Run Importer" />
	<input type="submit" class="form_submit" name="submit" value="Save Settings" />
	
	</td> 
	<td></td>
  </tr>
</table>
</form>

<?php
}


	$checkbox_check = get_option('_checkboxcv');

	
	
	
	
	
	

	

	

	
if(isset($_POST['submit_button_press']) && $_POST['templates_in'] == 'null'){
	/*
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
			
		*/
}


function cleans($string){
	$string = str_replace(' ', '_', $string); // Replaces all spaces with hyphens.
	return preg_replace('/[^A-Za-z0-9\-]/', '', $string); // Removes special chars.
	
}


	
	



	


?>