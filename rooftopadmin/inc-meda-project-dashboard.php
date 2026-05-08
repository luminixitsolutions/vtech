 <div class="row">
                            <div class="col-sm-6 col-xl-2">
                                 <a href="view-total-selections.php?projid=<?php echo $_GET['prjid'];?>&page=1&title=Total Selection&subheadid=<?php echo $_GET['id'];?>">
                               <div class="card mb-4 bg-pattern-3-dark">
                                    <div class="card-body" style="padding-top: 15px; padding-bottom: 15px; padding-right: 5px; padding-left: 10px;">
                                        <div class="d-flex align-items-center">
                                          
                                            <div class="ml-3">
                                                <h6 class="mb-0" style="color: black;">Total<br> Applications</h6>
                                        <div class="text-large"><?php  
                                                            $sql4 = "SELECT * FROM tbl_users WHERE ProjectId='".$_GET['prjid']."' AND ProjectSubHeadId='".$_GET['id']."' AND Roll=5 AND ProjectType=2";
                                                            echo $rncnt4 = getRow($sql4);

                                                        ?></div>
                                        
                                     </div>
                                        </div>
                                     </div>
                                </div></a>
                            </div>

                             <div class="col-sm-6 col-xl-2">
                                <a href="view-total-selections.php?projid=<?php echo $_GET['prjid'];?>&page=2&FieldSurveyDetails=1&title=Total JSR Done&subheadid=<?php echo $_GET['id'];?>">
                               <div class="card mb-4 bg-pattern-3-dark">
                                    <div class="card-body" style="padding-top: 15px; padding-bottom: 15px; padding-right: 5px; padding-left: 10px;">
                                        <div class="d-flex align-items-center">
                                          
                                            <div class="ml-3">
                                                <h6 class="mb-0" style="color: black;">Total Survey<br> Done</h6>
                                        <div class="text-large"><?php  
                                                            $sql47 = "SELECT * FROM tbl_users WHERE ProjectId='".$_GET['prjid']."' AND ProjectSubHeadId='".$_GET['id']."' AND Roll=5 AND FieldSurveyDetails=1 AND ProjectType=2";
                                                            echo $rncnt47 = getRow($sql47);

                                                        ?></div>
                                        
                                     </div>
                                        </div>
                                     </div>
                                </div></a>
                            </div>

                        <div class="col-sm-6 col-xl-2">
                                <a href="view-total-selections.php?projid=<?php echo $_GET['prjid'];?>&page=3&FieldSurveyDetails=0&title=Total JSR Pending&subheadid=<?php echo $_GET['id'];?>">
                               <div class="card mb-4 bg-pattern-3-dark">
                                    <div class="card-body" style="padding-top: 15px; padding-bottom: 15px; padding-right: 5px; padding-left: 10px;">
                                        <div class="d-flex align-items-center">
                                          
                                            <div class="ml-3">
                                                <h6 class="mb-0" style="color: black;">Total Survey Pending</h6>
                                        <div class="text-large"><?php  
                                                            $sql4 = "SELECT * FROM tbl_users WHERE ProjectId='".$_GET['prjid']."' AND ProjectSubHeadId='".$_GET['id']."' AND Roll=5 AND FieldSurveyDetails=0 AND ProjectType=2";
                                                            echo $rncnt4 = getRow($sql4);

                                                        ?></div>
                                        
                                     </div>
                                        </div>
                                     </div>
                                </div></a>
                            </div>

                            <div class="col-sm-6 col-xl-2">
                               <a href="view-total-selections.php?projid=<?php echo $_GET['prjid'];?>&page=4&FieldSurveyDetails=2&title=Total JSR Cancellation&subheadid=<?php echo $_GET['id'];?>">
                               <div class="card mb-4 bg-pattern-3-dark">
                                    <div class="card-body" style="padding-top: 15px; padding-bottom: 15px; padding-right: 5px; padding-left: 10px;">
                                        <div class="d-flex align-items-center">
                                          
                                            <div class="ml-3">
                                                <h6 class="mb-0" style="color: black;">Total Cancellation</h6>
                                        <div class="text-large"><?php  
                                                            $sql4 = "SELECT * FROM tbl_users WHERE ProjectId='".$_GET['prjid']."' AND ProjectSubHeadId='".$_GET['id']."' AND Roll=5 AND FieldSurveyDetails=2 AND ProjectType=2";
                                                            echo $rncnt4 = getRow($sql4);

                                                        ?></div>
                                        
                                     </div>
                                        </div>
                                     </div>
                                </div></a>
                            </div>


                             <div class="col-sm-6 col-xl-2">
                               <a href="total-installations.php?projid=<?php echo $_GET['prjid'];?>&page=7&DispatchStatus=Yes&title=Total Material Dispatch Done&subheadid=<?php echo $_GET['id'];?>">
                               <div class="card mb-4 bg-pattern-3-dark">
                                    <div class="card-body" style="padding-top: 15px; padding-bottom: 15px; padding-right: 5px; padding-left: 10px;">
                                        <div class="d-flex align-items-center">
                                          
                                            <div class="ml-3">
                                                <h6 class="mb-0" style="color: black;">Total Material Dispatch Done</h6>
                                        <div class="text-large"><?php  
                                                            $sql4 = "SELECT tdo.*,tu.Fname,tu.Phone,tu.Address FROM tbl_rooftop_sell tdo 
                    LEFT JOIN tbl_users tu ON tdo.CustId=tu.id WHERE tdo.Inst_Dispatcher_Otp_Verify=1 AND tu.ProjectId='".$_GET['prjid']."' AND tu.ProjectSubHeadId='".$_GET['id']."' AND tu.Roll=5 AND tu.ProjectType=2";
                                                            echo $rncnt4 = getRow($sql4);

                                                        ?></div>
                                        
                                     </div>
                                        </div>
                                     </div>
                                </div></a>
                            </div>

                          

                          <div class="col-sm-6 col-xl-2">
                                <a href="total-installations.php?projid=<?php echo $_GET['prjid'];?>&page=7&DispatchStatus=No&title=Total Material Dispatch Pending&subheadid=<?php echo $_GET['id'];?>">
                               <div class="card mb-4 bg-pattern-3-dark">
                                    <div class="card-body" style="padding-top: 15px; padding-bottom: 15px; padding-right: 5px; padding-left: 10px;">
                                        <div class="d-flex align-items-center">
                                          
                                            <div class="ml-3">
                                                <h6 class="mb-0" style="color: black;">Total Material Dispatch Pending</h6>
                                        <div class="text-large"><?php  

                                        
                                                            echo $rncnt47 - $rncnt4;
                                                        ?></div>
                                        
                                     </div>
                                        </div>
                                     </div>
                                </div></a>
                            </div>

                            <div class="col-sm-6 col-xl-2">
                                 <a href="total-installations.php?projid=<?php echo $_GET['prjid'];?>&page=7&InstallStatus=Yes&title=Total Installation Done&subheadid=<?php echo $_GET['id'];?>">
                               <div class="card mb-4 bg-pattern-3-dark">
                                    <div class="card-body" style="padding-top: 15px; padding-bottom: 15px; padding-right: 5px; padding-left: 10px;">
                                        <div class="d-flex align-items-center">
                                          
                                            <div class="ml-3">
                                                <h6 class="mb-0" style="color: black;">Total Installation Done</h6>
                                        <div class="text-large"><?php  
                                                            $sql4 = "SELECT * FROM tbl_installations ti 
                    LEFT JOIN tbl_users tu ON ti.CustId=tu.id WHERE tu.ProjectId='".$_GET['prjid']."' AND tu.ProjectSubHeadId='".$_GET['id']."' AND tu.Roll=5 AND ti.InstallStatus='Yes' AND tu.ProjectType=2";
                                                            echo $rncnt4 = getRow($sql4);

                                                        ?></div>
                                        
                                     </div>
                                        </div>
                                     </div>
                                </div></a>
                            </div>
                          
                            <div class="col-sm-6 col-xl-2">
                                  <a href="total-installations.php?projid=<?php echo $_GET['prjid'];?>&page=8&InstallStatus=No&title=Total Installation Pending&subheadid=<?php echo $_GET['id'];?>">
                               <div class="card mb-4 bg-pattern-3-dark">
                                    <div class="card-body" style="padding-top: 15px; padding-bottom: 15px; padding-right: 5px; padding-left: 10px;">
                                        <div class="d-flex align-items-center">
                                          
                                            <div class="ml-3">
                                                <h6 class="mb-0" style="color: black;">Total Installation<br> Pending</h6>
                                        <div class="text-large"><?php  
                                                           
                                                            echo $rncnt47 - $rncnt4;
                                                        ?></div>
                                        
                                     </div>
                                        </div>
                                     </div>
                                </div></a>
                            </div>

                            <div class="col-sm-6 col-xl-2">
                                   <a href="total-installations.php?projid=<?php echo $_GET['prjid'];?>&page=9&PoInspection=Yes&title=Total Inspection Done&subheadid=<?php echo $_GET['id'];?>">
                               <div class="card mb-4 bg-pattern-3-dark">
                                    <div class="card-body" style="padding-top: 15px; padding-bottom: 15px; padding-right: 5px; padding-left: 10px;">
                                        <div class="d-flex align-items-center">
                                          
                                            <div class="ml-3">
                                                <h6 class="mb-0" style="color: black;">Total Inspection Done</h6>
                                        <div class="text-large"><?php  
                                                            $sql4 = "SELECT * FROM tbl_installations ti 
                    LEFT JOIN tbl_users tu ON ti.CustId=tu.id WHERE tu.ProjectId='".$_GET['prjid']."' AND tu.ProjectSubHeadId='".$_GET['id']."' AND tu.Roll=5 AND ti.PoInspection='Yes' AND tu.ProjectType=2";
                                                            echo $rncnt4 = getRow($sql4);

                                                        ?></div>
                                        
                                     </div>
                                        </div>
                                     </div>
                                </div></a>
                            </div>

                             <div class="col-sm-6 col-xl-2">
                                   <a href="total-installations.php?projid=<?php echo $_GET['prjid'];?>&page=10&PoInspection=No&title=Total Inspection Pending&subheadid=<?php echo $_GET['id'];?>">
                               <div class="card mb-4 bg-pattern-3-dark">
                                    <div class="card-body" style="padding-top: 15px; padding-bottom: 15px; padding-right: 5px; padding-left: 10px;">
                                        <div class="d-flex align-items-center">
                                          
                                            <div class="ml-3">
                                                <h6 class="mb-0" style="color: black;">Total Inspection Pending</h6>
                                        <div class="text-large"><?php  
                                                            echo $rncnt47 - $rncnt4;

                                                        ?></div>
                                        
                                     </div>
                                        </div>
                                     </div>
                                </div></a>
                            </div>
                           
                            <div class="col-sm-6 col-xl-2">
                                   <a href="total-installations.php?projid=<?php echo $_GET['prjid'];?>&page=11&DataUploadStatus=Yes&title=DISCOM Approval Approval Done&subheadid=<?php echo $_GET['id'];?>">
                               <div class="card mb-4 bg-pattern-3-dark">
                                    <div class="card-body" style="padding-top: 15px; padding-bottom: 15px; padding-right: 5px; padding-left: 10px;">
                                        <div class="d-flex align-items-center">
                                          
                                            <div class="ml-3">
                                                <h6 class="mb-0" style="color: black;">DISCOM Approval Done</h6>
                                        <div class="text-large"><?php  
                                                            $sql4 = "SELECT * FROM tbl_users tu LEFT JOIN tbl_installations ti ON ti.CustId=tu.id WHERE tu.ProjectId='".$_GET['prjid']."' AND tu.ProjectSubHeadId='".$_GET['id']."' AND tu.Roll=5 AND ti.DataUploadStatus='Yes' AND tu.ProjectType=2";
                                                            echo $rncnt4 = getRow($sql4);

                                                        ?></div>
                                        
                                     </div>
                                        </div>
                                     </div>
                                </div></a>
                            </div>
 
                            
                            <div class="col-sm-6 col-xl-2">
                                  <a href="total-installations.php?projid=<?php echo $_GET['prjid'];?>&page=12&DataUploadStatus=No&title=DISCOM Approval Pending&subheadid=<?php echo $_GET['id'];?>">
                               <div class="card mb-4 bg-pattern-3-dark">
                                    <div class="card-body" style="padding-top: 15px; padding-bottom: 15px; padding-right: 5px; padding-left: 10px;">
                                        <div class="d-flex align-items-center">
                                          
                                            <div class="ml-3">
                                                <h6 class="mb-0" style="color: black;">DISCOM Approval Pending</h6>
                                        <div class="text-large"><?php  
                                                            $sql4 = "SELECT * FROM tbl_users tu LEFT JOIN tbl_installations ti ON ti.CustId=tu.id WHERE tu.ProjectId='".$_GET['prjid']."' AND tu.ProjectSubHeadId='".$_GET['id']."' AND tu.Roll=5 AND ti.DataUploadStatus='No' AND tu.ProjectType=2";
                                                            echo $rncnt4 = getRow($sql4);

                                                        ?></div>
                                        
                                     </div>
                                        </div>
                                     </div>
                                </div></a>
                            </div>
 
                             <div class="col-sm-6 col-xl-2">
                                  <a href="total-installations.php?projid=<?php echo $_GET['prjid'];?>&page=11&DataUploadNational=Yes&title=Data Updated On National Portal Done&subheadid=<?php echo $_GET['id'];?>">
                               <div class="card mb-4 bg-pattern-3-dark">
                                    <div class="card-body" style="padding-top: 15px; padding-bottom: 15px; padding-right: 5px; padding-left: 10px;">
                                        <div class="d-flex align-items-center">
                                           
                                            <div class="ml-3"> 
                                                <h6 class="mb-0" style="color: black;">Data Updated On National Portal Done</h6>
                                        <div class="text-large"><?php  
                                                            $sql4 = "SELECT * FROM tbl_users tu LEFT JOIN tbl_installations ti ON ti.CustId=tu.id WHERE tu.ProjectId='".$_GET['prjid']."' AND tu.ProjectSubHeadId='".$_GET['id']."' AND tu.Roll=5 AND ti.DataUploadNational='Yes' AND tu.ProjectType=2";
                                                            echo $rncnt4 = getRow($sql4);

                                                        ?></div>
                                        
                                     </div>
                                        </div>
                                     </div>
                                </div></a>
                            </div>

                          
                            <div class="col-sm-6 col-xl-2">
                                 <a href="total-installations.php?projid=<?php echo $_GET['prjid'];?>&page=12&DataUploadNational=No&title=Data Updated On National Portal Pending&subheadid=<?php echo $_GET['id'];?>">
                               <div class="card mb-4 bg-pattern-3-dark">
                                    <div class="card-body" style="padding-top: 15px; padding-bottom: 15px; padding-right: 5px; padding-left: 10px;">
                                        <div class="d-flex align-items-center">
                                          
                                            <div class="ml-3">
                                                <h6 class="mb-0" style="color: black;">Data Updated On National Portal Pending</h6>
                                        <div class="text-large"><?php  
                                                            $sql4 = "SELECT * FROM tbl_users tu LEFT JOIN tbl_installations ti ON ti.CustId=tu.id WHERE tu.ProjectId='".$_GET['prjid']."' AND tu.ProjectSubHeadId='".$_GET['id']."' AND tu.Roll=5 AND ti.DataUploadNational='No' AND tu.ProjectType=2";
                                                            echo $rncnt4 = getRow($sql4);

                                                        ?></div>
                                        
                                     </div>
                                        </div>
                                     </div>
                                </div></a>
                            </div>
                     
                            
                             <div class="col-sm-6 col-xl-2">
                                   <a href="total-installations.php?projid=<?php echo $_GET['prjid'];?>&page=11&SubsidyRedeemed=Yes&title=Subsidy Redeemed Done&subheadid=<?php echo $_GET['id'];?>">
                               <div class="card mb-4 bg-pattern-3-dark">
                                    <div class="card-body" style="padding-top: 15px; padding-bottom: 15px; padding-right: 5px; padding-left: 10px;">
                                        <div class="d-flex align-items-center">
                                          
                                            <div class="ml-3">
                                                <h6 class="mb-0" style="color: black;">Subsidy Redeemed <br>Done</h6>
                                        <div class="text-large"><?php  
                                                            $sql4 = "SELECT * FROM tbl_users tu LEFT JOIN tbl_installations ti ON ti.CustId=tu.id WHERE tu.ProjectId='".$_GET['prjid']."' AND tu.ProjectSubHeadId='".$_GET['id']."' AND tu.Roll=5 AND ti.SubsidyRedeemed='Yes' AND tu.ProjectType=2";
                                                            echo $rncnt4 = getRow($sql4);

                                                        ?></div>
                                        
                                     </div>
                                        </div>
                                     </div>
                                </div></a>
                            </div>

                            <div class="col-sm-6 col-xl-2">
                                   <a href="total-installations.php?projid=<?php echo $_GET['prjid'];?>&page=12&SubsidyRedeemed=No&title=Subsidy Redeemed Pending&subheadid=<?php echo $_GET['id'];?>">
                               <div class="card mb-4 bg-pattern-3-dark">
                                    <div class="card-body" style="padding-top: 15px; padding-bottom: 15px; padding-right: 5px; padding-left: 10px;">
                                        <div class="d-flex align-items-center">
                                          
                                            <div class="ml-3">
                                                <h6 class="mb-0" style="color: black;">Subsidy Redeemed Pending</h6>
                                        <div class="text-large"><?php  
                                                            $sql4 = "SELECT * FROM tbl_users tu LEFT JOIN tbl_installations ti ON ti.CustId=tu.id WHERE tu.ProjectId='".$_GET['prjid']."' AND tu.ProjectSubHeadId='".$_GET['id']."' AND tu.Roll=5 AND ti.SubsidyRedeemed='No' AND tu.ProjectType=2";
                                                            echo $rncnt4 = getRow($sql4);

                                                        ?></div>
                                        
                                     </div>
                                        </div>
                                     </div>
                                </div></a>
                            </div>
                             

                           <div class="col-sm-6 col-xl-2">
                                  <a href="total-installations.php?projid=<?php echo $_GET['prjid'];?>&page=13&SubsidyAproved=Yes&title=Subsidy Aproved Done&subheadid=<?php echo $_GET['id'];?>">
                               <div class="card mb-4 bg-pattern-3-dark">
                                    <div class="card-body" style="padding-top: 15px; padding-bottom: 15px; padding-right: 5px; padding-left: 10px;">
                                        <div class="d-flex align-items-center">
                                          
                                            <div class="ml-3">
                                                <h6 class="mb-0" style="color: black;">Subsidy Approved<br> Done</h6>
                                        <div class="text-large"><?php  
                                                            $sql4 = "SELECT * FROM tbl_users tu LEFT JOIN tbl_installations ti ON ti.CustId=tu.id WHERE tu.ProjectId='".$_GET['prjid']."' AND tu.ProjectSubHeadId='".$_GET['id']."' AND tu.Roll=5 AND ti.SubsidyAproved='Yes' AND tu.ProjectType=2";
                                                            echo $rncnt4 = getRow($sql4);

                                                        ?></div>
                                        
                                     </div>
                                        </div>
                                     </div>
                                </div></a>
                            </div>
                             

                           <div class="col-sm-6 col-xl-2">
                                 <a href="total-installations.php?projid=<?php echo $_GET['prjid'];?>&page=14&SubsidyAproved=No&title=Subsidy Aproved Pending&subheadid=<?php echo $_GET['id'];?>">
                               <div class="card mb-4 bg-pattern-3-dark">
                                    <div class="card-body" style="padding-top: 15px; padding-bottom: 15px; padding-right: 5px; padding-left: 10px;">
                                        <div class="d-flex align-items-center">
                                          
                                            <div class="ml-3">
                                                <h6 class="mb-0" style="color: black;">Subsidy Approved<br> Pending</h6>
                                        <div class="text-large"><?php  
                                                            $sql4 = "SELECT * FROM tbl_users tu LEFT JOIN tbl_installations ti ON ti.CustId=tu.id WHERE tu.ProjectId='".$_GET['prjid']."' AND tu.ProjectSubHeadId='".$_GET['id']."' AND tu.Roll=5 AND ti.SubsidyAproved='No' AND tu.ProjectType=2";
                                                            echo $rncnt4 = getRow($sql4);

                                                        ?></div>
                                        
                                     </div>
                                        </div>
                                     </div>
                                </div></a>
                            </div>
                            
                            
                            <div class="col-sm-6 col-xl-2">
                                  <a href="total-installations.php?projid=<?php echo $_GET['prjid'];?>&page=13&SubsidyDisbursed=Yes&title=Subsidy Disbursed Done&subheadid=<?php echo $_GET['id'];?>">
                               <div class="card mb-4 bg-pattern-3-dark">
                                    <div class="card-body" style="padding-top: 15px; padding-bottom: 15px; padding-right: 5px; padding-left: 10px;">
                                        <div class="d-flex align-items-center">
                                          
                                            <div class="ml-3">
                                                <h6 class="mb-0" style="color: black;">Subsidy Disbursed<br> Done</h6>
                                        <div class="text-large"><?php  
                                                            $sql4 = "SELECT * FROM tbl_users tu LEFT JOIN tbl_installations ti ON ti.CustId=tu.id WHERE tu.ProjectId='".$_GET['prjid']."' AND tu.ProjectSubHeadId='".$_GET['id']."' AND tu.Roll=5 AND ti.SubsidyDisbursed='Yes' AND tu.ProjectType=2";
                                                            echo $rncnt4 = getRow($sql4);

                                                        ?></div>
                                        
                                     </div>
                                        </div>
                                     </div>
                                </div></a>
                            </div>
                             

                           <div class="col-sm-6 col-xl-2">
                                 <a href="total-installations.php?projid=<?php echo $_GET['prjid'];?>&page=14&SubsidyDisbursed=No&title=Subsidy Disbursed Pending&subheadid=<?php echo $_GET['id'];?>">
                               <div class="card mb-4 bg-pattern-3-dark">
                                    <div class="card-body" style="padding-top: 15px; padding-bottom: 15px; padding-right: 5px; padding-left: 10px;">
                                        <div class="d-flex align-items-center">
                                          
                                            <div class="ml-3">
                                                <h6 class="mb-0" style="color: black;">Subsidy Disbursed<br> Pending</h6>
                                        <div class="text-large"><?php  
                                                            $sql4 = "SELECT * FROM tbl_users tu LEFT JOIN tbl_installations ti ON ti.CustId=tu.id WHERE tu.ProjectId='".$_GET['prjid']."' AND tu.ProjectSubHeadId='".$_GET['id']."' AND tu.Roll=5 AND ti.SubsidyDisbursed='No' AND tu.ProjectType=2";
                                                            echo $rncnt4 = getRow($sql4);

                                                        ?></div>
                                        
                                     </div>
                                        </div>
                                     </div>
                                </div></a>
                            </div>

                            
                           <div class="col-sm-6 col-xl-2">
                                  <a href="total-installations.php?projid=<?php echo $_GET['prjid'];?>&page=15&PaymentStatus=2&title=Total Site Complete Payment Received&subheadid=<?php echo $_GET['id'];?>">
                               <div class="card mb-4 bg-pattern-3-dark">
                                    <div class="card-body" style="padding-top: 15px; padding-bottom: 15px; padding-right: 5px; padding-left: 10px;">
                                        <div class="d-flex align-items-center">
                                          
                                            <div class="ml-3">
                                                <h6 class="mb-0" style="color: black;">Total Site Complete Payment Received</h6>
                                        <div class="text-large"><?php  
                                                            $sql4 = "SELECT * FROM tbl_users tu LEFT JOIN tbl_installations ti ON ti.CustId=tu.id WHERE tu.ProjectId='".$_GET['prjid']."' AND tu.ProjectSubHeadId='".$_GET['id']."' AND tu.Roll=5 AND ti.PaymentStatus='2' AND tu.ProjectType=2";
                                                            echo $rncnt4 = getRow($sql4);

                                                        ?></div>
                                        
                                     </div>
                                        </div>
                                     </div>
                                </div></a>
                            </div>
                            
                          
                          <div class="col-sm-6 col-xl-2">
                                  <a href="total-installations.php?projid=<?php echo $_GET['prjid'];?>&page=16&PaymentStatus=1&title=Total Site Parital Payment Received&subheadid=<?php echo $_GET['id'];?>">
                               <div class="card mb-4 bg-pattern-3-dark">
                                    <div class="card-body" style="padding-top: 15px; padding-bottom: 15px; padding-right: 5px; padding-left: 10px;">
                                        <div class="d-flex align-items-center">
                                          
                                            <div class="ml-3">
                                                <h6 class="mb-0" style="color: black;">Total Site Parital Payment<br>Received </h6>
                                        <div class="text-large"><?php  
                                                            $sql4 = "SELECT * FROM tbl_users tu LEFT JOIN tbl_installations ti ON ti.CustId=tu.id WHERE tu.ProjectId='".$_GET['prjid']."' AND tu.ProjectSubHeadId='".$_GET['id']."' AND tu.Roll=5 AND ti.PaymentStatus='1' AND tu.ProjectType=2";
                                                            echo $rncnt4 = getRow($sql4);

                                                        ?></div>
                                        
                                     </div>
                                        </div>
                                     </div>
                                </div></a>
                            </div>
                              
                                
                                <div class="col-sm-6 col-xl-2">
                                  <a href="project-abstract.php?projid=<?php echo $_GET['prjid'];?>&subheadid=<?php echo $_GET['id'];?>&title=<?php echo $_GET['name'];?>">
                               <div class="card mb-4 bg-pattern-3-dark" style="height: 100px;">
                                    <div class="card-body" style="padding-top: 15px; padding-bottom: 15px; padding-right: 5px; padding-left: 10px;">
                                        <div class="d-flex align-items-center">
                                          
                                            <div class="ml-3">
                                                <h6 class="mb-0" style="color: black;padding-top: 25px;">Project Abstract</h6>
                                        <div class="text-large"></div>
                                        
                                     </div>
                                        </div>
                                     </div>
                                </div></a>
                            </div>
                          
                            
                            
                    </div>