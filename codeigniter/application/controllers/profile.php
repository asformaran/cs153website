<?php

class Profile extends CI_Controller {

	public function index() {
		$this->load->library('session');
		$this->load->helper('url');
		$userinfo = $this->session->flashdata('userinfo');
		$this->load->helper('url');
		$this->load->view('profilepage', $userinfo);
	}
}
?>
