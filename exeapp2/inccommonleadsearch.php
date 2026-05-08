<div class="form-group col-md-3">
<label class="form-label">Industry</label>
 <select class="form-control" name="CatId" id="CatId">
  <?php if($Roll==1 || $Roll==4){?>
<option selected="" value="all">All</option>
<?php } ?>
 <?php 
  $sql12 = "SELECT * FROM tbl_category WHERE Status='1'";
  if($Roll==2){
    $sql12.=" AND Name='$UserCat'";
  }
  //echo $sql12;
  $row12 = getList($sql12);
  foreach($row12 as $result){
     ?>
  <option <?php if($_REQUEST["CatId"] == $result['Name']) {?> selected <?php } ?> value="<?php echo $result['Name'];?>">
    <?php echo $result['Name']; ?></option>
<?php } ?>
</select>
<div class="clearfix"></div>
</div>

  <div class="form-group col-md-3">
<label class="form-label">Services</label>
 <select class="form-control" name="Services" id="Services">
<option selected="" value="all">All</option>
 <?php 
  
  if($Roll==2){
     $sql12 = "SELECT * FROM tbl_courses WHERE CatId = '$UserCat' AND Status='1'";
  }
  else{
   $sql12 = "SELECT * FROM tbl_courses WHERE CatId = '".$_REQUEST["CatId"]."' AND Status='1'"; 
  }
  $row12 = getList($sql12);
  foreach($row12 as $result){
     ?>
  <option <?php if($_REQUEST["Services"] == $result['Name']) {?> selected <?php } ?> value="<?php echo $result['Name'];?>">
    <?php echo $result['Name']; ?></option>
<?php } ?>
</select>
<div class="clearfix"></div>
</div>

  <div class="form-group col-md-3">
                                            <label class="form-label">Diapostion </label>
                                            <select class="form-control" name="Diaposition" id="Diaposition">
                                                <option selected="" value="all">All</option>
                                                <?php 
                                                if($Roll==2){
 $q = "select * from tbl_diapostion WHERE Status=1 AND CatId='$UserCat'";
                                                }
                                                else{
                                        $q = "select * from tbl_diapostion WHERE Status=1 AND CatId='".$_REQUEST["CatId"]."'";
                                      }
                                        $r = $conn->query($q);
                                        while($rw = $r->fetch_assoc())
                                    {
                                ?>
                                                <option <?php if($_REQUEST['Diaposition']==$rw['Name']){ ?> selected <?php } ?>
                                                    value="<?php echo $rw['Name']; ?>"><?php echo $rw['Name']; ?></option>
                                                <?php } ?>
                                            </select>
                                        </div>


<div class="form-group col-md-3">
<label class="form-label">From Date </label>
<input type="date" name="FromDate" id="FromDate" class="form-control" value="<?php echo $_REQUEST['FromDate'] ?>" autocomplete="off">
</div>
<div class="form-group col-md-3">
<label class="form-label">To Date</label>
<input type="date" name="ToDate" id="ToDate" class="form-control" value="<?php echo $_REQUEST['ToDate'] ?>" autocomplete="off">
</div>
<input type="hidden" name="Search" value="Search">
<div class="form-group col-md-1" style="padding-top:20px;">
<button type="submit" name="submit" class="btn btn-primary btn-finish">Search</button>
</div>
<?php if(isset($_REQUEST['Search'])) {?>
<div class="col-md-1">
<label class="form-label d-none d-md-block">&nbsp;</label>
<a href="<?php echo $_SERVER['PHP_SELF']; ?>" class="btn btn-info btn-block" data-toggle="tooltip" data-placement="top" data-original-title="Clear Filter">X</a>
</div>
<?php } ?>