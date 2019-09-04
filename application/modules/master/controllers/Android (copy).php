<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');


class Android extends my_controller
{

	function __construct()
    {
        parent::__construct(); 
   		$this->load->model('Model_admin_login'); 

        //Authenticate data manipulation with the user level security key
        //if ($this->validate_auth_key() != 'success')
            //$data['status'] = 401;
            //$data['message'] = 'Session Expired';
            //echo json_encode($data);
          //  die();
    }

public function android_software_log_insert($slog_id,$mdl_name,$slog_name,$slog_type,$old_id,$new_id,$remarks,$userid,$branchid)
{

	$table_name='tbl_software_log';
	// date_default_timezone_set("Asia/Kolkata");
	// $dtTime = date('H:i:s');

	$data=array(
					'slog_id'   => $slog_id,
					'mdl_name'  => $mdl_name,
					'slog_name' => $slog_name,
					'slog_type' => $slog_type,
					'old_id'    => $old_id,
					'new_id'    => $new_id,
					'remarks'   => $remarks,
			   );

	$sess = array(
					
					'maker_id' => $userid,
					//'maker_date' => date('y-m-d'),
					'author_id' => $userid,
					'author_date' => date('y-m-d'),
					//'comp_id' => $this->session->userdata('comp_id'),				
					'brnh_id' => $branchid,
			);
	$data_merge = array_merge($data,$sess);	
	$this->Model_admin_login->insert_user($table_name,$data_merge);
	return;

}   


public function android_update_note_log($note_logid,$main_lead_id,$note_type,$note_desc,$userid,$branchid)
{

	$table_name='tbl_note';

	$data=array(
					'note_logid'   			=> $note_logid,
					'note_type'  			=> $note_type,
					'note_desc' 			=> $note_desc,
					'main_lead_id_note'		=> $main_lead_id,
			   );

	$sess = array(
					
					'maker_id' => $userid,
					'maker_date' => date('y-m-d'),
					'author_id' => $userid,
					'author_date' => date('y-m-d'),
					//'comp_id' => $this->session->userdata('comp_id'),				
					'brnh_id' => $branchid,
			);
	$data_merge = array_merge($data,$sess);	
	$this->Model_admin_login->insert_user($table_name,$data_merge);
	return;

}

   // ===================== authentication_key validation ============================
 

