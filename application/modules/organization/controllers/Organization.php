<?php
defined('BASEPATH') OR exit('No direct script access allowed');
error_reporting (E_ALL ^ E_NOTICE);
class Organization extends my_controller {

function __construct()
{
    parent::__construct(); 
    $this->load->library('pagination');
    $this->load->model('model_organization');
    $this->load->model('Model_admin_login');	
}


public function manage_organization()
{
	
	if($this->session->userdata('is_logged_in'))
	{	
		$data = $this->Manage_Organization_Data();
		$this->load->view('manage-organization',$data);
	}
	else
	{
		redirect('index');
	}
		
}


function orgview($param = FALSE)
{

     if($this->input->post('id') != "")
     {
   	     $param = $this->input->post('id');
         $data['result'] = $this->model_organization->getOrgDtl($param);
         $this->load->view('view-organization',$data);
   
      }else{
         $data['result'] = $this->model_organization->getOrgDtl($param);
	     $this->load->view('view-organization',$data);
     }
}

public function view_organization()
{
	
	if($this->input->get('id') !='')
	{	
		$param = $this->input->get('id');
        $data['result'] = $this->model_organization->getOrgDtl($param);
        $this->load->view('view-organization',$data);
	}
	else
	{
		redirect('index');
	}
		
}

public function Manage_Organization_Data()
{

		  $table_name='tbl_organization';
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
		  
		  $totalData   = $this->model_organization->count_org($table_name,'A',$this->input->get());

		  if($_GET['entries'] != '' && $_GET['filter'] != 'filter')
		  {
			 $url = site_url('/organization/Organization/manage_organization?entries='.$_GET['entries']);
		  }
		  elseif($_GET['filter'] == 'filter' || $_GET['entries'] != '')
		  {
			$url = site_url('/organization/Organization/manage_organization?filter='.$_GET['filter'].'&entries='.$_GET['entries']);
		  }
		  else
		  {
			$url = site_url('/organization/Organization/manage_organization?');
		  }

          $pagination = $this->ciPagination($url,$totalData,$sgmnt,$showEntries);
          
		  //$data=$this->user_function();
		  $data['dataConfig'] = array('total'=>$totalData,'perPage'=>$pagination['per_page'],'page'=>$pagination['page']);
		  $data['pagination'] = $this->pagination->create_links();
	
		  if($this->input->get('filter') == 'filter')   ////filter start ////
        	$data['result'] = $this->model_organization->filterOrgData($pagination['per_page'],$pagination['page'],$this->input->get());
          	else	
    		$data['result'] = $this->model_organization->getOrgkData($pagination['per_page'],$pagination['page']);


	      return $data;

}

public function ajax_ListOrgData()
{
    $data = $this->Manage_Organization_Data();
	$this->load->view('load-organization-data',$data);
}



public function insert_organization()
{
	
	@extract($_POST);
	
	$table_org = 'tbl_organization';
	$pri_org = 'org_id';
	$table_cntct = 'tbl_contact_m';
	$pri_cnt = 'contact_id';

    //=========================Organization============

	$email_val = json_encode($this->input->post('email_id'),true);
    $phone_no  = json_encode($this->input->post('phone_no'),true);
	
	$data_org= array(
			
					'org_name' => $this->input->post('org_name'),
					'website'  => $this->input->post('website'),
					'phone_no' => $phone_no,
					'email'    => $email_val,					
					'address'  => $this->input->post('address'),
					'city'     => $this->input->post('city'),
					'state_id' => $this->input->post('state_id'),
					'pin_code' => $this->input->post('pin_code'),
					'country'  => $this->input->post('country_id'),
					'description' => $this->input->post('snotes')

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

		if($this->input->post('oldorgid') != "")
		{
			$org_Id = $this->input->post('oldorgid');
			$this->Model_admin_login->update_user($pri_org,$table_org,$org_Id,$data_org);
			echo 2;	
		}			
		else
		{
			$this->Model_admin_login->insert_user($table_org,$dataall_org);
			$org_Id = $this->db->insert_id();
			$this->software_log_insert($org_Id,'Orgz','New','Orgz Create','','','Organization Created');
			echo 1;
		}
		//================Contact=======================

			$cemail_val = json_encode($this->input->post('cemail_id'),true);
			$cphone_no = json_encode($this->input->post('cphone_no'),true);

			$cntct_data = array(

								'contact_name' => $this->input->post('contact_name'),
								'designation'  => $this->input->post('designation'),
								'org_name' => $org_Id,
								'email' => $cemail_val,
								'phone' => $cphone_no

							   );

			$contactdata = array_merge($cntct_data,$sesio);

	        if($this->input->post('oldcontact_id') !=""){
			   	$con_Id = $this->input->post('oldcontact_id');
	            $this->Model_admin_login->update_user($pri_cnt,$table_cntct,$con_Id,$cntct_data);
			 }else{  
			 	if($this->input->post('contact_name') != ""){
			    $this->Model_admin_login->insert_user($table_cntct,$contactdata);
			    $con_Id = $this->db->insert_id();
			    $this->db->query("update tbl_organization set contact_id='$con_Id' where org_id='$org_Id' ");
				$this->software_log_insert($con_Id,'Contact','New','Contact Create','','','Contact Created');
			  }
			}

			// echo '<pre>';
			//    print_r($data);
			// echo '</pre>';die;

		
}

function ajaxget_contactData()
{
	   $ipt = $this->input->post('id');
       $sql = "select * from tbl_contact_m WHERE contact_id='$ipt' ";
       $result = $this->db->query($sql)->row();
      
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

 function  ajaxget_contactAlldata()
 {
 
   $ipt = $this->input->post('id');
   $sql = "select contact_id,contact_name,designation,email as cemail,phone as cphone FROM tbl_contact_m WHERE contact_id = $ipt";
   $result = $this->db->query($sql)->row();
   //print_r($result);
   if(sizeof($result) > 0)
    echo json_encode($result,true);
   else
    echo false;

}



function deleteOrg()
{
	$id=$_GET['org_id'];
	if($id!='')
	{
		$this->db->query("delete from tbl_organization where org_id='".$id."' ");

		$this->db->query("delete from tbl_file where file_type='Orgz' AND file_logid='$id' ");
		//$this->db->query("delete from tbl_mulit_orgz where morg_type='Organization' AND orgid='$id' ");
		$this->db->query("delete from tbl_note where note_type='Orgz' AND note_logid='$id' ");
		$this->db->query("delete from tbl_software_log where mdl_name='Orgz' AND slog_id='$id' ");
	}
	redirect('organization/Organization/manage_organization');
}


public function insert_orgz_inner()
{
	
	@extract($_POST);
	
	$table_org = 'tbl_organization';
	$pri_org = 'org_id';
	$table_cntct = 'tbl_contact_m';
	$pri_cnt = 'contact_id';

    //=========================Organization============

	$email_val = json_encode($this->input->post('email_id'),true);
    $phone_no  = json_encode($this->input->post('phone_no'),true);
	
	$data_org= array(
			
					'org_name' => $this->input->post('org_name'),
					'website'  => $this->input->post('website'),
					'phone_no' => $phone_no,
					'email'    => $email_val,					
					'address'  => $this->input->post('address'),
					'city'     => $this->input->post('city'),
					'state_id' => $this->input->post('state_id'),
					'pin_code' => $this->input->post('pin_code'),
					'country'  => $this->input->post('country_id'),
					'description' => $this->input->post('snotes')

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

		if($this->input->post('oldorgid') != "")
		{
			$org_Id = $this->input->post('oldorgid');
			$this->Model_admin_login->update_user($pri_org,$table_org,$org_Id,$data_org);
			echo $org_Id;	
		}			
		else
		{
			$this->Model_admin_login->insert_user($table_org,$dataall_org);
			$org_Id = $this->db->insert_id();
			$this->software_log_insert($org_Id,'Orgz','New','Orgz Create','','','Organization Created');
			echo $org_Id;
		}
		//================Contact=======================

			$cemail_val = json_encode($this->input->post('cemail_id'),true);
			$cphone_no = json_encode($this->input->post('cphone_no'),true);

			$cntct_data = array(

								'contact_name' => $this->input->post('contact_name'),
								'designation'  => $this->input->post('designation'),
								'org_name' => $org_Id,
								'email' => $cemail_val,
								'phone' => $cphone_no

							   );

			$contactdata = array_merge($cntct_data,$sesio);

	        if($this->input->post('oldcontact_id') !=""){
			   	$con_Id = $this->input->post('oldcontact_id');
	            $this->Model_admin_login->update_user($pri_cnt,$table_cntct,$con_Id,$cntct_data);
			 }else{  
			 	if($this->input->post('contact_name') != ""){
			    $this->Model_admin_login->insert_user($table_cntct,$contactdata);
			    $con_Id = $this->db->insert_id();
			    $this->db->query("update tbl_organization set contact_id='$con_Id' where org_id='$org_Id' ");
				$this->software_log_insert($con_Id,'Contact','New','Contact Create','','','Contact Created');
			  }
			}

			// echo '<pre>';
			//    print_r($data);
			// echo '</pre>';die;

		
}

public function ajax_OrgEditData()
{
	$param = $this->input->post('id');
    $data['result'] = $this->model_organization->getOrgDtl($param);
    $this->load->view('load-ajax-orgz',$data);	
}



function ajax_chkcompany()
{
	$cname   = $this->input->post('val');
    $sql     = "select org_name from tbl_organization where org_name = '".$cname."' AND status = 'A'";
	$query   = $this->db->query($sql);
	//print_r($query->result_array());
	if(sizeof($query->result_array()) > 0)
		echo 1;
	else
		echo 0;
}



public function get_org()
{
	
	@extract($_POST);	
	$table_name = 'tbl_organization';
	$pri_col = 'org_id';
	$id = $org_id;
	

	$this->load->model('Model_admin_login');

	
	$data= array(
			
					'org_name' => $this->input->post('org_name'),
					'city' => $this->input->post('city'),
					'address' => $this->input->post('address'),					
					'pin_code' => $this->input->post('pin_code'),
					'description' => $this->input->post('description'),
					'website' => $this->input->post('website')

				);

		$this->Model_admin_login->update_user($pri_col,$table_name,$id,$data);

		$query = $this->db->query("select *from $table_name where org_id='$id'");
		$getOrg=$query ->row();
		echo $getOrg->org_name;

}


public function insert_orgz_note()
{
	@extract($_POST);

	 // echo '<pre>';
	 // print_r($_POST);die;
	 // echo '</pre>';

	$table_name = 'tbl_note';
	$pri_col    = 'note_id';

	$id = $this->input->post('noteid');
	echo $this->input->post('orgzid');

	$this->load->model('Model_admin_login');

	$data = array(
						'note_logid' => $this->input->post('orgzid'),
						'note_type'	 => 'Orgz',	
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

		$orgzid = $this->input->post('orgzid');
	
	    if($orgzid != '' & $id == '')
		{	
			
			$note_desc = $this->input->post('note_desc');
			
				$this->software_log_insert($orgzid,'Orgz','Note','Orgz Notes','','',$note_desc);
		}

}


public function ajax_ListOrgzNotes()
{	
	$nid = $this->input->post('id');
	$data['result'] = $this->model_organization->getOrgDtl($nid);
	$this->load->view('load-ajax-orgz',$data);
}


public function insert_orgz_file()
{
	
	@extract($_POST);

	  // echo '<pre>';
	  // print_r($_POST);die;
	 // echo '</pre>';

	$table_name = 'tbl_file';
	$pri_col    = 'file_id';

	$id = $this->input->post('fileid');
	echo $this->input->post('orgzid');

		@$branchQuery2= $this->db->query("SELECT * FROM $table_name where file_id = '$id' ");
		$branchFetch2 = $branchQuery2->row();

	$this->load->model('Model_admin_login');

		if($_FILES['files_name']['name']!='')
		{
			$target = "crmfiles/orgfile/"; 
			$target1 =$target . @date(U)."_".( $_FILES['files_name']['name']);
			$filesname=@date(U)."_".( $_FILES['files_name']['name']);
			move_uploaded_file($_FILES['files_name']['tmp_name'], $target1);
		}
		else
		{
			$filesname=$branchFetch2->files_name;
		}

	$data = array(
						'file_logid' => $this->input->post('orgzid'),
						'file_type'	 => 'Orgz',	
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

		$orgzid = $this->input->post('orgzid');
	
	    if($orgzid != '' & $id == '')
		{	
			
			$files_desc = $this->input->post('files_desc');
			
				$this->software_log_insert($orgzid,'Orgz','File',$filesname,'','',$files_desc);
		}

}

public function ajax_orgzFilesData()
{
	$nid = $this->input->post('id');
	$data['result'] = $this->model_organization->getOrgDtl($nid);
	$this->load->view('load-ajax-orgz',$data);
}


} ?>