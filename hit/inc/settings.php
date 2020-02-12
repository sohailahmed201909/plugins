
<h2>SETTINGS - PAGE </h2>

<hr/>

<?php

$signi = get_option('radio_insert_update');
$tempalate = get_option('template_name_save');
$variation_custom_field = get_option('variation_custom_field');

if(isset($_POST['setting_save'])){
	
     if(isset($_POST['radio_insert_update'])){
		 $signi = $_POST['radio_insert_update'];
		 update_option('radio_insert_update',$_POST['radio_insert_update']);
	 }
	 
	 update_option('template_name_save',$_POST['template_name_save']);	 
	 $tempalate = get_option('template_name_save');
	 
	 update_option('variation_custom_field',$_POST['variation_custom_field']);
	 $variation_custom_field = get_option('variation_custom_field');
	 
	
}

?>


<form method='post' action='<?= $_SERVER['REQUEST_URI']; ?>' enctype='multipart/form-data'>
<span style="display:none"> 
<br/> 
<input name="radio_insert_update" type="radio"  value="insert" <?php echo ($signi == 'insert') ?  "checked" : "" ;  ?> /> Insert Product
<br/><br/>
<input name="radio_insert_update" type="radio"  value="update" <?php echo ($signi == 'update') ?  "checked" : "" ;  ?> /> Update Product
 <br/><br/> 
 </span>
 <h4>Save Tempalate</h4>
 
 <?php
	 echo '<textarea placeholder="Add comma separated template value" name="template_name_save" rows="10" cols="80">';
		echo $tempalate;
	 echo '</textarea>';
  ?>
 
 <br/><br/><hr/>
 <h2>Variation Custom Field Identifier</h2>
 <?php
	 echo '<textarea name="variation_custom_field" rows="10" cols="80">';
		echo $variation_custom_field;
	 echo '</textarea>';
  ?>
  
  <br/><br/><hr/>
  
  
<input type="submit" name="setting_save" value="Save Setting">
</form>


