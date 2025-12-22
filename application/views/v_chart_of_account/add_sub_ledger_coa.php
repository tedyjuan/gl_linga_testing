<!-- Card -->
<style>
	input[type="search"].form-control {
		margin-bottom: 10px;
		/* Menambahkan margin bawah 10px */
	}
</style>
<div class="card">
	<div class="card-header">
		<div class="row align-items-center mb-2">
			<div class="col-md-12 d-flex justify-content-between">
				<h2 class="mb-0"><?= $judul ?></h2>
				<div class="div ">
					<button class="btn btn-sm btn-primary" onclick="loadform('<?= $load_grid ?>')"><i
							class="bi bi-arrow-left-circle"></i> Back</button>
					<a href="javascript:void(0)" class="btn btn-sm btn-outline-primary" onclick="loadform('<?= $load_refresh ?>')">
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
					<h2>Informasi Ledger</h2>
					<table class="table table-bordered">
						<tbody>
							<tr>
								<th style="width: 35%">ID Akun Header</th>
								<td><?= $data->account_number; ?></td>
							</tr>
							<tr>
								<th style="width: 35%">Nama</th>
								<td><?= $data->name_coa; ?></td>
							</tr>
							<tr>
								<th style="width: 35%">Company </th>
								<td><?= $data->code_company; ?> - <?= $data->name_company; ?> </td>
							</tr>
							<tr>
								<th style="width: 35%">Debit / Credit</th>
								<td><?= $data->account_method ?></td>
							</tr>
							<tr>
								<th style="width: 35%">Tipe Account</th>
								<td><?= $data->account_type  ?></td>
							</tr>
							<tr>
								<th style="width: 35%">Tbag 1</th>
								<td><?= $data->code_trialbalance1 ?> - <?= $data->tbag1 ?></td>
							</tr>
							<tr>
								<th style="width: 35%">Tbag 2</th>
								<td><?= $data->code_trialbalance2 ?> - <?= $data->tbag2 ?> </td>
							</tr>
							<tr>
								<th style="width: 35%">Tbag 3</th>
								<td><?= $data->code_trialbalance3 ?> - <?= $data->tbag3 ?></td>
							</tr>
						</tbody>
					</table>
				</div>
				<div class="col-6 mt-1">
					<div class="mb-3">
						<label class="form-label" for="id_number_subledger">ID Akun sub-Ledger</label>
						<div class="input-group mb-3">
							<span class="input-group-text" id="id-number-ledger"><?= $data->account_number; ?></span>
							<input type="text" class="form-control" id="id_number_subledger" name="id_number_subledger" style="width: 80%;"
								data-parsley-required="true" data-parsley-errors-container=".err_id_akun_ledger" required=""
								placeholder="input 3 number" aria-describedby="id-number-ledger">
							<span class="text-danger err_id_akun_ledger"></span>
						</div>
					</div>
					<div class="mb-3">
						<label class="form-label" for="nama_akun_subledger">Nama sub-Ledger</label>
						<input type="text" id="nama_akun_subledger" name="nama_akun_subledger" data-parsley-required="true"
							data-parsley-errors-container=".err_nama_akun_subledger" required=""
							class="form-control-hover-light form-control kapital"
							placeholder="input nama akun sub-ledger">
						<span class="text-danger err_nama_akun_subledger"></span>
					</div>

					<div class="mb-3">
						<label class="form-label" for="deskripsi">Deskripsi</label>
						<textarea name="deskripsi" id="deskripsi" data-parsley-required="true"
							data-parsley-errors-container=".err_deskripsi" required=""
							class="form-control-hover-light form-control" placeholder="input deskripsi"></textarea>
						<span class="text-danger err_deskripsi"></span>
					</div>
				</div>
			</div>
			<div class="col-md-12 d-flex justify-content-end">
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
	$(document).ready(function() {
		$(".select2").select2();
		$('#id_number_subledger').mask('000');
		$('#id_number_subledger').on('blur', function() {
			let inputValue = $(this).val();
			if (inputValue.length === 1) {
				inputValue = '00' + inputValue;
			}
			if (inputValue.length === 2) {
				inputValue = '0' + inputValue ;
			}
			if (inputValue == '000') {
				$(this).val('001');
			} else {
				$(this).val(inputValue);
			}
		});

		$('.kapital').on('input', function(e) {
			this.value = this.value.replace(/[^a-zA-Z0-9 /-]/g, '').toUpperCase();
		});

	})
</script>
<script>
	$('#btnsubmit').click(function(e) {
		e.preventDefault();
		let form = $('#forms_add');
		var uuid = "<?= ($uuid) ?>";
		form.parsley().validate();
		if (form.parsley().isValid()) {
			console.log("ok");

			$.ajax({
				url: "<?= base_url('C_chart_of_account/simpanSubLedger') ?>",
				type: 'POST',
				method: 'POST',
				dataType: 'JSON',
				data: form.serialize() + '&uuid=' + uuid,
				beforeSend: function() {
					showLoader();
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
		} else {
			console.log("tidak ok");
		}
	});
</script>
