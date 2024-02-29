<!doctype html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>CointoPay - Crypto Payment Gateway</title>

    
    <link rel="stylesheet" href="/dashboard/assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="/dashboard/assets/css/fontawesome.min.css">
    <link rel="stylesheet" href="/dashboard/assets/css/jquery-ui.css">
    <link rel="stylesheet" href="/dashboard/assets/css/plugin/apexcharts.css">
    <link rel="stylesheet" href="/dashboard/assets/css/plugin/nice-select.css">
    <link rel="stylesheet" href="/dashboard/assets/css/arafat-font.css">
    <link rel="stylesheet" href="/dashboard/assets/css/plugin/animate.css">
    <link rel="stylesheet" href="/dashboard/assets/css/style.css">
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>

</head>

<body>
    <!-- start preloader -->
    <div class="preloader" id="preloader"></div>
    <!-- end preloader -->

    <!-- Scroll To Top Start-->
    <a href="javascript:void(0)" class="scrollToTop"><i class="fas fa-angle-double-up"></i></a>
    <!-- Scroll To Top End -->

    <!-- header-section start -->
    <header class="header-section">
        <div class="overlay">
            <div class="container">
                <div class="row d-flex header-area">
                    <div class="navbar-area d-flex align-items-center justify-content-between">
                        <div class="sidebar-icon">
                            <img src="/dashboard/assets/images/icon/menu.png" alt="icon">
                        </div>
                       
                        <div class="dashboard-nav">
                            
                           
                            <div class="single-item user-area">
                                <div class="profile-area d-flex align-items-center">
                                    <span class="user-profile">
                                        <img src="assets/images/GREY.png" height="50px" width="50px" alt="User">
                                    </span>
                                    <i class="fa-solid fa-sort-down"></i>
                                </div>
                                <div class="main-area user-content">
                                    <div class="head-area d-flex align-items-center">
                                        <div class="profile-img">
                                            <img src="assets/images/BLUE.png" alt="User">
                                        </div>
                                        <div class="profile-head">
                                            <a href="javascript:void(0);">
                                                <h5><?= $user['name'];?></h5>
                                            </a>
                                            <p class="wallet-id">Company: <?= $user['company'];?></p>
                                        </div>
                                    </div>
                                    <ul>
                                        <li class="border-area">
                                            <a href="javascript:void(0)" view="true" path="setting"><i class="fas fa-cog"></i>Settings</a>
                                        </li>
                                        <li>
                                            <a href="logout"><i class="fas fa-sign-out"></i>Logout</a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="sidebar-wrapper">
                        <div class="close-btn">
                            <i class="fa-solid fa-xmark"></i>
                        </div>
                        <div class="sidebar-logo">
                            <a href="/"> <img src="assets/images/LG_BLUE.png" class="logo" width="150" alt="logo"></a>
                        </div>
                        <ul>
                            <li class="active">
                                <a href="/dashboard">
                                    <img src="/dashboard/assets/images/icon/dashboard.png" alt="Dashboard"> Dashboard
                                </a>
                            </li>
                            <li>
                                <a href="javascript:void(0);" view="true" path="transactions">
                                    <img src="/dashboard/assets/images/icon/transactions.png" alt="Transactions"> Transactions
                                </a>
                            </li>
                           
                            <li>
                                <a href="javascript:void(0);" view="true" path="app_list">
                                    <img src="/dashboard/assets/images/icon/recipients.png" alt="Recipients"> App List
                                </a>
                            </li>
                           
                            <li>
                                <a href="javascript:void(0);" view="true" path="withdraw">
                                    <img src="/dashboard/assets/images/icon/withdraw.png" alt="Withdraw"> Withdraw Money
                                </a>
                            </li>
                        </ul>
                        <ul class="bottom-item pb-120">
                            <li>
                                <a href="javascript:void(0);" view="true" path="setting">
                                    <img src="/dashboard/assets/images/icon/account.png" alt="Account"> Account
                                </a>
                            </li>
                            <li>
                                <a href="javascript:void(0)">
                                    <img src="/dashboard/assets/images/icon/support.png" alt="Support"> Support
                                </a>
                            </li>
                            <li>
                                <a href="logout">
                                    <img src="/dashboard/assets/images/icon/quit.png" alt="Quit"> Logout
                                </a>
                            </li>
                        </ul>
                        <div class="invite-now">
                            <div class="img-area">
                                <img src="/dashboard/assets/images/invite-now-illus.png" alt="Image">
                            </div>
                            <p>Invite your friend and get $25</p>
                            <a href="javascript:void(0)" class="cmn-btn">Invite Now</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </header>
    <!-- header-section end -->

    <!-- Dashboard Section start -->
    <div content-loader="true">
    <section class="dashboard-section">
        <div class="overlay pt-120">
            <div class="container" >
                <div class="row">
                    <div class="col-xl-8 col-lg-7">
                        <div class="section-content">
                            <div class="acc-details">
                                <div class="top-area">
                                    <div class="left-side">
                                        <h5>Hi, <?= $user['name'];?>!</h5>
                                        <?php
                                                      $app_list = array();
                                                      $q_app = mysqli_query($connect, "SELECT * FROM app WHERE user_id='".$uid."'");
                                                      while($ap = mysqli_fetch_assoc($q_app)) {
                                                        $app_list[]=$ap['id'];
                                                      }
                                                      $app_listed = "'".implode("','",$app_list)."'";
                                                      echo "<!-- ".$app_listed." -->";

                                        $legoPrice = json_decode(file_get_contents("https://api.coingecko.com/api/v3/simple/price?ids=lego-coin&vs_currencies=usd"),true);
                                        $app_wallet = mysqli_query($connect, "SELECT * FROM app_balance WHERE app_id IN(".$app_listed.")");
                                        $sum_total = 0;
                                        while($app_bal = mysqli_fetch_assoc($app_wallet)) {
                                            if($app_bal['currency'] == "LEGO") {
                                                $sum_total=$sum_total+ ($legoPrice['lego-coin']['usd'] * $app_bal['balance']);
                                            } else {
                                                $sum_total=$sum_total+ $app_bal['balance'];
                                            }
                                        }

                                        $tx_query = mysqli_query($connect, "SELECT * FROM transaction WHERE app_id IN(".$app_listed.") ORDER BY trx_id DESC LIMIT 1");
                                        $last_tx = 0;
                                        if(mysqli_num_rows($tx_query) > 0) {
                                            $last = mysqli_fetch_array($tx_query);
                                            $last_tx = $last['usd_amount'];
                                        }
                                        ?>
                                        <h2>$<?= number_format($sum_total,2);?></h2>
                                        <h5 class="receive">Last Payment <span>$<?= number_format($last_tx,2);?></span></h5>
                                    </div>
                                    <div class="right-side">
                                        
                                        <div class="right-bottom">
                                            <h4>$0</h4>
                                            <h5>Withdraw</h5>
                                        </div>
                                    </div>
                                </div>
                                <div class="bottom-area">
                                    <div class="left-side">
                                        <a href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#recipientsMod" class="cmn-btn">New Merchant</a>
                                        <a href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#addBalance" class="cmn-btn">Add Balance</a>
                                        <a href="javascript:void(0);" view="true" path="withdraw" class="cmn-btn">Withdraw</a><a href="/doc" class="cmn-btn">API Documentation</a>
                                    </div>
                                   
                                </div>
                            </div>
                            <div class="transactions-area mt-40">
                                <div class="section-text">
                                    <h5>Transactions</h5>
                                    <p>Updated every several minutes</p>
                                </div>
                                <div class="top-area d-flex align-items-center justify-content-between">
                                    <ul class="nav nav-tabs" role="tablist">
                                        <li class="nav-item" role="presentation">
                                            <button class="nav-link active" id="latest-tab" data-bs-toggle="tab"
                                                data-bs-target="#latest" type="button" role="tab" aria-controls="latest"
                                                aria-selected="true">Latest</button>
                                        </li>
                                       
                                    </ul>
                                    <div class="view-all d-flex align-items-center">
                                        <a href="javascript:void(0)" view="true" path="transactions">View All</a>
                                        <img src="/dashboard/assets/images/icon/right-arrow.png" alt="icon">
                                    </div>
                                </div>
                                <div class="tab-content mt-40">
                                    <div class="tab-pane fade show active" id="latest" role="tabpanel"
                                        aria-labelledby="latest-tab">
                                        <div class="table-responsive">
                                            <table class="table">
                                                <thead>
                                                    <tr>
                                                        <th scope="col">#ID</th>
                                                        <th scope="col">Amount</th>
                                                        <th scope="col">Status</th>
                                                        <th scope="col">Date</th>
                                                        <th scope="col">Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                      <?php

                                    $c = mysqli_query($connect, "SELECT * FROM transaction WHERE app_id IN(".$app_listed.") ORDER BY trx_id DESC");
                                    $tx_empty = true;
                                    while($row = mysqli_fetch_assoc($c)) {
                                        if($row['status'] == 'Waiting'){
                                            $a = 'inprogress';
                                        }else if($row['status'] == 'Complete'){
                                            $a = 'completed';
                                        }else{
                                            $a = 'cancelled';
                                        }
                                        $tx_empty = false;

                                        $app_detail = mysqli_fetch_array(mysqli_query($connect, "SELECT * FROM app WHERE id='".$row['app_id']."'"));
                                        
                                    ?>
                                                    
                                                         <!-- <tr data-bs-toggle="modal" data-bs-target="#transactionsMod"> -->
                                                        <tr>
                                                            <th scope="col">
                                                                <p><?= $app_detail['name']?></p>
                                                                <p class="mdr">#<?= $row['trx_id'];?></p>
                                                            </th>
                                                            <th scope="col">
                                                                <p><?= $row['real_amount'];?></p>
                                                                <p class="mdr">Fee <?= $row['real_amount']*0.5/100;?></p>
                                                            </td>
                                                            <th scope="col">
                                                                <p class="<?= $a;?>"><?= $row['status'];?></p>
                                                            </th>
                                                            <th scope="col">
                                                                <p><?= date('h:i:s',$row['created_at']);?></p>
                                                                <p class="mdr"><?= date('d M Y',$row['created_at']);?></p>
                                                            </th>
                                                            <th scope="col">
                                                                <a href="javascript:void(0);" onclick="setCookie('tx_id', '<?= $row['trx_id'];?>', '8')" view="true" path="transaction_detail" class="cmn-btn">Transaction Detail</a>
                                                            </th>
                                                    </tr>
                                                <?php }?>
                                                <tr style="<?= $tx_empty ? '':'display: none;';?>"><th>==EMPTY==</th></tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                   
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-4 col-lg-5">
                        <div class="side-items">
                            <div class="single-item">
                                <div class="section-text d-flex align-items-center justify-content-between">
                                    <h6>App List</h6>
                                    <div class="view-all d-flex align-items-center">
                                        <a href="javascript:void(0)" view="true" path="app_list">View All</a>
                                        <img src="/dashboard/assets/images/icon/right-arrow.png" alt="icon">
                                    </div>
                                </div>
                                <ul class="recipients-item">
                                    <?php
                                    $no_app = true;
                                    $c = mysqli_query($connect, "SELECT * FROM app WHERE user_id='".$uid."'");
                                    while($row = mysqli_fetch_assoc($c)) {
                                        $r_id = mysqli_query($connect, "SELECT * FROM transaction WHERE app_id='".$row['id']."'");
                                        $no_app = false;
                                    ?>
                                    <li>
                                        <p class="left d-flex align-items-center">
                                            <img src="/dashboard/assets/images/recipients-1.png" alt="icon">
                                            <span class="info">
                                                <span><?= substr_replace($row['secret'], "...", 10);?></span>
                                                <span><?= $row['name'];?></span>
                                            </span>
                                        </p>
                                        <p class="right">
                                            <span> +<?= mysqli_num_rows($r_id);?></span>
                                            <span>Transaction</span>
                                        </p>
                                    </li>
                                   <?php } ?>
                                   <li style="<?= $no_app ? '':'display: none;';?>">
                                     <SPAN CLASS="align-items-center"><CENTER> ==NO MERCHANT== </CENTER></SPAN>
                                   </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    </div>
    <!-- Dashboard Section end -->

