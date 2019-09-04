<?php
   $this->load->view("header.php");
?>

  

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

</div>
</div>
</div>
<div class="btn-toolbar">
<div class="btn-group mr-10">
<h3 class="custom-font">System Setting</h3>
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
<?php $this->load->view("main_nav_system.php"); ?>
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
    <?php 
      $keynm = $this->db->query("select * from tbl_master_data_mst where param_id='".$_GET['param_id']."' ");
      $getKeynm = $keynm->row();
      $hnm = $getKeynm->keyname; 
    ?>
    <button class="btn btn-primary" onclick="chkparmaid();" data-toggle="modal" data-target="#masterModal" formid="#MasterForm" style="padding: 4px;">Add&nbsp;<?=$hnm?> </button>
  </div>  
  <div class="btn-group">
    <button class="btn btn-danger btn-sm delete_all" style="display: none;">Delete All</button>
  </div>    
</div>

<div class="btn-toolbar">
  <div class="btn-group mr-10" style="padding-top:10px;">
  <strong><?=$hnm?></strong>
  </div>
</div>
</div>
</div>
<div id="listingMasterData"> <!-- listingMasterData -->
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
    <th><?=$hnm?></th>
    <td>Default</td>
    <th>Action</th>
  </tr>
</thead>

<tbody id="dataTable">
<?php
if(sizeof($result) > 0){
$i = 1;
foreach($result as $dt){ ?>
<tr class="record" data-row-id="<?=$dt->serial_number; ?>">

<td>
<label class="checkbox checkbox-custom-alt checkbox-custom-sm m-0">
<input name="cid[]" type="checkbox" id="cid[]" class="sub_chk" data-id="<?=$dt->serial_number; ?>" value=" <?=$dt->serial_number;?>">
<i></i></label>
</td>


<td><?=$dt->keyvalue;?></td>

<td>
<input type="radio" name="dufault_d" id="dufault_d" onchange="changeRadio(this,'<?=$dt->serial_number;?>');" <?=$dt->default_v=='Y'?'checked':'';?> >
</td>

<td>
<?php 
$indst=$this->db->query("select * from tbl_leads where industry='$dt->serial_number' ");
$getIndst=$indst->num_rows();

$prirty=$this->db->query("select * from tbl_task where priority='$dt->serial_number' ");
$getPrirty=$prirty->num_rows();

$tstats=$this->db->query("select * from tbl_task where task_status='$dt->serial_number' ");
$getStatus=$tstats->num_rows();

$ctgry=$this->db->query("select * from tbl_task where task_name='$dt->serial_number' ");
$getCtgry=$ctgry->num_rows();

$num_rowss = $getIndst + $getPrirty + $getStatus + $getCtgry ;

?>
<div class="btn-group pull-right">
<a href="#" class=" dropdown-toggle-" title="Actions" data-toggle="dropdown" aria-expanded="false"><i class="fa fa-ellipsis-h"></i></a>
<ul class="dropdown-menu" role="menu">
<!-- <li><a href="#" onclick="editMaster(this);" property = "view" type="button" data-toggle="modal" data-target="#masterModal" arrt= '<?=json_encode($dt);?>'  data-backdrop='static' data-keyboard='false'><i class="fa fa-eye"></i> View This Master</a></li> -->
<li><a href="#" onclick="editMaster(this);" property = "edit" type="button" data-toggle="modal" data-target="#masterModal" arrt= '<?=json_encode($dt);?>'  data-backdrop='static' data-keyboard='false'><i class="fa fa-edit"></i> Edit This Master</a></li>
<?php if($num_rowss > 0) { ?>
<li><a href="#" onclick="return confirm('Master already map. You can not delete ?');" ><i class="fa fa-trash"></i> Delete This Master </a></li>
<?php } else { ?>
<li class="delbutton"  id="<?=$dt->serial_number;?>^tbl_master_data^serial_number"><a href="#"><i class="fa fa-trash"></i> Delete This Master</a></li>
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
</div> <!-- Close listingMasterData -->

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



<input type="hidden" id="param_id" value="<?php echo $_GET['param_id'];?>">  
<input type="text" style="display:none;" id="table_name" value="tbl_master_data">  
<input type="text" style="display:none;" id="pri_col" value="serial_number">

<?php
  $this->load->view("footer.php");
?>

   
<script type="text/javascript">
  function chkparmaid()
  {
    var pid = document.getElementById('param_id').value;
    //alert(pid);
    $('#key_name').val(pid).prop('selected', true);
    $('#paramid').val(pid);
    //key_name
  }

   function changeRadio(ths,idval){
      //alert(idval);

      ur ="<?=base_url('master/System/ajax_defaultDropdown');?>";
      var mode= $(ths).prop('checked');
      var pid = document.getElementById('param_id').value;
       //alert(pid);
      
       $.ajax({
          type:'POST',
          url:ur,
          data:{'mode':mode,'id':idval,'pmid':pid},
          success:function(data)
          {
            // alert(data);
            // console.log(data);
          }
       });
    }
</script>