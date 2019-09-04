<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

/* load the MX_Router class */

class my_controller extends MX_Controller {

function __construct()
{
   parent::__construct(); 
   $this->load->model('Model_admin_login'); 
}

  function invitedUser() 
   {
             
     if($this->input->get() != ""){
 	  $confirmid = $this->input->get('confirmid');
 	  $id        = $this->input->get('id');
   	  $sql   = "select * from tbl_user_mst where confirm_email = '".$confirmid."' AND user_id = $id";
   	  $query = $this->db->query($sql)->row();
      
   	  if(sizeof($query) > 0){
   	  	$data['result'] = $query;
   	  	  // echo "<pre>";
   	      //  print_r($query);
   	      // echo "</pre>";
   	  	if($query->status == 'A'){
   	  	  $this->session->set_flashdata('error', 'Sorry this user already verified, Please Login!!');
   	  	  	redirect('index');	
   	  	}
          $this->load->view('view-inviteduser',$data);
       }

    }
   //redirect('view_inviteduser');
 }

////////////function to give permission(add,edit,delete) to users starts///////////////

/*public function user_function(){
	
	if($this->session->userdata('is_logged_in'))
	{
		
		$userRole=$this->db->query("select * from tbl_user_role_mst where user_id='".$this->session->userdata('user_id')."' ");
		$userRoleFetch=$userRole->row();
		$userRoleFetch->role_id;
		$userRole1=$this->db->query("select * from tbl_role_mst where role_id='".$userRoleFetch->role_id."'");
		$userRoleFetch1=$userRole1->row();
		$userRoleFetch1->role_id;
		$data_user=$userRoleFetch1->action;
		$action=explode("-",$data_user);
		$kk['edit']=$action[0];
		$kk['view']=$action[1];
		$kk['delete']=$action[2];
		$kk['add']=$action[3];
		$kk['obj']=new my_controller();
		
		return $kk;
   }

}*/


public function dashboard()
{

	date_default_timezone_set("Asia/Kolkata");
	
    if($this->session->userdata('is_logged_in'))
	{
		redirect('master/index');
	}

    //print_r($this->input->post());die;
	$email     = $this->input->post('username');
	$password  = $this->input->post('password');


	$userQuery = $this->db->query("SELECT * FROM tbl_user_mst where status='A' and email_id='$email' and password='$password'");

	if($this->input->post('confirmid') != "")
	{
	
	 $user_id   = $this->input->post('useridd');
	 $confirmid = $this->input->post('confirmid');
	 $newpaswrd = $this->input->post('entrpaswrd');

	 $userQuery = $this->db->query("SELECT * FROM tbl_user_mst where status = 'I' and email_id = '$email' and password = '$password' AND user_id = '$user_id' AND confirm_email = '".$confirmid."'");

    }
	$fetchUser = $userQuery->row();
	$this->session->set_flashdata('flash_msg', $fetchUser->last_login);
	$count     = $userQuery->num_rows();
   
	$roleQuery = $this->db->query("update tbl_user_mst set logged_in= 1  where   user_id='".$fetchUser->user_id."'");
	

	$sess_array = array(
		
							'user_id'     => $fetchUser->user_id,
							'is_logged_in'=> 1,
							'user_name'   => $fetchUser->user_name,
							//'user_type'   => $fetchUser->user_type,
							//'comp_id' 	  => $fetchUser->comp_id,
							'brnh_id' 	  => $fetchUser->brnh_id,
							'author_id'   => $fetchUser->author_id,
							'maker_id' 	  => $fetchUser->maker_id,
							'role' 	  	  => $fetchUser->profile_user,
							//'profile' 	  => $fetchUser->role,
							'last_login'  => $fetchUser->last_login,
							//role and profile id reverse 28-11-2018
					   	);

	if($count > 0 && $this->input->post('confirmid') != "")
	{
	 $roleQuery = $this->db->query("update tbl_user_mst set password='".$newpaswrd."',logged_in= 1,emailvarified=1,status = 'A'  where   user_id='".$fetchUser->user_id."'");

	 $this->session->set_userdata(@$sess_array);
	 //$this->session->set_flashdata('flash_msg', $fetchUser->last_login);
	 redirect('master/master/manage_dashboard');
	}
	elseif($count > 0)
	{
	$roleQuery = $this->db->query("update tbl_user_mst set  logged_in= 1,status = 'A'  where   user_id='".$fetchUser->user_id."'");

	 $this->session->set_userdata(@$sess_array);
	 //$this->session->set_flashdata('flash_msg', $fetchUser->last_login);
	 redirect('master/master/manage_dashboard');
	}
	{
	 $this->session->set_flashdata('error', 'Sorry, We did not recognize this email address or password !.');
	 redirect('index');
	}

}

public function insert_signup()
{

	@extract($_POST);
	$table_name='tbl_user_mst';
	$pri_col='user_id';
	
	//echo "abcc";die;
	
		$data = array (
						'first_name' => $this->input->post('first_name'),
						'last_name' => $this->input->post('last_name'),
						'email_id' => $this->input->post('email_id'),
						'user_name' => $this->input->post('user_name'),
						'password' => $this->input->post('password')
					  );
	
		$sesio = array(
						'comp_id' => $this->session->userdata('comp_id'),
						'divn_id' => $this->session->userdata('divn_id'),
						'zone_id' => $this->session->userdata('zone_id'),
						'brnh_id' => $this->session->userdata('brnh_id'),
						'maker_date'=> date('y-m-d'),
						'author_date'=> date('y-m-d')
					 );
					 
		$dataAll = array_merge($data,$sesio);
	
		$this->Model_admin_login->insert_user($table_name,$data);
		$this->session->set_flashdata('message', 'Sign Up Successfully ! Please Login Now.');
		redirect('index');

}


function index() 
{
	if($this->session->userdata('is_logged_in'))
	{
		redirect('master/master/manage_dashboard');
	}
	else
	{
		redirect('index');
	}
}




public function dashboar()
{
	if($this->session->userdata('is_logged_in'))
	{
		$this->load->view('dashboard');
    }
	else
	{	
		redirect('index');
	}
}

