<?php 
session_start();
include_once 'config.php';
include_once 'auth.php';
$user_id = $_SESSION['Admin']['id'];
$MainPage = "Assign-Items-Store-Executive";
$Page = "Assign-Store-Executive-2";
//echo "<pre>";print_r($_SESSION["cart_item"]);
unset($_SESSION["cart_item"]);


function RandomStringGenerator($n)
{
    $generated_string = "";   
    $domain = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890";
    $len = strlen($domain);
    for ($i = 0; $i < $n; $i++)
    {
        $index = rand(0, $len - 1);
        $generated_string = $generated_string . $domain[$index];
    }
    return $generated_string;
} 


if (isset($_GET['backfill_codes']) && $_GET['backfill_codes'] === '1') {
    $sql = "SELECT id FROM tbl_distibute_item_details WHERE code='' LIMIT 50";
    $row = getList($sql);
    foreach ($row as $result) {
        $id = $result['id'];
        $Code2 = RandomStringGenerator(10) . $id;
        $conn->query("UPDATE tbl_distibute_item_details SET code='$Code2' WHERE id='$id'");
    }
}
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
    <script src="ckeditor/ckeditor.js"></script>
</head>

<body>
    <style type="text/css">
    .password-tog-info {
        display: inline-block;
        cursor: pointer;
        font-size: 12px;
        font-weight: 600;
        position: absolute;
        right: 50px;
        top: 30px;
        text-transform: uppercase;
        z-index: 2;
    }
    </style>
    <div class="layout-wrapper layout-2">
        <div class="layout-inner">

            <?php include_once 'sidebar.php'; ?>


            <div class="layout-container">

                <?php include_once 'top_header.php'; ?>

                <?php 
                
                if($_REQUEST['CreatedDate']==''){
                    $Created_Date = date('Y-m-d');
                }
                else{
                    $Created_Date = $_REQUEST['CreatedDate'];
                }
$id = $_GET['id'];
$sql7 = "SELECT * FROM tbl_distibute_items2 WHERE id='$id'";
$row7 = getRecord($sql7);

if(isset($_POST['submit'])){
 
}
?>

                <div class="layout-content">

                    <div class="container-fluid flex-grow-1 container-p-y">
                        <h4 class="font-weight-bold py-3 mb-0">Distribute Item To Dispatch Officier</h4>

                        <div class="card mb-4">
                            <div class="card-body">
                                 <form id="validation-form" method="post" autocomplete="off" action="save-distribute-item-store-executive-2.php">
                                <div class="row">

                                    <div class="col-lg-12">
                                <div id="alert_message"></div>
                               
                                    <input type="hidden" name="id" value="<?php echo $_GET['id']; ?>" id="userid">
                                    <input type="hidden" name="action" value="Save" id="action">
                                    <div class="form-row">
                                    
                                    <div class="form-group col-md-2">
<label class="form-label"> Store<span class="text-danger">*</span></label>
 <select class="form-control" name="BranchId" id="BranchId" required>
<?php 
 if($Roll == 1 || $Roll == 7){?>
<option selected="" value="">Select Store</option>
<?php }
 if($Roll == 1 || $Roll == 7){
  $sql12 = "SELECT * FROM tbl_branch WHERE Status='1'";
}
else{
  $sql12 = "SELECT * FROM tbl_branch WHERE Status='1' AND id='$BranchId'";
}

  $row12 = getList($sql12);
  foreach($row12 as $result){
     ?>
  <option <?php if($_REQUEST["BranchId"] == $result['id']) {?> selected <?php } ?> value="<?php echo $result['id'];?>">
    <?php echo $result['Name']; ?></option>
<?php } ?>
</select>
<div class="clearfix"></div>
</div> 

