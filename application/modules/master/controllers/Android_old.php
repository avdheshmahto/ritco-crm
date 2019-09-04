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
				'lead_id'		=> $value->lead_id,
				'firstCharacter'=> $firstCharacter,
				'lead_name'  	=> $value->lead_number,
				'lead_owner' 	=> $getOwner->user_name,
				'expected_closure_date'  => $value->closuredate,
				'long_description' => $bigDesc,
				'short_description' => $shortDesc,
				'lead_status'  => $getStatus1->keyvalue,
				'lead_stage'  => $getStage->keyvalue,
				'assign_to'   => $getUser->user_name."(".$getBrnh->brnh_name.")",
				'organization'=> $getOrg->org_name,
				'contact'     => $getCnt->contact_name,
				'email_id'    => $email_val,
				'phone_no'    => $phone_val,
				'address'     => $getCnt->address,
				'industry'    => $getInds->keyvalue,
				'opp_value'   => $value->opp_value,
				'description' => $value->discription,

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
		$this->leadJsonResponse(400,"Lead Map With Task ! You Can't Delete !",NULL);
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
					'lead_id'		=> $value->lead_id,
					'firstCharacter'=> $firstCharacter,
					'lead_name'  	=> $value->lead_number,
					'lead_owner' 	=> $getOwner->user_name,
					'expected_closure_date'  => $value->closuredate,
					'long_description' => $bigDesc,
					'short_description' => $shortDesc,
					'lead_status'  => $getStatus1->keyvalue,
					'lead_stage'  => $getStage->keyvalue,
					'assign_to'   => $getUser->user_name."(".$getBrnh->brnh_name.")",
					'organization'=> $getOrg->org_name,
					'contact'     => $getCnt->contact_name,
					'email_id'    => $email_val,
					'phone_no'    => $phone_val,
					'address'     => $getCnt->address,
					'industry'    => $getInds->keyvalue,
					'opp_value'   => $value->opp_value,
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


