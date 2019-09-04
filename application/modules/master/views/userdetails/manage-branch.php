<?php
   $this->load->view("header.php");
?>

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
  

<section id="content" >
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
<!-- <span class="input-group-btn">
<button class="btn btn-sm btn-default" type="button">Go!</button>
</span> -->
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
<div class="tile-bg">
<div class="p-0 bg-white-">
<div class="btn-toolbar pull-right">
  <div class="btn-group">
    <button class="btn btn-primary" data-toggle="modal" data-target="#branchModal" formid="#BranchForm" style="padding: 4px;">Add New Branch </button>
  </div>  
  <div class="btn-group">
    <button class="btn btn-danger btn-sm delete_all" style="display: none;">Delete All</button>
  </div>    
</div>

<div class="btn-toolbar">
<div class="btn-group mr-10" style="padding-top:10px;">
<strong>Branch</strong>
</div>
</div>
</div>
</div>
<div id="listBranchData"> <!-- listBranchData -->
<div class="tile-body p-0">
<div class="table-responsive">
<table class="table mb-0">
<thead>
  <tr>
    <th> 
      <label class="checkbox checkbox-custom-alt checkbox-custom-sm m-0">
      <input type="checkbox" id="check_all" onClick="checkall(this.checked)" value="check_all">
      <i></i></label>
    </th>
    <th>Branch Code</th>
    <th>Branch Name</th>
    <th class="pull-right">Action</th>
  </tr>
</thead>

<tbody id="dataTable">
<?php
if(sizeof($result) > 0){
$i = 1;
foreach($result as $dt){ ?>
<tr class="record" data-row-id="<?=$dt->brnh_id; ?>">

<td>
<label class="checkbox checkbox-custom-alt checkbox-custom-sm m-0">
<input name="cid[]" type="checkbox" id="cid[]" class="sub_chk" data-id="<?=$dt->brnh_id; ?>" value=" <?=$dt->brnh_id;?>">
<i></i></label>
</td>


<td><?=$dt->brnh_code;?></td>
<td><?=$dt->brnh_name;?></td>

<td>
<?php 
$brnchid=$this->db->query("select * from tbl_software_log where brnh_id='$dt->brnh_id' ");
$num_rowss=$brnchid->num_rows();
?>
<div class="btn-group pull-right">
<a href="#" class=" dropdown-toggle-" title="Actions" data-toggle="dropdown" aria-expanded="false"><i class="fa fa-ellipsis-h"></i></a>
<ul class="dropdown-menu" role="menu">
<!-- <li><a href="#" onclick="editMaster(this);" property = "view" type="button" data-toggle="modal" data-target="#masterModal" arrt= '<?=json_encode($dt);?>'  data-backdrop='static' data-keyboard='false'><i class="fa fa-eye"></i> View This Master</a></li> -->
<li><a href="#" onclick="editBranch(this);" property = "edit" type="button" data-toggle="modal" data-target="#branchModal" arrt= '<?=json_encode($dt);?>'  data-backdrop='static' data-keyboard='false'><i class="fa fa-edit"></i> Edit This Branch</a></li>
<?php if($num_rowss > 0) { ?>
<li><a href="#" onclick="return confirm('Branch already map. You can not delete ?');" ><i class="fa fa-trash"></i> Delete This Branch </a></li>
<?php } else { ?>
<li class="delbutton"  id="<?=$dt->brnh_id;?>^tbl_branch_mst^brnh_id"><a href="#"><i class="fa fa-trash"></i> Delete This Branch</a></li>
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
</div> <!-- Close listBranchData -->

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


<!-- Modal Branch -->
<div class="modal fade" id="branchModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
        <h4 class="modal-title" id="myModalLabel"><span class="top_title">Add</span> Branch</h4>
        <div id="resultarea" style="font-size: 15px;color: red; text-align:center"></div>
      </div>
      <form id="BranchForm">
        <div class="modal-body">
          <div class="panel-group icon-plus" id="accordion" role="tablist" aria-multiselectable="true">
            <div class="panel panel-default panel-transparent">
              <div class="panel-body">
                <div class="row">
                  <div class="form-group col-md-6">
                    <label for="email">Branch Code : </label>
                    <input type="hidden" name="branch_id" id="branch_id" class="hiddenField">
                    <input type="text" name="branch_code" id="branch_code" class="form-control">
                  </div>
                  <div class="form-group col-md-6">
                    <label for="email">Branch Name : </label>
                    <input type="text" name="branch_name" id="branch_name" class="form-control">
                  </div>
                </div>
              </div>
            </div>
            <!--panel close-->
          </div>
        </div>
        <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
        <button type="submit" id="branchsave" class="btn btn-primary">Save</button>
          <span id="branchload" style="display: none;">
             <img src="<?=base_url('assets/loadgif.gif');?>" alt="HTML5 Icon" width="54.63" height="40">
          </span>
        </div>
      </form>
    </div>
  </div>
</div>
<!-- Modal Branch-->


<input type="text" style="display:none;" id="table_name" value="tbl_branch_mst">  
<input type="text" style="display:none;" id="pri_col" value="brnh_id">

<?php
  $this->load->view("footer.php");
?>

   
<script type="text/javascript">

  $("#branch_code").keyup(function(){

      
      ur ="<?=base_url('master/master/ajax_chkbranch_code');?>";
      var brnh_cd = $('#branch_code').val();
      //alert(brnh);
       $.ajax({
          type:'POST',
          url:ur,
          data:{'val':brnh_cd},
          success:function(data)
          {
            if(data==1)
            {
              $('#resultarea').html("Branch Code Already Exists!"); 
              $('#branchsave').attr('disabled',true); 
            }
            else
            {
              $('#resultarea').html(""); 
              $('#branchsave').attr('disabled',false);
            }
            
          }
       });
});


  $("#branch_name").keyup(function(){

      
      ur ="<?=base_url('master/master/ajax_chkbranch');?>";
      var brnh = $('#branch_name').val();
      //alert(brnh);
       $.ajax({
          type:'POST',
          url:ur,
          data:{'val':brnh},
          success:function(data)
          {
            if(data==1)
            {
              $('#resultarea').html("Branch Name Already Exists!"); 
              $('#branchsave').attr('disabled',true); 
            }
            else
            {
              $('#resultarea').html(""); 
              $('#branchsave').attr('disabled',false);
            }
            
          }
       });
});  
      
</script>