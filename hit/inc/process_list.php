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

<?php

global $wpdb;
						 
				
date_default_timezone_set("Asia/Karachi");

if(isset($_REQUEST['process_submit_cancel'])){
if(isset($_REQUEST['cancle_process_id'])){
$id = $_REQUEST['cancle_process_id'];
$wpdb->update( $wpdb->prefix . 'process_list', array( 'state' => 'cancel'), array( 'id' => $id  ), array( '%s' ) );

$wpdb->query( $wpdb->prepare( "UPDATE  $wpdb->prefix" . "process_list set activity_date='" . date('Y-m-d h:i:s') . "'" ));

}			
}

if(isset($_REQUEST['process_submit_start'])){
if(isset($_REQUEST['start_process_id'])){
$id = $_REQUEST['start_process_id'];
$wpdb->update( $wpdb->prefix . 'process_list', array( 'state' => 'start'), array( 'id' => $id ), array( '%s' ) );
$wpdb->query( $wpdb->prepare( "UPDATE  $wpdb->prefix" . "process_list set activity_date='" . date('Y-m-d h:i:s') . "'" ));


}
}		

	
$query =   "SELECT * from $wpdb->prefix" . "process_list order by id desc";

$get_results = $wpdb->get_results($wpdb->prepare($query));
						 

?>



<table class="showfields" style="margin-top:2%"> 
<tr>
	<th>Id</th>
	<th>Log file</th>
	<th>Upload File</th>
	<th>Import Name</th>
	<th>Action</th>
	<th>State</th>
	<th>Process ID</th>
	<th>Templates</th>
	<th>Activity</th>
</tr>

<?php
  foreach($get_results as $key => $value){	  
?>

<tr>
	<td> <?php echo $value->id; ?> </td>
	<td> <a href="<?php echo $value->log_file; ?>">Log File</a> </td>
	<td> <a href="<?php echo $value->upload_file; ?>">Upload File</a> </td>
	<td> <?php echo $value->csv_import_settings; ?> </td>
	<td> <?php echo $value->action; ?> </td>
	<td> <?php //echo $value->state; ?> 
	<center>
	<form method='post' action='<?= $_SERVER['REQUEST_URI']; ?>'>
	   
	   <span style="background: #4CAF50;color: #fff;padding: 1px;max-width: 100px;radius: 12px;border-radius: 3px;margin-bottom: 5px;line-height: 2;">
	     <?php if($value->state == "register"){ echo "Ready To Start <br/>";  }  ?>
		 
		 <?php if($value->state != "register"){ echo "Current State is " . $value->state . "<br/>";  }  ?>
	   </span>
	   
	   <input type="hidden" name="cancle_process_id" value="<?php echo $value->id; ?>" />
	   <input type="hidden" name="start_process_id" value="<?php echo $value->id; ?>" />
	   
	   <span>
	      
		  <?php if($value->state == "started"){ echo "Process Start";  }  ?>
		  <?php if($value->state == "canceled"){ echo "Process Cancel";  }  ?>
	   
	   </span>
	   <input type="submit" name="process_submit_start" value="Start" />
	   <input type="submit" name="process_submit_cancel" value="Cancel" />
	  
	</form>
	</center>
	</td>
	<td> <?php echo $value->process_id; ?> </td>
	<td> <?php echo $value->template_name; ?> </td>
	<td> <?php echo $value->activity_date; ?> </td>
</tr>


<?php
  }
  
  
  if(isset($_REQUEST['start_process_id'])){
	  echo $_REQUEST['start_process_id'];
  }
  
  
  
  
?>
</table>