  public function ajax_checkuser(){
    $email   = $this->input->post('val');
    $sql     = "select email_id from tbl_user_mst where email_id = '".$email."' AND status = 'A'";
	$query   = $this->db->query($sql);
	//print_r($query->result_array());
	if(sizeof($query->result_array()) > 0)
		echo 1;
	else
		echo 0;

  }
  

public function logout()
{
   $user_id   = $this->session->userdata('user_id');
   $roleQuery = $this->db->query("update tbl_user_mst set logged_in= 0 where   user_id='".$user_id."'");
	$this->session->sess_destroy();
	redirect('/index');
}

public function singup()
{
	$this->load->view('signup');
}


public function forgotpage()
{
	$this->load->view('forgotpass');
}





public function get_cid() 
{
	if($this->session->userdata('is_logged_in'))
	{
		$data=$this->user_function();
		$this->load->view('get_cid',$data); 
	
	}	
	else
	{
		$this->load->view('index');
	}
}
		
		
public function error_page() 
{
	if($this->session->userdata('is_logged_in'))
	{
			$this->load->view('invalid_url');
	}
	else
	{
		$this->load->view('index');
	}
}
		
public function session_data() 
{

	$data = array(
					
					'comp_id' => $this->session->userdata('comp_id'),
					'brnh_id' => $this->session->userdata('brnh_id'),
					'maker_id' => $this->session->userdata('user_id'),
					'author_id' => $this->session->userdata('user_id'),
					'maker_date'=> date('y-m-d'),
					'author_date'=> date('y-m-d')

				);
				
	return $data;

}	
			


function parseWord($filename) 
{

    $striped_content = '';
    $content = '';

    if(!$filename || !file_exists($filename)) return false;

    $zip = zip_open($filename);

    if (!$zip || is_numeric($zip)) return false;

    while ($zip_entry = zip_read($zip)) {

        if (zip_entry_open($zip, $zip_entry) == FALSE) continue;

        if (zip_entry_name($zip_entry) != "word/document.xml") continue;

        $content .= zip_entry_read($zip_entry, zip_entry_filesize($zip_entry));

        zip_entry_close($zip_entry);
    }// end while

    zip_close($zip);

    //echo $content;
    //echo "<hr>";
    //file_put_contents('1.xml', $content);

    $content = str_replace('</w:r></w:p></w:tc><w:tc>', " ", $content);
    $content = str_replace('</w:r></w:p>', "\r\n", $content);
    $striped_content = strip_tags($content);

    return $striped_content;

} 
	
	
	


//=============================enter price=======================
 
