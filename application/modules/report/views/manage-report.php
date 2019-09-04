<?php $this->load->view('header.php'); ?>


<section id="content">
<div class="page page-tables-bootstrap">
<div class="row">
<div class="col-md-12">
<section class="tile">

  <input type="hidden" id="json_contact" value='<?=$json_contact;?>'>
  <input type="hidden" id="json_orgnization" value='<?=$json_orgnization;?>'>

<div class="pageheader tile-bg" style="margin-bottom:0px;">
<span>ADVANCED REPORTING</span>
</div><!--pageheader close-->

<div id="listingData"> <!-- listdataid -->
<div class="tile-widget-to__ tile-widget-top__">
<div class="show-entries____">
<ul class="nav nav-tabs mt-20_ mb-20">
<li><a href="<?=base_url('report/Report/searchLead');?>">Lead</a></li>
<li><a href="<?=base_url('report/Report/searchTask'); ?>">Task</a></li>
<!-- <li><a href="#">Report</a></li>
<li><a href="#">Report</a></li> -->
</ul>
</div>
</div>      

<!-- tile body -->
<div class="tile-body p-0">
<div class="table-responsive" style="height:300px;">

</div>
</div><!-- /tile body -->

</div> <!-- /listdataid -->

</section>
</div>
</div>

</div>
</section>



<?php $this->load->view('footer.php'); ?>
