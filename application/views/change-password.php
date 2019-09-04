<?php $this->load->view('header.php'); ?>

<?php
$query = $this->db->query("select * from tbl_user_mst where status='A' AND user_id = '".$this->session->userdata('user_id')."' ");
$result = $query->row();

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
<div class="row">
<div class="col-sm-6">
<strong>Change Password</strong>
</div>
</div>
</div>

<div class="tile-body p-0">
<div class="modal-body">
<div class="panel-group icon-plus" id="accordion" role="tablist" aria-multiselectable="true">
<div>
<article class="page-content">
<section class="block bottom20">
<header class="head-title">
  <h2>User Profile</h2>
</header>
<div class="profile-photo-create-edit" style="float: right; margin-right: 0px; margin-top: -5px;">
  <img width="50" height="50" src="<?=base_url();?>img/placeholder-contact.png" alt="sam kronick">
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
              <?php //if($role==1){ echo 'Administrator';}
                    //if($role==2){ echo 'Manager';}
                    //if($role==3){ echo 'User';}
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
<h2>Change Information</h2>
</header>
<div class="entity-detail">
<form method="post" action="insertnewpassword">
<table class="property-table">
    <tbody>
    <tr>
        <td class="ralign"><span class="title">Old Password : </span></td>
        <td>
            <div class="info"><input type="password" class="input-sm form-control" name="old_password" id="old_password" required=""></div>
        </td>
    </tr>
<div>
  <font color="#0033FF" style="display:marker"><b><?php echo $this->session->flashdata('errormsg');?></b> </font>
</div>
    <tr>
        <td class="ralign"><span class="title">New Password : </span></td>
        <td>
            <div class="info"><input type="password" class="input-sm form-control" name="new_password" id="new_password" required=""></div>
        </td>
    </tr>
    <tr>
        <td class="ralign"><span class="title">Confirm Password : </span></td>
        <td>
            <div class="info"><input type="password" class="input-sm form-control" name="cnf_password" id="cnf_password" required=""></div>
        </td>
    </tr>
    <tr>
        <td class="ralign"><span class="title"></span></td>
        <td>
            <div class="info"><button type="button" class="btn btn-primary" onclick="checkpassword(this)" style="margin: 15px 0px 0px 40px;">Save</button></div>
        </td>
    </tr>
    </tbody>
</table>
</form>
</div>
</section>
</article>

</div>

</div>
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

<?php $this->load->view('footer.php'); ?>

<script type="text/javascript">

function checkpassword(v)
{
    var newpass=document.getElementById('new_password').value;
    var cnfpass=document.getElementById('cnf_password').value;

    //alert(cnfpass);

    if(newpass == cnfpass)
    {
        v.type="submit";
    }
    else
    {
        alert("New Password and Confirm Password Not Match !");
    }
}

</script>
          