 public function enterPriceCheck($compId){
 //echo $productId;die;
   $this->db->where('comp_id', $compId);

    $query = $this->db->get('tbl_region_mst');

    $count_row = $query->num_rows();

    if ($count_row > 0) {
      //if count row return any row; that means you have already this email address in the database. so you must set false in this sense.
	  return prodductActive;
		

       //redirect('/master/add_category');
     } else {
      // doesn't return any row means database doesn't have this email
        return TRUE; // And here false to TRUE
     }

 
 }
 
//=================================Close enter price ===============================

//=============================Start Region =======================
 
 public function regionCheck($zoneid){
 //echo $productId;die;
   $this->db->where('zone_id', $zoneid);

    $query = $this->db->get('tbl_branch_mst');

    $count_row = $query->num_rows();

    if ($count_row > 0) {
      //if count row return any row; that means you have already this email address in the database. so you must set false in this sense.
	  return prodductActive;
		

       //redirect('/master/add_category');
     } else {
      // doesn't return any row means database doesn't have this email
        return TRUE; // And here false to TRUE
     }

 
 }
 
//=================================Close Region===============================
 
 
//=============================Start Branch =======================
 
 public function branchCheck($brnhid){
 //echo $productId;die;
   $this->db->where('brnh_id', $brnhid);

    $query = $this->db->get('tbl_wing_mst');

    $count_row = $query->num_rows();

    if ($count_row > 0) {
      //if count row return any row; that means you have already this email address in the database. so you must set false in this sense.
	  return prodductActive;
		

       //redirect('/master/add_category');
     } else {
      // doesn't return any row means database doesn't have this email
        return TRUE; // And here false to TRUE
     }

 
 }
 
//=================================Close Branch===============================
 
 
//=============================Start Department =======================
 
 public function departmentCheck($divnid){
 //echo $productId;die;
   $this->db->where('divn_id', $divnid);

    $query = $this->db->get('tbl_user_mst');

    $count_row = $query->num_rows();

    if ($count_row > 0) {
      //if count row return any row; that means you have already this email address in the database. so you must set false in this sense.
	  return prodductActive;
		

       //redirect('/master/add_category');
     } else {
      // doesn't return any row means database doesn't have this email
        return TRUE; // And here false to TRUE
     }

 
 }
 
//=================================Close Department===============================
 
 
//=============================Start Role =======================
 
 public function roleCheck($roleid){
 //echo $productId;die;
   $this->db->where('role_id', $roleid);

    $query = $this->db->get('tbl_role_func_action');

    $count_row = $query->num_rows();

    if ($count_row > 0) {
      //if count row return any row; that means you have already this email address in the database. so you must set false in this sense.
	  return prodductActive;
		

       //redirect('/master/add_category');
     } else {
      // doesn't return any row means database doesn't have this email
        return TRUE; // And here false to TRUE
     }

 
 }
 
//=================================Close Role===============================
 
 
//=============================Start User =======================
 
 public function userCheck($userid){
 //echo $productId;die;
   $this->db->where('user_id', $userid);

    $query = $this->db->get('tbl_user_role_mst');

    $count_row = $query->num_rows();

    if ($count_row > 0) {
      //if count row return any row; that means you have already this email address in the database. so you must set false in this sense.
	  return prodductActive;
		

       //redirect('/master/add_category');
     } else {
      // doesn't return any row means database doesn't have this email
        return TRUE; // And here false to TRUE
     }

 
 }
 
//=================================Close User===============================
 
//================================*Start single delete data ============== 


function delete_data()
{
	$this->load->model('Model_admin_login');
		$getdata= $_GET['id'];
		$dataex=explode("^",$getdata);
		$id=$dataex[0];
		$table_name =$dataex[1];
		$pri_col =$dataex[2];

		$this->Model_admin_login->delete_user($pri_col,$table_name,$id);	
}


function delete_data_user()
{
	$this->load->model('Model_admin_login');
		$getdata= $_GET['id'];
		$dataex=explode("^",$getdata);
		$id=$dataex[0];
		$table_name =$dataex[1];
		$pri_col =$dataex[2];

		$this->Model_admin_login->delete_user($pri_col,$table_name,$id);	
}

