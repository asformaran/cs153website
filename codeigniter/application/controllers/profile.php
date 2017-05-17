<?php

class Profile extends CI_Controller {

	public function index() {
		$this->load->library('session');
		$this->load->helper('url');
		$this->load->database();
		$userinfo = $this->session->userdata('userinfo');

		if (!$userinfo){
			redirect('login');
		}
		
		if ($userinfo['superuser']){
			$this->superuser = new Superuser($userinfo, $this->db);
			$this->session->set_userdata('usertype', $this->superuser);
			$this->superuser->loaduser($this->load);
		}
		else{
			$this->user = new User($userinfo, $this->db);
			$this->session->set_userdata('usertype', $this->user);
			$this->user->loaduser($this->load);
		}
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
		}
	}

	public function onlineusers() {
		$this->load->library('session');
		$this->load->database();
		$this->load->helper('url');
		$user = $this->session->userdata('usertype');

		if (isset($user)){
			$user->onlines($this->load, $this->db);
		}
		else {
			redirect('login');
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
		}
	}

	public function editself() {
		$this->load->library('session');
		$this->load->database();
		$this->load->helper('url');
		$this->load->library('form_validation');

		if (!$this->session->userdata('usertype')) {
			redirect('login');
		}

		$user = $this->session->userdata('usertype');
		$data['name'] = $user->info['name'];
		$data['address'] = $user->info['address'];
		$data['birthday'] = $user->info['birthday'];

		$this->form_validation->set_rules('birthday', 'Birthday', 'required', array('required' => '%s should be in date format.'));

		if ($this->form_validation->run() == FALSE) {
			$this->load->view('editinfo', $data);
		}
		else{
			$this->db->select('*');
			$this->db->where('username',$user->info['username']);
			$result = $this->db->get('sessions');

			if ($result->num_rows() > 0){
				$this->session->sess_regenerate();
				$user->editinfo($this->input, $this->session, $this->load, $this->db);
			}
			else {
				redirect('login');
			}
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
			$this->session->sess_destroy();
		}
		redirect('login');
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
		$load->helper('url');
		$load->view('userprofilepage', $this->info);
	}

	public function editinfo($input, $session, $load, $db) {

		$load->helper('url');
		$newinfo = $input->post();
		$db->where('username', $this->info['username']);
		$db->update('users', array('name' => $newinfo['name'], 'address' => $newinfo['address'], 'birthday' => $newinfo['birthday']));
		redirect('profile');		
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
		$load->helper('url');
		$load->view('superuserprofilepage', $this->info);
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

	public function create() {

	}

	public function update() {

	}

	public function edit() {

	}

	public function delete() {

	}

}
?>
