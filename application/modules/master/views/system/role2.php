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
Add Role
</div>
</div>
</div>

<div class="tile-body p-0">
<form  id="UserForm"> 
<div class="modal-body">
<div class="panel-group icon-plus" id="accordion" role="tablist" aria-multiselectable="true">
<div>
<article class="page-content">
<section class="block bottom20">
<header class="head-title">

</header>

<div class="entity-detail">
<table class="property-table">
<tbody>
<tr>
<td class="ralign"><span class="title">Role Name  </span></td>
<td><div class="info"><input type="text" name="role_name" id="role_name"> 

</div></td>
</tr>
<tr>
<td class="ralign"><span class="title">Reports To </span></td>
<td><div class="info"><select name="report_to" id="report_to">
<option value="" >----Select----</option>
<?php 
$sqlunit=$this->db->query("select * from tbl_role_mst where status='A'");
foreach ($sqlunit->result() as $fetchunit){
?>
<option value="<?php echo $fetchunit->role_id;?>"><?php echo $fetchunit->role_name; ?></option>
<?php } ?>
</select>
</div></td>
</tr>
</tbody>
</table>
</div>
</section>

</article>




</div><!--close-->
</div>
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
          