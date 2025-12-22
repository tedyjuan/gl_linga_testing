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
					<div class="mb-3">
						<label class="form-label" for="perusahaan">Perusahaan</label>
						<select id="perusahaan" name="perusahaan" class="form-control-hover-light form-control select2"
							data-parsley-required="true" data-parsley-errors-container=".err_name" required="">
							<option value="">Pilih</option>
						</select>
						<span class="text-danger err_name"></span>
					</div>
				</div>
				<div class="col-6">
					<div class="mb-3">
						<label class="form-label" for="depo">Depo</label>
						<select id="depo" name="depo" class="form-control-hover-light form-control select2"
							data-parsley-required="true" data-parsley-errors-container=".err_depo" required="">
							<option value="">Pilih</option>
						</select>
						<span class="text-danger err_depo"></span>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-6">
					<div class="mb-3">
						<label class="form-label" for="kode_journal_source">Kode</label>
						<input type="text" id="kode_journal_source" name="kode_journal_source" data-parsley-minlength="2"
							class="form-control-hover-light form-control kapital" data-parsley-required="true"
							data-parsley-errors-container=".err_journal_source" required=""
							placeholder="input kode journal source">
						<span class="text-danger err_journal_source"></span>
					</div>
				</div>
				<div class="col-6">
					<div class="mb-3">
						<label class="form-label" for="des">Deskripsi</label>
						<input type="text" id="des" name="des" data-parsley-required="true"
							data-parsley-errors-container=".err_sing_cc" required=""
							class="form-control-hover-light form-control kapital"
							placeholder="input deskripsi">
						<span class="text-danger err_sing_cc"></span>
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
	$('#btnsubmit').click(function(e) {
		e.preventDefault();
		let form = $('#forms_add');
		form.parsley().validate();
		if (form.parsley().isValid()) {
			$.ajax({
				url: "<?= base_url('C_journal_source/simpandata') ?>",
				type: 'POST',
				method: 'POST',
				dataType: 'JSON',
				data: form.serialize(),
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
		$('#kode_journal_source').mask('###', {
			translation: {
				'#': {
					pattern: /[A-Za-z0-9]/, // Mengizinkan angka 0-9 dan huruf A-Z
					optional: true
				}
			}
		});


		$('.kapital').on('input', function(e) {
			this.value = this.value.replace(/[^a-zA-Z0-9 /-]/g, '').toUpperCase();
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
		$('#perusahaan').on('change', function() {
			var companyCode = $(this).val();
			// Mengosongkan dropdown dan reset nilai default sebelum AJAX
			$('#depo').empty().append('<option value="">Pilih</option>');
			if (companyCode) {
				// Memuat Depo
				$.ajax({
					url: '<?= base_url('C_global/getDepoByCompany/'); ?>' + companyCode,
					method: 'GET',
					dataType: 'JSON',
					beforeSend: function() {
						showLoader();
					},
					success: function(data) {
						hideLoader();
						data.forEach(function(depo) {
							$('#depo').append('<option value="' + depo.code_depo +
								'" data-alias="' + depo.alias +
								'" data-area="' + depo.code_area + '">' + depo.code_depo + '-' + depo.name + '</option>');
						});
					}
				});

				$("#depo").select2()

			}
		});
	})
</script>
