<?php
/*
 * ----------------------------------------------------------------------------
 * "THE BEER-WARE LICENSE" :
 * <thepixeldeveloper@googlemail.com> wrote this file. As long as you retain this notice you
 * can do whatever you want with this stuff. If we meet some day, and you think
 * this stuff is worth it, you can buy me a beer in return Mathew Davies
 * ----------------------------------------------------------------------------
 */
class Main extends Controller {

	/**
	 * index
	 *
	 * @return void
	 * @author Mathew
	 **/
	function index()
	{
		//redirect('welcome/status');
		redirect('main/view_main');
		
	}

	/**
	 * View Main
	 *
	 * @return void
	 * @author MGreen
	 **/
	public function view_main()
	{
	
		$data['content'] = $this->load->view('collection/main', '', true);
		$this->load->view('template', $data);
	}
	
	/**
	 * List Records
	 *
	 * @return void
	 * @author MGreen
	 **/
	public function list_records($type)
	{	
		$this->load->model('collection_model');
	
		$data['records']  = $this->collection_model->get_records_by_type($type);
		$data['content'] = $this->load->view('collection/list_records', $data, true);
		
		$this->load->view('template', $data);
	}
	
	/**
	 * Update Record
	 *
	 * @return void
	 * @author MGreen
	 **/
	public function update_record($id)
	{
		$this->load->model('collection_model');
	
		if ($this->input->post('submit')) {
			//Update the Record
			$this->collection_model->update_record();
		}
	
		$data['record']  = $this->collection_model->get_record($id);
		$data['record_types']  = $this->collection_model->get_record_types();
		$data['media_types']  = $this->collection_model->get_media_types();
		$data['content'] = $this->load->view('collection/edit_record', $data, true);
		
		$this->load->view('template', $data);
	}

	
	/**
	 * Add New Record
	 *
	 * @return void
	 * @author MGreen
	 **/
	public function add_new_record()
	{
		
		$data['record_types']  = $this->collection_model->get_record_types();
		$data['media_types']  = $this->collection_model->get_media_types();		
		$data['content'] = $this->load->view('collection/add_new_record', $data, true);
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
		
		$data['content'] = $this->load->view('upload/upload_main', '', true);
		$this->load->view('template', $data);
	}	


}

/* End of file welcome.php */
/* Location: ./system/application/controllers/welcome.php */