    function validate_auth_key()
    {
        /*
         * Ignore the authentication and returns success by default to constructor
         * For pubic calls: login, forget password.
         * Pass post parameter 'authenticate' = 'false' to ignore the user level authentication
        */
        //if ($this->input->post('authenticate') == 'false')
          //  return 'success';

        //$response           = array();
        // $token = $this->input->get("token");
        
        // $query              = $this->db->get_where('tbl_user_mst', array('token' => $token));

        // if ($query->num_rows() > 0) {
        //     $row                    = $query->row();
        //     $response['status']     = 'success';
        //     $response['login_type'] = 'admin';
            
        //     $response['login_user_id'] = $row->user_id;
        //     $response['token_key'] = $token;
        // } else {
        //     $response['status'] = 'failed';
        // }
        // //return json_encode($response);
        // echo $response['status'];
    }

// ===================== User Login API ============================

public function user_login()
{


	$email     = $this->input->post('email');
	$password  = $this->input->post('password');
	//$token     = $this->input->post('token');

	$userQuery = $this->db->query("SELECT * FROM tbl_user_mst where status='A' and email_id='$email' and password='$password'");
	$fetchUser = $userQuery->row();
	$count     = $userQuery->num_rows();
   
	if($count > 0)
	{

		$this->db->query("update tbl_user_mst set logged_in= 1,status = 'A'  where   user_id='".$fetchUser->user_id."'");

		$profilename=$this->db->query("select distinct(profile_name),profile_id from tbl_profile_mst where profile_id = '$fetchUser->profile_user'");
		$getPrflName=$profilename->row();

		$branchname=$this->db->query("select * from tbl_branch_mst where brnh_id='$fetchUser->brnh_id' ");
		$getBranchName=$branchname->row();

		//$userList  = array();

		$userData = array(		
							'user_id'      => $fetchUser->user_id,							
							'user_name'    => $fetchUser->user_name,
							'email_id'     => $fetchUser->email_id,
							'profile_id'   => $fetchUser->profile_user,
							'profile_name' => $getPrflName->profile_name,
							'branch_name'  => $getBranchName->brnh_name,
							'brnh_id' 	   => $fetchUser->brnh_id,							
							'maker_id' 	   => $fetchUser->maker_id,							
							'maker_date'   => $fetchUser->maker_date,
							'last_login'   => $fetchUser->last_login,
							//role and profile_id reverse 28-11-2018
					   	);

		$datau['user']=$userData;

		$prfl_prmsn=$this->db->query("select * from tbl_profile_mst where profile_id='$fetchUser->profile_user' And status='A' ORDER BY id ASC ");
		$getPrflprmsn=$prfl_prmsn->result();
		$permission  = array();
		foreach ($getPrflprmsn as $getValue) 
		{
			$mdlName=$this->db->query("select * from tbl_module_mst where module_id='$getValue->module_id' ");
			$getMdlName=$mdlName->row();
			$profile = array(
								// 'profile_id'     => $getValue->profile_id,							
								// 'profile_name'   => $getValue->profile_name,
								// 'module_id'      => $getValue->module_id,
								'module_name'    => $getMdlName->module_name,
								'read_id'        => $getValue->read_id,
								'create_id' 	 => $getValue->create_id,
								'edit_id' 	     => $getValue->edit_id,							
								'delete_id' 	 => $getValue->delete_id,
		    				);
			array_push($permission,$profile);
		}

		$datap['permission']=$permission;	 
		$data=array_merge($datau,$datap);
		//array_push($userList,$data);

	 $this->jsonResponse(200,"Login Successfull !",$data);			
	 // $json = array("status" => "success", "message" => 'Login successfully!');
     // echo json_encode($json, JSON_PRETTY_PRINT);
	}
	else
	{

	 $this->jsonResponse(400,"Invalid Login Request !",NULL);	 
	 //$json = array("status" => "error", "message" => 'There is an Error!');
	}

}


function jsonResponse($status,$status_message,$data) 
{
	header('Content-type: application/json');
	header('Accept: application/vnd.ritco.v1');

	$response['status']=$status;
	$response['status_message']=$status_message;
	$response['data']=$data;
	//$response['permission']=$permission;
	$json_response = json_encode($response);
	echo $json_response;	
}


// =====================Get, Insert, Update and Delete User API ============================

public function get_user_list()
{
	
	$user=$this->db->query("select * from tbl_user_mst ORDER BY user_id DESC ");
	
	$user_data=array();	
	foreach ($user->result() as $getData) 
	{

		$profilename=$this->db->query("select distinct(profile_name),profile_id from tbl_profile_mst where status = 'A' AND profile_id='$getData->profile_user' ");
		$getProfileName=$profilename->row();
		$branchname=$this->db->query("select * from tbl_branch_mst where status='A' AND brnh_id='$getData->brnh_id' ");
		$getBranchName=$branchname->row();

        $data_array = array(
        				'user_id'       => $getData->user_id,
 						'user_name'     => $getData->user_name,						
						'email_id'      => $getData->email_id,
						'branch'        => $getBranchName->brnh_name,
						'profile_name'  => $getProfileName->profile_name,
						'maker_date'    => $getData->maker_date,
						'last_login'    => $getData->last_login,						
					    'emailvarified' => $getData->emailvarified,
						'status'        => $getData->status,						

				     );
        $datau['user']=$data_array;

        $prfl_prmsn=$this->db->query("select * from tbl_profile_mst where status='A' AND profile_id='$getData->profile_user' ORDER BY id ");
		$getPrflprmsn=$prfl_prmsn->result();
		$permission = array();
		foreach ($getPrflprmsn as $getValue) 
		{
			
			$mdlName=$this->db->query("select * from tbl_module_mst where module_id='$getValue->module_id' ");
			$getMdlName=$mdlName->row();

			$profile = array(
								// 'profile_id'     => $getValue->profile_id,							
								// 'profile_name'   => $getValue->profile_name,
								// 'module_id'      => $getValue->module_id,
							    'module_name'    => $getMdlName->module_name,
								'read_id'        => $getValue->read_id,
								'create_id' 	 => $getValue->create_id,
								'edit_id' 	     => $getValue->edit_id,							
								'delete_id' 	 => $getValue->delete_id,
		    				);
			array_push($permission,$profile);
		}
		
		$datap['permission']=$permission;
		
		$abc=array_merge($datau,$datap);

		array_push($user_data,$abc);

    }  
    
    $this->UserJsonResponse(200,"User List !",$user_data);

}


public function insert_update_user()
{
	


	$table_name = 'tbl_user_mst';
	$pri_col    = 'user_id';
 	
 	$id         = "";
 	$uid        = $this->input->post('userid');
 	$email      = $this->input->post('email');
	

		if($email !="")
		{

			$fullname   = $this->input->post('name');
			$email      = $this->input->post('email');
	        $randstring = $this->RandomString();
	        $password   = rand(10,100000);

	        $data = array(
				'user_name'    => $this->input->post('name'),
				'email_id'     => $this->input->post('email'),
				'profile_user' => $this->input->post('user_pro'),
				'brnh_id'      => $this->input->post('branch'),

				'password'      => 'admin',
			    'confirm_email' =>  $randstring,
				'password'      =>  $password
		    );

		    $updatedata = array(

				'user_name'    => $this->input->post('name'),
				'email_id'     => $this->input->post('email'),
				'profile_user' => $this->input->post('user_pro'),
				'brnh_id'      => $this->input->post('branch'),
		    );

	        $sesio = array(
				'maker_id'    => $this->input->post('maker_id'),
				'author_id'   => $this->input->post('maker_id'),
				'maker_date'  => date('y-m-d'),
				'author_date' => date('y-m-d')
			 );


	        if($uid == "")
	        {
			  $data_all = array_merge($data,$sesio);
			  $this->Model_admin_login->insert_user($table_name,$data_all);
			  $id = $this->db->insert_id();
			  //echo 1;

				//========================User All List=====================

				$user=$this->db->query("select * from tbl_user_mst ORDER BY user_id DESC ");
	
				$user_data=array();	
				foreach ($user->result() as $getData) 
				{

					$profilename=$this->db->query("select distinct(profile_name),profile_id from tbl_profile_mst where status = 'A' AND profile_id='$getData->profile_user' ");
					$getProfileName=$profilename->row();
					$branchname=$this->db->query("select * from tbl_branch_mst where status='A' AND brnh_id='$getData->brnh_id' ");
					$getBranchName=$branchname->row();

			        $data_array = array(
			        				'user_id'       => $getData->user_id,
			 						'user_name'     => $getData->user_name,						
									'email_id'      => $getData->email_id,
									'branch'        => $getBranchName->brnh_name,
									'profile_name'  => $getProfileName->profile_name,
									'maker_date'    => $getData->maker_date,
									'last_login'    => $getData->last_login,						
								    'emailvarified' => $getData->emailvarified,
									'status'        => $getData->status,						

							     );
			        $datau['user']=$data_array;

			        $prfl_prmsn=$this->db->query("select * from tbl_profile_mst where status='A' AND profile_id='$getData->profile_user' ORDER BY id ");
					$getPrflprmsn=$prfl_prmsn->result();
					$permission = array();
					foreach ($getPrflprmsn as $getValue) 
					{
						
						$mdlName=$this->db->query("select * from tbl_module_mst where module_id='$getValue->module_id' ");
						$getMdlName=$mdlName->row();

						$profile = array(
											// 'profile_id'     => $getValue->profile_id,				
											// 'profile_name'   => $getValue->profile_name,
											// 'module_id'      => $getValue->module_id,
										    'module_name'    => $getMdlName->module_name,
											'read_id'        => $getValue->read_id,
											'create_id' 	 => $getValue->create_id,
											'edit_id' 	     => $getValue->edit_id,						
											'delete_id' 	 => $getValue->delete_id,
					    				);
						array_push($permission,$profile);
					}
					
					$datap['permission']=$permission;
					
					$abc=array_merge($datau,$datap);

					array_push($user_data,$abc);

			    }  
			    //===============End==============================

			  $this->UserJsonResponse(200,"User Added Successfully! Please Check Mail!",$user_data);

			}
			else
			{
			  
			  $this->Model_admin_login->update_user($pri_col,$table_name,$uid,$updatedata);
			  //echo 2;
				//========================User All List=====================

				$user=$this->db->query("select * from tbl_user_mst ORDER BY user_id DESC ");
	
				$user_data=array();	
				foreach ($user->result() as $getData) 
				{

					$profilename=$this->db->query("select distinct(profile_name),profile_id from tbl_profile_mst where status = 'A' AND profile_id='$getData->profile_user' ");
					$getProfileName=$profilename->row();
					$branchname=$this->db->query("select * from tbl_branch_mst where status='A' AND brnh_id='$getData->brnh_id' ");
					$getBranchName=$branchname->row();

			        $data_array = array(
			        				'user_id'       => $getData->user_id,
			 						'user_name'     => $getData->user_name,						
									'email_id'      => $getData->email_id,
									'branch'        => $getBranchName->brnh_name,
									'profile_name'  => $getProfileName->profile_name,
									'maker_date'    => $getData->maker_date,
									'last_login'    => $getData->last_login,						
								    'emailvarified' => $getData->emailvarified,
									'status'        => $getData->status,						

							     );
			        $datau['user']=$data_array;

			        $prfl_prmsn=$this->db->query("select * from tbl_profile_mst where status='A' AND profile_id='$getData->profile_user' ORDER BY id ");
					$getPrflprmsn=$prfl_prmsn->result();
					$permission = array();
					foreach ($getPrflprmsn as $getValue) 
					{
						
						$mdlName=$this->db->query("select * from tbl_module_mst where module_id='$getValue->module_id' ");
						$getMdlName=$mdlName->row();

						$profile = array(
											// 'profile_id'     => $getValue->profile_id,				
											// 'profile_name'   => $getValue->profile_name,
											// 'module_id'      => $getValue->module_id,
										    'module_name'    => $getMdlName->module_name,
											'read_id'        => $getValue->read_id,
											'create_id' 	 => $getValue->create_id,
											'edit_id' 	     => $getValue->edit_id,						
											'delete_id' 	 => $getValue->delete_id,
					    				);
						array_push($permission,$profile);
					}
					
					$datap['permission']=$permission;
					
					$abc=array_merge($datau,$datap);

					array_push($user_data,$abc);

			    } 
			    //===============End==============================			  
			  $this->UserJsonResponse(200,"User Updated Successfully!",$user_data);
			}
	       
	        if($id != "")
	        {
				$this->mailFunction($email,$randstring,$id,$fullname,$password);  
			}

		}
}

function RandomString($length = 20)
{
	$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
	$charactersLength = strlen($characters);
	$randomString = '';
	for ($i = 0; $i < $length; $i++) 
	{
	    $randomString .= $characters[rand(0, $charactersLength - 1)];
	}
	return $randomString;
}

function mailResend()
{
	$userid=$this->input->get('userid');

	$usr=$this->db->query("select * from tbl_user_mst where user_id='$userid'");
	$count=$usr->num_rows();
	$getUsr=$usr->row();

	$email      = $getUsr->email_id;
	$randstring = $getUsr->confirm_email;
	$id         = $getUsr->user_id;
	$fullname   = $getUsr->user_name;
	$password   = $getUsr->password;

	if($count > 0)
	{
		$this->mailFunction($email,$randstring,$id,$fullname,$password);  
		$this->UserJsonResponse(200,"Mail Sent !",NULL);
	}
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
        if($this->email->send())
        {
     	  //echo "Successfully";
        }

}


function delete_user()
{
	$uid=$this->input->get('userid');
	$user=$this->db->query("select * from tbl_user_mst where user_id='".$uid."' ");
	$getUser=$user->num_rows();

	$oldids=$this->db->query("select * from tbl_software_log where slog_type='User' AND old_id='$uid' ");
    $oldNumRow=$oldids->num_rows();
    $newids=$this->db->query("select * from tbl_software_log where slog_type='User' AND new_id='$uid' ");
    $newNumRow=$newids->num_rows();
    $mkrid=$this->db->query("select * from tbl_software_log where maker_id='$uid' ");
    $getMkrid=$mkrid->num_rows();

    $num_rowss=$oldNumRow + $newNumRow + $getMkrid;
	
	if($num_rowss > 0 && $uid != "")
	{
		$this->UserJsonResponse(400,"User Already Map! You Can't Delete ",NULL);	
	}	
	elseif($uid != "" && $getUser > 0)
	{
		$this->db->query("delete from tbl_user_mst where user_id='".$uid."' ");

		//========================User All List=====================

				$user=$this->db->query("select * from tbl_user_mst ORDER BY user_id DESC ");
	
				$user_data=array();	
				foreach ($user->result() as $getData) 
				{

					$profilename=$this->db->query("select distinct(profile_name),profile_id from tbl_profile_mst where status = 'A' AND profile_id='$getData->profile_user' ");
					$getProfileName=$profilename->row();
					$branchname=$this->db->query("select * from tbl_branch_mst where status='A' AND brnh_id='$getData->brnh_id' ");
					$getBranchName=$branchname->row();

			        $data_array = array(
			        				'user_id'       => $getData->user_id,
			 						'user_name'     => $getData->user_name,						
									'email_id'      => $getData->email_id,
									'branch'        => $getBranchName->brnh_name,
									'profile_name'  => $getProfileName->profile_name,
									'maker_date'    => $getData->maker_date,
									'last_login'    => $getData->last_login,						
								    'emailvarified' => $getData->emailvarified,
									'status'        => $getData->status,						

							     );
			        $datau['user']=$data_array;

			        $prfl_prmsn=$this->db->query("select * from tbl_profile_mst where status='A' AND profile_id='$getData->profile_user' ORDER BY id ");
					$getPrflprmsn=$prfl_prmsn->result();
					$permission = array();
					foreach ($getPrflprmsn as $getValue) 
					{
						
						$mdlName=$this->db->query("select * from tbl_module_mst where module_id='$getValue->module_id' ");
						$getMdlName=$mdlName->row();

						$profile = array(
											// 'profile_id'     => $getValue->profile_id,				
											// 'profile_name'   => $getValue->profile_name,
											// 'module_id'      => $getValue->module_id,
										    'module_name'    => $getMdlName->module_name,
											'read_id'        => $getValue->read_id,
											'create_id' 	 => $getValue->create_id,
											'edit_id' 	     => $getValue->edit_id,						
											'delete_id' 	 => $getValue->delete_id,
					    				);
						array_push($permission,$profile);
					}
					
					$datap['permission']=$permission;
					
					$abc=array_merge($datau,$datap);

					array_push($user_data,$abc);

			    }
			    //===============End==============================	

		$this->UserJsonResponse(200,"User Deleted Successfully !",$user_data);
	}
	else
	{ 
		$this->UserJsonResponse(400,"User Not Found !",NULL);		
	}
}

function UserJsonResponse($status,$status_message,$data)
{
	header('Content-type: application/json');
	header('Accept: application/vnd.ritco.v1');

	$response['status']=$status;
	$response['status_message']=$status_message;
	$response['data']=$data;
	//$response['permission']=$permission;
	$json_response = json_encode($response);
	echo $json_response;	
}



// ===================== Get Profile Name And Branch For Dropdown List API ============================

public function get_profile_branch()
{
	
	$profilename=$this->db->query("select distinct(profile_name),profile_id from tbl_profile_mst where status = 'A'");
	
	$profile=array();	
	foreach ($profilename->result() as $getProfile) 
	{

        $data = array(
						'profile_id'  => $getProfile->profile_id,
						'profile_name'   => $getProfile->profile_name
				     );
        array_push($profile,$data);
    }    

    $branchname=$this->db->query("select * from tbl_branch_mst where status='A' ORDER BY brnh_id DESC");
    
    $branch=array();
    foreach($branchname->result() as $getBranch)
    {
    	$data = array(
    					'branch_id' => $getBranch->brnh_id,
    					'branch_name' => $getBranch->brnh_name

    				);
    	array_push($branch, $data);
    }

    $this->UserProfileBranch(200,"Profile & Branch List",$profile,$branch);

}


function UserProfileBranch($status,$status_message,$profile,$branch)
{
	header('Content-type: application/json');
	header('Accept: application/vnd.ritco.v1');

	$response['status']=$status;
	$response['status_message']=$status_message;
	$response['profile']=$profile;
	$response['branch']=$branch;	
	$json_response = json_encode($response);
	echo $json_response;	
}


// =====================Get, Insert, Update and Delete Branch API ============================


public function get_branch_list()
{
	
	$branch=$this->db->query("select * from tbl_branch_mst ORDER BY brnh_id DESC ");
	
	$branch_data=array();	
	foreach ($branch->result() as $getData) 
	{
		

        $data = array(
        				'brnh_id'   => $getData->brnh_id,
						'brnh_code' => $getData->brnh_code,
						'brnh_name' => $getData->brnh_name,

				     );

        array_push($branch_data,$data);
    }    
    
    $this->branchJsonResponse(200,"Branch List !",$branch_data);

}


function insert_update_branch()
{
	
	//extract($_POST);

	$table_name = 'tbl_branch_mst';
	$pri_col = 'brnh_id';

	$id = $this->input->post('branch_id');

	$data = array(

				'brnh_code' => $this->input->post('branch_code'),
				'brnh_name' => $this->input->post('branch_name'),

				);
	$ses = array(

				//'brnha_id'    => $this->input->post('brnh_id'),
				'maker_id'    => $this->input->post('user_id'),
				'author_id'   => $this->input->post('user_id'),
				'maker_date'  => date('y-m-d'),
				'author_date' => date('y-m-d')


				);

	$data_all = array_merge($data,$ses);

	if($id != '')
	{
		$this->Model_admin_login->update_user($pri_col,$table_name,$id,$data);
		//echo 2;
		//==================All Branch List=========================
			$branch=$this->db->query("select * from tbl_branch_mst ORDER BY brnh_id DESC ");
	
			$branch_data=array();	
			foreach ($branch->result() as $getData) 
			{
				

		        $data = array(
		        				'brnh_id'   => $getData->brnh_id,
								'brnh_code' => $getData->brnh_code,
								'brnh_name' => $getData->brnh_name,

						     );

		        array_push($branch_data,$data);
		    } 

    	//=========================End===========================

		$this->branchJsonResponse(200,"Branch Updated Successfully!",$branch_data);
	}
	else
	{
		$this->Model_admin_login->insert_user($table_name,$data_all);
		//echo 1;

			//==================All Branch List=========================
			$branch=$this->db->query("select * from tbl_branch_mst ORDER BY brnh_id DESC ");
	
			$branch_data=array();	
			foreach ($branch->result() as $getData) 
			{
				

		        $data = array(
		        				'brnh_id'   => $getData->brnh_id,
								'brnh_code' => $getData->brnh_code,
								'brnh_name' => $getData->brnh_name,

						     );

		        array_push($branch_data,$data);
		    } 

    	//=========================End===========================
		$this->branchJsonResponse(200,"Branch Created Successfully!",$branch_data);
	}

}

function delete_branch()
{
	
	$bid=$this->input->get('branch_id');
	
	$brnch=$this->db->query("select * from tbl_branch_mst where brnh_id='".$bid."' ");
	$getBrnch=$brnch->num_rows();

    $brnh_id=$this->db->query("select * from tbl_software_log where brnh_id='$bid' ");
    $getBrnh_id=$brnh_id->num_rows();

    $num_rowss=$getBrnh_id;
	
	if($num_rowss > 0 && $bid != "")
	{
		$this->branchJsonResponse(400,"Branch Already Map! You Can't Delete ",NULL);	
	}	
	elseif($bid != "" && $getBrnch > 0)
	{
		$this->db->query("delete from tbl_branch_mst where brnh_id='".$bid."' ");

		//==================All Branch List=========================
			$branch=$this->db->query("select * from tbl_branch_mst ORDER BY brnh_id DESC ");
	
			$branch_data=array();	
			foreach ($branch->result() as $getData) 
			{
				

		        $data = array(
		        				'brnh_id'   => $getData->brnh_id,
								'brnh_code' => $getData->brnh_code,
								'brnh_name' => $getData->brnh_name,

						     );

		        array_push($branch_data,$data);
		    } 

    	//=========================End===========================

		$this->branchJsonResponse(200,"Branch Deleted Successfully !",$branch_data);
	}
	else
	{ 
		$this->branchJsonResponse(400,"Branch Not Found !",NULL);		
	}
	//redirect('organization/Organization/manage_organization');
}


function branchJsonResponse($status,$status_message,$data)
{
	header('Content-type: application/json');
	header('Accept: application/vnd.ritco.v1');

	$response['status']=$status;
	$response['status_message']=$status_message;
	$response['data']=$data;
	$json_response = json_encode($response);
	echo $json_response;
}


//======================= Get,Insert,Update and Delete Profile Permission API ===================

public function get_profile_list()
{
	
	$profile=$this->db->query("select distinct(profile_name),profile_id from tbl_profile_mst ORDER BY profile_id DESC ");	

	$profile_data=array();	
	foreach ($profile->result() as $getData) 
	{
		
		$queryuser = $this->db->query("select * from tbl_user_mst where status='A' AND profile_user='$getData->profile_id' ");
		$getUsercount = $queryuser->num_rows();
        $profileData = array(
						'profile_id'   => $getData->profile_id,
						'profile_name' => $getData->profile_name,
						'Assign_users' => $getUsercount,

				     );

        
        $datapr['profile']=$profileData;

        $prfl_prmsn=$this->db->query("select * from tbl_profile_mst where status='A' AND profile_id='$getData->profile_id' ");
		$getPrflprmsn=$prfl_prmsn->result();
		$permission            = array();
		foreach ($getPrflprmsn as $getValue) 
		{
			$mdlName=$this->db->query("select * from tbl_module_mst where module_id='$getValue->module_id' ");
			$getMdlName=$mdlName->row();
			$profile = array(
								// 'profile_id'     => $getValue->profile_id,							
								// 'profile_name'   => $getValue->profile_name,
								'module_id'      => $getValue->module_id,
								'module_name'    => $getMdlName->module_name,
								'read_id'        => $getValue->read_id,
								'create_id' 	 => $getValue->create_id,
								'edit_id' 	     => $getValue->edit_id,							
								'delete_id' 	 => $getValue->delete_id,
		    				);
			array_push($permission,$profile);
		}

		$datape['permission']=$permission;

		$data=array_merge($datapr,$datape);

		array_push($profile_data,$data);
    }
            
    $this->profileJsonResponse(200,"Profile Name List !",$profile_data);

}


public function insert_update_profile()
{
	
	//@extract($_POST);
	//$table_name ='tbl_profile_mst';
	
	$json = file_get_contents('php://input');	
	//$jsonString=$implode("",a)
	$book = json_decode($json,true);
	//print_r($json);die;
	// access title of $book object
	$array = json_decode(json_encode($book), true);
	
	foreach($array as $row) 
	{
	
		$profile_id   = $row['profile_id'];
		$profile_name = $row['profile_name'];
		$user_id 	  = $row['user_id'];
		$branch_id    = $row['branch_id'];
		$operation    = $row['operation'];

		if($operation == 'edit'){
			$this->db->query("delete from tbl_profile_mst where profile_id = '$profile_id' ");
		}
		$profile_id   = $row['profile_id'] + 1;
	       
        foreach($row['permission'] as $k) 
        {
            $module_id = $k['module_id'];
            $read_id   = $k['read_id'];
            $edit_id   = $k['edit_id'];            
            $create_id = $k['create_id'];            
            $delete_id = $k['delete_id'];

            $profinsert = "insert into tbl_profile_mst set  profile_id= '$profile_id',profile_name= '$profile_name', module_id= '$module_id',read_id= '$read_id',edit_id= '$edit_id',create_id= '$create_id',delete_id= '$delete_id',maker_date= NOW(), author_date= now(), author_id= '$user_id', maker_id='$user_id', comp_id='', brnh_id='$branch_id' ";
		
			$this->db->query($profinsert);

        }
	
	}

		//================Profile Name List==========
		$profile=$this->db->query("select distinct(profile_name),profile_id from tbl_profile_mst ORDER BY profile_id DESC ");	

		$profile_data=array();	
		foreach ($profile->result() as $getData) 
		{
			
			$queryuser = $this->db->query("select * from tbl_user_mst where status='A' AND profile_user='$getData->profile_id' ");
			$getUsercount = $queryuser->num_rows();
	        $profileData = array(
							'profile_id'   => $getData->profile_id,
							'profile_name' => $getData->profile_name,
							'Assign_users' => $getUsercount,

					     );

	        
	        $datapr['profile']=$profileData;

	        $prfl_prmsn=$this->db->query("select * from tbl_profile_mst where status='A' AND profile_id='$getData->profile_id' ");
			$getPrflprmsn=$prfl_prmsn->result();
			$permission            = array();
			foreach ($getPrflprmsn as $getValue) 
			{
				$mdlName=$this->db->query("select * from tbl_module_mst where module_id='$getValue->module_id' ");
				$getMdlName=$mdlName->row();
				$profile = array(
									// 'profile_id'     => $getValue->profile_id,							
									// 'profile_name'   => $getValue->profile_name,
									'module_id'      => $getValue->module_id,
									'module_name'    => $getMdlName->module_name,
									'read_id'        => $getValue->read_id,
									'create_id' 	 => $getValue->create_id,
									'edit_id' 	     => $getValue->edit_id,							
									'delete_id' 	 => $getValue->delete_id,
			    				);
				array_push($permission,$profile);
			}

			$datape['permission']=$permission;

			$data=array_merge($datapr,$datape);

			array_push($profile_data,$data);
	    }
	    
	    //==============End===================

 	$this->profileJsonResponse(200,"Profile Added Successfully!",$profile_data);

}


function delete_profile()
{
	
	$pid=$this->input->get('profile_id');
	
	$profle=$this->db->query("select distinct(profile_id),profile_name from tbl_profile_mst where profile_id='".$pid."' ");
	$getProfle=$profle->num_rows();

    $usrPrfle=$this->db->query("select * from tbl_user_mst where profile_user='$pid' ");
    $getUsrPrfl=$usrPrfle->num_rows();

    $num_rowss=$getUsrPrfl;
	
	if($num_rowss > 0 && $pid != "")
	{
		$this->branchJsonResponse(400,"Profile Already Map! You Can't Delete ",NULL);	
	}	
	elseif($pid != "" && $getProfle > 0)
	{
		$this->db->query("delete from tbl_profile_mst where profile_id='".$pid."' ");
		//================Profile Name List==========
		$profile=$this->db->query("select distinct(profile_name),profile_id from tbl_profile_mst ORDER BY profile_id DESC ");	

		$profile_data=array();	
		foreach ($profile->result() as $getData) 
		{
			
			$queryuser = $this->db->query("select * from tbl_user_mst where status='A' AND profile_user='$getData->profile_id' ");
			$getUsercount = $queryuser->num_rows();
	        $profileData = array(
							'profile_id'   => $getData->profile_id,
							'profile_name' => $getData->profile_name,
							'Assign_users' => $getUsercount,

					     );

	        
	        $datapr['profile']=$profileData;

	        $prfl_prmsn=$this->db->query("select * from tbl_profile_mst where status='A' AND profile_id='$getData->profile_id' ");
			$getPrflprmsn=$prfl_prmsn->result();
			$permission            = array();
			foreach ($getPrflprmsn as $getValue) 
			{
				$mdlName=$this->db->query("select * from tbl_module_mst where module_id='$getValue->module_id' ");
				$getMdlName=$mdlName->row();
				$profile = array(
									// 'profile_id'     => $getValue->profile_id,							
									// 'profile_name'   => $getValue->profile_name,
									'module_id'      => $getValue->module_id,
									'module_name'    => $getMdlName->module_name,
									'read_id'        => $getValue->read_id,
									'create_id' 	 => $getValue->create_id,
									'edit_id' 	     => $getValue->edit_id,							
									'delete_id' 	 => $getValue->delete_id,
			    				);
				array_push($permission,$profile);
			}

			$datape['permission']=$permission;

			$data=array_merge($datapr,$datape);

			array_push($profile_data,$data);
	    }
	    
	    //==============End===================
		$this->branchJsonResponse(200,"Profile Deleted Successfully !",$profile_data);
	
	}
	else
	{ 
		$this->profileJsonResponse(400,"Profile Not Found !",NULL);		
	}

	//redirect('organization/Organization/manage_organization');

}



function profileJsonResponse($status,$status_message,$data)
{
	header('Content-type: application/json');
	header('Accept: application/vnd.ritco.v1');

	$response['status']=$status;
	$response['status_message']=$status_message;
	$response['data']=$data;
	//$response['permission']=$permission;
	$json_response = json_encode($response);
	echo $json_response;
}


//==========================Master Data API=====================

public function get_lead_industry()
{
	
	$master=$this->db->query("select * from tbl_master_data where param_id=24 ORDER BY serial_number DESC ");
	
	$master_data=array();	
	foreach ($master->result() as $getData) 
	{
		
		$sql=$this->db->query("select * from tbl_master_data_mst where param_id='$getData->param_id' ");
		$getSql=$sql->row();
		
        $data = array(
						'serial_number' => $getData->serial_number,
						'param_id'      => $getData->param_id,
						'keyname'	    => $getSql->keyname,
						'keyvalue'      => $getData->keyvalue,
						'default_v'     => $getData->default_v,

				     );

        array_push($master_data,$data);
    }    
    
    $this->masterJsonResponse(200,"Lead Industry List !",$master_data);

}

public function get_task_priority()
{
	
	$master=$this->db->query("select * from tbl_master_data where param_id=17 ORDER BY serial_number DESC ");
	
	$master_data=array();	
	foreach ($master->result() as $getData) 
	{
		
		$sql=$this->db->query("select * from tbl_master_data_mst where param_id='$getData->param_id' ");
		$getSql=$sql->row();

        $data = array(
						'serial_number' => $getData->serial_number,
						'param_id'      => $getData->param_id,
						'keyname'	    => $getSql->keyname,
						'keyvalue'      => $getData->keyvalue,
						'default_v'     => $getData->default_v,

				     );

        array_push($master_data,$data);
    }    
    
    $this->masterJsonResponse(200,"Task Priority List !",$master_data);

}


public function get_task_status()
{
	
	$master=$this->db->query("select * from tbl_master_data where param_id=21 ORDER BY serial_number DESC ");
	
	$master_data=array();	
	foreach ($master->result() as $getData) 
	{
		
		$sql=$this->db->query("select * from tbl_master_data_mst where param_id='$getData->param_id' ");
		$getSql=$sql->row();
		
        $data = array(
						'serial_number' => $getData->serial_number,
						'param_id'      => $getData->param_id,
						'keyname'	    => $getSql->keyname,
						'keyvalue'      => $getData->keyvalue,
						'default_v'     => $getData->default_v,

				     );

        array_push($master_data,$data);
    }    
    
    $this->masterJsonResponse(200,"Task Status List !",$master_data);

}


public function get_task_category()
{
	
	$master=$this->db->query("select * from tbl_master_data where param_id=23 ORDER BY serial_number DESC ");
	
	$master_data=array();	
	foreach ($master->result() as $getData) 
	{
		
		$sql=$this->db->query("select * from tbl_master_data_mst where param_id='$getData->param_id' ");
		$getSql=$sql->row();
		
        $data = array(
						'serial_number' => $getData->serial_number,
						'param_id'      => $getData->param_id,
						'keyname'	    => $getSql->keyname,
						'keyvalue'      => $getData->keyvalue,
						'default_v'     => $getData->default_v,

				     );

        array_push($master_data,$data);
    }    
    
    $this->masterJsonResponse(200,"Task Category List !",$master_data);

}


public function post_lead_industry()
{
	
	//extract($_POST);
	$table_name ='tbl_master_data';
	$pri_col ='serial_number';
	
	$id = $this->input->post('serial_number');		
	
	$data = array(
				
				'param_id' => $this->input->post('param_id'),
				'keyvalue' => $this->input->post('key_value'),
				'default_v' => 'N'
				
				);
	$sesio = array(
				
				//'comp_id' => $this->session->userdata('comp_id'),
				'brnh_id' => $this->input->post('branch_id'),
				'maker_id' => $this->input->post('user_id'),
				'author_id' => $this->input->post('user_id'),
				'maker_date'=> date('y-m-d'),
				'author_date'=> date('y-m-d')
				
				);
	
	$dataall=array_merge($data,$sesio);
		
	if($id != '')
	{
		$this->Model_admin_login->update_user($pri_col,$table_name,$id,$data);
		//echo "2";
		//===================Master Data List====================//
				
			$master=$this->db->query("select * from tbl_master_data where param_id=24 ORDER BY serial_number DESC ");
			
			$master_data=array();	
			foreach ($master->result() as $getData) 
			{
				
				$sql=$this->db->query("select * from tbl_master_data_mst where param_id='$getData->param_id' ");
				$getSql=$sql->row();
				
		        $data = array(
								'serial_number' => $getData->serial_number,
								'param_id'      => $getData->param_id,
								'keyname'	    => $getSql->keyname,
								'keyvalue'      => $getData->keyvalue,
								'default_v'     => $getData->default_v,

						     );

		        array_push($master_data,$data);
		    }    
	    //==================End============================//    
	    $this->masterJsonResponse(200,"Lead Industry Updated Successfully !",$master_data);

	}
	
	else
	{
		$this->Model_admin_login->insert_user($table_name,$dataall);
		//echo 1;
		//===================Master Data List====================//
				
			$master=$this->db->query("select * from tbl_master_data where param_id=24 ORDER BY serial_number DESC ");
			
			$master_data=array();	
			foreach ($master->result() as $getData) 
			{
				
				$sql=$this->db->query("select * from tbl_master_data_mst where param_id='$getData->param_id' ");
				$getSql=$sql->row();
				
		        $data = array(
								'serial_number' => $getData->serial_number,
								'param_id'      => $getData->param_id,
								'keyname'	    => $getSql->keyname,
								'keyvalue'      => $getData->keyvalue,
								'default_v'     => $getData->default_v,

						     );

		        array_push($master_data,$data);
		    }    
	    
	    //==================End============================//    
	    $this->masterJsonResponse(200,"Lead Industry Created Successfully !",$master_data);
	}	
	//echo $this->input->post('paramid');
}


public function post_task_priority()
{
	
	//extract($_POST);
	$table_name ='tbl_master_data';
	$pri_col ='serial_number';
	
	$id = $this->input->post('serial_number');		
	
	$data = array(
				
				'param_id' => $this->input->post('param_id'),
				'keyvalue' => $this->input->post('key_value'),
				'default_v' => 'N'
				
				);
	$sesio = array(
				
				//'comp_id' => $this->session->userdata('comp_id'),
				'brnh_id' => $this->input->post('branch_id'),
				'maker_id' => $this->input->post('user_id'),
				'author_id' => $this->input->post('user_id'),
				'maker_date'=> date('y-m-d'),
				'author_date'=> date('y-m-d')
				
				);
	
	$dataall=array_merge($data,$sesio);
		
	if($id != '')
	{
		$this->Model_admin_login->update_user($pri_col,$table_name,$id,$data);
		//echo "2";
		//===================Master Data List====================//
				
			$master=$this->db->query("select * from tbl_master_data where param_id=17 ORDER BY serial_number DESC ");
			
			$master_data=array();	
			foreach ($master->result() as $getData) 
			{
				
				$sql=$this->db->query("select * from tbl_master_data_mst where param_id='$getData->param_id' ");
				$getSql=$sql->row();
				
		        $data = array(
								'serial_number' => $getData->serial_number,
								'param_id'      => $getData->param_id,
								'keyname'	    => $getSql->keyname,
								'keyvalue'      => $getData->keyvalue,
								'default_v'     => $getData->default_v,

						     );

		        array_push($master_data,$data);
		    }    
	    //==================End============================//    
	    $this->masterJsonResponse(200,"Task Priority Updated Successfully !",$master_data);

	}
	
	else
	{
		$this->Model_admin_login->insert_user($table_name,$dataall);
		//echo 1;
		//===================Master Data List====================//
				
			$master=$this->db->query("select * from tbl_master_data where param_id=17 ORDER BY serial_number DESC ");
			
			$master_data=array();	
			foreach ($master->result() as $getData) 
			{
				
				$sql=$this->db->query("select * from tbl_master_data_mst where param_id='$getData->param_id' ");
				$getSql=$sql->row();
				
		        $data = array(
								'serial_number' => $getData->serial_number,
								'param_id'      => $getData->param_id,
								'keyname'	    => $getSql->keyname,
								'keyvalue'      => $getData->keyvalue,
								'default_v'     => $getData->default_v,

						     );

		        array_push($master_data,$data);
		    }    
	    
	    //==================End============================//    
	    $this->masterJsonResponse(200,"Task Priority Created Successfully !",$master_data);
	}	
	//echo $this->input->post('paramid');
}


public function post_task_status()
{
	
	//extract($_POST);
	$table_name ='tbl_master_data';
	$pri_col ='serial_number';
	
	$id = $this->input->post('serial_number');		
	
	$data = array(
				
				'param_id' => $this->input->post('param_id'),
				'keyvalue' => $this->input->post('key_value'),
				'default_v' => 'N'
				
				);
	$sesio = array(
				
				//'comp_id' => $this->session->userdata('comp_id'),
				'brnh_id' => $this->input->post('branch_id'),
				'maker_id' => $this->input->post('user_id'),
				'author_id' => $this->input->post('user_id'),
				'maker_date'=> date('y-m-d'),
				'author_date'=> date('y-m-d')
				
				);
	
	$dataall=array_merge($data,$sesio);
		
	if($id != '')
	{
		$this->Model_admin_login->update_user($pri_col,$table_name,$id,$data);
		//echo "2";
		//===================Master Data List====================//
				
			$master=$this->db->query("select * from tbl_master_data where param_id=21 ORDER BY serial_number DESC ");
			
			$master_data=array();	
			foreach ($master->result() as $getData) 
			{
				
				$sql=$this->db->query("select * from tbl_master_data_mst where param_id='$getData->param_id' ");
				$getSql=$sql->row();
				
		        $data = array(
								'serial_number' => $getData->serial_number,
								'param_id'      => $getData->param_id,
								'keyname'	    => $getSql->keyname,
								'keyvalue'      => $getData->keyvalue,
								'default_v'     => $getData->default_v,

						     );

		        array_push($master_data,$data);
		    }    
	    //==================End============================//    
	    $this->masterJsonResponse(200,"Task Status Updated Successfully !",$master_data);

	}
	
	else
	{
		$this->Model_admin_login->insert_user($table_name,$dataall);
		//echo 1;
		//===================Master Data List====================//
				
			$master=$this->db->query("select * from tbl_master_data where param_id=21 ORDER BY serial_number DESC ");
			
			$master_data=array();	
			foreach ($master->result() as $getData) 
			{
				
				$sql=$this->db->query("select * from tbl_master_data_mst where param_id='$getData->param_id' ");
				$getSql=$sql->row();
				
		        $data = array(
								'serial_number' => $getData->serial_number,
								'param_id'      => $getData->param_id,
								'keyname'	    => $getSql->keyname,
								'keyvalue'      => $getData->keyvalue,
								'default_v'     => $getData->default_v,

						     );

		        array_push($master_data,$data);
		    }    
	    
	    //==================End============================//    
	    $this->masterJsonResponse(200,"Task Status Created Successfully !",$master_data);
	}	
	//echo $this->input->post('paramid');
}


public function post_task_category()
{
	
	//extract($_POST);
	$table_name ='tbl_master_data';
	$pri_col ='serial_number';
	
	$id = $this->input->post('serial_number');		
	
	$data = array(
				
				'param_id' => $this->input->post('param_id'),
				'keyvalue' => $this->input->post('key_value'),
				'default_v' => 'N'
				
				);
	$sesio = array(
				
				//'comp_id' => $this->session->userdata('comp_id'),
				'brnh_id' => $this->input->post('branch_id'),
				'maker_id' => $this->input->post('user_id'),
				'author_id' => $this->input->post('user_id'),
				'maker_date'=> date('y-m-d'),
				'author_date'=> date('y-m-d')
				
				);
	
	$dataall=array_merge($data,$sesio);
		
	if($id != '')
	{
		$this->Model_admin_login->update_user($pri_col,$table_name,$id,$data);
		//echo "2";
		//===================Master Data List====================//
				
			$master=$this->db->query("select * from tbl_master_data where param_id=23 ORDER BY serial_number DESC ");
			
			$master_data=array();	
			foreach ($master->result() as $getData) 
			{
				
				$sql=$this->db->query("select * from tbl_master_data_mst where param_id='$getData->param_id' ");
				$getSql=$sql->row();
				
		        $data = array(
								'serial_number' => $getData->serial_number,
								'param_id'      => $getData->param_id,
								'keyname'	    => $getSql->keyname,
								'keyvalue'      => $getData->keyvalue,
								'default_v'     => $getData->default_v,

						     );

		        array_push($master_data,$data);
		    }    
	    //==================End============================//    
	    $this->masterJsonResponse(200,"Task Category Updated Successfully !",$master_data);

	}
	
	else
	{
		$this->Model_admin_login->insert_user($table_name,$dataall);
		//echo 1;
		//===================Master Data List====================//
				
			$master=$this->db->query("select * from tbl_master_data where param_id=23 ORDER BY serial_number DESC ");
			
			$master_data=array();	
			foreach ($master->result() as $getData) 
			{
				
				$sql=$this->db->query("select * from tbl_master_data_mst where param_id='$getData->param_id' ");
				$getSql=$sql->row();
				
		        $data = array(
								'serial_number' => $getData->serial_number,
								'param_id'      => $getData->param_id,
								'keyname'	    => $getSql->keyname,
								'keyvalue'      => $getData->keyvalue,
								'default_v'     => $getData->default_v,

						     );

		        array_push($master_data,$data);
		    }    
	    
	    //==================End============================//    
	    $this->masterJsonResponse(200,"Task Category Created Successfully !",$master_data);
	}	
	//echo $this->input->post('paramid');
}

function delete_lead_industry()
{
	
	$srlno=$this->input->get('serial_number');
	
   	$lead=$this->db->query("select * from tbl_leads where industry='$srlno' ");
    $getLead=$lead->num_rows();

    $mstr=$this->db->query("select * from tbl_master_data where serial_number='$srlno' ");
    $ttlmstr=$mstr->num_rows();

    $num_rowss=$getLead;
	
	if($num_rowss > 0 && $srlno != "")
	{
		$this->masterJsonResponse(400,"Lead Industry Already Map! You Can't Delete ",NULL);	
	}	
	elseif($srlno != "" && $ttlmstr > 0)
	{
		$this->db->query("delete from tbl_master_data where serial_number='".$srlno."' ");
		//===================Master Data List====================//
				
			$master=$this->db->query("select * from tbl_master_data where param_id=24 ORDER BY serial_number DESC ");
			
			$master_data=array();	
			foreach ($master->result() as $getData) 
			{
				
				$sql=$this->db->query("select * from tbl_master_data_mst where param_id='$getData->param_id' ");
				$getSql=$sql->row();
				
		        $data = array(
								'serial_number' => $getData->serial_number,
								'param_id'      => $getData->param_id,
								'keyname'	    => $getSql->keyname,
								'keyvalue'      => $getData->keyvalue,
								'default_v'     => $getData->default_v,

						     );

		        array_push($master_data,$data);
		    }    
	    //==================End============================// 
		$this->masterJsonResponse(200,"Lead Industry Deleted Successfully !",$master_data);
	}
	else
	{ 
		$this->masterJsonResponse(400,"Lead Industry Not Found !",NULL);		
	}

}



function delete_task_priority()
{
	
	$srlno=$this->input->get('serial_number');
	
   	$task=$this->db->query("select * from tbl_task where priority='$srlno' ");
    $getTask=$task->num_rows();

    $mstr=$this->db->query("select * from tbl_master_data where serial_number='$srlno' ");
    $ttlmstr=$mstr->num_rows();

    $num_rowss=$getTask;
	
	if($num_rowss > 0 && $srlno != "")
	{
		$this->masterJsonResponse(400,"Task Priority Already Map! You Can't Delete ",NULL);	
	}	
	elseif($srlno != "" && $ttlmstr > 0)
	{
		$this->db->query("delete from tbl_master_data where serial_number='".$srlno."' ");
		//===================Master Data List====================//
				
			$master=$this->db->query("select * from tbl_master_data where param_id=17 ORDER BY serial_number DESC ");
			
			$master_data=array();	
			foreach ($master->result() as $getData) 
			{
				
				$sql=$this->db->query("select * from tbl_master_data_mst where param_id='$getData->param_id' ");
				$getSql=$sql->row();
				
		        $data = array(
								'serial_number' => $getData->serial_number,
								'param_id'      => $getData->param_id,
								'keyname'	    => $getSql->keyname,
								'keyvalue'      => $getData->keyvalue,
								'default_v'     => $getData->default_v,

						     );

		        array_push($master_data,$data);
		    }    
	    //==================End============================// 
		$this->masterJsonResponse(200,"Task Priority Deleted Successfully !",$master_data);
	}
	else
	{ 
		$this->masterJsonResponse(400,"Task Priority Not Found !",NULL);		
	}

}


function delete_task_status()
{
	
	$srlno=$this->input->get('serial_number');
	
   	$task=$this->db->query("select * from tbl_task where task_status='$srlno' ");
    $getTask=$task->num_rows();

    $mstr=$this->db->query("select * from tbl_master_data where serial_number='$srlno' ");
    $ttlmstr=$mstr->num_rows();

    $num_rowss=$getTask;
	
	if($num_rowss > 0 && $srlno != "")
	{
		$this->masterJsonResponse(400,"Task Status Already Map! You Can't Delete ",NULL);	
	}	
	elseif($srlno != "" && $ttlmstr > 0)
	{
		$this->db->query("delete from tbl_master_data where serial_number='".$srlno."' ");
		//===================Master Data List====================//
				
			$master=$this->db->query("select * from tbl_master_data where param_id=21 ORDER BY serial_number DESC ");
			
			$master_data=array();	
			foreach ($master->result() as $getData) 
			{
				
				$sql=$this->db->query("select * from tbl_master_data_mst where param_id='$getData->param_id' ");
				$getSql=$sql->row();
				
		        $data = array(
								'serial_number' => $getData->serial_number,
								'param_id'      => $getData->param_id,
								'keyname'	    => $getSql->keyname,
								'keyvalue'      => $getData->keyvalue,
								'default_v'     => $getData->default_v,

						     );

		        array_push($master_data,$data);
		    }    
	    //==================End============================// 
		$this->masterJsonResponse(200,"Task Status Deleted Successfully !",$master_data);
	}
	else
	{ 
		$this->masterJsonResponse(400,"Task Status Not Found !",NULL);		
	}

}


function delete_task_category()
{
	
	$srlno=$this->input->get('serial_number');
	
   	$task=$this->db->query("select * from tbl_task where task_name='$srlno' ");
    $getTask=$task->num_rows();

    $mstr=$this->db->query("select * from tbl_master_data where serial_number='$srlno' ");
    $ttlmstr=$mstr->num_rows();

    $num_rowss=$getTask;
	
	if($num_rowss > 0 && $srlno != "")
	{
		$this->masterJsonResponse(400,"Task Category Already Map! You Can't Delete ",NULL);	
	}	
	elseif($srlno != "" && $ttlmstr > 0)
	{
		$this->db->query("delete from tbl_master_data where serial_number='".$srlno."' ");
		//===================Master Data List====================//
				
			$master=$this->db->query("select * from tbl_master_data where param_id=23 ORDER BY serial_number DESC ");
			
			$master_data=array();	
			foreach ($master->result() as $getData) 
			{
				
				$sql=$this->db->query("select * from tbl_master_data_mst where param_id='$getData->param_id' ");
				$getSql=$sql->row();
				
		        $data = array(
								'serial_number' => $getData->serial_number,
								'param_id'      => $getData->param_id,
								'keyname'	    => $getSql->keyname,
								'keyvalue'      => $getData->keyvalue,
								'default_v'     => $getData->default_v,

						     );

		        array_push($master_data,$data);
		    }    
	    //==================End============================//
		$this->masterJsonResponse(200,"Task Category Deleted Successfully !",$master_data);
	}
	else
	{ 
		$this->masterJsonResponse(400,"Task Category Not Found !",NULL);		
	}

}

function get_system_setting()
{
	$tusr=$this->db->query("select * from tbl_user_mst");
	$data['user_count']=$tusr->num_rows();
	$data['user_percentage']=$data['user_count']/30*100;

	$storage=$this->db->query("SELECT SUM(ROUND(((DATA_LENGTH + INDEX_LENGTH) / 1024 / 1024), 2)) AS SIZE_IN_MB FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_SCHEMA ='techvyas_ritco_demo' ");
	$getStorage=$storage->row();
	$data['file_size']=$getStorage->SIZE_IN_MB;

	$this->masterJsonResponse(200,"System Setting Information!",$data);
}

function masterJsonResponse($status,$status_message,$data)
{
	header('Content-type: application/json');
	header('Accept: application/vnd.ritco.v1');

	$response['status']=$status;
	$response['status_message']=$status_message;
	$response['data']=$data;
	$json_response = json_encode($response);
	echo $json_response;
}


///////////////////=================Change Password=======================////////////////////////

function change_password()
{	
	$userid  = $this->input->post('userid');
	//$oldpass = $this->input->post('old_password');
	$newpass = $this->input->post('new_password');

	$query = $this->db->query("select * from tbl_user_mst where status='A' AND user_id = '$userid' ");
	$cntquery = $query->num_rows();

	if($cntquery > 0)
	{
		$this->db->query("update tbl_user_mst set password = '$newpass' where user_id = '$userid' ");
		//$this->session->set_flashdata('msg','Password Change Successfully!.');
		$this->passwordJsonResponse(200,"Password Change Successfully!",NULL);
	}
	else
	{
		//$this->session->set_flashdata('errormsg',' You Entered Wrong Old Password!!.');
		$this->passwordJsonResponse(400,"Something Went Wrong!",NULL);	
	}
}


function reset_user_password()
{	
	$userid  = $this->input->post('userid');
	$oldpass = $this->input->post('old_password');
	$newpass = $this->input->post('new_password');

	$query = $this->db->query("select * from tbl_user_mst where status='A' AND password='$oldpass' AND user_id = '$userid' ");
	$cntquery = $query->num_rows();

	if($cntquery > 0)
	{
		$this->db->query("update tbl_user_mst set password = '$newpass' where user_id = '$userid' ");
		//$this->session->set_flashdata('msg','Password Change Successfully!.');
		$this->passwordJsonResponse(200,"Password Change Successfully!",NULL);
	}
	else
	{
		//$this->session->set_flashdata('errormsg',' You Entered Wrong Old Password!!.');
		$this->passwordJsonResponse(400,"You Entered Wrong Old Password!",NULL);	
	}
}


function passwordJsonResponse($status,$status_message,$data)
{
	header('Content-type: application/json');
	header('Accept: application/vnd.ritco.v1');

	$response['status']=$status;
	$response['status_message']=$status_message;
	$response['data']=$data;
	$json_response = json_encode($response);
	echo $json_response;
}


//=======================Get,Insert,Update,Delete API=============================


public function get_user_industry_stage()
{
	$usr=$this->db->query("select * from tbl_user_mst where status='A'");

	$ListUsr=array();
	foreach ($usr->result() as $getU) 
	{
		$brnh=$this->db->query("select * from tbl_branch_mst where brnh_id='$getU->brnh_id' ");
		$getBrnh=$brnh->row();

		$dataUsr=array(

				'user_id'     => $getU->user_id, 
				'user_name'   => $getU->user_name ."(".$getBrnh->brnh_name.")",
				//'user_branch' => $getBrnh->brnh_name,

				);

		array_push($ListUsr, $dataUsr);

	}

	$data1['user']=$ListUsr;


	$master=$this->db->query("select * from tbl_master_data where param_id=24 ");
	
	$master_data=array();	
	foreach ($master->result() as $getData) 
	{
				
        $dataInd = array(
						'serial_number' => $getData->serial_number,
						'keyvalue'      => $getData->keyvalue,

				     );

        array_push($master_data,$dataInd);
    } 

    $data2['industry']=$master_data;

    $masterData=$this->db->query("select * from tbl_master_data where param_id=20 ");
	
	$master_stage=array();	
	foreach ($masterData->result() as $getStage) 
	{
		
        $dataInd = array(
						'serial_number' => $getStage->serial_number,
						'keyvalue'      => $getStage->keyvalue,
				     );

        array_push($master_stage,$dataInd);
    } 

    $data3['stage']=$master_stage;

    $AllData=array_merge($data1,$data2,$data3);

	$this->leadJsonResponse(200,"All Dropdown List !",$AllData);
}

public function get_lead_list()
{

	$lead=$this->db->query("select * from tbl_leads where status='A' ORDER BY last_update DESC ");

	$leadList = array();
	foreach ($lead->result() as $value) 
	{
		$string=$value->lead_number;
		$firstCharacter = $string[0];

	    $sqlgroup1=$this->db->query("select * from tbl_user_mst where user_id='$value->maker_id' ");
	    $getOwner = $sqlgroup1->row();

	     $tskDes = $this->db->query("select * from tbl_note where note_logid='".$value->lead_id."' AND (main_lead_id_note='main_lead' OR main_lead_id_note='main_task' OR main_lead_id_note='Inner Lead') AND (note_type='Lead' OR note_type='Task') ORDER BY note_id DESC ");

  		$getTskdesc = $tskDes->row();


	    $big = $getTskdesc->note_desc;  
		$big = strip_tags($big);
		$small = substr($big, 0, 20);
		$bigDesc = $big;
		$shortDesc = strtolower($small ."....."); 

	   $status1=$this->db->query("select * from tbl_master_data where serial_number='$value->lead_state'");
	  	$getStatus1 = $status1->row();

	  	$stg = $this->db->query("select * from tbl_master_data where serial_number='$value->stage' ");
  		$getStage = $stg->row();

  		$inds = $this->db->query("select * from tbl_master_data where serial_number='$value->industry' ");
  		$getInds = $inds->row();

  		$sqlgroup1=$this->db->query("select * from tbl_user_mst where user_id='$value->user_resp' ");
		$getUser = $sqlgroup1->row();
 		
 		$brnh=$this->db->query("select * from tbl_branch_mst where brnh_id='$getUser->brnh_id' ");
		$getBrnh=$brnh->row();

 		$cnt=$this->db->query("select * from tbl_contact_m where contact_id='$value->contact_id'");
 		$getCnt=$cnt->row();

 		$email_val=json_decode($getCnt->email);
 		$phone_val=json_decode($getCnt->phone);

 		$org=$this->db->query("select * from tbl_organization where org_id='$value->org_id' ");
 		$getOrg=$org->row();

		$datal = array(
				'lead_id'				=> $value->lead_id,
				'firstCharacter'		=> $firstCharacter,
				'lead_name'  			=> $value->lead_number,
				'lead_owner' 			=> $getOwner->user_name,
				'expected_closure_date' => $value->closuredate,
				'long_description' 		=> $bigDesc,
				'short_description' 	=> $shortDesc,
				'lead_status'  			=> $getStatus1->keyvalue,
				'lead_stage'  			=> $getStage->keyvalue,
				'assign_to'   			=> $getUser->user_name."(".$getBrnh->brnh_name.")",
				'organization_id' 		=> $value->org_id,
				'organization'			=> $getOrg->org_name,
				'contact_id'			=> $value->contact_id,
				'contact'     			=> $getCnt->contact_name,
				'email_id'    			=> $email_val,
				'phone_no'    			=> $phone_val,
				'address'     			=> $getCnt->address,
				'industry'    			=> $getInds->keyvalue,
				'opp_value'   			=> $value->opp_value,
				'description' 			=> $value->discription,

				);
		array_push($leadList,$datal);
	}

	$this->leadJsonResponse(200,"Lead List",$leadList);

}


public function post_lead()
{

		//@extract($_POST);

		$json = file_get_contents('php://input');	
		$book = json_decode($json,true);
		//print_r($json);die;
		$row = json_decode(json_encode($book), true);

		// echo '<pre>';
	    //  print_r($array);
		// echo '</pre>';die;

		$table_name     = 'tbl_leads';
		$table_contact  = 'tbl_contact_m';
		$table_org		= 'tbl_organization';
		$pri_col        = 'lead_id';
		$cont_colum  	= 'contact_id';
		$org_colum		= 'org_id';
		
		//foreach($array as $row){					

		$id = $row['lead_id'];
		//echo $row['org_name'];die;

		//============Organization================
        
        $dataOrg = array(
							'org_name' => $row['org_name'],
						);

         $sesio = array(

							'brnh_id'     => $row['branch_id'],
							'maker_id'    => $row['user_id'],
							'author_id'   => $row['user_id'],
							'maker_date'  => date('y-m-d'),
							'author_date' => date('y-m-d')

				       );

                 
        $dataOranization = array_merge($dataOrg,$sesio);
        
			if($row['oldorg'] != ""){
				$org_Id = $row['oldorg'];
		        $this->Model_admin_login->update_user($org_colum,$table_org,$org_Id,$dataOrg);
			}else{				
	           $this->Model_admin_login->insert_user($table_org,$dataOranization);
	           $org_Id = $this->db->insert_id();
	           $this->android_software_log_insert($org_Id,'Orgz','New','Orgz Create','','','Organization Created',$row['user_id'],$row['branch_id']);
	        }
			
			////////=============Contact======================////////////

        $email_val = json_encode($row['email_id'],true);
        $phone_no  = json_encode($row['phone_no'],true);

		$CntData = array(
				            'contact_name' => $row['contact'],
							'org_name'     => $org_Id,
							'email'        => $email_val,
							'phone'        => $phone_no,
							'address'      => $row['address'],
						);

		$contactdata = array_merge($CntData,$sesio);

        if($row['oldcontact'] !=""){
		   	$con_Id = $row['oldcontact'];
            $this->Model_admin_login->update_user($cont_colum,$table_contact,$con_Id,$CntData);
		 }else{  
		    $this->Model_admin_login->insert_user($table_contact,$contactdata);
		    $con_Id = $this->db->insert_id();
		    $this->db->query("update tbl_organization set contact_id='$con_Id' where org_id='$org_Id' ");
			$this->android_software_log_insert($con_Id,'Contact','New','Contact Create','','','Contact Created',$row['user_id'],$row['branch_id']);
		}
		

		//==============================Lead=================================
		date_default_timezone_set("Asia/Kolkata");
		$dtTime = date('Y-m-d H:i:s');

        $data = array(
					   
					    'contact_id'     => $con_Id,
						'org_id'         => $org_Id,
						'lead_number'    => $row['lead_number'],
						'user_resp'      => $row['user_resp'],
						'industry'       => $row['industry'],
						//'source'         => $this->input->post('source'),
						//'probability'    => $this->input->post('probability'),
						'last_update'    => $dtTime,
						'closuredate'    => $row['closuredate'],				
						'opp_value'      => $row['opp_value'],
						'stage'          => $row['stage'],
						'discription'    => $row['discription'],
						'lead_state'     => '65'
						
					);
	
		$dataall = array_merge($data,$sesio);

		if($id != '')
		{
		    		    
			$login_id=$row['user_id'];

		    $newstg = $row['stage'];
		    $stg = $this->db->query("select * from tbl_leads where lead_id='$id' ");
			$getStg = $stg->row();
			$oldstg = $getStg->stage;
			if($oldstg != $newstg){
				$this->android_software_log_insert($id,'Lead','Lead','Stage',$oldstg,$newstg,'Lead Stage Changed',$row['user_id'],$row['branch_id']);
			}

			$newusr = $row['user_resp'];
			$usr = $this->db->query("select * from tbl_leads where lead_id='$id' ");
			$getUsr = $usr->row();
			$oldusr = $getUsr->user_resp;

			

			//==//==========Software Log Data============

			$pid=$this->db->query("select *from tbl_software_log where slog_id='$id' AND mdl_name='Lead'");
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
				$this->android_software_log_insert($id,'Lead','Lead','User',$oldusr,$newusr,'Lead User Changed',$row['user_id'],$row['branch_id']);
				$this->db->query("update tbl_software_log set seen_id='$newstatusid' where slog_id='$id' AND mdl_name='Lead' ");
			}

			

			$this->db->query("update  tbl_note set note_desc = '".$row['discription']."' where main_lead_id_note = 'main_lead' and note_logid = '$id'");

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

		  	//echo 2;
			$this->leadJsonResponse(200,"Lead Updated Successfully !",NULL);

		}
		else
		{    

		$this->Model_admin_login->insert_user($table_name,$dataall);

   		$Lead_Id = $this->db->insert_id();
		   $this->android_software_log_insert($Lead_Id,'Lead','New','Lead Create','','','Lead Created',$row['user_id'],$row['branch_id']);
   	
	    $newusr = $row['user_resp'];
	    $lgnusr = $row['user_id'];
	    if($newusr != $lgnusr ){
	    	$this->android_software_log_insert($Lead_Id,'Lead','Lead','User',$lgnusr,$newusr,'Lead User Changed',$row['user_id'],$row['branch_id']);
	    }

		//==============Software Log Data & Lead Seen_id Updated====
	    $usrid=$lgnusr.",".$newusr;
		$this->db->query("update tbl_software_log set seen_id='$usrid' where slog_id='$Lead_Id' and mdl_name = 'Lead' ");			
		$this->db->query("update tbl_leads set seen_id='$usrid' where lead_id='$Lead_Id' ");
		//===============End=============

			$note_des = $row['discription'];
			    $this->android_update_note_log($Lead_Id,'main_lead','Lead',$note_des,$row['user_id'],$row['branch_id']);	

			//echo 1;
			$this->leadJsonResponse(200,"Lead Created Successfully !",NULL);
		}

