<?php $this->load->view('header.php'); 
?>

<div id="ajax_content"> <!-- ajax_content -->

<section id="content">
<div class="page page-tables-bootstrap">
<div class="row">
<div class="col-md-12">
<section class="tile">
<div class="pageheader tile-bg">
<div class="p-0 bg-white-">

<div class="btn-toolbar">
<div class="btn-group mr-10">
<span>CALENDAR</span>
</div>
</div>
</div>
</div><!--pageheader close-->

<div class="tile-body p-15">

<div class="row">
<div class="col-sm-2" style="display:none;">
<div class="sidebar-nav">
<div class="navbar navbar-default" role="navigation">

<div class="navbar-collapse collapse sidebar-navbar-collapse">
<div class="tcol bg-tr-white lt b-r">
          <!-- left side body -->
          <div class="p-15- collapse- collapse-xs collapse-sm " id="external-events">
          	<?php
          		 $i=1;
          	     // $first_day_this_month = date('Y-m-01'); // hard-coded '01' for first day
				 // $last_day_this_month  = date('Y-m-t');
               if($this->session->userdata('role') == 1)
               {
               	 $sql = "select * from tbl_task order by date_due DESC";
               }
               else
               {

            	  //==================Software Log===============

				  $in = array();
				  $out = array();

				  $slog = $this->db->query("select * from tbl_software_log where mdl_name='Task' AND slog_name='Task' AND slog_type='User' AND maker_id='".$this->session->userdata('user_id')."' ");
				  $numCnt=$slog->num_rows();

						  foreach($slog->result() as $slogdata)
						  {

						    $oldusr = $slogdata->slog_id;
						    array_push($in,$oldusr);

						  }
						  
						  if($numCnt > 0)
						    {
						    	$oldData= implode(', ', $in);
						    }
						    else
						    {
						    	$oldData='9999999';
						    }

				    $slog1 = $this->db->query("select * from tbl_software_log where mdl_name='Task' AND slog_name='Task' AND slog_type='User' AND new_id='".$this->session->userdata('user_id')."' ");
				    $numCnt1=$slog1->num_rows();

				    	  foreach($slog1->result() as $slogdata1)
						  {
						    $newusr = $slogdata1->slog_id; 
						    array_push($out,$newusr); 	    
						  }
						  
						  if($numCnt1 > 0)
						    {
						    	$newData= implode(', ', $out);
						    }
						    else
						    {
						    	$newData='9999999';
						    }

			  //======================End=========================
               	 $sql = "select * from tbl_task where status='A' AND (maker_id='".$this->session->userdata('user_id')."' OR brnh_id='".$this->session->userdata('brnh_id')."' OR user_resp='".$this->session->userdata('user_id')."') order by date_due DESC";
               }
                              

          	   $task = $this->db->query($sql);
               $taskcalender1 = array();
               //$i = 0;
          		 foreach($task->result() as $getTask) {

                $sqlprio=$this->db->query("select * from tbl_master_data where serial_number ='".$getTask->priority."'");
                $progress_v = $sqlprio->row();

                $name=$this->db->query("select * from tbl_master_data where serial_number ='".$getTask->task_name."'");
                $getTname = $name->row();

                $sqltask_status=$this->db->query("select * from tbl_master_data where serial_number ='".$getTask->task_status."'");
                $sqltask_status1 = $sqltask_status->row();

                $sqltask_ass=$this->db->query("select * from tbl_user_mst where user_id ='".$getTask->user_resp."'");
                $sqltask_ass_user = $sqltask_ass->row();

                $ownr=$this->db->query("select * from tbl_user_mst where user_id ='".$getTask->maker_id."'");
                $getOwnr = $ownr->row();

                $sqltask_id=$getTask->task_id;                
               
               /////// task status //////
                if($getTask->task_status == 19)
                 $statuscolor = ' draggabledClass';
                elseif($getTask->task_status == 20)
                 $statuscolor = 'b-danger draggabledClass';
                elseif($getTask->task_status == 21)
                 $statuscolor = 'b-warning draggabledClass';
                elseif($getTask->task_status == 33)
                 $statuscolor = 'b-success draggabledClass';
                else
                 $statuscolor = 'b-primary draggabledClass';
        

                
                //$taskcalender['id']        = 999;
                $taskcalender['title']     = $getTname->keyvalue;
                $taskcalender['start']     = $getTask->date_due;
                $taskcalender['className'] = $statuscolor;
                //$taskcalender['start']     = '2018-09-15T16:00:00';
                  
                $taskViewData = '<section class="block bottom20_" style="padding: 15px;"><header class="head-title"><h2>Task Details</h2></header><div class="entity-detail"><table class="property-table"><tbody><tr><td class="ralign"><span class="title">Task Name :</span></td><td><div class="info">'.$getTname->keyvalue.'</div></td></tr><tr><td class="ralign"><span class="title">Assign To :</span></td><td><div class="info">'.$sqltask_ass_user->user_name.'</div></td></tr><tr><td class="ralign"><span class="title">Task Owner : </span></td><td><div class="info">'.$getOwnr->user_name.'</div></td></tr></tbody></table></div></section><section class="block bottom20_" style="padding: 15px;"><header class="head-title"><h2>Additional Information</h2></header><div class="entity-detail"><table class="property-table"><tbody><tr><td class="ralign"><span class="title">Due Date :</span></td><td><div class="info">'.$getTask->date_due.'</div></td></tr><tr><td class="ralign"><span class="title"> Progress % : </span></td><td><div class="info">'.$getTask->progress_percnt.'</div></td></tr><tr><td class="ralign"><span class="title"> Priority : </span></td><td><div class="info">'.$progress_v->keyvalue.'</div></td></tr><tr><td class="ralign"><span class="title"> Status : </span></td><td><div class="info">'.$sqltask_status1->keyvalue.'</div></td></tr></tbody></table></div></section><div class="modal-footer"><a href="#" class="btn btn-default pull-left" data-dismiss="modal" onclick="getViewTaskPage('.$sqltask_id.');">View Task >></a><button type="button" class="btn btn-default" data-dismiss="modal">Close</button></div>';

                  $taskcalender['geoip_data_info_task']  = $taskViewData;
               ?>
                
             <?php 
  			       $pri_col    = 'task_id';
  			       $table_name = 'tbl_task';
               $rmndrdate = $getTask->date_due;
               $rmdarr = explode(' ',$rmndrdate);
               
               if((date('m/d/Y') == $rmdarr[0])){
                 $cssrecord = 'p-10 event-control ui-draggable ui-draggable-handle';
               }else{
                  $cssrecord = "p-10 event-control $statuscolor record";
               }
			      ?>
            <div class="<?=$cssrecord;?>" data-row-id="<?=$fetch_list->task_id; ?>"><a href="#" onclick="viewTask(this);" property = "view" type="button" data-toggle="modal" data-target="#myModal" arrt='<?=json_encode($taskViewData);?>'  data-backdrop='static' data-keyboard='false'><?=$i .". ".$getTname->keyvalue;?> </a> 
            <a class="pull-right text-muted event-remove delbutton" id="<?php echo $getTask->task_id."^".$table_name."^".$pri_col ; ?>"><i class="fa fa-trash-o"></i></a>
            </div>
  
            <?php
                $taskcalender1[]=$taskcalender;
                $i++;
             }  
              $jsondata =  json_encode($taskcalender1,true)
             //$jsondata =  mysql_real_escape_string(json_encode($taskcalender1,true));
            ?>
          </div>
          <!-- /left side body -->
        </div>
