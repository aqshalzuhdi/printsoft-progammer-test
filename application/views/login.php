<?php
    if(!empty($this->session->userdata("user"))) {
        redirect("dashboard");
    }
?>

<!doctype html>
<html lang="en" data-bs-theme="auto">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="Mark Otto, Jacob Thornton, and Bootstrap contributors">
    <meta name="generator" content="Hugo 0.122.0">
    <title>Printsoft - Login</title>
    <link href="<?php echo base_url(); ?>assets/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/vendors/toastr/toastr.min.css">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/vendors/ladda/ladda-themeless.min.css" />
    <link href="<?php echo base_url(); ?>assets/css/custom.css" rel="stylesheet">
</head>

<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12">
                <div class="row g-0">
                    <div class="col-12 col-md-6 d-flex align-items-center justify-content-center">
                        <div class="col-12 col-lg-11 col-xl-10">
                            <div class="row">
                                <div class="col-12">
                                    <div class="mb-5">
                                        <div class="text-center mb-4">
                                            <a href="#!">
                                                <img src="<?php echo base_url(); ?>assets/images/logo.png" alt="Printsoft" width="175">
                                            </a>
                                        </div>
                                        <h4 class="fw-bold">Sign In</h4>
                                    </div>
                                </div>
                            </div>
                            <form id="FrmLogin" method="POST">
                                <div class="notifikasi">
                                    <?php 
                                        print_r($this->session->userdata("notifikasi"));
                                    ?>
                                </div>
                                <div class="row gy-3 overflow-hidden">
                                    <div class="col-12">
                                        <div class="form-floating mb-3">
                                            <input type="text" class="form-control" name="form[username]" id="username" placeholder="Enter your username" required>
                                            <label for="username" class="form-label">Username</label>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="form-floating mb-3">
                                            <input type="password" class="form-control" name="form[password]" id="password" value="" placeholder="Enter your password" required>
                                            <label for="password" class="form-label">Password</label>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="d-flex gap-2 gap-md-4 flex-column flex-md-row justify-content-between">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" value="" name="remember_me" id="remember_me">
                                                <label class="form-check-label text-secondary" for="remember_me">
                                                    Remember me
                                                </label>
                                            </div>
                                            <a href="#!" class="link-secondary text-decoration-none">Forgot password</a>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="d-grid">
                                            <button type="submit" class="btn btn-dark btn-lg ladda-button ladda-button-submit" data-style="slide-up">Sign In</button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                            <div class="row">
                                <div class="col-12">
                                    <div class="d-flex gap-2 flex-column flex-md-row justify-content-md-center mt-2">
                                        Don't have an account? <a href="#!" class="link-secondary text-decoration-none">Sign Up</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-md-6">
                        <img class="img-fluid rounded-start w-100 h-100 object-fit-cover" loading="lazy" src="<?php echo base_url(); ?>assets/images/illustration.png" alt="">
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="<?php echo base_url(); ?>assets/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script type="text/javascript" src="<?php echo base_url(); ?>assets/js/jquery-3.7.1.min.js"></script>

    <!-- LADDA BUTTON -->
    <script type="text/javascript" src="<?php echo base_url(); ?>assets/vendors/ladda/spin.min.js"></script>
    <script type="text/javascript" src="<?php echo base_url(); ?>assets/vendors/ladda/ladda.min.js"></script>
    <script type="text/javascript" src="<?php echo base_url(); ?>assets/vendors/ladda/ladda.jquery.min.js"></script>
    <!-- LADDA BUTTON  -->

    <!-- JQuery Validate -->
    <script src="<?php echo base_url(); ?>assets/vendors/jquery-validation/jquery.validate.min.js"></script>

    <!-- Toastr -->
    <script src="<?php echo base_url(); ?>assets/vendors/toastr/toastr.min.js"></script>
    <script src="<?php echo base_url(); ?>assets/js/proses.js"></script>

    <script>
        var FrmLogin = $("#FrmLogin").validate({
            submitHandler: function(form) {
                Login(form, function(resp) {
                    if(resp.IsError == true) {
                        $("#FrmLogin").find("input[type=text]").filter(":first").focus();
                    }
                });
            },
            errorPlacement: function (error, element) {
                if (element.parent(".input-group").length) { 
                    error.insertAfter(element.parent());      // radio/checkbox?
                } else if (element.hasClass("select2") || element.hasClass("select")) {     
                    error.insertAfter(element.next("span"));  // select2
                } else {                                      
                    error.insertAfter(element);               // default
                }
            }
        });

        function Login(selectorform, successfunc) {
            successfunc = (typeof successfunc !== 'undefined') ?  successfunc : "";
            var formdata = $(selectorform).serialize() + "&act=login";
            var formaction = $(selectorform).attr("action");
            $.ajax({
                type: "POST",
                url: base_url + "/ajax/auth-ajax",
                data: formdata,
                dataType: "JSON",
                tryCount: 0,
                retryLimit: 3,
                beforeSend: function() {
                    l.ladda("start");
                },
                success: function(resp) {
                    if(resp.IsError == false) {
                        if(resp.lsdt == "success") window.location.href = base_url + "/dashboard.html";
                        if(resp.lsdt == "error") window.location.href = base_url + "/auth/login.html";
                    } else {
                        $(".notifikasi").html(resp.lsdt);
                    }
                    if(successfunc != "") {
                        successfunc(resp);
                    }

                    l.ladda("stop");
                },
                error: function(xhr, textstatus, errorthrown) {
                    console.log(xhr);
                    setTimeout(function(){
                        l.ladda("stop");
                        toastrshow("warning", "Login gagal, mohon periksa koneksi internet anda kembali", "Peringatan");
                    }, 500);
                }
            });
        }
    </script>
</body>
</html>