 function delete_data_orgz() {
	
	$this->load->model('Model_admin_login');
		$getdata= $_GET['id'];
		$dataex=explode("^",$getdata);
		$id=$dataex[0];
		$table_name =$dataex[1];
		$pri_col =$dataex[2];

		$this->Model_admin_login->delete_user($pri_col,$table_name,$id);

		$this->db->query("delete from tbl_file where file_type='Orgz' AND file_logid='$id' ");
		//$this->db->query("delete from tbl_mulit_orgz where morg_type='Organization' AND orgid='$id' ");
		$this->db->query("delete from tbl_note where note_type='Orgz' AND note_logid='$id' ");
		$this->db->query("delete from tbl_software_log where mdl_name='Orgz' AND slog_id='$id' ");
		
}


 function delete_data_cntct() {
	
	$this->load->model('Model_admin_login');
		$getdata= $_GET['id'];
		$dataex=explode("^",$getdata);
		$id=$dataex[0];
		$table_name =$dataex[1];
		$pri_col =$dataex[2];

		$this->Model_admin_login->delete_user($pri_col,$table_name,$id);

		$this->db->query("delete from tbl_file where file_type='Contact' AND file_logid='$id' ");
		//$this->db->query("delete from tbl_mulit_orgz where morg_type='Contact' AND orgid='$id' ");
		$this->db->query("delete from tbl_note where note_type='Contact' AND note_logid='$id' ");
		$this->db->query("delete from tbl_software_log where mdl_name='Contact' AND slog_id='$id' ");
		
}


function delete_data_lead() {
	
	$this->load->model('Model_admin_login');
		$getdata= $_GET['id'];
		$dataex=explode("^",$getdata);
		
		$id=$dataex[0];
		$table_name =$dataex[1];
		$pri_col =$dataex[2];
		
		$this->Model_admin_login->delete_user($pri_col,$table_name,$id);

		$this->db->query("delete from tbl_lead_rates where lead_id='$id' ");
		$this->db->query("delete from tbl_mulit_orgz where morg_logid='$id' ");
		$this->db->query("delete from tbl_file where file_type='Lead' AND file_logid='$id' ");
		$this->db->query("delete from tbl_note where note_type='Lead' AND note_logid='$id' ");
		$this->db->query("delete from tbl_software_log where mdl_name='Lead' AND slog_id='$id' ");
		
}

function delete_data_task() {
	
	$this->load->model('Model_admin_login');
		$getdata= $_GET['id'];
		$dataex=explode("^",$getdata);
		
		$id=$dataex[0];
		$table_name =$dataex[1];
		$pri_col =$dataex[2];
		
		
		

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

		$this->Model_admin_login->delete_user($pri_col,$table_name,$id);
		
		
}
//================================Close single delete data ============== 

 
//================================* Start Multiple delete table data ============== 
 function multiple_delete_table(){		
	
	$id=$_POST['ids'];

	$tabledata =$_POST['table_name'];
	$table_name_ex=explode("^",$tabledata);
	
	$pri_data =$_POST['pri_col'];
	$pri_col_ex =explode("^",$pri_data);
	
	$this->db->query("delete from $tabledata where $pri_data in($id)");
		
}
//================================Close Multiple delete table data ============== 

 

//================================*Start multiple_delete_two_table==================
function multiple_delete_two_table(){		
$id=$_POST['ids'];

	$tabledata =$_POST['table_name'];
	$table_name_ex=explode("^",$tabledata);
	$table_name=tbl_product_serial_log;
	$table_name_dtl=tbl_product_serial;
	
	
	$pri_data =$_POST['pri_col'];
	$pri_col_ex =explode("^",$pri_data);
	$pri_col =product_id;
	$pri_col_dtl =product_id;
	

	$this->db->query("delete from $tabledata where $pri_data in($id)");
	$this->db->query("delete from $table_name where $pri_col in($id)");
	$this->db->query("delete from $table_name_dtl where $pri_col_dtl in($id)");
		
	
			
}

//===============================Close multiple_delete_two_table==========================




//===================Update  Dismiss Status=========================

function update_task_dismiss_status()
{
	
	$login_id=$this->session->userdata('user_id');

	$getid = $_GET['id'];

	$dismissid=$this->db->query("select * from tbl_task where task_id='$getid' ");
	$fetchDismisId=$dismissid->row();			
	$aid=$fetchDismisId->dismiss;

	$bid=explode(",",$aid);
	$cid=array_unique($bid);
	$getDismissId=implode(",",$cid);
	
	if($getDismissId != '')
	{
		$newdismisid=$getDismissId.','.$login_id;	
	}
	else
	{
		$newdismisid=$login_id;
	}

	date_default_timezone_set("Asia/Kolkata");
	$dtTime = date('Y-m-d H:i:s');
	$this->db->query("update tbl_task set dismiss = '$newdismisid',dismiss_date='$dtTime' where task_id='$getid' ");

}

function update_lead_dismiss_status()
{

	$login_id=$this->session->userdata('user_id');

	$getid = $_GET['id'];

	$dismissid=$this->db->query("select * from tbl_leads where lead_id='$getid' ");
	$fetchDismisId=$dismissid->row();			
	$aid=$fetchDismisId->dismiss;

	$bid=explode(",",$aid);
	$cid=array_unique($bid);
	$getDismissId=implode(",",$cid);
	
	if($getDismissId != '')
	{
		$newdismisid=$getDismissId.','.$login_id;	
	}
	else
	{
		$newdismisid=$login_id;
	}

	$this->db->query("update tbl_leads set dismiss = '$newdismisid' where lead_id='$getid' ");

}
//=======================Close ============================



public function forgotPassword()
{

//echo "bbbb";die;
@extract($_POST);
$email_id=$this->input->post('email_id');
$userQuery=$this->db->query("select * from tbl_user_mst where email_id='$email_id' and status='A' ");
$cnt=$userQuery->num_rows();
	
	if($cnt>0)
	{
		$getUser=$userQuery->row();
		$fullname=$getUser->user_name;
		$toemail=$getUser->email_id;
		$cnfemail=$getUser->confirm_email;
		$id=$getUser->user_id;
		
			//$msg="Name :- ".$fullname." & Your Password Is :- ".$password ;
			$url     =  'http://techvyaserp.in'.base_url()."master/resetPasswordPage?cnfrmail=$cnfemail&id=$id";
			$body    =" <table  width=100% border=0><tr>";
        	$body   .=' 
                 		<td align="center"  style="text-align:left;font-size:23px;color:#65676f;padding:30px 0 15px 0" valign="top">Hi '.$fullname.',</td></tr>
     					<tr><td align="center" style="text-align:left;font-size:16px;color:#65676f;padding:0 0 30px 0;line-height:1.8" valign="top" width="538px">
                  		<span style="font-weight:bold">Click below button to reset your password.</span>
			            <div><a  href="'.$url.'" style="font-size:16px;font-weight:bold;background:#33b2c1;margin-top:18px;padding:10px 0;border-radius:3px;color:#fff;text-transform:uppercase;display:inline-block;text-decoration:none;text-align:center;width:270px" target="_blank">Reset Password</a></div>
               			</td>
                 		</tr>';
         	$body   .= '</table>';
		
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
		

		$this->email->from('info@techvyaserp.in', 'Tech Vyas');
		$this->email->to($toemail);
		$this->email->bcc('collestbablu@gmail.com');
		
		$this->email->subject('Reset Password');
		$this->email->message($body);
		
		$this->email->send();

		$this->db->query("update tbl_user_mst set password_status='S' where user_id = '$id' ");
		$this->session->set_flashdata('message', 'Please check your mail for reset password ');
		redirect('master/master/forgotpage');
	}
	else
	{
		$this->session->set_flashdata('message', 'Email Id did not match to admin account or User is not verified!');
		redirect('master/master/forgotpage');
	}

}


function resetPasswordPage()
 {
 	             
     if($this->input->get() != "")
     {
	 	  $confirmemail = $this->input->get('cnfrmail');
	 	  $id        = $this->input->get('id');

	   	  $sql   = "select * from tbl_user_mst where confirm_email = '".$confirmemail."' AND user_id = $id";
	   	  $query = $this->db->query($sql)->row();
      
   	  if(sizeof($query) > 0)
   	  {
	   	  	$data['result'] = $query;
	   	  	  // echo "<pre>";
	   	      //  print_r($query);
	   	      // echo "</pre>";
	   	  	if($query->password_status == 'R')
	   	  	{
	   	  	    $this->session->set_flashdata('error', 'Password already reset, Please Login Now!');
	   	  	  	redirect('index');	
	   	  	}
	          $this->load->view('reset-password',$data);
       }

    }
 
 }



function passwordReset()
{	
	
	$userid=$this->input->post('useridd');
	$email=$this->input->post('emailid');
	$newpass=$this->input->post('entrpaswrd');

	$query = $this->db->query("select * from tbl_user_mst where status='A' AND email_id='$email' AND user_id='$userid' ");
	$cntquery = $query->num_rows();

	if($cntquery > 0)
	{
		$this->db->query("update tbl_user_mst set password='$newpass',password_status='R' where user_id = '$userid' ");
		$this->session->set_flashdata('error','Password Change Successfully. Now You Can Login !');
		redirect('index');
	}
	
}


public function ciPagination($url,$totalData,$sgmnt,$showEntries)
{
	  
	    $config['use_page_numbers']     = FAlSE;
        $config['page_query_string']    = TRUE;
        $config['query_string_segment'] = 'offset';
       
        $config['base_url']       =  $url;
        $config['total_rows']     =  $totalData;
        $config['per_page']       =  $showEntries;
        $config["uri_segment"]    =  $sgmnt;
        //$choice                   =  $config["total_rows"] / $config["per_page"];
        $config["num_links"]      =  2;//floor($choice);

        //config for bootstrap pagination class integration
        $config['full_tag_open']  = '<ul class="pagination">';
        $config['full_tag_close'] = '</ul>';
        $config['first_link']     = 'First';
        $config['last_link']      = 'Last';
        $config['first_tag_open'] = '<li>';
        $config['first_tag_close']= '</li>';
        $config['prev_link']      = '&laquo';
        $config['prev_tag_open']  = '<li class="prev">';
        $config['prev_tag_close'] = '</li>';
        $config['next_link']      = '&raquo'; 
        $config['next_tag_open']  = '<li>';
        $config['next_tag_close'] = '</li>';
        $config['last_tag_open']  = '<li>';
        $config['last_tag_close'] = '</li>';
        $config['cur_tag_open']   = '<li class="active"><a href="#">';
        $config['cur_tag_close']  = '</a></li>';
        $config['num_tag_open']   = '<li>';
        $config['num_tag_close']  = '</li>';

        $this->pagination->initialize($config);
        $pages = $_GET['offset'];
        $postlist['page'] = ($pages != "") ? $pages : 0;

        return array('per_page'=>$config['per_page'] ,'page'=>$postlist['page']);	
	   
 }



public function software_log_insert($slog_id,$mdl_name,$slog_name,$slog_type,$old_id,$new_id,$remarks)
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
					
