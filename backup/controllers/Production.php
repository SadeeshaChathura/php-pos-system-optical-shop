<?php
defined('BASEPATH') OR exit('No direct script access allowed');

date_default_timezone_set('Asia/Colombo');

class Production extends CI_Controller {
    public function index(){
        $this->load->model('Commeninfo');
        $this->load->model('Productioninfo');
		$result['menuaccess']=$this->Commeninfo->Getmenuprivilege();
		$result['factorylist']=$this->Productioninfo->Getfactorylist();
		$this->load->view('production', $result);
	}
    public function Productiondetailaccoproduction(){
		$this->load->model('Productioninfo');
        $result=$this->Productioninfo->Productiondetailaccoproduction();
	}
    public function Getmachineaccofactory(){
		$this->load->model('Productioninfo');
        $result=$this->Productioninfo->Getmachineaccofactory();
	}
    public function Productionstepone(){
		$this->load->model('Productioninfo');
        $result=$this->Productioninfo->Productionstepone();
	}
    public function Getbatchnoaccoproductiondetail(){
		$this->load->model('Productioninfo');
        $result=$this->Productioninfo->Getbatchnoaccoproductiondetail();
	}
    public function Getqtyinfoaccoproductiondetail(){
		$this->load->model('Productioninfo');
        $result=$this->Productioninfo->Getqtyinfoaccoproductiondetail();
	}
    public function Checkavabilitystock(){
		$this->load->model('Productioninfo');
        $result=$this->Productioninfo->Checkavabilitystock();
	}
}