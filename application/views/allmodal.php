
<!-- Modal Master -->

<div class="modal fade" id="masterModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
        <h4 class="modal-title" id="myModalLabel"><span class="top_title">Add</span> Master</h4>
        <div id="resultareas" style="font-size: 15px;color: red; text-align:center"></div>
      </div>
      <form id="MasterForm">
        <div class="modal-body">
          <div class="panel-group icon-plus" id="accordion" role="tablist" aria-multiselectable="true">
            <div class="panel panel-default panel-transparent">
              <div class="panel-body">
                <div class="row">
                  <div class="form-group col-md-6">
                    <label for="email">Name : </label>
                    <input type="hidden" name="serial_number" id="serial_number" class="hiddenField">
                    <input type="hidden" name="paramid" id="paramid" >
                    <select tabindex="3" class="form-control" id="key_name"  disabled="">
                      <option  value="">-- Select Keyname --</option>
                      <?php 
                        $sql   = "select * from tbl_master_data_mst where status = 'A'";
                        $query = $this->db->query($sql)->result();
                        foreach ($query as  $dt){  ?>
                      <option value="<?=$dt->param_id;?>" >
                      <?=$dt->keyname;?>
                      </option>
                      <?php } ?>
                    </select>
                  </div>
                  <div class="form-group col-md-6">
                    <label for="email">Value : </label>
                    <input type="text" name="key_value" id="key_value" class="form-control"  placeholder="value">
                  </div>
                </div>
              </div>
            </div>
            <!--panel close-->
          </div>
        </div>
        <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
        <button type="submit" id="masterformsave" class="btn btn-primary">Save</button>
          <span id="mastersaveload" style="display: none;">
             <img src="<?=base_url('assets/loadgif.gif');?>" alt="HTML5 Icon" width="54.63" height="40">
          </span>
        </div>
      </form>
    </div>
  </div>
</div>
<!-- Modal Master close-->




<!-- All Module Modal Start-->