		//===============Lead Multi Details==========================

		

		if($id =="")
		{

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

			$data_org_all = array_merge($datam_org,$sesio);
			$this->Model_admin_login->insert_user($table_multi,$data_org_all);

			$data_cnt_all = array_merge($datam_cnt,$sesio);
			$this->Model_admin_login->insert_user($table_multi,$data_cnt_all);	
		}
		 
	//}			
}


function delete_lead()
{
	$id=$this->input->get('lead_id');

	$task=$this->db->query("select * from tbl_task where lead_id='$id' ");
	$tskNumRow=$task->num_rows();

	$num_rows=$tskNumRow;

	$tld=$this->db->query("select * from tbl_leads where lead_id='".$id."' ");
	$count=$tld->num_rows();

	if($id != '' && $num_rows > 0)
	{
		$delete_leadthis->leadJsonResponse(400,"Lead Map With Task ! You Can't Delete !",NULL);
	}
	elseif($id !='' && $count > 0)
	{
		$this->db->query("delete from tbl_leads where lead_id='".$id."' ");
		$this->db->query("delete from tbl_lead_rates where lead_id='$id' ");
		$this->db->query("delete from tbl_mulit_orgz where morg_logid='$id' ");
		$this->db->query("delete from tbl_file where file_type='Lead' AND file_logid='$id' ");
		$this->db->query("delete from tbl_note where note_type='Lead' AND note_logid='$id' ");
		$this->db->query("delete from tbl_software_log where mdl_name='Lead' AND slog_id='$id' ");


		//======================Lead List=====================

		$lead=$this->db->query("select * from tbl_leads where status='A' ORDER BY lead_id DESC ");

		$leadList = array();
		foreach ($lead->result() as $value) 
		{
			
			$string=$value->lead_number;
			$firstCharacter = $string[0];

		    $sqlgroup1=$this->db->query("select * from tbl_user_mst where user_id='$value->maker_id' ");
		    $getOwner = $sqlgroup1->row();

		     $tskDes = $this->db->query("select * from tbl_note where note_logid='".$value->lead_id."' AND (main_lead_id_note='main_lead' OR main_lead_id_note='main_task' OR main_lead_id_note='Inner Lead') AND (note_type='Lead' OR note_type='Task') ORDER BY note_id DESC ");

	  		$getTskdesc = $tskDes->row();


		    $big = $getTskdesc->note_desc;  
			$big = strip_tags($big);
			$small = substr($big, 0, 20);
			$bigDesc = $big;
			$shortDesc = strtolower($small ."....."); 

		   $status1=$this->db->query("select * from tbl_master_data where serial_number='$value->lead_state'");
		  	$getStatus1 = $status1->row();

		  	$stg = $this->db->query("select * from tbl_master_data where serial_number='$value->stage' ");
	  		$getStage = $stg->row();

	  		$inds = $this->db->query("select * from tbl_master_data where serial_number='$value->industry' ");
	  		$getInds = $inds->row();

	  		$sqlgroup1=$this->db->query("select * from tbl_user_mst where user_id='$value->user_resp' ");
			$getUser = $sqlgroup1->row();
	 		
	 		$brnh=$this->db->query("select * from tbl_branch_mst where brnh_id='$getUser->brnh_id' ");
			$getBrnh=$brnh->row();

	 		$cnt=$this->db->query("select * from tbl_contact_m where contact_id='$value->contact_id'");
	 		$getCnt=$cnt->row();

	 		$email_val=json_decode($getCnt->email);
	 		$phone_val=json_decode($getCnt->phone);

	 		$org=$this->db->query("select * from tbl_organization where org_id='$value->org_id' ");
	 		$getOrg=$org->row();

			$datal = array(
					'lead_id'				=> $value->lead_id,
					'firstCharacter'		=> $firstCharacter,
					'lead_name'  			=> $value->lead_number,
					'lead_owner' 			=> $getOwner->user_name,
					'expected_closure_date' => $value->closuredate,
					'long_description' 		=> $bigDesc,
					'short_description' 	=> $shortDesc,
					'lead_status'  			=> $getStatus1->keyvalue,
					'lead_stage'  			=> $getStage->keyvalue,
					'assign_to'   		    => $getUser->user_name."(".$getBrnh->brnh_name.")",
					'organization_id' 		=> $value->org_id,
					'organization'			=> $getOrg->org_name,
					'contact_id'			=> $value->contact_id,
					'contact'    			=> $getCnt->contact_name,
					'email_id'    			=> $email_val,
					'phone_no'    			=> $phone_val,
					'address'     			=> $getCnt->address,
					'industry'    			=> $getInds->keyvalue,
					'opp_value'   			=> $value->opp_value,
					//'description' => $value->discription,

					);
			array_push($leadList,$datal);
		}

		//=========================End========================
		$this->leadJsonResponse(200,"Lead Deleted Successfully !",$leadList);
	}
	else
	{
		$this->leadJsonResponse(400,"Lead Not Found !",NULL);
	}
	
}


