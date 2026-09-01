<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Notificationinfo extends CI_Model {

    public function __construct(){
        parent::__construct();
    }

    /**
     * Merges several live data sources into one time-sorted feed.
     * No notifications table required — each source is queried fresh.
     */
    public function GetNotifications($limit = 8){
        $companyid = $this->session->userdata('companyid');
        $branchid  = $this->session->userdata('branchid');

        $notifications = array_merge(
            $this->_LowStockNotifications(),
            $this->_RecentInvoiceNotifications($companyid, $branchid),
            $this->_OverdueOrderNotifications($companyid, $branchid),
            $this->_RecentGrnNotifications()
        );

        usort($notifications, function($a, $b){
            return strtotime($b['raw_time']) - strtotime($a['raw_time']);
        });

        return array_slice($notifications, 0, $limit);
    }

    /**
     * "Unread" = generated in the last 24 hours, since this feed has no
     * persisted read/unread state. The frontend actually overrides this
     * with its own localStorage-based read tracking (keyed by each
     * notification's 'id'), so this server-side count is only used as a
     * fallback before JS has run. Swap this out if you later add a
     * tbl_notification_read table keyed by user + notification id.
     */
    public function GetUnreadCount(){
        $all = $this->GetNotifications(50);
        $count = 0;
        foreach ($all as $n) {
            if (strtotime($n['raw_time']) >= strtotime('-24 hours')) {
                $count++;
            }
        }
        return $count;
    }

    /**
     * Builds a short, stable id for a notification from its identifying
     * parts (source type + row id + a timestamp/date that changes only
     * when the underlying row actually changes). Used by the frontend to
     * remember which notifications have been read, via localStorage.
     */
    private function _MakeId($parts){
        return substr(md5(implode('|', $parts)), 0, 12);
    }

    private function _LowStockNotifications(){
        $sql = "SELECT mi.idtbl_material_info, mi.materialname, mi.reorderlevel,
                    COALESCE(SUM(s.qty),0) AS totalqty, MAX(s.updatedatetime) AS lastupdate
                FROM tbl_material_info mi
                LEFT JOIN tbl_stock s
                    ON s.tbl_material_info_idtbl_material_info = mi.idtbl_material_info
                    AND s.status = 1
                WHERE mi.status = 1
                GROUP BY mi.idtbl_material_info
                HAVING totalqty <= mi.reorderlevel AND mi.reorderlevel > 0
                ORDER BY lastupdate DESC
                LIMIT 5";
        $query = $this->db->query($sql);

        $items = array();
        foreach ($query->result() as $row) {
            // Display time still prefers the real stock update time when present,
            // falling back to "now" only for sorting/display — never for the id.
            $displayTime = $row->lastupdate ?: date('Y-m-d H:i:s');

            // id is stable for the whole calendar day regardless of whether
            // lastupdate is null — this is what fixes "mark all read" not sticking.
            $idDateComponent = $row->lastupdate ?: date('Y-m-d');

            $items[] = array(
                'id'       => $this->_MakeId(array('stock', $row->idtbl_material_info, $idDateComponent)),
                'type'     => 'warning',
                'icon'     => 'alert-triangle',
                'text'     => 'Stock low: <strong>' . htmlspecialchars($row->materialname) . '</strong> (' . (int)$row->totalqty . ' left)',
                'raw_time' => $displayTime,
                'link'     => base_url() . 'Rptmatstock'
            );
        }
        return $items;
    }

    private function _RecentInvoiceNotifications($companyid, $branchid){
        $this->db->select('idtbl_invoice, nettotal, insertdatetime');
        $this->db->from('tbl_invoice');
        $this->db->where('DATE(insertdatetime)', date('Y-m-d'));
        $this->db->where('status', 1);
        if ($companyid) $this->db->where('tbl_company_idtbl_company', $companyid);
        if ($branchid)  $this->db->where('tbl_company_branch_idtbl_company_branch', $branchid);
        $this->db->order_by('insertdatetime', 'DESC');
        $this->db->limit(5);
        $query = $this->db->get();

        $items = array();
        foreach ($query->result() as $row) {
            $invnum = 'INV-' . str_pad($row->idtbl_invoice, 6, '0', STR_PAD_LEFT);
            $items[] = array(
                'id'       => $this->_MakeId(array('inv', $row->idtbl_invoice)),
                'type'     => 'info',
                'icon'     => 'file-text',
                'text'     => 'New invoice <strong>' . $invnum . '</strong> — LKR ' . number_format($row->nettotal, 2),
                'raw_time' => $row->insertdatetime,
                'link'     => base_url() . 'Invoiceview'
            );
        }
        return $items;
    }

    private function _OverdueOrderNotifications($companyid, $branchid){
        $this->db->select('idtbl_invoice, duedate, nettotal');
        $this->db->from('tbl_invoice');
        $this->db->where('ordertype', 2);
        $this->db->where('paycomplete', 0);
        $this->db->where('status', 1);
        $this->db->where('duedate <', date('Y-m-d'));
        if ($companyid) $this->db->where('tbl_company_idtbl_company', $companyid);
        if ($branchid)  $this->db->where('tbl_company_branch_idtbl_company_branch', $branchid);
        $this->db->order_by('duedate', 'ASC');
        $this->db->limit(5);
        $query = $this->db->get();

        $items = array();
        foreach ($query->result() as $row) {
            $invnum = 'INV-' . str_pad($row->idtbl_invoice, 6, '0', STR_PAD_LEFT);
            $items[] = array(
                'id'       => $this->_MakeId(array('overdue', $row->idtbl_invoice, $row->duedate)),
                'type'     => 'danger',
                'icon'     => 'clock',
                'text'     => 'Order <strong>' . $invnum . '</strong> overdue (was due ' . date('M d', strtotime($row->duedate)) . ')',
                'raw_time' => $row->duedate . ' 00:00:00',
                'link'     => base_url() . 'Invoiceview'
            );
        }
        return $items;
    }

    private function _RecentGrnNotifications(){
        $this->db->select('g.idtbl_grn, g.total, g.insertdatetime, s.suppliername');
        $this->db->from('tbl_grn g');
        $this->db->join('tbl_supplier s', 's.idtbl_supplier = g.tbl_supplier_idtbl_supplier', 'left');
        $this->db->where('DATE(g.insertdatetime)', date('Y-m-d'));
        $this->db->where('g.status', 1);
        $this->db->order_by('g.insertdatetime', 'DESC');
        $this->db->limit(5);
        $query = $this->db->get();

        $items = array();
        foreach ($query->result() as $row) {
            $items[] = array(
                'id'       => $this->_MakeId(array('grn', $row->idtbl_grn)),
                'type'     => 'success',
                'icon'     => 'truck',
                'text'     => 'GRN received from <strong>' . htmlspecialchars($row->suppliername ?: 'Supplier') . '</strong>',
                'raw_time' => $row->insertdatetime,
                'link'     => base_url() . 'Goodreceive'
            );
        }
        return $items;
    }
}