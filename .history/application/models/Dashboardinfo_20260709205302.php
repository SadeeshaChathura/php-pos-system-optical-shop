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

    public function MonthlySalesTotal($yearMonth = null) {
        $cb = $this->companyBranch();
        $this->db->select_sum('nettotal');
        if ($yearMonth && preg_match('/^\d{4}-\d{2}$/', $yearMonth)) {
            $start = $yearMonth . '-01';
            $end = date('Y-m-t', strtotime($start));
        } else {
            $start = date('Y-m-01');
            $end = date('Y-m-t');
        }
        $this->db->where('invdate >=', $start);
        $this->db->where('invdate <=', $end);
        $this->db->where('status', 1);
        $this->db->where('tbl_company_idtbl_company', $cb['company']);
        $this->db->where('tbl_company_branch_idtbl_company_branch', $cb['branch']);
        $q = $this->db->get('tbl_invoice')->row();
        return isset($q->nettotal) ? (float)$q->nettotal : 0;
    }

    public function ProductsCount() {
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

    // Last N days daily sales trend, ending on $endDate (defaults to today)
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

        // Fill missing dates with 0
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

    // Last N months sales trend, ending on the month containing $endYearMonth
    public function MonthlySalesChart($months = 12, $endYearMonth = null) {
        $cb = $this->companyBranch();
        if ($endYearMonth && preg_match('/^\d{4}-\d{2}$/', $endYearMonth)) {
            $end = date('Y-m-t', strtotime($endYearMonth . '-01'));
            $start = date('Y-m-01', strtotime("-" . ($months - 1) . " months", strtotime($endYearMonth . '-01')));
        } else {
            $end = date('Y-m-t');
            $start = date('Y-m-01', strtotime("-" . ($months - 1) . " months"));
        }

        $this->db->select("DATE_FORMAT(invdate, '%Y-%m') as salesmonth, SUM(nettotal) as total");
        $this->db->where('invdate >=', $start);
        $this->db->where('invdate <=', $end);
        $this->db->where('status', 1);
        $this->db->where('tbl_company_idtbl_company', $cb['company']);
        $this->db->where('tbl_company_branch_idtbl_company_branch', $cb['branch']);
        $this->db->group_by("DATE_FORMAT(invdate, '%Y-%m')");
        $this->db->order_by('salesmonth', 'ASC');
        $rows = $this->db->get('tbl_invoice')->result();

        $result = [];
        for ($i = $months - 1; $i >= 0; $i--) {
            $m = date('Y-m', strtotime("-" . $i . " months", strtotime($end)));
            $result[$m] = 0;
        }
        foreach ($rows as $r) {
            $result[$r->salesmonth] = (float)$r->total;
        }
        return $result;
    }

    // ---------- STOCK ----------

    // Items at/below reorder level (always live/current, not date filtered)
    public function LowStockItems() {
        return $this->LowStockItemsRaw();
    }

    private function LowStockItemsRaw() {
        $sql = "SELECT mi.idtbl_material_info, mi.materialname, mi.reorderlevel,
                       IFNULL(SUM(s.qty),0) as currentstock
                FROM tbl_material_info mi
                LEFT JOIN tbl_stock s 
                       ON s.tbl_material_info_idtbl_material_info = mi.idtbl_material_info 
                      AND s.status = 1
                WHERE mi.status = 1
                GROUP BY mi.idtbl_material_info
                HAVING currentstock <= mi.reorderlevel
                ORDER BY currentstock ASC";
        return $this->db->query($sql)->result();
    }

    // ---------- FAST / SLOW MOVING ----------

    // Top sellers by qty in the $days window ending on $endDate
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

    // Slowest movers (sold the least, or none) in the $days window ending on $endDate
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

    public function OverdueAdvanceOrders() {
    $cb = $this->companyBranch();
    $sql = "SELECT i.idtbl_invoice, i.duedate, i.nettotal,
                   c.name AS customername, c.contact AS customercontact,
                   IFNULL(pay.paidsofar,0) AS paidsofar
            FROM tbl_invoice i
            LEFT JOIN tbl_customer c ON c.idtbl_customer = i.tbl_customer_idtbl_customer
            LEFT JOIN (
                SELECT ph.tbl_invoice_idtbl_invoice AS invid, SUM(p.nettotal) AS paidsofar
                FROM tbl_invoice_payment p
                JOIN tbl_invoice_payment_has_tbl_invoice ph
                  ON ph.tbl_invoice_payment_idtbl_invoice_payment = p.idtbl_invoice_payment
                GROUP BY ph.tbl_invoice_idtbl_invoice
            ) pay ON pay.invid = i.idtbl_invoice
            WHERE i.ordertype = 2
              AND i.paycomplete = 0
              AND i.status = 1
              AND i.duedate < CURDATE()
              AND i.tbl_company_idtbl_company = ?
              AND i.tbl_company_branch_idtbl_company_branch = ?
            ORDER BY i.duedate ASC";
    return $this->db->query($sql, [$cb['company'], $cb['branch']])->result();
}
}