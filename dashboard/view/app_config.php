<?php
$trx_id = $_COOKIE['app_id'];
$q = mysqli_query($connect, "SELECT * FROM app WHERE id='".$trx_id."'");
$f = mysqli_fetch_array($q);
  $sum = mysqli_fetch_array(mysqli_query($connect, "SELECT SUM(balance) as sum FROM app WHERE id='".$trx_id."'"));

$config = mysqli_query($connect, "SELECT * FROM app_config WHERE app_id='".$trx_id."'");
$f_config = mysqli_fetch_array($config);

$bsc = mysqli_fetch_array(mysqli_query($connect, "SELECT * FROM app_balance WHERE app_id='".$trx_id."' AND network='BSC'"));
$ether = mysqli_fetch_array(mysqli_query($connect, "SELECT * FROM app_balance WHERE app_id='".$trx_id."' AND network='ETHER'"));
$tron = mysqli_fetch_array(mysqli_query($connect, "SELECT * FROM app_balance WHERE app_id='".$trx_id."' AND network='TRON'"));

if($f_config['enabled'] > 0){
    $c = 'checked=""';
}else{
    $c = '';
}

if($f_config['withdraw_enable'] > 0){
    $w = 'checked=""';
}else{
    $w = '';
}
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
                                        <h6><?= $f['name'];?></h6>
                                        <p class="active-status">Active</p>
                                    </div>
                                </div>
                                <div class="owner-info">
                                    <ul>
                                        <li>
                                            <p>(LEGO):</p>
                                            <span class="mdr"><?= $bsc['balance'];?> LEGO</span>
                                        </li>
                                         <li>
                                            <p>(USDT ETHER):</p>
                                            <span class="mdr"><?= $ether['balance'];?> USDT</span>
                                        </li>
                                         <li>
                                            <p>(USDT TRON):</p>
                                            <span class="mdr"><?= $tron['balance'];?> USDT</span>
                                        </li>
                                        <li>
                                            <p>Created At:</p>
                                            <span class="mdr"><?= date('d-M-Y h:i:s ', $f['created_at']);?> </span>
                                        </li>
                                        <li>
                                            <p>Total Transaction:</p>
                                            <span class="mdr"><?= $sum['sum'];?></span>
                                        </li>
                                    </ul>
                                </div>
                                <div class="owner-action">
                                    <a href="javascript:void(0)" view="true" path="app_list">
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
                                    <li class="nav-item" role="presentation">
                                        <button  view="true" path="tx_list" class="nav-link" id="security-tab" role="tab" aria-controls="security"
                                            aria-selected="false">Transactions</button>
                                    </li>
                                   <li class="nav-item" role="presentation">
                                        <button class="nav-link" id="security-tab" type="button" role="tab" aria-controls="security"
                                            data-bs-toggle="modal" data-bs-target="#cashout" aria-selected="false">Withdraw</button>
                                    </li>
                                    
                                </ul>
                                <div class="tab-content mt-40">
                                   
                                    <div class="tab-pane show active" id="security" role="tabpanel"
                                        aria-labelledby="security-tab" style="display:block;">
                                       
                                        <div class="change-pass mb-40">
                                            <div class="row">
                                                <div class="col-sm-12">
                                                    <h5>App Config</h5>
                                                    <p>You can set the scope of your app in this current tab.</p>
                                                </div>
                                                <br>
                                                <div class="col-sm-12">
                                                    <form callrouter="true" action="config_process">
                                                        <div class="row justify-content-center">
                                                             <div class="col-md-12">
                                                                <div class="single-input">
                                                                    <label for="confirm-password">Secret Key</label>
                                                                    <input type="text" value="<?= $f['secret'];?>" disabled>
                                                                </div>
                                                            </div>
                                                            <div class="notification-single">
                                                                <h6>App Enabled</h6>
                                                                <label class="switch">
                                                                    <input type="checkbox" name="enable" <?= $c;?>>
                                                                    <span class="slider round"></span>
                                                                </label>
                                                            </div>
                                                              <div class="notification-single">
                                                                <h6>Withdraw Enabled</h6>
                                                                <label class="switch">
                                                                    <input type="checkbox" <?= $w;?> name="withdraw_enabled" >
                                                                    <span class="slider round"></span>
                                                                </label>
                                                            </div>
                                                            <div class="col-md-12">
                                                                <div class="single-input">
                                                                    <label for="confirm-password">Withdraw Limit</label>
                                                                    <input type="text" name="withdraw_limit" value="<?= $f_config['withdraw_limit'];?>" placeholder="Please input the withdraw limit per 1 transaction handled">
                                                                </div>
                                                            </div>
                                                            <div class="col-md-12">
                                                                <div class="single-input">
                                                                    <label for="confirm-password">Withdraw Daily</label>
                                                                    <input type="text"name="withdraw_daily"  value="<?= $f_config['withdraw_daily'];?>" placeholder="Please input the amount withdraw limit daily">
                                                                </div>
                                                            </div>
                                                            <div class="col-md-12">
                                                                <div class="single-input">
                                                                    <label for="confirm-password">Callback URL</label>
                                                                    <input type="text"name="callback_url"  value="<?= $f_config['callback_url'];?>" placeholder="Please input the callback url">
                                                                </div>
                                                            </div>
                                                            <div class="col-md-12">
                                                                <div class="single-input">
                                                                    <label for="confirm-password">Registered Ip (Seperated by comma)</label>
                                                                    <input type="text"name="registered_ip"  value="<?= $f_config['registered_ip'];?>" placeholder="Please input the ip whitelist, seperated by comma if you want to add more than 1 ip">
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
                                                                    <button class="cmn-btn w-100">Update App Config</button>
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