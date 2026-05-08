<?php
        //for level 1
        $sql11 = "SELECT UnderBy FROM customers WHERE id='$UserId'";
        $row11 = getRecord($sql11);
        $rncnt11 = getRow($sql11);
        $UnderUserId1 = $row11['UnderBy'];
        $Narration = "Commision Amount Received By Booking Hall - ".$Fname." ".$Lname;
        if($rncnt11 > 0){
            $sql_11 = "SELECT * FROM tbl_set_percentage WHERE Roll=2";
            $row_11 = getRecord($sql_11);
            //$Per = $row_11['Percentage'];
            $Per = 20;
            $Commision = $TotAmt*($Per/100);
        $sql12 = "INSERT INTO wallet SET UserId='$UnderUserId1',UserId2='$UserId',Percetage='$Per',Amount='$Commision',Status='Cr',Narration='$Narration',CreatedDate='$OrderDate',CreatedTime='$OrderTime',PrdId='3',Active='$Active',Oid='$oid'";
        $conn->query($sql12);
         }
        //for level 2
        $sql22 = "SELECT UnderBy FROM customers WHERE id='$UnderUserId1'";
        $row22 = getRecord($sql22);
        $UnderUserId2 = $row22['UnderBy'];
        if($UnderUserId2 != 0){
             $Per = 5;
            $Commision = $TotAmt*($Per/100);
        $sql12 = "INSERT INTO wallet SET UserId='$UnderUserId2',UserId2='$UserId',Percetage='$Per',Amount='$Commision',Status='Cr',Narration='$Narration',CreatedDate='$OrderDate',CreatedTime='$OrderTime',PrdId='3',Active='$Active',Oid='$oid'";
        $conn->query($sql12);
        }
        //for level 3
        $sql33 = "SELECT UnderBy FROM customers WHERE id='$UnderUserId2'";
        $row33 = getRecord($sql33);
        $UnderUserId3 = $row33['UnderBy'];
        if($UnderUserId3 != 0){
            $Per = 3;
            $Commision = $TotAmt*($Per/100);
        $sql12 = "INSERT INTO wallet SET UserId='$UnderUserId3',UserId2='$UserId',Percetage='$Per',Amount='$Commision',Status='Cr',Narration='$Narration',CreatedDate='$OrderDate',CreatedTime='$OrderTime',PrdId='3',Active='$Active',Oid='$oid'";
        $conn->query($sql12);
        }  
        //for level 4
        $sql44 = "SELECT UnderBy FROM customers WHERE id='$UnderUserId3'";
        $row44 = getRecord($sql44);
        $UnderUserId4 = $row44['UnderBy'];
        if($UnderUserId4 != 0){
            $Per = 2;
            $Commision = $TotAmt*($Per/100);
        $sql12 = "INSERT INTO wallet SET UserId='$UnderUserId4',UserId2='$UserId',Percetage='$Per',Amount='$Commision',Status='Cr',Narration='$Narration',CreatedDate='$OrderDate',CreatedTime='$OrderTime',PrdId='3',Active='$Active',Oid='$oid'";
        $conn->query($sql12);
        } 
           
?>         