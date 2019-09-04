<style>

.sidebar-left {
    float: left;
}

.left-sidenav {
    width: 180px;
    padding: 0;
    background-color: #fff;
    border-radius: 3px;
    -webkit-border-radius: 3px;
    border: 1px solid #e5e5e5;
    margin-bottom: 20px;
}

.left-sidenav li {
    border-bottom: 1px solid #e5e5e5;
}

.left-sidenav>li>a {
    display: inline-block;
    width: 176px \9;
    margin: 0;
    /*padding: 8px 0 0 14px !important;*/
    height:auto;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    width:auto;
	font-family:Arial, Helvetica, sans-serif;
	font-size:13px;
	text-decoration:none;
}

.nav-list>li>a {
    padding:5px 0 2px 20px;
    color:#222;
}

.left-sidenav>li:hover{background-color:#eee}.

.left-sidenav>.active>a {
    position: relative;
    z-index: 2;
    border-right: 0 !important;
}

.nav-list>.active>a, .nav-list>.active>a:hover {
    padding-left:16px;
    font-weight: bold;
    color: #dd4b39;
    text-shadow: none;
    background-color: transparent;
    border-left: 3px solid #dd4b39;
}

</style>


            <?php $uri = $_SERVER['REQUEST_URI'];
            @$uri1=explode('/',$uri);
            @$uri2=@$uri1[2]."/".@$uri1[3]."/".@$uri1[4];  ?>

<aside class="sidebar-left">
<ul class="nav nav-list left-sidenav">
       <!--<li <?php if($uri2 == "master/Userdetails/manage_user") { ?>class="active" <?php } ?> >
        <a href="<?=base_url('master/Userdetails/manage_user');?>">User Setting</a></li> -->             
       <li <?php if($uri2 == "master/master/userprofileview") { ?>class="active" <?php } ?> >
        <a href="<?=base_url('master/master/userprofileview');?>">User Profile</a></li>
        <?php if($this->session->userdata('role')==1) { ?>
       <li <?php if($uri2 == "master/Userdetails/setting_user") { ?>class="active" <?php } ?> >
        <a href="<?=base_url('master/Userdetails/setting_user');?>">User List</a></li>
       <li <?php if($uri2 == "master/master/user_branch") { ?>class="active" <?php } ?> >
        <a href="<?=base_url('master/master/user_branch');?>">User Branch</a></li>
        <li <?php if($uri2 == "master/System/manage_profile") { ?>class="active" <?php } ?> >
            <a href="<?=base_url();?>master/System/manage_profile">Profile Permission</a></li>
        <?php } ?>
       <li <?php if($uri2 == "master/master/changepassword") { ?>class="active" <?php } ?> >
        <a href="<?=base_url('master/master/changepassword');?>">Change Password</a></li>
       
       <?php //if($this->session->userdata('role')==1 || $this->session->userdata('role')==2){ ?>
       <!-- <li><a href="<?php //echo base_url('admin/rolefunction/role_function_action');?>">User Role</a></li> -->
       <?php //} ?>
 </ul>
</aside>



<!-- <aside class="sidebar-left">
<ul class="nav nav-list left-sidenav">
<li><a href="#"><i class="icon-chevron-right" style="display:-webkit-box;"></i>Referrals</a></li>
<li><a href="#"><i class="icon-chevron-right" style="display:-webkit-box;"></i>User Details </a></li>
<li><a href="#"><i class="icon-chevron-right" style="display:-webkit-box;"></i>Email Accounts</a></li>
<li><a href="#"><i class="icon-chevron-right" style="display:-webkit-box;"></i>Email Signature </a></li>
<li><a href="#"><i class="icon-chevron-right" style="display:-webkit-box;"></i>Mailbox </a></li>
<li><a href="#"><i class="icon-chevron-right" style="display:-webkit-box;"></i>User Settings </a></li>
<li><a href="#"><i class="icon-chevron-right" style="display:-webkit-box;"></i>Notifications </a></li>
<li><a href="#"><i class="icon-chevron-right" style="display:-webkit-box;"></i>Following </a></li>
<li><a href="#"><i class="icon-chevron-right" style="display:-webkit-box;"></i>Contact Sync </a></li>
<li><a href="#"><i class="icon-chevron-right" style="display:-webkit-box;"></i>Calendar Sync </a></li>
<li><a href="#"><i class="icon-chevron-right" style="display:-webkit-box;"></i>Exchange Settings </a></li>
</ul>
</aside> -->