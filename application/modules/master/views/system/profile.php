<?php $this->load->view('header.php'); ?>

<form class="form-horizontal" name="myformprofile" id="myformprofile"  action="<?=base_url('master/System/save_profile');?>" method="POST"> 
<div class="main-content">

<div id="editItem" class="modal fade modal" role="dialog">
<div class="modal-dialog modal-lg">
<div class="modal-contentitem" id="modal-contentitem">

</div>
</div>
</div>  

</div>
</form>

<div class="main-content">
<div id="assignuser" class="modal fade modal" role="dialog">
<div class="modal-dialog modal-lg">


<form name="myformuser" class="form-horizontal" id ="myformuser" action="<?=base_url('master/System/save_user');?>" method="POST" >
<div class="modal-content">
<div class="modal-header">
<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">x</span></button>
<h4 class="modal-title" id="myModalLabel"><span class="top_title">Assign</span> Users</h4>
<div id="resultarea" style="font-size: 15px;color: red; text-align:center"></div> 
</div>
<div class="modal-body">

<div class="form-group">
<?php

$profileQuery1=$this->db->query("select * from tbl_profile_mst where profile_id='$id'");
$fetch_list1=$profileQuery1->row();     
//echo $fetch_list1->profile_name;
?>
<div class="row">
<div class="col-md-12">
</div>
</div>
<input type="hidden" name="profile_id" id="profile_id" >
</div>
<div class="form-group">
<label for="inputEmail3" class="col-sm-4 control-label">Assign Users</label>
<div class="col-sm-4">
<input type="hidden" name="assign_user_id" id="assign_user_id" value="">
<select name="userassign[]" required  id="userassign" class="chosen-select" style="width: 240px;" multiple="">
<!-- <option value="" >----Select----</option> -->
<?php 
$sqlunit=$this->db->query("select * from tbl_user_mst where profile_user=''");
foreach ($sqlunit->result() as $fetchunit){
?>
<option value="<?php echo $fetchunit->user_id;?>"><?php echo $fetchunit->user_name; ?></option>
<?php } ?>
</select>  


</div>
</div>



<!--form-horizontal close-->
</div>
<div class="modal-footer">

<button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>

<button type="submit" id="formsaveuser" class="btn btn-primary">Save</button>

<span id="saveload" style="display: none;">

</span>
</div>
</div><!--modal-content close-->
</form>




</div>
</div>
</div>  


<div class="modal-contentitem1" id="modal-contentitem1">
<section id="content">
<div class="page page-tables-bootstrap">
<div class="row">
<div class="col-md-12">
<section class="tile">
<div class="pageheader tile-bg">
<div class="p-0 bg-white-">
<div class="btn-toolbar pull-right">
<div class="btn-group">
<div class="input-group">

</div>
</div>
</div>
<div class="btn-toolbar">
<div class="btn-group mr-10">
<h3 class="custom-font">User Settings <!-- <i class="fa fa-angle-right"></i> Profile --></h3>
</div>
</div>
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
<div class="tile-bg">
<div class="p-0 bg-white-">
<div class="btn-toolbar pull-right">
<div class="btn-group">
<a href="<?=base_url();?>master/System/manage_role1" class="btn btn-danger btn-sm">Add New Profile</a>
</div>
</div>
<div class="btn-toolbar">
<div class="btn-group mr-10" style="padding-top:10px;">
<strong>Profile</strong>
</div>
</div>
</div>

</div>

<div class="tile-body p-0">
<div class="table-responsive">
<table class="table mb-0" id="usersList">
<thead>
<tr>
<th>Profile Name</th>
<th>Assigned Users</th>
<th>Custom Profiles</th>
<th></th>
<th>Action</th>

