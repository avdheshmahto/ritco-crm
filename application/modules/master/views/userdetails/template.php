<?php
  $this->load->view("header.php");
?>
<div id="ajax_content">
  <?=$this->load->view($contant_data);?>
</div>

<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
<div class="modal-dialog modal-lg" role="document">
<div class="modal-content">
 <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
     <h4 class="modal-title" id="myModalLabel"><span class="top_title">Add New</span> User</h4>
     <div id="resultarea" style="font-size: 15px;color: red; text-align:center"></div> 
  </div>
   <form  id="UserForm"> 
     <div class="modal-body">
      <div class="panel-group icon-plus" id="accordion" role="tablist" aria-multiselectable="true">
      <div class="panel panel-default panel-transparent">
          
         <div class="panel-body">

          <div class="row">
            <div class="form-group col-md-6">
              <label for="name">Name : </label>
              <input type="text" name="name" id="name" class="form-control" required="" placeholder="Name">
              <input type="hidden" name="userid" id="user_id" class="hiddenField">
            </div>
             <div class="form-group col-md-6">
              <label for="email">Email Address : </label>
              <input type="email" name="email" id="email" class="form-control" required="" onkeyup="check_email(this.value);"  placeholder="Email Address">
            </div>
            </div>

          <div class="row">
		      <div class="form-group col-md-6">
              <label for="email">Profile Permission : </label>
              <select tabindex="3" class="chosen-select form-control" name="user_pro" id="user_pro" required="">
              <option  value="" >-- select profile -- </option>
               <?php 
                $sqlpro   = "select distinct(profile_name),profile_id from tbl_profile_mst where status = 'A' ";
                $querypro = $this->db->query($sqlpro)->result();
                 foreach ($querypro as  $dtpro){  ?>
                   <option value="<?=$dtpro->profile_id;?>"><?=$dtpro->profile_name;?></option>
                <?php 
                  }
                ?>
            </select>
          </div>          
          <div class="form-group col-md-6">
              <label for="email">Branch : </label>
              <select tabindex="3" class="chosen-select form-control" name="branch" id="branch" required="">
              <option  value="" >-- select branch -- </option>
               <?php 
                $sql   = "select * from tbl_branch_mst where status = 'A'";
                $query = $this->db->query($sql)->result();
                 foreach ($query as  $dt){  ?>
                   <option value="<?=$dt->brnh_id;?>"><?=$dt->brnh_name;?></option>
                <?php 
                  }
                ?>
            </select>
          </div>        
          </div>

       </div>

     </div><!--panel close-->
    </div>
  </div>

  <div class="modal-footer">
    <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
    <button type="submit" id="usersave"class="btn btn-primary">Save</button>
    <span id="userload" style="display: none;">
	     <img src="<?=base_url('assets/loadgif.gif');?>" alt="HTML5 Icon" width="54.63" height="40">
	  </span>
  </div>
  </form>
</div>
</div>
</div>
<?php
   $this->load->view("footer.php");
?>
<script type="text/javascript">

 function changeswitch(ths,idval)
 {

    ur ="<?=base_url('master/Userdetails/ajax_activeInactive');?>";
    var mode= $(ths).prop('checked');
     //alert(mode);
     $.ajax({
        type:'POST',
        url:ur,
        data:{'mode':mode,'id':idval},
        success:function(data)
        {
          // alert(data);
          // console.log(data);
        }
     });
  }


function check_email(value)
{
   
   var ur = "ajax_checkemail";
   
   $.ajax({

     url:ur,
     type:"POST",
     data:{'val':value},
     success:function(data){
         
            if(data == 1){
              $('#resultarea').html("Sorry. This E-mail already exist");
              $('#usersave').attr('disabled',true);

            } else {
              $('#resultarea').html("");
              $('#usersave').attr('disabled',false);
            }

     }
   });
  }



</script>
          