<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends CI_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see https://codeigniter.com/userguide3/general/urls.html
	 */
	public function index()
	{
		$result['companylist']=CompanyList();
		$this->load->view('login', $result);
	}
	public function LoginUser(){
		$this->load->model('Userinfo');
        $result=$this->Userinfo->LoginUser();

		$company=$this->input->post('company');
		$branch=$this->input->post('branch');

		$resultcompany=$this->Userinfo->Companybranchinfo();

		// print_r($result);
        if($result!=false){
            $user_data=array(
                'userid'=>$result->idtbl_user,
                'name'=>$result->name,
                'type'=>$result->idtbl_user_type,
                'typename'=>$result->type,
				'companyid'=>$company,
                'branchid'=>$branch,
                'company'=>$resultcompany->company,
                'branch'=>$resultcompany->branch,
                'loggedin'=>true
            );

			$this->session->set_userdata($user_data);
			
			redirect('Welcome/Dashboard');            
        }
        else{
            $this->session->set_flashdata('msg', 'Invalid Username or password');
            redirect();
        }
	}
	public function Logout(){
        $this->session->unset_userdata('userid');
        $this->session->unset_userdata('name');
        $this->session->unset_userdata('type');
        $this->session->unset_userdata('typename');
		$this->session->unset_userdata('companyid');
        $this->session->unset_userdata('branchid');
        $this->session->unset_userdata('company');
        $this->session->unset_userdata('branch');
        $this->session->unset_userdata('loggedin');
        $this->cart->destroy();
        redirect(base_url());
    }
	public function Dashboard(){
		$this->load->model('Commeninfo');
		$this->load->model('Dashboardinfo');

		$result['menuaccess'] = $this->Commeninfo->Getmenuprivilege();

		// Selected date via GET `date=YYYY-MM-DD`, defaults to today.
		// This single date now drives the whole dashboard filter.
		$selectedDate = $this->input->get('date');
		if (!$selectedDate || !DateTime::createFromFormat('Y-m-d', $selectedDate)) {
			$selectedDate = date('Y-m-d');
		}
		$selectedMonth = date('Y-m', strtotime($selectedDate));

		$result['selectedDate']  = $selectedDate;
		$result['selectedMonth'] = $selectedMonth;

		// Cards
		$result['dailySalesTotal']   = $this->Dashboardinfo->DailySalesTotal($selectedDate);
		$result['monthlySalesTotal'] = $this->Dashboardinfo->MonthlySalesTotal($selectedMonth);
		$result['productsCount']     = $this->Dashboardinfo->ProductsCount();
		$result['ordersToday']       = $this->Dashboardinfo->OrdersTodayCount($selectedDate);

		// Charts
		$result['dailySalesChart']   = $this->Dashboardinfo->DailySalesChart(7, $selectedDate);
		$result['monthlySalesChart'] = $this->Dashboardinfo->MonthlySalesChart(12, $selectedMonth);

		// Stock / movement
		$result['lowStockItems']  = $this->Dashboardinfo->LowStockItems();
		$result['fastMoving']     = $this->Dashboardinfo->FastMovingItems(5, 30, $selectedDate);
		$result['slowMoving']     = $this->Dashboardinfo->SlowMovingItems(5, 30, $selectedDate);
		$result['overdueOrders'] = $this->Dashboardinfo->OverdueAdvanceOrders();

		$this->load->view('dashboard', $result);
	}
	public function Getbranchaccocompany(){
		$recordID=$this->input->post('recordID');
        $result=CompanyBranchList($recordID);
	}
	public function Getbranchaccocompany(){
		$recordID = $this->input->post('recordID');
		$result   = CompanyBranchList($recordID);
		echo json_encode($result);
	}
}