</tr>
</thead>
<tbody id="dataTable">
<?php 
$ardprof = $this->db->query("select distinct(profile_name),profile_id from tbl_profile_mst where status='A' order by profile_id desc  ");
//echo "select * from tbl_profile_mst where status='A'";
foreach($ardprof->result() as $profard) {
$queryuser = $this->db->query("select * from tbl_user_mst where status='A' AND profile_user='$profard->profile_id' ");
$getUsercount = $queryuser->num_rows();
?>
<tr class="record">
<td><?=$profard->profile_name; ?></td>
<td><?=$getUsercount;?></td>
<td><?php if($profard->profile_name == 'Admin'){?>No<?php }else{?>Yes<?php } ?></td>
<td></td>

<td>
<?php 
$usrPrfle=$this->db->query("select * from tbl_user_mst where profile_user='$profard->profile_id' ");
$getUsrPrfl=$usrPrfle->num_rows();

$num_rowss=$getUsrPrfl;

?>
<div class="btn-group">
<div class="btn-group pull-right">
<a href="#" class=" dropdown-toggle-" title="Actions" data-toggle="dropdown" aria-expanded="false"><i class="fa fa-ellipsis-h"></i></a>
<ul class="dropdown-menu" role="menu">
<?php if($profard->profile_name=='Admin'){ ?> 
<li>
<a class="modalEditItem" data-a="<?php echo $profard->profile_id;?>" href='#editItem' onclick="getEditItem('<?php echo $profard->profile_id;?>','edit')" type="button" data-toggle="modal" data-backdrop='static' data-keyboard='false'><i class="fa fa-edit"></i> View This Profile</a>  
</li>
<?php }else{ ?>
<li>
<a class="modalEditItem" data-a="<?php echo $profard->profile_id;?>" href='#editItem' onclick="getEditItem('<?php echo $profard->profile_id;?>','edit')" type="button" data-toggle="modal" data-backdrop='static' data-keyboard='false'><i class="fa fa-edit"></i> Edit This Profile</a>  
</li>

<?php if($num_rowss > 0) { ?>
<li><a href="#" onclick="return confirm('Profile already map. You can not delete ?');" ><i class="fa fa-trash"></i> Delete This Profile </a></li>
<?php } else { ?>
<li class="delbutton"  id="<?=$profard->profile_id;;?>^tbl_profile_mst^profile_id"> 
<a href="#"><i class="fa fa-trash"></i> Delete This Profile</a>
<?php } ?>
<li>
<!-- <a class="modalMapSpare"   onclick="getassignuser(this);"   arrt= '<?=json_encode($profard);?>' data-target="#assignuser" data-toggle="modal" data-backdrop='static' data-keyboard='false'  id="formreset" ><i class="fa fa-user"></i>Assign Users</a> -->
</li>
<?php } ?>
</ul>
</div>

</tr>
<?php  }  ?>
</tbody>
</table>




</div>
</div>
</div>
</div>
</div>
</div><!--media close-->
</div>
</div><!-- post-comments -->


</section>
</div>




<?php $this->load->view('footer.php'); ?>



<script type="text/javascript">

function getEditItem(v,button_type){
var pro=v;
//alert(pro);
var xhttp = new XMLHttpRequest();
xhttp.open("GET", "manage_profile_edit?ID="+pro+"&type="+button_type, false);
xhttp.send();

document.getElementById("modal-contentitem").innerHTML = xhttp.responseText;
} 


//**********************************************************************************************

function getassignuser(ths) {
//console.log('edit ready !');
$('.error').css('display','none');
var rowValue = $(ths).attr('arrt');

var button_property = $(ths).attr('property');
console.log(rowValue);
if(rowValue !== undefined)
var editVal = JSON.parse(rowValue);
//alert(editVal.trigger_code);
$('#profile_id').val(editVal.profile_id);
$('#profile_name').val(editVal.profile_name);


};


//*******************************************************************************************************
</script>

<script type="text/javascript">


function submitForm() {
//alert("dhdh");
var form_data = new FormData(document.getElementById("myformprofile"));
//alert(form_data);
form_data.append("label", "WEBUPLOAD");


$.ajax({
url: "<?=base_url();?>master/System/save_profile",
type: "POST",
data: form_data,
processData: false,  // tell jQuery not to process the data
contentType: false   // tell jQuery not to set contentType
}).done(function( data ) {
//alert(data);
//console.log(data);

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


function submitFormuser() {
//alert("dhdh");
var form_data = new FormData(document.getElementById("myformuser"));
//alert(form_data);
form_data.append("label", "WEBUPLOAD");



$.ajax({
url: "<?=base_url();?>master/System/save_user",
type: "POST",
data: form_data,
processData: false,  // tell jQuery not to process the data
contentType: false   // tell jQuery not to set contentType
}).done(function( data ) {
// alert(data);
//console.log(data);

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
      alert("Sorry. This Profile Name Already Exist!");
      $('#addprofile').attr('disabled',true);
    }      
    else
    {
      //alert("Sorry. This E-mail already exist");
     $('#addprofile').attr('disabled',false);
    }     
  }
});
}
</script>
</script>


