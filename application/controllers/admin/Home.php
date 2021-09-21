<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends CI_Controller {

	public function __construct(){
		parent::__construct();
		$admin = $this->session->userdata('admin');

		if(empty($admin)){
			$this->session->set_flashdata('msg','Your Session Has Been Expired');
			redirect(base_url().'admin/login/index');
		}
	}	

	public function index(){

		$admin = $this->session->userdata('admin');
		//print_r($admin);
		$this->load->view('admin/dashboard');
	}
}
