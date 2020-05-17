<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Bidang_persil extends Admin_Controller 
{

	public function __construct()
	{
		parent::__construct();
		session_start();
		$this->load->model('header_model');
		$this->load->model('bidang_persil_model');
		$this->modul_ini = 13;
		$this->sub_modul_ini = 53;
	}

	public function clear()
	{
		unset($_SESSION['cari']);
		$_SESSION['per_page'] = 20;
		redirect('bidang_persil');
	}

	public function search(){
		$_SESSION['cari'] = $this->input->post('cari');
		if ($_SESSION['cari'] == '') unset($_SESSION['cari']);
		redirect('bidang_persil');
	}

	public function index()
	{
		$header = $this->header_model->get_data();
		$header['minsidebar'] = 1;
		$this->tab_ini = 16;

		$data['cari'] = isset($_SESSION['cari']) ? $_SESSION['cari'] : '';
		$data['keyword'] = $this->bidang_persil_model->autocomplete();
		$_SESSION['per_page'] = $_POST['per_page'] ?: null;
		$data['per_page'] = $_SESSION['per_page'];

		$data["desa"] = $this->config_model->get_data();
		$data['paging']  = $this->bidang_persil_model->paging($page);
		$data["bidang"] = $this->bidang_persil_model->list_data($data['paging']->offset, $data['paging']->per_page);

		$this->load->view('header', $header);
		$this->load->view('nav', $nav);
		$this->load->view('data_persil/bidang_persil', $data);
		$this->load->view('footer');
	}

}
