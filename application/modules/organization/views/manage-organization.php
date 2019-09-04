<?php $this->load->view('header.php'); ?>
<?php 
$userPerQuery=$this->db->query("select * from tbl_profile_mst where profile_id='".$this->session->userdata('role')."' and module_id='1'");
$getAcc=$userPerQuery->row();
?>
<?php

  $contact = $this->db->query("select contact_id,contact_name  from tbl_contact_m where status = 'A' ");  
  $arr = array();

  foreach ($contact->result() as $getContact) 
  { 
      $arr[] = $getContact->contact_name."^".$getContact->contact_id;
  } 


  $json_contact   = json_encode($arr,true);

  //print_r($arr);

  $contact = $this->db->query("select * from tbl_organization where status='A' ORDER BY org_name ");
  $arrorgnization = array();

    foreach ($contact->result() as $getContact) 
    { 
       $arrorgnization[] = $getContact->org_name.'^'.$getContact->org_id;
    } 

  $json_orgnization = json_encode($arrorgnization,true);

  ?>

<div id="ajax_content">
<section id="content">
<div class="page page-tables-bootstrap">
<div class="row">
<div class="col-md-12">
<section class="tile">

  <input type="hidden" id="json_contact" value='<?=$json_contact;?>'>
  <input type="hidden" id="json_orgnization" value='<?=$json_orgnization;?>'>

<div class="pageheader tile-bg">
<span>ORGANIZATIONS</span>
<div class="page-bar">
<ul class="page-breadcrumb">
<div class="btn-toolbar pull-left">
<div class="btn-group">
<select class="input-sm form-control inline">
<option value="0">All Organizations</option>
</select>
</div>
</div>
</ul>

<div class="btn-toolbar pull-right">
<div class="btn-group">
  <?php
if($getAcc->create_id=='1')
{
?>
<button class="btn btn-danger mb-10" data-toggle="modal" data-target="#orgModal" formid="#OrganizationForm" id="formreset" style="padding: 4px;">Add New Organization</button>
<?php } ?>
</div>
<div class="btn-group">
  <?php
if($getAcc->delete_id=='1')
{
?>
<button class="btn btn-primary btn-sm mb-10 delete_all" style="display: none;"> Delete All</button>
<?php } ?>
</div>
</div>
</div>
</div><!--pageheader close-->

<div id="listingData"> <!-- listdataid -->
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

</div> <!-- /listdataid -->

</section>
</div>
</div>

</div>
</section>

</div> <!-- /ajax_content -->

<!-- Modal Orgz-->
<div class="modal fade" id="orgModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
<div class="modal-dialog modal-lg" role="document">
<div class="modal-content">

<div class="modal-header">
<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
<h4 class="modal-title" id="myModalLabel"><span class="top_title">Add</span> Organization</h4>
<div id="resultarea" style="font-size: 15px;color: red; text-align:center"></div> 
</div>

<form  id="OrganizationForm"> 
<div class="modal-body">
<div class="sb-container container-example1">
<!-- <div class="tile-body slim-scroll" style="max-height: 350px;overflow:auto;"> -->
<div class="panel-group icon-plus" id="accordion" role="tablist" aria-multiselectable="true">

<div class="panel panel-default panel-transparent">
<div class="panel-heading" role="tab" id="headingOne">
<h4 class="panel-title">
<a data-toggle="collapse" data-parent="#accordion" href="#ORGDETAILS" aria-expanded="true" aria-controls="ORGDETAILS">
<span><i class="fa fa-minus text-sm mr-5"></i> ORGANIZATION DETAILS</span>
</a>
</h4>
</div>
<div id="ORGDETAILS" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingOne">
<div class="panel-body">

<div class="row">
<div class="form-group col-md-6">
<input type="hidden" name="oldorgid" id="oldorgid" class="hiddenField">
<label>*Organization Name : <span class="label label-success" id="newidorg" style="display: none;">new</span></label>
<div class="typeahead__container ">
<div class="typeahead__field">
<div class="typeahead__query">
<input class="orgnizationjs-typeahead form-control"
   name="org_name" 
   id="org_name"
   type="search"
   autofocus
   autocomplete="off" >
</div>
</div>
</div>

