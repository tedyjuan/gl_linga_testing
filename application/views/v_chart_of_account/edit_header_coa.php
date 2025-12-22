<!-- Card -->
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
					<div class="mb-3">
						<label class="form-label" for="perusahaan">Company</label>
						<input type="text" id="perusahaan" name="perusahaan" disabled
							value="<?= $data->code_company; ?> - <?= $data->nm_company; ?>" class="bg-soft-dark form-control">
					</div>
					<div class="mb-3">
						<label class="form-label" for="no_akun">No Akun</label>
						<input type="text" id="no_akun" name="no_akun" disabled value="<?= $data->account_number; ?>" class="bg-soft-dark form-control">
					</div>
					<div class="mb-3">
						<label class="form-label" for="nama_akun">Nama Akun</label>
						<input type="text" id="nama_akun" name="nama_akun" value="<?= $data->name; ?>" data-parsley-required="true"
							data-parsley-errors-container=".err_nama_akun" required=""
							class="form-control-hover-light form-control kapital"
							placeholder="input nama akun">
						<span class="text-danger err_nama_akun"></span>
					</div>
					<div class="row">
						<div class="col-6">
							<div class="mb-3">
								<label class="form-label" for="akun_dc">Debit / Kredit</label>
								<select id="akun_dc" name="akun_dc" class="form-control-hover-light form-control select2"
									data-parsley-required="true" data-parsley-errors-container=".err_akun_dc" required="">
									<option value="">Pilih</option>
									<option value="debit" <?= $data->account_method == "debit" ? 'selected' : '' ?>>Debit</option>
									<option value="credit" <?= $data->account_method == "credit" ? 'selected' : '' ?>>Kredit</option>
								</select>
								<span class="text-danger err_akun_dc"></span>
							</div>
						</div>
						<div class="col-6">
							<div class="mb-3">
								<label class="form-label" for="akun_group">COA Group</label>
								<select id="akun_group" name="akun_group" class="form-control-hover-light form-control select2"
									data-parsley-required="true" data-parsley-errors-container=".err_akun_group" required="">
									<option value="">Pilih</option>
									<option value="kas" <?= $data->account_group == "kas" ? 'selected' : '' ?>>Kas</option>
									<option value="bank" <?= $data->account_group == "bank" ? 'selected' : '' ?>>Bank</option>
									<option value="inventory" <?= $data->account_group == "inventory" ? 'selected' : '' ?>>inventory</option>
									<option value="sales" <?= $data->account_group == "sales" ? 'selected' : '' ?>>Sales</option>
								</select>
								<span class="text-danger err_akun_group"></span>
							</div>
						</div>
					</div>
					<div class="mb-3">
						<label class="form-label" for="akun_type">Tipe Akun</label>
						<select id="akun_type" name="akun_type" class="form-control-hover-light form-control select2"
							data-parsley-required="true" data-parsley-errors-container=".err_akun_type" required="">
							<option value="">Pilih </option>
							<?php foreach ($type_akun as $type) : ?>
								<option value="<?= $type->account_type ?>" <?= $data->account_type == "$type->account_type" ? 'selected' : '' ?>><?= $type->account_type ?></option>
							<?php endforeach; ?>
						</select>
						<span class="text-danger err_akun_type"></span>
					</div>
				</div>
				<div class="col-6">

					<div class="mb-3">
						<label class="form-label" for="tbag1">Trial Balance Group 1</label>
						<select id="tbag1" name="tbag1" class="form-control-hover-light form-control select2">
							<option value="">Pilih</option>
							<?php if ($data->code_trialbalance1 != '') { ?>
								<?php foreach ($tbag1List as $tbg1) : ?>
									<option value="<?= $tbg1->code_trialbalance1 ?>"
										<?= $data->code_trialbalance1 == "$tbg1->code_trialbalance1" ? 'selected' : '' ?>>
										<?= $tbg1->code_trialbalance1 ?> - <?= $tbg1->description ?>
									</option>
								<?php endforeach; ?>
							<?php } ?>
						</select>
					</div>
					<div class="mb-3">
						<label class="form-label" for="tbag2">Trial Balance Group 2</label>
						<select id="tbag2" name="tbag2" class="form-control-hover-light form-control select2">
							<option value="">Pilih</option>
							<?php if ($data->code_trialbalance2 != '') { ?>
								<?php foreach ($tbag2List as $tbg2) : ?>
									<option value="<?= $tbg2->code_trialbalance2 ?>"
										<?= $data->code_trialbalance2 == "$tbg2->code_trialbalance2" ? 'selected' : '' ?>>
										<?= $tbg2->code_trialbalance2 ?> - <?= $tbg2->description ?>
									</option>
								<?php endforeach; ?>
							<?php } ?>
						</select>
					</div>
					<div class="mb-3">
						<label class="form-label" for="tbag3">Trial Balance Group 3</label>
						<select id="tbag3" name="tbag3" class="form-control-hover-light form-control select2">
							<option value="">Pilih</option>
							<?php if ($data->code_trialbalance3 != '') { ?>
								<?php foreach ($tbag3List as $tbg3) : ?>
									<option value="<?= $tbg3->code_trialbalance3 ?>"
										<?= $data->code_trialbalance3 == "$tbg3->code_trialbalance3" ? 'selected' : '' ?>>
										<?= $tbg3->code_trialbalance3 ?> - <?= $tbg3->description ?>
									</option>
								<?php endforeach; ?>
							<?php } ?>
						</select>
					</div>
					<div class="mb-3">
						<label class="form-label" for="deskripsi">Deskripsi</label>
						<textarea name="deskripsi" id="deskripsi" cols="4" rows="4" placeholder="Input deskripsi"
							class="form-control-hover-light form-control"><?= $data->description; ?></textarea>
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
		$('#no_akun').mask('0000');
		$('#no_akun').on('blur', function() {
			let inputValue = $(this).val();
			if (inputValue.length === 1) {
				inputValue = inputValue + '000';
			} else if (inputValue.length === 2) {
				inputValue = inputValue + '00';
			} else if (inputValue.length === 3) {
				inputValue = inputValue + '0';
			}
			$(this).val(inputValue);
		});
		$('#no_akun').on('keyup', function() {
			let inputValue = $(this).val();

			if (inputValue.startsWith('0')) {
				inputValue = inputValue.slice(1);
			}
			// Jika input lebih kecil dari 1, kosongkan input
			if (parseInt(inputValue) < 1 && inputValue !== '') {
				inputValue = '';
			}
			// Set kembali value setelah manipulasi
			$(this).val(inputValue);
		});
		$('.kapital').on('input', function(e) {
			this.value = this.value.replace(/[^a-zA-Z0-9 /-]/g, '').toUpperCase();
		});

	})
