<?php $this->load->view('header.php'); ?>


<?php
$query = $this->db->query("select * from tbl_user_mst where status='A' AND user_id = '".$this->session->userdata('user_id')."' ");
$getUser = $query->row();

$username = $getUser->user_name;
$email_id = $getUser->email_id;
$role     = $getUser->role;
$last_login = $getUser->last_login;
$maker_date = $getUser->maker_date;
?>


<section id="content">
<div class="page page-tables-bootstrap">
<div class="row">
<div class="col-md-12">
<section class="tile">
<div class="pageheader">
<div class="p-0 bg-white-">
<div class="btn-toolbar pull-right">
<div class="btn-group">
<div class="input-group">

</div>
</div>
</div>
<!-- <div class="btn-toolbar">
<div class="btn-group mr-10">
<h3 class="custom-font">User Setting</h3>
</div>
</div> -->
</div>
</div><!--pageheader close-->

<div class="tile-body p-15">
<div class="row">
<div class="col-sm-2">
<div class="sidebar-nav-">
<div class="navbar navbar-default" role="navigation">
<div class="navbar-header">
<button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".sidebar-navbar-collapse"> <span class="sr-only">Toggle navigation</span> <span class="icon-bar"></span> <span class="icon-bar"></span> <span class="icon-bar"></span> </button>
<span class="visible-xs navbar-brand">Data Usere</span> </div>
<div class="navbar-collapse collapse sidebar-navbar-collapse">
<?php $this->load->view("main_nav.php"); ?>
</div>
</div>
</div>
</div><!--col-sm-10 close-->

<div class="col-sm-10">
<section class="tile" style="top:0px;">
<div class="tile-bg-">
<div class="p-15 tile-bg b-b">
<div class="btn-toolbar pull-right">
<div class="btn-group">

</div>
</div>
<h3 class="custom-font m-0 mr-5 inline-block">Profile Details</h3>
</div>

</div>

<div class="tile-body p-0">
<!-- <form class="form-horizontal"  id="Profileform"  > -->
<form name="myForm" class="form-horizontal" id ="myform" action="#" 
        onsubmit="return submitForm();"method="POST" enctype="multipart/form-datam">
<div class="modal-body">
<div class="panel-group icon-plus" id="accordion" role="tablist" aria-multiselectable="true">
<div>
<article class="page-content">
<section class="block bottom20">
<!-- <header class="head-title">
<h2>PROFILE DETAILS</h2>
</header> -->

<div class="entity-detail">

<div class="form-group">
<label for="inputEmail3" class="col-sm-4 control-label">Profile Name</label>
<div class="col-sm-4">
<input type="text" class="form-control" name="profile_name" id="profile_name" onkeyup="chkprofilename(this.value);">
 <?php 
$getval = $this->db->query("select * from tbl_profile_mst where id !='' and status = 'A' order by id desc limit 0,1");
$getproval = $getval->row();
  ?>
<input type="hidden" name="profile_id" id="profile_id" value="<?php echo $getproval->profile_id+1; ?>">  
</div>
</div>





</div>
</section>
</article>

<table class="table table-hover">
<thead>
<tr>
<th>&nbsp;</th>
<th colspan="4"><center>Permissions</center></th>
</tr>
</thead>
<tbody>
<tr>
<th>&nbsp;</th>
<th>Read</th>
<th>Create</th>
<th>Edit</th>
<th>Delete</th>
</tr>
<?php