function lead_details()
{

	$leadid = $this->input->get('lead_id');
	//======================Lead List=====================

		$lead=$this->db->query("select * from tbl_leads where lead_id='$leadid' ");
		$value = $lead->row();
		// $leadList = array();
		// foreach ($lead->result() as $value) 
		// {

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

		    //$data3['stage']=$master_stage;


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

		   // $data3['status']=$master_status;
			
			//===================End======================

		    $sqlgroup1=$this->db->query("select * from tbl_user_mst where user_id='$value->maker_id' ");
		    $getOwner = $sqlgroup1->row();


			$status1=$this->db->query("select * from tbl_master_data where serial_number='$value->lead_state'");
		  	$getStatus1 = $status1->row();

		  	$stg = $this->db->query("select * from tbl_master_data where serial_number='$value->stage' ");
	  		$getStage = $stg->row();

	  		$inds = $this->db->query("select * from tbl_master_data where serial_number='$value->industry' ");
	  		$getInds = $inds->row();

	  		$sqlgroup=$this->db->query("select * from tbl_user_mst where user_id='$value->user_resp' ");
			$getUser = $sqlgroup->row();
	 		
	 		// 	$brnh=$this->db->query("select * from tbl_branch_mst where brnh_id='$getUser->brnh_id' ");
			// $getBrnh=$brnh->row();
			// ."(".$getBrnh->brnh_name.")"

	 		$cnt=$this->db->query("select * from tbl_contact_m where contact_id='$value->contact_id'");
	 		$getCnt=$cnt->row();

	 		$email_val=json_decode($getCnt->email);
	 		$phone_val=json_decode($getCnt->phone);

	 		$org=$this->db->query("select * from tbl_organization where org_id='$value->org_id' ");
	 		$getOrg=$org->row();

			$mkrdt=$value->maker_date;
			$crdate=date("Y-m-d");
			$earlier = new DateTime($crdate);
			$later = new DateTime($mkrdt);

			$diff = $later->diff($earlier)->format("%a");
			$leadage = $diff." days";


	$tskDes = $this->db->query("select * from tbl_note where note_logid='".$value->lead_id."' AND (main_lead_id_note='main_lead' OR main_lead_id_note='main_task' OR main_lead_id_note='Inner Lead') AND (note_type='Lead' OR note_type='Task') ORDER BY note_id DESC ");
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

			$datal = array(
					'lead_id'		=> $value->lead_id,
					//'firstCharacter'=> $firstCharacter,
					'lead_name'  	=> $value->lead_number,
					'lead_owner' 	=> $getOwner->user_name,
					'expected_closure_date'  => $value->closuredate,
					//'long_description' => $bigDesc,
					//'short_description' => $shortDesc,
					'lead_status'  => $getStatus1->keyvalue,
					'docket_no'    => $value->docket_no,

					'lead_stage'  => $getStage->keyvalue,
					'assign_to'   => $getUser->user_name,
					'organization'=> $getOrg->org_name,
					'contact'     => $getCnt->contact_name,
					'email_id'    => $email_val,
					'phone_no'    => $phone_val,
					'address'     => $getCnt->address,
					'industry'    => $getInds->keyvalue,
					'opp_value'   => $value->opp_value,
					'description' => $desc_list,
					'leadage'     => $leadage,
					'maker_date'  => $value->maker_date,
					'stage'		  => $master_stage, 
					'status'	  => $master_status,		

					);
			//array_push($leadList,$datal);
		//}

		$this->leadJsonResponse(200,"Lead Details !",$datal);
		//=========================End========================

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


function lead_all_log__0()
{

	$log_leadid=$this->input->get('lead_id');
	
	$leadDtl=$this->db->query("select * from tbl_leads where lead_id='$log_leadid' ");	
	$getDtl=$leadDtl->row();

	$abc = $this->db->query("select * from tbl_user_mst where user_id='".$getDtl->maker_id."'");
	$xyz = $abc->row();

	$data0['lead_created_by']=$xyz->user_name;
	$data0['lead_created_on']=$getDtl->maker_date;

	$SftLog = $this->db->query("select * from tbl_software_log where slog_id='".$log_leadid."' AND mdl_name='Lead' AND slog_name='Task' ORDER BY sid DESC ");

	$task=array();
	foreach ($SftLog->result() as $getSftLog) 
	{  


		$tskname = $getSftLog->slog_type;
		$maker_date = $getSftLog->maker_date;
		
		$asnUsr = $this->db->query("select * from tbl_user_mst where user_id='".$getSftLog->new_id."'");
		$getAsnusr = $asnUsr->row();
		$AsinName = $getAsnusr->user_name;

		$makr = $this->db->query("select * from tbl_user_mst where user_id='".$getSftLog->old_id."'");
		$getMakr = $makr->row();
		$makrNm = $getMakr->user_name;

		$remarks = $getSftLog->remarks;

		$taskData=array(

					'task_name'   => $tskname,
					'maker_date'  => $maker_date,
					'assign_to'   => $AsinName,
					'created_by'  => $makrNm,
					'remarks'     => $remarks,

					);		
		//}

		array_push($task,$taskData);
	}

	$data1['Task']=$task;

	$SftLog1 = $this->db->query("select * from tbl_software_log where slog_id='".$log_leadid."' AND mdl_name='Lead' AND slog_name='Lead' AND slog_type='User' ORDER BY sid DESC ");

	$user=array();
	foreach ($SftLog1->result() as $getSftLog1) 
	{ 


		$RespUsr = $this->db->query("select * from tbl_user_mst where user_id='".$getSftLog1->maker_id."'");
		$getRespUsr = $RespUsr->row();
		$resp_usr_name = $getRespUsr->user_name;

		$oldusr = $this->db->query("select * from tbl_user_mst where user_id='".$getSftLog1->old_id."' ");
		$getOldUsr = $oldusr->row();
		$oldUsrName = $getOldUsr->user_name;

		$newUsr = $this->db->query("select * from tbl_user_mst where user_id='".$getSftLog1->new_id."' ");
		$getNewUsr = $newUsr->row();
		$newUsrName = $getNewUsr->user_name;
		
		$userData=array(

									'old_username' => $oldUsrName,
									'new_username' => $newUsrName,
									'maker_date'   => $getSftLog1->maker_date,
									'created_by'   => $resp_usr_name,

								    );

		array_push($user,$userData);
	
	}

	$data2['User']=$user;

	$SftLog2 = $this->db->query("select * from tbl_software_log where slog_id='".$log_leadid."' AND mdl_name='Lead' AND slog_name='Lead' AND slog_type='Stage' ORDER BY sid DESC ");

	$stage=array();
	foreach ($SftLog2->result() as $getSftLog2) 
	{ 


		$RespUsr = $this->db->query("select * from tbl_user_mst where user_id='".$getSftLog2->maker_id."'");
		$getRespUsr = $RespUsr->row();
		$resp_usr_name = $getRespUsr->user_name;

		$oldkey = $this->db->query("select * from tbl_master_data where serial_number='".$getSftLog2->old_id."' ");
		$getOldKey = $oldkey->row();
		$oldKeyName = $getOldKey->keyvalue;

		$newkey = $this->db->query("select * from tbl_master_data where serial_number='".$getSftLog2->new_id."' ");
		$getNewKey = $newkey->row();
		$newKeyName = $getNewKey->keyvalue;	
		
		$stageData=array(

									'old_keyname'  => $oldKeyName,
									'new_keyname'  => $newKeyName,
									'maker_date'   => $getSftLog2->maker_date,
									'created_by'   => $resp_usr_name,

								    );

		array_push($stage,$stageData);
	
	}

	$data3['Stage']=$stage;

	$SftLog3 = $this->db->query("select * from tbl_software_log where slog_id='".$log_leadid."' AND mdl_name='Lead' AND slog_name='Lead' AND slog_type='Status' ORDER BY sid DESC ");

	$status=array();
	foreach ($SftLog3->result() as $getSftLog3) 
	{ 


		$RespUsr = $this->db->query("select * from tbl_user_mst where user_id='".$getSftLog3->maker_id."'");
		$getRespUsr = $RespUsr->row();
		$resp_usr_name = $getRespUsr->user_name;

		$oldkey = $this->db->query("select * from tbl_master_data where serial_number='".$getSftLog3->old_id."' ");
		$getOldKey = $oldkey->row();
		$oldKeyName = $getOldKey->keyvalue;

		$newkey = $this->db->query("select * from tbl_master_data where serial_number='".$getSftLog3->new_id."' ");
		$getNewKey = $newkey->row();
		$newKeyName = $getNewKey->keyvalue;	
		
		$statusData=array(

									'old_keyname'  => $oldKeyName,
									'new_keyname'  => $newKeyName,
									'maker_date'   => $getSftLog3->maker_date,
									'created_by'   => $resp_usr_name,

								    );

		array_push($status,$statusData);
	
	}

	$data4['Status']=$status;



	$SftLog4 = $this->db->query("select * from tbl_software_log where slog_id='".$log_leadid."' AND mdl_name='Lead' AND slog_name='File' ORDER BY sid DESC ");

	$files=array();
	foreach ($SftLog4->result() as $getSftLog4) 
	{	

		$filename = $getSftLog4->slog_type;
		$file_ext = pathinfo($filename);
		$file_extn = $file_ext[extension];

		$makr = $this->db->query("select * from tbl_user_mst where user_id='".$getSftLog4->maker_id."'");
		$getMakr = $makr->row();
		$makrNm = $getMakr->user_name;

		$filesData=array(

						'file_type'   => $file_extn,
						'file_name'   => $filename,
						'maker_date'  => $getSftLog4->maker_date,
						'created_by'  => $makrNm,

					    );

		array_push($files,$filesData);

	}

	$data5['Files']=$files;


	$SftLog5 = $this->db->query("select * from tbl_software_log where slog_id='".$log_leadid."' AND mdl_name='Lead' AND slog_name='Note' AND slog_type='Lead Notes' ORDER BY sid DESC ");

	$remarks=array();
	foreach ($SftLog5->result() as $getSftLog5) 
	{	

		$notename = $getSftLog5->slog_type;

		$makr = $this->db->query("select * from tbl_user_mst where user_id='".$getSftLog5->maker_id."'");
		$getMakr = $makr->row();
		$makrNm = $getMakr->user_name;

		$remarksData=array(

						'name'        => $notename,
						'remarks'     => $getSftLog5->remarks,
						'maker_date'  => $getSftLog5->maker_date,
						'created_by'  => $makrNm,

					    );

		array_push($remarks,$remarksData);

	}

	$data6['Remarks']=$remarks;

	$data=array_merge($data0,$data1,$data2,$data3,$data4,$data5,$data6);

	$this->leadJsonResponse(200,"Lead All Activity",$data);

}




function lead_all_log()
{

	$log_leadid=$this->input->get('lead_id');
	
	$leadDtl=$this->db->query("select * from tbl_leads where lead_id='$log_leadid' ");	
	$getDtl=$leadDtl->row();

	$abc = $this->db->query("select * from tbl_user_mst where user_id='".$getDtl->maker_id."'");
	$xyz = $abc->row();

	$data0['lead_created_by']=$xyz->user_name;
	$data0['lead_created_on']=$getDtl->maker_date;	

	$SftLog1 = $this->db->query("select * from tbl_software_log where slog_id='".$log_leadid."' AND mdl_name='Lead' AND (slog_name='Lead' OR slog_name='Note' OR slog_name='Status') AND (slog_type='Lead Notes' OR slog_type='User' OR slog_type='Stage' OR slog_type='Status' OR slog_type='Won') ORDER BY sid DESC ");

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

		$logData=array(

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

		array_push($all_log,$logData);
	
	}

	$data=array_merge($data0,$all_log);

	$this->leadJsonResponse(200,"Lead All Activity",$all_log);

}

function lead_related_org()
{

	$LdId=$this->input->get('lead_id');
	$org=$this->db->query("select * from tbl_mulit_orgz where morg_logid='$LdId' AND morg_type='Organization' ");
	$count=$org->num_rows();
	foreach ($org->result() as $getOrg) 
	{

		$orgnm=$this->db->query("select * from tbl_organization where org_id='$getOrg->orgid'");
		$getNmOrg=$orgnm->row();
		$data=array(

				'Lead_id' 		=> $LdId,
				'Org_id'  		=> $getOrg->orgid,
				'Org_name'		=> $getNmOrg->org_name,
				'org_details' 	=> $getOrg->morg_details,	
				'default_org'	=> $getOrg->default_org,


				   );
	}
	
	if($count > 0)
	{
		$this->leadJsonResponse(200,"Lead Organization Related Information",$data);
	}
	else
	{
		$this->leadJsonResponse(400,"Lead Not Found",NULL);
	}
	

}

function lead_related_contact()
{


	$LdId=$this->input->get('lead_id');
	$mutli=$this->db->query("select * from tbl_mulit_orgz where morg_logid='$LdId' AND morg_type='Contact'");
	$count=$mutli->num_rows();
	foreach ($mutli->result() as $getMulti) 
	{
		
		$cntnm=$this->db->query("select * from tbl_contact_m where contact_id='$getMulti->orgid'");
		$getNmCnt=$cntnm->row();
		$data=array(

				'Lead_id' 	   => $LdId,				
				'Cnt_id'  	   => $getMulti->orgid,
				'Cnt_name'	   => $getNmCnt->contact_name,
				'cnt_details'  => $getMulti->morg_details,
			    'default_cnt'  => $getMulti->default_org,

				   );
	}

	
	if($count > 0)
	{
		$this->leadJsonResponse(200,"Lead Contact Related Information",$data);
	}
	else
	{
		$this->leadJsonResponse(400,"Lead Not Found",NULL);
	}

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






}  ?>