<?php

class Login extends CI_Controller {

	public function index() {
		$this->load->helper('url');
		$this->load->library('session');
		$this->load->database();
		$this->db->select('*');
		$this->db->where('sessid', $this->session->session_id);
		$result = $this->db->get('sessions');
		if ($result->num_rows() > 0){
			redirect(profile);
		}
		$this->load->library('form_validation', NULL, "loginform");
		$this->loginform->set_rules('username', 'username', 'trim|required|alpha_dash', array('required' => 'Please enter your %s.'));
		$this->loginform->set_rules('password', 'password', 'trim|required|alpha_dash', array('required' => 'You must provide a %s.'));

		if ($this->loginform->run() == FALSE) {
			$this->load->view('loginpage');
		}
		else{
			$this->auth();
		}
	}

	public function auth() {
		$this->load->library('session');
		$this->session->sess_regenerate(TRUE);
		$this->load->database();

		$credentials = $this->input->post();

		$this->db->select('*');
		$this->db->where('username', $credentials['username']);
		$this->db->where('password', $credentials['password']);
		$result = $this->db->get('users');

		if ($result->num_rows() > 0){

			$this->db->select('*');
			$this->db->where('username', $result->result_array()[0]['username']);
			$updatesess = $this->db->get('sessions');
			if ($updatesess->num_rows() > 0){
				$this->db->where('username', $result->result_array()[0]['username']);
				$this->db->update('sessions', array('sessid' => $this->session->session_id));
			}
			else{
				$this->db->insert('sessions', array('sessid' => $this->session->session_id, 'username' => $result->result_array()[0]['username']));
			}

			$data['username'] = $result->result_array()[0]['username'];
			$data['superuser'] = $result->result_array()[0]['superuser'];
			$this->session->set_userdata('userinfo', $data);
			$this->session->set_userdata($this->session->session_id, $data['username']);
			redirect('profile');
		}
		else {
			$data['error'] = "Invalid login credentials<br><br>";
			$this->load->view('loginpage', $data);
		}
	}
}
?>
