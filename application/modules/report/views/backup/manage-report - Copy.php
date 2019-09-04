<?php $this->load->view('header.php'); ?>


<style>
/* make sidebar nav vertical */ 
@media (min-width: 768px){
  .affix-content .container {
    width:100%;
  }   


    .affix-content .container .page-header{
    margin-top: 0;
  }
  .sidebar-nav{
     /*position:fixed; 
     width:100%;*/
  }
  .affix-sidebar{
    padding-right:0; 
    font-size:small;
    padding-left: 0;
  }  
  .affix-row, .affix-container, .affix-content{
    height: 100%;
    margin-left: 0;
    margin-right: 0;    
  } 
  .affix-content{
    background-color:white; 
  } 
  .sidebar-nav .navbar .navbar-collapse {
    padding: 0;
    max-height: none;
  }
  .sidebar-nav .navbar{
    border-radius:0; 
    margin-bottom:0; 
    border:0;
  }
  .sidebar-nav .navbar ul {
    float: none;
    display: block;
  }
  
  .nav-header{
    padding-top: 12px;
    padding-bottom: 12px;
	padding: 10px 15px;	
	}
  
    
  .sidebar-nav .navbar li {
    float: none;
    display: block;
	
  }
  .sidebar-nav .navbar li a {
    padding-top: 12px;
    padding-bottom: 12px;
  }  
}

@media (min-width: 769px){
  .affix-content .container {
        width:100%;
  }
    .affix-content .container .page-header{
    margin-top: 0;
  }  
}

@media (min-width: 992px){
  .affix-content .container {
    width:100%;
  }
    .affix-content .container .page-header{
    margin-top: 0;
  }
}

@media (min-width: 1220px){
  .affix-row{
    overflow: hidden;
  }

  .affix-content{
    overflow: auto;
  }

  .affix-content .container {
      width:100%;
  }

  .affix-content .container .page-header{
    margin-top: 0;
  }
  .affix-content{
    padding-right: 30px;
    padding-left: 30px;
  }  
  .affix-title{
    border-bottom: 1px solid #ecf0f1; 
    padding-bottom:10px;
  }
  .navbar-nav {
    margin: 0;
  }
  .navbar-collapse{
    padding: 0;
  }
  .sidebar-nav .navbar li a:hover {
    background-color: #428bca;
    color: white;
  }
  .sidebar-nav .navbar li a > .caret {
    margin-top: 8px;
  }  
}

</style>
    
<!--/ CONTROLS Content -->

<section id="content">
<div class="page page-tables-bootstrap">
<div class="row">
<div class="col-md-12">
<section class="tile">
<div class="pageheader tile-bg">
<div class="p-0 bg-white-">

<div class="btn-toolbar">
<div class="btn-group mr-10">
<h3 class="custom-font">Advanced Reporting</h3>
</div>
</div>
</div>
</div><!--pageheader close-->

<div class="tile-body p-15">

<div class="row">
<div class="col-sm-3">
<div class="sidebar-nav">
<div class="navbar navbar-default" role="navigation">
<div class="navbar-header">

<span class="visible-xs navbar-brand">Report Folders</span> </div>
<div class="navbar-collapse collapse sidebar-navbar-collapse">
<ul class="nav navbar-nav" id="sidenav01">

<li><a href="#"><i class="fa fa-folder"></i>&nbsp; &nbsp; Organization Reports <span class="pull-right"><i class="fa fa-chevron-right icon-data"></i></span></a></li>
<li><a href="#"><i class="fa fa-folder"></i>&nbsp; &nbsp; Contact Reports <span class="pull-right"><i class="fa fa-chevron-right icon-data"></i></span></a></li>
<li><a href="<?=base_url('report/Report/searchLead');?>"><i class="fa fa-folder"></i>&nbsp; &nbsp; Lead Reports <span class="pull-right"><i class="fa fa-chevron-right icon-data"></i></span></a></li>
<li><a href="#"><i class="fa fa-folder"></i>&nbsp; &nbsp; Task Reports <span class="pull-right"><i class="fa fa-chevron-right icon-data"></i></span></a></li>
</ul>
</div>
<!--/.nav-collapse -->
</div>
</div>


</div><!--col-sm-3 close-->

<div class="col-sm-9">
<section class="tile" style="top:0px;">
<div class="tile-widget_ tile-widget-to">
<h4 style="padding-left:15px;">Task Reports</h4>

<div class="tile-bg">
<div class="row">
<div class="col-sm-6">
<div class="input-group">
<input type="text" class="input-sm form-control" placeholder="Search...">
<span class="input-group-btn">
<button class="btn btn-sm btn-default" type="button">Go!</button>
</span>
</div>
</div>
</div>
</div>
</div>
<!-- /tile widget -->
<!-- tile body -->
<div class="tile-body p-0">
<div class="table-responsive">
<table class="table mb-0" id="usersList">
<thead>
<tr>
  <th>Task Name</th>
  <th>Date Due</th>
  <th>Responsible User</th>
  <th>Task Owner</th>
</tr>
</thead>
<tbody>
<tr>
<td><a href="#">1. Personalize your account</a></td>
<td>-</td>
<td><a href="#">ravi kumar</a></td>
<td><a href="#">ravi kumar</a></td>
</tr>

<tr>
<td><a href="#">1. Personalize your account</a></td>
<td>-</td>
<td><a href="#">ravi kumar</a></td>
<td><a href="#">ravi kumar</a></td>
</tr>

<tr>
<td><a href="#">1. Personalize your account</a></td>
<td>-</td>
<td><a href="#">ravi kumar</a></td>
<td><a href="#">ravi kumar</a></td>
</tr>

<tr>
<td><a href="#">1. Personalize your account</a></td>
<td>-</td>
<td><a href="#">ravi kumar</a></td>
<td><a href="#">ravi kumar</a></td>
</tr>



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
<!--/ CONTENT -->



<?php $this->load->view('footer.php'); ?>
