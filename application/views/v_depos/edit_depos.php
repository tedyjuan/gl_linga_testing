<!-- Card -->
<div class="card">
	<div class="card-header">
		<div class="row align-items-center mb-2">
			<div class="col-md-12 d-flex justify-content-between">
				<h2 class="mb-0"><?= $judul ?></h2>
				<div class="div ">
					<button class="btn btn-sm btn-primary" onclick="loadform('<?= $load_grid ?>')"><i
							class="bi bi-arrow-left-circle"></i> Back</button>
					<a href="javascript:void(0)" class="btn btn-sm btn-outline-primary"
						onclick="loadform('<?= $load_refresh ?>')">
						<i class="bi bi-arrow-clockwise"></i> Refresh
					</a>
				</div>
			</div>
		</div>
	</div>
	<div class="card-body">
		<form id="forms_add" method="post">
			<div class="row">
				<div class="col-6">
					<div class="mb-3">
						<label class="form-label" for="perusahaan">Company</label>
						<select id="perusahaan" name="perusahaan" <?= $disabled == 'ON' ? 'disabled' : ''; ?>
							class="form-control-hover-light form-control select2"
							data-parsley-required="true" data-parsley-errors-container=".err_name" required>
							<option value="">Pilih</option>
							<?php if (!empty($companies)): ?>
								<?php foreach ($companies as $c): ?>
									<option value="<?= $c->code_company ?>"
										<?= (isset($data->code_company) && $data->code_company == $c->code_company) ? 'selected' : '' ?>>
										<?= $c->name ?>
									</option>
								<?php endforeach; ?>
							<?php endif; ?>
						</select>
						<span class="text-danger err_name"></span>
					</div>
				</div>

				<div class="col-6">
					<div class="mb-3">
						<label class="form-label" for="kode_depo">Code Branch</label>
						<input type="text" id="kode_depo" name="kode_depo" <?= $disabled == 'ON' ? 'readonly' : ''; ?>
							value="<?= isset($data->code_depo) ? $data->code_depo : '' ?>"
							data-parsley-required="true" data-parsley-errors-container=".err_kodedepo" required
							class="<?= $disabled == 'ON' ? 'bg-soft-dark' : 'form-control-hover-light'; ?> form-control" placeholder="input kode depo">
						<span class="text-danger err_kodedepo"></span>
					</div>
				</div>
			</div>

			<div class="row">
				<div class="col-6">
					<div class="mb-3">
						<label class="form-label" for="nama_depo">Name Branch</label>
						<input type="text" id="nama_depo" name="nama_depo" <?= $disabled == 'ON' ? 'readonly' : ''; ?>
							value="<?= isset($data->name) ? $data->name : '' ?>"
							class="<?= $disabled == 'ON' ? 'bg-soft-dark' : 'form-control-hover-light'; ?> form-control" required
							placeholder="input nama depo">
						<span class="text-danger err_namadepo"></span>
					</div>
				</div>

				<div class="col-6">
					<div class="mb-3">
						<label class="form-label" for="kd_depo_cost_center">Code Branch Cost Center</label>
						<input type="text" id="kd_depo_cost_center" name="kd_depo_cost_center" <?= $disabled == 'ON' ? 'readonly' : ''; ?>
							value="<?= isset($data->code_area) ? $data->code_area : '' ?>"
							class="<?= $disabled == 'ON' ? 'bg-soft-dark' : 'form-control-hover-light'; ?> form-control" required
							placeholder="input kode depo cost center">
						<span class="text-danger err_depo_cc"></span>
					</div>
				</div>
			</div>

			<div class="row">
				<div class="col-6">
					<div class="mb-3">
						<label class="form-label" for="singkatan_cost_center">Abbreviation Cost Center</label>
						<input type="text" id="singkatan_cost_center" name="singkatan_cost_center"
							value="<?= isset($data->alias) ? $data->alias : '' ?>" <?= $disabled == 'ON' ? 'readonly' : ''; ?>
							class="<?= $disabled == 'ON' ? 'bg-soft-dark' : 'form-control-hover-light'; ?> form-control" required
							placeholder="input singkatan cost center">
						<span class="text-danger err_sing_cc"></span>
					</div>
				</div>

				<div class="col-3">
					<div class="mb-3">
						<label class="form-label" for="npwp">NPWP</label>
						<input type="text" id="npwp" name="npwp"
							value="<?= isset($data->npwp) ? $data->npwp : '' ?>"
							class="form-control-hover-light form-control" required
							placeholder="input npwp">
						<span class="text-danger err_npwp"></span>
					</div>
				</div>
				<div class="col-3">
					<div class="mb-3">
						<label class="form-label" for="status_depo">Status Branch</label>
						<select name="status_depo" id="status_depo"
							class="form-control-hover-light form-control" required>
							<option value="">-- Pilih --</option>
							<option value="depo" <?= (isset($data->status_depo) && $data->status_depo == 'depo') ? 'selected' : '' ?>>Branch</option>
							<option value="pusat" <?= (isset($data->status_depo) && $data->status_depo == 'pusat') ? 'selected' : '' ?>>Head office</option>
						</select>
						<span class="text-danger err_status_depo"></span>
					</div>
				</div>
			</div>

			<div class="row">
				<div class="col-6">
					<div class="mb-3">
						<label class="form-label" for="kota">City</label>
						<input type="text" id="kota" name="kota"
							value="<?= isset($data->city) ? $data->city : '' ?>"
							class="form-control-hover-light form-control" required
							placeholder="input City">
						<span class="text-danger err_kota"></span>
					</div>
				</div>

				<div class="col-3">
					<div class="mb-3">
						<label class="form-label" for="kode_pos">Postal Code</label>
						<input type="text" id="kode_pos" name="kode_pos"
							value="<?= isset($data->postal_code) ? $data->postal_code : '' ?>"
							class="form-control-hover-light form-control" required
							placeholder="input Postal Code">
						<span class="text-danger err_kodepos"></span>
					</div>
				</div>

				<div class="col-3">
					<div class="mb-3">
						<label class="form-label" for="nomor_hp">Handphone</label>
						<input type="text" id="nomor_hp" name="nomor_hp"
							value="<?= isset($data->phone_no) ? $data->phone_no : '' ?>"
							class="form-control-hover-light form-control" required
							placeholder="input nomor handphone">
						<span class="text-danger err_no_hp"></span>
					</div>
				</div>
			</div>

			<div class="row">
				<div class="col-12">
					<div class="mb-3">
						<label class="form-label" for="alamat">Address</label>
						<textarea name="alamat" id="alamat" cols="2" rows="4"
							class="form-control-hover-light form-control" required
							placeholder="input alamat"><?= isset($data->address) ? $data->address : '' ?></textarea>
						<span class="text-danger err_alamat"></span>
					</div>
				</div>
			</div>

			<div class="col-md-12 d-flex justify-content-end">
				<div></div>
				<div>
					<button type="button" id="btnsubmit" class="btn btn-sm btn-primary">
						<i class="bi bi-send"></i> Save
					</button>
					<button type="reset" class="btn btn-sm btn-outline-danger">
						<i class="bi bi-eraser-fill"></i> Reset
					</button>
				</div>
			</div>
		</form>

	</div>