public function update_lead_assignto()
{


	$json = file_get_contents('php://input');	
	$book = json_decode($json,true);
	//print_r($json);die;
	$row = json_decode(json_encode($book), true);
	
	$id = $row['lead_id'];
	$uid = $row['assign_user'];
	// print_r($_POST);die;

		if($id != '')
		{	

			$login_id=$row['user_id'];

			$newusr = $row['assign_user'];
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
				$this->android_software_log_insert($id,'Lead','Lead','User',$oldusr,$newusr,'Lead User Changed',$row['user_id'],$row['branch_id']);
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
		//echo $uid;
		$this->leadJsonResponse(200,"Lead Assign To Updated Successfully !",NULL);
	}


}


public function get_task_dropdown()
{
	$usr=$this->db->query("select * from tbl_user_mst where status='A'");

	$ListUsr=array();
	foreach ($usr->result() as $getU) 
	{
		$brnh=$this->db->query("select * from tbl_branch_mst where brnh_id='$getU->brnh_id' ");
		$getBrnh=$brnh->row();

		$dataUsr=array(

				'user_id'     => $getU->user_id, 
				'user_name'   => $getU->user_name ."(".$getBrnh->brnh_name.")",
				//'user_branch' => $getBrnh->brnh_name,

				);

		array_push($ListUsr, $dataUsr);

	}

	$data1['User']=$ListUsr;


	$master=$this->db->query("select * from tbl_master_data where param_id=23 ");
	
	$master_data=array();	
	foreach ($master->result() as $getData) 
	{
				
        $dataTname = array(
						'serial_number' => $getData->serial_number,
						'keyvalue'      => $getData->keyvalue,

				     );

        array_push($master_data,$dataTname);
    } 

    $data2['TaskName']=$master_data;

    $masterData=$this->db->query("select * from tbl_master_data where param_id=17 ");
	
	$master_prio=array();	
	foreach ($masterData->result() as $getPrio) 
	{
		
        $dataPrio = array(
						'serial_number' => $getPrio->serial_number,
						'keyvalue'      => $getPrio->keyvalue,
				     );

        array_push($master_prio,$dataPrio);
    } 

    $data3['Priority']=$master_prio;


    $masterData2=$this->db->query("select * from tbl_master_data where param_id=21 ");
	
	$master_status=array();	
	foreach ($masterData2->result() as $getStatus) 
	{
		
        $dataStatus = array(
						'serial_number' => $getStatus->serial_number,
						'keyvalue'      => $getStatus->keyvalue,
				     );

        array_push($master_status,$dataStatus);
    } 

    $data4['Status']=$master_status;    

    $AllData=array_merge($data1,$data2,$data3,$data4);

	$this->leadJsonResponse(200,"All Dropdown List !",$AllData);
}


public function post_lead_task()
{

	
	//@extract($_POST);
	
	//print_r($_POST);die;

	$json = file_get_contents('php://input');	
	$book = json_decode($json,true);
	//print_r($json);die;
	$row = json_decode(json_encode($book), true);

	$table_name_log ='tbl_task_log';
	$table_name = 'tbl_task';
	$pri_col = 'task_id';
	
	$leadidz = $row['leadid'];
	
	//$this->load->model('Model_admin_login');

	date_default_timezone_set("Asia/Kolkata");
	$dtTime = date('Y-m-d H:i:s');

	$data= array(
					
					'task_name' 	  => $row['task_name'],
					'date_due'  	  => $row['due_date'],
					//'reminder_date' => $row['reminder_date'],
					'priority'  	  => $row['priority'],
					'progress_percnt' => $row['progress'],
					'task_status' 	  => $row['status'],
					'user_resp'   	  => $row['user_resp'],
					'lead_id'     	  => $row['leadid'],
					'contact_person'  => $row['tcontact_person'],
					'org_name' 		  => $row['torg_name'],
					'description' 	  => $row['snotes'],
					//'visibility'	  => $row['optionsRadios'],
					'last_update'     => $dtTime,

				);


		$sesio = array(
					//'comp_id'   => $row['comp_id'],
					'brnh_id'     => $row['branch_id'],
					'maker_id'    => $row['user_id'],
					'author_id'   => $row['user_id'],
					'maker_date'  => date('y-m-d'),
					'author_date' => date('y-m-d')
				  );
		
		if($leadidz != '')
		{
			$dataall = array_merge($data,$sesio);
			$this->Model_admin_login->insert_user($table_name,$dataall);
		}		

		$lastid=$this->db->insert_id();	
		//=======================Task Log=====================		
		
		$datalog= array(
				
						'task_id' 		   => $lastid,
						'task_name' 	   => $row['task_name'],
						'date_due' 		   => $row['due_date'],
						//'reminder_date'  => $row['reminder_date'],
						'progress_percnt'  => $row['progress'],
						'priority'         => $row['priority'],
						'task_status' 	   => $row['status'],
						'user_resp' 	   => $row['user_resp'],
						'lead_id' 		   => $row['leadid'],
						'contact_person'   => $row['tcontact_person'],
						'org_name' 		   => $row['torg_name'],
						'description'      => $row['snotes'],
						//'visibility'     => $row['optionsRadios'],									
							
				      	);
			
	 	if($lastid != '')		
	 	{
	 		$datalogs = array_merge($datalog,$sesio);
			$this->Model_admin_login->insert_user($table_name_log,$datalogs);
			//echo $leadidz;
	 	}		

		////-===================Software Log======================
	
	    if($lastid != '')
		{	

			$this->android_software_log_insert($lastid,'Task','New','Task Create','','','Task Created',$row['user_id'],$row['branch_id']);

			$newusr = $row['user_resp'];
			$lgnusr = $row['user_id'];
			if($newusr != $lgnusr ){
		 		$this->android_software_log_insert($lastid,'Task','Task','User',$lgnusr,$newusr,'Task User Changed',$row['user_id'],$row['branch_id']);
		 	}

			$task_name = $row['task_name'];
			$tnm = $this->db->query("select * from tbl_master_data where serial_number='$task_name' ");
			$getNm = $tnm->row();
			$tsknm = $getNm->keyvalue;
			$maker_id = $row['user_id'];
			$users = $row['user_resp'];
			$description = $row['snotes'];
			

				$this->android_software_log_insert($lastid,'Task','Task',$tsknm,$maker_id,$users,$description,$row['user_id'],$row['branch_id']);

				$lead_id = $row['leadid'];
				if($lead_id != '')
				{
					$this->android_update_note_log($lead_id,'main_task','Task',$description,$row['user_id'],$row['branch_id']);	
				}
				

			//==============Software Log Data & Task Seen_id====
		    $usrid=$lgnusr.",".$newusr;
			$this->db->query("update tbl_software_log set seen_id='$usrid' where slog_id='$lastid' and mdl_name = 'Task' ");			
			$this->db->query("update tbl_task set seen_id='$usrid' where task_id='$lastid' ");
			//===============End=============
		
			$this->leadJsonResponse(200,"Lead Task Added Successfully !",NULL);
		}

		
}



public function update_lead_stage()
{
	
	//@extract($_POST);

	$json  = file_get_contents('php://input');	
	$book  = json_decode($json,true);
	//print_r($json);die;
	$array = json_decode(json_encode($book), true);

	$lid    = $array['stage_leadid'];
	$newstg = $array['new_stage'];

	$stg = $this->db->query("select * from tbl_leads where lead_id='$lid' ");
	$getStg = $stg->row();
	$oldstg = $getStg->stage;
	
	if($oldstg != $newstg)
	{
		$this->android_software_log_insert($lid,'Lead','Lead','Stage',$oldstg,$newstg,'Lead Stage Changed',$array['user_id'],$array['branch_id']);
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
		//echo $lid;
		$this->leadJsonResponse(200,"Lead Stage Updated Successfully !",NULL);
	}

}

