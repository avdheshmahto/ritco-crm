
<?php $this->load->view('javascriptPage'); ?>

<div class="footer-bg">
<div class="row">
<div class="col-sm-9">
Copyright &copy; <?php echo date('Y');?> <a target="_blank" href="http://www.techvyas.com/"> Tech Vyas Solutions Pvt. Ltd. </a> All rights reserved.
</div>
<div class="col-sm-3">


<?php

$crdate = date('Y-m-d');
$login_id=$this->session->userdata('user_id');
 

//$tskrmndr=$this->db->query("SELECT * FROM tbl_task WHERE SUBSTRING_INDEX(SUBSTRING_INDEX(reminder_date, ' ', 1), ' ', -1) = '$crdate' ");
$tskrmndr=$this->db->query("SELECT * FROM tbl_task WHERE task_status !='21'  ");

    foreach($tskrmndr->result() as $getTaskData) 
    {

      // if($getTaskData->last_update != '')
      // {
      
        $date = explode(" ", $getTaskData->last_update);
        $fdate = date('Y-m-d', strtotime($date[0]. ' + 5 days'));

        if($crdate >= $fdate )
        {
            $getSnid = $getTaskData->seen_id;
            $SnId = explode(',', $getSnid);
            $LgnUsr = explode(',', $login_id);
            $resultsnid=array_intersect($SnId,$LgnUsr);

            $dsms_date = $getTaskData->dismiss_date;
            $sqlTime = date('Y-m-d H:i:s', strtotime('+24 hours', strtotime($dsms_date)));

            date_default_timezone_set("Asia/Kolkata");
            $dtTime = date('Y-m-d H:i:s');

            if(strtotime($sqlTime) <= strtotime($dtTime))
            {
                $getDismisId = '';
                $DismissId = explode(',', $getDismisId);
                $dismisresult=array_diff($resultsnid,$DismissId);        
                //print_r($notifyid);                
            }
            else
            {
                $getDismisId = $getTaskData->dismiss;
                $DismissId = explode(',', $getDismisId);
                $dismisresult=array_diff($resultsnid,$DismissId);        
                //print_r($notifyid);                 
            }

            $getSize = sizeof($dismisresult);

            if($getSize > 0)
            {
              $RmndrCnt ++;
            }
        }
      //}
    }

/*$leadrmndr=$this->db->query("SELECT * FROM tbl_leads WHERE SUBSTRING_INDEX(SUBSTRING_INDEX(closuredate, ' ', 1), ' ', -1) = '$crdate' ");
//$LeadCnt=$leadrmndr->num_rows();
   foreach($leadrmndr->result() as $getLeadData) 
    {

        $getSnid = $getLeadData->seen_id;
        $SnId = explode(',', $getSnid);
        $LgnUsr = explode(',', $login_id);
        $resultsnid=array_intersect($SnId,$LgnUsr);

        $getDismisId = $getLeadData->dismiss;
        $DismissId = explode(',', $getDismisId);
        $dismisresult=array_diff($resultsnid,$DismissId);        
        //print_r($notifyid);
        $getSize = sizeof($dismisresult);

        if($getSize > 0){
          $LeadCnt ++;
        }
    }*/

//$count=$RmndrCnt + $LeadCnt;
if($RmndrCnt > 0)
{
  $i=$RmndrCnt;      
}
else
{
 $i=0;       
}


?>

<!--Reminders ----->
<div class="row chat-window col-xs-5 col-md-3" id="chat_window_1" style="margin-left:10px;">
<div class="col-xs-12 col-md-12" style="margin: 0 0 0 15px;">
<div class="panel panel-default_bg rem">
<div class="panel-heading top-bar">
<div class="col-md-8 col-xs-8">
<h3 class="panel-title"> Reminders <span class="badge bg-lightred-f"><?=$i?></span></h3> 
</div>
<div class="col-md-4 col-xs-4" style="text-align: right;">
<a href="#"><span id="minim_chat_window" class="glyphicon glyphicon-minus icon_minim"></span></a>
<!-- <a href="#"><span class="glyphicon glyphicon-remove icon_close" data-id="chat_window_1"></span></a> -->
</div>
</div>
<div class="panel-body msg_container_base">
<ul class="list-group no-radius no-border" id="mails-list">

<?php 

