<?php

class Welcome extends Controller {

	function Welcome()
	{
		parent::Controller();

		$this->load->database();

		$this->load->scaffolding('papers');
		
	}
	
	function index()
	{
		$data['query'] = $this->db->query('SELECT * FROM papers');
		
		$this->load->view('welcome_message', $data);
	}
}

/* End of file welcome.php */
/* Location: ./system/application/controllers/welcome.php */