public function update_lead_status()
{
	
	//@extract($_POST);

	$json  = file_get_contents('php://input');	
	$book  = json_decode($json,true);
	//print_r($json);die;
	$array = json_decode(json_encode($book), true);

	// echo '<pre>';
	// print_r($_POST);die;
	
	$lid = $array['state_leadid'];
	//$ldstg = $this->input->post('ldstg');

	$stat = $array['new_state'];
	$desc = $array['lead_stat_desc'];
	$docket = $array['docket_no'];


	$stats = $this->db->query("select * from tbl_leads where lead_id='$lid' ");
	$getStas = $stats->row();
	$oldstatus = $getStas->lead_state;

	if($oldstatus != $stat)
	{
		$this->android_software_log_insert($lid,'Lead','Lead','Status',$oldstatus,$stat,'Lead Status Changed',$array['user_id'],$array['branch_id']);
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
		$this->android_software_log_insert($lid,'Lead','Status','Won','','',$docket,$array['user_id'],$array['branch_id']);
	}
	elseif($stat == 67)
	{
		$this->db->query("update tbl_leads set lead_state='".$stat."', stage='17',lead_stat_desc='".$desc."',last_update='$dtTime' where lead_id='".$lid."' ");	
	}
	elseif($stat == 79)
	{
		$this->db->query("update tbl_leads set lead_state='".$stat."', stage='17',lead_stat_desc='".$desc."',last_update='$dtTime' where lead_id='".$lid."' ");	
	}

 	   //echo $lid;
 	   if($lid != '')
 	   {
 	   		$this->leadJsonResponse(200,"Lead Status Updated Successfully !",NULL);
 	   }	   

}


public function insert_lead_remarks()
{
	
	//@extract($_POST);

	$json  = file_get_contents('php://input');	
	$book  = json_decode($json,true);
	//print_r($json);die;
	$array = json_decode(json_encode($book), true);

	 // echo '<pre>';
	 // print_r($_POST);die;
	 // echo '</pre>';

	$table_name = 'tbl_note';
	$pri_col    = 'note_id';

	$id = $array['noteid'];
	//echo $this->input->post('leadidno');

	//$this->load->model('Model_admin_login');

	$data = array(
						'note_logid' => $array['leadidno'],
						'note_type'	 => 'Lead',	
						'main_lead_id_note'	=>'Inner Lead',
						//'note_date' => $this->input->post('note_date'),
						'note_desc' => $array['note_desc']
					   
					  );
	$sesio = array(

						//'comp_id' => $array['comp_id'],
						'brnh_id'    => $array['branch_id'],
						'maker_id'   => $array['user_id'],
						'author_id'  => $array['user_id'],
						'maker_date' => date('y-m-d'),
						'author_date'=> date('y-m-d')
				    );
		
		
		date_default_timezone_set("Asia/Kolkata");
		$dtTime = date('Y-m-d H:i:s');

		if($id != '')
		{
			$leadidno  = $array['leadidno'];
			$note_desc = $array['note_desc'];
			//$main_id   = $array['main_id'];

			$this->Model_admin_login->update_user($pri_col,$table_name,$id,$data);
			//$this->db->query("update tbl_leads set discription = '$note_desc' where main_lead_id = '$main_id' and lead_id = '$leadidno' ");
			$this->db->query("update tbl_leads set last_update = '$dtTime' where lead_id = '$leadidno' ");
		  	//echo 2;
		  	$this->leadJsonResponse(200,"Lead Remarks Updated Successfully !",NULL);	
		}
		else
		{	
			$data_merger = array_merge($data,$sesio);
			$this->Model_admin_login->insert_user($table_name,$data_merger);
			$leadidno = $array['leadidno'];
			$this->db->query("update tbl_leads set last_update = '$dtTime' where lead_id = '$leadidno' ");
	    	//echo 1;
	    	$this->leadJsonResponse(200,"Lead Remarks Added Successfully !",NULL);
		}

		////-===================Software Log======================

		$leadidno = $array['leadidno'];
	
	    if($leadidno != '' & $id == '')
		{	
			
			$note_desc = $array['note_desc'];
				//$this->android_software_log_insert($leadidno,'Lead','Note','Lead Notes','','','Lead Remarks Added');
				$this->android_software_log_insert($leadidno,'Lead','Note','Lead Notes','','',$note_desc,$array['user_id'],$array['branch_id']);

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


function lead_details()
{

	$LdId = $this->input->get('lead_id');

	//======================Lead List=====================

		$lead=$this->db->query("select * from tbl_leads where lead_id='$LdId' ");
		$count=$lead->num_rows();

	if($count > 0){

		$value_lead = $lead->row();
		

			$masterData=$this->db->query("select * from tbl_master_data where param_id=20 ");
			
			$master_stage=array();	
			foreach ($masterData->result() as $getStage) 
			{
				
		        $dataStage = array(
								'serial_number' => $getStage->serial_number,
								'keyvalue'      => $getStage->keyvalue,
						     );

		        array_push($master_stage,$dataStage);
		    } 

		
		    $masterData2=$this->db->query("select * from tbl_master_data where param_id=22 ");
			
			$master_status=array();	
			foreach ($masterData2->result() as $getStatus) 
			{
				
		        $dataStatus = array(
								'serial_number' => $getStatus->serial_number,
								'keyvalue'      => $getStatus->keyvalue,
						     );

		        array_push($master_status,$dataStatus);
		    } 

		
			//===================End======================

		    $sqlgroup1=$this->db->query("select * from tbl_user_mst where user_id='$value_lead->maker_id' ");
		    $getOwner = $sqlgroup1->row();


			$status1=$this->db->query("select * from tbl_master_data where serial_number='$value_lead->lead_state'");
		  	$getStatus1 = $status1->row();

		  	$stg = $this->db->query("select * from tbl_master_data where serial_number='$value_lead->stage' ");
	  		$getStage = $stg->row();

	  		$inds = $this->db->query("select * from tbl_master_data where serial_number='$value_lead->industry' ");
	  		$getInds = $inds->row();

	  		$sqlgroup=$this->db->query("select * from tbl_user_mst where user_id='$value_lead->user_resp' ");
			$getUser = $sqlgroup->row();
	 		
	 		// 	$brnh=$this->db->query("select * from tbl_branch_mst where brnh_id='$getUser->brnh_id' ");
			// $getBrnh=$brnh->row();
			// ."(".$getBrnh->brnh_name.")"

	 		$cnt=$this->db->query("select * from tbl_contact_m where contact_id='$value_lead->contact_id'");
	 		$getCnt=$cnt->row();

	 		$email_val=json_decode($getCnt->email);
	 		$phone_val=json_decode($getCnt->phone);

	 		$org=$this->db->query("select * from tbl_organization where org_id='$value_lead->org_id' ");
	 		$getOrg=$org->row();

			$mkrdt=$value_lead->maker_date;
			$crdate=date("Y-m-d");
			$earlier = new DateTime($crdate);
			$later = new DateTime($mkrdt);

			$diff = $later->diff($earlier)->format("%a");
			$leadage = $diff." days";


	$tskDes = $this->db->query("select * from tbl_note where note_logid='".$value_lead->lead_id."' AND (main_lead_id_note='main_lead' OR main_lead_id_note='main_task' OR main_lead_id_note='Inner Lead') AND (note_type='Lead' OR note_type='Task') ORDER BY note_id DESC ");
	$desc_list=array();
	
	foreach ($tskDes->result() as $getTskdesc) 
	{

		$tskOwnr = $this->db->query("select * from tbl_user_mst where user_id='$getTskdesc->maker_id' ");
		$getTskownr = $tskOwnr->row();
		
		$descp = array(

				'owner' => $getTskownr->user_name,
				'date'  => $getTskdesc->note_date,
				'type'  => $getTskdesc->note_type,
				'desc'  => strip_tags($getTskdesc->note_desc),

			);
		
		array_push($desc_list,$descp);


	}

	$data_details['details'] = array(

					'lead_id'		=> $value_lead->lead_id,
					'lead_name'  	=> $value_lead->lead_number,
					'lead_owner' 	=> $getOwner->user_name,
					'expected_closure_date'  => $value_lead->closuredate,
					'lead_status'  => $getStatus1->keyvalue,
					'docket_no'    => $value_lead->docket_no,
					'lead_stage'  => $getStage->keyvalue,
					'assign_to'   => $getUser->user_name,
					'organization'=> $getOrg->org_name,
					'contact'     => $getCnt->contact_name,
					'email_id'    => $email_val,
					'phone_no'    => $phone_val,
					'address'     => $getCnt->address,
					'industry'    => $getInds->keyvalue,
					'opp_value'   => $value_lead->opp_value,
					'description' => $desc_list,
					'leadage'     => $leadage,
					'maker_date'  => $value_lead->maker_date,
					'stage'		  => $master_stage, 
					'status'	  => $master_status,		

				);
	
	//===============+Related=========================


	$org=$this->db->query("select * from tbl_mulit_orgz where morg_logid='$LdId' AND morg_type='Organization' ");
	$count=$org->num_rows();

	$data1=array();
	foreach ($org->result() as $getOrg) 
	{

		$orgnm=$this->db->query("select * from tbl_organization where org_id='$getOrg->orgid'");
		$getNmOrg=$orgnm->row();
		$org_data=array(

				'Lead_id' 		=> $LdId,
				'Org_id'  		=> $getOrg->orgid,
				'Org_name'		=> $getNmOrg->org_name,
				'org_details' 	=> $getOrg->morg_details,	
				'default_org'	=> $getOrg->default_org,


				   );

		array_push($data1,$org_data);
	}

	$aa['organization']=$data1;
	//$LdId=$this->input->get('lead_id');
	$mutli=$this->db->query("select * from tbl_mulit_orgz where morg_logid='$LdId' AND morg_type='Contact'");

	$data2=array();
	foreach ($mutli->result() as $getMulti) 
	{
		
		$cntnm=$this->db->query("select * from tbl_contact_m where contact_id='$getMulti->orgid'");
		$getNmCnt=$cntnm->row();
		$cnt_data=array(

				'Lead_id' 	   => $LdId,				
				'Cnt_id'  	   => $getMulti->orgid,
				'Cnt_name'	   => $getNmCnt->contact_name,
				'cnt_details'  => $getMulti->morg_details,
			    'default_cnt'  => $getMulti->default_org,

				   );

		array_push($data2,$cnt_data);
	}
	
	$bb['contact']=$data2;


	$ltask=$this->db->query("select * from tbl_task where lead_id='$LdId'");
	$data3=array();

	foreach ($ltask->result() as $getLd) 
	{

		$tname=$this->db->query("select * from tbl_master_data where serial_number='$getLd->task_name'");
		$getTname=$tname->row();

		$tstatus=$this->db->query("select * from tbl_master_data where serial_number='$getLd->task_status'");
		$getTstatus=$tstatus->row();

		$usrnm=$this->db->query("select * from tbl_user_mst where user_id='$getLd->user_resp' ");
		$getUsrnm=$usrnm->row();

		$task_data=array(

				'task_name'   => $getTname->keyvalue,
				'due_date'	  => $getLd->task_name,
				'assign_to'   => $getUsrnm->user_name,
				'description' => $getLd->description,
				'status' 	  => $getTstatus->keyvalue,
				'progress'	  => $getLd->progress_percnt,
			);

		array_push($data3,$task_data);
	}

	$cc['task']=$data3;

	$rates=$this->db->query("select * from tbl_lead_rates where lead_id='$LdId' ");
	$count_rates=$rates->num_rows();

	if($count_rates > 0)
	{
		$data4=array();
		foreach ($rates->result() as $getRates) 
		{
			
		  $rates_data=array(

					'rates' 			=> $getRates->lead_rates,
					'from' 				=> $getRates->lead_from,
					'to' 				=> $getRates->lead_to,
					'product' 			=> $getRates->lead_product,
					'rate_type' 		=> $getRates->rate_type,
					'basic_freight' 	=> $getRates->bsc_frght,
					'gr_charge' 		=> $getRates->gr_chrg,
					'labour_charge' 	=> $getRates->lbr_chrg,
					'enroute_charge' 	=> $getRates->enrt_chrg,
					'delivery_charge' 	=> $getRates->dlvry_charge,
					'misc_charge' 		=> $getRates->misc_charge,
					'risk_charge'		=> $getRates->risk_charge,
					'remarks' 			=> $getRates->rate_rmrks,
			);

		  array_push($data4,$rates_data);

		}

		$dd['rates']=$data4;
	}
	else
	{
		$dd['rates']=NULL;	
	}

	$files=$this->db->query("select * from tbl_file where file_logid='$LdId' AND file_type='Lead'");
	$count_files=$files->num_rows();

	if($count_files > 0)
	{
		$data5=array();
		foreach($files->result() as $getFiles)
		{


			$files_data=array(
					'file_name' =>$getFiles->files_name,
					'file_desc' =>$getFiles->files_desc,
				);

			array_push($data5,$files_data);
		}

		$ee['files']=$data5;
	}
	else
	{
		$ee['files']=NULL;
	}

	$remarks=$this->db->query("select * from tbl_note where main_lead_id_note='Inner Lead' AND note_type='Lead' AND note_logid='$LdId'");
	$count_remarks=$remarks->num_rows();

	if($count_remarks > 0)
	{
		$data6=array();
		foreach($remarks->result() as $getRemarks)
		{	

			$remarks_data=array(

				'remarks_date' =>$getRemarks->note_date,
				'remarks_desc' =>$getRemarks->note_desc,

			);

			array_push($data6,$remarks_data);

		}

		$ff['remarks']=$data6;
	}
	else
	{
		$ff['remarks']=NULL;
	}

	$data_related['related']=array_merge($aa,$bb,$cc,$dd,$ee,$ff);

	///===============All Activity=======================

	$leadDtl=$this->db->query("select * from tbl_leads where lead_id='$LdId' ");	
	$getDtl=$leadDtl->row();

	$abc = $this->db->query("select * from tbl_user_mst where user_id='".$getDtl->maker_id."'");
	$xyz = $abc->row();

	//$data0['lead_created_by']=$xyz->user_name;
	//$data0['lead_created_on']=$getDtl->maker_date;	

	$SftLog1 = $this->db->query("select * from tbl_software_log where slog_id='".$LdId."' AND mdl_name='Lead' AND (slog_name='Lead' OR slog_name='Note' OR slog_name='Status') AND (slog_type='Lead Notes' OR slog_type='User' OR slog_type='Stage' OR slog_type='Status' OR slog_type='Won') ORDER BY sid DESC ");

	$all_log=array();
	foreach ($SftLog1->result() as $getSftLog1) 
	{ 


		$RespUsr=$this->db->query("select * from tbl_user_mst where user_id='".$getSftLog1->maker_id."'");
		$getRespUsr=$RespUsr->row();

		$docket_no='';
		if($getSftLog1->slog_name == 'Status' && $getSftLog1->slog_type == 'Won')
		{
			$docket_no=$getSftLog1->remarks;
		}

		$oldUsrName='';
		$newUsrName='';
		if($getSftLog1->slog_type == 'User')
		{

		$oldusr=$this->db->query("select * from tbl_user_mst where user_id='".$getSftLog1->old_id."' ");
		$getOldUsr=$oldusr->row();
		$oldUsrName=$getOldUsr->user_name;

		$newUsr = $this->db->query("select * from tbl_user_mst where user_id='".$getSftLog1->new_id."' ");
		$getNewUsr = $newUsr->row();
		$newUsrName=$getNewUsr->user_name;

		}
        
        $oldKeyName='';
        $newKeyName='';
        if($getSftLog1->slog_type == 'Status' || $getSftLog1->slog_type == 'Stage')
        {
        
        $oldkey=$this->db->query("select * from tbl_master_data where serial_number='".$getSftLog1->old_id."' ");
		$getOldKey = $oldkey->row();
		$oldKeyName=$getOldKey->keyvalue;

		$newkey = $this->db->query("select * from tbl_master_data where serial_number='".$getSftLog1->new_id."' ");
		$getNewKey = $newkey->row();
		$newKeyName=$getNewKey->keyvalue;
		
		}

		$data0=array(

						'old_username' => $oldUsrName,
						'new_username' => $newUsrName,
						
						'old_key'	  => $oldKeyName,
						'new_key'	  => $newKeyName,

						'docket_no'   => $docket_no,

						'activity_name' => $getSftLog1->slog_type,
						'remarks'       => $getSftLog1->remarks,

						'maker_date'    => $getSftLog1->maker_date,
						'created_by'    => $getRespUsr->user_name,

						'lead_created_by' => $xyz->user_name,
						'lead_created_on' => $getDtl->maker_date,

					  );

		array_push($all_log,$data0);
	
	}

	//$log=array_merge($data0,$all_log);

	$data_all_activity['activity']=$all_log;


	$data=array_merge($data_details,$data_related,$data_all_activity);
	

  }
	if($count > 0){
		$this->leadJsonResponse(200,"Lead Inner Page Data !",$data);	
	}else{
		$this->leadJsonResponse(400,"Lead Not Found !",NULL);
	}
	
	//=========================End========================

}


function leadJsonResponse($status,$status_message,$data)
{
	header('Content-type: application/json');
	header('Accept: application/vnd.ritco.v1');

	$response['status']=$status;
	$response['status_message']=$status_message;
	$response['data']=$data;
	$json_response = json_encode($response);
	echo $json_response;
}


function get_task_list()
{

	//$taskid=$this->input->get('task_id');

	$tasks=$this->db->query("select * from tbl_task where status='A' ORDER BY last_update DESC");	
	$count=$tasks->num_rows();
	$task_array = array();
	foreach ($tasks->result() as $getTlist) 
	{

		$mstrs=$this->db->query("select * from tbl_master_data where serial_number='$getTlist->task_name'");
		$getMstr=$mstrs->row();

		$prts=$this->db->query("select * from tbl_master_data where serial_number='$getTlist->priority' ");
		$getPrts=$prts->row();

		$ts=$this->db->query("select * from tbl_master_data where serial_number='$getTlist->task_status'");
		$getTs=$ts->row();

		$sqlgroup1=$this->db->query("select * from tbl_user_mst where user_id='$getTlist->user_resp' ");
		$getUser = $sqlgroup1->row();
 		
 		$brnh=$this->db->query("select * from tbl_branch_mst where brnh_id='$getUser->brnh_id' ");
		$getBrnh=$brnh->row();

		$usr=$this->db->query("select * from tbl_user_mst where user_id='$getTlist->maker_id' ");
		$gerMaker = $usr->row();
 		
		$lds=$this->db->query("select * from tbl_leads where lead_id='$getTlist->lead_id' ");
		$getLd=$lds->row();		

		$tskDes = $this->db->query("select * from tbl_note where note_logid='".$getTlist->task_id."' AND main_lead_id_note='Inner Task' AND note_type='Task' ORDER BY note_id DESC ");

	  	$getTskdesc = $tskDes->row();
		if(sizeof($getTskdesc) > 0) 
		{  
		    $big = $getTskdesc->note_desc;  
			$big = strip_tags($big);
			$small = substr($big, 0, 20);
			//echo $small .".....";
		
		} else {

		   	$big = $getTlist->description;
			$big = strip_tags($big);
			$small = substr($big, 0, 20);
			//echo strtolower($small .".....");
		} 


		$cntnm=$this->db->query("select * from tbl_contact_m where contact_id='$getTlist->contact_person' ");
		$getCnt=$cntnm->row();

		$orgnm=$this->db->query("select * from tbl_organization where org_id='$getTlist->org_name' ");
		$getOrgnm=$orgnm->row();


		if($getTlist->lead_id == 0)
		{
			$lead_name='';
			$lead_id='';
			$contact_id='';
			$contact_name='';
			$org_id='';
			$org_name='';
		}
		else
		{
			$lead_name=$getLd->lead_number;
			$lead_id=$getTlist->lead_id;
			$contact_id=$getTlist->contact_person;
			$contact_name=$getCnt->contact_name;
			$org_id=$getTlist->org_name;
			$org_name=$getOrgnm->org_name;
		}

		$task_datas=array(

			'task_id'		=>$getTlist->task_id,
			'task_name_id'  =>$getTlist->task_name,
			'task_name' 	=>$getMstr->keyvalue,
			'priority_id'	=>$getTlist->priority,
			'priority_name' =>$getPrts->keyvalue,
			'status_id'		=>$getTlist->task_status,
			'task_status'	=>$getTs->keyvalue,
			'progress'		=>$getTlist->progress_percnt,
			'due_date'		=>$getTlist->date_due,
			'assignto_id'	=>$getTlist->user_resp,
			'assign_to'		=>$getUser->user_name."(".$getBrnh->brnh_name.")",

			'lead_id'		=>$lead_id,
			'lead_name' 	=>$lead_name,
			'contact_id'	=>$contact_id,
			'contact_name'	=>$contact_name,
			'org_id'		=>$org_id,
			'org_name'		=>$org_name,
			
			'task_owner'	=>$gerMaker->user_name,			
			'small_desc'	=>strtolower($small ."....."),
			'big_desc'		=>$big,
								

		);
		
		array_push($task_array,$task_datas);
	}

	if($count > 0){
		$this->taskJsonResponse(200,"Task List",$task_array);
	}else{
		$this->taskJsonResponse(400,"Task Id Not Found !",NULL);
	}

}


function task_related_to()
{
	
	$tskld=$this->db->query("select * from tbl_leads where status='A'");

	$datatask=array();
	foreach($tskld->result() as $getTskLd)
	{

		$cnt=$this->db->query("select * from tbl_contact_m where contact_id='$getTskLd->contact_id'");
		$getCnt=$cnt->row();

		$org=$this->db->query("select * from tbl_organization where org_id='$getTskLd->org_id'");
		$getOrg=$org->row();

		$related_data=array(

				'lead_id'   	=>$getTskLd->lead_id,
				'lead_name'		=>$getTskLd->lead_number,
				'contact_id'	=>$getTskLd->contact_id,
				'contact_name'	=>$getCnt->contact_name,
				'org_id'		=>$getTskLd->org_id,
				'org_name'		=>$getOrg->org_name

			);

		array_push($datatask,$related_data);
	}

	$this->taskJsonResponse(200,"Task Linked To",$datatask);

}


function delete_task()
{
	

	$id=$this->input->get('task_id');
	$task=$this->db->query("select * from tbl_task where task_id='$id' ");
	$count=$task->num_rows();

	if($id!='' && $count > 0)
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


		//=====================+Data===========================

		$tasks=$this->db->query("select * from tbl_task where status='A' ORDER BY last_update DESC");	
		$count=$tasks->num_rows();
		$task_array = array();
		foreach ($tasks->result() as $getTlist) 
		{

			$mstrs=$this->db->query("select * from tbl_master_data where serial_number='$getTlist->task_name'");
			$getMstr=$mstrs->row();

			$prts=$this->db->query("select * from tbl_master_data where serial_number='$getTlist->priority' ");
			$getPrts=$prts->row();

			$ts=$this->db->query("select * from tbl_master_data where serial_number='$getTlist->task_status'");
			$getTs=$ts->row();

			$sqlgroup1=$this->db->query("select * from tbl_user_mst where user_id='$getTlist->user_resp' ");
			$getUser = $sqlgroup1->row();
	 		
	 		$brnh=$this->db->query("select * from tbl_branch_mst where brnh_id='$getUser->brnh_id' ");
			$getBrnh=$brnh->row();

			$usr=$this->db->query("select * from tbl_user_mst where user_id='$getTlist->maker_id' ");
			$gerMaker = $usr->row();
	 		
			$lds=$this->db->query("select * from tbl_leads where lead_id='$getTlist->lead_id' ");
			$getLd=$lds->row();		

			$tskDes = $this->db->query("select * from tbl_note where note_logid='".$getTlist->task_id."' AND main_lead_id_note='Inner Task' AND note_type='Task' ORDER BY note_id DESC ");

		  	$getTskdesc = $tskDes->row();
			if(sizeof($getTskdesc) > 0) 
			{  
			    $big = $getTskdesc->note_desc;  
				$big = strip_tags($big);
				$small = substr($big, 0, 20);
				//echo $small .".....";
			
			} else {

			   	$big = $getTlist->description;
				$big = strip_tags($big);
				$small = substr($big, 0, 20);
				//echo strtolower($small .".....");
			} 


			$cntnm=$this->db->query("select * from tbl_contact_m where contact_id='$getTlist->contact_person' ");
			$getCnt=$cntnm->row();

			$orgnm=$this->db->query("select * from tbl_organization where org_id='$getTlist->org_name' ");
			$getOrgnm=$orgnm->row();


			if($getTlist->lead_id == 0)
			{
				$lead_name='';
				$lead_id='';
				$contact_id='';
				$contact_name='';
				$org_id='';
				$org_name='';
			}
			else
			{
				$lead_name=$getLd->lead_number;
				$lead_id=$getTlist->lead_id;
				$contact_id=$getTlist->contact_person;
				$contact_name=$getCnt->contact_name;
				$org_id=$getTlist->org_name;
				$org_name=$getOrgnm->org_name;
			}

			$task_datas=array(

				'task_id'		=>$getTlist->task_id,
				'task_name_id'  =>$getTlist->task_name,
				'task_name' 	=>$getMstr->keyvalue,
				'priority_id'	=>$getTlist->priority,
				'priority_name' =>$getPrts->keyvalue,
				'status_id'		=>$getTlist->task_status,
				'task_status'	=>$getTs->keyvalue,
				'progress'		=>$getTlist->progress_percnt,
				'due_date'		=>$getTlist->date_due,
				'assignto_id'	=>$getTlist->user_resp,
				'assign_to'		=>$getUser->user_name."(".$getBrnh->brnh_name.")",

				'lead_id'		=>$lead_id,
				'lead_name' 	=>$lead_name,
				'contact_id'	=>$contact_id,
				'contact_name'	=>$contact_name,
				'org_id'		=>$org_id,
				'org_name'		=>$org_name,
				
				'task_owner'	=>$gerMaker->user_name,			
				'small_desc'	=>strtolower($small ."....."),
				'big_desc'		=>$big,
									

			);
			
			array_push($task_array,$task_datas);
		}
	}	

	if($count > 0) {
		$this->taskJsonResponse(200,"Task Deleted Successfully !",$task_array);
	}else {
		$this->taskJsonResponse(400,"Task Not Found !",NULL);
	}
}


function edit_task()
{
	
	//@extract($_POST);
	
	//print_r($_POST);die;

	$json = file_get_contents('php://input');	
	$book = json_decode($json,true);
	//print_r($json);die;
	$row = json_decode(json_encode($book), true);

	$table_name_log ='tbl_task_log';
	$table_name = 'tbl_task';
	$pri_col = 'task_id';
	
	$taskid = $row['task_id'];
	
	//$this->load->model('Model_admin_login');

	date_default_timezone_set("Asia/Kolkata");
	$dtTime = date('Y-m-d H:i:s');

	$data= array(
					
					'task_name' 	  => $row['task_name'],
					'date_due'  	  => $row['due_date'],
					//'reminder_date' => $row['reminder_date'],
					'priority'  	  => $row['priority'],
					'progress_percnt' => $row['progress'],
					'task_status' 	  => $row['status'],
					'user_resp'   	  => $row['user_resp'],
					'lead_id'     	  => $row['leadid'],
					'contact_person'  => $row['tcontact_person'],
					'org_name' 		  => $row['torg_name'],
					'description' 	  => $row['snotes'],
					//'visibility'	  => $row['optionsRadios'],
					'last_update'     => $dtTime,

				);


		$sesio = array(
					//'comp_id'   => $row['comp_id'],
					'brnh_id'     => $row['branch_id'],
					'maker_id'    => $row['user_id'],
					'author_id'   => $row['user_id'],
					'maker_date'  => date('y-m-d'),
					'author_date' => date('y-m-d')
				  );
		
		//=======================Task Log=====================		
		
		$datalog= array(
				
						'task_id' 		   => $row['task_id'],
						'task_name' 	   => $row['task_name'],
						'date_due' 		   => $row['due_date'],
						//'reminder_date'  => $row['reminder_date'],
						'progress_percnt'  => $row['progress'],
						'priority'         => $row['priority'],
						'task_status' 	   => $row['status'],
						'user_resp' 	   => $row['user_resp'],
						'lead_id' 		   => $row['leadid'],
						'contact_person'   => $row['tcontact_person'],
						'org_name' 		   => $row['torg_name'],
						'description'      => $row['snotes'],
						//'visibility'     => $row['optionsRadios'],									
							
				      	);

		$datalogs = array_merge($datalog,$sesio);
		$this->Model_admin_login->insert_user($table_name_log,$datalogs);
			
	 	if($taskid != '')		
		{	

			$login_id=$row['user_id'];

			$newstatus=$row['status'];
			$tsts=$this->db->query("select * from tbl_task where task_id='$taskid' ");
			$getTsts=$tsts->row();
			$oldstatus=$getTsts->task_status;
			if($oldstatus != $newstatus){
				$this->android_software_log_insert($taskid,'Task','Task','Status',$oldstatus,$newstatus,'Task Status Changed');
			}

			$newusr = $row['user_resp'];
			$usr = $this->db->query("select * from tbl_task where task_id='$taskid' ");
			$getUsr = $usr->row();
			$oldusr = $getUsr->user_resp;

			//==========Software Log Data============

			$pid=$this->db->query("select * from tbl_software_log where slog_id='$taskid' and mdl_name='Task' ");
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
			$this->db->query("update tbl_software_log set seen_id='$updtId' where slog_id='$taskid' AND mdl_name='Task' ");
			//============End===============

			 if($oldusr != $newusr){
			 		$this->android_software_log_insert($taskid,'Task','Task','User',$oldusr,$newusr,'Task User Changed');
			 		$this->db->query("update tbl_software_log set seen_id='$newstatusid' where slog_id='$taskid' AND mdl_name='Task' ");
			 }

			$this->Model_admin_login->update_user($pri_col,$table_name,$taskid,$data);

			$this->db->query("update tbl_note set note_desc = '$snotes' where main_lead_id_note = 'main_task' and note_logid = '$taskid'");


			//==========Task Seen_id Update============

			$snid=$this->db->query("select * from tbl_task where task_id='$taskid' ");
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
				$this->db->query("update tbl_task set seen_id='$newseenid' where task_id='$taskid' ");	
			}
			
			//============End===============

			$this->taskJsonResponse(200,"Task Updated Successfully !",NULL);
	
		}		
					
}