$moduleQuery=$this->db->query("select * from tbl_module_mst where status='A' ORDER BY module_id ASC");
foreach($moduleQuery->result() as $moduleget){

?>
<tr>


<td><input type="hidden" checked="" name="profilek[]" id="profilek" value="<?php echo $moduleget->module_id;?>"><?php echo $moduleget->module_name;?></td>
<td>
<div class="checkbox tiny">
<input type="checkbox" disabled="disabled" checked="checked" value="" id="read_id<?=$moduleget->module_id;?>" onclick="checkboxsel(this.id);">
<input type="hidden" name="read_id[]" id="read_idhide<?=$moduleget->module_id;?>" value="1">
<div class="checkbox-container">
<div class="checkbox-checkmark"></div>
</div>
</div>
</td>
<td>

<div class="checkbox tiny">
<input type="checkbox" checked="checked"  id="create_id<?=$moduleget->module_id;?>" onclick="checkboxselcreate(this.id);">
<input type="hidden" name="create_id[]" id="create_idhide<?=$moduleget->module_id;?>" value="1">
<div class="checkbox-container">
<div class="checkbox-checkmark"></div>
</div>
</div>
</td>
<td>

<div class="checkbox tiny">
<input type="checkbox" disabled="disabled" checked="checked" id="edit_id<?=$moduleget->module_id;?>" onclick="checkboxseledit(this.id);">
<input type="hidden" name="edit_id[]" id="edit_idhide<?=$moduleget->module_id;?>" value="1">
<div class="checkbox-container">
<div class="checkbox-checkmark"></div>
</div>
</div>
</td>
<td>

<div class="checkbox tiny">
<input type="checkbox"  id="delete_id<?=$moduleget->module_id;?>" checked="checked" onclick="checkboxseldelete(this.id);">
<input type="hidden" name="delete_id[]" id="delete_idhide<?=$moduleget->module_id;?>" value="1">
<div class="checkbox-container">
<div class="checkbox-checkmark"></div>
</div>
</div>
</td>
<!-- <td><input type="text" name="checktest[]" id="checktest<?=$moduleget->module_id;?>" value="0" ></td>
 --></tr>
<?php } ?>

</tbody>
</table>
</div><!--close-->
</div>
</div>

<div class="tile-footer text-right bg-tr-black lter dvd dvd-top">
 
<a href="<?=base_url();?>master/System/manage_profile"><button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button></a>
<button type="submit" id="formsave" class="btn btn-primary" onclick="checkboxval();">Save</button>
<!-- <button type="submit" id="formsave"  class="btn btn-primary"">Save</button> -->
</div>

</form>


</div>
</section>
</div><!--col-sm-9 close-->

</div><!--row close-->
</div><!--tile-body p-0 close-->
</section>
</div>
</div>
</div>
</section>

<?php $this->load->view('footer.php'); ?>

<script type="text/javascript">
	

	function submitForm() {
   //alert();
  var form_data = new FormData(document.getElementById("myform"));
  form_data.append("label", "WEBUPLOAD");
  
 
  //var spare_name_schedd=document.getElementById("spare_name_schedd").value;
  //alert(spare_name_schedd);
  
  
  $.ajax({
      url: "<?=base_url();?>master/System/save_profile",
      type: "POST",
      data: form_data,
      processData: false,  // tell jQuery not to process the data
      contentType: false   // tell jQuery not to set contentType
  }).done(function( data ) {
	//alert(data);
    //console.log(data);
	window.location.href="manage_profile";
	 if(data == 1 || data == 2){
	  if(data == 1)
		var msg = "Data Successfully Add !";
	  else
		var msg = "Data Successfully Updated !";
	
	
	}else{
	$("#resultarea56").text(data);
	}
    //Perform ANy action after successfuly post data
      
  });
 // ajex_spare_Data(); 
  return false;     
}
</script>
<script type="text/javascript">
  
