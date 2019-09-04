<?php $this->load->view('header.php'); ?>
    
<!--/ CONTROLS Content -->

<section id="content">
<div class="page page-full page-mail">
<div class="tbox tbox-sm">
<!-- left side -->
<div class="tcol w-md bg-tr-white lt b-r">
<!-- left side header-->
<div class="p-15 bg-white_" style="min-height: 61px">
<button class="btn btn-sm btn-default pull-right visible-sm visible-xs" data-toggle="collapse" data-target="#mail-nav" aria-expanded="false" aria-controls="collapseExample"><i class="fa fa-bars"></i></button>
</div>
<!-- /left side header -->
<!-- left side body -->
<div class="p-15 collapse collapse-xs collapse-sm" id="mail-nav">
<div class="panel panel-default">
<div class="panel-heading">
<h3 class="panel-title">Report Folders</h3>
</div>
<ul class="list-group">
<a href="#" class="list-group-item">My Personal Reports <span class="badge"><i class="fa fa-chevron-right icon-data"></i></span></a>
<a href="#" class="list-group-item active">Shared Reports <span class="badge"><i class="fa fa-chevron-right icon-data"></i></span></a>
<a href="#" class="list-group-item">All Reports <span class="badge"><i class="fa fa-chevron-right icon-data"></i></span></a>
<a href="#" class="list-group-item">Task Reports <span class="badge"><i class="fa fa-chevron-right icon-data"></i></span></a>
<a href="#" class="list-group-item">Contact Reports <span class="badge"><i class="fa fa-chevron-right icon-data"></i></span></a>
<a href="#" class="list-group-item">Organization Reports <span class="badge"><i class="fa fa-chevron-right icon-data"></i></span></a>
<a href="#" class="list-group-item">Lead Reports <span class="badge"><i class="fa fa-chevron-right icon-data"></i></span></a>
<a href="#" class="list-group-item">Opportunity Reports <span class="badge"><i class="fa fa-chevron-right icon-data"></i></span></a>
<a href="#" class="list-group-item">Project Reports <span class="badge"><i class="fa fa-chevron-right icon-data"></i></span></a>
<a href="#" class="list-group-item">Other Reports <span class="badge"><i class="fa fa-chevron-right icon-data"></i></span></a>
</ul>
</div>

            
</div>
<!-- /left side body -->
</div>
<!-- /left side -->
<!-- right side -->
<div class="tcol">
<!-- right side header -->
<div class="p-15 bg-white b-b">
<div class="btn-toolbar pull-right">
<div class="btn-group">
<button class="btn btn-danger btn-sm" data-toggle="modal" data-target="#myModal">New Report</button>
</div>			
<div class="btn-group">
<button class="btn btn-default btn-sm br-2"> Dashboard</button>
</div>
<div class="btn-group">
<button class="btn btn-default btn-sm br-2">Legacy Reports </button>
</div>
</div>
<div class="btn-toolbar">
<div class="btn-group mr-10">
<h3 class="custom-font">Advanced Reporting</h3>
</div>
</div>
</div>
<!-- /right side header -->
<!-- right side body -->
<div>
<section class="tile">
<div class="tile-widget tile-widget-to">
<div class="alert alert-warning">Welcome to Advanced Reporting! Need help? Check out our <a href="#" target="_blank">articles</a> or watch the <a href="#" target="_blank">video.</a></div>
<div class="row">
<div class="tile-b">
<div class="">
<div class="btn-group">
<h3 class="custom-font">My Personal Reports</h3>
</div>

</div>
</div>
</div>
<div class="row">
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
<!-- /tile body -->
<!-- tile footer -->
<div class="tile-footer dvd dvd-top">
<div class="row">
<div class="col-sm-5 hidden-xs">
<small class="text-muted">showing 20-30 of 50 items</small>
</div>
<div class="col-sm-3 text-center"></div>
<div class="col-sm-4 text-right">
<ul class="pagination pagination-sm m-0">
  <li><a href><i class="fa fa-chevron-left"></i></a></li>
  <li><a href>1</a></li>
  <li><a href>2</a></li>
  <li><a href>3</a></li>
  <li><a href><i class="fa fa-chevron-right"></i></a></li>
</ul>
</div>
</div>
</div>
<!-- /tile footer -->
</section>
</div>

<!-- /right side body -->
</div>
<!-- /right side -->
</div>
</div>
</section>
<!--/ CONTENT -->



<?php $this->load->view('footer.php'); ?>
