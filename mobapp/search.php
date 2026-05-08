<?php session_start();
require_once 'config.php';
$Page = "search";
$PageName = "Search"; ?>
<!doctype html>
<html lang="en" class="h-100">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <meta name="generator" content="">
    <title><?php echo $Proj_Title; ?></title>

    <!-- manifest meta -->
    <meta name="apple-mobile-web-app-capable" content="yes">

    <!-- Favicons -->
    <link rel="apple-touch-icon" href="img/favicon180.png" sizes="180x180">
    <link rel="icon" href="img/favicon32.png" sizes="32x32" type="image/png">
    <link rel="icon" href="img/favicon16.png" sizes="16x16" type="image/png">

    <!-- Material icons-->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

    <!-- Google fonts-->
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500&amp;display=swap" rel="stylesheet">

    <!-- swiper CSS -->
    <link href="vendor/swiper/css/swiper.min.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="css/style.css" rel="stylesheet" id="style">
    <link rel="stylesheet" href="dist/css/styles.css" />
    <link rel="stylesheet" href="dist/aos.css" />
</head>

<body class="body-scroll d-flex flex-column h-100 menu-overlay" data-page="products">
    <!-- screen loader -->
   



    <!-- Begin page content -->
    <main class="flex-shrink-0 main">
        <!-- Fixed navbar -->
       <?php include_once 'back-header.php'; ?>
        <!-- page content start -->
       
        <div class="main-container">
            <div class="form-group float-label position-relative mb-1">
                            <div class="bottom-right ">
                                
                                <a href="#" class="btn btn-sm btn-link text-dark btn-40 rounded text-mute"><i class="material-icons">search</i></a>
                            </div>
                            <input type="text" class="form-control" id="search_text3">
                            <span class="form-control-label" style="padding-left:15px;">Search product by name</span>
                        </div><br>
<div class="container mb-4" id="show_prod">
    

            </div>
     

         
             
        </div>
    </main>
  <?php include_once 'footer.php'; ?>
<script src="dist/aos.js"></script>
    <script>
      AOS.init({
        easing: 'ease-in-out-sine'
      });
    </script>
    <!-- Required jquery and libraries -->
    <script src="js/jquery-3.3.1.min.js"></script>
    <script src="js/popper.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.min.js"></script>

    <!-- cookie js -->
    <script src="js/jquery.cookie.js"></script>

    <!-- Swiper slider  js-->
    <script src="vendor/swiper/js/swiper.min.js"></script>

    <!-- Customized jquery file  -->
    <script src="js/main.js"></script>
    <script src="js/color-scheme-demo.js"></script>


    <!-- page level custom script -->
    <script src="js/app.js"></script>
      <script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
      <link href="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css" rel="stylesheet">
      
<script type="text/javascript">
        $(document).ready(function() {
            function load_data3(query)
 {

  $.ajax({
   url:"ajax_files/ajax_search.php",
   method:"POST",
   data:{query:query},
   success:function(data)
   {
    console.log(data);
    $('#show_prod').html(data);
    
   }
  });
 }
 $('#search_text3').keyup(function(){
  var search = $(this).val();
  if(search != '')
  {
   load_data3(search);
  }
  else
  {
   load_data3();
  }
 });
            });
    </script>      
    <script type="text/javascript">
         function getDiffSize(id,pid){
      $('#sizeid'+pid).val(id);
    var sizeid = $('#sizeid'+pid).val();
    getDiffPrice2(id,pid);
   }   
     function getDiffPrice2(sizeid,pid){
     var action = 'getDiffPrice2';
            $.ajax({
  type: "POST",
  url: "ajax_files/ajax_product.php",
   data:{action:action,pid:pid,sizeid:sizeid},  
  success: function(data){
    res = JSON.parse(data);
      var MinPrice = res.MinPrice;
      var MaxPrice = res.MaxPrice;
      var OfferPrice = res.OfferPrice;
      var OfferPer = res.OfferPer;
      var MinPrice2 = res.MinPrice2;
      var MaxPrice2 = res.MaxPrice2;
      var OfferPrice2 = res.OfferPrice2;
      var status = res.status;
      var Stock = res.Stock;
      var ErrorMsg = res.ErrorMsg;
        $('#prd_price'+pid).val(MinPrice);
      if(Stock == 1){


/*$('#MaxPrice3'+pid).html('<del>&#8377;'+MaxPrice2+'</del>');*/
$('#MinPrice3'+pid).html('&#8377;'+MinPrice2);
//$('#OfferPer2'+pid).html('-'+OfferPer+'%');
//$('#notify_btn'+pid).hide();
$('#cart_btn'+pid).show();
      }
      else{
toastr.error('Currently, This Size Of Product is Not Available!', 'Error', {timeOut: 3000});
//$('#notify_btn'+pid).show();
$('#cart_btn'+pid).hide();
      }
    }
                  });
   } 
        function addCart(id){
var action = "shop_cart";
var quantity = $('#qntno'+id).val();
var code = $('#code'+id).val();
var pid = $('#pid'+id).val();
var sizeid = $('#sizeid'+id).val();
var ramid = $('#ramid'+id).val();
var storageid = $('#storageid'+id).val();
var price = $('#prd_price'+id).val();
$.ajax({
  type: "POST",
  url: "ajax_files/ajax_shop_cart.php",
  data: {action:action,pid:pid,quantity:quantity,code:code,sizeid:sizeid,ramid:ramid,storageid:storageid,price:price},
   beforeSend:function(){
     $('#add-cart'+id).attr('disabled','disabled');
     $('#add-cart'+id).text('Adding To Cart...');
    },

  success: function(data){
       toastr.success('Product Added to Cart', 'Success', {timeOut: 1000});
       $('#add-cart'+id).attr('disabled',false);
                       $('#add-cart'+id).text('Added..');
      }
});

        }
    </script>
</body>

</html>
