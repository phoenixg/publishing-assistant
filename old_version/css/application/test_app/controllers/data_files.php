<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Data_files extends Controller {
	
	function Data_files()
	{
		parent::Controller();
		$this->load->helper(array('form', 'url', 'file'));
	}
	
	function index($errors = array(''))
	{	
		$data['files'] = get_filenames('./uploads');
		$data['errors'] = $errors;
		//$data['content'] = $this->load->view('upload/upload_form', $data, true);
		$data['content'] = $this->load->view('data_files/main', $data, true);
		$this->load->view('template', $data);
	}

	/**
	 * Manage Uploads
	 *
	 * @return void
	 * @author MGreen
	 **/
	public function manage_uploads()
	{
		$data['content'] = $this->load->view('data_files/main', '', true);
		$this->load->view('template', $data);
	}	
	
	function do_upload()
	{
		$config['upload_path'] = './uploads/';
		$config['allowed_types'] = 'xls';
		$config['max_size']	= '10000';
		//$config['max_width']  = '1024';
		//$config['max_height']  = '768';
		
		$this->load->library('upload', $config);
	
		if ( ! $this->upload->do_upload())
		{
			$errors = array('error' => $this->upload->display_errors());
			$this->index($errors);
			//$data['content'] = $this->load->view('upload/upload_form', $error, true);
			//$this->load->view('template', $data);
		}	
		else
		{
			redirect ('upload');
			/*$data = array('upload_data' => $this->upload->data());
			$data['content'] = $this->load->view('upload/upload_form', $data, true);
			$this->load->view('template', $data);*/
		}
	}	
	
	/**
	 * EXCEL SHOW
	 *
	 * @return void
	 * @author MGreen
	 **/
	public function show_excel($file="")
	{
		$data['file'] = $file;
		$data['content'] = $this->load->view('data_files/show', $data, true);
		$this->load->view('template', $data);
	}	
	
}
?>