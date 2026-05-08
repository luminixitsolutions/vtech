 <div class="row">
               <div class="col-sm-6 col-xl-3">
                                <a href="view-leads-converted-to-order.php?stateid=<?php echo $_GET['id'];?>">
                               <div class="card bg-primary text-white ui-hover-icon mb-4 bg-pattern-3">
                                        <div class="card-body text-center">
                                          <h2><?php  
                        $sql4 = "SELECT tu.* FROM tbl_rooftop_lead_quotation ts 
                         LEFT JOIN tbl_users tu ON ts.CustId=tu.id 
                         WHERE ts.OppConverted=1 AND tu.Roll=5 AND tu.ProjectType='2' AND tu.StateId='".$_GET['id']."'";
                        echo $rncnt4 = getRow($sql4); ?></h2>
                                        <h6 class="mb-0">Total No Of Leads Converted To Order</h6>
                                        <i class="lnr lnr-users hov-icon"></i>
                                     </div>
                                </div></a>
                            </div>
                            
                            
                            <div class="col-sm-6 col-xl-3">
                                <a href="view-rooftop-total-selections.php?stateid=<?php echo $_GET['id'];?>&page=2&FieldSurveyDetails=1&title=Total Survey Done">
                               <div class="card bg-success text-white ui-hover-icon mb-4 bg-pattern-3">
                                        <div class="card-body text-center">
                                          <h2><?php  
                                                            $sql47 = "SELECT * FROM tbl_users WHERE StateId='".$_GET['id']."' AND Roll=5 AND FieldSurveyDetails=1 AND ProjectType=2";
                                                            echo $rncnt47 = getRow($sql47);

                                                        ?></h2>
                                        <h6 class="mb-0">Total Survey Done</h6>
                                        <i class="lnr lnr-users hov-icon"></i>
                                     </div>
                                </div></a>
                            </div>
 
                          
                          <div class="col-sm-6 col-xl-3">
                                <a href="view-rooftop-total-selections.php?stateid=<?php echo $_GET['id'];?>&page=3&FieldSurveyDetails=0&title=Total Survey Pending">
                               <div class="card bg-danger text-white ui-hover-icon mb-4 bg-pattern-3">
                                        <div class="card-body text-center">
                                          <h2><?php  
                                                            $sql4 = "SELECT * FROM tbl_users WHERE StateId='".$_GET['id']."' AND Roll=5 AND FieldSurveyDetails=0 AND ProjectType=2";
                                                            echo $rncnt4 = getRow($sql4);

                                                        ?></h2>
                                        <h6 class="mb-0">Total Survey Pending</h6>
                                        <i class="lnr lnr-users hov-icon"></i>
                                     </div>
                                </div></a>
                            </div>
                            
                            <div class="col-sm-6 col-xl-3">
                                <a href="view-rooftop-total-selections.php?stateid=<?php echo $_GET['id'];?>&page=4&FieldSurveyDetails=2&title=Total Order Finalized">
                               <div class="card bg-warning text-white ui-hover-icon mb-4 bg-pattern-3">
                                        <div class="card-body text-center">
                                          <h2><?php  
                                                            $sql4 = "SELECT tq.* FROM tbl_rooftop_quotation tq INNER JOIN tbl_users tu ON tq.CustId=tu.id WHERE tu.StateId='".$_GET['id']."' AND tu.Roll=5 AND tu.FieldSurveyDetails=2";
                                                            echo $rncnt4 = getRow($sql4);

                                                        ?></h2>
                                        <h6 class="mb-0">Total Order Finalized</h6>
                                        <i class="lnr lnr-users hov-icon"></i>
                                     </div>
                                </div></a>
                            </div>
                             

                           <div class="col-sm-6 col-xl-3">
                                <a href="total-rooftop-installations.php?stateid=<?php echo $_GET['id'];?>&&page=7&DispatchStatus=Yes&title=Total Material Dispatch Done">
                               <div class="card bg-info text-white ui-hover-icon mb-4 bg-pattern-3">
                                        <div class="card-body text-center">
                                          <h2><?php  
                    $sql4 = "SELECT tdo.*,tu.Fname,tu.Phone,tu.Address FROM tbl_rooftop_sell tdo 
                    LEFT JOIN tbl_users tu ON tdo.CustId=tu.id WHERE tdo.Inst_Dispatcher_Otp_Verify=1 AND tu.StateId='".$_GET['id']."' AND tu.Roll=5 AND tu.ProjectType=2";
                                        echo $rncnt4 = getRow($sql4);

                                                        ?></h2>
                                        <h6 class="mb-0">Total Material Dispatch Done</h6>
                                        <i class="lnr lnr-users hov-icon"></i>
                                     </div>
                                </div></a>
                            </div>

