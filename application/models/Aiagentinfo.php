<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Aiagentinfo
 * ------------------------------------------------------------------
 * Rule-based "AI Agent" data layer. No external API calls — every
 * insight is computed directly from your own data (tbl_invoice,
 * tbl_stock, tbl_material_info, etc.) using simple trend/threshold
 * logic. Follows the same company/branch scoping pattern as
 * Dashboardinfo.
 * ------------------------------------------------------------------
 */
class Aiagentinfo extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    private function companyBranch() {
        return [
            'company' => $this->session->userdata('companyid'),
            'branch'  => $this->session->userdata('branchid'),
        ];
    }

    /* =========================================================
     *  SALES AGENT
     * ========================================================= */

    // Compare current month vs previous month net sales
    public function RevenueTrend($selectedDate = null) {
        $selectedDate = $selectedDate ?: date('Y-m-d');
        $cb = $this->companyBranch();

        $curStart  = date('Y-m-01', strtotime($selectedDate));
        $curEnd    = date('Y-m-t', strtotime($selectedDate));
        $prevStart = date('Y-m-01', strtotime('-1 month', strtotime($curStart)));
        $prevEnd   = date('Y-m-t', strtotime($prevStart));

        $current  = $this->_sumNetTotal($curStart, $curEnd, $cb);
        $previous = $this->_sumNetTotal($prevStart, $prevEnd, $cb);

        $change = 0;
        if ($previous > 0) {
            $change = (($current - $previous) / $previous) * 100;
        } elseif ($current > 0) {
            $change = 100;
        }

        return [
            'current'  => $current,
            'previous' => $previous,
            'change'   => round($change, 1),
        ];
    }

    private function _sumNetTotal($start, $end, $cb) {
        $this->db->select_sum('nettotal');
        $this->db->where('invdate >=', $start);
        $this->db->where('invdate <=', $end);
        $this->db->where('status', 1);
        $this->db->where('tbl_company_idtbl_company', $cb['company']);
        $this->db->where('tbl_company_branch_idtbl_company_branch', $cb['branch']);
        $q = $this->db->get('tbl_invoice')->row();
        return isset($q->nettotal) ? (float)$q->nettotal : 0;
    }

    // Top items by REVENUE (not just quantity) in a window
    public function TopRevenueItems($limit = 5, $days = 30, $endDate = null) {
        $endDate   = $endDate ?: date('Y-m-d');
        $startDate = date('Y-m-d', strtotime("-$days days", strtotime($endDate)));
        $cb = $this->companyBranch();

        $this->db->select("mi.materialname, SUM(id.total) as totalrevenue, SUM(id.qty) as totalqty");
        $this->db->from('tbl_invoice_detail id');
        $this->db->join('tbl_invoice i', 'i.idtbl_invoice = id.tbl_invoice_idtbl_invoice');
        $this->db->join('tbl_material_info mi', 'mi.idtbl_material_info = id.tbl_material_info_idtbl_material_info');
        $this->db->where('i.invdate >=', $startDate);
        $this->db->where('i.invdate <=', $endDate);
        $this->db->where('i.status', 1);
        $this->db->where('i.tbl_company_idtbl_company', $cb['company']);
        $this->db->where('i.tbl_company_branch_idtbl_company_branch', $cb['branch']);
        $this->db->group_by('id.tbl_material_info_idtbl_material_info');
        $this->db->order_by('totalrevenue', 'DESC');
        $this->db->limit($limit);
        return $this->db->get()->result();
    }

    // Customers overdue on advance-order balances, worst offenders first
    public function CustomerRiskList() {
        $cb = $this->companyBranch();
        $sql = "SELECT i.idtbl_invoice, i.duedate, i.nettotal,
                    c.idtbl_customer, c.name AS customername, c.contact AS customercontact,
                    IFNULL(pay.paidsofar,0) AS paidsofar,
                    DATEDIFF(CURDATE(), i.duedate) AS dayslate
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
                ORDER BY dayslate DESC";
        return $this->db->query($sql, [$cb['company'], $cb['branch']])->result();
    }

    // Payment method split over a window
    public function PaymentMethodBreakdown($days = 30, $endDate = null) {
        $endDate   = $endDate ?: date('Y-m-d');
        $startDate = date('Y-m-d', strtotime("-$days days", strtotime($endDate)));
        $cb = $this->companyBranch();

        $sql = "SELECT pd.method, SUM(pd.amount) as total, COUNT(*) as txcount
                FROM tbl_invoice_payment_detail pd
                JOIN tbl_invoice_payment p ON p.idtbl_invoice_payment = pd.tbl_invoice_payment_idtbl_invoice_payment
                WHERE p.paydate >= ? AND p.paydate <= ? AND p.status = 1
                AND p.tbl_company_idtbl_company = ? AND p.tbl_company_branch_idtbl_company_branch = ?
                GROUP BY pd.method
                ORDER BY total DESC";
        return $this->db->query($sql, [$startDate, $endDate, $cb['company'], $cb['branch']])->result();
    }

    // Average invoice value: this window vs the window before it
    public function AverageInvoiceValue($days = 30, $endDate = null) {
        $endDate = $endDate ?: date('Y-m-d');
        $cb = $this->companyBranch();

        $curStart  = date('Y-m-d', strtotime("-$days days", strtotime($endDate)));
        $prevEnd   = date('Y-m-d', strtotime('-1 day', strtotime($curStart)));
        $prevStart = date('Y-m-d', strtotime("-$days days", strtotime($prevEnd)));

        $cur  = $this->_avgInvoice($curStart, $endDate, $cb);
        $prev = $this->_avgInvoice($prevStart, $prevEnd, $cb);

        $change = 0;
        if ($prev > 0) { $change = (($cur - $prev) / $prev) * 100; }

        return ['current' => $cur, 'previous' => $prev, 'change' => round($change, 1)];
    }

    private function _avgInvoice($start, $end, $cb) {
        $this->db->select_avg('nettotal');
        $this->db->where('invdate >=', $start);
        $this->db->where('invdate <=', $end);
        $this->db->where('status', 1);
        $this->db->where('tbl_company_idtbl_company', $cb['company']);
        $this->db->where('tbl_company_branch_idtbl_company_branch', $cb['branch']);
        $q = $this->db->get('tbl_invoice')->row();
        return isset($q->nettotal) ? (float)$q->nettotal : 0;
    }

    // Turns the metrics above into plain-language recommendations
    public function SalesAgentInsights($selectedDate = null) {
        $selectedDate = $selectedDate ?: date('Y-m-d');
        $insights = [];

        $trend = $this->RevenueTrend($selectedDate);
        if ($trend['previous'] > 0) {
            if ($trend['change'] <= -10) {
                $insights[] = ['type' => 'danger', 'icon' => 'fa-arrow-down',
                    'text' => "Revenue is down " . abs($trend['change']) . "% vs last month (LKR " . number_format($trend['current'], 2) . " vs LKR " . number_format($trend['previous'], 2) . "). Consider a promotion on slow-moving stock or following up with overdue customers."];
            } elseif ($trend['change'] >= 10) {
                $insights[] = ['type' => 'success', 'icon' => 'fa-arrow-up',
                    'text' => "Revenue is up " . $trend['change'] . "% vs last month (LKR " . number_format($trend['current'], 2) . " vs LKR " . number_format($trend['previous'], 2) . "). Keep stock levels healthy on your top sellers."];
            }
        }

        $risk = $this->CustomerRiskList();
        if (!empty($risk)) {
            $totalOwed = 0;
            foreach ($risk as $r) { $totalOwed += ($r->nettotal - $r->paidsofar); }
            $insights[] = ['type' => 'danger', 'icon' => 'fa-exclamation-circle',
                'text' => count($risk) . " customer(s) have overdue advance-order balances totaling LKR " . number_format($totalOwed, 2) . ". Most overdue: " . htmlspecialchars($risk[0]->customername) . " (" . $risk[0]->dayslate . " days late)."];
        }

        $avgInv = $this->AverageInvoiceValue(30, $selectedDate);
        if ($avgInv['previous'] > 0 && abs($avgInv['change']) >= 10) {
            $direction = $avgInv['change'] > 0 ? 'increased' : 'decreased';
            $insights[] = ['type' => $avgInv['change'] > 0 ? 'success' : 'info', 'icon' => 'fa-receipt',
                'text' => "Average invoice value has $direction by " . abs($avgInv['change']) . "% (now LKR " . number_format($avgInv['current'], 2) . ") over the last 30 days."];
        }

        $top = $this->TopRevenueItems(1, 30, $selectedDate);
        if (!empty($top)) {
            $insights[] = ['type' => 'info', 'icon' => 'fa-star',
                'text' => "Your top revenue earner in the last 30 days is " . htmlspecialchars($top[0]->materialname) . " (LKR " . number_format($top[0]->totalrevenue, 2) . " from " . (int)$top[0]->totalqty . " units)."];
        }

        if (empty($insights)) {
            $insights[] = ['type' => 'info', 'icon' => 'fa-info-circle', 'text' => 'No notable sales trends to report right now — everything looks steady.'];
        }

        return $insights;
    }

    /* =========================================================
     *  INVENTORY AGENT
     * ========================================================= */

    // Items at/below reorder level, enriched with sales velocity + a suggested order qty
    public function ReorderSuggestions($velocityDays = 30) {
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
        $lowStock = $this->db->query($sql)->result();

        if (empty($lowStock)) return [];

        $startDate = date('Y-m-d', strtotime("-$velocityDays days"));
        $endDate   = date('Y-m-d');

        foreach ($lowStock as $item) {
            $this->db->select_sum('id.qty', 'soldqty');
            $this->db->from('tbl_invoice_detail id');
            $this->db->join('tbl_invoice i', 'i.idtbl_invoice = id.tbl_invoice_idtbl_invoice');
            $this->db->where('id.tbl_material_info_idtbl_material_info', $item->idtbl_material_info);
            $this->db->where('i.invdate >=', $startDate);
            $this->db->where('i.invdate <=', $endDate);
            $this->db->where('i.status', 1);
            $q = $this->db->get()->row();
            $sold = isset($q->soldqty) ? (float)$q->soldqty : 0;

            $dailyVelocity = $sold / $velocityDays;
            $item->dailyvelocity = round($dailyVelocity, 2);
            $item->daysofstock   = $dailyVelocity > 0 ? round($item->currentstock / $dailyVelocity, 1) : null;

            // Target = enough stock to cover 2x the velocity window, never less than 2x reorder level
            $targetStock = ceil($dailyVelocity * $velocityDays * 2);
            $targetStock = max($targetStock, $item->reorderlevel * 2);
            $item->suggestedorderqty = max(0, $targetStock - $item->currentstock);
        }
        return $lowStock;
    }

    // Suppliers who can supply a given material (for the "Find Suppliers" action)
    public function SuppliersForMaterial($materialId) {
        $this->db->select('s.idtbl_supplier, s.suppliername, s.primarycontactno, s.email');
        $this->db->from('tbl_supplier_has_tbl_material_info smi');
        $this->db->join('tbl_supplier s', 's.idtbl_supplier = smi.tbl_supplier_idtbl_supplier');
        $this->db->where('smi.tbl_material_info_idtbl_material_info', $materialId);
        $this->db->where('s.status', 1);
        return $this->db->get()->result();
    }

    // Stock on hand that hasn't sold at all in the given window ("dead stock")
    public function DeadStock($days = 90, $limit = 10) {
        $startDate = date('Y-m-d', strtotime("-$days days"));
        $endDate   = date('Y-m-d');

        $sql = "SELECT mi.idtbl_material_info, mi.materialname, IFNULL(SUM(s.qty),0) as currentstock,
                       IFNULL(SUM(s.qty*s.costprice),0) as stockvalue
                FROM tbl_material_info mi
                LEFT JOIN tbl_stock s
                       ON s.tbl_material_info_idtbl_material_info = mi.idtbl_material_info AND s.status = 1
                WHERE mi.status = 1
                AND mi.idtbl_material_info NOT IN (
                    SELECT DISTINCT id.tbl_material_info_idtbl_material_info
                    FROM tbl_invoice_detail id
                    JOIN tbl_invoice i ON i.idtbl_invoice = id.tbl_invoice_idtbl_invoice
                    WHERE i.invdate >= ? AND i.invdate <= ? AND i.status = 1
                )
                GROUP BY mi.idtbl_material_info
                HAVING currentstock > 0
                ORDER BY stockvalue DESC
                LIMIT ?";
        return $this->db->query($sql, [$startDate, $endDate, $limit])->result();
    }

    // Stock valuation grouped by category (at cost and at sale price)
    public function StockValuation() {
        $sql = "SELECT mc.categoryname, SUM(s.qty*s.costprice) as costvalue, SUM(s.qty*s.saleprice) as salevalue, SUM(s.qty) as totalqty
                FROM tbl_stock s
                JOIN tbl_material_info mi ON mi.idtbl_material_info = s.tbl_material_info_idtbl_material_info
                JOIN tbl_material_category mc ON mc.idtbl_material_category = mi.tbl_material_category_idtbl_material_category
                WHERE s.status = 1
                GROUP BY mi.tbl_material_category_idtbl_material_category
                ORDER BY costvalue DESC";
        return $this->db->query($sql)->result();
    }

    public function StockValuationTotal() {
        // Raw SUM(expr) — select_sum() would try to escape "qty*costprice"
        // as if it were a single column name and throw a SQL error, so we
        // pass the expression through with escaping disabled (2nd param false).
        $this->db->select('SUM(qty*costprice) as costvalue, SUM(qty*saleprice) as salevalue', false);
        $this->db->where('status', 1);
        $q = $this->db->get('tbl_stock')->row();
        return [
            'costvalue' => isset($q->costvalue) ? (float)$q->costvalue : 0,
            'salevalue' => isset($q->salevalue) ? (float)$q->salevalue : 0,
        ];
    }

    // Turns the metrics above into plain-language recommendations
    public function InventoryAgentInsights() {
        $insights = [];

        $reorder = $this->ReorderSuggestions();
        if (!empty($reorder)) {
            $urgent = array_filter($reorder, function ($i) { return $i->currentstock <= 0; });
            if (!empty($urgent)) {
                $names = array_map(function ($i) { return $i->materialname; }, array_slice($urgent, 0, 3));
                $insights[] = ['type' => 'danger', 'icon' => 'fa-times-circle',
                    'text' => count($urgent) . " item(s) are completely OUT OF STOCK: " . implode(', ', $names) . (count($urgent) > 3 ? '...' : '') . ". Reorder immediately."];
            }
            $insights[] = ['type' => 'warning', 'icon' => 'fa-exclamation-triangle',
                'text' => count($reorder) . " item(s) are at or below reorder level. Suggested order quantities are listed below based on recent sales velocity."];
        }

        $dead = $this->DeadStock(90, 5);
        if (!empty($dead)) {
            $value = array_sum(array_map(function ($i) { return $i->stockvalue; }, $dead));
            $insights[] = ['type' => 'info', 'icon' => 'fa-snowflake',
                'text' => count($dead) . " item(s) haven't sold in 90+ days, tying up roughly LKR " . number_format($value, 2) . " in stock. Consider a clearance promotion."];
        }

        $val = $this->StockValuationTotal();
        if ($val['costvalue'] > 0) {
            $margin = $val['salevalue'] > 0 ? round((($val['salevalue'] - $val['costvalue']) / $val['salevalue']) * 100, 1) : 0;
            $insights[] = ['type' => 'success', 'icon' => 'fa-warehouse',
                'text' => "Total stock on hand is worth LKR " . number_format($val['costvalue'], 2) . " at cost (potential sale value LKR " . number_format($val['salevalue'], 2) . ", ~$margin% margin)."];
        }

        if (empty($insights)) {
            $insights[] = ['type' => 'info', 'icon' => 'fa-info-circle', 'text' => 'Stock levels look healthy — no urgent inventory actions right now.'];
        }

        return $insights;
    }
}