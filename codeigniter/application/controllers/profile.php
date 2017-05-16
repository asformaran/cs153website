<?php

class Profile extends CI_Controller {

	public function index() {
		$this->load->library('session');
		$this->load->helper('url');
		$userinfo = $this->session->userdata('userinfo');

		if (!$userinfo){
			redirect('login');
		}
		
		if ($userinfo['superuser']){
			$this->superuser = new Superuser($userinfo);
			$this->session->set_userdata('usertype', $this->superuser);
			$this->superuser->loaduser($this->load);
		}
		else{
			$this->user = new User($userinfo);
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
			redirect('login');
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

	public function __construct($userinfo) {
		$this->info['username'] = $userinfo['username'];
		$this->info['name'] = $userinfo['name'];
		$this->info['address'] = $userinfo['address'];
		$this->info['birthday'] = $userinfo['birthday'];
		$this->info['superuser'] = $userinfo['superuser'];
	}

	public function loaduser($load) {
		$load->helper('url');
		$load->view('userprofilepage', $this->info);
	}

	public function editinfo() {

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
