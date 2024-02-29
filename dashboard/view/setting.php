<?php
$q = mysqli_query($connect, "SELECT * FROM users WHERE id='".$_SESSION['id']."'");
$f = mysqli_fetch_array($q);
$cek = mysqli_query($connect, "SELECT * FROM app WHERE user_id='".$_SESSION['id']."'");
$total = mysqli_num_rows($cek);
?>
<section class="dashboard-section account">
        <div class="overlay pt-120">
            <div class="container">
                <div class="main-content">
                    <div class="row">
                        <div class="col-xxl-3 col-xl-4 col-md-6">
                            <div class="owner-details">
                                <div class="profile-area">
                                    <div class="profile-img">
           
                                        <img src="assets/images/BLUE.png" alt="image">
                                    </div>
                                    <div class="name-area">
                                        <h6><?= $f['company'];?></h6>
                                        <p class="active-status"><?= $f['name'];?></p>
                                    </div>
                                </div>
                                <div class="owner-info">
                                    <ul>
                                        <li>
                                            <p>Registered at:</p>
                                            <span class="mdr"><?= date('d-M-Y h:i:s ', $f['created_at']);?> </span>
                                        </li>
                                    </ul>
                                </div>
                                <div class="owner-action">
                                    <a href="/dashboard">
                                        <img src="assets/images/icon/logout.png" alt="image">
                                        Go Back
                                    </a>
                                    
                                </div>
                            </div>
                        </div>
                        <div class="col-xxl-9 col-xl-8">
                            <div class="tab-main">
                                <ul class="nav nav-tabs" role="tablist">
                                  
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link" id="security-tab" data-bs-toggle="tab"
                                            data-bs-target="#security" type="button" role="tab" aria-controls="security"
                                            aria-selected="true">Config</button>
                                    </li>
                                </ul>
                                <div class="tab-content mt-40">
                                   
                                    <div class="tab-pane show active" id="security" role="tabpanel"
                                        aria-labelledby="security-tab" style="display:block;">
                                       
                                        <div class="change-pass mb-40">
                                            <div class="row">
                                                <div class="col-sm-12">
                                                    <h5>Account Config</h5>
                                                    <p>You can set the information of your account in this current tab.</p>
                                                </div>
                                                <br>
                                                <div class="col-sm-12">
                                                    <form callrouter="true" action="update_account">
                                                        <div class="row justify-content-center">
                                                             <div class="col-md-12">
                                                                <div class="single-input">
                                                                    <label for="confirm-password">Full Name</label>
                                                                    <input type="text" value="<?= $f['name'];?>" name="f_name">
                                                                </div>
                                                            </div>
                                                            <div class="col-md-12">
                                                                <div class="single-input">
                                                                    <label for="confirm-password">Email</label>
                                                                    <input type="text" value="<?= $f['email'];?>" disabled>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-12">
                                                                <div class="single-input">
                                                                    <label for="confirm-password">Company</label>
                                                                    <input type="text" value="<?= $f['company'];?>" disabled>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-12">
                                                                <div class="single-input">
                                                                    <label for="confirm-password">Change Password ( NEW PASSWORD )</label>
                                                                    <input type="password" name="new_pw1" placeholder="Leave it blank if you are not decide to change your password">
                                                                </div>
                                                            </div>
                                                            <div class="col-md-12">
                                                                <div class="single-input">
                                                                    <label for="confirm-password">Change Password ( CONFIRM NEW PASSWORD )</label>
                                                                    <input type="password" name="new_pw2" placeholder="Leave it blank if you are not decide to change your password">
                                                                </div>
                                                            </div>
                                                            <div class="col-md-12">
                                                                <div class="single-input">
                                                                    <label for="confirm-password">Current Account Password</label>
                                                                    <input type="password" name="curr_pass"  placeholder="Insert your current account password">
                                                                </div>
                                                            </div>
                                                            <div class="col-md-12">
                                                                <div class="btn-border w-100">
                                                                    <button class="cmn-btn w-100">Update Account</button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                       
                                    </div>
                                   
                                   
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="add-card">
        <div class="container">
            <div class="row">
                <div class="col-lg-6">
                    <div class="modal fade" id="cashout" aria-modal="true" role="dialog" >
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header justify-content-between">
                                    <h6>Withdraw</h6>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"><i class="fa-solid fa-xmark"></i></button>
                                </div>
                                <form action="withdraw" callrouter="true">
                                    <div class="row justify-content-center">
                                        <div class="col-md-12">
                                            <div class="single-select">
                                                <label for="cardNumber">App Source</label>
                                                <select name="source_app" style="padding: 10px 20px;color: var(--para-color);width: 100%;font-family: var(--body-font);background: var(--bs-white);border: 1px solid #eeecf7;border-radius: 10px;">
                                                    <?php
                                                      $get_app = mysqli_query($connect, "SELECT * FROM app WHERE user_id = '".$uid."'");
                                                        while($apl = mysqli_fetch_assoc($get_app)) {
                                                            ?>
                                                    <option value="<?= $apl['id'];?>"><?= $apl['name'];?></option>
                                                <?php } ?>

                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="single-select">
                                                <label for="cardNumber">Select Currency</label>
                                                <select name="currency" style="padding: 10px 20px;color: var(--para-color);width: 100%;font-family: var(--body-font);background: var(--bs-white);border: 1px solid #eeecf7;border-radius: 10px;">
                                                    <?php
                                                      $get_app = mysqli_query($connect, "SELECT * FROM supported_currency");
                                                        while($apl = mysqli_fetch_assoc($get_app)) {
                                                            ?>
                                                    <option value="<?= $apl['id'];?>"><?= $apl['currency_network'];?> / <?= $apl['currency_name'];?></option>
                                                <?php } ?>

                                                </select>
                                            </div>
                                        </div>
                                         <div class="col-md-12">
                                            <div class="single-input">
                                                <label for="cardHolder">Receiver Address</label>
                                                <input type="text" name="receiver" placeholder="Please input receiver address of selected network currency">
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="single-input">
                                                <label for="cardHolder">Amount</label>
                                                <input type="text" name="amount" placeholder="Please input withdrawal amount">
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="single-input">
                                                <label for="cardHolder">Account Password</label>
                                                <input type="password" name="curr_pass" placeholder="Please input your current account password!">
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="btn-border w-100">
                                                <button class="cmn-btn w-100">Submit Withdrawal</button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </section>
    <script>
        bind_view();
    </script>