<!-- POPUP -->

 <!-- Add Recipients Popup start -->
    <div class="add-recipients">
        <div class="container">
            <div class="row">
                <div class="col-lg-6">
                    <div class="modal fade" id="recipientsMod" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header justify-content-between">
                                    <h6>Create New Merchant</h6>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"><i class="fa-solid fa-xmark"></i></button>
                                </div>
                               
                                <div class="tab-content">
                                    <div class="tab-pane fade show active" id="company" role="tabpanel" aria-labelledby="company-tab">
                                       
                                        <form action="create_merchant" callrouter="true">
                                            <div class="row justify-content-center">
                                                <div class="col-md-12">
                                                    <div class="single-input">
                                                        <label for="companyfname">Merchant Name</label>
                                                        <input type="text" id="companyfname" name="merchant" placeholder="Please input your merchant name">
                                                    </div>
                                                </div>
                                                <div class="col-md-12">
                                                    <div class="single-input">
                                                        <label for="companyfname">Current Password</label>
                                                        <input type="password" name="curr_pass" placeholder="Please input your current password">
                                                    </div>
                                                </div>
                                                <div class="col-12">
                                                    <div class="btn-border w-100">
                                                        <button type="Submit" class="cmn-btn w-100">Create new Merchant</button>
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

     <div class="add-recipients">
        <div class="container">
            <div class="row">
                <div class="col-lg-6">
                    <div class="modal fade" id="addBalance" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header justify-content-between">
                                    <h6>Add Balance</h6>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"><i class="fa-solid fa-xmark"></i></button>
                                </div>
                               
                                <div class="tab-content">
                                    <div class="tab-pane fade show active" id="company" role="tabpanel" aria-labelledby="company-tab">
                                       
                                        <form action="inject" callrouter="true">
                                            <div class="row justify-content-center">
                                                <div class="col-md-12">
                                                    <div class="single-select">
                                                        <label for="cardNumber" style="display:block !important;">App Source</label>
                                                        <select name="key" style="padding: 10px 20px;color: var(--para-color);width: 100%;font-family: var(--body-font);background: var(--bs-white);border: 1px solid #eeecf7;border-radius: 10px;">
                                                            <?php
                                                              $get_app = mysqli_query($connect, "SELECT * FROM app WHERE user_id = '".$uid."'");
                                                                while($apl = mysqli_fetch_assoc($get_app)) {
                                                                    ?>
                                                            <option value="<?= $apl['secret'];?>"><?= $apl['name'];?></option>
                                                        <?php } ?>

                                                        </select>
                                                    </div>
                                                </div>
                                                 <div class="col-md-12">
                                                    <div class="single-select">
                                                        <label for="cardNumber" style="display:block !important;">Select Currency</label>
                                                        <select name="currency" style="padding: 10px 20px;color: var(--para-color);width: 100%;font-family: var(--body-font);background: var(--bs-white);border: 1px solid #eeecf7;border-radius: 10px;">
                                                            <?php
                                                              $get_app = mysqli_query($connect, "SELECT * FROM supported_currency");
                                                                while($apl = mysqli_fetch_assoc($get_app)) {
                                                                    ?>
                                                            <option value="<?= $apl['currency_name'];?>"><?= $apl['currency_name'];?></option>
                                                        <?php } ?>

                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-12">
                                                    <div class="single-select">
                                                        <label for="cardNumber" style="display:block !important;">Select Currency Type</label>
                                                        <select name="type" style="padding: 10px 20px;color: var(--para-color);width: 100%;font-family: var(--body-font);background: var(--bs-white);border: 1px solid #eeecf7;border-radius: 10px;">
                                                            <?php
                                                              $get_app = mysqli_query($connect, "SELECT * FROM supported_currency");
                                                                while($apl = mysqli_fetch_assoc($get_app)) {
                                                                    ?>
                                                            <option value="<?= $apl['currency_network'];?>"><?= $apl['currency_network'];?> FOR <?= $apl['currency_name'];?></option>
                                                        <?php } ?>

                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-12">
                                                    <div class="single-input">
                                                        <label for="companyfname"  style="display:block !important;">Amount</label>
                                                        <input type="text" name="amount" placeholder="Please input amount you want to add">
                                                    </div>
                                                </div>
                                                <div class="col-12">
                                                    <div class="btn-border w-100">
                                                        <button type="Submit" class="cmn-btn w-100">Add Balance</button>
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



    <!-- Card Popup start -->
    <div class="card-popup">
        <div class="container">
            <div class="row">
                <div class="col-lg-6">
                    <div class="modal fade" id="cardMod" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header justify-content-center">
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"><i class="fa-solid fa-xmark"></i></button>
                                </div>
                                <div class="main-content">
                                    <div class="top-area mb-40 mt-40 text-center">
                                        <div class="card-area mb-30">
                                            <img src="/dashboard/assets/images/visa-card-2.png" alt="image">
                                        </div>
                                        <div class="text-area">
                                            <h5>CoinPaymentspayment Card </h5>
                                            <p>Linked: 01 Jun 2021</p>
                                        </div>
                                    </div>
                                    <div class="tab-area d-flex align-items-center justify-content-between">
                                        <ul class="nav nav-tabs mb-30" role="tablist">
                                            <li class="nav-item" role="presentation">
                                                <button class="btn-link" id="cancel-tab" data-bs-toggle="tab"
                                                    data-bs-target="#cancel" type="button" role="tab"
                                                    aria-controls="cancel" aria-selected="false">
                                                    <img src="/dashboard/assets/images/icon/limit.png" alt="icon">
                                                    Set transfer limit
                                                </button>
                                            </li>
                                            <li class="nav-item" role="presentation">
                                                <button class="d-none" id="limit-tab" data-bs-toggle="tab"
                                                    data-bs-target="#limit" type="button" role="tab"
                                                    aria-controls="limit" aria-selected="true"></button>
                                            </li>
                                            <li>
                                                <button>
                                                    <img src="/dashboard/assets/images/icon/remove.png" alt="icon">
                                                    Remove from Linked
                                                </button>
                                            </li>
                                        </ul>
                                    </div>
                                    <div class="tab-content mt-30">
                                        <div class="tab-pane fade show active" id="limit" role="tabpanel"
                                            aria-labelledby="limit-tab">
                                            <div class="bottom-area">
                                                <p class="history">Transaction History : <span>20</span></p>
                                                <ul>
                                                    <li>
                                                        <p class="left">
                                                            <span>03:00 PM</span>
                                                            <span>17 Oct, 2020</span>
                                                        </p>
                                                        <p class="right">
                                                            <span>$160.48</span>
                                                            <span>Withdraw</span>
                                                        </p>
                                                    </li>
                                                    <li>
                                                        <p class="left">
                                                            <span>01:09 PM</span>
                                                            <span>15 Oct, 2021</span>
                                                        </p>
                                                        <p class="right">
                                                            <span>$106.58</span>
                                                            <span>Withdraw</span>
                                                        </p>
                                                    </li>
                                                    <li>
                                                        <p class="left">
                                                            <span>04:00 PM</span>
                                                            <span>12 Oct, 2020</span>
                                                        </p>
                                                        <p class="right">
                                                            <span>$176.58</span>
                                                            <span>Withdraw</span>
                                                        </p>
                                                    </li>
                                                    <li>
                                                        <p class="left">
                                                            <span>06:00 PM</span>
                                                            <span>25 Oct, 2020</span>
                                                        </p>
                                                        <p class="right">
                                                            <span>$167.85</span>
                                                            <span>Withdraw</span>
                                                        </p>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                        <div class="tab-pane fade" id="cancel" role="tabpanel"
                                            aria-labelledby="cancel-tab">
                                            <div class="main-area">
                                                <div class="transfer-area">
                                                    <p>Set a transfer limit for CoinPaymentspayment Card</p>
                                                    <p class="mdr">Transfer Limit</p>
                                                </div>
                                                <form action="dashboard.html#">
                                                    <div class="input-area">
                                                        <input class="xxlr" placeholder="400.00" type="number">
                                                        <select>
                                                            <option value="1">USD</option>
                                                            <option value="2">USD</option>
                                                            <option value="3">USD</option>
                                                        </select>
                                                    </div>
                                                    <div
                                                        class="bottom-area d-flex align-items-center justify-content-between">
                                                        <a href="javascript:void(0)" class="cmn-btn cancel">Cancel and
                                                            Back</a>
                                                        <a href="javascript:void(0)" class="cmn-btn">Confirm Transfer
                                                            Limit</a>
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
    <!-- Card Popup start -->

    <!-- Add Card Popup start -->
    <div class="add-card">
        <div class="container">
            <div class="row">
                <div class="col-lg-6">
                    <div class="modal fade" id="addcardMod" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header justify-content-between">
                                    <h6>Add Card</h6>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"><i class="fa-solid fa-xmark"></i></button>
                                </div>
                                <form action="dashboard.html#">
                                    <div class="row justify-content-center">
                                        <div class="col-md-12">
                                            <div class="single-input">
                                                <label for="cardNumber">Card Number</label>
                                                <input type="text" id="cardNumber"
                                                    placeholder="5890 - 6858 - 6332 - 9843">
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="single-input">
                                                <label for="cardHolder">Card Holder</label>
                                                <input type="text" id="cardHolder" placeholder="Alfred Davis">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="single-input">
                                                <label for="month">Month</label>
                                                <input type="text" id="month" placeholder="12">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="single-input">
                                                <label for="year">Year</label>
                                                <input type="text" id="year" placeholder="2025">
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="btn-border w-100">
                                                <button class="cmn-btn w-100">Add Card</button>
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
    <!-- Add Card Popup start -->



    <!-- Transactions Popup start -->
    <div class="transactions-popup">
        <div class="container">
            <div class="row">
                <div class="col-lg-6">
                    <div class="modal fade" id="transactionsMod" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header justify-content-between">
                                    <h5>Transaction Details</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"><i class="fa-solid fa-xmark"></i></button>
                                </div>
                                <div class="main-content">
                                    <div class="row">
                                        <div class="col-sm-5 text-center">
                                            <div class="icon-area">
                                                <img src="/dashboard/assets/images/icon/transaction-details-icon.png" alt="icon">
                                            </div>
                                            <div class="text-area">
                                                <h6>Envato Pty Ltd</h6>
                                                <p>16 Jan 2022</p>
                                                <h3>717.14 USD</h3>
                                                <p class="com">Completed</p>
                                            </div>
                                        </div>
                                        <div class="col-sm-7">
                                            <div class="right-area">
                                                <h6>Transaction Details</h6>
                                                <ul class="payment-details">
                                                    <li>
                                                        <span>Payment Amount</span>
                                                        <span>718.64 USD</span>
                                                    </li>
                                                    <li>
                                                        <span>Fee</span>
                                                        <span>1.50 USD</span>
                                                    </li>
                                                    <li>
                                                        <span>Total Amount</span>
                                                        <span>717.14 USD</span>
                                                    </li>
                                                </ul>
                                                <ul class="payment-info">
                                                    <li>
                                                        <p>Payment From</p>
                                                        <span class="mdr">Envato Pty Ltd</span>
                                                    </li>
                                                    <li>
                                                        <p>Payment Description</p>
                                                        <span class="mdr">Envato Feb 2022 Member Payment</span>
                                                    </li>
                                                    <li>
                                                        <p>Transaction  ID:</p>
                                                        <span class="mdr">6559595979565959895559595</span>
                                                    </li>
                                                </ul>
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
    <!-- Transactions Popup start -->

    <!--==================================================================-->
    <script src="/dashboard/assets/js/jquery.min.js"></script>
    <script src="/dashboard/assets/js/bootstrap.min.js"></script>
    <script src="/dashboard/assets/js/jquery-ui.js"></script>
    <script src="/dashboard/assets/js/plugin/apexcharts.js"></script>
    <script src="/dashboard/assets/js/plugin/jquery.nice-select.min.js"></script>
    <script src="/dashboard/assets/js/plugin/waypoint.min.js"></script>
    <script src="/dashboard/assets/js/plugin/wow.min.js"></script>
    <script src="/dashboard/assets/js/plugin/plugin.js"></script>
    <script src="/dashboard/assets/js/main.js"></script>
    <script src="index.js?id=<?= time();?>"></script>
    <script>bind_form();</script>
</body>

</html>