//$task=$this->db->query("SELECT * FROM tbl_task WHERE SUBSTRING_INDEX(SUBSTRING_INDEX(reminder_date, ' ', 1), ' ', -1) = '$crdate' ");
$task=$this->db->query("SELECT * FROM tbl_task WHERE task_status !='21'  ");


foreach($task->result() as $getTask) 
{

  // if($getTask->last_update != '')
  // {
  
    $date = explode(" ", $getTask->last_update);
    $fdate = date('Y-m-d', strtotime($date[0]. ' + 5 days'));

    if($crdate >= $fdate )
    {

      $getSnid = $getTask->seen_id;
      $SnId = explode(',', $getSnid);
      $LgnUsr = explode(',', $login_id);
      $resultsnid=array_intersect($SnId,$LgnUsr);

      $dsms_dates = $getTask->dismiss_date;
      $sqlTimes = date('Y-m-d H:i:s', strtotime('+24 hours', strtotime($dsms_dates)));

      date_default_timezone_set("Asia/Kolkata");
      $dtTimes = date('Y-m-d H:i:s');

      if(strtotime($sqlTimes) <= strtotime($dtTimes))
      {
        $getDismisId = '';
        $DismissId = explode(',', $getDismisId);
        $dismisresult=array_diff($resultsnid,$DismissId);        
        //print_r($notifyid);
      }
      else
      {
        $getDismisId = $getTask->dismiss;
        $DismissId = explode(',', $getDismisId);
        $dismisresult=array_diff($resultsnid,$DismissId);        
        //print_r($notifyid);
      }


  $getSize = sizeof($dismisresult);
  if($getSize > 0)
  {


    $lead=$this->db->query("select * from tbl_leads where lead_id = '$getTask->lead_id' ");
    $getLead=$lead->row();
    $leadno=$getLead->lead_number;

    $townr=$this->db->query("select * from tbl_user_mst where user_id='".$getTask->maker_id."' ");
    $getOwnr=$townr->row();

    $tname=$this->db->query("select * from tbl_master_data where serial_number = '$getTask->task_name' ");
    $getTname=$tname->row();
    $tnm=$getTname->keyvalue;


    $sqlprio=$this->db->query("select * from tbl_master_data where serial_number ='".$getTask->priority."'");
    $progress_v = $sqlprio->row();

    $name=$this->db->query("select * from tbl_master_data where serial_number ='".$getTask->task_name."'");
    $getTname = $name->row();

    $sqltask_status=$this->db->query("select * from tbl_master_data where serial_number ='".$getTask->task_status."'");
    $sqltask_status1 = $sqltask_status->row();

    $sqltask_ass=$this->db->query("select * from tbl_user_mst where user_id ='".$getTask->user_resp."'");
    $sqltask_ass_user = $sqltask_ass->row();

    $sqltask_id=$getTask->task_id; 


$taskViewData = '<section class="block bottom20__" style="padding: 15px;"><header class="head-title"><h2>Task Details</h2></header><div class="entity-detail"><table class="property-table"><tbody><tr><td class="ralign"><span class="title">Task Name :</span></td><td><div class="info">'.$getTname->keyvalue.'</div></td></tr><tr><td class="ralign"><span class="title">Assign To :</span></td><td><div class="info">'.$sqltask_ass_user->user_name.'</div></td></tr><tr><td class="ralign"><span class="title">Task Owner :</span></td><td><div class="info">'.$getOwnr->user_name.'</div></td></tr></tbody></table></div></section><section class="block bottom20__" style="padding: 15px;"><header class="head-title"><h2>Additional Information</h2></header><div class="entity-detail"><table class="property-table"><tbody><tr><td class="ralign"><span class="title">Due Date:</span></td><td><div class="info">'.$getTask->date_due.'</div></td></tr><tr><td class="ralign"><span class="title"> Progress % : </span></td><td><div class="info">'.$getTask->progress_percnt.'</div></td></tr><tr><td class="ralign"><span class="title"> Priority : </span></td><td><div class="info">'.$progress_v->keyvalue.'</div></td></tr><tr><td class="ralign"><span class="title"> Status : </span></td><td><div class="info">'.$sqltask_status1->keyvalue.'</div></td></tr></tbody></table></div></section><div class="modal-footer"><a href="#" class="btn btn-default pull-left" data-dismiss="modal" onclick="getViewTaskPage('.$sqltask_id.');">View Task >></a><button type="button" class="btn btn-default" data-dismiss="modal">Close</button></div>';

//<tr><td class="ralign"><span class="title">  Reminder: </span></td><td><div class="info">'.$getTask->reminder_date.'</div></td></tr>
?>


<li class="list-group-item b-primary__">
<div class="media">
<!-- <p>TODAY</p>	 -->
  <div class="rmndrtask">
<div class="media-body">
<div class="media-heading m-0"> <a href="#" onClick="viewNotify(this);" data-toggle="modal" data-target="#myNotifyView" arrt='<?=json_encode($taskViewData);?>' class="mr-5"><?php echo $tnm." (".$sqltask_ass_user->user_name.")"; ?></a></div>
<small>Tasks - <?=$getTask->date_due; ?></small>  
<div class="dismiss task_dismiss" id="<?=$getTask->task_id; ?>">Dismiss</div>
</div>
  </div>
</div>
</li>
<?php } ?>
<?php } ?>
<?php //} ?>
<?php } ?>

