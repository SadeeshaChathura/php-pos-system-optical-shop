<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboardinfo extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    private function companyBranch() {
        return [
            'company' => $this->session->userdata('companyid'),
            'branch'  => $this->session->userdata('branchid'),
        ];
    }

    // ---------- CARD METRICS ----------

    public function DailySalesTotal($date = null) {
    $date = $date ?: date('Y-m-d');
    $cb = $this->companyBranch();
    $this->db->select_sum('nettotal');
    $this->db->where('invdate', $date);
    $this->db->where('status', 1);
    $this->db->where('tbl_company_idtbl_company', $cb['company']);
    $this->db->where('tbl_company_branch_idtbl_company_branch', $cb['branch']);
    $q = $this->db->get('tbl_invoice')->row();
    return isset($q->nettotal) ? (float)$q->nettotal : 0;
}

// MonthlySalesTotal($yearMonth) stays as-is — controller will pass
// the month derived from the selected date.

public function ProductsCount() {
    // unchanged — not date dependent
    $this->db->where('status', 1);
    return $this->db->count_all_results('tbl_material_info');
}

public function OrdersTodayCount($date = null) {
    $date = $date ?: date('Y-m-d');
    $cb = $this->companyBranch();
    $this->db->where('DATE(invdate)', $date);
    $this->db->where('status', 1);
    $this->db->where('tbl_company_idtbl_company', $cb['company']);
    $this->db->where('tbl_company_branch_idtbl_company_branch', $cb['branch']);
    return $this->db->count_all_results('tbl_invoice');
}

// ---------- CHARTS ----------

public function DailySalesChart($days = 7, $endDate = null) {
    $endDate = $endDate ?: date('Y-m-d');
    $cb = $this->companyBranch();
    $startDate = date('Y-m-d', strtotime("-" . ($days - 1) . " days", strtotime($endDate)));

    $this->db->select("DATE(invdate) as salesdate, SUM(nettotal) as total");
    $this->db->where('invdate >=', $startDate);
    $this->db->where('invdate <=', $endDate);
    $this->db->where('status', 1);
    $this->db->where('tbl_company_idtbl_company', $cb['company']);
    $this->db->where('tbl_company_branch_idtbl_company_branch', $cb['branch']);
    $this->db->group_by('DATE(invdate)');
    $this->db->order_by('salesdate', 'ASC');
    $rows = $this->db->get('tbl_invoice')->result();

    $result = [];
    for ($i = $days - 1; $i >= 0; $i--) {
        $d = date('Y-m-d', strtotime("-$i days", strtotime($endDate)));
        $result[$d] = 0;
    }
    foreach ($rows as $r) {
        $result[$r->salesdate] = (float)$r->total;
    }
    return $result;
}

// MonthlySalesChart($months, $endYearMonth) stays as-is.

// ---------- FAST / SLOW MOVING ----------

public function FastMovingItems($limit = 5, $days = 30, $endDate = null) {
    $endDate = $endDate ?: date('Y-m-d');
    $startDate = date('Y-m-d', strtotime("-$days days", strtotime($endDate)));

    $this->db->select("mi.materialname, SUM(id.qty) as totalqty");
    $this->db->from('tbl_invoice_detail id');
    $this->db->join('tbl_invoice i', 'i.idtbl_invoice = id.tbl_invoice_idtbl_invoice');
    $this->db->join('tbl_material_info mi', 'mi.idtbl_material_info = id.tbl_material_info_idtbl_material_info');
    $this->db->where('i.invdate >=', $startDate);
    $this->db->where('i.invdate <=', $endDate);
    $this->db->where('i.status', 1);
    $this->db->group_by('id.tbl_material_info_idtbl_material_info');
    $this->db->order_by('totalqty', 'DESC');
    $this->db->limit($limit);
    return $this->db->get()->result();
}

public function SlowMovingItems($limit = 5, $days = 30, $endDate = null) {
    $endDate = $endDate ?: date('Y-m-d');
    $startDate = date('Y-m-d', strtotime("-$days days", strtotime($endDate)));

    $sql = "SELECT mi.materialname, IFNULL(SUM(id.qty),0) as totalqty
            FROM tbl_material_info mi
            LEFT JOIN tbl_invoice_detail id 
                   ON id.tbl_material_info_idtbl_material_info = mi.idtbl_material_info
            LEFT JOIN tbl_invoice i 
                   ON i.idtbl_invoice = id.tbl_invoice_idtbl_invoice 
                  AND i.invdate >= ? AND i.invdate <= ? AND i.status = 1
            WHERE mi.status = 1
            GROUP BY mi.idtbl_material_info
            ORDER BY totalqty ASC
            LIMIT ?";
    $params = [$startDate, $endDate, $limit];
    return $this->db->query($sql, $params)->result();
}
}