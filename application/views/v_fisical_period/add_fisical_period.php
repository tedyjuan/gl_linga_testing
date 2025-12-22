<div class="card">
	<div class="card-header">
		<div class="row align-items-center mb-2">
			<div class="col-md-12 d-flex justify-content-between">
				<h2 class="mb-0"><?= $judul ?></h2>
				<div class="div ">
					<button class="btn btn-sm btn-primary" onclick="loadform('<?= $load_grid ?>')"><i
							class="bi bi-arrow-left-circle"></i> Back</button>
					<a href="javascript:void(0)" class="btn btn-sm btn-outline-primary"
						onclick="loadform('<?= $load_back ?>')">
						<i class="bi bi-arrow-clockwise"></i> Refresh
					</a>
				</div>
			</div>
		</div>
	</div>
	<div class="card-body">
		<form id="forms_add">
			<div class="row">
				<div class="col-6">
					<label class="form-label" for="branch">Branch</label>
					<select style="width:100%" id="branch" name="branch" class="form-control-hover-light form-control select2"
						data-parsley-required="true" data-parsley-errors-container=".err_branch" required="">
						<option value=""></option>
						<?php foreach ($depos as $row) : ?>
							<option value="<?= $row->code_depo ?>"><?= $row->code_depo ?> - <?= $row->name ?></option>
						<?php endforeach; ?>
					</select>
					<span class="text-danger err_branch"></span>
				</div>
				<div class="col-6">
					<div class="mb-3">
						<label class="form-label" for="year">Year</label>
						<select style="width: 100%;" id="year" name="year" class="form-control-hover-light form-control select2"
							data-parsley-required="true" data-parsley-errors-container=".err_year" required="">
							<option value=""></option>
						</select>
						<span class="text-danger err_year"></span>
					</div>
				</div>
			</div>
			<div class="col-md-12 d-flex justify-content-end mt-4">
				<div></div>
				<div>
					<button type="button" id="btnsubmit" class="btn btn-sm btn-primary"><i class="bi bi-send"></i>
						Simpan</button>
					<button type="reset" class="btn btn-sm btn-outline-danger"><i class="bi bi-eraser-fill"></i>
						Reset</button>
				</div>
			</div>
		</form>
	</div>
</div>
<script>
	$('#btnsubmit').click(function(e) {
		e.preventDefault();
		let form = $('#forms_add');
		form.parsley().validate();
		if (form.parsley().isValid()) {
			$.ajax({
				url: "<?= base_url('C_fisical_period/simpandata') ?>",
				type: 'POST',
				method: 'POST',
				dataType: 'JSON',
				data: form.serialize(),
				beforeSend: function() {
					// showLoader();
				},
				success: function(data) {
					if (data.hasil == 'true') {
						swet_sukses(data.pesan);
						loadform('<?= $load_grid ?>');
					} else {
						swet_gagal(data.pesan);
						hideLoader();
					}
				},
				error: function(xhr) {

					if (xhr.status === 422) {
						let errors = xhr.responseJSON.errors;
						$.each(errors, function(key, value) {
							$(`.err_${key}`).html(value[0]);
						});
					} else {
						swet_gagal("Terjadi kesalahan server (" + xhr.status + ")");
					}
				},
			});
		}
	});
</script>

<script>
	$(document).ready(function() {
		tahun();
		$(".select2").select2({
			placeholder: 'Search...',
		});
		$('#period_oke').datepicker({
			format: "yyyy-mm",
			startView: "months",
			minViewMode: "months",
			autoclose: true,
			orientation: "bottom auto"
		});
	});

	function tahun() {

		let currentYear = new Date().getFullYear(); // tahun sekarang
		let minYear = currentYear - 2;
		let maxYear = currentYear + 2;

		// Generate option tahun
		for (let y = minYear; y <= maxYear; y++) {
			$('#year').append(`<option value="${y}">${y}</option>`);
		}

		// Set default = tahun sekarang
		$('#year').val(currentYear).trigger('change');

		// Jika pakai select2
		if ($('.select2').length) {
			$('#year').select2();
			$('#year').val(currentYear).trigger('change'); // ulangi setelah select2
		}
	}
</script>