<?php 

/*$lead=$this->db->query("SELECT * FROM tbl_leads WHERE SUBSTRING_INDEX(SUBSTRING_INDEX(closuredate, ' ', 1), ' ', -1) = '$crdate' ");


foreach ($lead->result() as $getLead) 
{ 
    $getSnid = $getLead->seen_id;
    $SnId = explode(',', $getSnid);
    $LgnUsr = explode(',', $login_id);
    $resultsnid=array_intersect($SnId,$LgnUsr);

    $getDismisId = $getLead->dismiss;
    $DismissId = explode(',', $getDismisId);
    $dismisresult=array_diff($resultsnid,$DismissId);        
    //print_r($notifyid);
    $getSize = sizeof($dismisresult);

if($getSize > 0){*/

?>

<!-- <li class="list-group-item b-primary__">
<div class="media">
<p>TODAY</p>	
  <div class="rmndrlead">
<div class="media-body">
<div class="media-heading m-0"> <a href="<?php //echo base_url('lead/Lead/view_lead?id=');?><?=$getLead->lead_id; ?>" class="mr-5"><?php //echo "Closure Lead (".$getLead->lead_number.")"; ?></a></div>
<small>Lead - <?=$getLead->closuredate; ?></small>  
<div class="dismiss lead_dismiss" id="<?=$getLead->lead_id; ?>">Dismiss</div>
</div>
  </div>
</div>
</li> -->
<?php // } ?>	
<?php // } ?>



</ul>
</div>
</div>
</div>
</div>
<!--Reminders close----->


</div>
</div>
</div>





<script>window.jQuery || document.write('<script src="<?=base_url();?>assets/assets/js/vendor/jquery/jquery-1.11.2.min.js"><\/script>')</script>

<!--- typehead js-->
<script src="<?=base_url('customjs/jquery.typeahead.js');?>"></script>
<!---close typehead js-->

<script src="<?=base_url();?>assets/assets/js/vendor/bootstrap/bootstrap.min.js"></script>
<script src="<?=base_url();?>assets/assets/js/vendor/jRespond/jRespond.min.js"></script>
<script src="<?=base_url();?>assets/assets/js/vendor/d3/d3.min.js"></script>
<script src="<?=base_url();?>assets/assets/js/vendor/d3/d3.layout.min.js"></script>
<script src="<?=base_url();?>assets/assets/js/vendor/rickshaw/rickshaw.min.js"></script>
<script src="<?=base_url();?>assets/assets/js/vendor/sparkline/jquery.sparkline.min.js"></script>
<script src="<?=base_url();?>assets/assets/js/vendor/slimscroll/jquery.slimscroll.min.js"></script>
<script src="<?=base_url();?>assets/assets/js/vendor/animsition/js/jquery.animsition.min.js"></script>
<script src="<?=base_url();?>assets/assets/js/vendor/daterangepicker/moment.min.js"></script>
<script src="<?=base_url();?>assets/assets/js/vendor/daterangepicker/daterangepicker.js"></script>
<script src="<?=base_url();?>assets/assets/js/vendor/screenfull/screenfull.min.js"></script>
<script src="<?=base_url();?>assets/assets/js/vendor/flot/jquery.flot.min.js"></script>

