<?php session_start();
require_once 'config.php';
$PageName = "Vendors";
$Page = "Shop"; ?>
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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>

<body class="body-scroll d-flex flex-column h-100 menu-overlay" data-page="products">
    <!-- screen loader -->
   



    <!-- Begin page content -->
    <main class="flex-shrink-0 main">
        <!-- Fixed navbar -->
        <?php include_once 'back-header.php'; ?>
        <!-- page content start -->
        <div class="main-container">
             <div class="container mb-4">
                <input type="hidden" name="Search" value="search">
                        <input type="hidden" name="catid" id="catid" value="<?php echo $_GET['cat_id'];?>">
                        <input type="hidden" name="subid" id="subid" value="<?php echo $_GET['subid'];?>">
                <div class="swiper-container categories2tab1 text-center mb-4">
                    <div class="swiper-wrapper">
                        <?php for($i=1;$i<=10;$i++){?>
                        <div class="swiper-slide">
                            <button class="btn btn-sm <?php if($_GET['dist'] == $i){?>btn-default active <?php } else{?>btn-outline-default<?php } ?> rounded" onclick="getDistance(<?php echo $i;?>);"><?php echo $i;?> KM</button>
                        </div>
                        <?php } ?>
                        <div class="swiper-slide">
                            <button class="btn btn-sm <?php if($_GET['dist'] == 15){?>btn-default active <?php } else{?>btn-outline-default<?php } ?> rounded" onclick="getDistance(15);">15 KM</button>
                        </div>
                        <div class="swiper-slide">
                            <button class="btn btn-sm <?php if($_GET['dist'] == 20){?>btn-default active <?php } else{?>btn-outline-default<?php } ?> rounded" onclick="getDistance(20);">20 KM</button>
                        </div>
                        
                    </div>
                    <!-- Add Pagination -->
                    <div class="swiper-pagination white-pagination text-left mb-3"></div>
                </div>
 <script>
        function getDistance(val){
            var catid = $('#catid').val();
            var subid = $('#subid').val();
            window.location.href="vendor-lists.php?cat_id="+catid+"&subid="+subid+"&dist="+val;
        }
    </script>
                
<?php 
$CatId = $_GET['cat_id'];
  $SubCatId = $_GET['subid'];
$sql12 = "SELECT c.Name As CatName,s.Name As SubCatName FROM category c LEFT JOIN sub_category s ON c.id=s.CatId WHERE c.id='$CatId' AND s.id='$SubCatId'";
$row12 = getRecord($sql12);
 
 function distance($lat1, $lon1, $lat2, $lon2, $unit) {
  if (($lat1 == $lat2) && ($lon1 == $lon2)) {
    return 0;
  }
  else {
    $theta = $lon1 - $lon2;
    $dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) +  cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
    $dist = acos($dist);
    $dist = rad2deg($dist);
    $miles = $dist * 60 * 1.1515;
    $unit = strtoupper($unit);

    if ($unit == "K") {
      return ($miles * 1.609344);
    } else if ($unit == "N") {
      return ($miles * 0.8684);
    } else {
      return $miles;
    }
  }
}
                                    //$lat = '21.10995';
                                    //$lng = '79.11198';
                                    if(isset($_SESSION['lat'])){
                                      /*  $lat = $_SESSION['lat'];
                                    $lng = $_SESSION['lng'];*/
                                    $lat = '21.10995';
                                    $lng = '79.11198';
                                    }
                                    else{
                                        $lat = '21.10995';
                                        $lng = '79.11198';
                                    }
                                    
                                     $sql11 = "";
                             if($_POST['Search'] == 'search'){
                                $SubCatId = $_POST['SubCatId'];
                                $var = $_POST['SubCatId'];
                                $StateId = $_POST['StateId'];
                                $CityId = $_POST['CityId'];
                               
                                $sql11.="SELECT * FROM customers WHERE Status=1";
                                if($_POST['SubCatId']){
                                    //$sql11.=" AND SubCatid='$SubCatId'";
                                    $sql11.=" ";
                                }
                                if($_POST['StateId']){
                                    $sql11.=" AND StateId='$StateId'";
                                }

                                if($_POST['CityId']){
                                   $sql11.=" AND CityId='$CityId'";     
                                }
                             } 
                             else{   
                             $var = $_GET['subid']; 
                             if($_GET['dist']){
                                 $dist = $_GET['dist'];
                             }
                             else{
                                 $dist = 1;
                             }
                            $sql11 = "SELECT *, ( 3959 * acos( cos( radians($lat) ) * cos( radians( latitude ) ) * cos( radians( longitude ) - radians($lng) ) + sin( radians($lat) ) * sin(radians(latitude)) ) ) AS distance FROM customers HAVING distance < $dist ORDER BY distance LIMIT 0 , 20";
                            }
                            //echo $sql11;
                            $row11 = getList($sql11);
                            $rncnt11 = getRow($sql11);
                            if($rncnt11 > 0){
                            foreach($row11 as $result){
                                 $subarr = explode(',',$result['PrdSubCatId']);
     foreach($subarr as $subcheck)
           {
            if($subcheck == $var){ 
            $vedlat = $result['latitude'];
                $vedlong = $result['longitude'];
            $tot_dist = round(distance($lat, $lng, $vedlat, $vedlong, "K"),1);   
                         ?> 
                <div class="card mb-3">
                    <div class="card-body position-relative">
                        
                        <div class="media">
                            <a href=""><div class="icon icon-60 mr-3 rounded">
                                <figure class="background">
                                    <?php if($result['Photo'] == '') {?>
                                                    <img src="no_image.jpg" alt="" style="width: 60px;height: 60px;">
                                                  <?php } else{?>
                                                    <img src="../uploads/<?php echo $result['Photo']; ?>" alt="" style="width: 60px;height: 60px;">
                                                  <?php } ?>
                                    
                                </figure>
                            </div></a>
                            <div class="media-body">
                                <small class="text-secondary"><i class="material-icons" style="font-size: 10px;">room</i> <?php echo $tot_dist; ?> Km</small>
                                <a href="vendor-details.php?id=<?php echo $result['id'];?>&cat_id=<?php echo $CatId;?>&subid=<?php echo $SubCatId; ?>"><h6 class="mb-1 text-default"><?php echo $result['ShopName'];?></h6></a>
                                <p class="small mb-0">
                                <i class="fa fa-star" style="color: #ffa700;"></i>
                                <i class="fa fa-star" style="color: #ffa700;"></i>
                               <i class="fa fa-star" style="color: #ffa700;"></i>
                                <i class="fa fa-star" style="color: #ffa700;"></i>
                                <i class="fa fa-star-half-empty" style="color: #ffa700;"></i>
                                <span class="text-mute">4.85</span>
                            </p>
                            </div>

                            <a href="tel:<?php echo $result['Phone'];?>"><button class="btn btn-default btn-40 rounded-circle"><i class="material-icons">call</i></button></a>
                           
                        </div>
                       
                    </div>
                </div>
                 <?php }} }} else{?>
                     <div class="card mb-3">
                    <div class="card-body position-relative">
                        <h5 style="color: red;">Oops! Shop Not Found Near By You...</h5>
                    </div>
                </div>
            <?php } ?>
                
                 
            </div>
        </div>
    </main>
  <?php include_once 'footer.php'; ?>

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
    
</body>

</html>