<?php if($Roll == 1 || $Roll == 7){?>


<div class="form-group col-md-4">
<label class="form-label"> Dispatch Officier<span class="text-danger">*</span></label>
<select class="select2-demo form-control" name="StoreExeId" id="StoreExeId" required>
    <option selected="" value="">Select store first</option>
    <?php
        $BranchId = (int) ($_REQUEST['BranchId'] ?? 0);
        if ($BranchId > 0) {
        $sql12 = "SELECT id, Fname FROM tbl_users WHERE Status='1' AND Roll=26
            AND (BranchId='$BranchId' OR FIND_IN_SET('$BranchId', REPLACE(IFNULL(TRIM(MulBranchId),''),' ','')))
            ORDER BY Fname ASC";
        $row12 = getList($sql12);
        foreach ($row12 as $result) {
    ?>
        <option <?php if($_REQUEST["StoreExeId"] == $result['id']) {?> selected <?php } ?> value="<?php echo $result['id']; ?>">
        <?php echo htmlspecialchars($result['Fname']); ?></option>
        <?php }
        } ?>
</select>

<div class="clearfix"></div>
</div> 
<?php } else{?>

<!--<div class="form-group col-md-4">
<label class="form-label"> Store Incharge<span class="text-danger">*</span></label>
<select class="select2-demo form-control" name="StoreInchId" id="StoreInchId" required onchange="getItems(this.value)">
   
    <?php
     
        $sql12 = "SELECT * FROM tbl_users WHERE Status='1' AND Roll=27 AND id='$user_id'";
       
        $row12 = getList($sql12);
        foreach ($row12 as $result) {
    ?>
        <option <?php if($_REQUEST["StoreInchId"] == $result['id']) {?> selected <?php } ?> value="<?php echo $result['id']; ?>">
        <?php echo $result['Fname']; ?></option>
        <?php } ?>
</select>

<div class="clearfix"></div>
</div> -->


<div class="form-group col-md-4">
<label class="form-label"> Dispatch Officier<span class="text-danger">*</span></label>
<select class="select2-demo form-control" name="StoreExeId" id="StoreExeId" required>
    <option selected="" value="">Select</option>
    <?php
     /*$sql12 = "SELECT * FROM tbl_users WHERE Status='1' AND Roll=32 AND UnderUser='$user_id'";*/
     $sql12 = "SELECT * FROM tbl_users WHERE Status='1' AND Roll=26 AND UnderUser='$user_id'";
        $row12 = getList($sql12);
        foreach ($row12 as $result) {
    ?>
        <option <?php if($_REQUEST["StoreExeId"] == $result['id']) {?> selected <?php } ?> value="<?php echo $result['id']; ?>">
        <?php echo $result['Fname']; ?></option>
        <?php } ?>
</select>

<div class="clearfix"></div>
</div>

<?php } ?>

<div class="form-group col-lg-2">
<label class="form-label">Date <span class="text-danger">*</span></label>
<input type="date" name="CreatedDate" id="CreatedDate" class="form-control" value="<?php echo $Created_Date; ?>" required>
<div class="clearfix"></div>
</div>



</div>

<div id="distribute-items-panel" class="w-100" style="display:none;">
    <div id="distribute-items-loading" class="text-center py-4" style="display:none;">
        <div class="spinner-border text-primary" role="status"></div>
        <p class="mt-2 mb-0 text-muted">Loading store stock...</p>
    </div>
    <div id="distribute-items-content"></div>
</div>
                               </div>


 <div class="col-lg-5" id="emidetails" style="display:none;">
    

 </div>

  
                                

 </div>
 </form>





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
function saveCart(id){
     var action = "saveCart";
     var quantity = "1";
      $.ajax({
      url:"assign-serial-no-distribute-session.php",
      type:"POST",
      data:{action:action,quantity:quantity,id:id},
      success:function(data){
          console.log(data);
          //alert(data);
      },
    });
 }
 
 function delete_prod(id){
     var action = "delete_shop_prod";
     var quantity = 1;
      $.ajax({
      url:"assign-serial-no-distribute-session.php",
      type:"POST",
      data:{action:action,id:id},
      success:function(data){
          console.log(data);
      },

    });
 }
 
 
var distributeItemsXhr = null;