</div>
<div class="form-group col-md-6">
<label for="contactemail">Website : </label>
<input type="text" name="website" id="website" class="form-control mb-10" >
</div>
</div>

<div class="row">
<div class="form-group col-md-6">
<label>Email : </label>
  <table id="addrowbox">
    <tr style="line-height: 3;">
     <td style="width:100%;">
    <input type="email" name="email_id[]" id="email_id0" class="form-control">
     </td>
     <td align="center" style="width: 150px;">
       <!-- <i class="fa fa-trash" aria-hidden="true" style="font-size: 19px;" ></i> -->
     </td>
  </tr>
  </table>
  <a style="cursor: pointer;" onclick="addRowOrgEmail();"><small>+ add one more</small></a>
</div>
<div class="form-group col-md-6">
<label>Phone : </label>
<table id="addrowboxp">
  <tr style="line-height:3;">
    <td style="width:100%;">
     <input type='text' name="phone_no[]" id="phone_no0" class="form-control">
    </td>
    <td align="center" style="width: 150px;">
     <!-- <i class="fa fa-trash" aria-hidden="true" style="font-size: 19px;" ></i> -->
    </td>
  </tr>
</table>
  <a  style="cursor: pointer;" onclick="addRowOrgPhone();"><small>+ add one more</small></a>
</div>
</div>

<div class="row">
<div class="form-group col-md-6">
<label>Address : </label>
<textarea name="address" id="address"  class="form-control" ></textarea>
</div>
<div class="form-group col-md-6">
<label>City : </label>
<input type='text' name="city" id="city" class="form-control" >
</div>
</div>

<div class="row">
<div class="form-group col-md-6">
<label>State : </label>
<select name="state_id" id="state_id" class="chosen-select form-control">
  <option value="">--Select--</option>
  <?php
  $stateQquery=$this->db->query("select * from tbl_state_m where status='A'  ORDER BY stateName ");
  foreach($stateQquery->result() as $getState){
  ?>
  <option value="<?=$getState->stateid;?>"><?=$getState->stateName;?></option>
  <?php } ?>
</select>
</div>
<div class="form-group col-md-6">
<label>Pin Code : </label>
<input type="number" name="pin_code" id="pin_code" class="form-control">
</div>
</div>

<div class="row">
<div class="form-group col-md-6">
<label>Country : </label>
<select name="country_id" id="country_id" class="chosen-select form-control">
  <option value="">--Select--</option>
  <?php
  $stateQquery=$this->db->query("select * from tbl_country_m where status='A'  ORDER BY countryName ");
  foreach($stateQquery->result() as $getState){
  ?>
  <option value="<?=$getState->contryid;?>" <?php if($getState->contryid == 1) { ?>selected <?php } ?> ><?=$getState->countryName;?></option>
  <?php } ?>
</select>
</div>
</div>

</div>
</div>
</div><!--panel close-->


<div class="panel panel-default panel-transparent">
<div class="panel-heading" role="tab" id="headingOne">
<h4 class="panel-title">
<a data-toggle="collapse" data-parent="#accordion" href="#NAMEDETAILS" aria-expanded="true" aria-controls="NAMEDETAILS">
<span><i class="fa fa-minus text-sm mr-5"></i>ORGANIZATION CONTACT PERSON DETAILS</span>
</a>
</h4>
</div>
<div id="NAMEDETAILS" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingOne">
<div class="panel-body">

<div class="row">
<div class="form-group col-md-6">
<input type="hidden" name="oldcontact_id" id="oldcontact_id" class="hiddenField">
<label>*Contact Person Name : <span class="label label-success" id="newid" style="display: none;">new</span></label>
<div class="typeahead__container ">
<div class="typeahead__field">
<div class="typeahead__query">
<input class="js-typeahead form-control"
       name="contact_name" 
       id="contact_name"
       type="search"
       autofocus
       autocomplete="off" >
</div>
</div>
</div>
</div>
<div class="form-group col-md-6">
<label>Designation : </label>
<input type="text" name="designation" id="designation"  class="form-control">
</div>
</div>

<div class="row">

