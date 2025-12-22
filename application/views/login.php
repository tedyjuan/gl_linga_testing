<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<title>Login | General Ledger</title>
	<link rel="shortcut icon" href="<?= base_url('public/') ?>assets/favicon.ico">
	<link rel="stylesheet" href="<?= base_url('public/') ?>assets/css/vendor.min.css">
	<link rel="stylesheet" href="<?= base_url('public/') ?>assets/css/theme.minc619.css?v=1.0">
</head>

<body>
	<main id="content" role="main" class="main">
		<div class="position-fixed top-0 end-0 start-0 bg-img-start" style="height: 32rem; background-image: url(<?= base_url('public/') ?>assets/svg/components/card-6.svg);">
			<div class="shape shape-bottom zi-1">
				<svg preserveAspectRatio="none" xmlns="http://www.w3.org/2000/svg" x="0px" y="0px" viewBox="0 0 1921 273">
					<polygon fill="#fff" points="0,273 1921,273 1921,0 " />
				</svg>
			</div>
		</div>
		<div class="container py-5 py-sm-7">
			<div class="mx-auto" style="max-width: 30rem;">
				<div class="card card-lg mb-5">
					<div class="card-body">
						<form class="js-validate needs-validation" action="<?= base_url('Auth/authProcess') ?>" method="POST" autocomplete="off">
							<div class="text-center">
								<div class="mb-5">
									<!-- <a class="d-flex justify-content-center mb-5" href="<?= base_url('auth') ?>">
										<img class="zi-2" src="<?= base_url('public/assets/img/logo/logo.svg'); ?>" alt="Image Description" style="width: 15rem;">
									</a> -->
									<p>Please login with your account</p>
								</div>
							</div>


							<div class="mb-3">
								<label class="form-label" for="username">Employee ID</label>
								<input type="text" onkeyup="cleanEmployeeId()" class="form-control form-control-lg" name="username" id="username" tabindex="2" placeholder="Input Employee ID or username" required>
							</div>
							<div class="mb-3">
								<label class="form-label w-100" for="password" tabindex="0">
									Password
								</label>
								<div class="input-group input-group-merge">
									<input type="password" class="js-toggle-password form-control form-control-lg" name="password" id="password" placeholder="Input characters required" required tabindex="2">
									<a id="changePassTarget" class="input-group-append input-group-text" href="javascript:;">
										<i id="changePassIcon" class="bi-eye"></i>
									</a>
								</div>
								<span class="invalid-feedback">Please enter a valid password.</span>
							</div>
							<div class="d-grid">
								<button type="submit" class="btn btn-primary btn-lg" tabindex="2"><i class="bi bi-send"></i> Sign in</button>
							</div>
						</form>
					</div>
					<div class="card-footer">
						<span class="text-muted">
							<div class="row justify-content-center align-items-center">
								<div class="col">
									<strong>BINTANGMP </strong> &copy; <?= date('Y'); ?> | General Ledger System
								</div>

								<!-- End Col -->
							</div>
						</span>
					</div>
				</div>
			</div>
		</div>
	</main>

	<script src="<?= base_url('public/') ?>assets/js/vendor.min.js"></script>
	<script src="<?= base_url('public/') ?>assets/js/theme.min.js"></script>
	<script src="<?= base_url("public/assets/vendor/sweetalert/sweetalert.min.all.js") ?>"></script>

	<script>
		<?php
		$flashTypes = ['success', 'info', 'error', 'warning'];
		foreach ($flashTypes as $type) {
			if ($this->session->flashdata($type)) {
				$message = $this->session->flashdata($type);
				echo "Swal.fire({ icon: '$type', title: '" . addslashes($message) . "' });";
			}
		}
		?>
	</script>


	<script>
		$(document).ready(function() {
			$('#changePassTarget').on('click', function() {
				const passwordInput = $('#password');
				const icon = $('#changePassIcon');
				const type = passwordInput.attr('type');

				if (type === 'password') {
					passwordInput.attr('type', 'text');
					icon.removeClass('bi-eye').addClass('bi-eye-slash');
				} else {
					passwordInput.attr('type', 'password');
					icon.removeClass('bi-eye-slash').addClass('bi-eye');
				}
			});
		});

		function cleanEmployeeId() {
			let input = document.getElementById("username");
			// Allow only letters, numbers, and dot
			input.value = input.value.replace(/[^a-zA-Z0-9.]/g, '');
		}
	</script>

</body>

</html>