function setupCheckboxGroup(groupPrefix, selectAllId) {
  const selectAll = document.getElementById(selectAllId);
  const checkboxes = document.querySelectorAll(`#distribute-items-content input[type='checkbox'][id^='${groupPrefix}']`);
  if (!checkboxes.length) return;

  const headerSelectAll = document.getElementById(groupPrefix === 'Check_Id' ? 'selectAllHeader' : 'selectAll2Header');

  function syncSelectAllState() {
    const allChecked = [...checkboxes].every(x => x.checked);
    if (selectAll) selectAll.checked = allChecked;
    if (headerSelectAll) headerSelectAll.checked = allChecked;
  }

  function setGroupChecked(isChecked) {
    checkboxes.forEach(cb => {
      const id = cb.id.replace(groupPrefix, '');
      cb.checked = isChecked;
      $('#CheckId' + id).val(isChecked ? 1 : 0);
      if (isChecked) saveCart(id);
      else delete_prod(id);
    });
    if (selectAll) selectAll.checked = isChecked;
    if (headerSelectAll) headerSelectAll.checked = isChecked;
  }

  [selectAll, headerSelectAll].forEach(el => {
    if (!el || el._boundDistribute) return;
    el._boundDistribute = true;
    el.addEventListener('change', function () {
      setGroupChecked(this.checked);
    });
  });

  checkboxes.forEach(cb => {
    if (cb._boundDistribute) return;
    cb._boundDistribute = true;
    cb.addEventListener('change', function () {
      const id = this.id.replace(groupPrefix, '');
      const isChecked = this.checked;
      $('#CheckId' + id).val(isChecked ? 1 : 0);
      if (isChecked) saveCart(id);
      else delete_prod(id);
      syncSelectAllState();
    });
  });
}

var distributeSerialsXhr = null;
var DT_ROW_LIMIT = 80;

function initDistributeDataTables() {
  if (!$.fn.DataTable) return;
  $('.distribute-serial-table').each(function () {
    var $t = $(this);
    var rowCount = $t.find('tbody tr').length;
    if (rowCount === 0 || rowCount > DT_ROW_LIMIT) return;
    if ($.fn.DataTable.isDataTable(this)) {
      $t.DataTable().destroy();
    }
    $t.DataTable({
      pageLength: 50,
      lengthMenu: [[25, 50, 100], [25, 50, 100]],
      scrollX: true,
      deferRender: true
    });
  });
}

function bindSerialTableFilters() {
  $(document).off('input.distributeSerialFilter', '.serial-table-filter');
  $(document).on('input.distributeSerialFilter', '.serial-table-filter', function () {
    var q = $(this).val().toLowerCase().trim();
    var $rows = $($(this).data('target')).find('tbody tr');
    $rows.each(function () {
      var show = !q || $(this).text().toLowerCase().indexOf(q) >= 0;
      $(this).toggle(show);
    });
  });
}

function bindDistributeItemsPanel() {
  setupCheckboxGroup('Check_Id', 'selectAll');
  setupCheckboxGroup('Check_Id2', 'selectAll2');
  bindSerialTableFilters();
  setTimeout(initDistributeDataTables, 0);
}

function loadDistributeSerials(BranchId) {
  if (distributeSerialsXhr) {
    distributeSerialsXhr.abort();
  }
  $('#distribute-serials-loading').show();
  $('#distribute-serials-content').empty();

  distributeSerialsXhr = $.ajax({
    url: 'ajax_distribute-item-store-executive-2-search.php',
    method: 'POST',
    timeout: 180000,
    data: { action: 'searchItems', phase: 'serials', BranchId: BranchId },
    success: function (html) {
      $('#distribute-serials-loading').hide();
      $('#distribute-serials-content').html(html);
      bindDistributeItemsPanel();
    },
    error: function (xhr, status) {
      if (status === 'abort') return;
      $('#distribute-serials-loading').hide();
      $('#distribute-serials-content').html('<div class="alert alert-warning">Could not load serial products. You can still save quantity items.</div>');
    },
    complete: function () {
      distributeSerialsXhr = null;
    }
  });
}

