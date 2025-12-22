<!-- Card -->
<div class="card">
	<div class="card-header">
		<div class="row align-items-center mb-3">
			<div class="col-md-12 d-flex justify-content-between">
				<h2 class="mb-0"><?= $judul; ?></h2>
				<div class="div">
					<a href="javascript:void(0)" class="btn btn-sm btn-outline-primary"
						onclick="loadform('<?= $load_grid ?>')">
						<i class="bi bi-arrow-clockwise"></i> Refresh
					</a>
				</div>
			</div>
		</div>
	</div>
	<div class="card-body">
		<form id="forms_generate">
			<div class="row">
				<div class="col-6">
					<div class="mb-3">
						<label class="form-label" id="company" for="company">Company</label>
						<input type="text" id="company" name="company" value="<?= $company; ?>" class="form-control" disabled>
					</div>
				</div>
				<div class="col-6">
					<div class="mb-3">
						<label class="form-label" for="branch">Branch</label>
						<select id="branch" name="branch" class="form-control-hover-light form-control"
							data-parsley-required="true" data-parsley-errors-container=".err_branch" required="">
							<option value="">Pilih</option>
							<?php foreach ($depo as $row) : ?>
								<option value="<?= $row->code_depo; ?>"><?= $row->code_depo . ' - ' . $row->name; ?></option>
							<?php endforeach; ?>
						</select>
						<span class="text-danger err_branch"></span>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-6">
					<div class="mb-3">
						<label class="form-label" for="periode_start">Periode</label>
						<input type="text" id="periode_start" name="periode_start"
							class="form-control-hover-light form-control periodebulan" data-parsley-required="true"
							data-parsley-errors-container=".err_periode_start" required=""
							placeholder="input periode">
						<span class="text-danger err_periode_start"></span>
					</div>
				</div>
				<div class="col-6">
					<div class="mb-3">
						<label class="form-label" for="periode_end">Periode</label>
						<input type="text" id="periode_end" name="periode_end"
							class="form-control-hover-light form-control periodebulan" data-parsley-required="true"
							data-parsley-errors-container=".err_periode_end" required=""
							placeholder="input periode">
						<span class="text-danger err_periode_end"></span>
					</div>
				</div>
			</div>
			<div class="col-md-12 d-flex justify-content-end">
				<div></div>
				<div>
					<button type="button" id="btnsubmit" class="btn btn-sm btn-primary"><i class="bi bi-file-earmark-pdf"></i>
						Generate</button>
					<button type="reset" class="btn btn-sm btn-outline-danger"><i class="bi bi-eraser-fill"></i>
						Reset</button>
				</div>
			</div>
		</form>
	</div>
</div>
<script>
	$(document).ready(function() {

		$('#periode_start').datepicker({
			format: "MM yyyy",
			startView: "months",
			minViewMode: "months",
			autoclose: true,
			orientation: "bottom auto"
		}).on('changeDate', function(e) {
			let year = e.date.getFullYear();
			let month = ('0' + (e.date.getMonth() + 1)).slice(-2);
			// Replace value dengan format yang kamu mau
			$('#periode_start').data('real', year + '-' + month);
			let form = $('#forms_generate');
			form.parsley().validate();
		});
		$('#periode_end').datepicker({
			format: "MM yyyy",
			startView: "months",
			minViewMode: "months",
			autoclose: true,
			orientation: "bottom auto"
		}).on('changeDate', function(e) {
			let year = e.date.getFullYear();
			let month = ('0' + (e.date.getMonth() + 1)).slice(-2);
			// Replace value dengan format yang kamu mau
			$('#periode_end').data('real', year + '-' + month);
			let form = $('#forms_generate');
			form.parsley().validate();
		});

	});
	$('#btnsubmit').click(function(e) {
		e.preventDefault();
		let form = $('#forms_generate');

		form.parsley().validate();
		if (form.parsley().isValid()) {

			let periode_start = $('#periode_start').data('real');
			let periode_end = $('#periode_end').data('real');
			let branch = $("#branch").val();

			// bikin URL lengkap
			let url = "<?= base_url('CR_trialbalance/Report') ?>" +
				"?period=" + periode_start +
				"&periode_end=" + periode_end +
				"&branch=" + branch 
			// BUKA TAB BARU TAMPILKAN PDF
			window.open(url, '_blank');
		}
	});
</script>
