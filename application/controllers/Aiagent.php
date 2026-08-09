<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Aiagent extends CI_Controller {

    public function __construct() {
        parent::__construct();
        if (!$this->session->userdata('loggedin')) {
            redirect(base_url());
        }
        $this->load->model('Commeninfo');
        $this->load->model('Aiagentinfo');
        $this->load->model('Chatagentinfo');
    }

    public function SalesAgent() {
        $result['menuaccess'] = $this->Commeninfo->Getmenuprivilege();

        $selectedDate = $this->input->get('date');
        if (!$selectedDate || !DateTime::createFromFormat('Y-m-d', $selectedDate)) {
            $selectedDate = date('Y-m-d');
        }
        $result['selectedDate'] = $selectedDate;

        $result['insights']         = $this->Aiagentinfo->SalesAgentInsights($selectedDate);
        $result['revenueTrend']     = $this->Aiagentinfo->RevenueTrend($selectedDate);
        $result['avgInvoice']       = $this->Aiagentinfo->AverageInvoiceValue(30, $selectedDate);
        $result['topRevenueItems']  = $this->Aiagentinfo->TopRevenueItems(5, 30, $selectedDate);
        $result['customerRisk']     = $this->Aiagentinfo->CustomerRiskList();
        $result['paymentBreakdown'] = $this->Aiagentinfo->PaymentMethodBreakdown(30, $selectedDate);

        $this->load->view('aiagent_sales', $result);
    }

    public function InventoryAgent() {
        $result['menuaccess'] = $this->Commeninfo->Getmenuprivilege();

        $result['insights']       = $this->Aiagentinfo->InventoryAgentInsights();
        $result['reorder']        = $this->Aiagentinfo->ReorderSuggestions();
        $result['deadStock']      = $this->Aiagentinfo->DeadStock(90, 10);
        $result['valuation']      = $this->Aiagentinfo->StockValuation();
        $result['valuationTotal'] = $this->Aiagentinfo->StockValuationTotal();

        $this->load->view('aiagent_inventory', $result);
    }

    public function GetSuppliers() {
        $materialId = (int) $this->input->post('materialId');
        $result = $this->Aiagentinfo->SuppliersForMaterial($materialId);
        echo json_encode($result);
    }

    /**
     * Chat Assistant — free-text (well, keyword-matched) Q&A over your
     * own ERP data. Renders the chat UI; ChatQuery() below answers
     * individual messages via AJAX.
     */
    public function ChatAgent() {
        $result['menuaccess'] = $this->Commeninfo->Getmenuprivilege();
        $this->load->view('aiagent_chat', $result);
    }

    public function ChatQuery() {
        $message = $this->input->post('message');
        $response = $this->Chatagentinfo->AnswerQuery($message);
        echo json_encode($response);
    }
}