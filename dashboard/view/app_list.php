 <section class="dashboard-section transactions">
        <div class="overlay pt-120">
            <div class="container" >
                <div class="head-area">
                    <div class="row">
                        <div class="col-lg-5 col-md-4">
                            <h4>Application</h4>
                        </div>
                        
                    </div>
                </div>
                <div class="row">
                    <div class="col-xl-12">
                        <div class="transactions-main">
                            <div class="top-items">
                                <h6>All Listed App (Click the transaction to get the detail of it)</h6>
                                
                            </div>
                           
                                 <div class="table-responsive">
                                            <table class="table">
                                                <thead>
                                                    <tr>
                                                        <th scope="col">Merchant Name</th>
                                                        <th scope="col">Secret Key</th>
                                                        <th scope="col">Date Created</th>
                                                        <th scope="col">Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                     <?php
                                    $c = mysqli_query($connect, "SELECT * FROM app WHERE user_id='".$uid."'");
                                    while($row = mysqli_fetch_assoc($c)) {
                                        $r_id = mysqli_query($connect, "SELECT * FROM transaction WHERE app_id='".$row['id']."'");
                                        if($row['id'] == null){
                                            $z = '';
                                        }else{
                                            $z = 'display:none';
                                        }
                                    ?>
                                                    
                                                         <!-- <tr data-bs-toggle="modal" data-bs-target="#transactionsMod"> -->
                                                        <tr style="">
                                                        <th scope="row">
                                                            <p><?= $row['name'];?></p>
                                                        </th>
                                                          <td>
                                                            <p><?= substr_replace($row['secret'], "...", 10);?></p>
                                                           
                                                        </td>
                                                        <td>
                                                            <p><?= date('h:i:s',$row['created_at']);?></p>
                                                            <p class="mdr"><?= date('d M Y',$row['created_at']);?></p>
                                                        </td>
                                                      
                                                        <td>
                                                            <a href="javascript:void(0);" onclick="setCookie('app_id', '<?= $row['id'];?>', '8')" view="true" path="app_config" class="cmn-btn">App Config</a>
                                                        </td>
                                                    </tr>
                                                <?php }?>
                                                <tr style="<?= $z;?>"><th>==EMPTY==</th></tr>
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