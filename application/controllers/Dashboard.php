<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends CI_Controller {

  function __construct() {
      parent::__construct();
      $this->load->helper('url');
  }

	public function index()
	{
		$this->load->view('dashboard');
	}

  public function diff()
  {
    $this->load->library('diff');
		$result = $this->diff->compareDir('v1', 'v2');
    echo json_encode($result);
  }

}
