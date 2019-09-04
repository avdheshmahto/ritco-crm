<?php
defined('BASEPATH') OR exit('No direct script access allowed');
error_reporting (E_ALL ^ E_NOTICE);
class Lead extends my_controller {

function __construct()
{
    parent::__construct(); 
   	$this->load->library('pagination');
   	$this->load->model('Model_admin_login');
    $this->load->model('model_lead');	
}  



/*public function ajex_nextIncrementId()
{
 	$cust    = $this->input->post('customer');
	$sp    = $this->input->post('sales_person');
 	
	$customer=$this->db->query("select * from tbl_contact_m where group_name='4' AND contact_id = '$cust' ");
	$getCustomer=$customer->row();
	$cust_code=$getCustomer->code_name;
	
	$sales=$this->db->query("select * from tbl_user_mst where comp_id != '1' AND user_id = '$sp' ");
	$getSales=$sales->row();
	$sales_code=$getSales->code_name;
	
    $query    = $this->db->query("SELECT auto_increment FROM INFORMATION_SCHEMA.TABLES WHERE table_name = 'tbl_leads' ");
	$result   = $query->row_array();

	if(sizeof($result) > 0)
	{
		 echo $cust_code.'-'.$sales_code.'-'.$result['auto_increment'];
    }
}*/


public function manage_lead()
{

	if($this->session->userdata('is_logged_in'))
	{
		$data = $this->Show_Lead_Data();
		$this->load->view('manage-lead',$data);
	}
	else
	{
		redirect('index');
	}
		
}

function leadview($param = FALSE)
{
    if($this->input->post('id') != "")
     {
   	     $param = $this->input->post('id');
         $data['result'] = $this->model_lead->getLeadDtl($param);
         $this->load->view('view-lead',$data);          
      }else{
         $data['result'] = $this->model_lead->getLeadDtl($param);
	     $this->load->view('view-lead',$data);
     }
}


public function view_lead()
{
	
	if($this->input->get('id') != "")
     {
   	     $param = $this->input->get('id');
         $data['result'] = $this->model_lead->getLeadDtl($param);
         $this->load->view('view-lead',$data);
	}
	else
	{
		redirect('index');
	}
		
}

public function Show_Lead_Data()
{

		  $table_name='tbl_leads';
		  $data['result'] = "";
		  $sgmnt = "4";
		  
		  if($_GET['entries'] != '')
		  {
			$showEntries = $_GET['entries'];
		  }
		  else
		  {
			$showEntries= 10;
		  }
		  
		  $totalData   = $this->model_lead->count_leads($table_name,'A',$this->input->get());
		  

		 //  if($_GET['entries'] != '' && $_GET['filter'] == '')
		 //  {
			//  $url = site_url('/lead/Lead/manage_lead?entries='.$_GET['entries']);
		 //  }
		 //  elseif($_GET['filter'] != '' && $_GET['entries'] == '')
		 //  {
			// $url = site_url('/lead/Lead/manage_lead?filter='.$_GET['filter']);
		 //  }
		 //  else
		 //  {
			// $url = site_url('/lead/Lead/manage_lead?');
		 //  }

    	  if($_GET['entries'] != '' && $_GET['filter'] != '')
		  {
			 $url = site_url('/lead/Lead/manage_lead?filter='.$_GET['filter'].'&entries='.$_GET['entries']);
		  }
		  elseif($_GET['search'] != '' && $_GET['entries'] != '')
		  {
			  $url = site_url('/lead/Lead/manage_lead?filter='.$_GET['filter'].'&user='.$_GET['user'].'&status='.$_GET['status'].'&stage='.$_GET['stage'].'&entries='.$_GET['entries']);
		  }
		  elseif($_GET['entries'] != '' && $_GET['search'] == '')
		  {
			 $url = site_url('/lead/Lead/manage_lead?entries='.$_GET['entries']);
		  }
		  elseif($_GET['search'] != '' && $_GET['entries'] == '')
		  {
			$url = site_url('/lead/Lead/manage_lead?filter='.$_GET['filter'].'&user='.$_GET['user'].'&status='.$_GET['status'].'&stage='.$_GET['stage']);
		  }
		  else
		  {
			$url = site_url('/lead/Lead/manage_lead?');
		  }		  


          $pagination = $this->ciPagination($url,$totalData,$sgmnt,$showEntries);

         // $data=$this->user_function();	
		  $data['dataConfig'] = array('total'=>$totalData,'perPage'=>$pagination['per_page'],'page'=>$pagination['page']);
		  $data['pagination'] = $this->pagination->create_links();
	
		  if($this->input->get('filter') != '')   ////filter start ////
        	$data['result'] = $this->model_lead->filterLeadData($pagination['per_page'],$pagination['page'],$this->input->get());
          	else	
    		$data['result'] = $this->model_lead->getLeadData($pagination['per_page'],$pagination['page']);


	        return $data;

}


public function ajax_ListLeadData(){

	$data = $this->Show_Lead_Data();
	$this->load->view('load-lead-data',$data);
}

public function insert_lead()
{


		@extract($_POST);
		// echo '<pre>';
	    //   print_r($_POST);
		// echo '</pre>';die;

		$table_name     = 'tbl_leads';
		$table_contact  = 'tbl_contact_m';
		$table_org		= 'tbl_organization';
		$pri_col        = 'lead_id';
		$cont_colum  	= 'contact_id';
		$org_colum		= 'org_id';
		
		$id = $this->input->post('lead_id');

		//============Organization================
        
        $dataOrg = array(
						  'org_name' => $this->input->post('org_name'),
						);

         $sesio = array(

							'comp_id' => $this->session->userdata('comp_id'),
							'brnh_id' => $this->session->userdata('brnh_id'),
							'maker_id' => $this->session->userdata('user_id'),
							'author_id' => $this->session->userdata('user_id'),
							'maker_date'=> date('y-m-d'),
							'author_date'=> date('y-m-d')

				       );

                 
        $dataOranization = array_merge($dataOrg,$sesio);
        
			if($this->input->post('orgidcontant') != ""){
				$org_Id = $this->input->post('orgidcontant');
		        $this->Model_admin_login->update_user($org_colum,$table_org,$org_Id,$dataOrg);
			}else{				
	           $this->Model_admin_login->insert_user($table_org,$dataOranization);
	           $org_Id = $this->db->insert_id();
	           $this->software_log_insert($org_Id,'Orgz','New','Orgz Create','','','Organization Created');
	        }
			
			////////=============Contact======================////////////

        $email_val = json_encode($this->input->post('email_id'),true);
        $phone_no  = json_encode($this->input->post('phone_no'),true);

		$CntData = array(
				            'contact_name' => $this->input->post('contact'),
							'org_name'     => $org_Id,
							'email'        => $email_val,
							'phone'        => $phone_no,
							'address'      => $this->input->post('address'),
						);

		$contactdata = array_merge($CntData,$sesio);

        if($this->input->post('oldcontact') !=""){
		   	$con_Id = $this->input->post('oldcontact');
            $this->Model_admin_login->update_user($cont_colum,$table_contact,$con_Id,$CntData);
		 }else{  
		    $this->Model_admin_login->insert_user($table_contact,$contactdata);
		    $con_Id = $this->db->insert_id();
		    $this->db->query("update tbl_organization set contact_id='$con_Id' where org_id='$org_Id' ");
			$this->software_log_insert($con_Id,'Contact','New','Contact Create','','','Contact Created');
		}
		

		//==============================Lead=================================
		date_default_timezone_set("Asia/Kolkata");
		$dtTime = date('Y-m-d H:i:s');

       $data = array(
					   
					    'contact_id'     => $con_Id,
						'org_id'         => $org_Id,
						'lead_number'    => $this->input->post('lead_number'),
						'user_resp'      => $this->input->post('user_resp'),
						'industry'       => $this->input->post('industry'),
						//'source'         => $this->input->post('source'),
						//'probability'    => $this->input->post('probability'),
						'last_update'    => $dtTime,
						'closuredate'    => $this->input->post('closuredate'),				
						'opp_value'      => $this->input->post('opp_value'),
						'stage'          => $this->input->post('stage'),
						'discription'    => $this->input->post('summernote'),
						'lead_state'     => '65'
						
					);
	
		$dataall = array_merge($data,$sesio);

		if($id != '')
		{
		    		    
			$login_id=$this->session->userdata('user_id');

		    $newstg = $this->input->post('stage');
		    $stg = $this->db->query("select * from tbl_leads where lead_id='$id' ");
			$getStg = $stg->row();
			$oldstg = $getStg->stage;
			if($oldstg != $newstg){
				$this->software_log_insert($id,'Lead','Lead','Stage',$oldstg,$newstg,'Lead Stage Changed');
			}

			$newusr = $this->input->post('user_resp');
			$usr = $this->db->query("select * from tbl_leads where lead_id='$id' ");
			$getUsr = $usr->row();
			$oldusr = $getUsr->user_resp;

			

			//==//==========Software Log Data============

			$pid=$this->db->query("select * from tbl_software_log where slog_id='$id' and mdl_name='Lead' ");
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
			$this->db->query("update tbl_software_log set seen_id='$updtId' where slog_id='$id' AND mdl_name='Lead' ");
			//============End===============

			if($oldusr != $newusr){
				$this->software_log_insert($id,'Lead','Lead','User',$oldusr,$newusr,'Lead User Changed');
				$this->db->query("update tbl_software_log set seen_id='$newstatusid' where slog_id='$id' AND mdl_name='Lead' ");
			}

			

			$this->db->query("update  tbl_note set note_desc = '$summernote' where main_lead_id_note = 'main_lead' and note_logid = '$id'");

			$this->Model_admin_login->update_user($pri_col,$table_name,$id,$data);

			//==========Lead Seen_id Update============

			$snid=$this->db->query("select * from tbl_leads where lead_id='$id' ");
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
				$this->db->query("update tbl_leads set seen_id='$newseenid' where lead_id='$id' ");	
			}
			
			//============End===============

		  echo 2;

		}
		else
		{    

		$this->Model_admin_login->insert_user($table_name,$dataall);

   		$Lead_Id = $this->db->insert_id();
		   $this->software_log_insert($Lead_Id,'Lead','New','Lead Create','','','Lead Created');
   	
	    $newusr = $this->input->post('user_resp');
	    $lgnusr = $this->session->userdata('user_id');
	    if($newusr != $lgnusr ){
	    	$this->software_log_insert($Lead_Id,'Lead','Lead','User',$lgnusr,$newusr,'Lead User Changed');
	    }

		//==============Software Log Data & Lead Seen_id Updated====
	    $usrid=$lgnusr.",".$newusr;
		$this->db->query("update tbl_software_log set seen_id='$usrid' where slog_id='$Lead_Id' and mdl_name = 'Lead' ");			
		$this->db->query("update tbl_leads set seen_id='$usrid' where lead_id='$Lead_Id' ");
		//===============End=============

			    $note_des = $this->input->post('summernote');
			    	$this->update_note_log($Lead_Id,'main_lead','Lead',$note_des);	

			echo 1;
		}

