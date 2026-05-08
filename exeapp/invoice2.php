<?php 
session_start();
include_once 'config.php';
include_once 'auth.php';
$user_id = $_SESSION['Admin']['id'];
$MainPage = "Sell";
$Page = "Add-Sell";
?>
<!DOCTYPE html>

<html lang="en" class="default-style layout-fixed layout-navbar-fixed">

<head>
    <title><?php echo $Proj_Title; ?> - <?php if($_GET['id']) {?>Edit <?php } else{?> Add <?php } ?> Raw Stock
    </title>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <meta name="description" content="" />
    <meta name="keywords" content="">
    <meta name="author" content="" />

    <?php include_once 'header_script.php'; ?>
</head>

<body>
    <style type="text/css">
         @media print {
        @page {
            margin-top: 10px;
            margin-left: 50px;
            margin-right: 0px;
            margin-bottom: 0px;
        }

        @page :footer {
            display: none
        }

        .noPrint {
            display: none;
        }

        @page :header {
            display: none
        }
    }

    @media print {
        a[href]:after {
            content: none !important;
        }
    }

    @media screen {
        div.divFooter {
            display: none;
        }
    }

    @media print {
        div.divFooter {
            position: fixed;
            bottom: 20;
        }
    }
    </style>
    <!-- [ Preloader ] Start -->
    <div class="page-loader">
        <div class="bg-primary"></div>
    </div>
    <!-- [ Preloader ] Ebd -->

    <!-- [ Layout wrapper ] Start -->
    <body class="body-scroll d-flex flex-column h-100 menu-overlay">
   


    <!-- Begin page content -->
    <main class="flex-shrink-0 main">
            <!-- [ Layout sidenav ] Start -->
             
            <!-- [ Layout sidenav ] End -->
            <!-- [ Layout container ] Start -->
            
                <!-- [ Layout navbar ( Header ) ] Start -->
                 
                <!-- [ Layout navbar ( Header ) ] End -->
<script>
function myFunction() {
  window.print();
}
</script>
<?php  
$id = $_GET['id'];
$sql7 = "SELECT ts.*,tu.Fname,tu.Lname,tu.Phone,tu.Address FROM tbl_general_ledger ts 
                    LEFT JOIN tbl_users tu ON ts.UserId=tu.id  WHERE ts.id='$id'";
$row = getRecord($sql7);

   $number = $row['PaidAmt'];
  include_once 'convert_currancy.php';
