<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Bidang_persil_model extends CI_Model {

  public function __construct()
  {
    parent::__construct();
  }

  private function search_sql()
  {
    if (isset($_SESSION['cari']))
    {
      $cari = $_SESSION['cari'];
      $kw = $this->db->escape_like_str($cari);
      $kw = '%' .$kw. '%';
      $this->db->where("p.nomor like '$kw'")
        ->or_where("c.nomor like '$kw'");
    }
  }

  public function paging($p=1)
  {
    $this->main_sql();
    $jml = $this->db->select('COUNT(*) AS jml')
      ->get()->row()->jml;

    $this->load->library('paging');
    $cfg['page'] = $p;
    $cfg['per_page'] = $_SESSION['per_page'];
    $cfg['num_rows'] = $jml;
    $this->paging->init($cfg);

    return $this->paging;
  }

  private function main_sql()
  {
    $this->db->from('mutasi_cdesa m')
      ->join('cdesa c', 'c.id = m.id_cdesa_masuk', 'left')
      ->join('persil p', 'p.id = m.id_persil', 'left')
      ->join('data_persil_peruntukan dp', 'm.peruntukan = dp.id', 'left')
      ->join('data_persil_jenis dj', 'm.jenis_bidang_persil = dj.id', 'left')
      ->join('ref_persil_kelas rk', 'p.kelas = rk.id', 'left')
      ->join('tweb_wil_clusterdesa w', 'w.id = p.id_wilayah', 'left');
    $this->search_sql();
  }

  public function list_data($offset, $per_page)
  {
    $this->main_sql();
    $data = $this->db
      ->select('m.*, p.nomor, rk.kode as kelas_tanah, dp.nama as peruntukan, dj.nama as jenis_persil')
      ->select('CONCAT("RT ", rt, " / RW ", rw, " - ", dusun) as lokasi, p.lokasi as alamat')
      ->order_by('p.nomor, m.no_bidang_persil')
      ->get()
      ->result_array();
    return $data;
  }
}