</div>
</div>
</div><!--sidebar-nav close-->
</div><!--col-sm-3 close-->

<div class="col-sm-12">
<section class="tile-" style="top:0px;">
<div class="tile-body p-0">
<div class="tcol">
          <!-- right side header -->
          <div class="p-15- bg-white">
            <div class="pull-right">
              <div class="btn-group">
                <input type="hidden" id="json_value" value='<?=$jsondata;?>'>
              </div>
            </div>
            
          </div>
            <!-- /right side header -->
            <!-- right side body -->
            <div class="p-15-">
              <div id='calendar'></div>
            </div>
          <!-- /right side body -->
        </div>
</div>
</section>
</div><!--col-sm-9 close-->

</div><!--row close-->
</div><!--tile-body p-0 close-->
</section>
</div>
</div>
</div>
</section><!--/ CONTENT -->

</div> <!-- Close ajax_content -->


<!-- Modal -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header" style="padding: 15px;">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span> <span class="sr-only">close</span></button>
                <h4 id="modalTitletask1" class="modal-title">View Task Details</h4>
            </div>
            <div id="modalBody1" class="modal-body__">
              <div class="modal-body__">
                  <div class="panel-group__ icon-plus" id="accordion" role="tablist" aria-multiselectable="true">
                   <div>
                    <article class="page-content" id="viewtaskDetails">
                    </article>
                   </div>
                 </div>
                </div>
             </div>
        </div>
    </div>
