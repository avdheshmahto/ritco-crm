<?php
class model_admin_login extends CI_Model {
	
	public function can_log_in(){
	
	

			$this->db->where('user_name',$this->input->post('username'));
			$this->db->where('password',($this->input->post('password')));
			$query=$this->db->get('tbl_user_mst');
			$res   = $query->result(); 
			if($query->num_rows()==1){
				return $res;
			}else{
			
			
				return false;
		}
		
	
	}
	
	
	public function insert_user($table_name,$data){
	
	$this->db->insert($table_name, $data);
		
	}
	
	public function update_user($pri_col,$table_name,$id,$data){
		
		 $this->db->where($pri_col,$id);
		 $this->db->update($table_name,$data);
		}
	
	

	public function delete_user($pri_col,$table_name,$id){
		$this->db->where($pri_col,$id);
		$this->db->delete($table_name);
	}
	
	
	
	// model of insert and update contact starts
	
	public function insert_contact($table_name,$data,$data1){
	
		$this->db->insert($table_name, $data,$data1);
 	}
	public function update_contact($pri_col1,$table_name1,$id1,$data1){
		
		 $this->db->where($pri_col1,$id1);
		 $this->db->update($table_name1,$data1);
		}
	
	public function delete_address($pri_col1,$table_name1,$id){
		$this->db->where($pri_col1,$id);
		$this->db->delete($table_name1);
	}
	
	
	
	//  model of insert and update contact starts
	
		
  public function update($pri_col,$table_name,$id,$data,$kk){//with two condition
  $this->db->where($pri_col,$id,$kk);
  $this->db->update($table_name,$data);
}

	
	
}
