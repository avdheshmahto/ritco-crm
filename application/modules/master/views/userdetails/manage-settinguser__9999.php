<div id="ajax_content">
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
<input type="text" class="input-sm form-control" id="searchTerm" onkeyup="doSearch();" placeholder="Search...">

</div>
</div>
</div>
<div class="btn-toolbar">
<div class="btn-group mr-10">
<h3 class="custom-font">User Setting</h3>
</div>
</div>
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
<button class="btn btn-primary" data-toggle="modal" data-target="#myModal" formid="#UserForm" id="formreset" style="padding: 4px;">Add New User</button>
</div>  
<div class="btn-group">
<button class="btn btn-danger btn-sm delete_all" style="display: none;"> Delete All </button>
</div>    
</div>

<div class="btn-toolbar">
<div class="btn-group mr-10">
<h3 class="custom-font">User List</h3>
</div>

<div id="chkmail" class="mail-alert">
  <font color="#0033FF" style="display:marker"><b><?php echo $this->session->flashdata('msg');?></b> </font>
</div>

</div>
</div>
</div>

<div class="tile-body p-0">
<div class="table-responsive table-overflow">
<table class="table mb-0" id="usersList">
<thead>
<tr>
  <th> 
    <label class="checkbox checkbox-custom-alt checkbox-custom-sm m-0">
       <input type="checkbox" id="check_all" onClick="checkall(this.checked)" value="check_all">
      <i></i>
     </label>
  </th>
  <th>SL No.</th>
  <th>Name</th>
  <th>Profile</th>
  <th>Email</th>
  <th>Last Login</th>
  <th>Branch</th>
  <th></th>
  <th style="text-align: right;">Action</th>
  
</tr>
</thead>
    <tbody id="dataTable">
       <?php
       if(sizeof($result) > 0){
        $i = 1;
        foreach($result as $dt){ ?>
        <tr class="record" data-row-id="<?=$dt->user_id; ?>">
        <td>
          <label class="checkbox checkbox-custom-alt checkbox-custom-sm m-0">
              <input name="cid[]" type="checkbox" id="cid[]" class="sub_chk" data-id="<?=$dt->user_id; ?>" value=" <?=$dt->user_id;?>">
              <i></i>
          </label>
        </td>
        <th><?php echo $i; ?></th>
        <td><a href="#" onclick="getviewsettingpage('<?=$dt->user_id;?>');">
          <?=$dt->user_name;?></a></td>

        <td><?php 
        $proper=$this->db->query("select distinct(profile_name),profile_id from tbl_profile_mst where profile_id='$dt->profile_user' ");
        $getPname=$proper->row();
        echo $getPname->profile_name;
        ?></td>
        <!-- <td><?=$dt->logged_in==1?'<i class="fa fa-fw fa-circle text-greensea" title="This User Logged In!"></i>':'';?><?=$dt->email_id;?></td> -->
        <td><?=$dt->email_id;?></td>
        <td><?=$dt->last_login;?></td>
        <td><?php 
          $brnch = "select * from tbl_branch_mst where brnh_id='".$dt->brnh_id."' ";
          $getBrnch = $this->db->query($brnch)->row();
          echo $getBrnch->brnh_name;
        ?>
        </td>
        <?php if($dt->emailvarified == 1){ ?>
        <td>
          <div class="onoffswitch greensea">
            <input type="checkbox" name="onoffswitch" class="onoffswitch-checkbox" onchange="changeswitch(this,'<?=$dt->user_id;?>');" id="switch-on<?=$i;?>" <?=$dt->status=='A'?'checked':'';?>>
             <label class="onoffswitch-label" for="switch-on<?=$i;?>" >
              <span class="onoffswitch-inner"></span>
              <span class="onoffswitch-switch"></span>
             </label>
          </div>
        </td>
      <?php } else {?>
        <td></td>
      <?php } ?>
      <td>

        <?php 
        $oldids=$this->db->query("select * from tbl_software_log where old_id='$dt->user_id' ");
        $oldNumRow=$oldids->num_rows();
        $newids=$this->db->query("select * from tbl_software_log where new_id='$dt->user_id' ");
        $newNumRow=$newids->num_rows();

        $num_rowss=$oldNumRow + $newNumRow; ?>

        <div class="btn-group pull-right">
          <a href="#" class=" dropdown-toggle-" title="Actions" data-toggle="dropdown" aria-expanded="false"><i class="fa fa-ellipsis-h"></i></a>
           <ul class="dropdown-menu" role="menu">
            <li><a href="#" onclick="editUser(this);" property = "edit" type="button" data-toggle="modal" data-target="#myModal" arrt= '<?=json_encode($dt);?>'  data-backdrop='static' data-keyboard='false'><i class="fa fa-edit"></i> Edit This User</a></li>
            <?php if($dt->emailvarified == 0){ ?>
            <li><a href="#" onclick="MailResend('<?=$dt->user_id;?>');"><i class="fa fa-envelope"></i>Resend Mail</a></li>
            <?php } ?>
           <?php if($num_rowss > 0) { ?>
            <li><a href="#" onclick="return confirm('User already map. You can not delete ?');" ><i class="fa fa-trash"></i> Delete This User </a></li>
          <?php } else { ?>
            <li class="delbutton_user"  id="<?=$dt->user_id;?>^tbl_user_mst^user_id"> 
              <a href="#"><i class="fa fa-trash"></i> Delete This User</a>
            </li>
          <?php } ?>

            <?php if($this->session->userdata('role')==1) {?>
              <li><a href="#" onclick="editPassword('<?=$dt->user_id;?>');" data-toggle="modal" data-target="#passwordModal" arrt= '<?=json_encode($dt);?>' ><i class="fa fa-key"></i>Change Password</a></li>
            <?php } ?>
           </ul>
      </div>
      </td>
      </tr>
    <?php $i++; } } ?>
    </tbody>
