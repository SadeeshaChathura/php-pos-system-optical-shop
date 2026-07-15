<?php
defined('BASEPATH') OR exit('No direct script access allowed');

date_default_timezone_set('Asia/Colombo');

class CustomerPrescription extends CI_Controller {

    public function index($customerid = null){
        $this->load->model('Commeninfo');
        $this->load->model('CustomerPrescriptioninfo');

        $result['menuaccess'] = $this->Commeninfo->Getmenuprivilege();
        $result['selectedcustomerid'] = $customerid;
        $result['selectedcustomer'] = null;

        if (!empty($customerid)) {
            $result['selectedcustomer'] = $this->CustomerPrescriptioninfo->Getcustomer($customerid);
        }

        $this->load->view('customerprescription', $result);
    }

    public function Customerprescriptioninsertupdate(){
        $this->load->model('CustomerPrescriptioninfo');
        $result = $this->CustomerPrescriptioninfo->Customerprescriptioninsertupdate();
    }

    public function Customerprescriptionstatus($x, $y){
        $this->load->model('CustomerPrescriptioninfo');
        $result = $this->CustomerPrescriptioninfo->Customerprescriptionstatus($x, $y);
    }

    public function Customerprescriptionedit(){
        $this->load->model('CustomerPrescriptioninfo');
        $result = $this->CustomerPrescriptioninfo->Customerprescriptionedit();
    }

    public function Customerprescriptionview(){
        $this->load->model('CustomerPrescriptioninfo');
        $result = $this->CustomerPrescriptioninfo->Customerprescriptionview();
    }

    public function Customerprescriptionlist(){
        $this->load->model('CustomerPrescriptioninfo');
        $result = $this->CustomerPrescriptioninfo->Customerprescriptionlist();
    }

    public function Customersearch(){
        $this->load->model('CustomerPrescriptioninfo');
        $result = $this->CustomerPrescriptioninfo->Customersearch();
    }
}