public function update_task_assignto()
{


	$json = file_get_contents('php://input');	
	$book = json_decode($json,true);
	//print_r($json);die;
	$row = json_decode(json_encode($book), true);

	$id = $row['task_id'];
	$uid = $row['assign_user'];
	 //print_r($_POST);die;

		if($id != '')
		{	

			$login_id=$row['user_id'];
			
			$newusr = $row['assign_user'];
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
				$this->android_software_log_insert($id,'Task','Task','User',$oldusr,$newusr,'Task User Changed');
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
		//echo $uid;
		$this->taskJsonResponse(200,"Task Assign To Updated Successfully !",NULL);
	}
}


function task_details()
{

	$taskid=$this->input->get('task_id');

	$task=$this->db->query("select * from tbl_task where task_id='$taskid' ");
	$count=$task->num_rows();
	$getTask=$task->row();

	if($count > 0)
	{


		$tsts=$this->db->query("select * from tbl_master_data where serial_number='$getTask->task_status'");
		$getStatus=$tsts->row();

		$assign=$this->db->query("select * from tbl_user_mst where user_id='$getTask->user_resp'");
		$getAssign=$assign->row();

		$prty=$this->db->query("select * from tbl_master_data where serial_number='$getTask->priority'");
		$getPrty=$prty->row();

		$tstatus=$this->db->query("select * from tbl_master_data where serial_number='$getTask->task_status' ");
		$getStatus=$tstatus->row();

		if($getTask->lead_id == 0)
		{
			$leadname='';
			$cntname='';
			$orgname='';
		}
		else
		{
			$leadnm=$this->db->query("select * from tbl_leads where lead_id='$getTask->lead_id'");
			$getLeadnm=$leadnm->row();
			$leadname=$getLeadnm->lead_number;

			$cntnm=$this->db->query("select * from tbl_contact_m where contact_id='$getTask->contact_person' ");
			$getCnt=$cntnm->row();
			$cntname=$getCnt->contact_name;

			$orgnm=$this->db->query("select * from tbl_organization where org_id='$getTask->org_name' ");
			$getOrgnm=$orgnm->row();
			$orgname=$getOrgnm->org_name;
		}

		$usr=$this->db->query("select * from tbl_user_mst where user_id='$getTask->maker_id'");
		$getUsr=$usr->row();


		$tskDes = $this->db->query("select * from tbl_note where note_logid='".$taskid."' AND main_lead_id_note='Inner Task' AND note_type='Task' ORDER BY note_id DESC  ");
		$desc_list=array();
	
		foreach ($tskDes->result() as $getTskdesc) 
		{

			$tskOwnr = $this->db->query("select * from tbl_user_mst where user_id='$getTskdesc->maker_id' ");
			$getTskownr = $tskOwnr->row();
			
			$descp = array(

					'owner' => $getTskownr->user_name,
					'date'  => $getTskdesc->note_date,
					'type'  => $getTskdesc->note_type,
					'desc'  => strip_tags($getTskdesc->note_desc),

				);
			
			array_push($desc_list,$descp);


		}

		$desc_data=array(

					'owner' => $getUsr->user_name,
					'date'  => $getTask->maker_date,
					'type'  => 'Task',
					'desc'  => strip_tags($getTask->description),
		);

		$data_desc=array_merge($desc_list,$desc_data);

		$data_details['details']=array(


										'task_id' 		=>$taskid,
										'task_name'		=>$getStatus->keyvalue,
										'assign_to'		=>$getAssign->user_name,
										'due_date'		=>$getTask->date_due,
										'priority'		=>$getPrty->keyvalue,
										'progress'		=>$getTask->progress_percnt,
										'status'		=>$getStatus->keyvalue,
										'maker_date'	=>$getTask->maker_date,
										'lead_name' 	=>$leadname,
										'contact_name'	=>$cntname,
										'org_name'		=>$orgname,
										'task_desc'		=>$data_desc,

									);

		//===============+Related=========================


	$org=$this->db->query("select * from tbl_organization where org_id='$getTask->org_name' ");
	$getOrg=$org->row();
	$countorg=$org->num_rows();

	if($countorg > 0)
	{
		
		$json_phone=json_decode($getOrg->phone_no,true);
		$json_email=json_decode($getOrg->email,true);

		$data1=array(

					
					'Org_name'		=> $getOrg->org_name,
					'org_phone' 	=> $json_phone[0],	
					'org_email'		=> $json_email[0],
					'org_city' 		=> $getOrg->city,
					'org_pincode'  	=> $getOrg->pin_code,


				   );	
	}
	else
	{
		$data1=array(

					
					'Org_name'		=> '',
					'org_phone' 	=> '',	
					'org_email'		=> '',
					'org_city' 		=> '',
					'org_pincode'  	=> '',


				   );	
	}
	

	$aa['organization']=$data1;
	
	$cntnm=$this->db->query("select * from tbl_contact_m where contact_id='$getTask->contact_person'");
	$getNmCnt=$cntnm->row();

	$count_cnt=$cntnm->num_rows();

	if($count_cnt > 0)
	{
		
		$json_cphone=json_decode($getNmCnt->phone,true);
		$json_cemail=json_decode($getNmCnt->email,true);

		$data2=array(

			'contact_name' 	   	=> $getNmCnt->contact_name,				
			'contact_phone'  	=> $json_cphone[0],
			'contact_email'	  	=> $json_cemail_[0],
			'contact_city'  	=> $getNmCnt->city_name,
		    'contact_pincode'   => $getNmCnt->pincode,

			   );
	
	}
	else
	{

		$data2=array(

			'contact_name' 	   	=> '',				
			'contact_phone'  	=> '',
			'contact_email'	  	=> '',
			'contact_city'  	=> '',
		    'contact_pincode'   => '',

			   );

	}

	
	
	$bb['contact']=$data2;


	$tlead=$this->db->query("select * from tbl_leads where lead_id='$getTask->lead_id'");
	$getTlead=$tlead->row();

	$count_lead=$tlead->num_rows();

	
	if($count_lead > 0)
	{
		$cnt=$this->db->query("select * from tbl_contact_m where contact_id='$getTlead->contact_id'");
		$getCname=$cnt->row();

		$org=$this->db->query("select * from tbl_organization where org_id='$getTlead->org_id'");
		$getOname=$org->row();

		$usrnm=$this->db->query("select * from tbl_user_mst where user_id='$getTlead->user_resp' ");
		$getUsrnm=$usrnm->row();

		$data3=array(

						'lead_name'    => $getTlead->lead_number,
						'assign_to'    => $getUsrnm->user_name,
						'closure_date' => $getTlead->date_due,				
						'contact_name' => $getCname->contact_name,
						'org_name' 	   => $getOname->org_name,

					);

	}
	else
	{
		$data3=array(

						'lead_name'    => '',
						'assign_to'    => '',
						'closure_date' => '',				
						'contact_name' => '',
						'org_name' 	   => '',

					);		
	}

	$cc['lead']=$data3;


	$files=$this->db->query("select * from tbl_file where file_logid='$taskid' AND file_type='Task'");
	$count_file=$files->num_rows();

	if($count_file > 0)
	{
		$data5=array();
		foreach($files->result() as $getFiles)
		{


			$files_data=array(
					'file_name' =>$getFiles->files_name,
					'file_desc' =>$getFiles->files_desc,
				);

			array_push($data5,$files_data);
		}

		$dd['files']=$data5;
	}
	else
	{
		$dd['files']=NULL;
	}

	$remarks=$this->db->query("select * from tbl_note where main_lead_id_note='Inner Task' AND note_type='Task' AND note_logid='$taskid'");
	$count_remarks=$remarks->num_rows();

	if($count_remarks > 0)
	{
		$data6=array();
		foreach($remarks->result() as $getRemarks)
		{	

			$remarks_data=array(

				'remarks_date' =>$getRemarks->note_date,
				'remarks_desc' =>$getRemarks->note_desc,

			);

			array_push($data6,$remarks_data);

		}

		$ee['remarks']=$data6;
	}
	else
	{
		$ee['remarks']=NULL;
	}

	$data_related['related']=array_merge($aa,$bb,$cc,$dd,$ee);


	///===============All Activity Task=======================

	$leadDtl=$this->db->query("select * from tbl_task where task_id='$taskid' ");	
	$getDtl=$leadDtl->row();

	$abc = $this->db->query("select * from tbl_user_mst where user_id='".$getDtl->maker_id."'");
	$xyz = $abc->row();

	//$data0['lead_created_by']=$xyz->user_name;
	//$data0['lead_created_on']=$getDtl->maker_date;	

	$SftLog1 = $this->db->query("select * from tbl_software_log where slog_id='".$taskid."' AND mdl_name='Task' AND (slog_name='Task' OR slog_name='Note') AND (slog_type='Task Notes' OR slog_type='User' OR slog_type='Status') ORDER BY sid DESC ");

	$all_log=array();
	foreach ($SftLog1->result() as $getSftLog1) 
	{ 


		$RespUsr=$this->db->query("select * from tbl_user_mst where user_id='".$getSftLog1->maker_id."'");
		$getRespUsr=$RespUsr->row();

	/*	if($getSftLog1->slog_name == 'Task' && $getSftLog1->slog_type != 'User' && $getSftLog1->slog_type != 'Status')
		{
			
			$tskname = $getSftLog1->slog_type;

			$makr = $this->db->query("select * from tbl_user_mst where user_id='".$getSftLog1->old_id."'");
			$getMakr = $makr->row();
			$makrNm = $getMakr->user_name;

			$asnUsr = $this->db->query("select * from tbl_user_mst where user_id='".$getSftLog1->new_id."'");
			$getAsnusr = $asnUsr->row();
			$AsinName = $getAsnusr->user_name;

			$makerDate=$getSftLog1->maker_date;
			$remarks=$getSftLog1->remarks;

		}*/

		$oldUsrName='';
		$newUsrName='';
		if($getSftLog1->slog_name == 'Task' && $getSftLog1->slog_type == 'User')
		{

		$oldusr=$this->db->query("select * from tbl_user_mst where user_id='".$getSftLog1->old_id."' ");
		$getOldUsr=$oldusr->row();
		$oldUsrName=$getOldUsr->user_name;

		$newUsr = $this->db->query("select * from tbl_user_mst where user_id='".$getSftLog1->new_id."' ");
		$getNewUsr = $newUsr->row();
		$newUsrName=$getNewUsr->user_name;

		// $RespUsr = $this->db->query("select * from tbl_user_mst where user_id='".$getSftLog1->maker_id."'");
		// $getRespUsr = $RespUsr->row();
		// $resp_usr_name = $getRespUsr->user_name;
		// $makerDate=$getSftLog1->maker_date;

		}
        
        $oldKeyName='';
        $newKeyName='';
        if($getSftLog1->slog_name == 'Task' && $getSftLog1->slog_type == 'Status')
        {
        
        $oldkey=$this->db->query("select * from tbl_master_data where serial_number='".$getSftLog1->old_id."' ");
		$getOldKey = $oldkey->row();
		$oldKeyName=$getOldKey->keyvalue;

		$newkey = $this->db->query("select * from tbl_master_data where serial_number='".$getSftLog1->new_id."' ");
		$getNewKey = $newkey->row();
		$newKeyName=$getNewKey->keyvalue;

		// $RespUsr = $this->db->query("select * from tbl_user_mst where user_id='".$getSftLog1->maker_id."'");
		// $getRespUsr = $RespUsr->row();
		// $resp_usr_name = $getRespUsr->user_name;

		// $makerDate=$getSftLog1->maker_date;
		
		}

		$data0=array(

						'old_username' => $oldUsrName,
						'new_username' => $newUsrName,
						
						'old_key'	  => $oldKeyName,
						'new_key'	  => $newKeyName,

						'activity_name' => $getSftLog1->slog_type,
						'remarks'       => $getSftLog1->remarks,

						'maker_date'    => $getSftLog1->maker_date,
						'created_by'    => $getRespUsr->user_name,

						'task_created_by' => $xyz->user_name,
						'task_created_on' => $getDtl->maker_date,

					  );

		array_push($all_log,$data0);
	
	}


		$data_all_activity['activity']=$all_log;


		$data=array_merge($data_details,$data_related,$data_all_activity);

		$this->taskJsonResponse(200,"Task Inner Page Data !",$data);

	}
	else
	{

		$this->taskJsonResponse(400,"Task Not Found !",NULL);

	}

}