function checkboxsel(v){
var zz=document.getElementById(v).id;

    var myarra = zz.split("read_id");

    var asx= myarra[1];
    var pri=document.getElementById("read_id"+asx).value;

//document.getElementById("read_idhide"+asx).value=pri;
 var a=document.getElementById("read_id"+asx).checked; 
if (a== true) {
  //alert("a");
  document.getElementById("read_idhide"+asx).value=1;
   //document.getElementById("edit_id"+asx).disabled=false;
}else{
//alert("fhfj");
document.getElementById("read_idhide"+asx).value=0;
}
}


  
function checkboxselcreate(v){
var zz=document.getElementById(v).id;

    var myarra = zz.split("create_id");

    var asx= myarra[1];
    var pri=document.getElementById("create_id"+asx).value;

//document.getElementById("read_idhide"+asx).value=pri;
 var a=document.getElementById("create_id"+asx).checked; 
if (a== true) {
  //alert("a");
  document.getElementById("create_idhide"+asx).value=1;
 document.getElementById("read_id"+asx).disabled=true;
  document.getElementById("read_id"+asx).checked=true;
  document.getElementById("read_idhide"+asx).value=1;
}else{
//alert("fhfj");
document.getElementById("create_idhide"+asx).value=0;
//document.getElementById("read_id"+asx).disabled=true;
//document.getElementById("read_id"+asx).disabled=false;
//document.getElementById("read_idhide"+asx).value=0;
var editidhd=document.getElementById("edit_idhide"+asx).value;
if(editidhd==0){
document.getElementById("read_id"+asx).disabled=false;
}
//readidval(this.id);

}
}  


function checkboxseledit(v){
var zz=document.getElementById(v).id;

    var myarra = zz.split("edit_id");

    var asx= myarra[1];
    var pri=document.getElementById("edit_id"+asx).value;

//document.getElementById("read_idhide"+asx).value=pri;
 var a=document.getElementById("edit_id"+asx).checked; 
 
  
if (a== true) {
  //alert("a");
  document.getElementById("edit_idhide"+asx).value=1;
  document.getElementById("read_id"+asx).disabled=true;
  document.getElementById("read_id"+asx).checked=true;
  document.getElementById("read_idhide"+asx).value=1;
}else{
//alert("fhfj");
document.getElementById("edit_idhide"+asx).value=0;
//readidval(this.id);
var readidhd=document.getElementById("create_idhide"+asx).value;
if(readidhd==0){
document.getElementById("read_id"+asx).disabled=false;
}


}
}




function checkboxseldelete(v){
var zz=document.getElementById(v).id;

    var myarra = zz.split("delete_id");

    var asx= myarra[1];
    var pri=document.getElementById("delete_id"+asx).value;

//document.getElementById("read_idhide"+asx).value=pri;
 var a=document.getElementById("delete_id"+asx).checked; 
if (a== true) {
  //alert("a");
  document.getElementById("delete_idhide"+asx).value=1;
  document.getElementById("edit_id"+asx).checked=true;
  document.getElementById("edit_idhide"+asx).value=1;
  document.getElementById("edit_id"+asx).disabled=true;
  var editkidhd=document.getElementById("edit_idhide"+asx).value;

if(editkidhd==1){
document.getElementById("read_id"+asx).disabled=true;
 document.getElementById("read_id"+asx).checked=true;
  document.getElementById("read_idhide"+asx).value=1;
}

}else{
//alert("fhfj");
document.getElementById("delete_idhide"+asx).value=0;
document.getElementById("edit_id"+asx).disabled=false;


}
}

/*function checkboxval(){
//alert("kkkkk");
var a= document.getElementsByName("delete_id").value;
var b= document.getElementById("create_idhide").value;
var c= document.getElementById("edit_idhide").value;
var d= document.getElementById("delete_idhide").value;

alert("djd");

if(a =='1'|| b =='1' || c =='1' || d =='1'){

document.getElementById("checktest").value=1;

}else{

  document.getElementById("checktest").value=0;

}

}*/

function chkprofilename(value)
{
  //alert(value);
  $.ajax({
      url: "chk_profile_name",
      type: "POST",
      data: {'id':value},
    success:function( data ){
    console.log(data);
    if(data == 1)
    {
      alert("Sorry. This Prifile Name Already Exist!");
      $('#formsave').attr('disabled',true);
    }      
    else
    {
      //alert("Sorry. This E-mail already exist");
     $('#formsave').attr('disabled',false);
    }     
  }
});
}
</script>