<?php

class Profile extends CI_Controller {

	public function index() {
		$this->load->library('session');
		$this->load->helper('url');
		$this->load->database();
		$this->load->helper('security');

		$userinfo = $this->session->userdata('userinfo');

		if (!$userinfo){
			redirect('login');
			exit;
		}

		if (!$this->session->userdata('usertype')) {
			
			if ($userinfo['superuser']){
				$this->superuser = new Superuser($userinfo);
				$this->session->set_userdata('usertype', $this->superuser);
			}
			else{
				$this->user = new User($userinfo);
				$this->session->set_userdata('usertype', $this->user);
			}
		}

		$this->session->userdata('usertype')->loaduser($this->load, $this->db);
		
	}

	public function superpriv() {
		$this->load->library('session');
		$this->load->database();
		$this->load->helper('url');
		$this->load->helper('form');
		$this->load->helper('security');

		if (!$this->session->userdata('usertype')) {
			redirect('login');
			exit;
		}

		$user = $this->session->userdata('usertype');

		if ($user->info['superuser'] == 0){
			redirect('login');
			exit;
		}
			
		if ($this->input->post('changed')) {
			$this->session->sess_regenerate();
			$this->db->where('username', $user->info['username']);
			$this->db->update('sessions',array('sessid' => $this->session->session_id));
			$user->update($this->input->post(), $this->load, $this->db, $this->session);
			
		}
		else if ($this->input->post('cancelled')) {
			redirect('profile');
		}
		else {
			$this->db->select('*');
			//$this->db->where('username!=', $user->info['username']);
			$result = $this->db->get('users');
			$data['users'] = $result->result_array();
			$this->load->view('changepriv', $data);
		}
	}