<div class="form-group col-md-6">
 <label>Email : </label>
  <table id="cntaddrowbox">
    <tr style="line-height: 3;">
     <td style="width:100%;">
       <input type="email" name="cemail_id[]" id="cemail_id0" class="form-control">
     </td>
     <td align="center" style="width: 150px;">
       <!-- <i class="fa fa-trash" aria-hidden="true" style="font-size: 19px;" ></i> -->
     </td>
  </tr>
  </table>
  <a style="cursor: pointer;" onclick="addRowCompemail();"><small>+ add one more</small></a>
 </div>
  <div class="form-group col-md-6">
  <label>Phone : </label>
  <table id="cntaddrowboxp">
    <tr style="line-height:3;">
      <td style="width:100%;">
       <input type='text' name="cphone_no[]" id="cphone_no0" class="form-control">
      </td>
      <td align="center" style="width: 150px;">
       <!-- <i class="fa fa-trash" aria-hidden="true" style="font-size: 19px;" ></i> -->
      </td>
    </tr>
  </table>
  <a  style="cursor: pointer;" onclick="addRowComphone();"><small>+ add one more</small></a>
</div>

</div>

</div>
</div>
</div><!--panel close-->

<div class="panel panel-default panel-transparent">
<div class="panel-heading" role="tab" id="headingThree">
<h4 class="panel-title">
<a data-toggle="collapse" data-parent="#accordion" href="#DESCRIPTIONINFORMATION" aria-expanded="true" aria-controls="DESCRIPTIONINFORMATION">
<span><i class="fa fa-minus text-sm mr-5"></i> DESCRIPTION</span>
</a>
</h4>
</div>
<div id="DESCRIPTIONINFORMATION" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingThree">
<div class="panel-body">
<div class="form-group">
<div class="col-sm-12">
<textarea id="summernote" class="form-control"></textarea>
</div>
</div>
</div>
</div>
</div><!--panel close-->


</div>
</div>
</div>

<div class="modal-footer">
<button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
<button type="submit" id="formsave" class="btn btn-primary">Save</button>
  <span id="saveload" style="display: none;">
     <img src="<?=base_url('assets/loadgif.gif');?>" alt="HTML5 Icon" width="54.63" height="40">
  </span>
</div>

</form>
  
</div>
</div>
</div>
<!-- /Modal Orgz close-->



<input type="text" style="display:none;" id="table_name" value="tbl_organization">  
<input type="text" style="display:none;" id="pri_col" value="org_id">


<?php $this->load->view('footer.php'); ?>

<script type="text/javascript">


  function addRowOrgEmail(emailval = "",rowid = ""){

     var style = "";


    var orgEmailData = '<tr style="line-height: 3;'+style+'" class="project_images"><td style="width: 90%;"><input type="email" name="email_id[]" id="email_id'+rowid+'" class="form-control" value="'+emailval+'"></td><td align="center" style="width: 150px;"><i class="fa fa-trash" aria-hidden="true" style="font-size: 19px;" onclick="removeRows(this)"></i></td></tr>';
    
    $('#addrowbox').append(orgEmailData);

   }

  function addRowOrgPhone(phoneval = "",prowid = ""){
    
	
    var  style = "";

    var orgPhoneData = '<tr style="line-height: 3;'+style+'" class="project_images"><td style="width: 90%;"><input type="text" name="phone_no[]" id="phone_no'+prowid+'" class="form-control" value="'+phoneval+'"></td><td align="center" style="width: 150px;"><i class="fa fa-trash" aria-hidden="true" style="font-size: 19px;" onclick="removeRows(this)"></i></td></tr>';
     $('#addrowboxp').append(orgPhoneData);

   }
 
   function removeRows(ths){
     $(ths).parent().parent().remove();
   }


function chkCompName(value)
{
   
   /*var ur = "ajax_chkcompany";
   //alert(value);
   
   $.ajax({

     url:ur,
     type:"POST",
     data:{'val':value},
     success:function(data){
       //console.log(data);
   
            if(data == 1){
              $('#resultarea').html("Sorry. This Organization already exist");
              $('#formsave').attr('disabled',true);
              return false;
            } else {
              $('#resultarea').html("");
              $('#formsave').attr('disabled',false);
            }              
        }

    });*/
  
  }



