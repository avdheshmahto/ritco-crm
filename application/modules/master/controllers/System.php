<?php
defined('BASEPATH') OR exit('No direct script access allowed');
error_reporting (E_ALL ^ E_NOTICE);
class System extends my_controller {
  
function __construct()
{
    parent::__construct(); 
    $this->load->library('pagination');
    $this->load->model('model_master');	
}

 

public function manage_system()
{
	if($this->session->userdata('is_logged_in'))
	{
		//$data = array();
		//$data = $this->Manage_Product_List();
		$this->load->view('system/manage-systemsetting');
	}
	else
	{
		redirect('index');
	}
}




public function manage_role()
{
	if($this->session->userdata('is_logged_in'))
	{
		$this->load->view('master/system/role');
	}
	else
	{
		redirect('index');
	}
}

public function manage_profile()
{
	if($this->session->userdata('is_logged_in'))
	{
		$this->load->view('master/system/profile');
	}
	else
	{
		redirect('index');
	}
}


public function manage_role2()
{
	if($this->session->userdata('is_logged_in'))
	{
		$this->load->view('master/system/role2');
	}
	else
	{
		redirect('index');
	}
}

public function manage_role1()
{
	if($this->session->userdata('is_logged_in'))
	{
		$this->load->view('master/system/role1');
	}
	else
	{
		redirect('index');
	}
}



/*public function manage_assign()
{
	
$data=array(
'id' => $_GET['ID'],
'type' => $_GET['type']
);	
		$this->load->view("master/system/manage_profile",$data);
	
	
}
*/


public function manage_profile_edit()
{
$data=array(
'id' => $_GET['ID'],
'type' => $_GET['type']
);	
	
$this->load->view("master/system/profile-edit",$data);	
	
}


public function save_user(){

	@extract($_POST);
	$table_name ='tbl_user_profile_mst';
	
	//$this->load->model('Model_admin_login');
	
	//echo "dhjddj".$profile_id;die;



$userassign_id =count($this->input->post('userassign'));
	for($i=0;$i< $userassign_id;$i++)
	{		

	$upda_user = "update tbl_user_mst set profile_user = '$profile_id' where user_id ='$userassign[$i]'";
	$this->db->query($upda_user);

		$profinsert = "insert into tbl_user_profile_mst set  profile_id= '$profile_id',user_names= '$userassign[$i]',maker_date=NOW(), author_date=now(), author_id='".$this->session->userdata('user_id')."', maker_id='".$this->session->userdata('user_id')."', comp_id='".$this->session->userdata('comp_id')."', brnh_id='".$this->session->userdata('brnh_id')."'";
		
		$this->db->query($profinsert);
		
	}
redirect("master/System/manage_profile");
//echo 1;


}

public function save_profile()
{
	//echo "hjhj";
	
	@extract($_POST);
	//echo "hdhdj".$edit_id;die;
	$table_name ='tbl_profile_mst';
	//$pri_col ='profile_id';
	$this->load->model('Model_admin_login');
	//$pid = $_GET['param_id'];
	//echo "delete from tbl_profile_mst where profile_id = '$profile_id'";die;

$this->db->query("delete from tbl_profile_mst where profile_id = '$profile_id'");
	$profilekkk =count($this->input->post('profilek'));
//echo $profilekkk;die;
	
	for($i=0;$i< $profilekkk;$i++)
	{		
		//echo "insert into tbl_profile_mst set  profile_id= '$profile_id',profile_name= '$profile_name', module_id = '$profilek[$i]',read_id =  '$read_id[$i]',edit_id =  '$edit_id[$i]',create_id =  '$create_id[$i]',delete_id =  '$delete_id[$i]',maker_date=NOW(), author_date=now(), author_id='".$this->session->userdata('user_id')."', maker_id='".$this->session->userdata('user_id')."', comp_id='".$this->session->userdata('comp_id')."', brnh_id='".$this->session->userdata('brnh_id')."'";

		$profinsert = "insert into tbl_profile_mst set  profile_id= '$profile_id',profile_name= '$profile_name', module_id = '$profilek[$i]',read_id =  '$read_id[$i]',edit_id =  '$edit_id[$i]',create_id =  '$create_id[$i]',delete_id =  '$delete_id[$i]',maker_date=NOW(), author_date=now(), author_id='".$this->session->userdata('user_id')."', maker_id='".$this->session->userdata('user_id')."', comp_id='".$this->session->userdata('comp_id')."', brnh_id='".$this->session->userdata('brnh_id')."'";
		
		$this->db->query($profinsert);
//echo "sfuncsj";die;
	
		//echo $profinsert;die;
		
	
}
redirect("master/System/manage_profile");

//echo 1;
//print_r($profinsert);die;
}


public function manage_master()
{
	if($this->session->userdata('is_logged_in'))
	{	
		//$data = array();
		$data['result'] = $this->model_master->geCatglist($this->input->get('param_id'));
		$this->load->view('system/manage-master',$data);
	}
	else
	{
		redirect('index');
	}
}

public function ajax_ListMasterData()
{	
	$mid = $this->input->post('id');
	$data['result'] = $this->model_master->geCatglist($mid);
	$this->load->view('system/load-masterdata',$data);
}


public function insert_master_data()
{
	
	extract($_POST);
	$table_name ='tbl_master_data';
	$pri_col ='serial_number';
	
	//$pid = $_GET['param_id'];
	$id = $this->input->post('serial_number');		

	$this->load->model('Model_admin_login');
	
	$data = array(
				
				'param_id' => $this->input->post('paramid'),
				//'param_id' => $pid,
				'keyvalue' => $this->input->post('key_value'),
				'default_v' => 'N'
				
				);
	$sesio = array(
				
				'comp_id' => $this->session->userdata('comp_id'),
				'brnh_id' => $this->session->userdata('brnh_id'),
				'maker_id' => $this->session->userdata('user_id'),
				'author_id' => $this->session->userdata('user_id'),
				'maker_date'=> date('y-m-d'),
				'author_date'=> date('y-m-d')
				
				);
	
	$dataall=array_merge($data,$sesio);
		
	if($id!='')
	{
		$this->Model_admin_login->update_user($pri_col,$table_name,$id,$data);
		//echo "2";
	}
	
	else
	{
		$this->Model_admin_login->insert_user($table_name,$dataall);
		//echo 1;
	}	
	echo $this->input->post('paramid');
}


function ajax_defaultDropdown()
{
    $status_user  = $this->input->post('mode');
  	$id           = $this->input->post('id');
  	$pid           = $this->input->post('pmid');
    
    $d_status =  $status_user=="true"?'Y':'N';
  
    if($status_user != "")
    {
      $this->db->query("update tbl_master_data set default_v = 'N' where param_id = $pid ");
  	  $this->db->query("update tbl_master_data set default_v = '$d_status' where serial_number = $id");
    }

}


function chk_profile_name()
{
	$pname = $this->input->post('id');
	$Prflname = $this->db->query("SELECT DISTINCT(profile_name) FROM tbl_profile_mst WHERE profile_name = '$pname'");
	$getPname = $Prflname->num_rows();
	
	if($getPname > 0)
	{
		echo "1";
	}
	else
	{
		echo "0";
	}

}

	
} ?>