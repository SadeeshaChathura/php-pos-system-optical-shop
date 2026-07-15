<?php
defined('BASEPATH') OR exit('No direct script access allowed');

date_default_timezone_set('Asia/Colombo');

class Goodreceive extends CI_Controller {
    public function index(){
        $this->load->model('Commeninfo');
        $this->load->model('Goodreceiveinfo');
		$result['menuaccess']=$this->Commeninfo->Getmenuprivilege();
		$result['supplierlist']=$this->Goodreceiveinfo->Getsupplier();
		$result['porderlist']=$this->Goodreceiveinfo->Getporder();
		// $result['costlist']=$this->Goodreceiveinfo->Getcostlist();
		$this->load->view('goodreceive', $result);
	}
    public function Goodreceiveinsertupdate(){
		$this->load->model('Goodreceiveinfo');
        $result=$this->Goodreceiveinfo->Goodreceiveinsertupdate();
	}
	public function Goodreceivestatus($idtbl_grn, $status) {
		$porder_id = $this->input->get('porder_id');
	
		$this->load->model('Goodreceiveinfo');
		$result = $this->Goodreceiveinfo->Goodreceivestatus($idtbl_grn, $status, $porder_id);
	}
	
    public function Goodreceiveedit(){
		$this->load->model('Goodreceiveinfo');
        $result=$this->Goodreceiveinfo->Goodreceiveedit();
	}
    public function Getproductaccosupplier(){
		$this->load->model('Goodreceiveinfo');
        $result=$this->Goodreceiveinfo->Getproductaccosupplier();
	}
    public function Goodreceiveview(){
		$this->load->model('Goodreceiveinfo');
        $result=$this->Goodreceiveinfo->Goodreceiveview();
	}
    public function Getsupplieraccoporder(){
		$this->load->model('Goodreceiveinfo');
        $result=$this->Goodreceiveinfo->Getsupplieraccoporder();
	}
    public function Getproductaccoporder(){
		$this->load->model('Goodreceiveinfo');
        $result=$this->Goodreceiveinfo->Getproductaccoporder();
	}
    public function Getproductinfoaccoproduct(){
		$this->load->model('Goodreceiveinfo');
        $result=$this->Goodreceiveinfo->Getproductinfoaccoproduct();
	}
    public function Getexpdateaccoquater(){
		$this->load->model('Goodreceiveinfo');
        $result=$this->Goodreceiveinfo->Getexpdateaccoquater();
	}
    public function Getbatchnoaccosupplier(){
		$this->load->model('Goodreceiveinfo');
        $result=$this->Goodreceiveinfo->Getbatchnoaccosupplier();
	}
    public function Getpordertpeaccoporder(){
		$this->load->model('Goodreceiveinfo');
        $result=$this->Goodreceiveinfo->Getpordertpeaccoporder();
	}
	public function Getgoodreceiveid(){
		$this->load->model('Goodreceiveinfo');
        $result=$this->Goodreceiveinfo->Getgoodreceiveid();
	}
	public function Costinsertupdate(){
		$this->load->model('Goodreceiveinfo');
        $result=$this->Goodreceiveinfo->Costinsertupdate();
	}

	public function Getallocatecostlist(){
		$this->load->model('Goodreceiveinfo');
        $result=$this->Goodreceiveinfo->Getallocatecostlist();
	}
	public function Getexpencetype(){
		$this->load->model('Goodreceiveinfo');
        $result=$this->Goodreceiveinfo->Getexpencetype();
	}
}