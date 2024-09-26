<?php
    if(empty($this->session->userdata("user"))) {
        redirect("auth/login");
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
    <title>Printsoft - Dashboard</title>
    <link href="<?php echo base_url(); ?>assets/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/vendors/toastr/toastr.min.css">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/vendors/ladda/ladda-themeless.min.css" />
    <link href="<?php echo base_url(); ?>assets/css/custom.css" rel="stylesheet">
</head>
<body>