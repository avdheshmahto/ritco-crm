<?php
defined('BASEPATH') OR exit('No direct script access allowed');
error_reporting (E_ALL ^ E_NOTICE);
class master extends my_controller {


function __construct()
{
    parent::__construct(); 
    $this->load->library('pagination');
    $this->load->model('model_master');
    $this->load->model('model_admin_login');	
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



public function manage_calendar()
{
	if($this->session->userdata('is_logged_in'))
	{
		$this->load->view('calendar/manage-calendar');
	}
	else
	{
		redirect('index');
	}
}

public function manage_dashboard()
{
	if($this->session->userdata('is_logged_in'))
	{
		$this->load->view('dashboard/manage-dashboard');
	}
	else
	{
		redirect('index');
	}
}



public function manage_account()
{
	
	if($this->session->userdata('is_logged_in'))
	{
		$this->load->view('account/manage-account');
	}
	else
	{
		redirect('index');
	}
}


function userprofileview()
{
     if($this->session->userdata('user_id') != "")
     {
   	     $this->load->view('view-user-profile');
     }
     else
     {
         redirect('index');
     }
  }


function insert_branch_data()
{
	extract($_POST);

	$table_name = 'tbl_branch_mst';
	$pri_col = 'brnh_id';

	$id = $this->input->post('branch_id');

	$data = array(

				'brnh_code' => $this->input->post('branch_code'),
				'brnh_name' => $this->input->post('branch_name'),

				);
	$ses = array(

				'compa_id'    => $this->session->userdata('comp_id'),
				'brnha_id'    => $this->session->userdata('brnh_id'),
				'maker_id'    => $this->session->userdata('user_id'),
				'maker_date'  => date('y-m-d'),
				'author_id'   => $this->session->userdata('user_id'),
				'author_date' => date('y-m-d')


				);

	$data_all = array_merge($data,$ses);

	if($id != '')
	{
		$this->model_admin_login->update_user($pri_col,$table_name,$id,$data);
		echo 2;
	}
	else
	{
		$this->model_admin_login->insert_user($table_name,$data_all);
		echo 1;
	}
}

function user_branch()
{
	 if($this->session->userdata('is_logged_in'))
	 {		
	 	 $data['result'] = $this->model_master->getBranch();
		 $this->load->view('userdetails/manage-branch',$data);
	 }
	 else
	 {
	     redirect('index');
	 }
}

function ajax_ListBranchData()
{
	$data['result'] = $this->model_master->getBranch();
	$this->load->view('userdetails/load-branch',$data);	
}


function ajax_chkbranch_code()
{
	$brnch_cd = $this->input->post('val');
	$data = $this->db->query("select * from tbl_branch_mst where brnh_code='".$brnch_cd."' ");
	$cntdata = $data->num_rows();
	if($cntdata > 0)
		echo 1;
	else
		echo 0;
}


function ajax_chkbranch()
{
	$brnch = $this->input->post('val');
	$data = $this->db->query("select * from tbl_branch_mst where brnh_name='".$brnch."' ");
	$cntdata = $data->num_rows();
	if($cntdata > 0)
		echo 1;
	else
		echo 0;
}

function changepassword()
{
	if($this->session->userdata('is_logged_in'))
	{
		$this->load->view('change-password');
	}
	else
	{
		redirect('index');
	}
}

function insertnewpassword()
{	
	$userid=$this->session->userdata('user_id');
	$oldpass=$this->input->post('old_password');
	$newpass=$this->input->post('new_password');

	$query = $this->db->query("select * from tbl_user_mst where status='A' AND password = '$oldpass' AND user_id = '$userid' ");
	$cntquery = $query->num_rows();

	if($cntquery > 0)
	{
		$this->db->query("update tbl_user_mst set password = '$newpass' where user_id = '$userid' ");
		$this->session->set_flashdata('msg','Password Change Successfully!.');
		redirect('master/Userdetails/setting_user');
	}
	else
	{
		$this->session->set_flashdata('errormsg',' You Entered Wrong Old Password!!.');
		redirect('master/master/changepassword');	
	}
}


 function ajax_BranchUser(){

       $ipt = $this->input->post('id');
       $u_id = $this->input->post('u_id');
       $sql = "select * FROM  tbl_user_mst WHERE comp_id=2 AND brnh_id='".$ipt."' ORDER BY user_id";
       $result = $this->db->query($sql)->result();

       foreach ($result as $getUsr) {

       	if(sizeof($result) > 0){
       	
       	?>
       	<option value="<?=$getUsr->user_id?>" <?php if($getUsr->user_id==$u_id) {?> selected="selected" <?php }?> ><?=$getUsr->user_name;?></option>
       	<?php 
      	}
       }
      	
       if(sizeof($result) > 0)
       	  echo $data;
       else
          echo 1;
      
   }



function ajax_defaultOrg()
{

	$status_user   = $this->input->post('mode');
  	$mid           = $this->input->post('id');
  	$mleadid       = $this->input->post('leadid');
  	$morgid        = $this->input->post('orgid');
  	$type 		   = $this->input->post('mtype');
    
    $d_status =  $status_user=="true"?'Y':'N';
  
    if($status_user != "")
    {
	     if($type == 'Organization'){
	     	$this->db->query("update tbl_leads set org_id='$morgid' where lead_id='$mleadid' ");	
	     }
	     if($type == 'Contact'){
	     	$this->db->query("update tbl_leads set contact_id='$morgid' where lead_id='$mleadid' ");	
	     }
     
	    if($type == 'Organization'){
	       $this->db->query("update tbl_mulit_orgz set default_org = 'N' where morg_logid = '$mleadid' AND morg_type='Organization' ");
	  	   $this->db->query("update tbl_mulit_orgz set default_org = '$d_status' where morg_id = '$mid' ");  	  
	    }
	    if($type == 'Contact'){
	    	$this->db->query("update tbl_mulit_orgz set default_org = 'N' where morg_logid = '$mleadid' AND morg_type='Contact' ");
	  	   $this->db->query("update tbl_mulit_orgz set default_org = '$d_status' where morg_id = '$mid' ");
	    }


    }

    // $data=$this->load->view('load-ajax-lead');
     echo 1;

}

function update_software_log()
{

	$login_id=$this->session->userdata('user_id');
	$logid=$this->input->post('logid');
	$logids=explode(',', $logid);

	$getSize = sizeof($logids);

	for($i=0; $i<$getSize; $i++)
	{

		$pid=$this->db->query("select * from tbl_software_log where sid='$logids[$i]' ");
		$fetchId=$pid->row();
		
		$aid=$fetchId->notify;
		$bid=explode(",",$aid);
		$cid=array_unique($bid);
		$updtId=implode(",",$cid);
		
		if($updtId != '')
		{
			$notifyid=$updtId.','.$login_id;	
		}
		else
		{
			$notifyid=$login_id;
		}
		
		 	$this->db->query("update tbl_software_log set notify='$notifyid' where sid='$logids[$i]' ");
	}

	 $data = $this->load->view('ajaxheader');
	 echo $data;
}	





public function Getdata_status() 
{ 
		
		$userleadstatus = $this->input->post('userlead');

        $data = $this->model_master->get_all_lead_status($userleadstatus); 
 
        // data to json 
        // print_r($data);
 
        $responce->cols[] = array( 
            "id" => "", 
            "label" => "Topping", 
            "pattern" => "", 
            "type" => "string" 
        ); 
        $responce->cols[] = array( 
            "id" => "", 
            "label" => "Total", 
            "pattern" => "", 
            "type" => "number" 
        ); 
        foreach($data as $cd) 
        { 
            	
            $key=$this->db->query("select * from tbl_master_data where serial_number='$cd->lead_state'");
            $getKey=$key->row();

            $responce->rows[]["c"] = array( 
                array( 
                    "v" => "$getKey->keyvalue", 
                    "f" => null 
                ) , 
                array( 
                    "v" => (int)$cd->countval, 
                    "f" => null 
                ) 
            ); 
        } 
 
        echo json_encode($responce); 

} 


public function Getdata_stage() 
{ 
		$userleadstage=$this->input->post('userstage');
        $data = $this->model_master->get_all_lead_stage($userleadstage); 
 
        // data to json 
        // print_r($data);
 
        $responcestage->cols[] = array( 
            "id" => "", 
            "label" => "Topping", 
            "pattern" => "", 
            "type" => "string" 
        ); 
        $responcestage->cols[] = array( 
            "id" => "", 
            "label" => "Total", 
            "pattern" => "", 
            "type" => "number" 
        ); 
        foreach($data as $cd) 
        { 
            	
            $key=$this->db->query("select * from tbl_master_data where serial_number='$cd->stage'");
            $getKey=$key->row();

            $responcestage->rows[]["c"] = array( 
                array( 
                    "v" => "$getKey->keyvalue", 
                    "f" => null 
                ) , 
                array( 
                    "v" => (int)$cd->countval, 
                    "f" => null 
                ) 
            ); 
        } 
 
        echo json_encode($responcestage); 

} 



 function Getdata_lead(){
        $data  = $this->model_master->getdata_lead();
        print_r(json_encode($data, true));
    }



// function Getdata_task(){
//         $data  = $this->model_master->getdata_task();
//         print_r(json_encode($data, true));

// }



} ?>