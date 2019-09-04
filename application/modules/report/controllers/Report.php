<?php
defined('BASEPATH') OR exit('No direct script access allowed');
error_reporting (E_ALL ^ E_NOTICE);

class Report extends my_controller {

function __construct()
{
    parent::__construct(); 
    $this->load->library('pagination');
    $this->load->model('model_report');	
}     


/*public function manage_report()
{
	if($this->session->userdata('is_logged_in'))
	{
		$this->load->view('manage-report');
	}
	else
	{
	redirect('index');
	}
}*/



//=========================Lead==================

function lead_excel()
{
	$this->load->view('lead-excel');
}

//searchLead
function manage_report() 
{
    if($this->session->userdata('is_logged_in'))
	{
		$data = $this->Manage_Lead_Data();
    	$this->load->view('lead-report', $data);
	}
	else
	{
		redirect('index');
	}
}

function Manage_Lead_Data()
{


  	  $data['result'] = "";
	  $table_name  = 'tbl_leads';
	  //$url        = site_url('/report/Report/searchLead?');
	  $sgmnt      = "4";
	  
	  if($_GET['entries'] != '')
	  {
	  	$showEntries = $_GET['entries'];
	  }
	  else
	  {
	  	$showEntries= 10;
	  }
      
	  $totalData  = $this->model_report->count_lead($table_name,$this->input->get());
      
	  /*if($_GET['entries'] != '' && $_GET['search'] != 'search')
	  {
         $url = site_url('/report/Report/searchLead?entries='.$_GET['entries']);
      }
	  elseif($_GET['search'] == 'search' || $_GET['entries'] != '')
	  {
	  	$url = site_url('/report/Report/searchLead?user='.$_GET['user'].'&status='.$_GET['status'].'&stage='.$_GET['stage'].'&from_date='.$_GET['from_date'].'&to_date='.$_GET['to_date'].'&search='.$_GET['search'].'&entries='.$_GET['entries']);
	  }
	  else
	  {
	  	$url = site_url('/report/Report/searchLead?');
	  }*/

	  	  if($_GET['entries'] != '' && $_GET['filter'] != '')
		  {
			 $url = site_url('/report/Report/manage_report?filter='.$_GET['filter'].'&entries='.$_GET['entries']);
		  }
		  elseif($_GET['search'] != '' && $_GET['entries'] != '')
		  {
			  $url = site_url('/report/Report/manage_report?filter='.$_GET['filter'].'&user='.$_GET['user'].'&status='.$_GET['status'].'&stage='.$_GET['stage'].'&from_date='.$_GET['from_date'].'&to_date='.$_GET['to_date'].'&entries='.$_GET['entries']);
		  }
		  elseif($_GET['entries'] != '' && $_GET['search'] == '')
		  {
			 $url = site_url('/report/Report/manage_report?entries='.$_GET['entries']);
		  }
		  elseif($_GET['search'] != '' && $_GET['entries'] == '')
		  {
			$url = site_url('/report/Report/manage_report?filter='.$_GET['filter'].'&user='.$_GET['user'].'&status='.$_GET['status'].'&stage='.$_GET['stage'].'&from_date='.$_GET['from_date'].'&to_date='.$_GET['to_date']);
		  }
		  else
		  {
			$url = site_url('/report/Report/manage_report?');
		  }
      
	  $pagination   = $this->ciPagination($url,$totalData,$sgmnt,$showEntries);
  
    
      //$data=$this->user_function();      // call permission fnctn
      $data['dataConfig'] = array('total'=>$totalData,'perPage'=>$pagination['per_page'],'page'=>$pagination['page']);
      $data['pagination'] = $this->pagination->create_links();
	 
	  if($this->input->get('filter') != '')   ////filter start ////
        	$data['result'] = $this->model_report->filterLeadList($pagination['per_page'],$pagination['page'],$this->input->get());
          	else	
    		$data['result'] = $this->model_report->getLead($pagination['per_page'],$pagination['page']);
			
      return $data;


}

//=======================Task====================

function task_excel()
{
	$this->load->view('task-excel');
}

function searchTask() 
{
    if($this->session->userdata('is_logged_in'))
	{
		$data = $this->ManageTaskData();
		$this->load->view('task-report', $data);
	}
	else
	{
		redirect('index');
	}
}

function ManageTaskData()
{

	  $data['result'] = "";
	  $table_name  = 'tbl_task';
	  $sgmnt      = "4";
	  
	  if($_GET['entries'] != '')
	  {
	  	$showEntries = $_GET['entries'];
	  }
	  else
	  {
	  	$showEntries= 10;
	  }
      
	  $totalData  = $this->model_report->count_task($table_name,$this->input->get());
      
	  /*if($_GET['entries'] != '' && $_GET['filter'] != 'filter')
	  {
         $url = site_url('/report/Report/searchTask?entries='.$_GET['entries']);
      }
	  elseif($_GET['filter'] == 'filter' || $_GET['entries'] != '')
	  {
	  	$url = site_url('/report/Report/searchTask?contact_id='.$_GET['contact_id'].'&sales_person_id='.$_GET['sales_person_id'].'&f_date='.$_GET['f_date'].'&t_date='.$_GET['t_date'].'&filter='.$_GET['filter'].'&entries='.$_GET['entries']);
	  }
	  else
	  {
	  	$url = site_url('/report/Report/searchTask?');
	  }*/

	  	  if($_GET['entries'] != '' && $_GET['filter'] != '')
		  {
			 $url = site_url('/report/Report/searchTask?filter='.$_GET['filter'].'&entries='.$_GET['entries']);
		  }
		  elseif($_GET['search'] != '' && $_GET['entries'] != '')
		  {
			  $url = site_url('/report/Report/searchTask?filter='.$_GET['filter'].'&user='.$_GET['user'].'&status='.$_GET['status'].'&from_date='.$_GET['from_date'].'&to_date='.$_GET['to_date'].'&entries='.$_GET['entries']);
		  }
		  elseif($_GET['entries'] != '' && $_GET['search'] == '')
		  {
			 $url = site_url('/report/Report/searchTask?entries='.$_GET['entries']);
		  }
		  elseif($_GET['search'] != '' && $_GET['entries'] == '')
		  {
			$url = site_url('/report/Report/searchTask?filter='.$_GET['filter'].'&user='.$_GET['user'].'&status='.$_GET['status'].'&from_date='.$_GET['from_date'].'&to_date='.$_GET['to_date']);
		  }
		  else
		  {
			$url = site_url('/report/Report/searchTask?');
		  }
      
	  $pagination   = $this->ciPagination($url,$totalData,$sgmnt,$showEntries);
  
    
      //$data=$this->user_function();      // call permission fnctn
      $data['dataConfig'] = array('total'=>$totalData,'perPage'=>$pagination['per_page'],'page'=>$pagination['page']);
      $data['pagination'] = $this->pagination->create_links();
	 
	  if($this->input->get('filter') != '')   ////filter start ////
        	$data['result'] = $this->model_report->filterTaskList($pagination['per_page'],$pagination['page'],$this->input->get());
          	else	
    		$data['result'] = $this->model_report->getTaskList($pagination['per_page'],$pagination['page']);
			
      return $data;


}



}  ?>