
<?php
$username   = "";$last_login = "";
$email_id   = "";$role       = "";
$logged_in  = "";$user_id    = "";

if($result != ""){
    // print_r($result);
    $user_id    = $result->user_id;
    $username   = $result->user_name;
    $email_id   = $result->email_id;
    $role       = $result->role;
    $branch     = $result->brnh_id;
    $profile    = $result->profile_user;
    $last_login = $result->last_login;
    $maker_date = $result->maker_date;
    $logged_in  = $result->logged_in;
}

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
<div class="navbar-collapse collapse sidebar-navbar-collapse">
<?php $this->load->view("main_nav.php"); ?>
</div>
</div>
</div>
</div><!--col-sm-10 close-->

<div class="col-sm-10">
<section class="tile" style="top:0px;">
<div class="pageheader">
<div class="p-0 bg-white-">
<div class="btn-toolbar pull-right">
<div class="btn-group">
<button class="btn btn-sm" id="backbutton" onclick="getsettingpage();" style="margin: 0 10px 0px 0px;"><i class="fa fa-arrow-circle-left" aria-hidden="true"></i> Back</button>
</div>
<!-- <div class="btn-group">
<button class="btn btn-danger btn-sm" id="backbutton" onclick="getEditpage(<?=$user_id;?>);"><i class="fa fa fa-edit" aria-hidden="true"></i> Edit User</button>
</div> --> 
</div>

<div class="btn-toolbar">
<div class="btn-group mr-10">
<h3 class="custom-font" style="margin: 0px 0px 0px 10px;">User View Details</h3>
</div>
</div>
</div>
</div>
<!-- /tile widget -->
<!-- tile body -->
<form  id="UserForm" > 
<div class="modal-body">
<div class="panel-group icon-plus" id="accordion" role="tablist" aria-multiselectable="true">
<div >
<article class="page-content">
<section class="block bottom20">
<header class="head-title">
  <h2>User Details</h2>
</header>
<div class="profile-photo-create-edit" style="float: right; margin-right: 0px; margin-top: -5px;">
  <img width="50" height="50" src="../../../img/placeholder-contact.png" alt="sam kronick">
</div>
<div class="entity-detail">
<table class="property-table">
    <tbody id="dataTable">
    <tr>
        <td class="ralign"><span class="title">User Name</span></td>
        <td>
            <div class="info"><?=$username;?></div>
        </td>
    </tr>
    <tr>
        <td class="ralign"><span class="title">User Email Address</span></td>
        <td>
            <div class="info"><?=$email_id;?></div>
        </td>
    </tr>
       <!--  <tr>
            <td class="ralign"><span class="title">
              <?php if($role==1){ echo 'Administrator';}
                    if($role==2){ echo 'Manager';}
                    if($role==3){ echo 'User';}
                    ?></span></td>
            <td>
                <div class="info">
                    <img src="../../../img/checked.png">
                </div>
            </td>
        </tr> --> 
        <tr>
        <td class="ralign"><span class="title">Profile Name</span></td>
        <td>
            <?php 
                  $sqll   = $this->db->query("select distinct(profile_name),profile_id from tbl_profile_mst where profile_id = '$profile' and status = 'A' ");
                $qttt = $sqll->row();

            ?>
            <div class="info"><?=$qttt->profile_name;?></div>
        </td>
    </tr>
        <tr>
        <td class="ralign"><span class="title">Branch Name</span></td>
        <td>
            <?php 
                  $sql   = $this->db->query("select * from tbl_branch_mst where brnh_id = '$branch' and status = 'A'");
                $qtt = $sql->row();

            ?>
            <div class="info"><?=$qtt->brnh_name;?></div>
        </td>
    </tr>
    </tbody>
</table>
</div>
</section>
<section class="block bottom20">
<header class="head-title">
<h2>Additional Information</h2>
</header>
<div class="entity-detail">
<table class="property-table">
    <tbody>
    <tr>
        <td class="ralign"><span class="title">Date of Last Login</span></td>
        <td>
            <div class="info"><?=$last_login;?></div>
        </td>
    </tr>
    <tr>
        <td class="ralign"><span class="title">Date Created</span></td>
        <td>
            <div class="info"><?=$maker_date;?></div>
        </td>
    </tr>

    </tbody>