function loadDistributeItems() {
  var BranchId = $('#BranchId').val();
  if (!BranchId) {
    $('#distribute-items-panel').hide();
    $('#distribute-items-content').empty();
    return;
  }
  if (distributeSerialsXhr) {
    distributeSerialsXhr.abort();
    distributeSerialsXhr = null;
  }
  $('#distribute-items-panel').show();
  $('#distribute-items-loading').show();
  $('#distribute-items-content').empty();

  if (distributeItemsXhr) {
    distributeItemsXhr.abort();
  }
  distributeItemsXhr = $.ajax({
    url: 'ajax_distribute-item-store-executive-2-search.php',
    method: 'POST',
    timeout: 60000,
    data: { action: 'searchItems', phase: 'products', BranchId: BranchId },
    success: function (html) {
      $('#distribute-items-loading').hide();
      $('#distribute-items-content').html(html);
      loadDistributeSerials(BranchId);
    },
    error: function (xhr, status) {
      if (status === 'abort') return;
      $('#distribute-items-loading').hide();
      $('#distribute-items-content').html('<div class="alert alert-danger">Failed to load items. Please try again.</div>');
    },
    complete: function () {
      distributeItemsXhr = null;
    }
  });
}

function getItems(BranchId) {
  loadDistributeItems();
}

function getItems2(StoreExeId) {
  if (!StoreExeId) return;
  loadDistributeItems();
}
 function getVehicalNos(vehdate){
     var action = "getVehicalNos";
            $.ajax({
                url: "ajax_files/ajax_dropdown.php",
                method: "POST",
                data: {
                    action: action,
                    vehdate: vehdate
                },
                
                success: function(data) {
                    //alert(data);
                   $('#VehicalNo').html(data);
                    
                }
            });

 }
  function addVendor(){
        setTimeout(function() {
        window.open(
            'add-customer2.php', 'stickerPrint',
            'toolbar=1, scrollbars=1, location=1,statusbar=0, menubar=1, resizable=1, width=800, height=600,left=350,top=40,right=200'
        );
    }, 1);
    }

     function getPayType(val){
    if(val == 'Cheque'){
      $('.chequeoption').show();
      $('.upioption').hide();
    }
    else if(val == 'UPI'){
      $('.chequeoption').hide();
      $('.upioption').show();
    }
    else{
      $('.chequeoption').hide();
      $('.upioption').hide();
    }
  }

      function getSubTotal(){
     var sum = 0;
      $(".txt").each(function() {
      if(!isNaN(this.value) && this.value.length!=0) {
        sum += parseFloat(this.value);
      }
   });
   $('#GrossAmt').val(sum);
   
    }


    function getUserDetails(){
        var CellNo = $('#CellNo').val();
        var action = "getUserDetails2";
            $.ajax({
                url: "ajax_files/ajax_vendor.php",
                method: "POST",
                data: {
                    action: action,
                    CellNo: CellNo
                },
                dataType:"json",  
                success: function(data) {
                    $('#Address').val(data.Address);
                    $('#CustName').val(data.Fname+" "+data.Lname);
                    $('#Gname').val(data.Gname);
                    $('#Gphone').val(data.Gphone);
                    $('#Gname2').val(data.Gname2);
                    $('#Gphone2').val(data.Gphone2);
                    $('#AgentName').val(data.AgentName);
                    
                }
            });

    }
     $(document).ready(function() {
        var canLoadOnStoreOnly = <?php echo ($Roll == 1 || $Roll == 7) ? 'true' : 'false'; ?>;
        var i=1; 
    $('#add_more').click(function(){  
           i++;  
       var action = "getCustRow";
    $.ajax({
    url:"ajax_files/ajax_sell_products.php",
    method:"POST",
    data : {action:action,id:i},
    success:function(data)
    {
      $('#dynamic_field').append(data);
    }   
    });  
    }); 

    $(document).on('click', '.btn_remove', function(){  
           var button_id = $(this).attr("id");  
           if(confirm("Are you sure you want to delete?"))  
           { 
           $('#row'+button_id+'').remove();  
            getSubTotal();
            commonTotal();
           }
      }); 


     $(document).on("change", "#CustId", function(event) {
            var val = this.value;
            var action = "getUserDetails";
            $.ajax({
                url: "ajax_files/ajax_vendor.php",
                method: "POST",
                data: {
                    action: action,
                    id: val
                },
                dataType:"json",  
                success: function(data) {
                    
                    $('#Address').val(data.Address);
                    $('#CustName').val(data.Fname);
                    $('#CellNo').val(data.Phone);
                     $('#Gname').val(data.Gname);
                    $('#Gphone').val(data.Gphone);
                    $('#Gname2').val(data.Gname2);
                    $('#Gphone2').val(data.Gphone2);
                    $('#AgentName').val(data.AgentName);
                }
            });

        });

        $(document).on("change", "#BranchId", function() {
            var val = this.value;
            if (canLoadOnStoreOnly) {
                $.ajax({
                    url: "ajax_files/ajax_dropdown.php",
                    method: "POST",
                    data: { action: "getDispatchOfficersByBranch", id: val },
                    success: function(data) {
                        $('#StoreExeId').html(data);
                    }
                });
                getItems(val);
            }
        });

        $(document).on("change", "#StoreExeId", function() {
            if (!canLoadOnStoreOnly) {
                getItems2(this.value);
            }
        });

        if ($('#BranchId').val()) {
            if (window.history && window.history.replaceState) {
                var u = new URL(window.location.href);
                u.searchParams.delete('action');
                window.history.replaceState({}, '', u.pathname + (u.search || ''));
            }
            if (canLoadOnStoreOnly) {
                getItems($('#BranchId').val());
            } else if ($('#StoreExeId').val()) {
                getItems2($('#StoreExeId').val());
            }
        }

    });

     

     function getBrand(catid){
var action = "getBrands";
            $.ajax({
                url: "ajax_files/ajax_dropdown.php",
                method: "POST",
                data: {
                    action: action,
                    id: catid
                },
                success: function(data) {
                    $('#BrandId').html(data);
                  
                }
            });
}

