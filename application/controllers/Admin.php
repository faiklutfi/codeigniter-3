<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Admin extends CI_Controller
{

 function __construct()
 {
  parent::__construct();
  $this->load->model('m_model');
  $this->load->helper('my_helper');
        if ($this->session->userdata('logged_in')!=true) {
            redirect(base_url().'auth');
        }
 }

 public function index()
 {
  $data['siswa'] = $this->m_model->get_data('siswa')->num_rows();
  $this->load->view('admin/index');
 }

 public function siswa()
 {
  $data['siswa'] = $this->m_model->get_data('siswa')->result();
  $this->load->view('admin/siswa', $data);
 }
 
 public function tambah_siswa()
 {
  $data['siswa'] = $this->m_model->get_data('siswa')->result();
  $data['kelas'] = $this->m_model->get_data('kelas')->result();
  $this->load->view('admin/tambah_siswa', $data);
 }

 public function aksi_tambah_siswa()
 {
        $data = [
            'nama_siswa' => $this->input->post('nama'),
            'nisn' => $this->input->post('nisn'),
            'gender' => $this->input->post('gender'),
            'id_kelas' => $this->input->post('kelas'),
        ];
        $this->m_model->tambah_data('siswa', $data);
        redirect(base_url('admin/siswa'));
 }

 public function update_siswa()
 {
  $data['kelas'] = $this->m_model->get_data('kelas')->result();
  $this->load->view('admin/update_siswa', $data);
 }

 public function ubah_siswa($id)
 {
  $data['siswa']=$this->m_model->get_by_id('siswa', 'id_siswa', $id)->result();  
  $data['kelas']=$this->m_model->get_data('kelas')->result();
  $this->load->view('admin/ubah_siswa', $data);  

 }

 public function aksi_ubah_siswa()
    {
        $data = array (
            'nama_siswa' => $this->input->post('nama'),
            'nisn' => $this->input->post('nisn'),
            'gender' => $this->input->post('gender'),
            'id_kelas' => $this->input->post('kelas'),
        );
        $eksekusi=$this->m_model->ubah_data
        ('siswa', $data, array('id_siswa'=>$this->input->post('id_siswa')));
        if($eksekusi)
        {
            redirect(base_url('admin/siswa'));
        }
        else
        {
            redirect(base_url('admin/ubah_siswa/'.$this->input->post('id_siswa')));
        }
    }

 public function hapus_siswa($id)
    {
        $this->m_model->delete('siswa', 'id_siswa', $id);
        redirect(base_url('admin/siswa'));
    }
    public function akun()
    {
      $data['user'] = $this->m_model->get_by_id('admin', 'id', $this->session->userdata('id'))->result();
      $this->load->view('admin/akun', $data);
    }
    public function aksi_ubah_akun()
    {
      $password_baru = $this->input->post('password_baru');
      $konfirmasi_password = $this->input->post('konfirmasi_password');
      $email = $this->input->post('email');
      $username = $this->input->post('username');
  
      $data = array(
        'email' => $email,
        'username' => $username
      );
  
      if (!empty($password_baru)) {
        if ($password_baru === $konfirmasi_password) {
          $data['password'] = md5($password_baru);
        } else {
          $this->session->set_flashdata('message', 'Password baru dan konfirmasi password harus sama');
          redirect(base_url('admin/akun'));
        }
      }
  
      $this->session->set_userdata($data);
      $update_result = $this->m_model->ubah_data('admin', $data, array('id' => $this->session->userdata('id')));
  
      if ($update_result) {
        redirect(base_url('admin/akun'));
      } else {
        redirect(base_url('admin/akun'));
      }
    }

}

?>