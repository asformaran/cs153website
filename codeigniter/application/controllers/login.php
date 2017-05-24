<?php

class Login extends CI_Controller {

	public function index() {
		$this->load->helper('url');
		$this->load->library('session');
		$this->load->database();
		$this->load->helper('security');
		$this->db->select('*');
		$this->db->where('sessid', $this->session->session_id);
		$result = $this->db->get('sessions');
		if ($result->num_rows() > 0){
			redirect(profile);
		}
		$this->load->library('form_validation', NULL, "loginform");
		$this->loginform->set_rules('username', 'username', 'trim|required|alpha_dash|xss_clean', array('required' => 'Please enter your %s.'));
		$this->loginform->set_rules('password', 'password', 'trim|required|alpha_dash|xss_clean', array('required' => 'You must provide a %s.'));

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
		$this->db->where('password', sha1($credentials['password']));
		$result = $this->db->get('users');

		if ($result->num_rows() > 0){

			if ($credentials['username'] == "root"){
				$this->session->set_flashdata('rootuser', 'root');
				$this->notroot();
			}
			else {
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
				exit;
			}
		}
		else {
			$data['error'] = "Invalid login credentials<br><br>";
			$this->load->view('loginpage', $data);
		}
	}

	public function notroot() {
		$this->load->helper('url');
		$this->load->library('session');
		$this->load->helper('security');
		$this->load->database();

		if (!$this->session->flashdata('rootuser') || $this->session->flashdata('rootuser') != 'root') {
			redirect('login');
		}

		$this->load->library('form_validation', NULL, 'changetemp');
		$this->changetemp->set_rules('username', 'username', 'trim|xss_clean|required|alpha_dash|callback_username_check|callback_username_len', array('required' => 'Please enter your %s.', 'alpha_dash' => 'Only alphanumeric characters and dashes are allowed for %s.', 'xss_clean' => 'Dont do this.'));
		$this->changetemp->set_rules('password', 'password', 'trim|xss_clean|required|alpha_dash|callback_username_len', array('required' => 'You must provide a %s.', 'alpha_dash' => 'Only alphanumeric characters and dashes are allowed for %s.', 'xss_clean' => 'Dont do this.'));
		$this->changetemp->set_rules('name', 'name', 'trim|xss_clean|required|alpha_numeric_spaces|callback_username_len', array('required' => 'Please enter your %s.', 'alpha_numeric_spaces' => 'Only alphanumeric characters and spaces are allowed for %s.', 'xss_clean' => 'Dont do this.'));
		$this->changetemp->set_rules('address', 'address', 'trim|xss_clean|required|callback_username_len', array('required' => 'Please enter your %s.', 'xss_clean' => 'Dont do this.'));
		$this->changetemp->set_rules('birthday', 'birthday', 'required', array('required' => 'Please enter your %s.'));

		if ($this->changetemp->run()) {
			$details = $this->input->post();
			$this->db->select('*');
			$this->db->where('username',$details['username']);
			$result = $this->db->get('users');
			if ($result->num_rows() > 0){
				$data['error'] = "Account already existing!";
				$this->load->view('changetemp',$data);
			}
			else{
				$this->load->helper('url');
				$this->db->delete('users', array('username' => "root")); 
				$data = array('username' => $details['username'], 'password' => sha1($details['password']), 'name' => $details['name'], 'address' => $details['address'], 'birthday' => $details['birthday'], 'superuser' => 1);
				$this->db->insert('users', $data);
				$this->db->insert('sessions', array('sessid' => $this->session->session_id, 'username' => $details['username']));
				$this->db->insert('megauser', array('username' => $details['username']));
				$message = "User successfully created!";
				$this->session->set_flashdata('alert', $message);

				$data['username'] = $details['username'];
				$data['superuser'] = 1;
				$this->session->set_userdata('userinfo', $data);
				$this->session->set_userdata($this->session->session_id, $data['username']);
				redirect('profile');
				exit;
			}
			
		}
		else {
			$this->load->view('changetemp');
		}
	}

	public function username_check($str) {
        if ($str == 'root') {
            $this->changetemp->set_message('username_check', 'Username cannot be root!');
            return FALSE;
        }
        else {
            return TRUE;
        }
    }

    public function username_len($str) {
        if (strlen($str) > 255) {
            $this->changetemp->set_message('username_len', '%s cannot exceed maximum length!');
            return FALSE;
        }
        else {
            return TRUE;
        }
    }
}
?>
