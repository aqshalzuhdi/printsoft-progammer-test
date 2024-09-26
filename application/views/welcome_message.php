<!doctype html>
<html lang="en" data-bs-theme="auto">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="description" content="">
	<meta name="author" content="Mark Otto, Jacob Thornton, and Bootstrap contributors">
	<meta name="generator" content="Hugo 0.122.0">
	<title>Printsoft</title>
	<link href="<?php echo base_url(); ?>assets/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
	<link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/font/bootstrap-icons.min.css">
	<link href="<?php echo base_url(); ?>assets/css/custom.css" rel="stylesheet">
</head>
<body>
	<main>
		<div class="container py-4">
			<header class="pb-3 mb-4 border-bottom">
				<a href="<?php echo base_url(); ?>" class="d-flex align-items-center text-body-emphasis text-decoration-none">
					<img class="img-fluid" src="<?php echo base_url(); ?>assets/images/logo.png">
				</a>
			</header>
			<div class="dropdown position-absolute top-0 end-0 mt-3 me-5">
				<button class="btn py-2 d-flex align-items-center" id="bd-theme" type="button" aria-expanded="false" data-bs-toggle="dropdown" aria-label="Toggle theme (auto)">
					<div class="rounded-circle">
						<i class="bi bi-person"></i>
					</div>
					<span>John</span>
				</button>
				<ul class="dropdown-menu dropdown-menu-end shadow" aria-labelledby="bd-theme-text">
					<li>
						<button type="button" class="dropdown-item d-flex gap-2 align-items-center" data-bs-theme-value="light" aria-pressed="false">
							<i class="bi bi-person"></i>
							<span>Edit Account</span>
						</button>
					</li>
					<li>
						<button type="button" class="dropdown-item d-flex gap-2 align-items-center" data-bs-theme-value="light" aria-pressed="false">
							<i class="bi bi-key"></i>
							<span>Change Password</span>
						</button>
					</li>
				</ul>
			</div>
			<div class="p-5 mb-4 bg-info bg-gradient rounded-3">
				<div class="row">
					<div class="col-9">
						<div class="container-fluid py-3">
							<h2 class="fw-bold">Hi John, Have a nice day!</h2>
							<button class="btn btn-light btn-md" type="button">Account Information</button>
						</div>
					</div>
					<div class="col-3">
						<img class="img-fluid" src="<?php echo base_url(); ?>assets/images/illustration-2.png">
					</div>
				</div>
			</div>
			<div class="row align-items-md-stretch">
				<div class="col-md-4">
					<div class="h-100 p-3 border rounded-3">
						<h4>Your current information!</h4>
						<ul class="list-group list-group-flush">
							<li class="list-group-item">
								<div class="row">
									<div class="col-12 col-md-4 fw-bold">Username</div>
									<div class="col-12 col-md-1">:</div>
									<div class="col-12 col-md-7 text-start">
										<label class="value">john</label>
									</div>
								</div>
							</li>
							<li class="list-group-item">
								<div class="row">
									<div class="col-12 col-md-4 fw-bold">Name</div>
									<div class="col-12 col-md-1">:</div>
									<div class="col-12 col-md-7 text-start">
										<label class="value">john</label>
									</div>
								</div>
							</li>
							<li class="list-group-item">
								<div class="row">
									<div class="col-12 col-md-4 fw-bold">Address</div>
									<div class="col-12 col-md-1">:</div>
									<div class="col-12 col-md-7 text-start">
										<label class="value">john</label>
									</div>
								</div>
							</li>
						</ul>
					</div>
				</div>
			</div>
			<footer class="pt-3 mt-4 text-body-secondary border-top">
				&copy; 2024
			</footer>
		</div>
	</main>
	<script src="<?php echo base_url(); ?>assets/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>