</table>
</div>
</section>
<section class="block bottom20">
<header class="head-title">
<h2>Profile Permissions</h2>
</header>
<div class="entity-detail">
<table class="table table-hover">
<thead>
<tr>
<th>&nbsp;</th>
<th>Read</th>
<th>Create</th>
<th>Edit</th>
<th>Delete</th>
</tr>
</thead>
<tbody>
<?php
 $profileQuery=$this->db->query("select * from tbl_profile_mst where profile_id='$profile' ORDER BY module_id ASC");
 foreach($profileQuery->result() as $fetch_list){

   $moduleQuery=$this->db->query("select * from tbl_module_mst where module_id ='$fetch_list->module_id' and status='A' ORDER BY module_id ASC ");
   //foreach($moduleQuery->result() as $moduleget){
   $fetch_list23=$moduleQuery->row();
?>
<tr>


<td><input type="hidden" checked="" name="profilek[]" id="profilek" value="<?php echo $fetch_list23->module_id;?>"><?php echo $fetch_list23->module_name;?></td>
<td>
<div class="checkbox tiny">
<input type="checkbox"  <?php if($fetch_list->read_id=='1' and $fetch_list->module_id==$fetch_list23->module_id ){ ?> checked="checked" <?php }?> value="" id="read_id<?=$fetch_list23->module_id;?>" disabled="disabled" onclick="checkboxsel(this.id);">
<input type="hidden" name="read_id[]" id="read_idhide<?=$fetch_list23->module_id;?>" value="<?php echo $fetch_list->read_id; ?>">
<div class="checkbox-container">
<div class="checkbox-checkmark"></div>
</div>
</div>
</td>
<td>

<div class="checkbox tiny">
<input type="checkbox" <?php if($fetch_list->create_id=='1' and $fetch_list->module_id==$fetch_list23->module_id ){ ?> checked="checked" <?php }?>  id="create_id<?=$fetch_list23->module_id;?>" disabled="disabled" onclick="checkboxselcreate(this.id);">
<input type="hidden" name="create_id[]" id="create_idhide<?=$fetch_list23->module_id;?>" value="<?php echo $fetch_list->create_id; ?>">
<div class="checkbox-container">
<div class="checkbox-checkmark"></div>
</div>
</div>
</td>
<td>


<div class="checkbox tiny">
<input type="checkbox" <?php if($fetch_list->edit_id=='1' and $fetch_list->module_id==$fetch_list23->module_id ){ ?> checked="checked" <?php }?>  id="edit_id<?=$fetch_list23->module_id;?>" disabled="disabled" onclick="checkboxseledit(this.id);">
<input type="hidden" name="edit_id[]" id="edit_idhide<?=$fetch_list23->module_id;?>" value="<?php echo $fetch_list->edit_id; ?>">
<div class="checkbox-container">
<div class="checkbox-checkmark"></div>
</div>
</div>
</td>
<td>



<div class="checkbox tiny">
<input type="checkbox" <?php if($fetch_list->delete_id=='1' and $fetch_list->module_id==$fetch_list23->module_id ){ ?> checked="checked" <?php }?> id="delete_id<?=$fetch_list23->module_id;?>" disabled="disabled" onclick="checkboxseldelete(this.id);">
<input type="hidden" name="delete_id[]" id="delete_idhide<?=$fetch_list23->module_id;?>" value="<?php echo $fetch_list->delete_id; ?>">
<div class="checkbox-container">
<div class="checkbox-checkmark"></div>
</div>
</div>
</td>
<!-- <td><input type="text" name="checktest[]" id="checktest<?=$moduleget->module_id;?>" value="0" ></td>
 --></tr>
<?php  } ?>

</tbody>
</table></div>
</section>
</article>

</div>
</div>
</div>

</form>
<!-- /tile body -->

</section>
</div><!--col-sm-9 close-->

</div><!--row close-->
</div><!--tile-body p-0 close-->
</section>
</div>
</div>
</div>

</section>
          