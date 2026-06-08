<?php
session_start();
include_once '../config.php';
include_once '../auth.php';
$user_id = $_SESSION['Admin']['id'];
$MainPage = 'Transportor';
$Page = 'Add-Transportor';
?>
<!DOCTYPE html>
<html lang="en" class="default-style layout-fixed layout-navbar-fixed">
<head>
    <title><?php echo $Proj_Title; ?> - <?php if ($_GET['id']) { ?>Edit <?php } else { ?> Add <?php } ?> Transportor Account</title>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <?php include_once '../header_script.php'; ?>
</head>
<body>
    <div class="layout-wrapper layout-2">
        <div class="layout-inner">
            <?php include_once 'account-sidebar.php'; ?>
            <div class="layout-container">
                <?php include_once '../top_header.php'; ?>
                <?php
                $id = $_GET['id'];
                $sql7 = "SELECT * FROM tbl_users WHERE id='$id'";
                $row7 = getRecord($sql7);
                if (!is_array($row7)) {
                    $row7 = [];
                }
                ?>
                <div class="layout-content">
                    <div class="container-fluid flex-grow-1 container-p-y">
                        <h4 class="font-weight-bold py-3 mb-0"><?php if ($_GET['id']) { ?>Edit <?php } else { ?> Add <?php } ?> Transportor Account</h4>
                        <div class="card mb-4">
                            <div class="card-body">
                                <form id="validation-form" method="post" autocomplete="off" action="../ajax_files/ajax_transportor.php" enctype="multipart/form-data">
                                    <input type="hidden" name="id" value="<?php echo $_GET['id']; ?>">
                                    <input type="hidden" name="action" value="Save">
                                    <div class="form-row">
                                        <div class="form-group col-md-6">
                                            <label class="form-label">Company</label>
                                            <select class="form-control" name="CompId" id="CompId">
                                                <option selected="" value="">Select Company</option>
                                                <?php
                                                $sql12 = "SELECT * FROM tbl_users WHERE Status='1' AND Roll=10";
                                                foreach (getList($sql12) as $result) {
                                                ?>
                                                <option <?php if (($row7['CompId'] ?? '') == $result['id']) { ?> selected <?php } ?> value="<?php echo $result['id']; ?>"><?php echo $result['Fname']; ?></option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                        <div class="form-group col-md-3">
                                            <label class="form-label">Store</label>
                                            <select class="form-control" name="BranchId" id="BranchId">
                                                <?php if ($Roll == 1 || $Roll == 7) { ?><option selected="" value="">Select Store</option><?php } ?>
                                                <?php
                                                $sql12 = ($Roll == 1 || $Roll == 7) ? "SELECT * FROM tbl_branch WHERE Status='1'" : "SELECT * FROM tbl_branch WHERE Status='1' AND id='$BranchId'";
                                                foreach (getList($sql12) as $result) {
                                                ?>
                                                <option <?php if (($row7['BranchId'] ?? '') == $result['id']) { ?> selected <?php } ?> value="<?php echo $result['id']; ?>"><?php echo $result['Name']; ?></option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                        <div class="form-group col-md-3">
                                            <label class="form-label">Rooftop Store</label>
                                            <select class="form-control" name="RooftopBranchId" id="RooftopBranchId">
                                                <?php if ($Roll == 1 || $Roll == 7) { ?><option selected="" value="">Select Store</option><?php } ?>
                                                <?php
                                                $sql12 = ($Roll == 1 || $Roll == 7) ? "SELECT * FROM tbl_rooftop_branch WHERE Status='1'" : "SELECT * FROM tbl_rooftop_branch WHERE Status='1' AND id='$BranchId'";
                                                foreach (getList($sql12) as $result) {
                                                ?>
                                                <option <?php if (($row7['RooftopBranchId'] ?? '') == $result['id']) { ?> selected <?php } ?> value="<?php echo $result['id']; ?>"><?php echo $result['Name']; ?></option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                        <div class="form-group col-md-12">
                                            <label class="form-label">Transportor Name <span class="text-danger">*</span></label>
                                            <input type="text" name="Fname" class="form-control" value="<?php echo $row7['Fname'] ?? ''; ?>" required>
                                        </div>
                                        <div class="form-group col-md-12">
                                            <label class="form-label">Address</label>
                                            <textarea name="Address" class="form-control"><?php echo $row7['Address'] ?? ''; ?></textarea>
                                        </div>
                                        <input type="hidden" name="Password" value="12345">
                                        <div class="form-group col-md-4">
                                            <label class="form-label">Mobile No <span class="text-danger">*</span></label>
                                            <input type="text" name="Phone" class="form-control" value="<?php echo $row7['Phone'] ?? ''; ?>" required>
                                        </div>
                                        <div class="form-group col-md-4">
                                            <label class="form-label">Another Mobile No</label>
                                            <input type="text" name="Phone2" class="form-control" value="<?php echo $row7['Phone2'] ?? ''; ?>">
                                        </div>
                                        <div class="form-group col-md-4">
                                            <label class="form-label">Email Id</label>
                                            <input type="email" name="EmailId" class="form-control" value="<?php echo $row7['EmailId'] ?? ''; ?>">
                                        </div>
                                        <div class="form-group col-md-4">
                                            <label class="form-label">GST No</label>
                                            <input type="text" name="GstNo" class="form-control" value="<?php echo $row7['GstNo'] ?? ''; ?>">
                                        </div>
                                        <div class="form-group col-md-4">
                                            <label class="form-label">PAN No</label>
                                            <input type="text" name="PanNo" class="form-control" value="<?php echo $row7['PanNo'] ?? ''; ?>">
                                        </div>
                                        <div class="form-group col-md-12">
                                            <label class="form-label">Photo</label>
                                            <input type="file" class="form-control" name="Photo" style="opacity: 1;">
                                            <input type="hidden" name="OldPhoto" value="<?php echo $row7['Photo'] ?? ''; ?>">
                                            <?php if (!empty($row7['Photo'])) { ?>
                                            <img src="../uploads/<?php echo $row7['Photo']; ?>" alt="" style="width: 64px;height: 64px;margin-top:8px;">
                                            <?php } ?>
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label class="form-label">Status <span class="text-danger">*</span></label>
                                            <select class="form-control" name="Status" required>
                                                <option value="">Select Status</option>
                                                <option value="1" <?php if (($row7['Status'] ?? '') == '1') { ?> selected <?php } ?>>Active</option>
                                                <option value="0" <?php if (($row7['Status'] ?? '') == '0') { ?> selected <?php } ?>>Inactive</option>
                                            </select>
                                        </div>
                                    </div>
                                    <button type="submit" class="btn btn-primary btn-finish">Save</button>
                                </form>
                            </div>
                        </div>
                    </div>
                    <?php include_once '../footer.php'; ?>
                </div>
            </div>
        </div>
        <div class="layout-overlay layout-sidenav-toggle"></div>
    </div>
    <?php include_once '../footer_script.php'; ?>
</body>
</html>