		//===============Lead Multi Details==========================

		$table_multi = 'tbl_mulit_orgz';

		$datam_org = array(
						
							'morg_logid'   => $Lead_Id,
							'morg_type'	   => 'Organization',	
							'orgid'		   => $org_Id,
							'default_org'  => 'Y'
						   
						  );

		$datam_cnt = array(
						
							'morg_logid'   => $Lead_Id,
							'morg_type'	   => 'Contact',	
							'orgid'		   => $con_Id,
							'default_org'  => 'Y'
						   
						  );

		if($id =="")
		{
			$data_org_all = array_merge($datam_org,$sesio);
			$this->Model_admin_login->insert_user($table_multi,$data_org_all);

			$data_cnt_all = array_merge($datam_cnt,$sesio);
			$this->Model_admin_login->insert_user($table_multi,$data_cnt_all);	
		}
		 
				
}


function deleteLead()
{
	$id=$_GET['lead_id'];
	if($id!='')
	{
		$this->db->query("delete from tbl_leads where lead_id='".$id."' ");

		$this->db->query("delete from tbl_lead_rates where lead_id='$id' ");
		$this->db->query("delete from tbl_mulit_orgz where morg_logid='$id' ");
		$this->db->query("delete from tbl_file where file_type='Lead' AND file_logid='$id' ");
		$this->db->query("delete from tbl_note where note_type='Lead' AND note_logid='$id' ");
		$this->db->query("delete from tbl_software_log where mdl_name='Lead' AND slog_id='$id' ");
	}
	redirect('lead/Lead/manage_lead');
}


