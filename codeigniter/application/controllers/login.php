<?php

class Login extends CI_Controller {

	public function index() {
		$this->load->helper('url');
		$this->load->library('form_validation');
		$this->form_validation->set_rules('username', 'username', 'required', array('required' => 'Please enter your %s.'));
		$this->form_validation->set_rules('password', 'password', 'required', array('required' => 'You must provide a %s.'));

		if ($this->form_validation->run() == FALSE) {
			$this->load->view('loginpage');
		}
		else{
			$this->auth();
		}
	}

	public function auth() {
		$this->load->library('session');
		$this->load->database();

		$credentials = $this->input->post();

		$this->db->select('*');
		$this->db->where('username', $credentials['username']);
		$this->db->where('password', $credentials['password']);
		$result = $this->db->get('users');

		if ($result->num_rows() == 1){
			$data['username'] = $result->result_array()[0]['username'];
			$data['name'] = $result->result_array()[0]['name'];
			$data['address'] = $result->result_array()[0]['address'];
			$data['birthday'] = $result->result_array()[0]['birthday'];
			$this->session->set_flashdata('userinfo', $data);
			redirect('profile/index');
		}
		else {
			$data['error'] = "Invalid login credentials<br><br>";
			$this->load->view('loginpage', $data);
		}
	}
}
?>
