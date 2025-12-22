   <!-- Content -->
   <div class="content container">
   	<div class="row justify-content-center align-items-sm-center py-sm-10">
   		<div class="col-9 col-sm-5 col-lg-4">
   			<div class="text-center text-sm-end me-sm-4 mb-sm-0">
   				<img class="img-fluid" src="<?= base_url('public/assets/svg/illustrations/oc-thinking.svg'); ?>" alt="Image Description"
   					data-hs-theme-appearance="default">
   			</div>
   		</div>

   		<div class="col-sm-7 col-lg-6 text-center text-sm-start">
   			<?php if ($kode != '') { ?>
   				<h1 class="display-1 mb-0"><?= $kode; ?></h1>
   			<?php } else { ?>
   				<h1 class="display-1 mb-0">404</h1>
   			<?php } ?>
   			<?php if (empty($pesan)) { ?>
   				<p class="lead">Maaf, halaman yang Anda cari tidak dapat ditemukan.</p>
   			<?php } else { ?>
   				<p class="lead"><?php echo $pesan; ?></p>
   			<?php } ?>
   			<a class="btn btn-primary" href="<?= base_url('/'); ?>">Kembali ke Halaman Utama</a>
   		</div>
   		<!-- End Col -->
   	</div>
   	<!-- End Row -->
   </div>
   <!-- End Content -->
