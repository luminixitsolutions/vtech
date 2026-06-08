<style>
    .footer a:not(.btn) {
  padding: 0px 12px;
  text-align: center;
  color: #ffffff;
  display: block;
  text-decoration: none;
  border-top: 0px solid transparent;
}
</style>
<div class="footer" style="background: #405189;">
        <div class="row no-gutters justify-content-center" style="padding-top: 12px;">
            <div class="col-auto">
                <a href="home.php" class="">
                    <i class="material-icons">home</i>
                    <p>Home</p>
                </a>
            </div>
            <?php if(in_array("85", $Options)) {?>
            <div class="col-auto">
                <a href="survey_status.php" class="">
                    <i class="material-icons">insert_chart_outline</i>
                    <p>Survey</p>
                </a>
            </div>    
           <?php } if(in_array("86", $Options)) {?>
            <div class="col-auto">
                <a href="dispatch_status.php" class="">
                    <i class="material-icons">insert_chart_outline</i>
                    <p>Dispatch</p>
                </a>
            </div>
            <?php } if(in_array("87", $Options)) {?>
            <div class="col-auto">
                <a href="installations_status.php" class="">
                    <i class="material-icons">account_balance_wallet</i>
                    <p>Installation</p>
                </a>
            </div>
            <?php } if(in_array("88", $Options)) {?>
            <div class="col-auto">
                <a href="inspection_status.php" class="">
                    <i class="material-icons">two_wheeler</i>
                    <p>Inspection</p>
                </a>
            </div>
            <?php } ?>
            <div class="col-auto">
                <a href="profile.php">
                    <i class="material-icons">account_circle</i>
                    <p>Profile</p>
                </a>
            </div>
        </div>
    </div>
       <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">