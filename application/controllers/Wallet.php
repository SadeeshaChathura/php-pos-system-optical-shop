<?php
defined('BASEPATH') OR exit('No direct script access allowed');

date_default_timezone_set('Asia/Colombo');

class Wallet extends CI_Controller {
    public function index(){
        $this->load->model('Commeninfo');
        $this->load->model('Walletinfo');
        $result['menuaccess']=$this->Commeninfo->Getmenuprivilege();
        $result['wallet']=$this->Walletinfo->GetWalletSummary();
        $this->load->view('wallet', $result);
    }
}