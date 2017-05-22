<?php

class Profile extends CI_Controller {

	public function index() {
		$this->load->library('session');
		$this->load->helper('url');
		$this->load->database();

		if (!$this->session->userdata('usertype')) {
			$userinfo = $this->session->userdata('userinfo');

			if (!$userinfo){
				redirect('login');
				exit;
			}
			
			if ($userinfo['superuser']){
				$this->superuser = new Superuser($userinfo, $this->db);
				$this->session->set_userdata('usertype', $this->superuser);
			}
			else{
				$this->user = new User($userinfo, $this->db);
				$this->session->set_userdata('usertype', $this->user);
			}
		}

		$this->session->userdata('usertype')->loaduser($this->load, NULL);
		
	}

	public function superview() {
		$this->load->library('session');
		$this->load->database();
		$this->load->helper('url');
		$user = $this->session->userdata('usertype');

		if (isset($user)){
			if ($user->info['superuser']) {
				$user->view($this->load, $this->db);
			}
			else {
				echo "You are not a superuser!!<br>";
			}
		}
		else {
			redirect('login');
			exit;
		}
	}

	public function superadd() {
		$this->load->helper('url');
		$this->load->library('session');
		$this->load->database();

		if (!$this->session->userdata('usertype')) {
			redirect('login');
			exit;
		}

		$user = $this->session->userdata('usertype');

		$this->load->library('form_validation', NULL, 'createform');
		$this->createform->set_rules('username', 'username', 'trim|required|alpha_dash', array('required' => 'Please enter your %s.', 'alpha_dash' => 'Only alphanumeric characters and dashes are allowed for %s.'));
		$this->createform->set_rules('password', 'password', 'trim|required|alpha_dash', array('required' => 'You must provide a %s.', 'alpha_dash' => 'Only alphanumeric characters and dashes are allowed for %s.'));
		$this->createform->set_rules('name', 'name', 'trim|required|alpha_numeric_spaces', array('required' => 'Please enter your %s.', 'alpha_numeric_spaces' => 'Only alphanumeric characters and spaces are allowed for %s.'));
		$this->createform->set_rules('address', 'address', 'trim|required', array('required' => 'Please enter your %s.'));
		$this->createform->set_rules('birthday', 'birthday', 'required', array('required' => 'Please enter your %s.'));

		if ($this->createform->run() == FALSE && !$this->input->post('cancelled')) {
			$this->load->view('createuser');
		}
		else if ($this->createform->run() && $this->input->post('submitted')) {
			$this->session->sess_regenerate();
			$this->db->where('username', $user->info['username']);
			$this->db->update('sessions',array('sessid' => $this->session->session_id));
			$user->create($this->input->post(), $this->load, $this->db, $this->session);
			
		}
		else if ($this->input->post('cancelled')) {
			redirect('profile');
			exit;
		}
	}

	public function onlineusers() {
		$this->load->library('session');
		$this->load->database();
		$this->load->helper('url');
		$user = $this->session->userdata('usertype');

		if (isset($user)){

			/*$this->db->select('*');
			$result = $this->db->get('sessions');
			for ($row = 0; $row < $result->num_rows(); $row++) {
				$existsess = $result->result_array()[$row]['sessid'];
				if (!$this->session->userdata($existsess)){
					$this->db->where('sessid', $existsess);
					$this->db->delete('sessions');
				}
			}*/

			$user->onlines($this->load, $this->db);
		}
		else {
			redirect('login');
			exit;
		}
	}

	public function viewbirthdays() {
		$this->load->library('session');
		$this->load->database();
		$this->load->helper('url');
		$user = $this->session->userdata('usertype');

		if (isset($user)){
			$user->birthdays($this->load, $this->db);
		}
		else {
			redirect('profile');
			exit;
		}
	}

	public function editself() {
		$this->load->library('session');
		$this->load->database();
		$this->load->helper('url');
		$this->load->library('form_validation', NULL, 'selfeditform');

		if (!$this->session->userdata('usertype')) {
			redirect('login');
			exit;
		}

		$user = $this->session->userdata('usertype');
		$data['name'] = $user->info['name'];
		$data['address'] = $user->info['address'];
		$data['birthday'] = $user->info['birthday'];

		$this->selfeditform->set_rules('name', 'name', 'trim|required|alpha_numeric_spaces', array('required' => 'Please enter your %s.', 'alpha_numeric_spaces' => 'Only alphanumeric characters and spaces are allowed for %s.'));
		$this->selfeditform->set_rules('address', 'address', 'trim|required', array('required' => 'Please enter your %s.'));
		$this->selfeditform->set_rules('birthday', 'Birthday', 'trim|required', array('required' => '%s should be in date format.'));

		if ($this->selfeditform->run() == FALSE && !$this->input->post('cancelled')) {
			$this->load->view('editinfo', $data);
		}
		else if ($this->selfeditform->run() && $this->input->post('submitted')) {
			$this->session->sess_regenerate();
			$this->db->where('username', $user->info['username']);
			$this->db->update('sessions',array('sessid' => $this->session->session_id));
			$user->editself($this->input, $this->session, $this->load, $this->db);
		}
		else if ($this->input->post('cancelled')) {
			redirect('profile');
			exit;
		}
	}