?>
                <!-- [ Layout content ] Start -->
                <div class="layout-content">

                    <!-- [ content ] Start -->
                     <div class="container-fluid">
                    <div class="page-title-box">
                        <div class="row align-items-center">
                            <div class="col-sm-6">
                             
                            </div>
                            <div class="col-sm-6">
                                <div class="float-right d-none d-md-block">
                                    <form method="post" action="print.php">
                                    <div class="dropdown">
                                       <input type="hidden" name="id" value="<?php echo $_GET['id']; ?>">
                                        <!--  <button class="btn btn-danger arrow-none waves-effect waves-light" type="submit"><i class="fa fa-file-pdf mr-2"></i> PDF</button> -->
                                   
                                        <button class="btn btn-primary arrow-none waves-effect waves-light" type="button" onclick="myFunction()"><i class="fa fa-print mr-2"></i> Print</button>
                                       
                                    </div>
                                      </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- end row -->
                    <div class="row">
                        <div class="col-12">
                            <div class="card m-b-20" style="border: 1px solid gray;">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="invoice-title">
                                               <!--  <h4 class="float-right font-16"><strong>Receipt No # 1</strong></h4> -->
                                                <h3 class="mt-0 float-right"><img src="logo.png" alt="logo" height="50"></h3></div>
                                                 <div class="row">
                                                <div class="col-12"><address><strong>MAXX COOLERS </strong><br>Ashok Chowk Opp. Orient Grant Hotel, Grate Nag Road, Mahal, Nagpur - 09, Maharashtra<br>9175252025, 9175112225</address></div>
                                               
                                            </div>
                                          
                                          <br>
                                            <div class="row">
                                                <div class="col-6"><strong>Voucher No : </strong>#<?php echo $row['Code']; ?>
                                                    <br><br><strong>Account Name : </strong><?php echo $row['AccountName']; ?>
                                                       
                                                       <br><br>
                                                    <strong>Sum Of Rupees : </strong><?php echo  $result22 . "Rupees"; ?>
                                                    <br><br>
                                                <strong>Narration : </strong><?php echo $row['Narration']; ?>
                                                   <br><br>
                                                <strong>Amount : </strong><span style="border: 2px solid gray;font-size: 20px;">&nbsp; <?php echo $row['PaidAmt']; ?>/-&nbsp;</span>
                                                    <br><br>

                                               </div>

                                                <div class="col-6 text-right"><address><strong>Receipt Date : </strong><?php echo $row['CreatedDate']; ?> <br><strong>Time : </strong><?php echo date('h:i a'); ?></address><br><br><br><br><br><br><br><br><br><br><br><br><br><br>
                                                 <span class="text-right">Authorised Signatory</span></div>
                                                 
                                            </div>
                                           
                                        </div>
                                    </div>
                                
                                  
                                </div>
                            </div>
                        </div>
                        
                    </div>

                    <div class="row">
                        <div class="col-12">
                            <div class="card m-b-20" style="border: 1px solid gray;">
                                <div class="card-body">
                                     <div class="row">
                                        <div class="col-12">
                                            <div class="invoice-title">
                                               <!--  <h4 class="float-right font-16"><strong>Receipt No # 1</strong></h4> -->
                                                <h3 class="mt-0 float-right"><img src="logo.png" alt="logo" height="50"></h3></div>
                                                 <div class="row">
                                                <div class="col-12"><address><strong>MAXX COOLERS </strong><br>Ashok Chowk Opp. Orient Grant Hotel, Grate Nag Road, Mahal, Nagpur - 09, Maharashtra<br>9175252025, 9175112225</address></div>
                                               
                                            </div>
                                          
                                          <br>
                                            <div class="row">
                                                <div class="col-6"><strong>Voucher No : </strong>#<?php echo $row['Code']; ?>
                                                    <br><br><strong>Account Name : </strong><?php echo $row['AccountName']; ?>
                                                       
                                                       <br><br>
                                                    <strong>Sum Of Rupees : </strong><?php echo  $result22 . "Rupees"; ?>
                                                    <br><br>
                                                <strong>Narration : </strong><?php echo $row['Narration']; ?>
                                                   <br><br>
                                                <strong>Amount : </strong><span style="border: 2px solid gray;font-size: 20px;">&nbsp; <?php echo $row['PaidAmt']; ?>/-&nbsp;</span>
                                                    <br><br>

                                               </div>

                                                <div class="col-6 text-right"><address><strong>Receipt Date : </strong><?php echo $row['CreatedDate']; ?> <br><strong>Time : </strong><?php echo date('h:i a'); ?></address><br><br><br><br><br><br><br><br><br><br><br><br><br><br>
                                                 <span class="text-right">Authorised Signatory</span></div>
                                                 
                                            </div>
                                           
                                        </div>
                                    </div>
                                
                                  
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- end row -->
                </div>
                    <!-- [ content ] End -->

                    <!-- [ Layout footer ] Start -->
                    <?php include_once 'footer.php'; ?>
                    <!-- [ Layout footer ] End -->

                </div>
                <!-- [ Layout content ] Start -->

            </div>
            <!-- [ Layout container ] End -->
        </div>
        <!-- Overlay -->
        <div class="layout-overlay layout-sidenav-toggle"></div>
    </div>
    <!-- / Layout wrapper -->

<?php include_once 'footer_script.php'; ?>
</body>

</html>