<div class="col-sm-6 col-xl-3">
                                <a href="total-rooftop-installations.php?stateid=<?php echo $_GET['id'];?>&page=7&DispatchStatus=No&title=Total Material Dispatch Pending">
                               <div class="card bg-secondary text-white ui-hover-icon mb-4 bg-pattern-3">
                                        <div class="card-body text-center">
                                          <h2><?php  

                                        
                                                            echo $rncnt47 - $rncnt4;
                                                        ?></h2>
                                        <h6 class="mb-0">Total Material Dispatch Pending</h6>
                                        <i class="lnr lnr-users hov-icon"></i>
                                     </div>
                                </div></a>
                            </div>
                             

                            <div class="col-sm-6 col-xl-3">
                                <a href="total-rooftop-installations.php?stateid=<?php echo $_GET['id'];?>&&page=7&InstallStatus=Yes&title=Total Installation Done">
                               <div class="card bg-primary text-white ui-hover-icon mb-4 bg-pattern-3">
                                        <div class="card-body text-center">
                                          <h2><?php  
                                                            $sql4 = "SELECT * FROM tbl_rooftop_installations ti 
                    LEFT JOIN tbl_users tu ON ti.CustId=tu.id WHERE tu.ProjectId='102' AND tu.Roll=5 AND ti.InstallStatus='Yes' AND tu.ProjectType=2";
                                                            echo $rncnt4 = getRow($sql4);

                                                        ?></h2>
                                        <h6 class="mb-0">Total Installation<br> Done</h6>
                                        <i class="lnr lnr-users hov-icon"></i>
                                     </div>
                                </div></a>
                            </div>

                             <div class="col-sm-6 col-xl-3">
                                <a href="total-rooftop-installations.php?stateid=<?php echo $_GET['id'];?>&&page=8&InstallStatus=No&title=Total Installation Pending">
                               <div class="card bg-success text-white ui-hover-icon mb-4 bg-pattern-3">
                                        <div class="card-body text-center">
                                          <h2><?php  
                                                           
                                                            echo $rncnt47 - $rncnt4;
                                                        ?></h2>
                                        <h6 class="mb-0">Total Installation<br> Pending</h6>
                                        <i class="lnr lnr-users hov-icon"></i>
                                     </div>
                                </div></a>
                            </div>
                            
                           
                           
                             <div class="col-sm-6 col-xl-3">
                                <a href="total-rooftop-installations.php?stateid=<?php echo $_GET['id'];?>&&page=9&PaymentDone=Yes&title=Total No Of Site Payment Received">
                               <div class="card bg-danger text-white ui-hover-icon mb-4 bg-pattern-3">
                                        <div class="card-body text-center">
                                          <h2><?php  
                                                            $sql4 = "SELECT * FROM tbl_rooftop_installations ti  
                    LEFT JOIN tbl_users tu ON ti.CustId=tu.id WHERE tu.stateid='".$_GET['id']."' AND tu.Roll=5 AND ti.PaymentDone='Yes' AND tu.ProjectType=2";
                                                            echo $rncnt4 = getRow($sql4);

                                                        ?></h2>
                                        <h6 class="mb-0">Total No Of Site Payment Received</h6>
                                        <i class="lnr lnr-users hov-icon"></i>
                                     </div>
                                </div></a>
                            </div>

 
 <div class="col-sm-6 col-xl-3">
                                <a href="total-rooftop-installations.php?stateid=<?php echo $_GET['id'];?>&&page=10&PoInspection=No&title=Total No Of Site Payment Received Pending">
                               <div class="card bg-warning text-white ui-hover-icon mb-4 bg-pattern-3">
                                        <div class="card-body text-center">
                                          <h2><?php  
                                                            echo $rncnt47 - $rncnt4;

                                                        ?></h2>
                                        <h6 class="mb-0">Total No Of Site Payment Received Pending</h6>
                                        <i class="lnr lnr-users hov-icon"></i>
                                     </div>
                                </div></a>
                            </div>
                            
 <div class="col-sm-6 col-xl-3">
                                <a href="#">
                               <div class="card bg-info text-white ui-hover-icon mb-4 bg-pattern-3">
                                        <div class="card-body text-center">
                                          <h2><?php  
                                                            $sql4 = "SELECT SUM(ti.PaidAmount) AS PaidAmount FROM tbl_users tu LEFT JOIN tbl_rooftop_installations ti ON ti.CustId=tu.id WHERE tu.stateid='".$_GET['id']."' AND tu.Roll=5 AND ti.PaymentDone='Yes' AND tu.ProjectType=2";
                                         $row4 = getRecord($sql4);
                                         echo "&#8377;".$row4['PaidAmount'];

                                                        ?></h2> 
                                        <h6 class="mb-0">Amount Of Payment Received</h6>
                                        <i class="lnr lnr-users hov-icon"></i>
                                     </div>
                                </div></a>
                            </div>
 
                            <div class="col-sm-6 col-xl-3">
                                  <a href="#">
                               <div class="card bg-secondary text-white ui-hover-icon mb-4 bg-pattern-3">
                                        <div class="card-body text-center">
                                          <h2><?php  
                                                            $sql4 = "SELECT SUM(ti.BalanceAmount) AS BalanceAmount FROM tbl_users tu LEFT JOIN tbl_rooftop_installations ti ON ti.CustId=tu.id WHERE tu.stateid='".$_GET['id']."' AND tu.Roll=5 AND ti.PaymentDone='Yes' AND tu.ProjectType=2";
                                                         $row4 = getRecord($sql4);
                                         echo "&#8377;".$row4['BalanceAmount'];


                                                        ?></h2>
                                        <h6 class="mb-0">Amount Of Payment Pending</h6>
                                        <i class="lnr lnr-users hov-icon"></i>
                                     </div>
                                </div></a>
                            </div>
                            
                            
                            <div class="col-sm-6 col-xl-3">
                                <a href="total-rooftop-installations.php?stateid=<?php echo $_GET['id'];?>&&page=11&MeterDiscom=Yes&title=Totoal Net Meter Connection Done">
                               <div class="card bg-info text-white ui-hover-icon mb-4 bg-pattern-3">
                                        <div class="card-body text-center">
                                          <h2><?php  
                                                            $sql4 = "SELECT * FROM tbl_users tu LEFT JOIN tbl_rooftop_installations ti ON ti.CustId=tu.id WHERE tu.StateId='".$_GET['id']."' AND tu.Roll=5 AND ti.MeterDiscom='Yes' AND tu.ProjectType=2";
                                                            echo $rncnt4 = getRow($sql4); 
 
                                                        ?></h2>
                                        <h6 class="mb-0">Totoal Net Meter Connection Done</h6>
                                        <i class="lnr lnr-users hov-icon"></i>
                                     </div>
                                </div></a>
                            </div>

                          
                            
                            
                    </div>