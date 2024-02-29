 <section class="dashboard-section transactions">
        <div class="overlay pt-120">
            <div class="container" >
                <div class="head-area">
                    <div class="row">
                        <div class="col-lg-5 col-md-4">
                            <h4>Transactions</h4>
                        </div>
                        
                    </div>
                </div>
                <div class="row">
                    <div class="col-xl-12">
                        <div class="transactions-main">
                            <div class="top-items">
                                <h6>All Transactions (Click the transaction to get the detail of it)</h6>
                                
                            </div>
                           
                                 <div class="table-responsive">
                                            <table class="table">
                                                <thead>
                                                    <tr>
                                                        <th scope="col">Merchant Name / Trans ID</th>
                                                        <th scope="col">Date</th>
                                                        <th scope="col">Status</th>
                                                        <th scope="col">Amount</th>
                                                        <th scope="col">Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                      <?php
                                                      $app_list = array();
                                                      $q_app = mysqli_query($connect,"SELECT * FROM app WHERE user_id = '".$uid."'");
                                                      while($ap = mysqli_fetch_assoc($q_app)) {
                                                        $app_list[]=$ap['id'];
                                                      }
                                                      $app_listed = "'".implode("','",$app_list)."'";

                                    $c = mysqli_query($connect, "SELECT * FROM transaction WHERE app_id IN (".$app_listed.") ORDER BY trx_id DESC");
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
                                        if($row['trx_id'] == null){
                                            $d = 'display:none';
                                            $j = '';
                                        }else{
                                            $d = '';
                                             $j = 'display:none';
                                        }
                                        $app_detail = mysqli_fetch_array(mysqli_query($connect, "SELECT * FROM app WHERE id='".$row['app_id']."'"));
                                        
                                    ?>
                                                    
                                                         <!-- <tr data-bs-toggle="modal" data-bs-target="#transactionsMod"> -->
                                                    <tr>
                                                            <th scope="col">
                                                                <p><?= $app_detail['name']?></p>
                                                                <p class="mdr">#<?= $row['trx_id'];?></p>
                                                            </th>
                                                            <th scope="col">
                                                                <p><?= $row['amount'];?> <?= $row['currency']?></p>
                                                                <p class="mdr">Fee <?= ($row['amount']-$row['real_amount']);?> <?= $row['currency'] ?></p>
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
                                                <tr style="<?= $tx_empty ? '':'display: none';?>"><th>==EMPTY==</th></tr>
                                                </tbody>
                                            </table>
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