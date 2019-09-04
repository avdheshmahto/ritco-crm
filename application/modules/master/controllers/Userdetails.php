<?php
defined('BASEPATH') OR exit('No direct script access allowed');
error_reporting (E_ALL ^ E_NOTICE);
class Userdetails extends my_controller {

function __construct()
{
    parent::__construct(); 
	$this->load->library('pagination');
    $this->load->model('model_master');	
    $this->load->model('Model_admin_login');
}


	public function manage_user()
	{
		if($this->session->userdata('is_logged_in'))
		{
			$data = array();
			$this->load->view('userdetails/manage-userdetails',$data);
		}
		else
		{
			redirect('index');
		}
			
	 }

	public function setting_user()
	{
	  
	  if($this->session->userdata('is_logged_in'))
	   {
	      $data = array();   	
	   	  if($this->input->post('page_details') == 'ajex_load'){
	   	    $data['result'] = $this->model_master->getuserlist();
            $this->load->view('userdetails/manage-settinguser',$data);
	   	  }else{
	   	    $data['result']       = $this->model_master->getuserlist();
		    $data['contant_data'] = 'userdetails/manage-settinguser';
		    $this->load->view('userdetails/template',$data);
		  
	   	  }
	  }
	   else
	   {
		  redirect('index');
	   }
	}

	public function ajax_setting_user()
	{
	  $data = array();
	  $data['result'] = $this->model_master->getuserlist();
	  //print_r($data['result']);
	  $this->load->view('userdetails/manage-settinguser',$data);
	  
	}
 

  //  public function updateuserform()
  //  {

  //  		@extract($_POST);
		// $table_name = 'tbl_user_mst';
		// $pri_col    = 'user_id';
		// // echo "</pre>";
  // //       print_r($_POST);
  // //       echo "</pre>";
  // //       die;
	 // 	$uid         = $this->input->post('userid');
  //  		$data = array(

		// 			    'user_name' => $this->input->post('name'),
		// 				'email_id'  => $this->input->post('email'),
		// 				'role'		=> $this->input->post('usr_role'),
		// 				'comp_id'   => $this->input->post('usr_role'),
		// 				'brnh_id'   => $this->input->post('branch'),

		// 			);
  //  		if($uid != "")
  //  		{
  //  			$this->Model_admin_login->update_user($pri_col,$table_name,$uid,$data);
  //  			redirect('master/Userdetails/setting_user');
  //  		}
   		
  //  }

   public function insertuserform(){
   	    // echo "</pre>";
        // print_r($this->session->userdata);
        // echo "</pre>";
        // die;
	    @extract($_POST);
		$table_name = 'tbl_user_mst';
		$pri_col    = 'user_id';
	 	$uid         = $this->input->post('userid');
		

		if($email !=""){

		 $fullname   = $this->input->post('name');
		 $email      = $this->input->post('email');
		 //$role       = $this->input->post('role');
         $randstring = $this->RandomString();
         $password   = rand(10,100000);

        $data = array(
		 	// 'first_name' => $this->input->post('name'),
			// 'last_name'  => $this->input->post('lname'),
			'user_name'  => $this->input->post('name'),
			'email_id'   => $this->input->post('email'),
			//'role'       => $this->input->post('usr_role'),
			'profile_user'	 => $this->input->post('user_pro'),
			//'brnh_usr'   => $this->input->post('usr_brnch'),

			//'comp_id'    => $this->input->post('usr_role'),
			'brnh_id'    => $this->input->post('branch'),

			'password'      => 'admin',
		    'confirm_email' =>  $randstring,
			'password'      =>  $password
	    );

	    $updatedata = array(

			'user_name'  => $this->input->post('name'),
			'email_id'   => $this->input->post('email'),
			//'role'       => $this->input->post('usr_role'),
			'profile_user'	 => $this->input->post('user_pro'),
			//'brnh_usr'   => $this->input->post('usr_brnch'),

			//'comp_id'    => $this->input->post('usr_role'),
			'brnh_id'    => $this->input->post('branch'),
	    );

        $sesio = array(
			'maker_id'   => $this->session->userdata('user_id'),
			'author_id'  => $this->session->userdata('user_id'),
			'maker_date' => date('y-m-d'),
			'author_date'=> date('y-m-d')
		 );

        if($uid == "")
        {
		  $dataall = array_merge($data,$sesio);
		  $this->Model_admin_login->insert_user($table_name,$dataall);
		  $id = $this->db->insert_id();
		  echo 1;
		}
		else
		{
		  $this->Model_admin_login->update_user($pri_col,$table_name,$uid,$updatedata);
		  echo 2;
		}
       
        if($id != "")
        {
			$this->mailFunction($email,$randstring,$id,$fullname,$password);  
		}

	  }else{
	  	//redirect('master/Item/dashboar');
	  }

    }



function ResentMail()
{
	$userid=$this->input->post('uid');

	$usr=$this->db->query("select * from tbl_user_mst where user_id='$userid'");
	$getUsr=$usr->row();

	$email      = $getUsr->email_id;
	$randstring = $getUsr->confirm_email;
	$id         = $getUsr->user_id;
	$fullname   = $getUsr->user_name;
	$password   = $getUsr->password;

	if($userid != "")
	{
		$this->mailFunction($email,$randstring,$id,$fullname,$password);  
		echo 1;
	}
}

