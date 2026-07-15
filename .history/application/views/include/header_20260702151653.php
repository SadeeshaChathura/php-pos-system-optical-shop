<?php 
$functiondirectsale=uri_string();

if(current_url()!=base_url()){
    if($this->session->userdata('loggedin')==0){redirect();}
}

function menucheck($arraymenu, $menuID){
    foreach($arraymenu as $array){
        if($array->menuid==$menuID && $array->access_status=='1'){
            return $array->access_status;
        }
    }
    return '0';
}
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="description" content="" />
        <meta name="author" content="" />
        <title>Quantum Pay - By CalciteX</title>
        <link href="<?php echo base_url() ?>assets/css/styles.css" rel="stylesheet" />
        <link href="<?php echo base_url() ?>assets/css/pos-styles.css" rel="stylesheet" />
        <link href="https://cdn.datatables.net/1.10.20/css/dataTables.bootstrap4.min.css" rel="stylesheet" />
        <link rel="icon" type="image/x-icon" href="<?php echo base_url() ?>assets/img/favicon new.png" />
        <script data-search-pseudo-elements defer src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.11.2/js/all.min.js"></script>
        <!-- Datepicker CSS -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.8.0/css/bootstrap-datepicker.min.css">
        <script src="https://cdnjs.cloudflare.com/ajax/libs/feather-icons/4.24.1/feather.min.js"></script>
        <link href="<?php echo base_url() ?>assets/css/style.css" rel="stylesheet" />
        <link href="<?php echo base_url() ?>assets/css/animate.css" rel="stylesheet" />
        <link href="<?php echo base_url() ?>assets/css/select2.css" rel="stylesheet" />
        <link rel="stylesheet" href="https://maxst.icons8.com/vue-static/landings/line-awesome/font-awesome-line-awesome/css/all.min.css">
        <link href="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.css" rel="stylesheet">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/Mottie-Keyboard/1.30.1/css/keyboard.min.css">
        <link rel="stylesheet" href="<?php echo base_url() ?>assets/slick/slick.css">
        <link rel="stylesheet" href="<?php echo base_url() ?>assets/icofont/icofont.min.css">
        <link rel="stylesheet" href="<?php echo base_url() ?>assets/flaticon/flaticon.css">
        <link href="<?php echo base_url() ?>assets/css/modern-sidebar-topnav.css" rel="stylesheet" />
        <style>
            .table tr {
                cursor: pointer;
            }
        </style>
    </head>
    <body class="nav-fixed <?php if($functiondirectsale=='Directsale'){echo 'sidenav-toggled';} ?>">