<script src="<?=base_url();?>assets/assets/js/vendor/flot-spline/jquery.flot.spline.min.js"></script>
<script src="<?=base_url();?>assets/assets/js/vendor/easypiechart/jquery.easypiechart.min.js"></script>
<script src="<?=base_url();?>assets/assets/js/vendor/raphael/raphael-min.js"></script>
<script src="<?=base_url();?>assets/assets/js/vendor/morris/morris.min.js"></script>
<script src="<?=base_url();?>assets/assets/js/vendor/owl-carousel/owl.carousel.min.js"></script>
<script src="<?=base_url();?>assets/assets/js/vendor/datetimepicker/js/bootstrap-datetimepicker.min.js"></script>
<script src="<?=base_url();?>assets/assets/js/vendor/datatables/js/jquery.dataTables.min.js"></script>
<script src="<?=base_url();?>assets/assets/js/vendor/datatables/extensions/dataTables.bootstrap.js"></script>
<script src="<?=base_url();?>assets/assets/js/vendor/slider/bootstrap-slider.min.js"></script>
<!-- <script src="<?=base_url();?>assets/assets/js/vendor/colorpicker/js/bootstrap-colorpicker.min.js"></script> -->
<script src="<?=base_url();?>assets/assets/js/vendor/touchspin/jquery.bootstrap-touchspin.min.js"></script>
<script src="<?=base_url();?>assets/assets/js/vendor/daterangepicker/moment.min.js"></script>
<script src="<?=base_url();?>assets/assets/js/vendor/chosen/chosen.jquery.min.js"></script>
<script src="<?=base_url();?>assets/assets/js/vendor/filestyle/bootstrap-filestyle.min.js"></script>
<script src="<?=base_url();?>assets/assets/js/vendor/summernote/summernote.min.js"></script>
<script src="<?=base_url();?>assets/assets/js/vendor/fullcalendar/fullcalendar.min.js"></script>
<script src="<?=base_url();?>assets/assets/js/vendor/coolclock/coolclock.js"></script>
<script src="<?=base_url();?>assets/assets/js/vendor/coolclock/excanvas.js"></script>
<!-- <script src="<?=base_url();?>assets/assets/datetimepicker/jquery.datetimepicker.js"></script> -->
<!--/ vendor javascripts -->

<!-- ============== Custom JavaScripts =============== -->
<script src="<?=base_url();?>assets/assets/js/main.js"></script>
<!--/ =============Custom Javascripts ================ -->

<!-- =========================== Page Specific Scripts ====================== -->
<script src="http://ajax.aspnetcdn.com/ajax/jquery.validate/1.15.0/jquery.validate.js"></script>
<script src="<?=base_url();?>customjs/customjscode.js"></script>


<script>
    $(window).load(function(){
        $('#ex1').slider({
            formatter: function(value) {
                return 'Current value: ' + value;
            }
        });
        $("#ex1").on("slide", function(slideEvt) {
            $("#ex1SliderVal").text(slideEvt.value);
        });

        $("#ex2, #ex3, #ex4, #ex5").slider();

        //load wysiwyg editor
        $('#summernote').summernote({
            height: 100   //set editable area's height
        });
        //*load wysiwyg editor
    });
</script>
<!--/ Page Specific Scripts -->


<script type="text/javascript">
//manage page search script//

function doSearch() {
  var $rows = $('#dataTable tr');
  var val = $.trim($('#searchTerm').val()).replace(/ +/g, ' ').toLowerCase();
    $rows.show().filter(function() {
        var text = $(this).text().replace(/\s+/g, ' ').toLowerCase();
         if(text == "" || val == ''){
           $(this).css('color','black');
          } else {
         
            $(this).css('color','green');
        }
        console.log(val);
        return !~text.indexOf(val);
    }).hide();
}

// ends

//-===header search=================
function doSearch1() {
  var $rows = $('#dataTable tr');
  var val = $.trim($('#searchTerm1').val()).replace(/ +/g, ' ').toLowerCase();
    $rows.show().filter(function() {
        var text = $(this).text().replace(/\s+/g, ' ').toLowerCase();
         if(text == "" || val == ''){
           $(this).css('color','black');
          } else {
         
            $(this).css('color','green');
        }
        console.log(val);
        return !~text.indexOf(val);
    }).hide();
}
//////////////////close//////////////////////