public function update_lead_assignto()
{
	$id = $this->input->post('leadassignid');
	$uid = $this->input->post('assign_user');
	// print_r($_POST);die;

		if($id != '')
		{	

			$login_id=$this->session->userdata('user_id');

			$newusr = $this->input->post('assign_user');
			$usr = $this->db->query("select * from tbl_leads where lead_id='$id' ");
			$getUsr = $usr->row();
			$oldusr = $getUsr->user_resp;

			//==========Software Log Data============

			$pid=$this->db->query("select * from tbl_software_log where slog_id='$id' and mdl_name='Lead' ");
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
				$this->software_log_insert($id,'Lead','Lead','User',$oldusr,$newusr,'Lead User Changed');
				$this->db->query("update tbl_software_log set seen_id='$newstatusid' where slog_id='$id' AND mdl_name='Lead' ");
			}


			//==========Lead Seen_id Update============

			$snid=$this->db->query("select * from tbl_leads where lead_id='$id' ");
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
				$this->db->query("update tbl_leads set seen_id='$newseenid' where lead_id='$id' ");	
			}
			
			//============End===============
				
		}

	if($uid != '')
	{	
		date_default_timezone_set("Asia/Kolkata");
		$dtTime = date('Y-m-d H:i:s');
		$this->db->query("update tbl_leads set user_resp='".$uid."',last_update='$dtTime' where lead_id='".$id."' ");
		echo $uid;
	}
}

public function insert_lead_inner()
{

		@extract($_POST);
		// echo '<pre>';
	    //   print_r($_POST);
		// echo '</pre>';die;

		$table_name     = 'tbl_leads';
		$table_contact  = 'tbl_contact_m';
		$table_org		= 'tbl_organization';
		$pri_col        = 'lead_id';
		$cont_colum  	= 'contact_id';
		$org_colum		= 'org_id';
		
		$id = $this->input->post('lead_idz');

		///======Organization===========///////////

        $dataOrg = array(
							'org_name' => $this->input->post('org_name'),
						);

        $sesio = array(

						'comp_id' => $this->session->userdata('comp_id'),
						'brnh_id' => $this->session->userdata('brnh_id'),
						'maker_id' => $this->session->userdata('user_id'),
						'author_id' => $this->session->userdata('user_id'),
						'maker_date'=> date('y-m-d'),
						'author_date'=> date('y-m-d')
				       
				       );
         
        $dataOranization = array_merge($dataOrg,$sesio);
        
		if($this->input->post('orgidcontant') != ""){
			$org_Id = $this->input->post('orgidcontant');
	        $this->Model_admin_login->update_user($org_colum,$table_org,$org_Id,$dataOrg);
		}else{
           $this->Model_admin_login->insert_user($table_org,$dataOranization);
           $org_Id = $this->db->insert_id();
           $this->software_log_insert($org_Id,'Orgz','New','Orgz Create','','','Organization Created');
        }
		
		////==============Contact=================////////////////
        
        $email_val = json_encode($this->input->post('email_id'),true);
        $phone_no  = json_encode($this->input->post('phone_no'),true);

		$CntData = array(

				            'contact_name' => $this->input->post('contact'),
							'org_name'     => $org_Id,
							'email'        => $email_val,
							'phone'        => $phone_no,
							'address'      => $this->input->post('address'),
					   
					    );

		$contactdata = array_merge($CntData,$sesio);

        if($this->input->post('oldcontact') !=""){
		   	$con_Id = $this->input->post('oldcontact');
            $this->Model_admin_login->update_user($cont_colum,$table_contact,$con_Id,$CntData);
		 }else{  
		    $this->Model_admin_login->insert_user($table_contact,$contactdata);
		    $con_Id = $this->db->insert_id();
		    $this->db->query("update tbl_organization set contact_id='$con_Id' where org_id='$org_Id' ");
		    $this->software_log_insert($con_Id,'Contact','New','Contact Create','','','Contact Created');
		}
		

		//==============================Lead============================

		date_default_timezone_set("Asia/Kolkata");
		$dtTime = date('Y-m-d H:i:s');

       $data = array(
		    'contact_id'     => $con_Id,
			'org_id'         => $org_Id,
			'lead_number'    => $this->input->post('lead_number'),
			'user_resp'      => $this->input->post('assin_user'),
			'industry'       => $this->input->post('industry'),
			//'source'         => $this->input->post('source'),
			//'probability'    => $this->input->post('probability'),
			'last_update'    => $dtTime,
			'closuredate'    => $this->input->post('closuredate'),				
			'opp_value'      => $this->input->post('opp_value'),
			'stage'          => $this->input->post('stage'),
			'discription'    => $this->input->post('summernote'),
			'lead_state'     => '65'
			
		);
			 
	
		$dataall = array_merge($data,$sesio);

		if($id != '')
		{	

			$login_id=$this->session->userdata('user_id');

			$newstg = $this->input->post('stage');
		    $stg = $this->db->query("select * from tbl_leads where lead_id='$id' ");
			$getStg = $stg->row();
			$oldstg = $getStg->stage;
			if($oldstg != $newstg){
				$this->software_log_insert($id,'Lead','Lead','Stage',$oldstg,$newstg,'Lead Stage Changed');
			}

			$newusr = $this->input->post('assin_user');
			$usr = $this->db->query("select * from tbl_leads where lead_id='$id' ");
			$getUsr = $usr->row();
			$oldusr = $getUsr->user_resp;

			//==========Software Log Data============

			$pid=$this->db->query("select * from tbl_software_log where slog_id='$id' and mdl_name='Lead' ");
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
			$this->db->query("update tbl_software_log set seen_id='$updtId' where slog_id='$id' AND mdl_name='Lead' ");
			//============End===============

			if($oldusr != $newusr){
				$this->software_log_insert($id,'Lead','Lead','User',$oldusr,$newusr,'Lead User Changed');
				$this->db->query("update tbl_software_log set seen_id='$newstatusid' where slog_id='$id' AND mdl_name='Lead' ");
			}

			$this->db->query("update  tbl_note set note_desc = '$summernote' where main_lead_id_note = 'main_lead' and note_logid = '$id'");

			$this->Model_admin_login->update_user($pri_col,$table_name,$id,$data);

			//==========Lead Seen_id Update============

			$snid=$this->db->query("select * from tbl_leads where lead_id='$id' ");
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
				$this->db->query("update tbl_leads set seen_id='$newseenid' where lead_id='$id' ");	
			}
			
			//============End===============
			echo $id;

		} else {    

		$this->Model_admin_login->insert_user($table_name,$dataall);

   		$Lead_Id = $this->db->insert_id();
		   $this->software_log_insert($Lead_Id,'Lead','New','Lead Create','','','Lead Created');
   	
	    $newusr = $this->input->post('user_resp');
	    $lgnusr = $this->session->userdata('user_id');
	    if($newusr != $lgnusr ){
	    	$this->software_log_insert($Lead_Id,'Lead','Lead','User',$lgnusr,$newusr,'Lead User Changed');
	    }

		//==============Software Log Data & Lead Seen_id Updated====
	    $usrid=$lgnusr.",".$newusr;
		$this->db->query("update tbl_software_log set seen_id='$usrid' where slog_id='$Lead_Id' and mdl_name = 'Lead' ");			
		$this->db->query("update tbl_leads set seen_id='$usrid' where lead_id='$Lead_Id' ");
		//===============End=============

			    $note_des = $this->input->post('summernote');
			    	$this->update_note_log($Lead_Id,'main_lead','Lead',$note_des);	

			echo $id;
		}



		// if($id != '')
		// {
		//   $this->Model_admin_login->update_user($pri_col,$table_name,$id,$data);
		//   $this->db->query("update  tbl_note set note_desc = '$summernote' where main_lead_id_note = 'main_lead' and note_logid = '$id'");
		//   echo $id;
		// }


		//===============Lead Multi Details==========================

		$table_multi = 'tbl_mulit_orgz';

		$datam_org = array(
						
							'morg_logid'   => $Lead_Id,
							'morg_type'	   => 'Organization',	
							'orgid'		   => $org_Id,
							'default_org'  => 'Y'
						   
						  );

		$datam_cnt = array(
						
							'morg_logid'   => $Lead_Id,
							'morg_type'	   => 'Contact',	
							'orgid'		   => $con_Id,
							'default_org'  => 'Y'
						   
						  );

		if($id =="")
		{
			$data_org_all = array_merge($datam_org,$sesio);
			$this->Model_admin_login->insert_user($table_multi,$data_org_all);

			$data_cnt_all = array_merge($datam_cnt,$sesio);
			$this->Model_admin_login->insert_user($table_multi,$data_cnt_all);	
		}

}