   function useredit($param = FALSE){
   	  if($this->input->post('id') != ""){
   	    $param = $this->input->post('id');
        $data['result'] = $this->model_master->getEditUsers($param);
        $this->load->view('userdetails/edit-settinguser',$data);
       }else{
        $data['result'] = $this->model_master->getEditUsers($param);
        $data['contant_data'] = 'userdetails/edit-settinguser';
	    $this->load->view('userdetails/template',$data);
     }
   }

   function RandomString($length = 20)
   {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
   }

   function userview($param = FALSE){
     if($this->input->post('id') != ""){
   	     $param = $this->input->post('id');
         $data['result'] = $this->model_master->getEditUsers($param);
         $this->load->view('userdetails/view-settinguser',$data);
      }else{
         $data['result'] = $this->model_master->getEditUsers($param);
         $data['contant_data'] = 'userdetails/view-settinguser';
	     $this->load->view('userdetails/template',$data);
     }
  }

  function ajax_activeInactive(){
    $status_user  = $this->input->post('mode');
  	$id           = $this->input->post('id');
    
    $status =  $status_user=="true"?'A':'I';
  
    if($status_user != "")
  	  $this->db->query("update tbl_user_mst set status = '$status',logged_in=0 where user_id = $id");

  }

  function mailFunction($email,$randstring,$id,$fullname,$password)
  {
       $this->load->library('email');
   	   $this->email->initialize(array(
								       'protocol' => 'smtp',
								       'smtp_host' => '103.211.216.225',
								       'smtp_user' => 'info@techvyaserp.in',
								       'smtp_pass' => 'info@12345##',
								       'smtp_port' => 587,
								       'mailtype' => 'html',
								       'charset' => 'utf-8',
								       'wordwrap' => TRUE
								     ));
	    $url     = 'http://techvyaserp.in'.base_url()."master/invitedUser?confirmid=$randstring&id=$id";
		$to      =  $email;
		$subject = "CRM Email Confirmation";
		$body    = "<table  width=100% border=0><tr>";
		$body   .= '
	                <td align="center" style="text-align:left;font-size:25px;color:#65676f;padding:30px 0 15px 0" valign="top">Hi '.$fullname.',</td></tr>
	                <tr><td align="center" style="text-align:left;font-size:16px;color:#65676f;padding:0 0 30px 0;line-height:1.8" valign="top" width="538px">
	                <span style="font-weight:bold">First of all congratulations from Ritco CRM.</span><br/>
	                <span style="font-weight:bold">You are joined for Ritco CRM. To finish signing up, </span><br/>
	                <span style="font-weight:bold">Please confirm your email address and set your password for login. </span><br/>
	                
	                <div>
	                <a href="'.$url.'" style="font-size:16px;font-weight:bold;background:#33b2c1;margin-top:18px;padding:11px 0;border-radius:5px;color:#fff;text-transform:uppercase;display:inline-block;text-decoration:none;text-align:center;width:285px" target="_blank">Confirm email address</a></div>
	                  
	                 </td>
	                 </tr>';
	    $body   .= '</table>';

        $this->email->from('info@techvyaserp.in');
	    $this->email->to($email);
        $this->email->subject($subject);
        //$template = $this->load->view('email-remainder', $data, true);
        $this->email->message($body);
        if($this->email->send()){
     	  //echo "Successfully";
         }
      }


public function ajax_checkemail()
{

    $email   = $this->input->post('val');
    $sql     = "select email_id from tbl_user_mst where email_id = '".$email."' ";
	$query   = $this->db->query($sql);
	//print_r($query->result_array());
	if(sizeof($query->result_array()) > 0)
		echo 1;
	else
		echo 0;

}

public function changeuserpassword()
{
	$id = $this->input->post('passuser_id');
	$pass = $this->input->post('usr_new_pass'); 

	$this->db->query("update tbl_user_mst set password='$pass' where user_id='$id' ");
	echo 1;
}


} ?>