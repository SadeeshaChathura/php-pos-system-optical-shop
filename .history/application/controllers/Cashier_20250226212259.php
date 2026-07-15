<?php
defined('BASEPATH') OR exit('No direct script access allowed');

date_default_timezone_set('Asia/Colombo');

class Directsale extends CI_Controller {
    public function index(){
        $this->load->model('Commeninfo');
        $this->load->model('Directsaleinfo');
        $result['material']=$this->Directsaleinfo->Getmaterial();
        $result['location']=$this->Directsaleinfo->Getlocation();
		$result['menuaccess']=$this->Commeninfo->Getmenuprivilege();
        $result['customer']=$this->Directsaleinfo->Getcustomer();
		$this->load->view('directsale', $result);
	}
}