function loadFile(ths) {
  if (ths.files && ths.files[0]) {
        var reader = new FileReader();
        reader.onload = function (e) {
          $('#image').attr('src', e.target.result);
            };
          reader.readAsDataURL(ths.files[0]);
        }
}


 //=============start url get entries code==================

$("#entries").change(function()
    {
      var value=$(this).val();
      var pageurl  = $(this).attr('url');
      
      url = pageurl+"&entries="+value;
      window.location.href = url;
    });

$("#filter").change(function()
    {
      var value=$(this).val();
      var pageurl  = $(this).attr('url');
      
      url = pageurl+"&filter="+value;
      window.location.href = url;
    });



/*$("#userleadstatus").change(function()
    {
      var value=$(this).val();
      var pageurl  = $(this).attr('url');
      
      url = pageurl+"&userleadstatus="+value;
      window.location.href = url;
    });


$("#userleadstage").change(function()
    {
      var value=$(this).val();
      var pageurl  = $(this).attr('url');
      
      url = pageurl+"&userleadstage="+value;
      window.location.href = url;
    });*/


$(document).ready(function(){
  
  $('.msg_container_base').css('display','none');
  $('.glyphicon-minus').addClass('glyphicon-plus').removeClass('glyphicon-minus');
  $('.icon_minim').addClass('panel-collapsed');

  $('.summernote').summernote({
            height: 100   //set editable area's height
        });
  $('#summernotess').summernote({
            height: 100   //set editable area's height
        });
  $('#summernoteorgz').summernote({
            height: 100   //set editable area's height
        });

  $('#summernotecnt').summernote({
            height: 100   //set editable area's height
        });
  
   $(document).delegate("#formreset","click",function(){
      //alert('ssdfsdf');
      var formid =  $('#formreset').attr('formid');
      //alert(formid);
      $(formid)[0].reset();
      
      $(".hiddenField").val('');
      $(".chosen-select").trigger('chosen:updated');
      $(".top_title").html('Add');
      //$(formid+" :input").prop("readonly", false);
      //$("#button").css("display", "block");
      $(".error").html('');
      $('.project_images').remove();
      $('#summernote').code('');
      $('#summernotess').code('');
      $('#summernoteorgz').code('');
      $('#summernotecnt').code('');
      $('.summernote').code('');

      //CKEDITOR.instances['tem'].setData("");
      //$('#image').attr('src',url);

    });

    $(document).delegate("#formresetstage","click",function(){
      //alert('ssdfsdf');
      var formid =  $('#formresetstage').attr('formid');
      //alert(formid);
      $(formid)[0].reset();

      $("#leadwon").css("display", "none");
      $("#leadspecial").css("display", "none");
      $("#resultstage").html('');
      $("#stagesave").prop('disabled', false);
    
    });

    $(document).delegate("#formresetstate","click",function(){
      //alert('ssdfsdf');
      var formid =  $('#formresetstate').attr('formid');
      //alert(formid);
      $(formid)[0].reset();

      $("#docket").css("display", "none");
    
    });    

    $(document).delegate("#formresetstatus","click",function(){
      //alert('ssdfsdf');
      var formid =  $('#formresetstatus').attr('formid');
      //alert(formid);
      //$(formid)[0].reset();

      $("#new_status").val("");
    
    }); 


   });


  
//=============end url get entries code==================


// ===============starts here this javascript code is for single delete ===============


$(function() {
$(document).delegate(".delbutton_orgz","click",function(){  
 //Save the link in a variable called element
 var element = $(this);
 //Find the id of the link that was clicked
 var del_id = element.attr("id");
 //Built a url to send
 var info = 'id=' + del_id;

  if(confirm("Are You Sure? You want to delete !"))
   {
    $.ajax({
     type: "GET",
     url: "delete_data_orgz",
     data: info,
     success: function(){
     }
  });
$(this).parents(".record").animate({ backgroundColor: "#fbc7c7" }, "fast").animate({ opacity: "hide" }, "slow");

 }
return false;
});
});