	public function logout() {
		$this->load->library('session');
		$this->load->helper('url');

		if ($this->session->userdata('usertype')) {
			$this->load->database();
			$username = $this->session->userdata('userinfo');
			$this->db->delete('sessions',array('username' => $username['username']));
			$this->session->unset_userdata('usertype');
			$this->session->unset_userdata('userinfo');
			$this->session->sess_destroy();
		}
		redirect('login');
		exit;
	}
}

class User {

	public function __construct($userinfo, $db) {
		$db->select('name, address, birthday');
		$db->where('username', $userinfo['username']);
		$result = $db->get('users');
		$this->info['username'] = $userinfo['username'];
		$this->info['name'] = $result->result_array()[0]['name'];
		$this->info['address'] = $result->result_array()[0]['address'];
		$this->info['birthday'] = $result->result_array()[0]['birthday'];
		$this->info['superuser'] = $userinfo['superuser'];
	}

	public function loaduser($load) {
		$info['username'] = $this->info['username'];
		$info['name'] = $this->info['name'];
		$info['address'] = $this->info['address'];
		$info['birthday'] = $this->info['birthday'];
		$load->view('userprofilepage', $info);
	}

	public function editself($input, $session, $load, $db) {
		$load->helper('url');
		$newinfo = $input->post();
		$db->where('username', $this->info['username']);
		$db->update('users', array('name' => $newinfo['name'], 'address' => $newinfo['address'], 'birthday' => $newinfo['birthday']));

		$this->info['name'] = $newinfo['name'];
		$this->info['address'] = $newinfo['address'];
		$this->info['birthday'] = $newinfo['birthday'];
		$message = "Successfully edited your info!";
		$session->set_flashdata('alert', $message);
		redirect('profile');
		exit;
		//$session->userdata('usertype')->loaduser($load, $message);		
	}

	public function onlines($load, $db){
		$load->library('session');
		$load->helper('url');
		$load->database();

		$db->select('username');
		//$db->where('username != ', $this->info['username']);
		$query = $db->get('sessions');
		$data['online'] = $query->result_array();

		$load->view('viewonline', $data);
	}

	public function birthdays($load, $db){
		$load->library('session');
		$load->helper('url');
		$load->database();

		$db->select('username, name, birthday');
		//$db->where('username != ', $this->info['username']);
		$query = $db->get('users');
		$data['users'] = $query->result_array();

		$load->view('viewbirthdays', $data);
	}

	public function __destruct() {}

}

class Superuser extends User{

	public function loaduser($load) {
		$info['username'] = $this->info['username'];
		$info['name'] = $this->info['name'];
		$info['address'] = $this->info['address'];
		$info['birthday'] = $this->info['birthday'];
		$load->view('superuserprofilepage',$info);
	}

	public function view($load, $db) {
		$load->library('session');
		$load->helper('url');
		$load->database();

		$db->select('name, address, birthday');
		$query = $db->get('users');
		$data['users'] = $query->result_array();

		$load->view('viewusers', $data);
	}

	public function create($details, $load, $db, $session) {
		$db->select('*');
		$db->where('username',$details['username']);
		$result = $db->get('users');
		if ($result->num_rows() > 0){
			$data['error'] = "Account already existing!";
			$load->view('createuser',$data);
		}
		else{
			$load->helper('url');
			$data = array('username' => $details['username'], 'password' => $details['password'], 'name' => $details['name'], 'address' => $details['address'], 'birthday' => $details['birthday'], 'superuser' => 0);
			$db->insert('users', $data);
			$message = "User successfully created!";
			$session->set_flashdata('alert', $message);
			redirect('profile');
			exit;
			//$this->loaduser($load, $message);
		}
	}

	public function update() {

	}

	public function edit() {

	}

	public function delete() {

	}

}
?>