function insert_task_remarks()
{


	$json  = file_get_contents('php://input');	
	$book  = json_decode($json,true);
	//print_r($json);die;
	$row = json_decode(json_encode($book), true);

	$table_name = 'tbl_note';
	$pri_col    = 'note_id';

	$id = $row['noteid'];
	//echo  $row['tskid'];

	$this->load->model('Model_admin_login');

	$data = array(
				
					'note_logid'		=> $row['tskid'],
					'note_type'	 		=> 'Task',
					'main_lead_id_note'	=> 'Inner Task',
					//'note_name' 		=> $row['note_name'],
					//'note_date' 		=> $row['note_date'],
					'note_desc' 		=> $row['note_desc']
					   
				);
	$sesio = array(

						//'comp_id'   => $row['comp_id'],
						'brnh_id' 	  => $row['brnh_id'],
						'maker_id'    => $row['user_id'],
						'author_id'   => $row['user_id'],
						'maker_date'  => date('y-m-d'),
						'author_date' => date('y-m-d')
				    );
		
		$tskid = $row['tskid'];
		date_default_timezone_set("Asia/Kolkata");
		$dtTime = date('Y-m-d H:i:s');
		
		if($id != '')
		{
			$this->Model_admin_login->update_user($pri_col,$table_name,$id,$data);
			//$this->db->query("update  tbl_task set description = '$note_desc' where main_task_id = '$main_id' and task_id = '$tskid'");
			$this->db->query("update  tbl_task set last_update = '$dtTime' where task_id = '$tskid'");
		  	//echo 2;	
		  	$this->taskJsonResponse(200,"Task Remarks Updated Successfully !",);
		}
		else
		{	
			$data_merger = array_merge($data,$sesio);
			$this->Model_admin_login->insert_user($table_name,$data_merger);
			$this->db->query("update  tbl_task set last_update = '$dtTime' where task_id = '$tskid'");
	    	//echo 1;
	    	$this->taskJsonResponse(200,"Task Remarks Added Successfully !",);
		}

		////-===================Software Log======================

		
	
	    if($tskid != '' && $id == '')
		{	
			
			$note_desc = $row['note_desc'];			
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


function taskJsonResponse($status,$status_message,$data)
{
	header('Content-type: application/json');
	header('Accept: application/vnd.ritco.v1');

	$response['status']=$status;
	$response['status_message']=$status_message;
	$response['data']=$data;
	$json_response = json_encode($response);
	echo $json_response;
}


function get_organization_list()
{

	$org=$this->db->query("select * from tbl_organization where status='A' ORDER BY org_id DESC");
	$count=$org->num_rows();

	$org_array=array();
	foreach ($org->result() as $getOrgz) 
	{

		if($getOrgz->country != '')
		{
			$cntry=$this->db->query("select * from tbl_country_m where contryid='$getOrgz->country' ");
			$getCntry=$cntry->row();
			$cntryname=$getCntry->countryName;
		}
		else
		{
			$cntryname='';			
		}
		
		if($getOrgz->state_id != '')
		{
			$state=$this->db->query("select * from tbl_state_m where stateid='$getOrgz->state_id'");
			$getState=$state->row();
			$statename=$getState->stateName;	
		}
		else
		{
			$statename='';
		}
		

		$cnts=$this->db->query("select * from tbl_contact_m where contact_id='$getOrgz->contact_id' ");
		$getCnts=$cnts->row();
	
		$data_org=array(
					
						'org_id' 		=>$getOrgz->org_id,
						'org_name' 		=>$getOrgz->org_name,
						'org_website'  	=>$getOrgz->website,
						'org_email' 	=>$getOrgz->email,
						'org_phone' 	=>$getOrgz->phone_no,
						'org_address' 	=>$getOrgz->address,
						'org_city' 		=>$getOrgz->city,
						'state_id' 		=>$getOrgz->state_id,
						'state_name' 	=>$statename,
						'pin_code' 		=>$getOrgz->pin_code,
						'country_id' 	=>$getOrgz->country,
						'country_name' 	=>$cntryname,
						'org_desc' 		=>$getOrgz->description,
						'contact_id' 	=>$getOrgz->contact_id,
						'contact_name' 	=>$getCnts->contact_name,
						'designation' 	=>$getCnts->designation,
						'contact_email' =>$getCnts->email,
						'contact_phone' =>$getCnts->phone,
					
					);

		array_push($org_array, $data_org);

	}	

	if($count > 0)
	{
		$this->orgJsonResponse(200,"Organization List !",$org_array);
	}
	else
	{
		$this->orgJsonResponse(400,"Organization Not Found !",NULL);
	}

}

function post_organization()
{

	
	$json = file_get_contents('php://input');	
	$book = json_decode($json,true);
	//print_r($json);die;
	$row = json_decode(json_encode($book), true);
	
	$table_org = 'tbl_organization';
	$pri_org = 'org_id';
	$table_cntct = 'tbl_contact_m';
	$pri_cnt = 'contact_id';

    //=========================Organization============

	$email_val = json_encode($row['email_id'],true);
    $phone_no  = json_encode($row['phone_no'],true);
	
	$data_org= array(
			
					'org_name'    => $row['org_name'],
					'website'     => $row['website'],
					'phone_no'    => $phone_no,
					'email'       => $email_val,					
					'address'     => $row['address'],
					'city'        => $row['city'],
					'state_id'    => $row['state_id'],
					'pin_code' 	  => $row['pin_code'],
					'country'  	  => $row['country_id'],
					'description' => $row['snotes']

				);

		$sesio = array(

						//'comp_id' => $this->session->userdata('comp_id'),
						'brnh_id' 	  => $row['brnh_id'],
						'maker_id' 	  => $row['user_id'],
						'author_id'   => $row['user_id'],
						'maker_date'  => date('y-m-d'),
						'author_date' => date('y-m-d')
					  
					  );

		    // echo '<pre>';
			//    print_r($data);
			// echo '</pre>';die;

		$dataall_org = array_merge($data_org,$sesio);

		if($row['oldorgid'] != "")
		{
			$org_Id = $row['oldorgid'];
			$this->Model_admin_login->update_user($pri_org,$table_org,$org_Id,$data_org);
			echo 2;	
			$this->orgJsonResponse(200,"Organization Created Successfully !",NULL);
		}			
		else
		{
			$this->Model_admin_login->insert_user($table_org,$dataall_org);
			$org_Id = $this->db->insert_id();
			$this->software_log_insert($org_Id,'Orgz','New','Orgz Create','','','Organization Created');
			echo 1;
			$this->orgJsonResponse(200,"Organization Updated Successfully !",NULL);
		}
		//================Contact=======================

			$cemail_val = json_encode($row['cemail_id'],true);
			$cphone_no = json_encode($row['cphone_no'],true);

			$cntct_data = array(

								'contact_name' => $row['contact_name'],
								'designation'  => $row['designation'],
								'org_name' 	   => $org_Id,
								'email' 	   => $cemail_val,
								'phone' 	   => $cphone_no

							   );

			$contactdata = array_merge($cntct_data,$sesio);

	        if($row['oldcontact_id'] !=""){
			   	$con_Id = $row['oldcontact_id'];
	            $this->Model_admin_login->update_user($pri_cnt,$table_cntct,$con_Id,$cntct_data);
			 }else{  
			 	if($row['contact_name'] != ""){
			    $this->Model_admin_login->insert_user($table_cntct,$contactdata);
			    $con_Id = $this->db->insert_id();
			    $this->db->query("update tbl_organization set contact_id='$con_Id' where org_id='$org_Id' ");
				$this->software_log_insert($con_Id,'Contact','New','Contact Create','','','Contact Created');
			  }
			}

			//$this->orgJsonResponse(200,"Organization Created Successfully !",NULL);


}


function delete_organization()
{

	$id=$this->input->get('org_id');
	$orgd=$this->db->query("select * from tbl_organization where org_id='$id'");
	$countOrg=$orgd->num_rows();
	
	if($countOrg > 0 && $id != '')
	{
		
		$this->db->query("delete from tbl_organization where org_id='".$id."' ");

		$this->db->query("delete from tbl_file where file_type='Orgz' AND file_logid='$id' ");
		//$this->db->query("delete from tbl_mulit_orgz where morg_type='Organization' AND orgid='$id' ");
		$this->db->query("delete from tbl_note where note_type='Orgz' AND note_logid='$id' ");
		$this->db->query("delete from tbl_software_log where mdl_name='Orgz' AND slog_id='$id'");
		
		//================org list=================


		$org=$this->db->query("select * from tbl_organization where status='A' ORDER BY org_id DESC");
		$count=$org->num_rows();

		$org_array=array();
		foreach ($org->result() as $getOrgz) 
		{

			if($getOrgz->country != '')
			{
				$cntry=$this->db->query("select * from tbl_country_m where contryid='$getOrgz->country' ");
				$getCntry=$cntry->row();
				$cntryname=$getCntry->countryName;
			}
			else
			{
				$cntryname='';			
			}
			
			if($getOrgz->state_id != '')
			{
				$state=$this->db->query("select * from tbl_state_m where stateid='$getOrgz->state_id'");
				$getState=$state->row();
				$statename=$getState->stateName;	
			}
			else
			{
				$statename='';
			}
			

			$cnts=$this->db->query("select * from tbl_contact_m where contact_id='$getOrgz->contact_id' ");
			$getCnts=$cnts->row();
		
			$data_org=array(
						
							'org_id' 		=>$getOrgz->org_id,
							'org_name' 		=>$getOrgz->org_name,
							'org_website'  	=>$getOrgz->website,
							'org_email' 	=>$getOrgz->email,
							'org_phone' 	=>$getOrgz->phone_no,
							'org_address' 	=>$getOrgz->address,
							'org_city' 		=>$getOrgz->city,
							'state_id' 		=>$getOrgz->state_id,
							'state_name' 	=>$statename,
							'pin_code' 		=>$getOrgz->pin_code,
							'country_id' 	=>$getOrgz->country,
							'country_name' 	=>$cntryname,
							'org_desc' 		=>$getOrgz->description,
							'contact_id' 	=>$getOrgz->contact_id,
							'contact_name' 	=>$getCnts->contact_name,
							'designation' 	=>$getCnts->designation,
							'contact_email' =>$getCnts->email,
							'contact_phone' =>$getCnts->phone,
						
						);

			array_push($org_array, $data_org);

		}	

		//====================end===================

		$this->orgJsonResponse(200,"Organization Deleted Successfully !",$org_array);
	
	}
	else
	{
		$this->orgJsonResponse(400,"Organization Not Found !",NULL);		
	}
	
	//redirect('organization/Organization/manage_organization');

}


function organization_details()
{

	$orgid=$this->input->get('org_id');
	$orgz=$this->db->query("select * from tbl_organization where org_id='$orgid' ");
	$getOrgz=$orgz->row();
	$count=$orgz->num_rows();

	if($count > 0)
	{

		if($getOrgz->country != '')
			{
				$cntry=$this->db->query("select * from tbl_country_m where contryid='$getOrgz->country' ");
				$getCntry=$cntry->row();
				$cntryname=$getCntry->countryName;
			}
			else
			{
				$cntryname='';			
			}
			
			if($getOrgz->state_id != '')
			{
				$state=$this->db->query("select * from tbl_state_m where stateid='$getOrgz->state_id'");
				$getState=$state->row();
				$statename=$getState->stateName;	
			}
			else
			{
				$statename='';
			}
			

			$cnts=$this->db->query("select * from tbl_contact_m where contact_id='$getOrgz->contact_id' ");
			$getCnts=$cnts->row();
		
			$data_org['details']=array(
						
							'org_id' 		=>$getOrgz->org_id,
							'org_name' 		=>$getOrgz->org_name,
							'org_website'  	=>$getOrgz->website,
							//'org_email' 	=>$getOrgz->email,
							//'org_phone' 	=>$getOrgz->phone_no,
							'org_address' 	=>$getOrgz->address,
							'org_city' 		=>$getOrgz->city,
							//'state_id' 		=>$getOrgz->state_id,
							'state_name' 	=>$statename,
							'pin_code' 		=>$getOrgz->pin_code,
							//'country_id' 	=>$getOrgz->country,
							'country_name' 	=>$cntryname,
							'org_desc' 		=>$getOrgz->description,
							//'contact_id' 	=>$getOrgz->contact_id,
							'contact_name' 	=>$getCnts->contact_name,
							'designation' 	=>$getCnts->designation,
							'contact_email' =>$getCnts->email,
							'contact_phone' =>$getCnts->phone,
						
						);

		//===============+Related=========================		
		
		$cntnm=$this->db->query("select * from tbl_contact_m where org_name='$orgid'");
		$getNmCnt=$cntnm->row();

		$count_cnt=$cntnm->num_rows();

		if($count_cnt > 0)
		{
			
			$json_cphone=json_decode($getNmCnt->phone,true);
			$json_cemail=json_decode($getNmCnt->email,true);

			$data2=array(

				'contact_name' 	   	=> $getNmCnt->contact_name,				
				'contact_phone'  	=> $json_cphone[0],
				'contact_email'	  	=> $json_cemail[0],
				'contact_city'  	=> $getNmCnt->city_name,
			    'contact_pincode'   => $getNmCnt->pincode,

				   );
		
		}
		else
		{

			$data2=array(

				'contact_name' 	   	=> '',				
				'contact_phone'  	=> '',
				'contact_email'	  	=> '',
				'contact_city'  	=> '',
			    'contact_pincode'   => '',

				   );

		}

		
		
		$bb['contact']=$data2;


		$tlead=$this->db->query("select * from tbl_leads where org_id='$orgid'");
		$getTlead=$tlead->row();

		$count_lead=$tlead->num_rows();

		
		if($count_lead > 0)
		{
			$cnt=$this->db->query("select * from tbl_contact_m where contact_id='$getTlead->contact_id'");
			$getCname=$cnt->row();

			$org=$this->db->query("select * from tbl_organization where org_id='$getTlead->org_id'");
			$getOname=$org->row();

			$usrnm=$this->db->query("select * from tbl_user_mst where user_id='$getTlead->user_resp' ");
			$getUsrnm=$usrnm->row();

			$data3=array(

							'lead_name'    => $getTlead->lead_number,
							'assign_to'    => $getUsrnm->user_name,
							'closure_date' => $getTlead->closuredate,				
							'contact_name' => $getCname->contact_name,
							'org_name' 	   => $getOname->org_name,

						);

		}
		else
		{
			$data3=array(

							'lead_name'    => '',
							'assign_to'    => '',
							'closure_date' => '',				
							'contact_name' => '',
							'org_name' 	   => '',

						);		
		}

		$cc['lead']=$data3;

		$ltask=$this->db->query("select * from tbl_task where org_name='$orgid'");
		$data4=array();

		foreach ($ltask->result() as $getLd) 
		{

			$tname=$this->db->query("select * from tbl_master_data where serial_number='$getLd->task_name'");
			$getTname=$tname->row();

			$tstatus=$this->db->query("select * from tbl_master_data where serial_number='$getLd->task_status'");
			$getTstatus=$tstatus->row();

			$usrnm=$this->db->query("select * from tbl_user_mst where user_id='$getLd->user_resp' ");
			$getUsrnm=$usrnm->row();

			$task_data=array(

					'task_name'   => $getTname->keyvalue,
					'due_date'	  => $getLd->task_name,
					'assign_to'   => $getUsrnm->user_name,
					'description' => $getLd->description,
					'status' 	  => $getTstatus->keyvalue,
					'progress'	  => $getLd->progress_percnt,
				);

			array_push($data4,$task_data);
		}

		$dd['task']=$data4;

		$files=$this->db->query("select * from tbl_file where file_logid='$orgid' AND file_type='Orgz'");
		$count_file=$files->num_rows();

		if($count_file > 0)
		{
			$data5=array();

			foreach($files->result() as $getFiles)
			{


				$files_data=array(
						'file_name' =>$getFiles->files_name,
						'file_desc' =>$getFiles->files_desc,
					);

				array_push($data5,$files_data);
			}

			$ee['files']=$data5;
		}
		else
		{
			$ee['files']=NULL;	
		}

		$remarks=$this->db->query("select * from tbl_note where note_type='Orgz' AND note_logid='$orgid'");
		$count_remarks=$remarks->num_rows();

		if($count_remarks > 0)
		{

			$data6=array();

			foreach($remarks->result() as $getRemarks)
			{	

				$remarks_data=array(

					'remarks_date' =>$getRemarks->note_date,
					'remarks_desc' =>$getRemarks->note_desc,

				);

				array_push($data6,$remarks_data);

			}

			$ff['remarks']=$data6;
		}
		else
		{
			$ff['remarks']=NULL;	
		}

		$data_related['related']=array_merge($bb,$cc,$dd,$ee,$ff);

		///===============All Activity Task=======================

		$leadDtl=$this->db->query("select * from tbl_organization where org_id='$orgid' ");	
		$getDtl=$leadDtl->row();

		$abc = $this->db->query("select * from tbl_user_mst where user_id='".$getDtl->maker_id."'");
		$xyz = $abc->row();


		$SftLog1 = $this->db->query("select * from tbl_software_log where slog_id='".$orgid."' AND mdl_name='Orgz' AND slog_name='Note' AND slog_type='Orgz Notes' ORDER BY sid DESC ");		

		$all_log=array();
		foreach($SftLog1->result() as $getSftLog1)
		{

			$RespUsr=$this->db->query("select * from tbl_user_mst where user_id='".$getSftLog1->maker_id."'");
			$getRespUsr=$RespUsr->row();

			$data0=array(
							
							'activity_name' => $getSftLog1->slog_type,
							'remarks'       => $getSftLog1->remarks,

							'maker_date'    => $getSftLog1->maker_date,
							'created_by'    => $getRespUsr->user_name,

							'org_created_by' => $xyz->user_name,
							'org_created_on' => $getDtl->maker_date,

						  );

			array_push($all_log,$data0);
		}

		$data_all_activity['activity']=$all_log;

		$data=array_merge($data_org,$data_related,$data_all_activity);
		$this->orgJsonResponse(200,"Organization Details!",$data);

	}
	else
	{
		$this->orgJsonResponse(400,"Organization Not Found !",NULL);
	}

}

function insert_org_remarks()
{


	$json  = file_get_contents('php://input');	
	$book  = json_decode($json,true);
	//print_r($json);die;
	$row = json_decode(json_encode($book), true);

	$table_name = 'tbl_note';
	$pri_col    = 'note_id';

	$id = $row['noteid'];
	//echo  $row['orgzid'];

	$this->load->model('Model_admin_login');

	$data = array(
						'note_logid'  => $row['orgzid'],
						'note_type'	  => 'Orgz',	
						//'note_name' => $row['note_name'],
						//'note_date' => $row['note_date'],
						'note_desc'   => $row['note_desc']
					   
					  );
	$sesio = array(

						//'comp_id'   => $row['comp_id'],
						'brnh_id' 	  => $row['brnh_id'],
						'maker_id' 	  => $row['user_id'],
						'author_id'   => $row['user_id'],
						'maker_date'  => date('y-m-d'),
						'author_date' => date('y-m-d')
				    );
		
		
		if($id != '')
		{
			$this->Model_admin_login->update_user($pri_col,$table_name,$id,$data);
		  	//echo 2;	
		  	$this->orgJsonResponse(200,"Organization Remarks Updated Successfully !");
		}
		else
		{	
			$data_merger = array_merge($data,$sesio);
			$this->Model_admin_login->insert_user($table_name,$data_merger);
	    	//echo 1;
	    	$this->orgJsonResponse(200,"Organization Remarks Added Successfully !");
		}

		////-===================Software Log======================

		$orgzid = $row['orgzid'];
	
	    if($orgzid != '' & $id == '')
		{	
			
			$note_desc = $row['note_desc'];			
			$this->software_log_insert($orgzid,'Orgz','Note','Orgz Notes','','',$note_desc);

		}



}

function orgJsonResponse($status,$status_message,$data)
{
	header('Content-type: application/json');
	header('Accept: application/vnd.ritco.v1');

	$response['status']=$status;
	$response['status_message']=$status_message;
	$response['data']=$data;
	$json_response = json_encode($response);
	echo $json_response;
}


function get_contact_list()
{

	$data=$this->db->query("select * from tbl_contact_m where status='A' ORDER BY contact_id DESC");
	$count_data=$data->num_rows();

	$contact_array=array();
	foreach($data->result() as $getData)
	{

		if($getData->country != '')
		{
			$cntry=$this->db->query("select * from tbl_country_m where contryid='$getData->country' ");
			$getCntry=$cntry->row();
			$cntryname=$getCntry->countryName;
		}
		else
		{
			$cntryname='';			
		}
		
		if($getData->state_id != '')
		{
			$state=$this->db->query("select * from tbl_state_m where stateid='$getData->state_id'");
			$getState=$state->row();
			$statename=$getState->stateName;	
		}
		else
		{
			$statename='';
		}

	$org=$this->db->query("select * from tbl_organization where org_id='$getData->org_name' ");
	$getOrg=$org->row();

	$data_contact=array(

					'contact_id' 	=> $getData->contact_id,
					'contact_name'  => $getData->contact_name,
					'designation' 	=> $getData->designation,
					'contact_email' => $getData->email,
					'contact_phone'	=> $getData->phone,
					'address'		=> $getData->address,
					'city'			=> $getData->city_name,
					'state_id'		=> $getData->state_id,
					'state_name'	=> $statename,
					'pin_code'		=> $getData->pincode,
					'country_id'	=> $getData->country,
					'country_name'	=> $cntryname,

					'org_id'		=> $getData->org_name,
					'website'		=> $getOrg->org_name,
					'org_email'		=> $getOrg->email,
					'org_phone'		=> $getOrg->phone_no,

					);

		array_push($contact_array,$data_contact);

	}

	if($count_data > 0)
	{
		$this->contactJsonResponse(200,"Contact List",$contact_array);
	}
	else
	{
		$this->contactJsonResponse(400,"Contact List",NULL);	
	}

}


function post_contact()
{

		$json = file_get_contents('php://input');	
		$book = json_decode($json,true);
		//print_r($json);die;
		$row = json_decode(json_encode($book), true);

		$table_cnt = 'tbl_contact_m';
		$pri_cnt   = 'contact_id';
		$table_org = 'tbl_organization';
		$pri_org   = 'org_id';

		//===============Contact=====================
	 	
		$email_val = json_encode($row['email_id'],true);
        $phone_no  = json_encode($row['phone_no'],true);

		$data_cnt = array(
							'contact_name' => $row['contact_name'],
							'designation'  => $row['designation'],		       
							'email'        => $email_val,
							'phone'        => $phone_no,
							'address'      => $row['address'],
							'city_name'    => $row['city'],
							'state_id'     => $row['state_id'],
							'pincode'      => $row['pin_code'],
							'country'      => $row['country_id'],
							'description'  => $row['summernote']
					     );

	    $sesio = array(
						//'comp_id' => $this->session->userdata('comp_id'),
						'brnh_id' 	  => $row['brnh_id'],
						'maker_id' 	  => $row['user_id'],
						'author_id'   => $row['user_id'],
						'maker_date'  => date('y-m-d'),
						'author_date' => date('y-m-d')
					 );
		
		
		
		// echo '<pre>';
		// print_r($_POST);
		// echo '</pre>';die;
		$data_cntcnt = array_merge($data_cnt,$sesio);

	    if($row['oldcontact_id'] !="")
	    {
		   	$con_Id = $row['oldcontact_id'];
            $this->Model_admin_login->update_user($pri_cnt,$table_cnt,$con_Id,$data_cnt);
			echo 2;
			$this->contactJsonResponse(200,"Contact Created Successfully !",NULL);
		}	
		else
		{							
			$this->Model_admin_login->insert_user($table_cnt,$data_cntcnt);
			$con_Id = $this->db->insert_id();
			$this->software_log_insert($con_Id,'Contact','New','Contact Create','','','Contact Created');			
			echo 1; 	
			$this->contactJsonResponse(200,"Contact Updated Successfully !",NULL);
		}

		//====================Organization=================

			$oemail_val = json_encode($row['oemail_id'],true);
			$ophone_no  = json_encode($row['ophone_no'],true);

			$org_data = array(

								'org_name'   => $row['org_name'],
								'website'    => $row['website'],
								'contact_id' => $con_Id,
								'email'      => $oemail_val,
								'phone_no'   => $ophone_no

							   );

			$data_orgz = array_merge($org_data,$sesio);

	        if($row['oldorgid'] != ""){
			   	$org_Id = $row['oldorgid'];
	            $this->Model_admin_login->update_user($pri_org,$table_org,$org_Id,$org_data);
			 }else{  
			 	if($row['org_name'] != "")
			 	{
				 	$this->Model_admin_login->insert_user($table_org,$data_orgz);
				    $org_Id = $this->db->insert_id();
				    $this->db->query("update tbl_contact_m set org_name='$org_Id' where contact_id='$con_Id' ");
					$this->software_log_insert($org_Id,'Orgz','New','Orgz Create','','','Organization Created');
			 	}			    
			}

		//$this->contactJsonResponse(200,"Contact Created Successfully !",NULL);

}


function delete_contact()
{
	
	$id=$this->input->get('contact_id');
	$cntct=$this->db->query("select * from tbl_contact_m where contact_id='$id'");
	$count_cntct=$cntct->num_rows();

	if($count_cntct > 0 && $id != '')
	{
		
		$this->db->query("delete from tbl_contact_m where contact_id='".$id."' ");
		$this->db->query("delete from tbl_file where file_type='Contact' AND file_logid='$id' ");
		//$this->db->query("delete from tbl_mulit_orgz where morg_type='Contact' AND orgid='$id' ");
		$this->db->query("delete from tbl_note where note_type='Contact' AND note_logid='$id' ");
		$this->db->query("delete from tbl_software_log where mdl_name='Contact' AND slog_id='$id' ");

	//==================get contact list===================
	$data=$this->db->query("select * from tbl_contact_m where status='A' ORDER BY contact_id DESC");
	$count_data=$data->num_rows();

	$contact_array=array();
	foreach($data->result() as $getData)
	{

		if($getData->country != '')
		{
			$cntry=$this->db->query("select * from tbl_country_m where contryid='$getData->country' ");
			$getCntry=$cntry->row();
			$cntryname=$getCntry->countryName;
		}
		else
		{
			$cntryname='';			
		}
		
		if($getData->state_id != '')
		{
			$state=$this->db->query("select * from tbl_state_m where stateid='$getData->state_id'");
			$getState=$state->row();
			$statename=$getState->stateName;	
		}
		else
		{
			$statename='';
		}

		$org=$this->db->query("select * from tbl_organization where org_id='$getData->org_name' ");
		$getOrg=$org->row();

		$data_contact=array(

						'contact_id' 	=> $getData->contact_id,
						'contact_name'  => $getData->contact_name,
						'designation' 	=> $getData->designation,
						'contact_email' => $getData->email,
						'contact_phone'	=> $getData->phone,
						'address'		=> $getData->address,
						'city'			=> $getData->city_name,
						'state_id'		=> $getData->state_id,
						'state_name'	=> $statename,
						'pin_code'		=> $getData->pincode,
						'country_id'	=> $getData->country,
						'country_name'	=> $cntryname,

						'org_id'		=> $getData->org_name,
						'website'		=> $getOrg->org_name,
						'org_email'		=> $getOrg->email,
						'org_phone'		=> $getOrg->phone_no,

						);

				array_push($contact_array,$data_contact);

		}

		//====================end====================

		$this->contactJsonResponse(200,"Contact Deleted Successfully !",$contact_array);

	}
	else
	{
		$this->contactJsonResponse(400,"Contact Not Found !",NULL);		
	}
}


function contact_details()
{

	$contid=$this->input->get('contact_id');
	$cnt=$this->db->query("select * from tbl_contact_m where contact_id='$contid' ");
	$getCnt=$cnt->row();
	$count=$cnt->num_rows();

	if($count > 0)
	{

		if($getCnt->country != '')
			{
				$cntry=$this->db->query("select * from tbl_country_m where contryid='$getCnt->country' ");
				$getCntry=$cntry->row();
				$cntryname=$getCntry->countryName;
			}
			else
			{
				$cntryname='';			
			}
			
			if($getCnt->state_id != '')
			{
				$state=$this->db->query("select * from tbl_state_m where stateid='$getCnt->state_id'");
				$getState=$state->row();
				$statename=$getState->stateName;	
			}
			else
			{
				$statename='';
			}
			

			$orgs=$this->db->query("select * from tbl_organization where org_id='$getCnt->org_name' ");
			$getOrgs=$orgs->row();
		
			$data_cnt['details']=array(
						
							'contact_id' 	=>$getCnt->contact_id,
							'contact_name' 	=>$getCnt->contact_name,
							'designation' 	=>$getCnt->designation,
							'contact_email' =>$getCnt->email,
							'contact_phone' =>$getCnt->phone,								
							'cnt_address' 	=>$getCnt->address,
							'cont_city' 	=>$getCnt->city,
							//'state_id' 	=>$getOrgz->state_id,
							'state_name' 	=>$statename,
							'pin_code' 		=>$getCnt->pin_code,
							//'country_id' 	=>$getOrgz->country,
							'country_name' 	=>$cntryname,
							'cntct_desc' 	=>$getCnt->description,

							'org_name' 		=>$getOrgs->org_name,
							'org_website'  	=>$getOrgs->website,
							'org_email' 	=>$getOrgs->email,
							'org_phone' 	=>$getOrgs->phone_no,
							
						
						);

		//===============+Related=========================		
		
		$org=$this->db->query("select * from tbl_organization where org_id='$getCnt->org_name' ");
		$getOrg=$org->row();
		$countorg=$org->num_rows();

		if($countorg > 0)
		{
			
			$json_phone=json_decode($getOrg->phone_no,true);
			$json_email=json_decode($getOrg->email,true);

			$data1=array(

						
						'Org_name'		=> $getOrg->org_name,
						'org_phone' 	=> $json_phone[0],	
						'org_email'		=> $json_email[0],
						'org_city' 		=> $getOrg->city,
						'org_pincode'  	=> $getOrg->pin_code,


					   );	
		}
		else
		{
			$data1=array(

						
						'Org_name'		=> '',
						'org_phone' 	=> '',	
						'org_email'		=> '',
						'org_city' 		=> '',
						'org_pincode'  	=> '',


					   );	
		}
		

		$aa['organization']=$data1;


		$tlead=$this->db->query("select * from tbl_leads where contact_id='$contid'");
		$getTlead=$tlead->row();

		$count_lead=$tlead->num_rows();

		
		if($count_lead > 0)
		{
			$cnt=$this->db->query("select * from tbl_contact_m where contact_id='$getTlead->contact_id'");
			$getCname=$cnt->row();

			$org=$this->db->query("select * from tbl_organization where org_id='$getTlead->org_id'");
			$getOname=$org->row();

			$usrnm=$this->db->query("select * from tbl_user_mst where user_id='$getTlead->user_resp' ");
			$getUsrnm=$usrnm->row();

			$data3=array(

							'lead_name'    => $getTlead->lead_number,
							'assign_to'    => $getUsrnm->user_name,
							'closure_date' => $getTlead->closuredate,				
							'contact_name' => $getCname->contact_name,
							'org_name' 	   => $getOname->org_name,

						);

		}
		else
		{
			$data3=array(

							'lead_name'    => '',
							'assign_to'    => '',
							'closure_date' => '',				
							'contact_name' => '',
							'org_name' 	   => '',

						);		
		}

		$cc['lead']=$data3;

		$ltask=$this->db->query("select * from tbl_task where contact_person='$contid'");
		$data4=array();

		foreach ($ltask->result() as $getLd) 
		{

			$tname=$this->db->query("select * from tbl_master_data where serial_number='$getLd->task_name'");
			$getTname=$tname->row();

			$tstatus=$this->db->query("select * from tbl_master_data where serial_number='$getLd->task_status'");
			$getTstatus=$tstatus->row();

			$usrnm=$this->db->query("select * from tbl_user_mst where user_id='$getLd->user_resp' ");
			$getUsrnm=$usrnm->row();

			$task_data=array(

					'task_name'   => $getTname->keyvalue,
					'due_date'	  => $getLd->task_name,
					'assign_to'   => $getUsrnm->user_name,
					'description' => $getLd->description,
					'status' 	  => $getTstatus->keyvalue,
					'progress'	  => $getLd->progress_percnt,
				);

			array_push($data4,$task_data);
		}

		$dd['task']=$data4;

		$files=$this->db->query("select * from tbl_file where file_logid='$contid' AND file_type='Contact'");
		$count_file=$files->num_rows();

		if($count_file > 0)
		{
			$data5=array();

			foreach($files->result() as $getFiles)
			{


				$files_data=array(
						'file_name' =>$getFiles->files_name,
						'file_desc' =>$getFiles->files_desc,
					);

				array_push($data5,$files_data);
			}

			$ee['files']=$data5;
		}
		else
		{
			$ee['files']=NULL;	
		}

		$remarks=$this->db->query("select * from tbl_note where note_type='Contact' AND note_logid='$contid'");
		$count_remarks=$remarks->num_rows();

		if($count_remarks > 0)
		{

			$data6=array();

			foreach($remarks->result() as $getRemarks)
			{	

				$remarks_data=array(

					'remarks_date' =>$getRemarks->note_date,
					'remarks_desc' =>$getRemarks->note_desc,

				);

				array_push($data6,$remarks_data);

			}

			$ff['remarks']=$data6;
		}
		else
		{
			$ff['remarks']=NULL;	
		}

		$data_related['related']=array_merge($aa,$cc,$dd,$ee,$ff);

		///===============All Activity Task=======================

		$cntDtl=$this->db->query("select * from tbl_contact_m where contact_id='$contid' ");	
		$getDtl=$cntDtl->row();

		$abc = $this->db->query("select * from tbl_user_mst where user_id='".$getDtl->maker_id."'");
		$xyz = $abc->row();


		$SftLog1 = $this->db->query("select * from tbl_software_log where slog_id='".$contid."' AND mdl_name='Contact' AND slog_name='Note' AND slog_type='Contact Notes' ORDER BY sid DESC ");		

		$all_log=array();
		foreach($SftLog1->result() as $getSftLog1)
		{

			$RespUsr=$this->db->query("select * from tbl_user_mst where user_id='".$getSftLog1->maker_id."'");
			$getRespUsr=$RespUsr->row();

			$data0=array(
							
							'activity_name' => $getSftLog1->slog_type,
							'remarks'       => $getSftLog1->remarks,

							'maker_date'    => $getSftLog1->maker_date,
							'created_by'    => $getRespUsr->user_name,

							'org_created_by' => $xyz->user_name,
							'org_created_on' => $getDtl->maker_date,

						  );

			array_push($all_log,$data0);
		}

		$data_all_activity['activity']=$all_log;

		$data=array_merge($data_cnt,$data_related,$data_all_activity);
		$this->orgJsonResponse(200,"Contact Details!",$data);

	}
	else
	{
		$this->orgJsonResponse(400,"Contact Not Found !",NULL);
	}

}


function insert_contact_remarks()
{


	$json  = file_get_contents('php://input');	
	$book  = json_decode($json,true);
	//print_r($json);die;
	$row = json_decode(json_encode($book), true);

	$table_name = 'tbl_note';
	$pri_col    = 'note_id';

	$id = $row['noteid'];
	//echo $row['cntctid'];

	$this->load->model('Model_admin_login');

	$data = array(
						'note_logid'  => $row['cntctid'],
						'note_type'	  => 'Contact',	
						//'note_name' => $row['note_name'],
						//'note_date' => $row['note_date'],
						'note_desc'   => $row['note_desc']
					   
					  );
	$sesio = array(

						//'comp_id'     => $row['comp_id'],
						'brnh_id'     => $row['brnh_id'],
						'maker_id'    => $row['user_id'],
						'author_id'   => $row['user_id'],
						'maker_date'  => date('y-m-d'),
						'author_date' => date('y-m-d')
				    );
		
		
		if($id != '')
		{
			$this->Model_admin_login->update_user($pri_col,$table_name,$id,$data);
		  	//echo 2;
		  	$this->contactJsonResponse(200,"Contact Remarks Updated Successfully !",NULL);	
		}
		else
		{	
			$data_merger = array_merge($data,$sesio);
			$this->Model_admin_login->insert_user($table_name,$data_merger);
	    	//echo 1;
	    	$this->contactJsonResponse(200,"Contact Remarks Added Successfully !",NULL);	
		}

		////-===================Software Log======================

		$cntctid = $row['cntctid'];
	
	    if($cntctid != '' & $id == '')
		{	
			
			$note_desc = $row['note_desc'];			
			$this->software_log_insert($cntctid,'Contact','Note','Contact Notes','','',$note_desc);
		}



}


function contactJsonResponse($status,$status_message,$data)
{
	header('Content-type: application/json');
	header('Accept: application/vnd.ritco.v1');

	$response['status']=$status;
	$response['status_message']=$status_message;
	$response['data']=$data;
	$json_response = json_encode($response);
	echo $json_response;
}


}  ?>