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
<div class="page page-full__ page-mail">
<div class="tbox tbox-sm">

<div class="tcol">
<div class="p-15 bg-white b-b">
<div class="btn-toolbar">
<h3>Advanced Reporting</h3>
</div>
</div><!--p-15 bg-white b-b close-->


<div class="row">
<div class="col-md-12">
<section class="tile" >
<ul class="nav nav-tabs mt-20_ mb-20">
<li><a href="<?=base_url('report/Report/searchLead');?>">Lead</a></li>
<li><a href="<?=base_url('report/Report/searchTask'); ?>">Task</a></li>
<li><a href="#">Report</a></li>
<li><a href="#">Report</a></li>
</ul>
</section>

</div>
</div>

</div>
</div>
</div>
</section>



<?php $this->load->view('footer.php'); ?>
