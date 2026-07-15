<?php 
function SearchSupplierList($searchTerm){
    $companyid=$_SESSION['company_id'];
    $branchid=$_SESSION['branch_id'];

    if(!isset($searchTerm)){
        $CI = get_instance();
        $CI->db->where('status', 1);
        $CI->db->where('company_id', $companyid);
        $CI->db->where('company_branch_id', $branchid);
        $CI->db->select('idtbl_supplier, name, telephone_no');
        $CI->db->from('tbl_supplier');
        $CI->db->limit(5);
        $respond=$CI->db->get();
    }
    else{            
        if(!empty($searchTerm)){
            $CI = get_instance();
            $CI->db->where('status', 1);
            $CI->db->where('company_id', $companyid);
            $CI->db->where('company_branch_id', $branchid);
            $CI->db->select('idtbl_supplier, name, telephone_no');
            $CI->db->from('tbl_supplier');
            $CI->db->like('name', $searchTerm, 'both'); 
            $respond=$CI->db->get();
        }
        else{
            $CI = get_instance();
            $CI->db->where('status', 1);
            $CI->db->where('company_id', $companyid);
            $CI->db->where('company_branch_id', $branchid);
            $CI->db->select('idtbl_supplier, name, telephone_no');
            $CI->db->from('tbl_supplier');
            $CI->db->limit(5);
            $respond=$CI->db->get();             
        }
    }
    
    $data=array();
    
    foreach ($respond->result() as $row) {
        $data[]=array("id"=>$row->idtbl_supplier, "text"=>$row->name);
    }
    
    echo json_encode($data);
}