function ajax_innerLeadData()
{
	$param = $this->input->post('id');
    $data['result'] = $this->model_lead->getLeadDtl($param);
    $this->load->view('load-ajax-lead',$data);
}


     
 function  ajaxget_contactAlldata()
 {
   
   $ipt = $this->input->post('id');
   //$sql = "select *,C.address as caddress,C.email as cemail,C.phone as cphone,C.description as cdescrption,O.org_name as oname,O.phone_no as ophone_no,O.email as oemail,O.website as website,O.address as oaddress FROM tbl_contact_m C LEFT JOIN tbl_organization O ON O.org_id = C.Org_name WHERE C.contact_id = $ipt";
   $sql = "select *,address as caddress,email as cemail,phone as cphone FROM tbl_contact_m WHERE contact_id = $ipt";
   $result = $this->db->query($sql)->row();
   //print_r($result);
   if(sizeof($result) > 0)
    echo json_encode($result,true);
   else
    echo false;

}

function ajaxget_organizationAlldata()
{

       $ipt = $this->input->post('id');
       $sql = "select * FROM  tbl_organization WHERE org_id = $ipt";
       $result = $this->db->query($sql)->row();
      
       if(sizeof($result) > 0)
          echo json_encode($result,true);
       else
          echo false;
      
}

	

   function ajax_CntctOrgData(){
       $ipt = $this->input->post('id');
       $sql = "select * FROM  tbl_mulit_orgz WHERE morg_logid=$ipt AND morg_type='Contact' ORDER BY morg_id";
       $result = $this->db->query($sql)->result();

       foreach ($result as $getOrg) {

       	$org = $this->db->query("select * from tbl_organization where org_id='".$getOrg->orgid."' ");
       	$getOdata = $org->row();
       	$getNum = $org->num_rows();
       	if($getNum > 0){
       	$data 
       	?>
       	<option value="<?=$getOdata->org_id?>"><?=$getOdata->org_name?></option>
       	<?php 
      	}
       }
      	
       //if(sizeof($getOdata) > 0)
          //echo json_encode($result,true);
      	if($getNum > 0)
       	  echo $data;
       else
          echo 1;
      
   }


