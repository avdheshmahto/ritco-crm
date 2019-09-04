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
<h3 class="custom-font">System Settings <i class="fa fa-angle-right"></i> Roles</h3>
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
<?php $this->load->view("main_nav_system.php"); ?>
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
<button class="btn btn-danger mb-10" data-toggle="modal" data-target="#leadModal" formid="#LeadForm" id="formreset">Add New Lead</button>
</div>
</div>
<h3 class="custom-font m-0 mr-5 inline-block">Role</h3>
</div>
</div>

<div class="tile-body p-0">
<form  id="UserForm"> 
<div class="modal-body">
<div class="panel-group icon-plus" id="accordion" role="tablist" aria-multiselectable="true">
<div>
<article class="page-content">
<section class="block bottom20">
<div class="post-comments">
<div class="row">
<div class="media">
<div class="media-heading media-heading-bg">
<span>
<ul class="list-inline list-unstyled">
<button class="btn btn-default btn-xs" type="button" data-toggle="collapse" data-target="#collapseThree" aria-expanded="false" aria-controls="collapseExample"><span class="glyphicon fa fa-angle-down" aria-hidden="true"></span></button>
<li><span>Cis  </span></li>
<li>|</li>
<li><span> <a href="#">Add Role</a></span></li>
</ul>
</span>
</div>

<div class="panel-collapse collapse in" id="collapseThree">
<div class="media-body">
<div class="media">
<div class="media-heading media-heading-bg">
<span>
<ul class="list-inline list-unstyled">
<button class="btn btn-default btn-collapse btn-xs" type="button" data-toggle="collapse" data-target="#collapseFour" aria-expanded="false" aria-controls="collapseExample"><span class="glyphicon fa fa-angle-down" aria-hidden="true"></span></button>
<li><span>Regional Head  </span></li>
<li><span class="badge bg-lightred">1</span></li>
<li>|</li>
<li><span> <a href="#">Edit</a></span></li>
<li>|</li>
<li><span> <a href="#">Del</a></span></li>
<li>|</li>
<li><span> <a href="#">Assign</a></span></li>
<li>|</li>
<li><span> <a href="#">Add Role</a></span></li>
</ul>
</span> 
</div>

<div class="panel-collapse collapse in" id="collapseFour">
<div class="media-body">
<div class="media">
<div class="media-heading media-heading-bg">
<span>
<ul class="list-inline list-unstyled">
<button class="btn btn-default btn-xs" type="button" data-toggle="collapse" data-target="#collapseFive" aria-expanded="false" aria-controls="collapseExample"><span class="glyphicon fa fa-angle-down" aria-hidden="true"></span></button>
<li><span>Branch Manager  </span></li>
<li><span class="badge bg-lightred">2</span></li>
<li>|</li>
<li><span> <a href="#">Edit</a></span></li>
<li>|</li>
<li><span> <a href="#">Del</a></span></li>
<li>|</li>
<li><span> <a href="#">Assign</a></span></li>
<li>|</li>
<li><span> <a href="#">Add Role</a></span></li>
</ul>
</span>
</div>

<div class="panel-collapse collapse in" id="collapseFive">
<div class="media-body">
<div class="media">
<div class="media-heading media-heading-bg">
<span>
<ul class="list-inline list-unstyled">
<button class="btn btn-default btn-collapse btn-xs" type="button" data-toggle="collapse" data-target="#collapseSix" aria-expanded="false" aria-controls="collapseExample"><span class="glyphicon fa fa-angle-down" aria-hidden="true"></span></button>
<li><span>Executive  </span></li>
<li>|</li>
<li><span> <a href="#">Edit</a></span></li>
<li>|</li>
<li><span> <a href="#">Del</a></span></li>
<li>|</li>
<li><span> <a href="#">Assign</a></span></li>
<li>|</li>
<li><span> <a href="#">Add Role</a></span></li>
</ul>
</span>
</div>

<div class="panel-collapse collapse in" id="collapseSix">
<div class="media-body">
<ul class="list-inline list-unstyled">
<li><span>Assistant  </span></li>
<li>|</li>
<li><span> <a href="#">Edit</a></span></li>
<li>|</li>
<li><span> <a href="#">Del</a></span></li>
<li>|</li>
<li><span> <a href="#">Assign</a></span></li>
<li>|</li>
<li><span> <a href="#">Add Role</a></span></li>
</ul>
</div>
</div>
</div>
</div>
</div>
</div>
</div>
</div>
</div>
</div>
</div>
</div><!--media close-->
</div>
</div><!-- post-comments -->


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

<!--rol menu-->
<script>
$('[data-toggle="collapse"]').on('click', function() {
    var $this = $(this),
            $parent = typeof $this.data('parent')!== 'undefined' ? $($this.data('parent')) : undefined;
    if($parent === undefined) { /* Just toggle my  */
        $this.find('.glyphicon').toggleClass('fa-angle-down fa-angle-up');
        return true;
    }

    /* Open element will be close if parent !== undefined */
    var currentIcon = $this.find('.glyphicon');
    currentIcon.toggleClass('glyphicon-plus glyphicon-minus');
    $parent.find('.glyphicon').not(currentIcon).removeClass('glyphicon-minus').addClass('glyphicon-plus');

});

</script>
<!--rol menu close-->
          