$(function() {
$(document).delegate(".delbutton_cntct","click",function(){  
 //Save the link in a variable called element
 var element = $(this);
 //Find the id of the link that was clicked
 var del_id = element.attr("id");
 //Built a url to send
 var info = 'id=' + del_id;

  if(confirm("Are You Sure? You want to delete !"))
   {
    $.ajax({
     type: "GET",
     url: "delete_data_cntct",
     data: info,
     success: function(){
     }
  });
$(this).parents(".record").animate({ backgroundColor: "#fbc7c7" }, "fast").animate({ opacity: "hide" }, "slow");

 }
return false;
});
});



$(function() {
$(document).delegate(".delbutton_lead","click",function(){  
 //Save the link in a variable called element
 var element = $(this);
 //Find the id of the link that was clicked
 var del_id = element.attr("id");
 //Built a url to send
 var info = 'id=' + del_id;

  if(confirm("Are You Sure? You want to delete !"))
   {
    $.ajax({
     type: "GET",
     url: "delete_data_lead",
     data: info,
     success: function(){
     }
  });
$(this).parents(".record").animate({ backgroundColor: "#fbc7c7" }, "fast").animate({ opacity: "hide" }, "slow");

 }
return false;
});
});



$(function() {
$(document).delegate(".delbutton_task","click",function(){  
 //Save the link in a variable called element
 var element = $(this);
 //Find the id of the link that was clicked
 var del_id = element.attr("id");
 //Built a url to send
 var info = 'id=' + del_id;

  if(confirm("Are You Sure? You want to delete !"))
   {
    $.ajax({
     type: "GET",
     url: "delete_data_task",
     data: info,
     success: function(){
     }
  });
$(this).parents(".record").animate({ backgroundColor: "#fbc7c7" }, "fast").animate({ opacity: "hide" }, "slow");

 }
return false;
});
});


$(function() {
$(document).delegate(".delbutton_user","click",function(){  
 //Save the link in a variable called element
 var element = $(this);
 //Find the id of the link that was clicked
 var del_id = element.attr("id");
 //Built a url to send
 var info = 'id=' + del_id;

  if(confirm("Are You Sure? You want to delete !"))
   {
    $.ajax({
     type: "GET",
     url: "delete_data_user",
     data: info,
     success: function(){
     }
  });
$(this).parents(".record").animate({ backgroundColor: "#fbc7c7" }, "fast").animate({ opacity: "hide" }, "slow");

 }
return false;
});
});

  
$(function() {
$(document).delegate(".delbutton","click",function(){  
 //Save the link in a variable called element
 var element = $(this);
 //Find the id of the link that was clicked
 var del_id = element.attr("id");
 //Built a url to send
 var info = 'id=' + del_id;

  if(confirm("Are You Sure? You want to delete !"))
   {
    $.ajax({
     type: "GET",
     url: "delete_data",
     data: info,
     success: function(){
     }
  });
$(this).parents(".record").animate({ backgroundColor: "#fbc7c7" }, "fast").animate({ opacity: "hide" }, "slow");

 }
return false;
});
});

  //------------------------Multiple Delete Single  table---------------------
  
  //$('.delete_all').on('click', function(e) { 
    $(document).delegate(".delete_all_","click",function(e){
    var allVals = [];  
    $(".sub_chk:checked").each(function() {  
      allVals.push($(this).attr('data-id'));
    });  

    //alert(allVals.length); return false;  
    if(allVals.length <=0)  
    {  
      alert("Please select row.");  
    }  
    else {  
      //$("#loading").show(); 
      WRN_PROFILE_DELETE = "Are you sure? You want to delete this row!";  
      var check = confirm(WRN_PROFILE_DELETE);  
      //alert(check);
      if(check == true){  
        //for server side
        var table_name=document.getElementById("table_name").value;
        var pri_col=document.getElementById("pri_col").value;
        var join_selected_values = allVals.join(","); 
        //alert(join_selected_values);
  
        $.ajax({   
          type: "POST",  
          url: "multiple_delete_table",  
          cache:false,  
          data: "ids="+join_selected_values+"&table_name="+table_name+"&pri_col="+pri_col,  
          //alert(data);
          success: function(response)  
          {   
            $("#loading").hide();  
            $("#msgdiv").html(response);
           //referesh table
          }   
        });
      //for client side
        $.each(allVals, function( index, value ) {
          $('table tr').filter("[data-row-id='" + value + "']").remove();
        });
        

      }  
    }  
  });