function getProd(brandid){
var action = "getProd";
            $.ajax({
                url: "ajax_files/ajax_dropdown.php",
                method: "POST",
                data: {
                    action: action,
                    id: brandid
                },
                success: function(data) {
                    $('#ProductId').html(data);
                  
                }
            });
}

function getTotal(GrossAmt,CgstPer,SgstPer,IgstPer,SubTotal,Discount){
    //console.log(qty,vedprice,srno);
        var CgstAmt = Number(GrossAmt)*(Number(CgstPer)/100);
        var SgstAmt = Number(GrossAmt)*(Number(SgstPer)/100);
        var IgstAmt = Number(GrossAmt)*(Number(IgstPer)/100);
        $('#CgstAmt').val(parseFloat(CgstAmt).toFixed(2));
        $('#SgstAmt').val(parseFloat(SgstAmt).toFixed(2));
        $('#IgstAmt').val(parseFloat(IgstAmt).toFixed(2));
var SubTotal = Number(GrossAmt) + Number(CgstAmt) + Number(SgstAmt) + Number(IgstAmt);
$('#SubTotal').val(parseFloat(SubTotal).toFixed(2));
var Total = Number(SubTotal) - Number(Discount);
$('#Total').val(parseFloat(Total).toFixed(2));
}

    function commonTotal(){
        var GrossAmt = $('#GrossAmt').val();
        var CgstPer = $('#CgstPer').val();
        var SgstPer = $('#SgstPer').val();
        var IgstPer = $('#IgstPer').val();
        var SubTotal = $('#SubTotal').val();
        var UcdAmt = 0;
        var Discount = $('#Discount').val();
        getTotal(GrossAmt,CgstPer,SgstPer,IgstPer,SubTotal,Discount);
    }

function getProdTotal(qty,price,srno){
    var Total = (Number(qty) * Number(price));
$('#Total'+srno).val(parseFloat(Total).toFixed(2));
getSubTotal();
commonTotal();
}

function getProdDetails(val,srno){
    var qty = $('#Qty'+srno).val();
     var action = "getProdDetails";
            $.ajax({
                url: "ajax_files/ajax_sell_products.php",
                method: "POST",
                data: {
                    action: action,
                    id: val
                },
                dataType:"json",
                success: function(data) {
                
                    $('#ProductName'+srno).val(data.ProductName);
                    $('#ModelNo'+srno).val(data.ModelNo);
                    $('#Price'+srno).val(data.Price); 
                     getProdTotal(qty,data.Price,srno);
                }
            });
}
 </script>
</body>

</html>