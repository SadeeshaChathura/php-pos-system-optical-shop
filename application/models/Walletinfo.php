<?php
class Walletinfo extends CI_Model{

    public function GetWalletSummary(){
        $today      = date('Y-m-d');
        $monthStart = date('Y-m-01');
        $monthEnd   = date('Y-m-t');

        $obj = new stdClass();

        // ---------- Money received (all-time), split by method ----------
        $this->db->select("
            SUM(CASE WHEN method=1 THEN amount ELSE 0 END) AS cashin,
            SUM(CASE WHEN method=2 THEN amount ELSE 0 END) AS bankin,
            SUM(CASE WHEN method=3 THEN amount ELSE 0 END) AS chequein
        ", false);
        $this->db->from('tbl_invoice_payment_detail');
        $this->db->where('status !=', 3);
        $r = $this->db->get()->row();
        $obj->cashin   = $r->cashin   ? $r->cashin   : 0;
        $obj->bankin   = $r->bankin   ? $r->bankin   : 0;
        $obj->chequein = $r->chequein ? $r->chequein : 0;

        // ---------- Expenses: method split + today/month/all-time in ONE query ----------
        $sqlExpense = "
            SELECT
                SUM(CASE WHEN paymentmethod=1 THEN amount ELSE 0 END) AS cashexp,
                SUM(CASE WHEN paymentmethod=2 THEN amount ELSE 0 END) AS bankexp,
                SUM(CASE WHEN paymentmethod=3 THEN amount ELSE 0 END) AS chequeexp,
                SUM(CASE WHEN expensedate = ? THEN amount ELSE 0 END) AS todayexpense,
                SUM(CASE WHEN expensedate BETWEEN ? AND ? THEN amount ELSE 0 END) AS monthexpense,
                SUM(amount) AS totalexpense
            FROM tbl_expense
            WHERE status != 3
        ";
        $r = $this->db->query($sqlExpense, array($today, $monthStart, $monthEnd))->row();
        $obj->cashexp      = $r->cashexp      ? $r->cashexp      : 0;
        $obj->bankexp      = $r->bankexp      ? $r->bankexp      : 0;
        $obj->chequeexp    = $r->chequeexp    ? $r->chequeexp    : 0;
        $obj->todayexpense = $r->todayexpense ? $r->todayexpense : 0;
        $obj->monthexpense = $r->monthexpense ? $r->monthexpense : 0;
        $obj->totalexpense = $r->totalexpense ? $r->totalexpense : 0;

        // ---------- Derived wallet balances ----------
        $obj->cashinhand     = $obj->cashin - $obj->cashexp;
        $obj->bankbalance    = ($obj->bankin + $obj->chequein) - ($obj->bankexp + $obj->chequeexp);
        $obj->totalavailable = $obj->cashinhand + $obj->bankbalance;

        // ---------- Sales: today/month/all-time in ONE query ----------
        $sqlSales = "
            SELECT
                SUM(CASE WHEN invdate = ? THEN nettotal ELSE 0 END) AS todaysales,
                SUM(CASE WHEN invdate BETWEEN ? AND ? THEN nettotal ELSE 0 END) AS monthsales,
                SUM(nettotal) AS totalsales
            FROM tbl_invoice
            WHERE status != 3
        ";
        $r = $this->db->query($sqlSales, array($today, $monthStart, $monthEnd))->row();
        $obj->todaysales = $r->todaysales ? $r->todaysales : 0;
        $obj->monthsales = $r->monthsales ? $r->monthsales : 0;
        $obj->totalsales = $r->totalsales ? $r->totalsales : 0;

        // ---------- Payments received: today/month/all-time in ONE query ----------
        $sqlReceived = "
            SELECT
                SUM(CASE WHEN paydate = ? THEN nettotal ELSE 0 END) AS todayreceived,
                SUM(CASE WHEN paydate BETWEEN ? AND ? THEN nettotal ELSE 0 END) AS monthreceived,
                SUM(nettotal) AS totalreceived
            FROM tbl_invoice_payment
            WHERE status != 3
        ";
        $r = $this->db->query($sqlReceived, array($today, $monthStart, $monthEnd))->row();
        $obj->todayreceived = $r->todayreceived ? $r->todayreceived : 0;
        $obj->monthreceived = $r->monthreceived ? $r->monthreceived : 0;
        $obj->totalreceived = $r->totalreceived ? $r->totalreceived : 0;

        // ---------- Net movement ----------
        $obj->todaynet = $obj->todayreceived - $obj->todayexpense;
        $obj->monthnet = $obj->monthreceived - $obj->monthexpense;

        // ---------- Receivables (Sales invoiced vs actually received) ----------
        $obj->receivables = $obj->totalsales - $obj->totalreceived;
        if($obj->receivables < 0){ $obj->receivables = 0; }

        // ---------- Stock valuation ----------
        $this->db->select("SUM(qty*costprice) AS costvalue, SUM(qty*saleprice) AS salevalue", false);
        $this->db->from('tbl_stock');
        $this->db->where('status', 1);
        $r = $this->db->get()->row();
        $obj->stockcostvalue = $r->costvalue ? $r->costvalue : 0;
        $obj->stocksalevalue = $r->salevalue ? $r->salevalue : 0;

        // ---------- Total Purchases (GRN) ----------
        $this->db->select_sum('total');
        $this->db->from('tbl_grn');
        $this->db->where('status !=', 3);
        $obj->totalpurchases = $this->db->get()->row()->total ?: 0;

        return $obj;
    }
}