// ends here this javascript code is for multiple delete 

</script>

<!-- <script type="text/javascript" src="<?=base_url();?>assets/assets/scrollbar/scrollBar.js"></script>
<script type="text/javascript">
       $(".sb-container").scrollBox();
</script> -->

		

<!--Reminders css----->
<style>
.panel-default_bg{background-color:#F5F5F5;}

.panel{
    margin-bottom: 0px;
}
.chat-window{
    bottom:0;
    position:fixed;
    float:right;
    margin-left:10px;
	z-index:999999999;
	right:0px;
}
.chat-window > div > .panel{
    border-radius: 5px 5px 0 0;
}
.icon_minim{
    padding:2px 10px;
}
.msg_container_base{
  background: #F5F5F5;
  margin: 0;
  padding: 0 0px 21px;
  max-height:300px;
  overflow-x:hidden;
}
.top-bar {
  background: #F5F5F5;
  color:#333;
  padding: 10px;
  position: relative;
  overflow: hidden;
}
.msg_receive{
    padding-left:0;
    margin-left:0;
}
.msg_sent{
    padding-bottom:20px !important;
    margin-right:0;
}
.messages {
  background: white;
  padding: 10px;
  border-radius: 2px;
  box-shadow: 0 1px 2px rgba(0, 0, 0, 0.2);
  max-width:100%;
}
.messages > p {
    font-size: 13px;
    margin: 0 0 0.2rem 0;
  }
.messages > time {
    font-size: 11px;
    color: #ccc;
}
.msg_container {
    padding: 10px;
    overflow: hidden;
    display: flex;
}
/*img {
    display: block;
    width: 100%;
}*/
.avatar {
    position: relative;
}
.base_receive > .avatar:after {
    content: "";
    position: absolute;
    top: 0;
    right: 0;
    width: 0;
    height: 0;
    border: 5px solid #FFF;
    border-left-color: rgba(0, 0, 0, 0);
    border-bottom-color: rgba(0, 0, 0, 0);
}

.base_sent {
  justify-content: flex-end;
  align-items: flex-end;
}
.base_sent > .avatar:after {
    content: "";
    position: absolute;
    bottom: 0;
    left: 0;
    width: 0;
    height: 0;
    border: 5px solid white;
    border-right-color: transparent;
    border-top-color: transparent;
    box-shadow: 1px 1px 2px rgba(black, 0.2); // not quite perfect but close
}

.msg_sent > time{
    float: right;
}



.msg_container_base::-webkit-scrollbar-track
{
    -webkit-box-shadow: inset 0 0 6px rgba(0,0,0,0.3);
    background-color: #F5F5F5;
}

.msg_container_base::-webkit-scrollbar
{
    width: 12px;
    background-color: #F5F5F5;
}

.msg_container_base::-webkit-scrollbar-thumb
{
    -webkit-box-shadow: inset 0 0 6px rgba(0,0,0,.3);
    background-color: #555;
}

.btn-group.dropup{
    position:fixed;
    left:0px;
    bottom:0;
}

.dismiss {
	background:#fff;
	border:1px solid #f25454;
	color:#f25454;
	font-size:12px;
	text-transform: uppercase;
	padding:0px 10px;
	float:right;
	transition:all 0.3s ease-in-out;
	border-radius:30px;
	line-height:18px;
}

.dismiss:hover {
	background:#f25454;
	color:#fff;
	cursor: pointer;
}

</style>
<!--Reminders css close----->

<!-- Modal Reminders-->
<div class="modal fade" id="myNotifyView" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span> <span class="sr-only">close</span></button>
                <h4 id="modalTitletask1" class="modal-title">View Reminder Details</h4>
            </div>
            <div id="modalBody1" class="modal-body__">
              <div class="modal-body__">
                  <div class="panel-group__ icon-plus" id="accordion" role="tablist" aria-multiselectable="true">
                    
                    <div>
                    <article class="page-content" id="viewNotifyDetails" style="font-size: 15px;">
                    </article>
                   </div>
                   
                 </div>
                </div>
             </div>
           <!-- <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
           </div> -->
        </div>
    </div>
</div>
<!-- /Modal Reminders close-->

<!--Reminders js----->
<script>
$(document).on('click', '.icon_minim', function (e) {  
    var $this = $(this);
    if (!$this.hasClass('panel-collapsed')) {
        $this.parents('.panel').find('.panel-body').slideUp();
        $this.addClass('panel-collapsed');
        $this.removeClass('glyphicon-minus').addClass('glyphicon-plus');
    } else {
        $this.parents('.panel').find('.panel-body').slideDown();
        $this.removeClass('panel-collapsed');
        $this.removeClass('glyphicon-plus').addClass('glyphicon-minus');
    }
});

$(document).on('click', '.icon_close', function (e) {
    //$(this).parent().parent().parent().parent().remove();
    $( "#chat_window_1" ).remove();
});



function viewNotify(ths){
    //alert(ths);
      var datavalue  = $(ths).attr('arrt');

      $('#viewNotifyDetails').empty().append(JSON.parse(datavalue));
   }



// starts here this javascript code is for dismiss 


$(function() {

 $(document).delegate(".task_dismiss","click",function(){  
 //Save the link in a variable called element
 var element = $(this);
 //Find the id of the link that was clicked
 var del_id = element.attr("id");
 //alert(del_id);
 //Built a url to send
 var info = 'id=' + del_id;

  if(confirm("Are You Sure? You want to dismiss !"))
   {
    $.ajax({
     type: "GET",
     url: "update_task_dismiss_status",
     data: info,
     success: function(){
     }
  });
$(this).parents(".rmndrtask").animate({ backgroundColor: "#fbc7c7" }, "fast").animate({ opacity: "hide" }, "slow");

 }

return false;

});

});




$(function() {

 $(document).delegate(".lead_dismiss","click",function(){  
 //Save the link in a variable called element
 var element = $(this);
 //Find the id of the link that was clicked
 var del_id = element.attr("id");
 //alert(del_id);
 //Built a url to send
 var info = 'id=' + del_id;

  if(confirm("Are You Sure? You want to dismiss !"))
   {
    $.ajax({
     type: "GET",
     url: "update_lead_dismiss_status",
     data: info,
     success: function(){
     }
  });
$(this).parents(".rmndrlead").animate({ backgroundColor: "#fbc7c7" }, "fast").animate({ opacity: "hide" }, "slow");

 }

return false;

});

});

</script>
<!--Reminders js close----->


<!--dateTimePicker js final close-->  
<link rel="stylesheet" type="text/css" href="<?=base_url();?>assets/assets/date-time/jquery.datetimepicker.css"/>
<script src="<?=base_url();?>assets/assets/date-time/jquery.datetimepicker.js"></script>
<script>
$('.datetimepicker_mask').datetimepicker({
  //mask:'9999/19/39 29:59',
});
</script>

<!--dateTimePicker js final close-->



<script type="text/javascript">
// function heartbeat(){
//    setTimeout(function(){
//       $.ajax({ url: "logout", cache: false,
//       success: function(data){
//         //Next beat
//         heartbeat();
//       }, dataType: "json"});
//   }, 300000);//5 Minute(300 seconds)
// }

// $(document).ready(function(){
//     heartbeat();
// });

// window.onbeforeunload = function(){
//     //Ajax request to update the database
//     $.ajax({
//         type: "POST",
//         url: "logout"
//     });
// }

// $(window).on('mouseover', (function () {
//     window.onbeforeunload = null;
// }));
// $(window).on('mouseout', (function () {
//     window.onbeforeunload = ConfirmLeave;
// }));
// function ConfirmLeave() {
//     return "";
// }
// var prevKey="";
// $(document).keydown(function (e) {            
//     if (e.key=="F5") {
//         window.onbeforeunload = ConfirmLeave;
//     }
//     else if (e.key.toUpperCase() == "W" && prevKey == "CONTROL") {                
//         window.onbeforeunload = ConfirmLeave;   
//     }
//     else if (e.key.toUpperCase() == "R" && prevKey == "CONTROL") {
//         window.onbeforeunload = ConfirmLeave;
//     }
//     else if (e.key.toUpperCase() == "F4" && (prevKey == "ALT" || prevKey == "CONTROL")) {
//         window.onbeforeunload = ConfirmLeave;
//     }
//     prevKey = e.key.toUpperCase();
// });
</script>