<?php
defined('BASEPATH') OR exit('No direct script access allowed');

date_default_timezone_set('Asia/Colombo');

class Directsale extends CI_Controller {

    public function index(){
        $this->load->model('Commeninfo');
        $this->load->model('Directsaleinfo');

        $result['material']    = $this->Directsaleinfo->Getmaterial();
        $result['location']    = $this->Directsaleinfo->Getlocation();
        $result['menuaccess']  = $this->Commeninfo->Getmenuprivilege();
        $result['customer']    = $this->Directsaleinfo->Getcustomer();

        $this->load->view('directsale', $result);
    }

    public function Directsaleinsertupdate(){
        $this->load->model('Directsaleinfo');
        $this->Directsaleinfo->Directsaleinsertupdate();
    }

    public function Directsalestatus($x, $y){
        $this->load->model('Directsaleinfo');
        $this->Directsaleinfo->Directsalestatus($x, $y);
    }

    public function Directsaleedit(){
        $this->load->model('Directsaleinfo');
        $this->Directsaleinfo->Directsaleedit();
    }

    public function Getproductlist(){
        $this->load->model('Directsaleinfo');
        $this->Directsaleinfo->Getproductlist();
    }
    public function Getproductbatches(){
        $this->Directsaleinfo->Getproductbatches();
    }
    public function Getcategorylist(){
        $this->load->model('Directsaleinfo');
        $this->Directsaleinfo->Getcategorylist();
    }

    public function Getproductlistaccobarcode(){
        $this->load->model('Directsaleinfo');
        $this->Directsaleinfo->Getproductlistaccobarcode();
    }

    public function Getproductavalaibleqty(){
        $this->load->model('Directsaleinfo');
        $this->Directsaleinfo->Getproductavalaibleqty();
    }

    public function Getposprintbill($x){
        $this->load->model('Directsalereceiptinfo');
        $this->Directsalereceiptinfo->Getposprintbill($x);
    }

    public function Getcreditprintbill($x){
        $this->load->model('Directsalecreditreceiptinfo');
        $this->Directsalecreditreceiptinfo->Getcreditprintbill($x);
    }

    public function Getcustomerlist(){
        $this->load->model('Directsaleinfo');
        $this->Directsaleinfo->Getcustomerlist();
    }

    // ===== New endpoints for the redesigned POS =====

    public function Getcustomersearch(){
        $this->load->model('Directsaleinfo');
        $this->Directsaleinfo->Getcustomersearch();
    }

    public function Quickaddcustomer(){
        $this->load->model('Directsaleinfo');
        $this->Directsaleinfo->Quickaddcustomer();
    }
}