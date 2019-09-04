
<div class="modal-content">
<div class="modal-header">
<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">x</span></button>
<h4 class="modal-title" id="myModalLabel"><span class="top_title">Update</span> Profile</h4>
<div id="resultareadd" style="font-size: 15px;color: red; text-align:center"></div> 
</div>
<div class="modal-body">

<div class="form-group">
<?php
  
    $profileQuery1=$this->db->query("select * from tbl_profile_mst where profile_id='$id'");
    $fetch_list1=$profileQuery1->row();     

?>
<label for="inputEmail3" class="col-sm-4 control-label">Profile Name</label>
<div class="col-sm-4">
  <?php if($fetch_list1->profile_name =='Admin'){?> 
<input type="text" class="form-control" name="profile_name" readonly id="profile_name" value="<?php echo $fetch_list1->profile_name; ?>" >
<input type="hidden" name="profile_id" id="profile_id" value="<?php echo $fetch_list1->profile_id; ?>">
<?php }else{ ?>
  <input type="text" class="form-control" name="profile_name"  id="profile_name" value="<?php echo $fetch_list1->profile_name; ?>" >
<input type="hidden" name="profile_id" id="profile_id" value="<?php echo $fetch_list1->profile_id; ?>">
<?php } ?>
</div>
</div>



<table class="table table-hover">
<thead>
<tr>
<th>&nbsp;</th>
<th colspan="4"><center>  Permissions</center></th>
</tr>
</thead>
<tbody>
<tr>
<th>&nbsp;</th>
<th>Read</th>
<th>Create</th>
<th>Edit</th>
<th>Delete</th>
</tr>
<?php
 $profileQuery=$this->db->query("select * from tbl_profile_mst where profile_id='$id' ORDER BY module_id ASC");
 foreach($profileQuery->result() as $fetch_list){

   $moduleQuery=$this->db->query("select * from tbl_module_mst where module_id ='$fetch_list->module_id' and status='A' ORDER BY module_id ASC ");
   //foreach($moduleQuery->result() as $moduleget){
   $fetch_list23=$moduleQuery->row();
?>
<tr>


<td><input type="hidden" checked="" name="profilek[]" id="profilek" value="<?php echo $fetch_list23->module_id;?>"><?php echo $fetch_list23->module_name;?></td>
<td>
<div class="checkbox tiny">
<input type="checkbox"  <?php if($fetch_list->read_id=='1' and $fetch_list->module_id==$fetch_list23->module_id ){ ?> checked="checked" <?php }?> value="" id="read_id<?=$fetch_list23->module_id;?>" disabled="disabled" onclick="checkboxsel(this.id);">
<input type="hidden" name="read_id[]" id="read_idhide<?=$fetch_list23->module_id;?>" value="<?php echo $fetch_list->read_id; ?>">
<div class="checkbox-container">
<div class="checkbox-checkmark"></div>
</div>
</div>
</td>
<td>

<div class="checkbox tiny">
<input type="checkbox" <?php if($fetch_list->create_id=='1' and $fetch_list->module_id==$fetch_list23->module_id ){ ?> checked="checked" <?php }?>  id="create_id<?=$fetch_list23->module_id;?>" onclick="checkboxselcreate(this.id);">
<input type="hidden" name="create_id[]" id="create_idhide<?=$fetch_list23->module_id;?>" value="<?php echo $fetch_list->create_id; ?>">
<div class="checkbox-container">
<div class="checkbox-checkmark"></div>
</div>
</div>
</td>
<td>


<div class="checkbox tiny">
<input type="checkbox" <?php if($fetch_list->edit_id=='1' and $fetch_list->module_id==$fetch_list23->module_id ){ ?> checked="checked" <?php }?>  id="edit_id<?=$fetch_list23->module_id;?>" disabled="disabled" onclick="checkboxseledit(this.id);">
<input type="hidden" name="edit_id[]" id="edit_idhide<?=$fetch_list23->module_id;?>" value="<?php echo $fetch_list->edit_id; ?>">
<div class="checkbox-container">
<div class="checkbox-checkmark"></div>
</div>
</div>
</td>
<td>



<div class="checkbox tiny">
<input type="checkbox" <?php if($fetch_list->delete_id=='1' and $fetch_list->module_id==$fetch_list23->module_id ){ ?> checked="checked" <?php }?> id="delete_id<?=$fetch_list23->module_id;?>" onclick="checkboxseldelete(this.id);">
<input type="hidden" name="delete_id[]" id="delete_idhide<?=$fetch_list23->module_id;?>" value="<?php echo $fetch_list->delete_id; ?>">
<div class="checkbox-container">
<div class="checkbox-checkmark"></div>
</div>
</div>
</td>
<!-- <td><input type="text" name="checktest[]" id="checktest<?=$moduleget->module_id;?>" value="0" ></td>
 --></tr>
<?php  } ?>

</tbody>
</table>
<!-- </form> -->
<!-- </form> --><!--form-horizontal close-->
</div>
<div class="modal-footer">
  
<button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
<?php if($fetch_list1->profile_name =='Admin'){?> 

<?php }else{
?>
<button type="submit"  class="btn btn-primary" >Save</button>
<?php } ?>

</div>
</div><!--modal-content close-->