</script>
<script>
	$('#akun_type').on('change', function() {
		var akun_type = $(this).val();
		if (akun_type == '') {
			$('#tbag1').empty().append('<option value="">Pilih Type dahulu</option>');
			$('#tbag2').empty().append('<option value="">Pilih group 1 dahulu</option>');
			$('#tbag3').empty().append('<option value="">Pilih group 2 dahulu</option>');
		} else {
			$('#tbag1').empty().append('<option value="">Pilih</option>');
			$('#tbag2').empty().append('<option value="">Pilih group 1 dahulu</option>');
			$('#tbag3').empty().append('<option value="">Pilih group 2 dahulu</option>');
		}
		var company = "<?= $code_company; ?>";
		if (akun_type) {
			$.ajax({
				url: '<?= base_url('C_chart_of_account/get_tbag1'); ?>',
				method: 'POST',
				dataType: 'JSON',
				data: {
					akun_type: akun_type,
					code_company: company,
				},
				beforeSend: function() {
					showLoader();
				},
				success: function(data) {
					hideLoader();
					data.forEach(function(coa) {
						$('#tbag1').append('<option value="' + coa.code_trialbalance1 + '" data-company="' + company + '" >' + '(' + coa.code_trialbalance1 + ') ' + coa.description + '</option>');
					});
				}
			});

		}
	});
	$('#tbag1').on('change', function() {
		var val_tbag1 = $(this).val();
		if (val_tbag1 == '') {
			$('#tbag2').empty().append('<option value="">Pilih group 1 dahulu</option>');
			$('#tbag3').empty().append('<option value="">Pilih group 2 dahulu</option>');
		} else {
			$('#tbag2').empty().append('<option value="">Pilih</option>');
			$('#tbag3').empty().append('<option value="">Pilih group 2 dahulu</option>');
		}
		var company = $('#tbag1 option:selected').data('company');
		if (val_tbag1) {
			$.ajax({
				url: '<?= base_url('C_chart_of_account/get_tbag2'); ?>',
				method: 'POST',
				dataType: 'JSON',
				data: {
					tbag1: val_tbag1,
					code_company: company,
				},
				beforeSend: function() {
					showLoader();
				},
				success: function(data) {
					hideLoader();
					data.forEach(function(tbg2) {
						$('#tbag2').append('<option value="' + tbg2.code_trialbalance2 + '" data-company="' + company + '" >' + '(' + tbg2.code_trialbalance2 + ') ' + tbg2.description + '</option>');
					});
				}
			});

		}
	});
	$('#tbag2').on('change', function() {
		var val_tbag2 = $(this).val();
		if (val_tbag2 == '') {
			$('#tbag3').empty().append('<option value="">Pilih group 2 dahulu</option>');
		} else {
			$('#tbag3').empty().append('<option value="">Pilih</option>');
		}
		if (val_tbag2) {
			var company = $('#tbag2 option:selected').data('company');
			$.ajax({
				url: '<?= base_url('C_chart_of_account/get_tbag3'); ?>',
				method: 'POST',
				dataType: 'JSON',
				data: {
					tbag2: val_tbag2,
					code_company: company,
				},
				beforeSend: function() {
					showLoader();
				},
				success: function(data) {
					hideLoader();
					data.forEach(function(tbg3) {
						$('#tbag3').append('<option value="' + tbg3.code_trialbalance3 + '" data-company="' + company + '" >' + '(' + tbg3.code_trialbalance3 + ') ' + tbg3.description + '</option>');
					});
				}
			});

		}
	});
</script>
<script>
	$('#btnsubmit').click(function(e) {
		e.preventDefault();
		let form = $('#forms_add');
		var uuid = "<?= ($uuid) ?>";
		form.parsley().validate();
		if (form.parsley().isValid()) {
			$.ajax({
				url: "<?= base_url('C_chart_of_account/update') ?>",
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
		}
	});
</script>
