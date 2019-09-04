<?php
defined('BASEPATH') OR exit('No direct script access allowed');
error_reporting (E_ALL ^ E_NOTICE);
class Contact extends my_controller {
	

function __construct()
{
	parent::__construct();
	$this->load->library('pagination');
	$this->load->model('model_contact');
	$this->load->model('Model_admin_login');
}

public function manage_contact()
{
	if($this->session->userdata('is_logged_in'))
	{	
		$data =  $this->manage_contactJoin();
		$this->load->view('manage-contact',$data);
	}
	else
	{
		redirect('index');
	}
}

function contactview($param = FALSE)
{

     if($this->input->post('id') != "")
     {
   	     $param = $this->input->post('id');
   	     //echo "gfsdhajhxgcf".$param;
         $data['result'] = $this->model_contact->getContactDtl($param);
         $this->load->view('view-contact',$data);
     
     
     }else{
         $data['result'] = $this->model_contact->getContactDtl($param);
         //$data['contant_data'] = 'userdetails/view-settinguser';
	     $this->load->view('view-contact',$data);
     }
}

public function view_contact()
{
	if($this->input->get('id') != "")
     {
   	     $param = $this->input->get('id');
   	     //echo "gfsdhajhxgcf".$param;
         $data['result'] = $this->model_contact->getContactDtl($param);
         $this->load->view('view-contact',$data);
	}
	else
	{
		redirect('index');
	}
}

function manage_contactJoin()
{
  	  $data['result'] = "";
      $table_name  = 'tbl_contact_m';
	  //$url        = site_url('/master/Account/manage_contact?');
	  $sgmnt      = "4";
	  
	  if($_GET['entries'] != '')
	  {
	  	$showEntries = $_GET['entries'];
	  }
	  else
	  {
	  	$showEntries= 10;
	  }
      
	  $totalData  = $this->model_contact->count_contact($table_name,'A',$this->input->get());

      if($_GET['entries'] != '' && $_GET['filter'] != 'filter')
	  {
         $url = site_url('/contact/Contact/manage_contact?entries='.$_GET['entries']);
      }
	  elseif($_GET['filter'] == 'filter' || $_GET['entries'] != '')
	  {
	  	$url = site_url('/contact/Contact/manage_contact?filter='.$_GET['filter'].'&entries='.$_GET['entries']);
	  }
	  else
	  {
	  	$url = site_url('/contact/Contact/manage_contact?');
	  }

      $pagination   = $this->ciPagination($url,$totalData,$sgmnt,$showEntries);
  
      //$data=$this->user_function();      // call permission fnctn
      $data['dataConfig'] = array('total'=>$totalData,'perPage'=>$pagination['per_page'],'page'=>$pagination['page']);
      $data['pagination'] = $this->pagination->create_links();
	 
	  if($this->input->get('filter') == 'filter')   ////filter start ////
        	$data['result'] = $this->model_contact->filterContactList($pagination['per_page'],$pagination['page'],$this->input->get());
          	else	
    		$data['result'] = $this->model_contact->contact_get($pagination['per_page'],$pagination['page']);
			
      return $data;

}



public function ajax_ListContactData()
{
    $data =  $this->manage_contactJoin();
    $this->load->view('load-contact-data',$data);  
}
	


public function insert_contact()
{
	
		@extract($_POST);

		$table_cnt = 'tbl_contact_m';
		$pri_cnt   = 'contact_id';
		$table_org = 'tbl_organization';
		$pri_org   = 'org_id';

		//===============Contact=====================
	 	
		$email_val = json_encode($this->input->post('email_id'),true);
        $phone_no  = json_encode($this->input->post('phone_no'),true);

		$data_cnt = array(
							'contact_name' => $this->input->post('contact_name'),
							'designation'  => $this->input->post('designation'),		       
							'email'        => $email_val,
							'phone'        => $phone_no,
							'address'      => $this->input->post('address'),
							'city_name'    => $this->input->post('city'),
							'state_id'     => $this->input->post('state_id'),
							'pincode'      => $this->input->post('pin_code'),
							'country'      => $this->input->post('country_id'),
							'description'  => $this->input->post('summernote')
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

	    if($this->input->post('oldcontact_id') !="")
	    {
		   	$con_Id = $this->input->post('oldcontact_id');
            $this->Model_admin_login->update_user($pri_cnt,$table_cnt,$con_Id,$data_cnt);
			echo 2;
		}	
		else
		{							
			$this->Model_admin_login->insert_user($table_cnt,$data_cntcnt);
			$con_Id = $this->db->insert_id();
			$this->software_log_insert($con_Id,'Contact','New','Contact Create','','','Contact Created');

			// $json = array("status" => "success", "message" => 'Contact Created successfully!');
           // echo json_encode($json, JSON_PRETTY_PRINT);

			echo 1; 	
		}

		//====================Organization=================

			$oemail_val = json_encode($this->input->post('oemail_id'),true);
			$ophone_no = json_encode($this->input->post('ophone_no'),true);

			$org_data = array(

								'org_name' => $this->input->post('org_name'),
								'website'  => $this->input->post('website'),
								'contact_id' => $con_Id,
								'email' => $oemail_val,
								'phone_no' => $ophone_no

							   );

			$data_orgz = array_merge($org_data,$sesio);

	        if($this->input->post('oldorgid') != ""){
			   	$org_Id = $this->input->post('oldorgid');
	            $this->Model_admin_login->update_user($pri_org,$table_org,$org_Id,$org_data);
			 }else{  
			 	if($this->input->post('org_name') != ""){
				 	$this->Model_admin_login->insert_user($table_org,$data_orgz);
				    $org_Id = $this->db->insert_id();
				    $this->db->query("update tbl_contact_m set org_name='$org_Id' where contact_id='$con_Id' ");
					$this->software_log_insert($org_Id,'Orgz','New','Orgz Create','','','Organization Created');
			 	}			    
			}

			// echo '<pre>';
			//    print_r($data);
			// echo '</pre>';die;

		//====================Multi Organization============

		// $table_name_log = 'tbl_mulit_orgz';
		// //$clogid = $this->db->insert_id();

		// $datalog = array(
					
		// 				'morg_logid'   => $con_Id,
		// 				'morg_type'	   => 'Contact',	
		// 				'orgid'		   => $org_Id,
		// 				//'morg_details' => $this->input->post('org_details')
					   
		// 			  );
		// if($this->input->post('oldcontact_id') =="")
		// {
		// 	$data_org_log = array_merge($datalog,$sesio);
		// 	$this->Model_admin_login->insert_user($table_name_log,$data_org_log);	
		// }
		
}

function ajaxget_orgzData()
{
	   $ipt = $this->input->post('id');
       $sql = "select * from tbl_organization WHERE org_id='$ipt' ";
       $result = $this->db->query($sql)->row();
      
       if(sizeof($result) > 0)
          echo json_encode($result,true);
       else
          echo false;
}

 function  ajaxget_contactAlldata()
 {
 
   $ipt = $this->input->post('id');
   $sql = "select * FROM tbl_contact_m WHERE contact_id = $ipt";
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
   $sql = "select *,email as oemail,phone_no as ophone_no FROM  tbl_organization WHERE org_id = $ipt";
   $result = $this->db->query($sql)->row();
  
   if(sizeof($result) > 0)
      echo json_encode($result,true);
   else
      echo false;

}

function deleteContact()
{
	$id=$_GET['contact_id'];
	if($id!='')
	{
		$this->db->query("delete from tbl_contact_m where contact_id='".$id."' ");

		$this->db->query("delete from tbl_file where file_type='Contact' AND file_logid='$id' ");
		//$this->db->query("delete from tbl_mulit_orgz where morg_type='Contact' AND orgid='$id' ");
		$this->db->query("delete from tbl_note where note_type='Contact' AND note_logid='$id' ");
		$this->db->query("delete from tbl_software_log where mdl_name='Contact' AND slog_id='$id' ");
	}
	redirect('contact/Contact/manage_contact');
}



public function insert_contact_inner()
{
	
		@extract($_POST);

		$table_cnt = 'tbl_contact_m';
		$pri_cnt   = 'contact_id';
		$table_org = 'tbl_organization';
		$pri_org   = 'org_id';

		//===============Contact=====================
	 	
		$email_val = json_encode($this->input->post('email_id'),true);
        $phone_no  = json_encode($this->input->post('phone_no'),true);

		$data_cnt = array(
							'contact_name' => $this->input->post('contact_name'),
							'designation'  => $this->input->post('designation'),		       
							'email'        => $email_val,
							'phone'        => $phone_no,
							'address'      => $this->input->post('address'),
							'city_name'    => $this->input->post('city'),
							'state_id'     => $this->input->post('state_id'),
							'pincode'      => $this->input->post('pin_code'),
							'country'      => $this->input->post('country_id'),
							'description'  => $this->input->post('summernote')
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

	    if($this->input->post('oldcontact_id') != "")
	    {
		   	$con_Id = $this->input->post('oldcontact_id');
            $this->Model_admin_login->update_user($pri_cnt,$table_cnt,$con_Id,$data_cnt);
			echo $con_Id;
		}	
		else
		{							
			$this->Model_admin_login->insert_user($table_cnt,$data_cntcnt);
			$con_Id = $this->db->insert_id();
			$this->software_log_insert($con_Id,'Contact','New','Contact Create','','','Contact Created');	
			echo $con_Id; 	
		}

		//====================Organization=================

			$oemail_val = json_encode($this->input->post('oemail_id'),true);
			$ophone_no = json_encode($this->input->post('ophone_no'),true);

			$org_data = array(

								'org_name' => $this->input->post('org_name'),
								'website'  => $this->input->post('website'),
								'contact_id' => $con_Id,
								'email' => $oemail_val,
								'phone_no' => $ophone_no

							   );

			$data_orgz = array_merge($org_data,$sesio);

	        if($this->input->post('oldorgid') != ""){
			   	$org_Id = $this->input->post('oldorgid');
	            $this->Model_admin_login->update_user($pri_org,$table_org,$org_Id,$org_data);
			 }else{
			 	if($this->input->post('org_name') != ""){  
			    $this->Model_admin_login->insert_user($table_org,$data_orgz);
			    $org_Id = $this->db->insert_id();
			    $this->db->query("update tbl_contact_m set org_name='$org_Id' where contact_id='$con_Id' ");
				$this->software_log_insert($org_Id,'Orgz','New','Orgz Create','','','Organization Created');
			 }
			}

			// echo '<pre>';
			//    print_r($data);
			// echo '</pre>';die;

		//====================Multi Organization============

		// $table_name_log = 'tbl_mulit_orgz';
		// //$clogid = $this->db->insert_id();

		// $datalog = array(
					
		// 				'morg_logid'   => $con_Id,
		// 				'morg_type'	   => 'Contact',	
		// 				'orgid'		   => $org_Id,
		// 				//'morg_details' => $this->input->post('org_details')
					   
		// 			  );
		// if($this->input->post('oldcontact_id') == "")
		// {
		// 	$data_org_log = array_merge($datalog,$sesio);
		// 	$this->Model_admin_login->insert_user($table_name_log,$data_org_log);	
		// }
		
}


function ajax_InnerContactPage()
{
	 $param = $this->input->post('id');
     $data['result'] = $this->model_contact->getContactDtl($param);
     $this->load->view('load-ajax-contact',$data);	
}


public function insert_contact_note()
{
	@extract($_POST);

	 // echo '<pre>';
	 // print_r($_POST);die;
	 // echo '</pre>';

	$table_name = 'tbl_note';
	$pri_col    = 'note_id';

	$id = $this->input->post('noteid');
	echo $this->input->post('cntctid');

	$this->load->model('Model_admin_login');

	$data = array(
						'note_logid' => $this->input->post('cntctid'),
						'note_type'	 => 'Contact',	
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

		$cntctid = $this->input->post('cntctid');
	
	    if($cntctid != '' & $id == '')
		{	
			
			$note_desc = $this->input->post('note_desc');
			
				$this->software_log_insert($cntctid,'Contact','Note','Contact Notes','','',$note_desc);
		}

}


public function ajax_ListCntctNotes()
{	
	$nid = $this->input->post('id');
	$data['result'] = $this->model_contact->getContactDtl($nid);
	$this->load->view('load-ajax-contact',$data);
}


public function insert_cntct_file()
{
	@extract($_POST);

	  // echo '<pre>';
	  // print_r($_POST);die;
	 // echo '</pre>';

	$table_name = 'tbl_file';
	$pri_col    = 'file_id';

	$id = $this->input->post('fileid');
	echo $this->input->post('cntctid');

		@$branchQuery2= $this->db->query("SELECT * FROM $table_name where file_id = '$id' ");
		$branchFetch2 = $branchQuery2->row();

	$this->load->model('Model_admin_login');

		if($_FILES['files_name']['name']!='')
		{
			$target = "crmfiles/contactfile/"; 
			$target1 =$target . @date(U)."_".( $_FILES['files_name']['name']);
			$filesname=@date(U)."_".( $_FILES['files_name']['name']);
			move_uploaded_file($_FILES['files_name']['tmp_name'], $target1);
		}
		else
		{
			$filesname=$branchFetch2->files_name;
		}

	$data = array(
						'file_logid' => $this->input->post('cntctid'),
						'file_type'	 => 'Contact',	
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

		$cntctid = $this->input->post('cntctid');
	
	    if($cntctid != '' & $id == '')
		{	
			
			$files_desc = $this->input->post('files_desc');
			
				$this->software_log_insert($cntctid,'Contact','File',$filesname,'','',$files_desc);
		}
}

public function ajax_cntctFilesData()
{
	$nid = $this->input->post('id');
	$data['result'] = $this->model_contact->getContactDtl($nid);
	$this->load->view('load-ajax-contact',$data);
}


//================Multi Orgz

public function insert_multi_orgz_cntct()
{
	@extract($_POST);

	 // echo '<pre>';
	 // print_r($_POST);die;
	 // echo '</pre>';

	$table_name = 'tbl_mulit_orgz';
	$pri_col    = 'morg_id';

	$id = $this->input->post('morgid');
	echo $this->input->post('cntid');

	$this->load->model('Model_admin_login');

	$data = array(
						'morg_logid'   => $this->input->post('cntid'),
						'morg_type'	   => 'Contact',	
						'orgid'		   => $this->input->post('multi_org'),
						'morg_details' => $this->input->post('org_details')
					   
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
}


public function ajax_ListMultiOrgCntData()
{	
	$nid = $this->input->post('id');
	$data['result'] = $this->model_contact->getContactDtl($nid);
	$this->load->view('load-ajax-contact',$data);
}


function ajax_ChkOrgz(){
       $ipt = $this->input->post('cid');
       $opt = $this->input->post('oid');
       $sql = "select * FROM  tbl_mulit_orgz WHERE morg_logid = $ipt AND orgid=$opt AND morg_type='Contact' ";
       $result = $this->db->query($sql)->row();
      
       if(sizeof($result) > 0)
          echo 1;
       else
          echo false;
      
   }

} ?>