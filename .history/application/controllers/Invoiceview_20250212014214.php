<?php
defined('BASEPATH') OR exit('No direct script access allowed');

date_default_timezone_set('Asia/Colombo');

class Invoiceview extends CI_Controller {
    public function index(){
        $this->load->model('Commeninfo');
		$this->load->model('Invoiceviewinfo');
		$result['menuaccess']=$this->Commeninfo->Getmenuprivilege();
        $result['location']=$this->Invoiceviewinfo->locationlist();      
		$this->load->view('invoiceview', $result);
	}

	public function Getinvoicedetails(){
        $this->load->model('Invoiceviewinfo');
        $result=$this->Invoiceviewinfo->Getinvoicedetails();
    }

    public function printreport($x){
		$this->load->model('Invoiceviewreportinfo');
        $result=$this->Invoiceviewreportinfo->printreport($x);
	}
    public function printreportpos($x){
		$this->load->model('Invoiceviewreportposinfo');
        $result=$this->Invoiceviewreportposinfo->printreportpos($x);
	}

    public function Getproductlisttoselectpicker(){
		$this->load->model('Invoiceviewinfo');
        $result=$this->Invoiceviewinfo->Getproductlisttoselectpicker();
	}
}