					'maker_id' => $this->session->userdata('user_id'),
					//'maker_date' => date('y-m-d'),
					'author_id' => $this->session->userdata('user_id'),
					'author_date' => date('y-m-d'),
					'comp_id' => $this->session->userdata('comp_id'),				
					'brnh_id' => $this->session->userdata('brnh_id'),
			);
	$data_merge = array_merge($data,$sess);	
	$this->Model_admin_login->insert_user($table_name,$data_merge);
	return;

}


//================Remarks Log=================

public function update_note_log($note_logid,$main_lead_id,$note_type,$note_desc)
{

	$table_name='tbl_note';

	$data=array(
					'note_logid'   			=> $note_logid,
					'note_type'  			=> $note_type,
					'note_desc' 			=> $note_desc,
					'main_lead_id_note'		=> $main_lead_id,
			   );

	$sess = array(
					
					'maker_id' => $this->session->userdata('user_id'),
					'maker_date' => date('y-m-d'),
					'author_id' => $this->session->userdata('user_id'),
					'author_date' => date('y-m-d'),
					'comp_id' => $this->session->userdata('comp_id'),				
					'brnh_id' => $this->session->userdata('brnh_id'),
			);
	$data_merge = array_merge($data,$sess);	
	$this->Model_admin_login->insert_user($table_name,$data_merge);
	return;

}


} ?>