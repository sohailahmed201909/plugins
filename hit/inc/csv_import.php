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
	font-size: 25px;
    border-radius: 5px;
    padding: 12px;
    width: 30%;
    background: #3b5998;
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

<?php
 $tempalate = get_option('template_name_save');
 $tempalate = explode(",",$tempalate);
 $csv_import = "";
 
 if(isset($_REQUEST['templates_in'])){
	 update_option('csv_import',$_REQUEST['templates_in']);
	
 }
  $csv_import = get_option('csv_import');
?>

<h2>Map Fields</h2>

<p>Before completing the import, you can ensure that your column headers are mapped to the correct field in WordPress. This acts as a final check to make sure everything is will be imported correctly. Check the list to make sure that your fields are being mapped to the right place.</p>

<hr/><br/>

<h2>Import CSV Data Here</h2>

<form method='post' action='<?= $_SERVER['REQUEST_URI']; ?>' enctype='multipart/form-data'>
<table class="showfields"> 
<tr>
<th>Template Selection</th>
<th>Action</th>
<th>Product File</th>
<th>CSV</th>
</tr>
<tr>
<td>
	  <select name="templates_in" class="templates_in">
		 <option value="">Please Select The Tempalate</option>
		
		<?php
		   foreach($tempalate as $key => $value){
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
</td>
<td>
	<select name="get_action" class="templates_in">
	     <option value="">Please Select Insert/Update</option>
		 <option value="insert">Insert</option>
		 <option value="update">Update</option>
	</select>
</td>
<td>
	<input type="file" name="product_csv_file" />
</td>
<td>
	<input type="submit" name="import_products" value="Import Products">
</td>
</tr>
</table> 
</form>

<hr/><br/>



