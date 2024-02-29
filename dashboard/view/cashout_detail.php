<?php
$trx_id = $_COOKIE['c_id'];
$q = mysqli_query($connect, "SELECT * FROM withdraw WHERE id='".$trx_id."'");
$f = mysqli_fetch_array($q);
$q1 = mysqli_query($connect, "SELECT * FROM app WHERE id='".$f['app_id']."'");
$f1 = mysqli_fetch_array($q1);
?>
<section class="dashboard-section pay step step-2 step-3">
        <div class="overlay pt-120">
            <div class="container">
                <div class="main-content">
                    <div class="head-area d-flex align-items-center justify-content-between">
                        <h4>Cashout Detail</h4>
                        <div class="icon-area">
                            <img src="assets/images/icon/support-icon.png" alt="icon">
                        </div>
                    </div>
                    <div class="choose-recipient">
                        <div class="step-area">
                            <h5>#<?= $f['id'];?></h5>
                        </div>
                        <div class="user-select">
                            <div class="single-user">
                                <div class="left d-flex align-items-center">
                                    <div class="img-area">
                                        <img src="assets/images/GREY.png" height="50px" width="50px" alt="image">
                                    </div>
                                    <div class="text-area">
                                        <p><?= $f1['name'];?></p>
                                    </div>
                                </div>
                               
                            </div>
                        </div>
                    </div>
                    <div class="payment-details">
                        <div class="top-area">
                            <h6>Cashout Details</h6>
                           
                        </div>
                        <div class="row">
                            <div class="col-xl-12">
                                <ul class="details-list">
                                    <li>
                                        <span>Cashout Amount</span>
                                        <b><?= $f['amount'];?> <?= $f['network'];?></b>
                                    </li>
                                    <li>
                                        <span>Cashout Status</span>
                                        <b><?= $f['status'];?></b>
                                    </li>
                                    <li>
                                        <span>Cashout Destination</span>
                                        <b><?= $f['to_address'];?></b>
                                    </li>
                                    
                                     <li>
                                        <span>Cashout Source</span>
                                        <b><?php if($f['from_api'] == 1){
                                            echo 'Remote / From API';
                                        }else{
                                            echo 'CointoPay Platform';
                                        }?></b>
                                    </li>
                                    <li>
                                        <span>Cashout Fee</span>
                                        <b><?= $f['amount']*0.5/100;?></b>
                                    </li>
                                    <li>
                                        <span>Cashout Hash</span>
                                        <b><?= $f['tx_hash'];?></b>
                                    </li>
                                    <li>
                                        <span>Cashout Submited </span>
                                        <b><?= date('d-M-Y h:i:s ', $f['created_at']);?> </b>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                   
                </div>
            </div>
        </div>
    </section>