</table>
</div>
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
</div>




<!-- Change Password Modal -->

<div class="modal fade" id="passwordModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
<div class="modal-dialog modal-lg" role="document">
<div class="modal-content">
 <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
     <h4 class="modal-title" id="myModalLabel">Change Password</h4>
     <div id="resultpassword" style="font-size: 15px;color: red; text-align:center"></div> 
  </div>
   <form  id="ChangePasswordForm"> 
     <div class="modal-body">
      <div class="panel-group icon-plus" id="accordion" role="tablist" aria-multiselectable="true">
      <div class="panel panel-default panel-transparent">
          
       <div class="panel-body">
        <div class="row">
          <div class="form-group col-md-6">
            <label for="name">New Password : </label>
            <input type="password" name="usr_new_pass" id="usr_new_pass" class="form-control" required="" placeholder="New Password">
            <input type="hidden" name="passuser_id" id="passuser_id">
          </div>
          <div class="form-group col-md-6">
           <label for="lname">Confirm Password : </label>
           <input type="password" name="usr_cnf_pass" id="usr_cnf_pass" class="form-control" required="" placeholder="Confirm Password">
          </div>
          </div>
       </div>

     </div><!--panel close-->
    </div>
  </div>

  <div class="modal-footer">
    <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
    <button type="button" id="saveUser"class="btn btn-primary" onclick="chkpassword(this);">Save</button>
    <span id="saveload" style="display: none;">
       <img src="<?=base_url('assets/loadgif.gif');?>" alt="HTML5 Icon" width="54.63" height="40">
    </span>
  </div>
  </form>
</div>
</div>
</div>

<!-- Modal close-->

<input type="text" style="display:none;" id="table_name" value="tbl_user_mst">  
<input type="text" style="display:none;" id="pri_col" value="user_id">



<script type="text/javascript">

function MailResend(usr)
{

  var ur = "ResentMail";
  //alert(usr)
   $.ajax({

     url:ur,
     type:"POST",
     data:{'uid':usr},
     success:function(data){
         
            if(data == 1){
              //alert(data);
              $('#chkmail').html('Mail Sent!');
            }

     }
   });
  }

  function editPassword(v){

    $('#passuser_id').val(v);

  }

  function chkpassword(v)
  {
    var newpass = $('#usr_new_pass').val();
    var cnfpass = $('#usr_cnf_pass').val();
    //alert(cnfpass);
    if(newpass == cnfpass)
    {
      v.type="submit";
    }
    else
    {
      alert("New Password and Confirm Password Not Match");
    }
  }
</script>