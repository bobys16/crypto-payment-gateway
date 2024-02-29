 <section class="dashboard-section transactions">
        <div class="overlay pt-120">
            <div class="container" >
                <div class="head-area">
                    <div class="row">
                        <div class="col-lg-5 col-md-4">
                            <h4>Cashout</h4>
                        </div>
                        
                    </div>
                </div>
                <div class="row">
                    <div class="col-xl-12">
                        <div class="transactions-main">
                            <div class="top-items">
                                <h6>All the cashout history log are saved in this page, whether it from API or manually from cointopay <br><br>
                                <button data-bs-toggle="modal" data-bs-target="#cashout" style="align-content: center;"class="cmn-btn">New Withdraw</button></h6>
                                
                            </div>
                           
                                 <div class="table-responsive">
                                            <table class="table">
                                                <thead>
                                                    <tr>
                                                        <th scope="col">Merchant Name / Trans ID</th>
                                                        <th scope="col">Destination</th>
                                                        <th scope="col">Status</th>
                                                        <th scope="col">Amount</th>
                                                        <th scope="col">Source</th>
                                                        <th scope="col">Hash</th>
                                                        <th scope="col">Date</th>
                                                        <th scope="col">Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                      <?php
                                                      $get_app = mysqli_query($connect, "SELECT id FROM app WHERE user_id = '".$uid."'");
                                                        $applist = array();
                                                        while($apl = mysqli_fetch_assoc($get_app)) {
                                                            $applist[]=$apl['id'];
                                                        }
                                    $lists = "'".implode("','",$applist)."'";

                                    $c = mysqli_query($connect, "SELECT a.*, b.name FROM withdraw a INNER JOIN app b ON a.app_id = b.id WHERE b.id IN(".$lists.") ORDER BY a.id DESC");
                                    while($row = mysqli_fetch_assoc($c)) {
                                        $j = '';
                                        $d = '';
                                        if($row['status'] == 'Pending'){
                                            $a = 'inprogress';
                                        }else if($row['status'] == 'Complete'){
                                            $a = 'completed';
                                        }else{
                                            $a = 'cancelled';
                                        }
                                        if($row['id'] == null){
                                            $d = 'display:none';
                                        }else{
                                             $j = 'display:none';
                                        }
                                        if($row['from_api'] == 1){
                                            $h = 'Remote / From API';
                                        }else{
                                            $h = 'CointoPay Platform';
                                        }
                                        
                                    ?>
                                                    
                                                         <!-- <tr data-bs-toggle="modal" data-bs-target="#transactionsMod"> -->
                                                        <tr style="<?= $d;?>">
                                                        <th scope="row">
                                                            <p><?= substr_replace($row['app_name'], "...", 10);?></p>
                                                            <p class="mdr">#<?= $row['id'];?></p>
                                                        </th>
                                                         <td>
                                                            <p class="mdr"><?= $row['to_address'];?></p>
                                                        </td>
                                                        <td>
                                                            <p class="<?= $a;?>"><?= $row['status'];?></p>
                                                        </td>
                                                         <td>
                                                            <p class="mdr"><?= $row['amount'];?> <?= $row['network'];?></p>
                                                        </td>
                                                         <td>
                                                            <p class="mdr"><?= $h;?> </p>
                                                        </td>
                                                         <td>
                                                            <p class="mdr"><?= substr_replace($row['tx_hash'], "...", 10);?> </p>
                                                        </td>
                                                        <td>
                                                            <p><?= date('h:i:s',$row['created_at']);?></p>
                                                            <p class="mdr"><?= date('d M Y',$row['created_at']);?></p>
                                                        </td>
                                                        
                                                       
                                                        <td>
                                                            <a href="javascript:void(0);" onclick="setCookie('c_id', '<?= $row['id'];?>', '8')" view="true" path="cashout_detail" class="cmn-btn">Cashout Detail</a>
                                                        </td>
                                                    </tr>
                                                <?php }?>
                                                <tr style="<?= $j;?>"><th>==EMPTY==</th></tr>
                                                </tbody>
                                            </table>
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
 function setCookie(name,value,days) {
    var expires = "";
    if (days) {
        var date = new Date();
        date.setTime(date.getTime() + (days*24*60*60*1000));
        expires = "; expires=" + date.toUTCString();
    }
    document.cookie = name + "=" + (value || "")  + expires + "; path=/";
}
</script>