	public function superadd() {
		$this->load->helper('url');
		$this->load->library('session');
		$this->load->database();
		$this->load->helper('security');

		if (!$this->session->userdata('usertype')) {
			redirect('login');
			exit;
		}

		$user = $this->session->userdata('usertype');

		if ($user->info['superuser'] == 0){
			redirect('login');
			exit;
		}

		$this->load->library('form_validation', NULL, 'createform');
		$this->createform->set_rules('username', 'username', 'trim|xss_clean|required|alpha_dash', array('required' => 'Please enter your %s.', 'alpha_dash' => 'Only alphanumeric characters and dashes are allowed for %s.'));
		$this->createform->set_rules('password', 'password', 'trim|xss_clean|required|alpha_dash', array('required' => 'You must provide a %s.', 'alpha_dash' => 'Only alphanumeric characters and dashes are allowed for %s.'));
		$this->createform->set_rules('name', 'name', 'trim|xss_clean|required|alpha_numeric_spaces', array('required' => 'Please enter your %s.', 'alpha_numeric_spaces' => 'Only alphanumeric characters and spaces are allowed for %s.'));
		$this->createform->set_rules('address', 'address', 'trim|xss_clean|required', array('required' => 'Please enter your %s.'));
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
		}
	}

	public function superedit() {
		$this->load->helper('url');
		$this->load->helper('form');
		$this->load->library('session');
		$this->load->database();
		$this->load->helper('security');

		if (!$this->session->userdata('usertype')) {
			redirect('login');
			exit;
		}

		$user = $this->session->userdata('usertype');

		if ($user->info['superuser'] == 0){
			redirect('login');
			exit;
		}

		$this->db->select('*');
		$this->db->where('username', $user->info['username']);
		$megauser = $this->db->get('megauser')->num_rows();
		if ($megauser == 0){
			redirect('profile');
			exit;
		}

		$this->load->library('form_validation', NULL, 'usereditform');
		$this->usereditform->set_rules('chosen','chosen', 'required', array('required' => "Please choose an entry in radio buttons."));
		$this->usereditform->set_rules('name', 'name', 'trim|xss_clean|alpha_numeric_spaces', array('alpha_numeric_spaces' => 'Only alphanumeric characters and spaces are allowed for %s.'));
		$this->usereditform->set_rules('address', 'address', 'trim|xss_clean');
		$this->usereditform->set_rules('birthday', 'birthday');

		$this->db->select('*');
		$this->db->where('username!=', $user->info['username']);
		//$this->db->where('superuser', 0);
		$result = $this->db->get('users');
		$data['users'] = $result->result_array();

		if ($this->usereditform->run() == FALSE && !$this->input->post('cancelled')) {
			$this->load->view('edituser', $data);
		}
		else if ($this->usereditform->run() && $this->input->post('updated')) {
			$this->session->sess_regenerate();
			$this->db->where('username', $user->info['username']);
			$this->db->update('sessions',array('sessid' => $this->session->session_id));
			$user->edit($this->input->post(), $this->load, $this->db, $this->session);
			
		}
		else if ($this->usereditform->run() && $this->input->post('view')) {
			$this->db->select('*');
			$this->db->where('username', $this->input->post('chosen'));
			$result = $this->db->get('users');
			$data['name'] = $result->result_array()[0]['name'];
			$data['address'] = $result->result_array()[0]['address'];
			$data['birthday'] = $result->result_array()[0]['birthday'];
			$this->load->view('edituser', $data);
		}
		else if ($this->input->post('cancelled')) {
			redirect('profile');
		}
	}

	public function superdelete(){
		$this->load->helper('url');
		$this->load->helper('form');
		$this->load->library('session');
		$this->load->database();
		$this->load->helper('security');

		if (!$this->session->userdata('usertype')) {
			redirect('login');
			exit;
		}

		$user = $this->session->userdata('usertype');

		if ($user->info['superuser'] == 0){
			redirect('login');
			exit;
		}

		if ($this->input->post('deleted')) {
			$this->session->sess_regenerate();
			$this->db->where('username', $user->info['username']);
			$this->db->update('sessions',array('sessid' => $this->session->session_id));
			$user->delete($this->input->post(), $this->load, $this->db, $this->session);
			
		}
		else if ($this->input->post('cancelled')) {
			redirect('profile');
		}
		else {
			$this->db->select('*');
			$this->db->where('username!=', $user->info['username']);
			$result = $this->db->get('users');
			$data['users'] = $result->result_array();
			$this->load->view('deleteusers', $data);
		}

	}

	public function onlineusers() {
		$this->load->library('session');
		$this->load->database();
		$this->load->helper('url');
		$this->load->helper('security');

		$user = $this->session->userdata('usertype');

		if (!$this->session->userdata('usertype')) {
			redirect('login');
			exit;
		}
		$user->onlines($this->load, $this->db);
	}

	public function viewbirthdays() {
		$this->load->library('session');
		$this->load->database();
		$this->load->helper('url');
		$this->load->helper('security');

		$user = $this->session->userdata('usertype');

		if (!$this->session->userdata('usertype')) {
			redirect('login');
			exit;
		}
		
		$user->birthdays($this->load, $this->db);
	}

	public function editself() {
		$this->load->library('session');
		$this->load->database();
		$this->load->helper('url');
		$this->load->helper('security');
		$this->load->library('form_validation', NULL, 'selfeditform');

		if (!$this->session->userdata('usertype')) {
			redirect('login');
			exit;
		}

		$user = $this->session->userdata('usertype');
		$data['name'] = $user->info['name'];
		$data['address'] = $user->info['address'];
		$data['birthday'] = $user->info['birthday'];

		$this->selfeditform->set_rules('name', 'name', 'trim|xss_clean|alpha_numeric_spaces|required', array('alpha_numeric_spaces' => 'Only alphanumeric characters and spaces are allowed for %s.', 'required' => "Please enter your name."));
		$this->selfeditform->set_rules('address', 'address', 'trim|xss_clean|required', array('required' => 'Please enter your address.'));
		$this->selfeditform->set_rules('birthday', 'Birthday','trim|required', array('required' => 'Please enter your birthday.'));

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

	public function __construct($userinfo) {
		$this->info['username'] = $userinfo['username'];
		$this->info['name'] = '';
		$this->info['address'] = '';
		$this->info['birthday'] = '';
		$this->info['superuser'] = $userinfo['superuser'];
	}

	public function loaduser($load, $db) {
		$db->select('*');
		$db->where('username', $this->info['username']);
		$result = $db->get('users');
		$this->info['name'] = $result->result_array()[0]['name'];
		$this->info['address'] = $result->result_array()[0]['address'];
		$this->info['birthday'] = $result->result_array()[0]['birthday'];
		$load->view('userprofilepage', $this->info);
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

	public function loaduser($load, $db) {
		$db->select('*');
		$db->where('username', $this->info['username']);
		$result = $db->get('users');
		$this->info['name'] = $result->result_array()[0]['name'];
		$this->info['address'] = $result->result_array()[0]['address'];
		$this->info['birthday'] = $result->result_array()[0]['birthday'];
		$load->view('superuserprofilepage',$this->info);
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
			$data = array('username' => $details['username'], 'password' => sha1($details['password']), 'name' => $details['name'], 'address' => $details['address'], 'birthday' => $details['birthday'], 'superuser' => 0);
			$db->insert('users', $data);
			$message = "User successfully created!";
			$session->set_flashdata('alert', $message);
			redirect('profile');
			exit;
		}
	}

	public function update ($details, $load, $db, $session) {
		$load->helper('url');
		$message = "Privileges successfully updated!";
		$db->select('username');
		$master = $db->get('megauser');
		$master = $master->row_array()['username'];
		$db->select('*');
		$db->where('superuser', 1);
		$supercount = $db->get('users')->num_rows();
		foreach ($details['privlevel'] as $entry){
			$db->select('*');
			$db->where('username', $entry);
			//$db->where('username!=', $this->info['username']);
			$result = $db->get('sessions');
			if ($result->num_rows() > 0 || $entry == $master){
				$message = "Some user/s cannot be removed for specific reason/s.";				
			}
			else{
				$db->select('superuser');
				$db->where('username', $entry);
				$result = $db->get('users');

				if ($result->row_array()['superuser'] == 0){
					$db->where('username', $entry);
					$db->update('users', array('superuser' => 1));
					$supercount ++;
				}
				else {
					if ($supercount == 1){
						$message = "One superuser remaining.";
					}
					else {
						$db->where('username', $entry);
						$db->update('users', array('superuser' => 0));
						$supercount --;
					}
				}
			}
		}
		$session->set_flashdata('alert', $message);
		redirect('profile');
		exit;
	}

	public function edit ($details, $load, $db, $session) {
		$db->where('username', $details['chosen']);
		$db->update('users', array('name' => $details['name'], 'address' => $details['address'], 'birthday' => $details['birthday']));
		$load->helper('url');
		$message = "User successfully updated!";
		$session->set_flashdata('alert', $message);
		redirect('profile');
		exit;
	}

	public function delete($details, $load, $db, $session) {
		$load->helper('url');
		$message = "Users successfully deleted!";
		$db->select('username');
		$master = $db->get('megauser');
		$master = $master->row_array()['username'];
		$db->select('*');
		$db->where('superuser', 1);
		$supercount = $db->get('users')->num_rows();
		foreach ($details['deletearr'] as $entry){
			$db->select('*');
			$db->where('username', $entry);
			$db->where('username!=', $this->info['username']);
			$result = $db->get('sessions');
			if ($result->num_rows() > 0 || $entry == $master){
				$message = "Some user/s cannot be removed for specific reason/s.";				
			}
			else{
				$db->select('superuser');
				$db->where('username', $entry);
				$result = $db->get('users');

				if ($result->row_array()['superuser'] == 0){
					$db->where('username', $entry);
					$db->delete('users');
				}
				else {
					if ($supercount == 1){
						$message = "One superuser remaining.";
					}
					else {
						$db->where('username', $entry);
						$db->delete('users');
						$supercount --;
					}
				}
			}
		}
		$session->set_flashdata('alert', $message);
		redirect('profile');
		exit;
	}
}
?>
