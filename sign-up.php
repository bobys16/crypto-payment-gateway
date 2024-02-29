<!doctype html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Money Transfer and Online Payments</title>

    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/fontawesome.min.css">
    <link rel="stylesheet" href="assets/css/jquery-ui.css">
    <link rel="stylesheet" href="assets/css/plugin/magnific-popup.css">
    <link rel="stylesheet" href="assets/css/plugin/slick.css">
    <link rel="stylesheet" href="assets/css/arafat-font.css">
    <link rel="stylesheet" href="assets/css/plugin/animate.css">
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        .nav-pills .nav-link {
            border-radius: 0;
        }

        .nav-pills .nav-item {
            width: 50%;
        }

        .nav-pills .nav-link {
            width: 100%;
        }

        .nav-pills .nav-link {
            color: var(--primary-color);
        }

        .nav-pills .nav-link.active, #submitButton {
            background-color: var(--primary-color);
            color: white;
        }

        .form-floating select {
            display: block!important;
        }

        input {
            padding: 10px 10px;
        }
    </style>
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
                    <nav class="navbar navbar-expand-lg navbar-light">
                        <a class="navbar-brand" href="index.html">
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
                            <h4 class="title">Register to CointoPay</h4>
                        </div>
                        <form id="register">
                            <div class="row">
                                <div class="col-12">
                                   
                                      <div class="tab-content" id="pills-tabContent">
                                        <div class="tab-pane fade show active" id="pills-home" role="tabpanel" aria-labelledby="pills-home-tab">
                                            <form id="contactForm" data-sb-form-api-token="API_TOKEN">
                                               
                                                <div class="form-floating mb-3">
                                                    <input class="form-control"  type="text" name="full_name" placeholder="Full Name" required/>
                                                    <label for="firstName">Full Name</label>
                                                    <div class="invalid-feedback" data-sb-feedback="firstName:required">Full Name is required.</div>
                                                </div>
                                               
                                                <div class="form-floating mb-3">
                                                    <input class="form-control"  type="email" name="email" placeholder="Email Address" required />
                                                    <label for="emailAddress">Email Address</label>
                                                 </div>
                                               
                                                <div class="form-floating mb-3">
                                                    <input class="form-control" type="password" name="pass" placeholder="Password" required />
                                                    <label for="password">Password</label>
                                                    <div class="invalid-feedback" data-sb-feedback="password:required">Password is required.</div>
                                                </div>
                                                <div class="form-floating mb-3">
                                                    <input class="form-control"  type="text" name="pass2" placeholder="Confirm Password"required />
                                                    <label for="confirmPassword">Confirm Password</label>
                                                   
                                                </div>
                                               <div class="form-floating mb-3">
                                                    <input class="form-control"  type="text" name="company" placeholder="Please insert company name" required/>
                                                    <label for="firstName">Company Name</label>
                                                    <div class="invalid-feedback" data-sb-feedback="firstName:required">Company Name is required.</div>
                                                </div>
                                               
                                                <div class="mb-3">
                                                    <label class="form-label d-block">By clicking register below, I certify that:</label>
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input" id="iAmTheAgeOfMajorityInMyCountryOfResidenceAndIHaveReadUnderstandAndAgreeToTheTermsOfTheUserAgreementAndPrivacyPolicy" type="checkbox" name="byClickingRegisterBelowICertifyThat" data-sb-validations="" />
                                                        <label class="form-check-label" for="iAmTheAgeOfMajorityInMyCountryOfResidenceAndIHaveReadUnderstandAndAgreeToTheTermsOfTheUserAgreementAndPrivacyPolicy">I am the age of majority in my country of residence and I have read understand and agree to the terms of the User Agreement and Privacy Policy.</label>
                                                    </div>
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input" id="agreeToReceiveElectronicCommunicationsFromCoinPaymentsInc" type="checkbox" name="byClickingRegisterBelowICertifyThat" data-sb-validations="" />
                                                        <label class="form-check-label" for="agreeToReceiveElectronicCommunicationsFromCoinPaymentsInc">agree to receive electronic communications from CointoPay Inc</label>
                                                    </div>
                                                </div>
                                               
                                                <div class="d-grid">
                                                    <button class="btn btn-primary btn-lg" id="submitButton" type="submit">Submit</button>
                                                </div>
                                            </form>
                                        </div>
                                       
                                      </div>
                                </div>
                            </div>
                        </form>
                       
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
    <script src="assets/js/plugin/counter.js"></script>
    <script src="assets/js/plugin/waypoint.min.js"></script>
    <script src="assets/js/plugin/jquery.magnific-popup.min.js"></script>
    <script src="assets/js/plugin/wow.min.js"></script>
    <script src="assets/js/plugin/plugin.js"></script>
    <script src="assets/js/main.js"></script>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>

    $('#register').on('submit',function(e) {
        e.preventDefault();
        $.ajax({
            url: '/dashboard/router',
            dataType: 'JSON',
            type: 'POST',
            data: $(this).serialize()+"&method=control&path=register",
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
                              text: 'You have succesfully registered, please login to continue...',
                             
                            })
                    setTimeout(function() { javascript:location.replace('/') }, 4000);
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