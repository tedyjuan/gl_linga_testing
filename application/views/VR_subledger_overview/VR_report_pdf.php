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
				<div class="col-6">
					<div class="mb-3">
						<label class="form-label" for="periode">Periode</label>
						<input type="text" id="date_periode_journal" name="date_periode_journal"
							class="form-control-hover-light form-control kapital" data-parsley-required="true"
							data-parsley-errors-container=".err_date_periode_journal" required=""
							placeholder="input start account">
						<span class="text-danger err_date_periode_journal"></span>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-6">
					<div class="mb-3">
						<label class="form-label" for="start_akun">From Account</label>
						<select id="start_akun" name="start_akun" class="form-control-hover-light form-control"
							data-parsley-required="true" data-parsley-errors-container=".err_start_akun" required="">
							<option value="">Pilih</option>
						</select>
						<span class="text-danger err_start_akun"></span>
					</div>
				</div>
				<div class="col-6">
					<div class="mb-3">
						<label class="form-label" for="end_account">To Account</label>
						<select id="end_account" name="end_account" class="form-control-hover-light form-control select2"
							data-parsley-required="true" data-parsley-errors-container=".err_end_account" required="">
							<option value="">Pilih</option>
						</select>
						<span class="text-danger err_end_account"></span>
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
		$("#end_account").select2();
		$('#date_periode_journal').datepicker({
			format: "MM yyyy",
			startView: "months",
			minViewMode: "months",
			autoclose: true,
			orientation: "bottom auto"
		}).on('changeDate', function(e) {
			let year = e.date.getFullYear();
			let month = ('0' + (e.date.getMonth() + 1)).slice(-2);
			// Replace value dengan format yang kamu mau
			$('#date_periode_journal').data('real', year + '-' + month);
			let form = $('#forms_generate');
			form.parsley().validate();
		});
		select_account_centers();

	});
	$('#btnsubmit').click(function(e) {
		e.preventDefault();
		let form = $('#forms_generate');

		form.parsley().validate();
		if (form.parsley().isValid()) {

			let period = $('#date_periode_journal').data('real');
			let branch = $("#branch").val();
			let start = $("#start_akun").val();
			let end = $("#end_account").val();

			// bikin URL lengkap
			let url = "<?= base_url('CR_account_subledger_overview/Report') ?>" +
				"?period=" + period +
				"&branch=" + branch +
				"&start=" + start +
				"&end=" + end;

			// BUKA TAB BARU TAMPILKAN PDF
			window.open(url, '_blank');
		}
	});


	function select_account_centers() {
		$("#start_akun").select2({
			placeholder: 'Search account',
			// minimumInputLength: 1,
			ajax: {
				url: "<?= base_url('CR_account_subledger_overview/get_account') ?>",
				dataType: "json",
				delay: 250,
				data: function(params) {
					return {
						cari: params.term
					};
				},
				processResults: function(data) {
					return {
						results: data.map(function(item) {
							return {
								id: item.account_number,
								text: item.account_number + ' - ' + item.name,
							};
						})
					};
				}
			},
		});
	}
	$('#start_akun').on('change', function() {
		var start_account = $(this).val();
		$('#end_account').empty().append('<option value="">Pilih</option>');
		if (start_account != '') {
			var start_account = $("#start_account").val();
			$.ajax({
				dataType: 'JSON',
				url: '<?= base_url('CR_account_subledger_overview/get_account_to'); ?>',
				type: 'POST',
				method: 'POST',
				dataType: 'JSON',
				data: {
					start_account: start_account
				},
				beforeSend: function() {
					showLoader();
				},
				success: function(data) {
					hideLoader();
					data.forEach(function(item) {
						$('#end_account').append('<option value="' + item.account_number + '" >' + item.account_number + ' - ' + item.name + '</option>');
					});
				}
			});

		}
	});
</script>
