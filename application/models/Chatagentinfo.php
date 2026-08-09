<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Chatagentinfo
 * ------------------------------------------------------------------
 * Free, rule-based "chat" over your own ERP data. No external AI API —
 * every answer comes from keyword-matching the question against a
 * fixed set of intents, then running the same style of queries used
 * by Aiagentinfo (which it reuses directly where possible).
 *
 * This will only understand phrasings close to the examples below.
 * If you later want free-form natural language, this model is the
 * seam where you'd swap in an LLM call — everything else (controller,
 * view, chat UI) stays the same.
 * ------------------------------------------------------------------
 */
class Chatagentinfo extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->model('Aiagentinfo');
        $this->load->helper('url');
    }

    private function companyBranch() {
        return [
            'company' => $this->session->userdata('companyid'),
            'branch'  => $this->session->userdata('branchid'),
        ];
    }

    /* =========================================================
     *  ENTRY POINT
     * ========================================================= */

    public function AnswerQuery($message) {
        $msg = strtolower(trim($message));

        if ($msg === '') {
            return $this->_reply('Ask me something like "what are my top selling products" or "which items need reordering".');
        }

        if (preg_match('/^(hi|hello|hey|hiya|yo|good morning|good afternoon|good evening|greetings)\b/', $msg)) {
            return $this->_greeting();
        }
        if ($this->_any($msg, ['thank you', 'thanks', 'thx', 'thankyou', 'cheers', 'appreciate it'])) {
            return $this->_thanks();
        }

        // Ordered intent checks — more specific phrases are checked
        // before generic ones (e.g. "how many suppliers" must be
        // caught before the generic "how many <item>" stock lookup,
        // and "how many sales" must be caught before the generic
        // "how many <item>" check too).
        if ($this->_any($msg, ['how many suppliers', 'total suppliers', 'number of suppliers'])) {
            return $this->_supplierCount();
        }
        if ($this->_any($msg, ['how many customers', 'total customers', 'number of customers'])) {
            return $this->_customerCount();
        }
        // "list" intents are checked before "who supplies"/"supplier for"
        // below, since a phrase like "show my supplier list" mentions
        // "supplier" but is asking for the roster, not a per-item lookup.
        if ($this->_any($msg, ['customer list', 'list of customers', 'list all customers', 'show all customers',
            'show my customers', 'show my customer list', 'my customer list', 'view customers', 'view my customers'])) {
            return $this->_customerList();
        }
        if ($this->_any($msg, ['supplier list', 'list of suppliers', 'list all suppliers', 'show all suppliers',
            'show my suppliers', 'show my supplier list', 'my supplier list', 'view suppliers', 'view my suppliers'])) {
            return $this->_supplierList();
        }
        if ($this->_any($msg, ['who supplies', 'supplier for', 'suppliers for', 'which supplier'])) {
            return $this->_supplierForItem($msg);
        }
        if ($this->_any($msg, ['how many sales', 'number of sales', 'sales count', 'count of sales'])) {
            return $this->_salesCount($msg);
        }
        if ($this->_any($msg, ['all products', 'list of products', 'list all products', 'show all products',
            'product list', 'all items', 'list of items', 'show me all products', 'all my products'])) {
            return $this->_allProducts();
        }
        if ($this->_any($msg, ['how to create a sale', 'steps to create a sale', 'steps of how to create a sale',
                'how do i create a sale', 'how to make a sale', 'how do i make a sale', 'how to create an invoice',
                'how do i create an invoice', 'steps of creating a sale', 'how to sell', 'how do i sell'])
            || ($this->_any($msg, ['want to create', 'want to make', 'create a new', 'make a new', 'create an', 'make an'])
                && $this->_any($msg, ['sale', 'invoice']))) {
            return $this->_howToCreateSale();
        }
        if ($this->_any($msg, ['how to print', 'how do i print', 'print a report', 'print report', 'printing a report'])) {
            return $this->_howToPrintReport();
        }
        if ($this->_any($msg, ['sale summary', 'sales summary', 'month end sales', 'end of month sales',
            'monthly sales summary', 'sales analysis', 'analyse my sales', 'analyze my sales', 'analyse sales',
            'analyze sales'])) {
            return $this->_saleSummary();
        }
        if ($this->_any($msg, ['stock summary', 'inventory summary', 'monthly stock summary', 'stock analysis',
            'analyse my stock', 'analyze my stock', 'analyse stock', 'analyze stock'])) {
            return $this->_stockSummary();
        }
        if ($this->_any($msg, ['fast moving', 'fastest moving', 'quick moving', 'best moving'])) {
            return $this->_fastMoving($msg);
        }
        if ($this->_any($msg, ['out of stock', 'zero stock', 'no stock left'])) {
            return $this->_outOfStock();
        }
        if ($this->_any($msg, ['reorder', 'restock', 'running low', 'running out', 'need to order', 'low stock',
            'should i order', 'should we order', 'how much should i order', 'how much to order', 'qty to order',
            'quantity to order', 'how many should i order', 'order next month', 'order for next month'])) {
            return $this->_reorder();
        }
        if ($this->_any($msg, ['dead stock', 'not selling', 'slow moving', 'stagnant', 'not moved'])) {
            return $this->_deadStock();
        }
        if ($this->_any($msg, ['stock value', 'inventory value', 'stock worth', 'warehouse value', 'total stock value'])) {
            return $this->_stockValue();
        }
        if ($this->_any($msg, ['top selling', 'best seller', 'best selling', 'top product', 'top item', 'top revenue', 'top 5', 'top 10'])) {
            return $this->_topSellers($msg);
        }
        if ($this->_any($msg, ['overdue', 'owes', 'unpaid', 'pending payment', 'balance due', 'advance order'])) {
            return $this->_overdue();
        }
        if ($this->_any($msg, ['average invoice', 'average sale', 'average order value', 'average bill'])) {
            return $this->_avgInvoice($msg);
        }
        if ($this->_any($msg, ['payment method', 'how do customers pay', 'cash or card', 'payment split', 'payment breakdown'])) {
            return $this->_paymentSplit($msg);
        }
        // Direct revenue phrases, OR a looser fallback: any mention of
        // "sales"/"revenue"/"turnover" alongside a recognizable date word
        // (today, this month, etc.) — catches loosely worded questions
        // like "analyse of my sales on this month".
        if ($this->_any($msg, ['revenue', 'how much did we sell', 'total sales', 'sales this', 'sales last',
                'turnover', 'how much did we make', 'show me today\'s sales', 'show me this month\'s sales', 'show me this month sale', 'puka sududa', 'show me monthly sales', 'daily sales need', 'show me daily sales',  'today sales', 'this month sales', 'monthly sales', 'sales figure'])
            || ($this->_any($msg, ['sales', 'revenue', 'turnover', 'earnings']) && $this->_hasDateWord($msg))) {
            return $this->_revenue($msg);
        }
        if ($this->_any($msg, ['list of menus', 'all menus', 'menus and function', 'menus and functions',
            'functions of the system', 'function of the system', 'what menus', 'show me the menus',
            'system menus', 'available menus', 'menu list'])) {
            return $this->_menuList();
        }
        if ($this->_any($msg, ['go to', 'take me to', 'navigate to', 'where is the', 'link for', 'open the',
            'show me the page for', 'link to'])) {
            return $this->_menuLink($msg);
        }
        if ($this->_any($msg, ['how many', 'stock of', 'do we have', 'units of', 'qty of', 'quantity of'])) {
            return $this->_itemStock($msg);
        }

        /* ---------------------------------------------------
         *  Newly added intents (kept below the original ones
         *  so none of the existing behaviour above is changed)
         * --------------------------------------------------- */

        // Quick report generation links
        if ($this->_any($msg, ['generate stock report'])) {
            return $this->_stockReportLink();
        }
        if ($this->_any($msg, ['generate sales report'])) {
            return $this->_salesReportLink();
        }
        if ($this->_any($msg, ['generate grn report'])) {
            return $this->_grnReportLink();
        }

        // Combined "today summary" (sales + stock + reorder level)
        if ($this->_any($msg, ['today summary', "today's summary"])) {
            return $this->_todaySummary();
        }

        // Extra daily/today sales phrasings
        if ($this->_any($msg, ['my today sale', 'today sale', 'daily sales', 'my daily sales', 'daily sale'])) {
            return $this->_revenue($msg);
        }

        // Stock status / stock updates
        if ($this->_any($msg, ['stock status', 'my stock updates', 'stock update', 'stock updates'])) {
            return $this->_stockSummary();
        }

        // New invoice / new sale (POS dashboard) quick link
        if ($this->_any($msg, ['new invoice', 'new sale', 'pos dashboard'])) {
            return $this->_newInvoiceLink();
        }

        // New customer
        if ($this->_any($msg, ['new customer', 'create a new customer', 'add a new customer', 'add new customer'])) {
            return $this->_newCustomerLink();
        }

        // New supplier
        if ($this->_any($msg, ['new supplier', 'create a new supplier', 'add a new supplier', 'add new supplier'])) {
            return $this->_newSupplierLink();
        }

        // My info / my business info (company info)
        if ($this->_any($msg, ['my info', 'my business info', 'business info', 'company info'])) {
            return $this->_companyInfoLink();
        }

        // Reports / my reports / generate reports (all reports links)
        if ($this->_any($msg, ['my reports', 'generate reports', 'all reports', 'reports list', 'show reports', 'reports'])) {
            return $this->_allReportsLinks();
        }

        // Purchase order creation
        if ($this->_any($msg, ['create a purchase order', 'create new po', 'create po', 'purchase order'])) {
            return $this->_purchaseOrderHelp();
        }

        // GRN / Goods Receive Note creation
        if ($this->_any($msg, ['goods receive note', 'create grn', 'create new grn', 'create good receive note', 'grn'])) {
            return $this->_grnHelp();
        }

        // Create a new user account
        if ($this->_any($msg, ['how to create user account', 'need to create a new user', 'steps to create a new user',
            'create a new user', 'create user account'])) {
            return $this->_createUserAccountHelp();
        }

        // Change user privileges
        if ($this->_any($msg, ['user accounts privileges', 'need to change privileges', 'how to change privileges',
            'change privileges', 'user privileges', 'privileges of existing user'])) {
            return $this->_privilegesHelp();
        }

        return $this->_reply(
            "I'm not sure how to answer that yet. Try asking about: revenue, top/fast/slow-moving sellers, " .
            "reorder suggestions, stock or sale summaries, overdue customers, payment methods, suppliers for " .
            "an item, how to create a sale or print a report, or \"go to <menu name>\" to jump to a page."
        );
    }

    /* =========================================================
     *  INTENT HANDLERS
     * ========================================================= */

    private function _greeting() {
        $name = isset($_SESSION['name']) ? $_SESSION['name'] : null;
        $greetingName = $name ? ucfirst($name) : 'there';
        return $this->_reply(
            "Hi $greetingName! I can help with sales figures, stock levels, reorder suggestions, quick summaries, " .
            "or finding your way to a page. Try one of the suggestions below, or just type a question."
        );
    }

    private function _thanks() {
        $replies = [
            "You're welcome! Let me know if you need anything else.",
            "Anytime! Happy to help with more questions.",
            "No problem at all — ask away if there's more you need.",
        ];
        return $this->_reply($replies[array_rand($replies)]);
    }

    private function _revenue($msg) {
        list($start, $end, $label) = $this->_dateRange($msg);
        $total = $this->_sumNetTotal($start, $end);
        $count = $this->_invoiceCount($start, $end);

        if ($count == 0) {
            return $this->_reply("No sales recorded for $label yet.");
        }
        $avg = $total / $count;
        return $this->_reply(
            "You made LKR " . number_format($total, 2) . " in revenue from $count invoice(s) during $label " .
            "— an average of LKR " . number_format($avg, 2) . " per sale."
        );
    }

    private function _topSellers($msg) {
        list($start, $end, $label) = $this->_dateRange($msg);
        $days = max(1, round((strtotime($end) - strtotime($start)) / 86400));

        preg_match('/top\s+(\d+)/', $msg, $m);
        $limit = isset($m[1]) ? (int)$m[1] : 5;
        $limit = max(1, min($limit, 20));

        $items = $this->Aiagentinfo->TopRevenueItems($limit, $days, $end);
        if (empty($items)) {
            return $this->_reply("No sales data found for $label.");
        }

        $lines = [];
        $i = 1;
        foreach ($items as $it) {
            $lines[] = $i . ". " . $it->materialname . " — LKR " . number_format($it->totalrevenue, 2) . " (" . (int)$it->totalqty . " units)";
            $i++;
        }
        return $this->_reply("Top " . count($items) . " sellers by revenue for $label:\n" . implode("\n", $lines), $items);
    }

    private function _reorder() {
        $items = $this->Aiagentinfo->ReorderSuggestions();
        if (empty($items)) {
            return $this->_reply("Nothing needs reordering right now — all stock levels look healthy.");
        }
        $lines = [];
        foreach (array_slice($items, 0, 10) as $it) {
            $lines[] = "- " . $it->materialname . ": " . (int)$it->currentstock . " in stock (reorder level " .
                (int)$it->reorderlevel . "), suggest ordering " . (int)$it->suggestedorderqty;
        }
        $more = count($items) > 10 ? "\n...and " . (count($items) - 10) . " more." : "";
        return $this->_reply(count($items) . " item(s) need reordering:\n" . implode("\n", $lines) . $more, $items);
    }

    private function _outOfStock() {
        $items = $this->Aiagentinfo->ReorderSuggestions();
        $out = array_values(array_filter($items, function ($i) { return $i->currentstock <= 0; }));
        if (empty($out)) {
            return $this->_reply("Nothing is completely out of stock right now.");
        }
        $names = array_map(function ($i) { return $i->materialname; }, $out);
        return $this->_reply(count($out) . " item(s) are out of stock: " . implode(', ', $names), $out);
    }

    private function _deadStock() {
        $items = $this->Aiagentinfo->DeadStock(90, 10);
        if (empty($items)) {
            return $this->_reply("No dead stock — everything has sold at least once in the last 90 days.");
        }
        $lines = [];
        foreach ($items as $it) {
            $lines[] = "- " . $it->materialname . ": " . (int)$it->currentstock . " units, worth LKR " . number_format($it->stockvalue, 2);
        }
        return $this->_reply(count($items) . " item(s) haven't sold in 90+ days:\n" . implode("\n", $lines), $items);
    }

    private function _stockValue() {
        $val = $this->Aiagentinfo->StockValuationTotal();
        if ($val['costvalue'] <= 0) {
            return $this->_reply("There's no stock on hand to value right now.");
        }
        $margin = $val['salevalue'] > 0 ? round((($val['salevalue'] - $val['costvalue']) / $val['salevalue']) * 100, 1) : 0;
        return $this->_reply(
            "Total stock on hand is worth LKR " . number_format($val['costvalue'], 2) . " at cost (LKR " .
            number_format($val['salevalue'], 2) . " at sale price, ~$margin% margin)."
        );
    }

    private function _overdue() {
        $risk = $this->Aiagentinfo->CustomerRiskList();
        if (empty($risk)) {
            return $this->_reply("No customers have overdue advance-order balances right now.");
        }
        $lines = [];
        $total = 0;
        foreach ($risk as $r) {
            $balance = $r->nettotal - $r->paidsofar;
            $total += $balance;
            $lines[] = "- " . $r->customername . ": LKR " . number_format($balance, 2) . " (" . $r->dayslate . " days late)";
        }
        return $this->_reply(count($risk) . " customer(s) overdue, totaling LKR " . number_format($total, 2) . ":\n" . implode("\n", $lines), $risk);
    }

    private function _avgInvoice($msg) {
        list($start, $end, $label) = $this->_dateRange($msg);
        $days = max(1, round((strtotime($end) - strtotime($start)) / 86400));

        $avg = $this->Aiagentinfo->AverageInvoiceValue($days, $end);
        if ($avg['current'] <= 0) {
            return $this->_reply("No invoices found for $label.");
        }
        $dir = $avg['change'] > 0 ? 'up' : ($avg['change'] < 0 ? 'down' : 'flat');
        return $this->_reply(
            "Average invoice value for $label is LKR " . number_format($avg['current'], 2) .
            " ($dir " . abs($avg['change']) . "% vs the period before)."
        );
    }

    private function _paymentSplit($msg) {
        list($start, $end, $label) = $this->_dateRange($msg);
        $days = max(1, round((strtotime($end) - strtotime($start)) / 86400));

        $rows = $this->Aiagentinfo->PaymentMethodBreakdown($days, $end);
        if (empty($rows)) {
            return $this->_reply("No payment data found for $label.");
        }
        $labels = [1 => 'Cash', 2 => 'Card / Cheque'];
        $lines = [];
        foreach ($rows as $r) {
            $name = $labels[$r->method] ?? ('Method #' . $r->method);
            $lines[] = "- $name: LKR " . number_format($r->total, 2) . " (" . (int)$r->txcount . " transactions)";
        }
        return $this->_reply("Payment method split for $label:\n" . implode("\n", $lines), $rows);
    }

    private function _customerCount() {
        $this->db->where('status', 1);
        $count = $this->db->count_all_results('tbl_customer');
        return $this->_reply("You have $count active customer(s) on record.");
    }

    private function _supplierCount() {
        $this->db->where('status', 1);
        $count = $this->db->count_all_results('tbl_supplier');
        return $this->_reply("You have $count active supplier(s) on record.");
    }

    // tbl_customer's real columns are "name" and "contact" (confirmed
    // from the schema dump — not "customername"/"customercontact" as
    // originally assumed). idtbl_customer 1 is the built-in "GUEST
    // CUSTOMER" row with status 3, so the status = 1 filter here already
    // naturally excludes it from the active list.
    private function _customerList() {
        $this->db->select('name, contact');
        $this->db->where('status', 1);
        $this->db->order_by('name', 'ASC');
        $customers = $this->db->get('tbl_customer')->result();
        $count = count($customers);

        if ($count == 0) {
            return $this->_reply(
                "You don't have any active customers on record yet.",
                null,
                [['label' => 'Customer List', 'url' => base_url() . 'Customer']]
            );
        }
        if ($count > 15) {
            return $this->_reply(
                "You have $count active customer(s) on record — that's too many to list here. Use the Customer List page to browse or search them.",
                null,
                [['label' => 'Customer List', 'url' => base_url() . 'Customer']]
            );
        }
        $lines = array_map(function ($c) {
            return "- " . $c->name . ($c->contact ? ' (' . $c->contact . ')' : '');
        }, $customers);
        return $this->_reply(
            "You have $count customer(s):\n" . implode("\n", $lines),
            $customers,
            [['label' => 'Customer List', 'url' => base_url() . 'Customer']]
        );
    }

    private function _supplierList() {
        $this->db->select('suppliername, primarycontactno');
        $this->db->where('status', 1);
        $this->db->order_by('suppliername', 'ASC');
        $suppliers = $this->db->get('tbl_supplier')->result();
        $count = count($suppliers);

        if ($count == 0) {
            return $this->_reply(
                "You don't have any active suppliers on record yet.",
                null,
                [['label' => 'Suppliers', 'url' => base_url() . 'Supplier']]
            );
        }
        if ($count > 15) {
            return $this->_reply(
                "You have $count active supplier(s) on record — that's too many to list here. Use the Suppliers page to browse or search them.",
                null,
                [['label' => 'Suppliers', 'url' => base_url() . 'Supplier']]
            );
        }
        $lines = array_map(function ($s) {
            return "- " . $s->suppliername . ($s->primarycontactno ? ' (' . $s->primarycontactno . ')' : '');
        }, $suppliers);
        return $this->_reply(
            "You have $count supplier(s):\n" . implode("\n", $lines),
            $suppliers,
            [['label' => 'Suppliers', 'url' => base_url() . 'Supplier']]
        );
    }

    private function _allProducts() {
        $this->db->where('status', 1);
        $count = $this->db->count_all_results('tbl_material_info');
        return $this->_reply(
            "You have $count active product(s) in your catalog. That's too many to list here — use the Product Detail page to browse or search them.",
            null,
            [['label' => 'Product Detail', 'url' => base_url() . 'Materialdetail']]
        );
    }

    private function _itemStock($msg) {
        $term = $this->_extractSearchTerm($msg, ['how many', 'do we have', 'in stock', 'stock of', 'units of',
            'qty of', 'quantity of', 'left', 'on hand', 'of', 'the', 'a ', 'an ', 'for', '?']);

        $material = $this->_findMaterial($term);
        if (!$material) {
            return $this->_reply('I couldn\'t find an item matching "' . $term . '". Try the exact product name from your catalog.');
        }

        $this->db->select_sum('qty');
        $this->db->where('tbl_material_info_idtbl_material_info', $material->idtbl_material_info);
        $this->db->where('status', 1);
        $q = $this->db->get('tbl_stock')->row();
        $qty = isset($q->qty) ? (float)$q->qty : 0;

        return $this->_reply('You currently have ' . (int)$qty . ' unit(s) of "' . $material->materialname . '" in stock.');
    }

    private function _supplierForItem($msg) {
        $term = $this->_extractSearchTerm($msg, ['who supplies', 'supplier for', 'suppliers for',
            'which supplier supplies', 'which supplier', 'supplies', 'for', 'of', '?']);

        $material = $this->_findMaterial($term);
        if (!$material) {
            return $this->_reply('I couldn\'t find an item matching "' . $term . '". Try the exact product name.');
        }

        $suppliers = $this->Aiagentinfo->SuppliersForMaterial($material->idtbl_material_info);

        // Fallback: if nothing is linked directly to this item, check
        // suppliers linked at the category level instead.
        if (empty($suppliers)) {
            $this->db->select('tbl_material_category_idtbl_material_category');
            $this->db->where('idtbl_material_info', $material->idtbl_material_info);
            $cat = $this->db->get('tbl_material_info')->row();

            if ($cat) {
                $this->db->select('s.idtbl_supplier, s.suppliername, s.primarycontactno, s.email');
                $this->db->from('tbl_supplier_has_tbl_material_category smc');
                $this->db->join('tbl_supplier s', 's.idtbl_supplier = smc.tbl_supplier_idtbl_supplier');
                $this->db->where('smc.tbl_material_category_idtbl_material_category', $cat->tbl_material_category_idtbl_material_category);
                $this->db->where('s.status', 1);
                $suppliers = $this->db->get()->result();
            }
        }

        if (empty($suppliers)) {
            return $this->_reply('No suppliers are linked to "' . $material->materialname . '" yet.');
        }

        $names = array_map(function ($s) {
            return $s->suppliername . ($s->primarycontactno ? ' (' . $s->primarycontactno . ')' : '');
        }, $suppliers);

        return $this->_reply('Suppliers for "' . $material->materialname . '": ' . implode(', ', $names), $suppliers);
    }

    private function _salesCount($msg) {
        list($start, $end, $label) = $this->_dateRange($msg);
        $count = $this->_invoiceCount($start, $end);
        $total = $this->_sumNetTotal($start, $end);

        if ($count == 0) {
            return $this->_reply("No sales recorded for $label.");
        }
        return $this->_reply("You had $count sale(s) $label, totaling LKR " . number_format($total, 2) . ".");
    }

    private function _fastMoving($msg) {
        list($start, $end, $label) = $this->_dateRange($msg);
        $cb = $this->companyBranch();

        $this->db->select("mi.materialname, SUM(id.qty) as totalqty, SUM(id.total) as totalrevenue");
        $this->db->from('tbl_invoice_detail id');
        $this->db->join('tbl_invoice i', 'i.idtbl_invoice = id.tbl_invoice_idtbl_invoice');
        $this->db->join('tbl_material_info mi', 'mi.idtbl_material_info = id.tbl_material_info_idtbl_material_info');
        $this->db->where('i.invdate >=', $start);
        $this->db->where('i.invdate <=', $end);
        $this->db->where('i.status', 1);
        $this->db->where('i.tbl_company_idtbl_company', $cb['company']);
        $this->db->where('i.tbl_company_branch_idtbl_company_branch', $cb['branch']);
        $this->db->group_by('id.tbl_material_info_idtbl_material_info');
        $this->db->order_by('totalqty', 'DESC');
        $this->db->limit(5);
        $items = $this->db->get()->result();

        if (empty($items)) {
            return $this->_reply("No sales data found for $label.");
        }
        $lines = [];
        $i = 1;
        foreach ($items as $it) {
            $lines[] = $i . ". " . $it->materialname . " — " . (int)$it->totalqty . " units sold (LKR " . number_format($it->totalrevenue, 2) . ")";
            $i++;
        }
        return $this->_reply("Fastest moving items for $label (by quantity sold):\n" . implode("\n", $lines), $items);
    }

    private function _saleSummary() {
        $trend = $this->Aiagentinfo->RevenueTrend();
        $avg = $this->Aiagentinfo->AverageInvoiceValue(30);
        $top = $this->Aiagentinfo->TopRevenueItems(1, 30);
        $risk = $this->Aiagentinfo->CustomerRiskList();

        $overdueTotal = 0;
        foreach ($risk as $r) {
            $overdueTotal += ($r->nettotal - $r->paidsofar);
        }

        $lines = [];
        $lines[] = "Revenue this month: LKR " . number_format($trend['current'], 2) .
            " (" . ($trend['change'] >= 0 ? '+' : '') . $trend['change'] . "% vs last month)";
        $lines[] = "Average invoice value (last 30 days): LKR " . number_format($avg['current'], 2);
        if (!empty($top)) {
            $lines[] = "Top seller: " . $top[0]->materialname . " (LKR " . number_format($top[0]->totalrevenue, 2) . ")";
        }
        $lines[] = !empty($risk)
            ? count($risk) . " overdue customer(s), totaling LKR " . number_format($overdueTotal, 2)
            : "No overdue customer balances.";

        return $this->_reply("Sales summary:\n" . implode("\n", $lines));
    }

    private function _stockSummary() {
        $reorder = $this->Aiagentinfo->ReorderSuggestions();
        $outOfStock = array_filter($reorder, function ($i) { return $i->currentstock <= 0; });
        $dead = $this->Aiagentinfo->DeadStock(90, 1000);
        $val = $this->Aiagentinfo->StockValuationTotal();

        $lines = [];
        $lines[] = "Stock value: LKR " . number_format($val['costvalue'], 2) . " at cost (LKR " .
            number_format($val['salevalue'], 2) . " at sale price)";
        $lines[] = count($reorder) . " item(s) at/below reorder level, " . count($outOfStock) . " completely out of stock";
        $lines[] = count($dead) . " item(s) haven't sold in the last 90 days";

        return $this->_reply("Stock summary:\n" . implode("\n", $lines));
    }

    private function _howToCreateSale() {
        return $this->_reply(
            "To create a new sale:\n" .
            "1. Go to Point of Sale > New Invoice.\n" .
            "2. Search for the customer, or use the guest customer.\n" .
            "3. Add items to the cart by searching the product name or scanning a batch.\n" .
            "4. Adjust quantity or discount if needed.\n" .
            "5. Enter the payment (cash or card/cheque) and confirm.\n" .
            "6. The invoice is generated and stock is deducted automatically.",
            null,
            [['label' => 'New Invoice (POS)', 'url' => base_url() . 'Directsale']]
        );
    }

    private function _howToPrintReport() {
        return $this->_reply(
            "To print a report:\n" .
            "1. Open the report you need — e.g. Sales Report or GRN Report, or Invoice History for a single sale.\n" .
            "2. Set your date range or filters at the top of the page.\n" .
            "3. Use the Print button on that page (usually top-right of the table) — it opens your browser's print dialog.\n" .
            "4. For a single invoice, open it from Invoice History and use its Print/View action.",
            null,
            [
                ['label' => 'Sales Report', 'url' => base_url() . 'RptSales'],
                ['label' => 'GRN Report', 'url' => base_url() . 'RptGRN'],
                ['label' => 'Invoice History', 'url' => base_url() . 'Invoiceview'],
            ]
        );
    }

    /* ---------------------------------------------------------
     *  NEWLY ADDED HANDLERS
     * --------------------------------------------------------- */

    // "generate stock report"
    private function _stockReportLink() {
        return $this->_reply(
            "Here's your stock report — set the date range or filters at the top, then use the Print button to generate it.",
            null,
            [['label' => 'Item Stock', 'url' => base_url() . 'Rptmatstock']]
        );
    }

    // "generate sales report"
    private function _salesReportLink() {
        return $this->_reply(
            "Here's your sales report — set your date range at the top, then use the Print button to generate it.",
            null,
            [['label' => 'Sales Report', 'url' => base_url() . 'RptSales']]
        );
    }

    // "generate grn report"
    private function _grnReportLink() {
        return $this->_reply(
            "Here's your GRN report — set your date range at the top, then use the Print button to generate it.",
            null,
            [['label' => 'GRN Report', 'url' => base_url() . 'RptGRN']]
        );
    }

    // "today summary" — a combined sales / stock / reorder level snapshot
    private function _todaySummary() {
        $trend = $this->Aiagentinfo->RevenueTrend();
        $todayTotal = $this->_sumNetTotal(date('Y-m-d'), date('Y-m-d'));
        $todayCount = $this->_invoiceCount(date('Y-m-d'), date('Y-m-d'));
        $reorder = $this->Aiagentinfo->ReorderSuggestions();
        $outOfStock = array_filter($reorder, function ($i) { return $i->currentstock <= 0; });
        $val = $this->Aiagentinfo->StockValuationTotal();

        $lines = [];
        $lines[] = "Today's sales: LKR " . number_format($todayTotal, 2) . " from $todayCount invoice(s)";
        $lines[] = "Revenue this month: LKR " . number_format($trend['current'], 2) .
            " (" . ($trend['change'] >= 0 ? '+' : '') . $trend['change'] . "% vs last month)";
        $lines[] = "Stock value: LKR " . number_format($val['costvalue'], 2) . " at cost";
        $lines[] = count($reorder) . " item(s) at/below reorder level, " . count($outOfStock) . " completely out of stock";

        return $this->_reply("Today's summary:\n" . implode("\n", $lines));
    }

    // "new invoice" / "new sale" / "pos dashboard"
    private function _newInvoiceLink() {
        return $this->_reply(
            "Here's a quick link to start a new sale:",
            null,
            [['label' => 'New Invoice (POS)', 'url' => base_url() . 'Directsale']]
        );
    }

    // "new customer" / "create a new customer"
    private function _newCustomerLink() {
        return $this->_reply(
            "You can add a new customer from the Customer List page — use the Add button there.",
            null,
            [['label' => 'Customer List', 'url' => base_url() . 'Customer']]
        );
    }

    // "new supplier" / "create a new supplier"
    private function _newSupplierLink() {
        return $this->_reply(
            "You can add a new supplier from the Suppliers page — use the Add button there.",
            null,
            [['label' => 'Suppliers', 'url' => base_url() . 'Supplier']]
        );
    }

    // "my info" / "my business info" / "company info"
    private function _companyInfoLink() {
        return $this->_reply(
            "Here's your business info:",
            null,
            [
                ['label' => 'Company', 'url' => base_url() . 'Company'],
                ['label' => 'Company Branch', 'url' => base_url() . 'Companybranch'],
            ]
        );
    }

    // "reports" / "my reports" / "generate reports" — all report links
    private function _allReportsLinks() {
        return $this->_reply(
            "Here are your reports — tap one to open it:",
            null,
            [
                ['label' => 'Sales Report', 'url' => base_url() . 'RptSales'],
                ['label' => 'GRN Report', 'url' => base_url() . 'RptGRN'],
                ['label' => 'Item Stock', 'url' => base_url() . 'Rptmatstock'],
                ['label' => 'Invoice History', 'url' => base_url() . 'Invoiceview'],
            ]
        );
    }

    // "create a purchase order" / "create po" / "purchase order"
    private function _purchaseOrderHelp() {
        return $this->_reply(
            "To create a purchase order:\n" .
            "1. Go to Purchase Order and click Add.\n" .
            "2. Select the supplier and add the items with quantities.\n" .
            "3. Save and confirm the order — it's then ready for GRN once goods arrive.",
            null,
            [['label' => 'Purchase Order', 'url' => base_url() . 'Purchaseorder']]
        );
    }

    // "goods receive note" / "create grn" / "create new grn"
    private function _grnHelp() {
        return $this->_reply(
            "To create a GRN (Goods Receive Note):\n" .
            "1. Go to Good Receive Note and click Add.\n" .
            "2. Select the confirmed purchase order, or enter items directly.\n" .
            "3. Enter batch numbers, quantities and prices, then save — stock is updated automatically.",
            null,
            [['label' => 'Good Receive Note', 'url' => base_url() . 'Goodreceive']]
        );
    }

    // "how to create user account" / "need to create a new user"
    private function _createUserAccountHelp() {
        return $this->_reply(
            "To create a new user account:\n" .
            "1. Go to User Account and click Add.\n" .
            "2. Enter the name, username, and password, and choose a user type and location.\n" .
            "3. Save, then set that user's menu privileges from the Privileges page.",
            null,
            [
                ['label' => 'User Account', 'url' => base_url() . 'User/Useraccount'],
                ['label' => 'Privileges', 'url' => base_url() . 'User/Userprivilege'],
            ]
        );
    }

    // "how to change privileges" / "need to change privileges of existing user"
    private function _privilegesHelp() {
        return $this->_reply(
            "To change a user's privileges:\n" .
            "1. Go to Privileges.\n" .
            "2. Select the user and the menu you want to change.\n" .
            "3. Toggle Add / Edit / Status Change / Remove / Access as needed, then save.",
            null,
            [['label' => 'Privileges', 'url' => base_url() . 'User/Userprivilege']]
        );
    }

    // Menu name -> URL directory used by _menuList() and _menuLink().
    private function _menuDirectory() {
        return [
            ['keywords' => ['dashboard'], 'label' => 'Dashboard', 'url' => 'Welcome/Dashboard'],
            ['keywords' => ['sales agent'], 'label' => 'Sales Agent', 'url' => 'Aiagent/SalesAgent'],
            ['keywords' => ['inventory agent'], 'label' => 'Inventory Agent', 'url' => 'Aiagent/InventoryAgent'],
            ['keywords' => ['chat assistant', 'chatbot'], 'label' => 'Chat Assistant', 'url' => 'Aiagent/ChatAgent'],
            ['keywords' => ['new invoice', 'create sale', 'make a sale', 'point of sale', ' pos ', 'direct sale'], 'label' => 'New Invoice (POS)', 'url' => 'Directsale'],
            ['keywords' => ['invoice history', 'invoice view', 'invoice'], 'label' => 'Invoice History', 'url' => 'Invoiceview'],
            ['keywords' => ['purchase order'], 'label' => 'Purchase Order', 'url' => 'Purchaseorder'],
            ['keywords' => ['good receive', 'grn'], 'label' => 'Good Receive Note', 'url' => 'Goodreceive'],
            ['keywords' => ['item stock', 'stock report'], 'label' => 'Item Stock', 'url' => 'Rptmatstock'],
            ['keywords' => ['batchwise', 'stock batchwise'], 'label' => 'Item Stock (Batchwise)', 'url' => 'Rptmatstockbatchwise'],
            ['keywords' => ['material category', 'product category'], 'label' => 'Category', 'url' => 'Materialcategory'],
            ['keywords' => ['material detail', 'product detail', 'products page'], 'label' => 'Product Detail', 'url' => 'Materialdetail'],
            ['keywords' => ['customer list', 'customers page'], 'label' => 'Customer List', 'url' => 'Customer'],
            ['keywords' => ['prescription'], 'label' => 'Prescriptions', 'url' => 'CustomerPrescription'],
            ['keywords' => ['supplier'], 'label' => 'Suppliers', 'url' => 'Supplier'],
            ['keywords' => ['grn report'], 'label' => 'GRN Report', 'url' => 'RptGRN'],
            ['keywords' => ['sales report'], 'label' => 'Sales Report', 'url' => 'RptSales'],
            ['keywords' => ['company branch'], 'label' => 'Company Branch', 'url' => 'Companybranch'],
            ['keywords' => ['company info', 'company page'], 'label' => 'Company', 'url' => 'Company'],
            ['keywords' => ['user account'], 'label' => 'User Account', 'url' => 'User/Useraccount'],
            ['keywords' => ['user type'], 'label' => 'User Type', 'url' => 'User/Usertype'],
            ['keywords' => ['user privilege', 'privileges'], 'label' => 'Privileges', 'url' => 'User/Userprivilege'],
        ];
    }

    private function _menuList() {
        $links = [];
        foreach ($this->_menuDirectory() as $d) {
            $links[] = ['label' => $d['label'], 'url' => base_url() . $d['url']];
        }
        return $this->_reply("Here are the menus/functions available — tap one to go there:", null, $links);
    }

    // Returns a reply with the matching menu's link, or a helpful
    // "couldn't match that" message if no known menu name was found.
    private function _menuLink($msg) {
        foreach ($this->_menuDirectory() as $d) {
            foreach ($d['keywords'] as $k) {
                if (strpos($msg, trim($k)) !== false) {
                    return $this->_reply(
                        "Here's the link:",
                        null,
                        [['label' => $d['label'], 'url' => base_url() . $d['url']]]
                    );
                }
            }
        }
        return $this->_reply(
            "I couldn't match that to a specific menu. Try naming it directly, e.g. \"go to sales report\" " .
            "or \"open customer list\" — or ask \"what menus are available\" to see the full list."
        );
    }

    /* =========================================================
     *  SHARED HELPERS
     * ========================================================= */

    private function _any($msg, $keywords) {
        foreach ($keywords as $k) {
            if (strpos($msg, $k) !== false) return true;
        }
        return false;
    }

    private function _hasDateWord($msg) {
        return $this->_any($msg, ['today', 'yesterday', 'this week', 'last week', 'this month', 'last month',
            'this year', 'last year', '7 day', '30 day', '90 day']);
    }

    private function _reply($text, $data = null, $links = null) {
        return ['text' => $text, 'data' => $data, 'links' => $links];
    }

    // Parses phrases like "today" / "this week" / "last month" /
    // "last 90 days" out of the message. Defaults to the last 30 days.
    private function _dateRange($msg) {
        $end = date('Y-m-d');

        if (strpos($msg, 'today') !== false) {
            return [$end, $end, 'today'];
        }
        if (strpos($msg, 'this week') !== false) {
            return [date('Y-m-d', strtotime('monday this week')), $end, 'this week'];
        }
        if (strpos($msg, 'last week') !== false) {
            return [date('Y-m-d', strtotime('monday last week')), date('Y-m-d', strtotime('sunday last week')), 'last week'];
        }
        if (strpos($msg, 'this month') !== false) {
            return [date('Y-m-01'), $end, 'this month'];
        }
        if (strpos($msg, 'last month') !== false) {
            $prevStart = date('Y-m-01', strtotime('-1 month'));
            $prevEnd = date('Y-m-t', strtotime($prevStart));
            return [$prevStart, $prevEnd, 'last month'];
        }
        if (strpos($msg, '90 day') !== false || strpos($msg, '3 month') !== false) {
            return [date('Y-m-d', strtotime('-90 days')), $end, 'the last 90 days'];
        }
        if (strpos($msg, '7 day') !== false || strpos($msg, 'this week') !== false) {
            return [date('Y-m-d', strtotime('-7 days')), $end, 'the last 7 days'];
        }
        return [date('Y-m-d', strtotime('-30 days')), $end, 'the last 30 days'];
    }

    private function _sumNetTotal($start, $end) {
        $cb = $this->companyBranch();
        $this->db->select_sum('nettotal');
        $this->db->where('invdate >=', $start);
        $this->db->where('invdate <=', $end);
        $this->db->where('status', 1);
        $this->db->where('tbl_company_idtbl_company', $cb['company']);
        $this->db->where('tbl_company_branch_idtbl_company_branch', $cb['branch']);
        $q = $this->db->get('tbl_invoice')->row();
        return isset($q->nettotal) ? (float)$q->nettotal : 0;
    }

    private function _invoiceCount($start, $end) {
        $cb = $this->companyBranch();
        $this->db->where('invdate >=', $start);
        $this->db->where('invdate <=', $end);
        $this->db->where('status', 1);
        $this->db->where('tbl_company_idtbl_company', $cb['company']);
        $this->db->where('tbl_company_branch_idtbl_company_branch', $cb['branch']);
        return $this->db->count_all_results('tbl_invoice');
    }

    // Strips known phrase words out of the message, leaving (hopefully)
    // just the product name the person typed.
    private function _extractSearchTerm($msg, $stripWords) {
        $term = ' ' . $msg . ' ';
        foreach ($stripWords as $s) {
            $term = str_ireplace($s, ' ', $term);
        }
        return trim(preg_replace('/\s+/', ' ', $term));
    }

    // Fuzzy-ish material lookup: exact (case-insensitive) name match
    // wins, otherwise the shortest LIKE match (closest to the search term).
    private function _findMaterial($term) {
        if ($term === '') return null;
        $like = '%' . $term . '%';
        $sql = "SELECT idtbl_material_info, materialname
                FROM tbl_material_info
                WHERE status = 1 AND materialname LIKE ?
                ORDER BY (LOWER(TRIM(materialname)) = LOWER(?)) DESC, LENGTH(materialname) ASC
                LIMIT 1";
        return $this->db->query($sql, [$like, $term])->row();
    }
}