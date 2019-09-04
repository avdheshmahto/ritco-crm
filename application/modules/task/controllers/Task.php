<?php
defined('BASEPATH') OR exit('No direct script access allowed');
error_reporting (E_ALL ^ E_NOTICE);
class Task extends my_controller {

function __construct()
{
    parent::__construct(); 
    $this->load->library('pagination');
    $this->load->model('Model_admin_login');
    $this->load->model('model_task');	
}


public function manage_task()
{
	
	if($this->session->userdata('is_logged_in'))
	{	
		 //$data = $this->load->view('manage-tasks');
		$data = $this->Manage_Task_Data();
		$this->load->view('manage-tasks',$data);
	}
	else
	{
		redirect('index');
	}
		
}

function taskview($param = FALSE)
{

     if($this->input->post('id') != "")
     {
   	     $param = $this->input->post('id');
   	     //echo "gfsdhajhxgcf".$param;
         $data['result'] = $this->model_task->getTaskDtl($param);
         $this->load->view('view-task',$data);
   
      }else{
         $data['result'] = $this->model_task->getTaskDtl($param);
         //$data['contant_data'] = 'userdetails/view-settinguser';
	     $this->load->view('view-task',$data);
     }
}

public function view_task()
{
	
	 if($this->input->get('id') != "")
     {
   	     $param = $this->input->get('id');
         $data['result'] = $this->model_task->getTaskDtl($param);
         $this->load->view('view-task',$data);
	}
	else
	{
		redirect('index');
	}
		
}

public function Manage_Task_Data()
{

		  $table_name='tbl_task';
		  $data['result'] = "";
		  //$url   = site_url('/tour/Tour/manage_tour?');
		  $sgmnt = "4";
		  
		  if($_GET['entries'] != '')
		  {
			$showEntries = $_GET['entries'];
		  }
		  else
		  {
			$showEntries= 10;
		  }
		  
		  $totalData   = $this->model_task->count_task($table_name,'A',$this->input->get());

		  if($_GET['entries'] != '' && $_GET['filter'] != '')
		  {
			 $url = site_url('/task/Task/manage_task?filter='.$_GET['filter'].'&entries='.$_GET['entries']);
		  }
		  elseif($_GET['search'] != '' && $_GET['entries'] != '')
		  {
			  $url = site_url('/task/Task/manage_task?filter='.$_GET['filter'].'&user='.$_GET['user'].'&status='.$_GET['status'].'&entries='.$_GET['entries']);
		  }
		  elseif($_GET['entries'] != '' && $_GET['search'] == '')
		  {
			 $url = site_url('/task/Task/manage_task?entries='.$_GET['entries']);
		  }
		  elseif($_GET['search'] != '' && $_GET['entries'] == '')
		  {
			$url = site_url('/task/Task/manage_task?filter='.$_GET['filter'].'&user='.$_GET['user'].'&status='.$_GET['status']);
		  }
		  else
		  {
			$url = site_url('/task/Task/manage_task?');
		  }

          $pagination = $this->ciPagination($url,$totalData,$sgmnt,$showEntries);
          
		  //$data=$this->user_function();
		  $data['dataConfig'] = array('total'=>$totalData,'perPage'=>$pagination['per_page'],'page'=>$pagination['page']);
		  $data['pagination'] = $this->pagination->create_links();
	
		  if($this->input->get('filter') != '')   ////filter start ////
        	$data['result'] = $this->model_task->filterTaskData($pagination['per_page'],$pagination['page'],$this->input->get());
          	else	
    		$data['result'] = $this->model_task->getTaskData($pagination['per_page'],$pagination['page']);


	      return $data;

}

public function ajax_ListTaskData(){

	$data = $this->Manage_Task_Data();
	$this->load->view('load-task-data',$data);
}



public function insert_task()
{
	
	@extract($_POST);
	
	//print_r($_POST);die;

	$table_name_log ='tbl_task_log';
	$table_name = 'tbl_task';
	$pri_col = 'task_id';

	$id = $this->input->post('task_id');
	

	date_default_timezone_set("Asia/Kolkata");
	$dtTime = date('Y-m-d H:i:s');
	
	$data= array(
					
					'task_name' => $this->input->post('task_name'),
					'date_due' => $this->input->post('due_date'),
					//'reminder_date'  => $this->input->post('reminder_date'),
					'priority'  => $this->input->post('priority'),
					'progress_percnt'  => $this->input->post('progress'),
					'task_status' => $this->input->post('status'),
					'user_resp' => $this->input->post('user_resp'),
					'lead_id' => $this->input->post('leadid'),
					'contact_person' => $this->input->post('contact_person'),
					'org_name' => $this->input->post('org_name'),
					'description' => $this->input->post('snotes'),
					//'visibility	' => $this->input->post('optionsRadios'),
					'last_update'    => $dtTime,

				);


	$sesio = array(
					'comp_id' => $this->session->userdata('comp_id'),
					'brnh_id' => $this->session->userdata('brnh_id'),
					'maker_id' => $this->session->userdata('user_id'),
					'author_id' => $this->session->userdata('user_id'),
					'maker_date'=> date('y-m-d'),
					'author_date'=> date('y-m-d')
				  );
		
	$dataall = array_merge($data,$sesio);

	if($id != '')
	{	

		$login_id=$this->session->userdata('user_id');

		$newstatus=$this->input->post('status');
		$tsts=$this->db->query("select * from tbl_task where task_id='$id' ");
		$getTsts=$tsts->row();
		$oldstatus=$getTsts->task_status;
		if($oldstatus != $newstatus){
			$this->software_log_insert($id,'Task','Task','Status',$oldstatus,$newstatus,'Task Status Changed');
		}

		$newusr = $this->input->post('user_resp');
		$usr = $this->db->query("select * from tbl_task where task_id='$id' ");
		$getUsr = $usr->row();
		$oldusr = $getUsr->user_resp;

		//==========Software Log Data============

		$pid=$this->db->query("select * from tbl_software_log where slog_id='$id' and mdl_name='Task' ");
		$fetchId=$pid->row();			
		$aid=$fetchId->seen_id;

		$bid=explode(",",$aid);
		$cid=array_unique($bid);
		$updtId=implode(",",$cid);
		
		if($updtId != '')
		{
			$newstatusid=$updtId.','.$newusr;	
		}
		else
		{
			$newstatusid=$login_id;
		}
		$this->db->query("update tbl_software_log set seen_id='$updtId' where slog_id='$id' AND mdl_name='Task' ");
		//============End===============

		 if($oldusr != $newusr){
		 		$this->software_log_insert($id,'Task','Task','User',$oldusr,$newusr,'Task User Changed');
		 		$this->db->query("update tbl_software_log set seen_id='$newstatusid' where slog_id='$id' AND mdl_name='Task' ");
		 }

		$this->Model_admin_login->update_user($pri_col,$table_name,$id,$data);
		$this->db->query("update tbl_note set note_desc = '$snotes' where main_lead_id_note = 'main_task' and note_logid = '$id'");


		//==========Task Seen_id Update============

		$snid=$this->db->query("select * from tbl_task where task_id='$id' ");
		$fetchSnId=$snid->row();			
		$aid=$fetchSnId->seen_id;

		$bid=explode(",",$aid);
		$cid=array_unique($bid);
		$getSnId=implode(",",$cid);
		
		if($getSnId != '')
		{
			$newseenid=$getSnId.','.$newusr;	
		}
		else
		{
			$newseenid=$login_id;
		}
		if($oldusr != $newusr){
			$this->db->query("update tbl_task set seen_id='$newseenid' where task_id='$id' ");	
		}
		
		//============End===============

		echo 2;	
	}			
	else
	{
		$this->Model_admin_login->insert_user($table_name,$dataall);
		echo 1;
	}


	
    //======================Task Log============================

		if($id == '')
		{
			$lastid=$this->db->insert_id();	
      			$this->software_log_insert($lastid,'Task','New','Task Create','','','Task Created');

			$newusr = $this->input->post('user_resp');
			$lgnusr = $this->session->userdata('user_id');
			if($newusr != $lgnusr ){
		 		$this->software_log_insert($lastid,'Task','Task','User',$lgnusr,$newusr,'Task User Changed');
		 	}


		 	$task_name = $this->input->post('task_name');
			$tnm = $this->db->query("select * from tbl_master_data where serial_number='$task_name' ");
			$getNm = $tnm->row();
			$tsknm = $getNm->keyvalue;
			$maker_id = $this->session->userdata('user_id');
			$users = $this->input->post('user_resp');
			$description = $this->input->post('snotes');
				$this->software_log_insert($lastid,'Task','Task',$tsknm,$maker_id,$users,$description);

			//==============Software Log Data & Task Seen_id====
		    $usrid=$lgnusr.",".$newusr;
			$this->db->query("update tbl_software_log set seen_id='$usrid' where slog_id='$lastid' and mdl_name = 'Task' ");			
			$this->db->query("update tbl_task set seen_id='$usrid' where task_id='$lastid' ");
			//===============End=============

			$lead_id = $this->input->post('leadid');
			if($lead_id != '')
			{
				$note_des = $this->input->post('snotes');
			    $this->update_note_log($lead_id,'main_task','Task',$note_des);
			}
			
		}
		else
		{
			$lastid=$this->input->post('task_id');
		}
		
				
		$datalog= array(
				
						'task_id' => $lastid,
						//'lead_id' => $this->input->post('leadid'),
						'task_name' => $this->input->post('task_name'),
						'date_due' => $this->input->post('due_date'),
						//'reminder_date'  => $this->input->post('reminder_date'),
						'progress_percnt'  => $this->input->post('progress'),
						'priority'  => $this->input->post('priority'),
						'task_status' => $this->input->post('status'),
						'user_resp' => $this->input->post('user_resp'),
						'lead_id' => $this->input->post('leadid'),
						'contact_person' => $this->input->post('contact_person'),
						'org_name' => $this->input->post('org_name'),
						'description' => $this->input->post('snotes'),
						//'visibility	' => $this->input->post('optionsRadios'),									
							
				      	);
				
		$datalogs = array_merge($datalog,$sesio);
		$this->Model_admin_login->insert_user($table_name_log,$datalogs);

		
}

function deleteTask()
{
	$id=$_GET['task_id'];
	if($id!='')
	{
		

		$this->db->query("delete from tbl_task_log where task_id='$id' ");
		$this->db->query("delete from tbl_file where file_type='Task' AND file_logid='$id' ");
		$this->db->query("delete from tbl_note where note_type='Task' AND note_logid='$id' ");
		$this->db->query("delete from tbl_software_log where mdl_name='Task' AND slog_id='$id' ");


		$task=$this->db->query("select * from tbl_task where lead_id != 0 AND task_id='$id' ");
		$getTask=$task->row();
		if(sizeof($getTask) > 0)
		{
			$this->db->query("delete from tbl_note where main_lead_id_note='main_task' AND note_type='Task' AND note_logid='".$getTask->lead_id."' ");
		}

		$this->db->query("delete from tbl_task where task_id='".$id."' ");

	}

	redirect('task/Task/manage_task');
}

///
public function insert_task_inner()
{
	
	@extract($_POST);
	
	//print_r($_POST);die;
	$table_name_log ='tbl_task_log';
	$table_name = 'tbl_task';
	$pri_col = 'task_id';

	$id = $this->input->post('task_id');

		date_default_timezone_set("Asia/Kolkata");
		$dtTime = date('Y-m-d H:i:s');

		$data= array(
					
					'task_name' => $this->input->post('task_name'),
					'date_due' => $this->input->post('due_date'),
					'reminder_date'  => $this->input->post('reminder_date'),
					'priority'  => $this->input->post('priority'),
					'progress_percnt'  => $this->input->post('progress'),
					'task_status' => $this->input->post('status'),
					'user_resp' => $this->input->post('user_resp'),
					'lead_id' => $this->input->post('leadid'),
					'contact_person' => $this->input->post('contact_person'),
					'org_name' => $this->input->post('org_name'),
					'description' => $this->input->post('snotes'),
					//'visibility	' => $this->input->post('optionsRadios'),
					'last_update'    => $dtTime,

				);
		$sesio = array(
					'comp_id' => $this->session->userdata('comp_id'),
					'brnh_id' => $this->session->userdata('brnh_id'),
					'maker_id' => $this->session->userdata('user_id'),
					'author_id' => $this->session->userdata('user_id'),
					'maker_date'=> date('y-m-d'),
					'author_date'=> date('y-m-d')
				  );


	if($id != '')
	{		

			$login_id=$this->session->userdata('user_id');

			$newstatus=$this->input->post('status');
			$tsts=$this->db->query("select * from tbl_task where task_id='$id' ");
			$getTsts=$tsts->row();
			$oldstatus=$getTsts->task_status;
			if($oldstatus != $newstatus){
			 $this->software_log_insert($id,'Task','Task','Status',$oldstatus,$newstatus,'Task Status Changed');
			}

			$newusr = $this->input->post('user_resp');
			$usr = $this->db->query("select * from tbl_task where task_id='$id' ");
			$getUsr = $usr->row();
			$oldusr = $getUsr->user_resp;

			//==========Software Log Data============

			$pid=$this->db->query("select * from tbl_software_log where slog_id='$id' and mdl_name='Task' ");
			$fetchId=$pid->row();			
			$aid=$fetchId->seen_id;

			$bid=explode(",",$aid);
			$cid=array_unique($bid);
			$updtId=implode(",",$cid);
			
			if($updtId != '')
			{
				$newstatusid=$updtId.','.$newusr;	
			}
			else
			{
				$newstatusid=$login_id;
			}
			$this->db->query("update tbl_software_log set seen_id='$updtId' where slog_id='$id' AND mdl_name='Task' ");
			//============End===============

			if($oldusr != $newusr){
				$this->software_log_insert($id,'Task','Task','User',$oldusr,$newusr,'Task User Changed');
				$this->db->query("update tbl_software_log set seen_id='$newstatusid' where slog_id='$id' AND mdl_name='Task' ");
			}

			$this->Model_admin_login->update_user($pri_col,$table_name,$id,$data);

			$this->db->query("update tbl_note set note_desc = '$snotes' where main_lead_id_note = 'main_task' and note_logid = '$id'");


			//==========Task Seen_id Update============

			$snid=$this->db->query("select * from tbl_task where task_id='$id' ");
			$fetchSnId=$snid->row();			
			$aid=$fetchSnId->seen_id;

			$bid=explode(",",$aid);
			$cid=array_unique($bid);
			$getSnId=implode(",",$cid);
			
			if($getSnId != '')
			{
				$newseenid=$getSnId.','.$newusr;	
			}
			else
			{
				$newseenid=$login_id;
			}
			if($oldusr != $newusr){
				$this->db->query("update tbl_task set seen_id='$newseenid' where task_id='$id' ");	
			}
			
			//============End===============

			echo $id;	
	}	

	//======================Task Log============================

	    $lastid=$this->input->post('task_id');
				
		$datalog= array(
				
						'task_id' => $lastid,
						//'lead_id' => $this->input->post('leadid'),
						'task_name' => $this->input->post('task_name'),
						'date_due' => $this->input->post('due_date'),
						'reminder_date'  => $this->input->post('reminder_date'),
						'progress_percnt'  => $this->input->post('progress'),
						'priority'  => $this->input->post('priority'),
						'task_status' => $this->input->post('status'),
						'user_resp' => $this->input->post('user_resp'),
						'lead_id' => $this->input->post('leadid'),
						'contact_person' => $this->input->post('contact_person'),
						'org_name' => $this->input->post('org_name'),
						'description' => $this->input->post('snotes'),
						//'visibility	' => $this->input->post('optionsRadios'),									
							
				      	);
				
		$datalogs = array_merge($datalog,$sesio);
		$this->Model_admin_login->insert_user($table_name_log,$datalogs);


}

function ajax_editInnerData()
{
		$param = $this->input->post('id');
        $data['result'] = $this->model_task->getTaskDtl($param);
        $this->load->view('load-ajax-task',$data);
}



function ajaxget_leadAlldata()
{
       $ipt = $this->input->post('id');
       $sql = "select L.lead_id,L.contact_id,L.org_id,C.contact_id,C.contact_name,O.org_id,O.org_name FROM  tbl_leads L, tbl_contact_m C, tbl_organization O WHERE lead_id = $ipt AND L.contact_id = C.contact_id AND L.org_id = O.org_id";
       $result = $this->db->query($sql)->row();
      
       if(sizeof($result) > 0)
          echo json_encode($result,true);
       else
          echo false;
      
}


public function insert_task_note()
{
	@extract($_POST);

	 // echo '<pre>';
	 // print_r($_POST);die;
	 // echo '</pre>';

	$table_name = 'tbl_note';
	$pri_col    = 'note_id';

	$id = $this->input->post('noteid');
	echo $this->input->post('tskid');

	$this->load->model('Model_admin_login');

	$data = array(
						'note_logid' => $this->input->post('tskid'),
						'note_type'	 => 'Task',
						'main_lead_id_note'	=>'Inner Task',
						//'note_name' => $this->input->post('note_name'),
						//'note_date' => $this->input->post('note_date'),
						'note_desc' => $this->input->post('note_desc')
					   
					  );
	$sesio = array(

						'comp_id' => $this->session->userdata('comp_id'),
						'brnh_id' => $this->session->userdata('brnh_id'),
						'maker_id' => $this->session->userdata('user_id'),
						'author_id' => $this->session->userdata('user_id'),
						'maker_date'=> date('y-m-d'),
						'author_date'=> date('y-m-d')
				    );
		
		$tskid = $this->input->post('tskid');
		date_default_timezone_set("Asia/Kolkata");
		$dtTime = date('Y-m-d H:i:s');
		
		if($id != '')
		{
			$this->Model_admin_login->update_user($pri_col,$table_name,$id,$data);
			//$this->db->query("update  tbl_task set description = '$note_desc' where main_task_id = '$main_id' and task_id = '$tskid'");
			$this->db->query("update  tbl_task set last_update = '$dtTime' where task_id = '$tskid'");
		  	//echo 2;	
		}
		else
		{	
			$data_merger = array_merge($data,$sesio);
			$this->Model_admin_login->insert_user($table_name,$data_merger);
			$this->db->query("update  tbl_task set last_update = '$dtTime' where task_id = '$tskid'");
	    	//echo 1;
		}

		////-===================Software Log======================

		
	
	    if($tskid != '' && $id == '')
		{	
			
			$note_desc = $this->input->post('note_desc');			
				//$this->software_log_insert($tskid,'Task','Note','Task Notes','','','Task Remarks Added');
				$this->software_log_insert($tskid,'Task','Note','Task Notes','','',$note_desc);

			//==========Software Log Data============

			$pid=$this->db->query("select * from tbl_software_log where slog_id='$tskid' and mdl_name='Task' ");
			$fetchId=$pid->row();			
			$aid=$fetchId->seen_id;

			$bid=explode(",",$aid);
			$cid=array_unique($bid);
			$updtId=implode(",",$cid);

			$this->db->query("update tbl_software_log set seen_id='$updtId' where slog_id='$tskid' AND mdl_name='Task' ");
			//============End===============

		}

}


public function ajax_ListTaskNotes()
{	
	$nid = $this->input->post('id');
	$data['result'] = $this->model_task->getTaskDtl($nid);
	$this->load->view('load-ajax-task',$data);
}


public function insert_task_file()
{
	@extract($_POST);

	  // echo '<pre>';
	  // print_r($_POST);die;
	 // echo '</pre>';

	$table_name = 'tbl_file';
	$pri_col    = 'file_id';

	$id = $this->input->post('fileid');
	echo $this->input->post('tskid');

		@$branchQuery2= $this->db->query("SELECT * FROM $table_name where file_id = '$id' ");
		$branchFetch2 = $branchQuery2->row();

	$this->load->model('Model_admin_login');

		if($_FILES['files_name']['name']!='')
		{
			$target = "crmfiles/taskfile/"; 
			$target1 =$target . @date(U)."_".( $_FILES['files_name']['name']);
			$filesname=@date(U)."_".( $_FILES['files_name']['name']);
			move_uploaded_file($_FILES['files_name']['tmp_name'], $target1);
		}
		else
		{
			$filesname=$branchFetch2->files_name;
		}

	$data = array(
						'file_logid' => $this->input->post('tskid'),
						'file_type'	 => 'Task',	
						'files_name' => $filesname,
						//'note_date' => $this->input->post('note_date'),
						'files_desc' => $this->input->post('files_desc')
					   
					  );
	$sesio = array(

						'comp_id' => $this->session->userdata('comp_id'),
						'brnh_id' => $this->session->userdata('brnh_id'),
						'maker_id' => $this->session->userdata('user_id'),
						'author_id' => $this->session->userdata('user_id'),
						'maker_date'=> date('y-m-d'),
						'author_date'=> date('y-m-d')
				    );
		
		
		if($id != '')
		{
			$this->Model_admin_login->update_user($pri_col,$table_name,$id,$data);
		  	//echo 2;	
		}
		else
		{	
			$data_merger = array_merge($data,$sesio);
			$this->Model_admin_login->insert_user($table_name,$data_merger);
	    	//echo 1;
		}

		////-===================Software Log======================

		$tskid = $this->input->post('tskid');
	
	    if($tskid != '' && $id == '')
		{	
			
			$files_desc = $this->input->post('files_desc');
			
				$this->software_log_insert($tskid,'Task','File',$filesname,'','',$files_desc);
		}

}

public function ajax_taskFilesData()
{
	$nid = $this->input->post('id');
	$data['result'] = $this->model_task->getTaskDtl($nid);
	$this->load->view('load-ajax-task',$data);
}



public function update_task_assignto()
{
	$id = $this->input->post('taskassignid');
	$uid = $this->input->post('assign_user');
	 //print_r($_POST);die;

		if($id != '')
		{	

			$login_id=$this->session->userdata('user_id');
			
			$newusr = $this->input->post('assign_user');
			$usr = $this->db->query("select * from tbl_task where task_id='$id' ");
			$getUsr = $usr->row();
			$oldusr = $getUsr->user_resp;

			//==========Software Log Data============

			$pid=$this->db->query("select * from tbl_software_log where slog_id='$id' and mdl_name='Task' ");
			$fetchId=$pid->row();			
			$aid=$fetchId->seen_id;

			$bid=explode(",",$aid);
			$cid=array_unique($bid);
			$updtId=implode(",",$cid);
			
			if($updtId != '')
			{
				$newstatusid=$updtId.','.$newusr;	
			}
			else
			{
				$newstatusid=$login_id;
			}

			//============End===============
			if($oldusr != $newusr){
				$this->software_log_insert($id,'Task','Task','User',$oldusr,$newusr,'Task User Changed');
				$this->db->query("update tbl_software_log set seen_id='$newstatusid' where slog_id='$id' AND mdl_name='Task' ");
			}


			//==========Task Seen_id Update============

			$snid=$this->db->query("select * from tbl_task where task_id='$id' ");
			$fetchSnId=$snid->row();			
			$aid=$fetchSnId->seen_id;

			$bid=explode(",",$aid);
			$cid=array_unique($bid);
			$getSnId=implode(",",$cid);
			
			if($getSnId != '')
			{
				$newseenid=$getSnId.','.$newusr;	
			}
			else
			{
				$newseenid=$login_id;
			}
			if($oldusr != $newusr){
				$this->db->query("update tbl_task set seen_id='$newseenid' where task_id='$id' ");	
			}
			
			//============End===============
		}

	if($uid != '')
	{	
		date_default_timezone_set("Asia/Kolkata");
		$dtTime = date('Y-m-d H:i:s');
		$this->db->query("update tbl_task set user_resp='".$uid."',last_update='$dtTime' where task_id='".$id."' ");
		echo $uid;
	}
}


function update_task_status()
{

	$id = $this->input->post('task_status_id');
	$tstatus = $this->input->post('new_status');
	if($tstatus != '')
	{

		$newstatus=$tstatus;
		$tsts=$this->db->query("select * from tbl_task where task_id='$id' ");
		$getTsts=$tsts->row();
		$oldstatus=$getTsts->task_status;
		if($oldstatus != $newstatus){
			$this->software_log_insert($id,'Task','Task','Status',$oldstatus,$newstatus,'Task Status Changed');
		}

		date_default_timezone_set("Asia/Kolkata");
		$dtTime = date('Y-m-d H:i:s');
		$this->db->query("update tbl_task set task_status='".$tstatus."',last_update='$dtTime' where task_id='".$id."' ");		

		echo $tstatus;
	}
}


function ajax_readUnreadTask()
{
	$login_id=$this->session->userdata('user_id');
	$id=$this->input->post('id');
	$sql=$this->db->query("select * from tbl_task where task_id='$id' ");
	$getSql=$sql->row();
	$vid=$getSql->visibility;

	$lvid=explode(",",$vid);
	$ldvid=array_unique($lvid);
	$fvid=implode(",",$ldvid);
	
	if($fvid != '')
	{
		$ruid=$fvid.",".$login_id;
	}
	else
	{
		$ruid=$login_id;
	}
	$this->db->query("update tbl_task set visibility='$ruid' where task_id='$id' ");
	//echo "Successfully Update";
	//print_r($fvid);
}

} ?>