function addRowComphone(phoneval = "",prowid = "")
{

    var style = "";

    var cphoneData = '<tr style="line-height: 3;'+style+'" class="project_images"><td style="width: 90%;"><input type="text" name="cphone_no[]" id="cphone_no'+prowid+'" class="form-control" value="'+phoneval+'"></td><td align="center" style="width: 150px;"><i class="fa fa-trash" aria-hidden="true" style="font-size: 19px;" onclick="removeRows(this)"></i></td></tr>';
     $('#cntaddrowboxp').append(cphoneData);

}

function addRowCompemail(emailval = "",rowid = "")
{
  
    var  style = "";

    var emailData = '<tr style="line-height: 3;'+style+'" class="project_images"><td style="width: 90%;"><input type="email" name="cemail_id[]" id="cemail_id'+rowid+'" class="form-control" value="'+emailval+'"></td><td align="center" style="width: 150px;"><i class="fa fa-trash" aria-hidden="true" style="font-size: 19px;" onclick="removeRows(this)"></i></td></tr>';
    
    $('#cntaddrowbox').append(emailData);

}

</script>

<script type="text/javascript">
	  
     
      var json_orgnization = JSON.parse($('#json_orgnization').val());
      //console.log(json_orgnization);
      var data = {countries:json_orgnization};
        typeof $.typeahead === 'function' && $.typeahead({
            input: ".orgnizationjs-typeahead",
            minLength: 1,
            order: "asc",
            maxItemPerGroup: 3,
            emptyTemplate: " <b style='color:blue'>{{query}}</b> will be added as a new organization !",
            source: {
              country: {
                data: data.countries
              },
            },
            callback: {
              onClickAfter: function (node, a, item, event) {
                event.preventDefault();
                var splitjs = item.display.split('^');
                var sindex  = "";
                  if(splitjs.indexOf(1)==-1)
                    sindex = splitjs[1];
                    ur = "ajaxget_organizationAlldata";
                    $.ajax({
                        type : "POST",
                        url  :  ur,
                        data :  {'id':sindex}, // serializes the form's elements.
                          success : function (data) {
                          // alert(data); // show response from the php script.
                          if(data != false){
                            console.log(data);
                            data = JSON.parse(data);
                            $('#oldorgid').val(data.org_id);
                            $('#org_name').val(data.org_name);
                            $('#website').val(data.website);

                                 if(data.email != ""){
                                    j_oemail = JSON.parse(data.email);
                                       if(j_oemail != ""){
                                        for(var i=0;i<j_oemail.length;i++){
                                          if(i == 0)
                                            $('#email_id0').val(j_oemail[0]);
                                          else
                                            addRowOrgEmail(j_oemail[i],i);

                                     }
                                    }
                                  }


                                  if(data.phone_no != ""){
                                    j_ophone = JSON.parse(data.phone_no);
                                  if(j_ophone != ""){
                                    for(var i=0;i<j_ophone.length;i++){
                                      if(i == 0)
                                       $('#phone_no0').val(j_ophone[0]);
                                      else
                                       addRowOrgPhone(j_ophone[i],i);
                                      
                                      }
                                    }
                                  }

                              $('#address').val(data.address);
            								  $('#city').val(data.city);
            								  $('#state_id').val(data.state_id).trigger("chosen:updated");
            								  $('#pin_code').val(data.pin_code);
            								  $('#country_id').val(data.country).trigger("chosen:updated");
                          }
                        }
                      });          
                    $('.js-result-container').text('');
                },
                onSearch:function (node, query) {
                 console.log(node);
                  $('#oldorgid').val("");
                  $('.project_images').remove();
                  $('#website').val("");
                  $('#email_id0').val("");
                  $('#phone_no0').val("");
                  $('#address').val("");
                  $('#city').val("");
                  $('#state_id').val("").trigger("chosen:updated");
                  $('#pin_code').val("");
                  $('#country_id').val("").trigger("chosen:updated");
              },
                onResult: function (node, query, obj, objCount) {
                  var text = "";
                  if(query !== ""){
                    text = objCount + ' elements matching "' + query + '"';
                  }
                   console.log(node);
                   $("#newidorg").css("display","none");
                  if(objCount == 0)
                   $("#newidorg").css("display","inline");
                   $('.js-result-container').text(text);
                }
             },
            // debug: true
        });




       var json_contact     = JSON.parse($('#json_contact').val());
       //console.log(json_contact);
       var data = {countries:json_contact};

        typeof $.typeahead === 'function' && $.typeahead({
            input: ".js-typeahead",
            minLength: 1,
            order: "asc",
            maxItemPerGroup: 3,
            emptyTemplate: " <b style='color:blue'>{{query}}</b> will be added as a new contact !",
            source: {
                country: {
                    data: data.countries
                },
            },
            callback: {
                onClickAfter: function (node, a, item, event) {
                    event.preventDefault();

                    var splitjs = item.display.split('^');
                    var sindex  = "";
                    if(splitjs.indexOf(1)==-1)
                      sindex = splitjs[1];

                      ur = "ajaxget_contactAlldata";
                      $.ajax({
                          type : "POST",
                          url  :  ur,
                          data :  {'id':sindex}, // serializes the form's elements.
                            success : function (data) {
                             // alert(data); // show response from the php script.
                             if(data != false){
                                 console.log(data);
                                 data = JSON.parse(data);

                                 $("#newid").css("display","none");
                                 $('#oldcontact_id').val(data.contact_id);
							                   $('#contact_name').val(data.contact_name);
                                 $('#designation').val(data.designation);
                                
                                  if(data.cemail != ""){
                                    j_email = JSON.parse(data.cemail);
                                    console.log(j_email);
                                       if(j_email != ""){
                                        for(var i=0;i<j_email.length;i++){
                                          if(i == 0)
                                            $('#cemail_id0').val(j_email[0]);
                                          else
                                            addRowCompemail(j_email[i],i);

                                     }
                                    }
                                  }

                                   if(data.cphone != ""){
                                    j_phone = JSON.parse(data.cphone);
                                    if(j_phone != ""){
                                    for(var i=0;i<j_phone.length;i++){
                                      if(i == 0)
                                       $('#cphone_no0').val(j_phone[0]);
                                      else
                                       addRowComphone(j_phone[i],i);

                                      }
                                    }
                                  }
  
                              }
                          }
                      });          
                    $('.js-result-container').text('');
               },
                onSearch:function (node, query) {
                  console.log(node);
                  if(query != "")
                  $("#newid").css("display","inline");
                  $('.project_images').remove();
                  $('#oldcontact_id').val("");
                  $('#designation').val("");
                  $('#cemail_id0').val("");
                  $('#cphone_no0').val("");
        
                },
                onResult: function (node, query, obj, objCount) {
                    var text = "";
                    if (query !== "") {
                        text = objCount + ' elements matching "' + query + '"';
                    }
                   console.log(node);
                   $("#newid").css("display","none");
                   if(objCount == 0)
                    $("#newid").css("display","inline");
                    $('.js-result-container').text(text);
               }
            },
           // debug: true
        });



function org_contactData(cntid)
{
  //alert(cntid);
  ur = "ajaxget_contactData";
    $.ajax({
        type : "POST",
        url  :  ur,
        data :  {'id':cntid}, // serializes the form's elements.
          success : function (data) {
           // alert(data); // show response from the php script.
           if(data != false){
               console.log(data);
               data = JSON.parse(data);
               $('#contact_name').val(data.contact_name);
               $('#designation').val(data.designation);
               //$('#cemail_id0').val(data.email);
               //$('#cphone_no0').val(data.phone);
               if(data.email != ""){
                j_email = JSON.parse(data.email);
               // console.log(j_email);
                   if(j_email != ""){
                    for(var i=0;i<j_email.length;i++){
                      if(i == 0)
                        $('#cemail_id0').val(j_email[0]);
                      else
                        addRowCompemail(j_email[i],i);

                 }
                }
              }

               if(data.phone != ""){
                j_phone = JSON.parse(data.phone);
                if(j_phone != ""){
                for(var i=0;i<j_phone.length;i++){
                  if(i == 0)
                   $('#cphone_no0').val(j_phone[0]);
                  else
                   addRowComphone(j_phone[i],i);

                  }
                }
              }

           }
        }
    });   
}
</script>