public function insert_lead_note()
{
	@extract($_POST);

	 // echo '<pre>';
	 // print_r($_POST);die;
	 // echo '</pre>';

	$table_name = 'tbl_note';
	$pri_col    = 'note_id';

	$id = $this->input->post('noteid');
	echo $this->input->post('leadidno');

	$this->load->model('Model_admin_login');

	$data = array(
						'note_logid' => $this->input->post('leadidno'),
						'note_type'	 => 'Lead',	
						'main_lead_id_note'	=>'Inner Lead',
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
		
		
		date_default_timezone_set("Asia/Kolkata");
		$dtTime = date('Y-m-d H:i:s');

		if($id != '')
		{
			$leadidno = $this->input->post('leadidno');
			$note_desc = $this->input->post('note_desc');
			$main_id   = $this->input->post('main_id');

			$this->Model_admin_login->update_user($pri_col,$table_name,$id,$data);
			//$this->db->query("update tbl_leads set discription = '$note_desc' where main_lead_id = '$main_id' and lead_id = '$leadidno' ");
			$this->db->query("update tbl_leads set last_update = '$dtTime' where lead_id = '$leadidno' ");
		  	//echo 2;	
		}
		else
		{	
			$data_merger = array_merge($data,$sesio);
			$this->Model_admin_login->insert_user($table_name,$data_merger);
			$leadidno = $this->input->post('leadidno');
			$this->db->query("update tbl_leads set last_update = '$dtTime' where lead_id = '$leadidno' ");
	    	//echo 1;
		}

		////-===================Software Log======================

		$leadidno = $this->input->post('leadidno');
	
	    if($leadidno != '' & $id == '')
		{	
			
			$note_desc = $this->input->post('note_desc');
				//$this->software_log_insert($leadidno,'Lead','Note','Lead Notes','','','Lead Remarks Added');
				$this->software_log_insert($leadidno,'Lead','Note','Lead Notes','','',$note_desc);

		//==========Software Log Data============

		$pid=$this->db->query("select * from tbl_software_log where slog_id='$leadidno' and mdl_name='Lead' ");
		$fetchId=$pid->row();			
		$aid=$fetchId->seen_id;

		$bid=explode(",",$aid);
		$cid=array_unique($bid);
		$updtId=implode(",",$cid);

		$this->db->query("update tbl_software_log set seen_id='$updtId' where slog_id='$leadidno' AND mdl_name='Lead' ");
		//============End===============

		}

}


public function ajax_ListNotesData()
{	
	$nid = $this->input->post('id');
	$data['result'] = $this->model_lead->getLeadDtl($nid);
	$this->load->view('load-ajax-lead',$data);
}

public function insert_lead_file()
{
	@extract($_POST);

	  // echo '<pre>';
	  // print_r($_POST);die;
	 // echo '</pre>';

	$table_name = 'tbl_file';
	$pri_col    = 'file_id';

	$id = $this->input->post('fileid');
	echo $this->input->post('leadidno');

		@$branchQuery2= $this->db->query("SELECT * FROM $table_name where file_id = '$id' ");
		$branchFetch2 = $branchQuery2->row();

	$this->load->model('Model_admin_login');

		if($_FILES['files_name']['name']!='')
		{
			$target = "crmfiles/leadfile/"; 
			$target1 =$target . @date(U)."_".( $_FILES['files_name']['name']);
			$filesname=@date(U)."_".( $_FILES['files_name']['name']);
			move_uploaded_file($_FILES['files_name']['tmp_name'], $target1);
		}
		else
		{
			$filesname=$branchFetch2->files_name;
		}

	$data = array(
						'file_logid' => $this->input->post('leadidno'),
						'file_type'	 => 'Lead',	
						'files_name' => $filesname,
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

		$leadidno = $this->input->post('leadidno');
	
	    if($leadidno != '' & $id == '')
		{	
			
			$files_desc = $this->input->post('files_desc');
			
				$this->software_log_insert($leadidno,'Lead','File',$filesname,'','',$files_desc);
		}

}

public function ajax_ListFilesData()
{
	$nid = $this->input->post('id');
	$data['result'] = $this->model_lead->getLeadDtl($nid);
	$this->load->view('load-ajax-lead',$data);
}


public function insert_lead_rates()
{	

	 @extract($_POST);

	echo $lid = $this->input->post('rateleadid');
	 $rateid = $this->input->post('rateid');


	 	//====================== Lead Price Special ==============
	
		$table_name = 'tbl_lead_rates';
		$pri_col = 'rates_id';

		$data = array(

						'lead_id' =>$this->input->post('rateleadid'), 
						'lead_rates' =>$this->input->post('lead_rates'),
						'lead_from' =>$this->input->post('lead_from'),
						'lead_to'   =>$this->input->post('lead_to'),
						'lead_product'   =>$this->input->post('lead_product'),
						'rate_type'   =>$this->input->post('rate_type'),
						'bsc_frght' =>$this->input->post('bsc_frght'),
						'gr_chrg'   =>$this->input->post('gr_chrg'),
						'lbr_chrg'  =>$this->input->post('lbr_chrg'),
						'enrt_chrg' =>$this->input->post('enrt_chrg'),
						'dlvry_charge' =>$this->input->post('dlvry_charge'),
						'misc_charge'  =>$this->input->post('misc_charge'),
						'risk_charge'  =>$this->input->post('risk_charge'),
						'rate_rmrks'  =>$this->input->post('rate_rmrks'),

					 );

		$sesio = array(

						'comp_id' => $this->session->userdata('comp_id'),
						'brnh_id' => $this->session->userdata('brnh_id'),
						'maker_id' => $this->session->userdata('user_id'),
						'author_id' => $this->session->userdata('user_id'),
						'maker_date'=> date('y-m-d'),
						'author_date'=> date('y-m-d')
				    );

		$data_all = array_merge($data,$sesio);

		if($rateid != ''){
			$this->Model_admin_login->update_user($pri_col,$table_name,$rateid,$data);
		}
		else{
			$this->Model_admin_login->insert_user($table_name,$data_all);			
		}

		//====================tbl_file & software Log================

		$table_file = 'tbl_file';

		if($_FILES['rates_file']['name']!='')
		{
			$target = "crmfiles/leadfile/"; 
			$target1 =$target . @date(U)."_".( $_FILES['rates_file']['name']);
			$filesname=@date(U)."_".( $_FILES['rates_file']['name']);
			move_uploaded_file($_FILES['rates_file']['tmp_name'], $target1);
		}
		else
		{
			$filesname='';
		}

			$datafile = array(
						'file_logid' => $lid,
						'file_type'	 => 'Lead',	
						'files_name' => $filesname,
						//'files_desc' => $this->input->post('files_desc')
					   
					  );			
		
		$data_merger = array_merge($datafile,$sesio);

		if($lid != '' && $rateid == '' && $filesname !='' ){
			$this->Model_admin_login->insert_user($table_file,$data_merger);
		}
		////-===================Software Log======================
	
	    if($lid != '' && $rateid == '' && $filesname !='' ){							
			$this->software_log_insert($lid,'Lead','File',$filesname,'','','');
		}

		//=== end =====

}


public function ajax_LeadRateData()
{
	$lid = $this->input->post('id');
	$data['result'] = $this->model_lead->getLeadDtl($lid);
	$this->load->view('load-ajax-lead',$data);
}


public function ajax_chKRates()
{
	$leadid=$this->input->post('id');
	$rates=$this->db->query("select * from tbl_lead_rates where lead_id='$leadid' ");
	$cntRates=$rates->num_rows();
	if($cntRates > 0)
	{
		echo 0;
	}
	else
	{
		echo 1;
	}
}

public function update_lead_stage()
{
	@extract($_POST);

	$lid = $this->input->post('stage_leadid');
	$newstg = $this->input->post('new_stage');

	$stg = $this->db->query("select * from tbl_leads where lead_id='$lid' ");
	$getStg = $stg->row();
	$oldstg = $getStg->stage;
	if($oldstg != $newstg){
		$this->software_log_insert($lid,'Lead','Lead','Stage',$oldstg,$newstg,'Lead Stage Changed');
		//==========Software Log Data============

			$pid=$this->db->query("select * from tbl_software_log where slog_id='$lid' and mdl_name='Lead' ");
			$fetchId=$pid->row();			
			$aid=$fetchId->seen_id;

			$bid=explode(",",$aid);
			$cid=array_unique($bid);
			$updtId=implode(",",$cid);

			$this->db->query("update tbl_software_log set seen_id='$updtId' where slog_id='$lid' AND mdl_name='Lead' ");
		//============End===============
	}


	if($lid !='')
	{	
		date_default_timezone_set("Asia/Kolkata");
		$dtTime = date('Y-m-d H:i:s');
		$this->db->query("update tbl_leads set stage='$newstg',last_update='$dtTime' where lead_id='$lid'");
		echo $lid;
	}

}


public function ajax_ListStageData()
{
	$sid = $this->input->post('id');
	$data['result'] = $this->model_lead->getLeadDtl($sid);
	$this->load->view('load-ajax-lead',$data);	
}


//---state

public function update_lead_state()
{
	
	@extract($_POST);

	// echo '<pre>';
	// print_r($_POST);die;
	
	$lid = $this->input->post('state_leadid');
	//$ldstg = $this->input->post('ldstg');

	$stat = $this->input->post('new_state');
	$desc = $this->input->post('lead_stat_desc');
	$docket = $this->input->post('docket_no');


	$stats = $this->db->query("select * from tbl_leads where lead_id='$lid' ");
	$getStas = $stats->row();
	$oldstatus = $getStas->lead_state;
	if($oldstatus != $stat){
		$this->software_log_insert($lid,'Lead','Lead','Status',$oldstatus,$stat,'Lead Status Changed');
		//==========Software Log Data============

			$pid=$this->db->query("select * from tbl_software_log where slog_id='$lid' and mdl_name='Lead' ");
			$fetchId=$pid->row();			
			$aid=$fetchId->seen_id;

			$bid=explode(",",$aid);
			$cid=array_unique($bid);
			$updtId=implode(",",$cid);

			$this->db->query("update tbl_software_log set seen_id='$updtId' where slog_id='$lid' AND mdl_name='Lead' ");
		//============End===============
	}
	date_default_timezone_set("Asia/Kolkata");
	$crntdate=date('Y/m/d H:i');
	$expt_dt=date('Y/m/d H:i', strtotime($crntdate. ' + 7 days'));


	//date_default_timezone_set("Asia/Kolkata");
	$dtTime = date('Y-m-d H:i:s');

	if($stat == 65)
	{		
		$this->db->query("update tbl_leads set lead_state='".$stat."', stage='17',lead_stat_desc='".$desc."',closuredate='$expt_dt',last_update='$dtTime' where lead_id='".$lid."' ");
	}
	elseif($stat == 66)
	{
		$this->db->query("update tbl_leads set lead_state='".$stat."', stage='78',docket_no='".$docket."',lead_stat_desc='".$desc."',last_update='$dtTime' where lead_id='".$lid."' ");
		$this->software_log_insert($lid,'Lead','Status','Won','','',$docket);
	}
	elseif($stat == 67)
	{
		$this->db->query("update tbl_leads set lead_state='".$stat."', stage='17',lead_stat_desc='".$desc."',last_update='$dtTime' where lead_id='".$lid."' ");	
	}
	elseif($stat == 79)
	{
		$this->db->query("update tbl_leads set lead_state='".$stat."', stage='17',lead_stat_desc='".$desc."',last_update='$dtTime' where lead_id='".$lid."' ");	
	}
		

		//====================tbl_file & software Log================

		$table_file = 'tbl_file';
	    //$file_col    = 'file_id';

		if($_FILES['sts_file']['name']!='')
		{
			$target = "crmfiles/leadfile/"; 
			$target1 =$target . @date(U)."_".( $_FILES['sts_file']['name']);
			$filesname=@date(U)."_".( $_FILES['sts_file']['name']);
			move_uploaded_file($_FILES['sts_file']['tmp_name'], $target1);
		}
		else
		{
			$filesname='';
		}

			$data = array(
						'file_logid' => $lid,
						'file_type'	 => 'Lead',	
						'files_name' => $filesname,
						//'files_desc' => $this->input->post('files_desc')
					   
					  );
			$sesio = array(

						'comp_id' => $this->session->userdata('comp_id'),
						'brnh_id' => $this->session->userdata('brnh_id'),
						'maker_id' => $this->session->userdata('user_id'),
						'author_id' => $this->session->userdata('user_id'),
						'maker_date'=> date('y-m-d'),
						'author_date'=> date('y-m-d')
				    );
		
		$data_merger = array_merge($data,$sesio);

		if($lid != '' && $filesname != '')
		{
			$this->Model_admin_login->insert_user($table_file,$data_merger);
		}
		////-===================Software Log======================
	
	    if($lid != '' && $filesname != '')
		{							
			$this->software_log_insert($lid,'Lead','File',$filesname,'','','');
		}

		//=== end =====

	echo $lid;

}


public function ajax_ListStateData()
{
	$ldid = $this->input->post('id');
	$data['result'] = $this->model_lead->getLeadDtl($ldid);
	$this->load->view('load-ajax-lead',$data);	
}



//================Multi Orgz===================

public function insert_multi_orgz()
{
	@extract($_POST);

	 // echo '<pre>';
	 // print_r($_POST);die;
	 // echo '</pre>';

	$table_org = 'tbl_organization';
	$pri_org = 'org_id';
	$table_cntct = 'tbl_contact_m';
	$pri_cnt = 'contact_id';

    //=========================Organization============

	$email_val = json_encode($this->input->post('demail_id'),true);
    $phone_no  = json_encode($this->input->post('dphone_no'),true);
	
	$data_org= array(
			
					'org_name' => $this->input->post('dorg_name'),
					'website'  => $this->input->post('dwebsite'),
					'phone_no' => $phone_no,
					'email'    => $email_val,					
					'address'  => $this->input->post('daddress'),
					'city'     => $this->input->post('dcity'),
					'state_id' => $this->input->post('dstate_id'),
					'pin_code' => $this->input->post('dpin_code'),
					'country'  => $this->input->post('dcountry_id'),
					'description' => $this->input->post('org_notes')

				);

		$sesio = array(
					'comp_id' => $this->session->userdata('comp_id'),
					'brnh_id' => $this->session->userdata('brnh_id'),
					'maker_id' => $this->session->userdata('user_id'),
					'author_id' => $this->session->userdata('user_id'),
					'maker_date'=> date('y-m-d'),
					'author_date'=> date('y-m-d')
				  );

		    // echo '<pre>';
			//    print_r($data);
			// echo '</pre>';die;

		$dataall_org = array_merge($data_org,$sesio);

		if($this->input->post('doldorgid') != "")
		{
			$org_Id = $this->input->post('doldorgid');
			$this->Model_admin_login->update_user($pri_org,$table_org,$org_Id,$data_org);
			//echo 2;	
		}			
		else
		{
			$this->Model_admin_login->insert_user($table_org,$dataall_org);
			$org_Id = $this->db->insert_id();
			$this->software_log_insert($org_Id,'Orgz','New','Orgz Create','','','Organization Created');
			//echo 1;
		}
		//================Contact=======================

			$cemail_val = json_encode($this->input->post('dcemail_id'),true);
			$cphone_no = json_encode($this->input->post('dcphone_no'),true);

			$cntct_data = array(

								'contact_name' => $this->input->post('dcontact_name'),
								'designation'  => $this->input->post('ddesignation'),
								'org_name' => $org_Id,
								'email' => $cemail_val,
								'phone' => $cphone_no

							   );

			$contactdata = array_merge($cntct_data,$sesio);

	        if($this->input->post('doldcontact_id') !=""){
			   	$con_Id = $this->input->post('doldcontact_id');
	            $this->Model_admin_login->update_user($pri_cnt,$table_cntct,$con_Id,$cntct_data);
			 }else{  
			 	if($this->input->post('dcontact_name') != ""){
			    $this->Model_admin_login->insert_user($table_cntct,$contactdata);
			    $con_Id = $this->db->insert_id();
			    $this->db->query("update tbl_organization set contact_id='$con_Id' where org_id='$org_Id' ");
				$this->software_log_insert($con_Id,'Contact','New','Contact Create','','','Contact Created');
			  }
			}

			
	//===============Multi table=====================


	$table_name = 'tbl_mulit_orgz';
	$pri_col    = 'morg_id';

	$id = $this->input->post('omorgid');
	$lead_id = $this->input->post('oleadid');
	$morg_notes = $this->input->post('org_notes');		


	$datam = array(
						'morg_logid'   => $lead_id,
						'morg_type'	   => 'Organization',	
						'orgid'		   => $org_Id,
						'morg_details' => $morg_notes
					   
					  );
				
		if($id != '')
		{
			$this->Model_admin_login->update_user($pri_col,$table_name,$id,$datam);
		  	//echo 2;	
		}
		else
		{	
			$data_merger = array_merge($datam,$sesio);
			$this->Model_admin_login->insert_user($table_name,$data_merger);
	    	//echo 1;
		}

	echo $lead_id;

}

public function ajax_ListMultiOrgData()
{	
	$nid = $this->input->post('id');
	$data['result'] = $this->model_lead->getLeadDtl($nid);
	$this->load->view('load-ajax-lead',$data);
}


	///======================Muti Contact=========================


public function insert_multi_cntct()
{

	    @extract($_POST);

	 	$table_cnt = 'tbl_contact_m';
		$pri_cnt   = 'contact_id';
		$table_org = 'tbl_organization';
		$pri_org   = 'org_id';

		//===============Contact=====================
	 	
		$email_val = json_encode($this->input->post('memail_id'),true);
        $phone_no  = json_encode($this->input->post('mphone_no'),true);

		$data_cnt = array(
							'contact_name' => $this->input->post('mcontact_name'),
							'designation'  => $this->input->post('mdesignation'),		       
							'email'        => $email_val,
							'phone'        => $phone_no,
							'address'      => $this->input->post('maddress'),
							'city_name'    => $this->input->post('mcity'),
							'state_id'     => $this->input->post('mstate_id'),
							'pincode'      => $this->input->post('mpin_code'),
							'country'      => $this->input->post('mcountry_id'),
							'description'  => $this->input->post('cnt_notes')
					     );

	    $sesio = array(
						'comp_id' => $this->session->userdata('comp_id'),
						'brnh_id' => $this->session->userdata('brnh_id'),
						'maker_id' => $this->session->userdata('user_id'),
						'author_id' => $this->session->userdata('user_id'),
						'maker_date'=> date('y-m-d'),
						'author_date'=> date('y-m-d')
					 );
		
		
		
		// echo '<pre>';
		// print_r($_POST);
		// echo '</pre>';die;
		$data_cntcnt = array_merge($data_cnt,$sesio);

	    if($this->input->post('moldcontact_id') !="")
	    {
		   	$con_Id = $this->input->post('moldcontact_id');
            $this->Model_admin_login->update_user($pri_cnt,$table_cnt,$con_Id,$data_cnt);
			//echo 2;
		}	
		else
		{							
			$this->Model_admin_login->insert_user($table_cnt,$data_cntcnt);
			$con_Id = $this->db->insert_id();
			$this->software_log_insert($con_Id,'Contact','New','Contact Create','','','Contact Created');	
			//echo 1; 	
		}

		//====================Organization=================

			$oemail_val = json_encode($this->input->post('moemail_id'),true);
			$ophone_no = json_encode($this->input->post('mophone_no'),true);

			$org_data = array(

								'org_name'   => $this->input->post('morg_name'),
								'website'    => $this->input->post('mwebsite'),
								'contact_id' => $con_Id,
								'email'      => $oemail_val,
								'phone_no'   => $ophone_no

							   );

			$data_orgz = array_merge($org_data,$sesio);

	        if($this->input->post('moldorgid') != ""){
			   	$org_Id = $this->input->post('moldorgid');
	            $this->Model_admin_login->update_user($pri_org,$table_org,$org_Id,$org_data);
			 }else{  
			 	if($this->input->post('morg_name') != ""){
				 	$this->Model_admin_login->insert_user($table_org,$data_orgz);
				    $org_Id = $this->db->insert_id();
				    $this->db->query("update tbl_contact_m set org_name='$org_Id' where contact_id='$con_Id' ");
					$this->software_log_insert($org_Id,'Orgz','New','Orgz Create','','','Organization Created');
			 	}			    
			}

			
	//===============Multi table=====================


	$table_name = 'tbl_mulit_orgz';
	$pri_col    = 'morg_id';

	$id = $this->input->post('cmorgid');
	$lead_id = $this->input->post('cleadid');
	$morg_notes = $this->input->post('cnt_notes');		


	$datam = array(
						'morg_logid'   => $lead_id,
						'morg_type'	   => 'Contact',	
						'orgid'		   => $con_Id,
						'morg_details' => $morg_notes
					   
					  );
				
		if($id != '')
		{
			$this->Model_admin_login->update_user($pri_col,$table_name,$id,$datam);
		  	//echo 2;	
		}
		else
		{	
			$data_merger = array_merge($datam,$sesio);
			$this->Model_admin_login->insert_user($table_name,$data_merger);
	    	//echo 1;
		}

	echo $lead_id;

}

public function ajax_ListMultiCntData()
{	
	$nid = $this->input->post('id');
	$data['result'] = $this->model_lead->getLeadDtl($nid);
	$this->load->view('load-ajax-lead',$data);
}




public function insert_task_lead()
{

	
	@extract($_POST);
	
	//print_r($_POST);die;

	$table_name_log ='tbl_task_log';
	$table_name = 'tbl_task';
	$pri_col = 'task_id';
	
	$leadidz = $this->input->post('lead_idz');
	
	$this->load->model('Model_admin_login');

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
					'contact_person' => $this->input->post('tcontact_person'),
					'org_name' => $this->input->post('torg_name'),
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
		$this->Model_admin_login->insert_user($table_name,$dataall);
		


		//=======================Task Log=====================

		$lastid=$this->db->insert_id();	
		
		$datalog= array(
				
						'task_id' => $lastid,
						'task_name' => $this->input->post('task_name'),
						'date_due' => $this->input->post('due_date'),
						//'reminder_date'  => $this->input->post('reminder_date'),
						'progress_percnt'  => $this->input->post('progress'),
						'priority'  => $this->input->post('priority'),
						'task_status' => $this->input->post('status'),
						'user_resp' => $this->input->post('user_resp'),
						'lead_id' => $this->input->post('leadid'),
						'contact_person' => $this->input->post('tcontact_person'),
						'org_name' => $this->input->post('torg_name'),
						'description' => $this->input->post('snotes'),
						//'visibility	' => $this->input->post('optionsRadios'),									
							
				      	);
				
		$datalogs = array_merge($datalog,$sesio);
		$this->Model_admin_login->insert_user($table_name_log,$datalogs);
		echo $leadidz;

		////-===================Software Log======================

		//$lead_id = $this->input->post('leadid');
	
	    if($lastid != '')
		{	

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

				$lead_id = $this->input->post('leadid');
				if($lead_id != '')
				{
					$this->update_note_log($lead_id,'main_task','Task',$description);	
				}
				

			//==============Software Log Data & Task Seen_id====
		    $usrid=$lgnusr.",".$newusr;
			$this->db->query("update tbl_software_log set seen_id='$usrid' where slog_id='$lastid' and mdl_name = 'Task' ");			
			$this->db->query("update tbl_task set seen_id='$usrid' where task_id='$lastid' ");
			//===============End=============
		}


}

public function ajax_getInnerLeadTask()
{
	$lid = $this->input->post('id');
	$data['result'] = $this->model_lead->getLeadDtl($lid);
	$this->load->view('load-ajax-lead',$data);

}

//=================manage page lead task================

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

function ajax_updateLeadStatus()
{	
	$crdate = date('Y/m/d');
	$mysql = $this->db->query("SELECT * FROM tbl_leads WHERE SUBSTRING_INDEX(SUBSTRING_INDEX(closuredate, ' ', 1), ' ', -1) < '$crdate' ");
	$numcnt=$mysql->num_rows();

	if($numcnt > 0)
	{
		foreach ($mysql->result() as $getRow) {

			$this->db->query("update tbl_leads set lead_state='79', stage='17' where lead_id='".$getRow->lead_id."' ");
		}
		//echo $data=$this->ajax_ListLeadData();
	}

}


function ajax_readUnread()
{
	$login_id=$this->session->userdata('user_id');
	$id=$this->input->post('id');
	$sql=$this->db->query("select * from tbl_leads where lead_id='$id' ");
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
	$this->db->query("update tbl_leads set visibility='$ruid' where lead_id='$id' ");
	//echo "Successfully Update";
	//print_r($fvid);
}



function ajax_chk_docketno()
{
	$docketno=$this->input->post('dkt_no');
	$data=$this->db->query("select * from tbl_leads where docket_no='$docketno'");
	$getData=$data->num_rows();
	if($getData > 0)
		echo 1;
	else
		echo 0;
}

} ?>