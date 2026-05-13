<?php
session_start();
include_once 'config.php';
include_once 'auth.php';
$user_id = $_SESSION['Admin']['id'];
$MainPage = "DCR-Verification";
$Page = "View-DCR-Verification";
?>
<!DOCTYPE html>
<html lang="en" class="default-style layout-fixed layout-navbar-fixed">

<head>
    <title><?php echo $Proj_Title; ?> </title>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <meta name="description" content="" />
    <meta name="keywords" content="">
    <meta name="author" content="" />
    <?php include_once 'header_script.php'; ?>
</head>

<body>

    <div class="layout-wrapper layout-2">
        <div class="layout-inner">

            <?php include_once 'sidebar.php'; ?>


            <div class="layout-container">

                <?php include_once 'top_header.php'; ?>


                <div class="layout-content">

                    <div class="container-fluid flex-grow-1 container-p-y">
                        <h4 class="font-weight-bold py-3 mb-0">DCR Verification Serial No Status
                        </h4>

                        <div class="card" style="padding: 10px;">

                            <div class="card-datatable table-responsive">
                                <table id="example" class="table table-striped table-bordered" style="width:100%">
                                    <thead>
                                        <tr>
                                            <th>#</th>

                                            <th>DCR Date</th>
                                            <th>Certificate No</th>
                                            <th>Uploaded Serial No</th>
                                            <th>Matched Serial No</th>
                                            <th>Not Matched Serial No</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $i = 1;
                                        $sql = "SELECT * FROM tbl_dcr_verification";
                                        $res = $conn->query($sql);
                                        while ($row = $res->fetch_assoc()) {
                                            $sql2 = "SELECT SUM(CASE WHEN match_status = 0 THEN 1 ELSE 0 END) AS unmatch, 
                                            SUM(CASE WHEN match_status = 1 THEN 1 ELSE 0 END) AS match_count, COUNT(*) AS total 
                                            FROM tbl_dcr_verification_serialno WHERE dcr_id='".$row['id']."'";
                                            $row2 = getRecord($sql2);
                                        ?>
                                            <tr>
                                                <td><?php echo $i; ?></td>
                                                <td><?php echo date("d/m/Y", strtotime(str_replace('-', '/', $row['dcrdate']))); ?></td>
                                                <td><?php echo $row['certificate_no']; ?></td>
                                                <td><a href="serial-no-list.php?id=<?php echo $row['id']; ?>&val=total" class="badge badge-pill badge-secondary"><?php echo $row2['total']; ?></a></td>
                                                <td><a href="serial-no-list.php?id=<?php echo $row['id']; ?>&val=match" class="badge badge-pill badge-success"><?php echo $row2['match_count']; ?></a></td>
                                                <td><a href="serial-no-list.php?id=<?php echo $row['id']; ?>&val=unmatch" class="badge badge-pill badge-danger"><?php echo $row2['unmatch']; ?></a></td>
                                            </tr>
                                        <?php $i++;
                                        } ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <?php include_once 'footer.php'; ?>

                </div>

            </div>

        </div>

        <div class="layout-overlay layout-sidenav-toggle"></div>
    </div>
    <?php include_once 'footer_script.php'; ?>
    <script type="text/javascript">
        $(document).ready(function() {
            $('#example').DataTable({
                "scrollX": true
            });
        });
    </script>
</body>

</html>