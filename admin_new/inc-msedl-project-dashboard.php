 <div class="row">
                             
                            
               
                         
                      
                      <div class="col-sm-6 col-xl-2">
                                 <a href="view-total-selections.php?projid=103&page=1&title=Total Selection&subheadid=<?php echo $_GET['id'];?>">
                               <div class="card mb-4 bg-pattern-3-dark">
                                    <div class="card-body" style="padding-top: 15px; padding-bottom: 15px; padding-right: 5px; padding-left: 10px;">
                                        <div class="d-flex align-items-center">
                                          
                                            <div class="ml-3">
                                                <h6 class="mb-0" style="color: black;">Total<br> Selection</h6>
                                        <div class="text-large"><?php  
                                                            $sql4 = "SELECT * FROM tbl_users WHERE ProjectId='103' AND ProjectSubHeadId='".$_GET['id']."' AND Roll=5 AND ProjectType=1";
                                                            echo $rncnt4 = getRow($sql4);

                                                        ?></div>
                                        
                                     </div>
                                        </div>
                                     </div>
                                </div></a>
                            </div>
                            
                            
                             <div class="col-sm-6 col-xl-2">
                               <a href="view-total-selections.php?projid=103&page=2&FieldSurveyDetails=1&title=Total JSR Done&subheadid=<?php echo $_GET['id'];?>">
                               <div class="card mb-4 bg-pattern-3-dark">
                                    <div class="card-body" style="padding-top: 15px; padding-bottom: 15px; padding-right: 5px; padding-left: 10px;">
                                        <div class="d-flex align-items-center">
                                          
                                            <div class="ml-3">
                                                <h6 class="mb-0" style="color: black;">Total JSR Done</h6>
                                        <div class="text-large"><?php  
                                                            $sql47 = "SELECT * FROM tbl_users WHERE ProjectId='103' AND ProjectSubHeadId='".$_GET['id']."' AND Roll=5 AND FieldSurveyDetails=1 AND ProjectType=1";
                                                            echo $rncnt47 = getRow($sql47);

                                                        ?></div>
                                        
                                     </div>
                                        </div>
                                     </div>
                                </div></a>
                            </div>
                            
                          
                            <div class="col-sm-6 col-xl-2">
                               <a href="view-total-selections.php?projid=103&page=3&FieldSurveyDetails=0&title=Total JSR Pending&subheadid=<?php echo $_GET['id'];?>">
                               <div class="card mb-4 bg-pattern-3-dark">
                                    <div class="card-body" style="padding-top: 15px; padding-bottom: 15px; padding-right: 5px; padding-left: 10px;">
                                        <div class="d-flex align-items-center">
                                          
                                            <div class="ml-3">
                                                <h6 class="mb-0" style="color: black;">Total JSR Pending</h6>
                                        <div class="text-large"><?php  
                                                            $sql4 = "SELECT * FROM tbl_users WHERE ProjectId='103' AND ProjectSubHeadId='".$_GET['id']."' AND Roll=5 AND FieldSurveyDetails=0 AND ProjectType=1";
                                                            echo $rncnt4 = getRow($sql4);

                                                        ?></div>
                                        
                                     </div>
                                        </div>
                                     </div>
                                </div></a>
                            </div>
                           

                          <div class="col-sm-6 col-xl-2">
                                <a href="view-total-selections.php?projid=103&page=4&FieldSurveyDetails=2&title=Total JSR Cancellation&subheadid=<?php echo $_GET['id'];?>">
                               <div class="card mb-4 bg-pattern-3-dark">
                                    <div class="card-body" style="padding-top: 15px; padding-bottom: 15px; padding-right: 5px; padding-left: 10px;">
                                        <div class="d-flex align-items-center">
                                          
                                            <div class="ml-3">
                                                <h6 class="mb-0" style="color: black;">Total Cancellation</h6>
                                        <div class="text-large"><?php  
                                                            $sql4 = "SELECT * FROM tbl_users WHERE ProjectId='103' AND ProjectSubHeadId='".$_GET['id']."' AND Roll=5 AND FieldSurveyDetails=2 AND ProjectType=1";
                                                            echo $rncnt4 = getRow($sql4);

                                                        ?></div>
                                        
                                     </div>
                                        </div>
                                     </div>
                                </div></a>
                            </div>
                     
                            
                           <div class="col-sm-6 col-xl-2">
                                <a href="total-installations.php?projid=103&page=7&DispatchStatus=Yes&title=Total Material Dispatch Done&subheadid=<?php echo $_GET['id'];?>">
                               <div class="card mb-4 bg-pattern-3-dark">
                                    <div class="card-body" style="padding-top: 15px; padding-bottom: 15px; padding-right: 5px; padding-left: 10px;">
                                        <div class="d-flex align-items-center">
                                          
                                            <div class="ml-3">
                                                <h6 class="mb-0" style="color: black;">Total Material Dispatch Done</h6>
                                        <div class="text-large"><?php  
                                                            $sql4 = "SELECT tdo.*,tu.Fname,tu.Phone,tu.Address FROM tbl_sell tdo 
                    LEFT JOIN tbl_users tu ON tdo.CustId=tu.id WHERE tdo.Inst_Dispatcher_Otp_Verify=1 AND tu.ProjectId='103' AND tu.ProjectSubHeadId='".$_GET['id']."' AND tu.Roll=5 AND tu.ProjectType=1";
                                                            echo $rncnt4 = getRow($sql4);

                                                        ?></div>
                                        
                                     </div>
                                        </div>
                                     </div>
                                </div></a>
                            </div>
                             

                         <div class="col-sm-6 col-xl-2">
                               <a href="total-installations.php?projid=103&page=7&DispatchStatus=No&title=Total Material Dispatch Pending&subheadid=<?php echo $_GET['id'];?>">
                               <div class="card mb-4 bg-pattern-3-dark">
                                    <div class="card-body" style="padding-top: 15px; padding-bottom: 15px; padding-right: 5px; padding-left: 10px;">
                                        <div class="d-flex align-items-center">
                                          
                                            <div class="ml-3">
                                                <h6 class="mb-0" style="color: black;">Total Material Dispatch Pending</h6>
                                        <div class="text-large"><?php  

                                        
                                                          $sql4 = "SELECT tdo.*,tu.Fname,tu.Phone,tu.Address FROM tbl_sell tdo 
                    LEFT JOIN tbl_users tu ON tdo.CustId=tu.id WHERE tdo.Inst_Dispatcher_Otp_Verify=1 AND tu.ProjectId='103' AND tu.ProjectSubHeadId='".$_GET['id']."' AND tu.Roll=5 AND tu.ProjectType=1";
                                                            $rncnt4 = getRow($sql4);
                                                            echo $rncnt47 - $rncnt4;
                                                        ?></div>
                                        
                                     </div>
                                        </div>
                                     </div>
                                </div></a>
                            </div>


                            <div class="col-sm-6 col-xl-2">
                               <a href="total-installations.php?projid=103&page=7&InstallStatus=Yes&title=Total Installation Done&subheadid=<?php echo $_GET['id'];?>">
                               <div class="card mb-4 bg-pattern-3-dark">
                                    <div class="card-body" style="padding-top: 15px; padding-bottom: 15px; padding-right: 5px; padding-left: 10px;">
                                        <div class="d-flex align-items-center">
                                          
                                            <div class="ml-3">
                                                <h6 class="mb-0" style="color: black;">Total Installation<br> Done</h6>
                                        <div class="text-large"><?php  
                                                            $sql4 = "SELECT * FROM tbl_installations ti 
                    LEFT JOIN tbl_users tu ON ti.CustId=tu.id WHERE tu.ProjectId='103' AND tu.ProjectSubHeadId='".$_GET['id']."' AND tu.Roll=5 AND ti.InstallStatus='Yes' AND ti.Type=2";
                                                            echo $rncnt4 = getRow($sql4);

                                                        ?></div>
                                        
                                     </div>
                                        </div>
                                     </div>
                                </div></a>
                            </div>
                             
                            <div class="col-sm-6 col-xl-2">
                               <a href="total-installations.php?projid=103&page=8&InstallStatus=No&title=Total Installation Pending&subheadid=<?php echo $_GET['id'];?>">
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
                               <a href="total-installations.php?projid=103&page=9&PoInspection=Yes&title=Total ICR Sign&subheadid=<?php echo $_GET['id'];?>">
                               <div class="card mb-4 bg-pattern-3-dark">
                                    <div class="card-body" style="padding-top: 15px; padding-bottom: 15px; padding-right: 5px; padding-left: 10px;">
                                        <div class="d-flex align-items-center">
                                          
                                            <div class="ml-3">
                                                <h6 class="mb-0" style="color: black;">Total ICR Sign</h6>
                                        <div class="text-large"><?php  
                                                            $sql4 = "SELECT * FROM tbl_installations ti 
                    LEFT JOIN tbl_users tu ON ti.CustId=tu.id WHERE tu.ProjectId='103' AND tu.ProjectSubHeadId='".$_GET['id']."' AND tu.Roll=5 AND ti.PoInspection='Yes' AND ti.Type=2";
                                                            echo $rncnt4 = getRow($sql4);

                                                        ?></div>
                                        
                                     </div>
                                        </div>
                                     </div>
                                </div></a>
                            </div>
                          
                           
                            <div class="col-sm-6 col-xl-2">
                               <a href="total-installations.php?projid=103&page=10&PoInspection=No&title=Total ICR Sign Pending&subheadid=<?php echo $_GET['id'];?>">
                               <div class="card mb-4 bg-pattern-3-dark">
                                    <div class="card-body" style="padding-top: 15px; padding-bottom: 15px; padding-right: 5px; padding-left: 10px;">
                                        <div class="d-flex align-items-center">
                                          
                                            <div class="ml-3">
                                                <h6 class="mb-0" style="color: black;">Total ICR Sign Pending</h6>
                                        <div class="text-large"><?php  
                                                            echo $rncnt47 - $rncnt4;

                                                        ?></div>
                                        
                                     </div>
                                        </div>
                                     </div>
                                </div></a>
                            </div>

                            <div class="col-sm-6 col-xl-2">
                                <a href="total-installations.php?projid=103&page=11&SentToHo=Yes&title=Files Sent to HO Done&subheadid=<?php echo $_GET['id'];?>">
                               <div class="card mb-4 bg-pattern-3-dark">
                                    <div class="card-body" style="padding-top: 15px; padding-bottom: 15px; padding-right: 5px; padding-left: 10px;">
                                        <div class="d-flex align-items-center">
                                          
                                            <div class="ml-3">
                                                <h6 class="mb-0" style="color: black;">Files Sent to HO Done</h6>
                                        <div class="text-large"><?php  
                                                            $sql4 = "SELECT * FROM tbl_users tu LEFT JOIN tbl_installations ti ON ti.CustId=tu.id WHERE tu.ProjectId='103' AND tu.ProjectSubHeadId='".$_GET['id']."' AND tu.Roll=5 AND ti.SentToHo='Yes' AND ti.Type=2";
                                                            echo $rncnt4 = getRow($sql4);

                                                        ?></div>
                                        
                                     </div>
                                        </div>
                                     </div>
                                </div></a>
                            </div>

                            
                            <div class="col-sm-6 col-xl-2">
                                 <a href="total-installations.php?projid=103&page=12&SentToHo=No&title=Files Sent to HO Pending&subheadid=<?php echo $_GET['id'];?>">
                               <div class="card mb-4 bg-pattern-3-dark">
                                    <div class="card-body" style="padding-top: 15px; padding-bottom: 15px; padding-right: 5px; padding-left: 10px;">
                                        <div class="d-flex align-items-center">
                                          
                                            <div class="ml-3">
                                                <h6 class="mb-0" style="color: black;">Files Sent to HO Pending</h6>
                                        <div class="text-large"><?php  
                                                            $sql4 = "SELECT * FROM tbl_users tu LEFT JOIN tbl_installations ti ON ti.CustId=tu.id WHERE tu.ProjectId='103' AND tu.ProjectSubHeadId='".$_GET['id']."' AND tu.Roll=5 AND ti.SentToHo='No' AND ti.Type=2";
                                                            echo $rncnt4 = getRow($sql4);

                                                        ?></div>
                                        
                                     </div>
                                        </div>
                                     </div>
                                </div></a>
                            </div>
                             
                            
                            <div class="col-sm-6 col-xl-2">
                                 <a href="total-installations.php?projid=103&page=11&FileInHand=Yes&title=File In Hand Done&subheadid=<?php echo $_GET['id'];?>">
                               <div class="card mb-4 bg-pattern-3-dark">
                                    <div class="card-body" style="padding-top: 15px; padding-bottom: 15px; padding-right: 5px; padding-left: 10px;">
                                        <div class="d-flex align-items-center">
                                          
                                            <div class="ml-3">
                                                <h6 class="mb-0" style="color: black;">File In Hand Done</h6>
                                        <div class="text-large"><?php  
                                                            $sql4 = "SELECT * FROM tbl_users tu LEFT JOIN tbl_installations ti ON ti.CustId=tu.id WHERE tu.ProjectId='103' AND tu.ProjectSubHeadId='".$_GET['id']."' AND tu.Roll=5 AND ti.FileInHand='Yes' AND ti.Type=2";
                                                            echo $rncnt4 = getRow($sql4);

                                                        ?></div>
                                        
                                     </div>
                                        </div>
                                     </div>
                                </div></a>
                            </div>
                           
                            
                            <div class="col-sm-6 col-xl-2">
                                <a href="total-installations.php?projid=103&page=12&FileInHand=No&title=File In Hand Pending&subheadid=<?php echo $_GET['id'];?>">
                               <div class="card mb-4 bg-pattern-3-dark">
                                    <div class="card-body" style="padding-top: 15px; padding-bottom: 15px; padding-right: 5px; padding-left: 10px;">
                                        <div class="d-flex align-items-center">
                                          
                                            <div class="ml-3">
                                                <h6 class="mb-0" style="color: black;">File In Hand Pending</h6>
                                        <div class="text-large"><?php  
                                                            $sql4 = "SELECT * FROM tbl_users tu LEFT JOIN tbl_installations ti ON ti.CustId=tu.id WHERE tu.ProjectId='103' AND tu.ProjectSubHeadId='".$_GET['id']."' AND tu.Roll=5 AND ti.FileInHand='No' AND ti.Type=2";
                                                            echo $rncnt4 = getRow($sql4);

                                                        ?></div>
                                        
                                     </div>
                                        </div>
                                     </div>
                                </div></a>
                            </div>
                            
                            
                            <div class="col-sm-6 col-xl-2">
                                <a href="total-installations.php?projid=103&page=11&CircleOfficeStatus=Yes&title=Total Bill Submit to Circle Office&subheadid=<?php echo $_GET['id'];?>">
                               <div class="card mb-4 bg-pattern-3-dark">
                                    <div class="card-body" style="padding-top: 15px; padding-bottom: 15px; padding-right: 5px; padding-left: 10px;">
                                        <div class="d-flex align-items-center">
                                          
                                            <div class="ml-3">
                                                <h6 class="mb-0" style="color: black;">Total Bill Submit to Circle Office</h6>
                                        <div class="text-large"><?php  
                                                            $sql4 = "SELECT * FROM tbl_users tu LEFT JOIN tbl_installations ti ON ti.CustId=tu.id WHERE tu.ProjectId='103' AND tu.ProjectSubHeadId='".$_GET['id']."' AND tu.Roll=5 AND ti.CircleOfficeStatus='Yes' AND ti.Type=2";
                                                            echo $rncnt4 = getRow($sql4);

                                                        ?></div>
                                        
                                     </div>
                                        </div>
                                     </div>
                                </div></a>
                            </div>
                          
                            <div class="col-sm-6 col-xl-2">
                               <a href="total-installations.php?projid=103&page=12&RmsIntegrationStatus=Yes&title=Total RMS Integration&subheadid=<?php echo $_GET['id'];?>">
                               <div class="card mb-4 bg-pattern-3-dark">
                                    <div class="card-body" style="padding-top: 15px; padding-bottom: 15px; padding-right: 5px; padding-left: 10px;">
                                        <div class="d-flex align-items-center">
                                          
                                            <div class="ml-3">
                                                <h6 class="mb-0" style="color: black;">Total RMS Integration</h6>
                                        <div class="text-large"><?php  
                                                            $sql4 = "SELECT * FROM tbl_users tu LEFT JOIN tbl_installations ti ON ti.CustId=tu.id WHERE tu.ProjectId='103' AND tu.ProjectSubHeadId='".$_GET['id']."' AND tu.Roll=5 AND ti.RmsIntegrationStatus='Yes' AND ti.Type=2";
                                                            echo $rncnt4 = getRow($sql4);

                                                        ?></div>
                                        
                                     </div>
                                        </div>
                                     </div>
                                </div></a>
                            </div>    
 
                            
                            <div class="col-sm-6 col-xl-2">
                               <a href="total-installations.php?projid=103&page=13&InsuranceApproval=Yes&title=Total Insurance Done&subheadid=<?php echo $_GET['id'];?>">
                               <div class="card mb-4 bg-pattern-3-dark">
                                    <div class="card-body" style="padding-top: 15px; padding-bottom: 15px; padding-right: 5px; padding-left: 10px;">
                                        <div class="d-flex align-items-center">
                                          
                                            <div class="ml-3">
                                                <h6 class="mb-0" style="color: black;">Total Insurance<br> Done</h6>
                                        <div class="text-large"><?php  
                                                            $sql4 = "SELECT * FROM tbl_users tu LEFT JOIN tbl_installations ti ON ti.CustId=tu.id WHERE tu.ProjectId='103' AND tu.ProjectSubHeadId='".$_GET['id']."' AND tu.Roll=5 AND ti.InsuranceApproval='Yes' AND ti.Type=2";
                                                            echo $rncnt4 = getRow($sql4);

                                                        ?></div>
                                        
                                     </div>
                                        </div>
                                     </div>
                                </div></a>
                            </div> 
                           
                            <div class="col-sm-6 col-xl-2">
                               <a href="total-installations.php?projid=103&page=14&InsuranceApproval=No&title=Total Insurance Pending&subheadid=<?php echo $_GET['id'];?>">
                               <div class="card mb-4 bg-pattern-3-dark">
                                    <div class="card-body" style="padding-top: 15px; padding-bottom: 15px; padding-right: 5px; padding-left: 10px;">
                                        <div class="d-flex align-items-center">
                                          
                                            <div class="ml-3">
                                                <h6 class="mb-0" style="color: black;">Total Insurance<br> Pending</h6>
                                        <div class="text-large"><?php  
                                                            $sql4 = "SELECT * FROM tbl_users tu LEFT JOIN tbl_installations ti ON ti.CustId=tu.id WHERE tu.ProjectId='103' AND tu.ProjectSubHeadId='".$_GET['id']."' AND tu.Roll=5 AND ti.InsuranceApproval='No' AND ti.Type=2";
                                                            echo $rncnt4 = getRow($sql4);

                                                        ?></div>
                                        
                                     </div>
                                        </div>
                                     </div>
                                </div></a>
                            </div> 
                             
                            
                           <div class="col-sm-6 col-xl-2">
                              <a href="total-installations.php?projid=103&page=15&PaymentDone=Yes&title=Total Site Payment Received&subheadid=<?php echo $_GET['id'];?>">
                               <div class="card mb-4 bg-pattern-3-dark">
                                    <div class="card-body" style="padding-top: 15px; padding-bottom: 15px; padding-right: 5px; padding-left: 10px;">
                                        <div class="d-flex align-items-center">
                                          
                                            <div class="ml-3">
                                                <h6 class="mb-0" style="color: black;">Total Site Payment Received</h6>
                                        <div class="text-large"><?php  
                                                            $sql4 = "SELECT * FROM tbl_users tu LEFT JOIN tbl_installations ti ON ti.CustId=tu.id WHERE tu.ProjectId='103' AND tu.ProjectSubHeadId='".$_GET['id']."' AND tu.Roll=5 AND ti.PaymentDone='Yes' AND ti.Type=2";
                                                            echo $rncnt4 = getRow($sql4);

                                                        ?></div>
                                        
                                     </div>
                                        </div>
                                     </div>
                                </div></a>
                            </div> 
                            
                          <div class="col-sm-6 col-xl-2">
                              <a href="total-installations.php?projid=103&page=16&PaymentDone=No&title=Total Site Payment Pending&subheadid=<?php echo $_GET['id'];?>">
                               <div class="card mb-4 bg-pattern-3-dark">
                                    <div class="card-body" style="padding-top: 15px; padding-bottom: 15px; padding-right: 5px; padding-left: 10px;">
                                        <div class="d-flex align-items-center">
                                          
                                            <div class="ml-3">
                                                <h6 class="mb-0" style="color: black;">Total Site Payment<br> Pending</h6>
                                        <div class="text-large"><?php  
                                                            $sql4 = "SELECT * FROM tbl_users tu LEFT JOIN tbl_installations ti ON ti.CustId=tu.id WHERE tu.ProjectId='103' AND tu.ProjectSubHeadId='".$_GET['id']."' AND tu.Roll=5 AND ti.PaymentDone='No' AND ti.Type=2";
                                                            echo $rncnt4 = getRow($sql4);

                                                        ?></div>
                                        
                                     </div>
                                        </div>
                                     </div>
                                </div></a>
                            </div> 
                             
                           
 <div class="col-sm-6 col-xl-2">
                                  <a href="project-abstract.php?projid=103&subheadid=<?php echo $_GET['id'];?>&title=<?php echo $_GET['name'];?>">
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
                            
                            <div class="col-sm-6 col-xl-2">
                                  <a href="project-abstract-of-abstract.php?projid=103&subheadid=<?php echo $_GET['id'];?>&title=MEDA">
                               <div class="card mb-4 bg-pattern-3-dark" style="height: 100px;">
                                    <div class="card-body" style="padding-top: 15px; padding-bottom: 15px; padding-right: 5px; padding-left: 10px;">
                                        <div class="d-flex align-items-center">
                                          
                                            <div class="ml-3">
                                                <h6 class="mb-0" style="color: black;padding-top: 25px;">Project Abstract of Abstracts</h6>
                                        <div class="text-large"></div>
                                        
                                     </div>
                                        </div>
                                     </div>
                                </div></a>
                            </div>
  
                          
                            
                            
                    </div>