</div>
<!-- /Modal close-->

<!-- /Modal -->

<div id="fullCalModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header" style="padding: 15px;">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span> <span class="sr-only">close</span></button>
                <h4 id="modalTitletask" class="modal-title"></h4>
            </div>
            <div id="modalBody1" class="modal-body__">
              <div class="modal-body__">
                  <div class="panel-group__ icon-plus" id="accordion" role="tablist" aria-multiselectable="true">
                    <div>
                    <article class="page-content" id="modalBodytask">
                     </article>
                  </div>
                 </div>
               </div>
             </div>
        </div>
    </div>
</div>


<?php $this->load->view('footer.php'); ?>


<!--    ============== Page Specific Scripts =============== -->
<script>
            $(window).load(function(){

               var jsonval1 = $('#json_value').val();
               jsonval = JSON.parse(jsonval1);
              
                console.log(jsonval);
                $('#calendar').fullCalendar({
                    header: {
                        //left: 'prev,next today',
                        left: 'prev,next',
                        center: 'title',
                        right: 'agendaDay,agendaWeek,month'
                    },
                    defaultDate: '<?php echo date('Y-m-d');?>',
                    //editable: true,
                    //droppable: true, // this allows things to be dropped onto the calendar
                    // drop: function() {
                    //   // is the "remove after drop" checkbox checked?
                    //   if ($('#drop-remove').is(':checked')) {
                    //       // if so, remove the element from the "Draggable Events" list
                    //       $(this).remove();
                    //   }
                    // },
                    eventLimit: true, // allow "more" link when too many events
                    events: jsonval,
                    eventRender: function (event, element) {
                      // alert(element);
                        element.attr('href', 'javascript:void(0);');
                        element.click(function() {  
                            $('#modalTitletask').html('Task Name : '+event.title);
                            $('#modalBodytask').html(event.geoip_data_info_task);
                            $('#fullCalModal').modal();   
                        });
                      }
                });
                
  $('#cal-prev').click(function(){
   $('#calendar').fullCalendar( 'prev' );
  });

  // Next month action
  $('#cal-next').click(function(){
      $('#calendar').fullCalendar( 'next' );
  });

  // Change to month view
  $('#change-view-month').click(function(){
      $('#calendar').fullCalendar('changeView', 'month');

      // safari fix
      $('#content .main').fadeOut(0, function() {
          setTimeout( function() {
              $('#content .main').css({'display':'table'});
          }, 0);
      });

  });

  // Change to week view
  $('#change-view-week').click(function(){
      $('#calendar').fullCalendar( 'changeView', 'agendaWeek');

      // safari fix
      $('#content .main').fadeOut(0, function() {
          setTimeout( function() {
          $('#content .main').css({'display':'table'});
          }, 0);
      });

  });

  // Change to day view
  $('#change-view-day').click(function(){
      $('#calendar').fullCalendar( 'changeView','agendaDay');

      // safari fix
      $('#content .main').fadeOut(0, function() {
          setTimeout( function() {
              $('#content .main').css({'display':'table'});
          }, 0);
      });

  });

  // Change to today view
  $('#change-view-today').click(function(){
      $('#calendar').fullCalendar('changeView','today');

     // safari fix
      $('#content .main').fadeOut(0, function() {
          setTimeout( function() {
              $('#content .main').css({'display':'table'});
          }, 0);
      });

  });
 });


function viewTask(ths){
  var datavalue  = $(ths).attr('arrt');

  $('#viewtaskDetails').empty().append(JSON.parse(datavalue));
}


  </script>