</div>
<script>
	$('#btnsubmit').click(function(e) {
		e.preventDefault();
		let form = $('#forms_add');
		var uuid = "<?= $uuid ?>";
		form.parsley().validate();
		if (form.parsley().isValid()) {
			$.ajax({
				url: "<?= base_url('C_depos/updatedata') ?>",
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

	$(document).ready(function() {
		$('#kd_depo_cost_center').on('keyup', function() {
			var currentValue = $(this).val();
			currentValue = currentValue.replace(/[^0-9]/g, '');
			if (currentValue === '') {
				var incrementedValue = 0;
			} else {
				var incrementedValue = parseInt(currentValue);
			}
			var formattedValue = String(incrementedValue).padStart(2, '0');
			if (formattedValue.length > 2) {
				$(this).val(formattedValue.slice(0, 2));
			} else {
				$(this).val(formattedValue).trigger("input");
			}
		});
		$('#nama_depo').on('keyup', function() {
			var value = $(this).val();
			var formattedValue = value.replace(/\b\w/g, function(char) {
				return char.toUpperCase();
			}).replace(/\B\w/g, function(char) {
				return char.toLowerCase();
			});
			$('#nama_depo').val(formattedValue);
		});
		$('#kode_depo').mask('000');
		$('#kode_pos').mask('00000');
		$('#singkatan_cost_center').on('input', function(e) {
			this.value = this.value.replace(/[^a-zA-Z0-9 ]/g, '').toUpperCase();
		});
		$("#perusahaan").empty().append(`<option class='form-control' value="<?= $data->code_company ?>"><?= $data->code_company ?> - <?= $data->nm_company ?></option>`).val("<?= $data->code_company ?>").trigger('change');
		$('#nomor_hp').mask("#-###0", {
			reverse: true
		});
		$('#npwp').mask('99.999.999.9-999.999');
		$('.string').on('input', function(e) {
			this.value = this.value.replace(/[^a-zA-Z0-9 ]/g, '');
		});

		$("#perusahaan").select2({
			placeholder: 'search code or name',
			allowClear: true,
			ajax: {
				url: "<?= base_url('C_company/search') ?>",
				dataType: "json",
				delay: 250,
				data: function(params) {
					return {
						getCompany: params.term
					};
				},
				processResults: function(data) {
					var results = [];
					$.each(data, function(index, item) {
						results.push({
							id: item.code_company,
							text: item.code_company + ' - ' + item.name,
						});
					});
					return {
						results: results
					};
				}
			}
		});
	})
</script>
