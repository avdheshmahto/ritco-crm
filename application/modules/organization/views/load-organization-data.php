<?php 
$userPerQuery=$this->db->query("select *from tbl_profile_mst where profile_id='".$this->session->userdata('role')."' and module_id='1'");
$getAcc=$userPerQuery->row();
?>

 <!-- listdataid -->
<div class="tile-widget-to tile-widget-top">
<div class="show-entries">
<div class="row">
<div class="col-sm-5">
<div style="line-height:30px;">
Show
<select class="btn btn-default btn-sm" name="DataTables_Table_0_length" aria-controls="DataTables_Table_0" id="entries"  url="<?=base_url('organization/Organization/manage_organization?');?>">
<option value="10">10</option>
<option value="25" <?=$entries=='25'?'selected':'';?>>25</option>
<option value="50" <?=$entries=='50'?'selected':'';?>>50</option>
<option value="100" <?=$entries=='100'?'selected':'';?>>100</option>
<option value="500" <?=$entries=='500'?'selected':'';?>>500</option>
<option value="<?=$dataConfig['total'];?>" <?=$entries==$dataConfig['total']?'selected':'';?>>ALL</option>
</select>
entries</div>
</div>

<div class="col-sm-4"></div>
<div class="col-sm-3">
<div class="input-group">
<input type="text" class="input-sm form-control" id="searchTerm"  onkeyup="doSearch()" placeholder="Search...">
<span class="input-group-btn">
<!-- <button class="btn btn-sm btn-default" type="button" onclick="exportTableToExcel('tblData')">Excel</button>  -->
</span> 
</div>
</div>
</div>
</div>
</div>      

<!-- tile body -->
<div class="tile-body p-0">
<div class="table-responsive table-overflow___">
<table class="table mb-0 table-hover" id="tblData">
<thead>
  <tr>
      <th style="width:20px;"> 
        <label class="checkbox checkbox-custom-alt checkbox-custom-sm m-0">
        <input type="checkbox" id="check_all" onClick="checkall(this.checked)" value="check_all">
        <i></i> </label>
      </th>
      <th></th>
      <th>Organization Name</th>
      <th>Website</th>
      <th>Contact Person Name</th>
      <th>Designation</th>
      <th style="text-align: right;">Action</th>
  </tr>
</thead>

<tbody id="dataTable">
<?php
foreach($result as $fetch_list){
?>
<tr class="record" data-row-id="<?=$fetch_list->org_id; ?>">
<td>
  <label class="checkbox checkbox-custom-alt checkbox-custom-sm m-0">
    <input name="cid[]" type="checkbox" id="cid[]" class="sub_chk" data-id="<?=$fetch_list->org_id; ?>" value="<?=$fetch_list->org_id;?>">
    <i></i>
  </label>
</td>
<td>
<?php
$string=$fetch_list->org_name;
$firstCharacter = $string[0];
?>
<!-- <a href="#" onclick="getViewOrgPage('<?=$fetch_list->org_id;?>');"> -->
<a href="<?php echo base_url('organization/Organization/view_organization?id=');?><?=$fetch_list->org_id;?>">
<div class="thumb thumb-sm">
<div class="img-circle bg-orange circle"><?=$firstCharacter?></div>
</div>
</a>
</td>
<td>
  <a href="<?php echo base_url('organization/Organization/view_organization?id=');?><?=$fetch_list->org_id;?>"><?=$fetch_list->org_name; ?></a>
</td>
<td><?=$fetch_list->website; ?></td>
<td>
<?php
  $cnt=$this->db->query("select * from tbl_contact_m where contact_id='".$fetch_list->contact_id."' ");
  $getCnt=$cnt->row();
  echo $getCnt->contact_name; ?>
</td>
<td><?=$getCnt->designation; ?></td>
<td>
<?php 
  $pri_col='org_id';
  $table_name='tbl_organization';
?>

<div class="btn-group pull-right">
  <a href="#" class=" dropdown-toggle-" title="Actions" data-toggle="dropdown" aria-expanded="false"><i class="fa fa-ellipsis-h"></i></a>
  <ul class="dropdown-menu" role="menu">
<?php if($getAcc->edit_id=='1') { ?>
  <li><a href="#" onclick="editOrganization(this);" property = "edit" type="button" data-toggle="modal" data-target="#orgModal" arrt= '<?=json_encode($fetch_list);?>'  data-backdrop='static' data-keyboard='false'><i class="fa fa-edit"></i> Edit This Organization</a></li>
<?php } ?>

<?php if($getAcc->delete_id=='1') { ?>
<?php
$cntct=$this->db->query("select * from tbl_contact_m where org_name='$fetch_list->org_id' ");
$cntctNumRow=$cntct->num_rows();
$lead=$this->db->query("select * from tbl_leads where org_id='$fetch_list->org_id' ");
$leadNumRow=$lead->num_rows();
$task=$this->db->query("select * from tbl_task where org_name='$fetch_list->org_id' ");
$tskNumRow=$task->num_rows();

$num_rows=$cntctNumRow + $leadNumRow + $tskNumRow;

if($num_rows > 0) { ?>
  <li><a href="#" onclick="return confirm('Organization already map. You can not delete ?');" ><i class="fa fa-trash"></i> Delete This Organization</a></li>
<?php } else { ?>
  <li><a href="#" class="delbutton_orgz" id="<?php echo $fetch_list->org_id."^".$table_name."^".$pri_col ; ?>"><i class="fa fa-trash"></i> Delete This Organization</a></li>
<?php } ?>
<?php } ?>
</ul>
</div>
</td>
</tr>
<?php } ?>
</tbody>
</table>
</div>
</div>

<!-- /tile body -->

<!-- tile footer -->
  <div class="tile-footer dvd dvd-top" style="height: 55px">
  <div class="row">
  <div class="col-sm-5 hidden-xs">
  <small class="text-muted">
  Showing <?=$dataConfig['page']+1;?> to 
  <?php
  $m=$dataConfig['page']==0?$dataConfig['perPage']:$dataConfig['page']+$dataConfig['perPage'];
  echo $m >= $dataConfig['total']?$dataConfig['total']:$m;
  ?> of <?php echo $dataConfig['total'];?> entries
  </small>
  </div>
      <div class="col-sm-3 text-center"></div>
      <div class="col-sm-4 text-right" style="margin: -22px 0px 0px 0px;">
        <?=$pagination;?>
      </div>
  </div>
  </div>
<!-- /tile footer -->

