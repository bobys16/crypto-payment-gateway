<!doctype html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Crypto Online Payments</title>

    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/fontawesome.min.css">
    <link rel="stylesheet" href="assets/css/jquery-ui.css">
    <link rel="stylesheet" href="assets/css/plugin/nice-select.css">
    <link rel="stylesheet" href="assets/css/plugin/magnific-popup.css">
    <link rel="stylesheet" href="assets/css/plugin/slick.css">
    <link rel="stylesheet" href="assets/css/arafat-font.css">
    <link rel="stylesheet" href="assets/css/plugin/animate.css">
    <link rel="stylesheet" href="assets/css/style.css">
</head>

<body id="home">
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
                    <nav class="navbar navbar-expand-lg navbar-light">
                        <a class="navbar-brand" href="/">
                            <img src="dashboard/assets/images/LG_BLUE.png" class="logo" width="150" alt="logo">
                        </a>
                        <button class="navbar-toggler collapsed" type="button" data-bs-toggle="collapse"
                            data-bs-target="#navbar-content">
                            <i class="fas fa-bars"></i>
                        </button>
                        <div class="collapse navbar-collapse justify-content-center" id="navbar-content">
                            <ul class="navbar-nav mr-auto mb-2 mb-lg-0">
                                <li class="nav-item main-navbar">
                                    <a class="nav-link" href="coins.html">Supported Coins</a>
                                </li>
                                <li class="nav-item main-navbar">
                                    <a class="nav-link" href="merchant-tools.html">Merchant Tools</a>
                                </li>
                                <li class="nav-item main-navbar">
                                    <a class="nav-link" href="help-center.html">Help Center</a>
                                </li>
                                <!-- <li class="nav-item main-navbar">
                                    <a class="nav-link" href="help-center.html">Fees</a>
                                </li> -->
                            </ul>
                            <div class="right-area header-action d-flex align-items-center max-un">
                                <a href="login" class="login">Login</a>
                                <a href="sign-up" class="cmn-btn">Sign Up
                                    <i class="icon-d-right-arrow-2"></i>
                                </a>
                            </div>
                        </div>
                    </nav>
                </div>
            </div>
        </div>
    </header>
    <!-- header-section end -->

    <!-- Login Reg start -->
    <section class="login-reg mt-3">
        <div class="overlay pt-120 pb-120">
            <div class="container">
                <div class="row align-items-center justify-content-center">
                    <div class="col-xl-6">
                        <div class="section-text text-center">
                            <h5 class="sub-title">Account</h5>
                            <h2 class="title">Log in to Continue</h2>
                            <p class="dont-acc">Don’t have an account? <a href="sign-up.html">Sign up</a></p>
                            
                        </div>
                        <form id="signin">
                            <div class="row">
                                <div class="col-12">
                                    <div class="single-input">
                                        <label for="logemail">Your Email</label>
                                        <input type="text" name="email" placeholder="Enter Your Email">
                                    </div>
                                    <div class="single-input">
                                        <label for="logpassword">Your Password</label>
                                        <input type="password"  name="password" placeholder="Enter Your Password">
                                    </div>
                                    <button type="submit" class="cmn-btn w-100">Login</button>
                                </div>
                            </div>
                        </form>
                        <div class="forgot-pass mt-30 text-center">
                            <a href="javascript:void(0)">Forgot Password</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Login Reg end -->

    <!--==================================================================-->
    <script src="assets/js/jquery.min.js"></script>
    <script src="assets/js/jquery-ui.js"></script>
    <script src="assets/js/bootstrap.min.js"></script>
    <script src="assets/js/fontawesome.js"></script>
    <script src="assets/js/plugin/slick.js"></script>
    <script src="assets/js/plugin/jquery.nice-select.min.js"></script>
    <script src="assets/js/plugin/counter.js"></script>
    <script src="assets/js/plugin/waypoint.min.js"></script>
    <script src="assets/js/plugin/jquery.magnific-popup.min.js"></script>
    <script src="assets/js/plugin/wow.min.js"></script>
    <script src="assets/js/plugin/plugin.js"></script>
    <script src="assets/js/main.js"></script>

    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>

    $('#signin').on('submit',function(e) {
        e.preventDefault();
        $.ajax({
            url: '/dashboard/router',
            dataType: 'JSON',
            type: 'POST',
            data: $(this).serialize()+"&method=control&path=login",
            headers: {
                'Elzgar': 'Its Lord'
            },
            beforeSend: function() {
                $('.btn').attr('disabled');
            },
            complete: function() {
                $('.btn').removeAttr('disabled');
            },
            success: function(r) {
                if(r.success == 'true') {
                    Swal.fire({
                              icon: 'success',
                              title: 'Success!',
                              text: 'You have succesfully logged in, redirecting to dashboard...',
                             
                            })
                    setTimeout(function() { javascript:location.replace('/dashboard') }, 4000);
                } else {
                     Swal.fire({
                              icon: 'error',
                              title: 'Error!',
                              text: r.msg,
                             
                            })
                }
            }
        });
    });
   </script>
</body>

</html>