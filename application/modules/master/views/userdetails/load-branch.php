 <!-- listBranchData -->
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
    <!-- <td>Default</td> -->
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
<!-- <td>
<input type="radio" name="dufault_d" id="dufault_d" onchange="changeRadio(this,'<?=$dt->serial_number;?>');" <?=$dt->default_v=='Y'?'checked':'';?> >
</td> -->

<td>
<div class="btn-group pull-right">
<a href="#" class=" dropdown-toggle-" title="Actions" data-toggle="dropdown" aria-expanded="false"><i class="fa fa-ellipsis-h"></i></a>
<ul class="dropdown-menu" role="menu">
<!-- <li><a href="#" onclick="editMaster(this);" property = "view" type="button" data-toggle="modal" data-target="#masterModal" arrt= '<?=json_encode($dt);?>'  data-backdrop='static' data-keyboard='false'><i class="fa fa-eye"></i> View This Master</a></li> -->
<li><a href="#" onclick="editBranch(this);" property = "edit" type="button" data-toggle="modal" data-target="#branchModal" arrt= '<?=json_encode($dt);?>'  data-backdrop='static' data-keyboard='false'><i class="fa fa-edit"></i> Edit This Branch</a></li>
<li class="delbutton"  id="<?=$dt->brnh_id;?>^tbl_branch_mst^brnh_id"><a href="#"><i class="fa fa-trash"></i> Delete This Branch</a></li>
</ul>
</div>
</td>

</tr>
<?php $i++; } } ?>
</tbody>
</table>
</div>
</div>
