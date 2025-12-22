<!-- Card -->
<div class="card mt-4">
	<div class="card-body">

		<table class="table table-sm table-striped table-hover table-bordered" id="mytable3" style="width: 100%">
			<thead>
				<tr class="table-primary">
					<th style="width: 30%;">Company</th>
					<th>Code</th>
					<th>Deskripsi</th>
					<th>Code TBG-2</th>
					<th style="width: 5%;">aksi</th>
				</tr>
			</thead>
		</table>
	</div>
</div>


<!-- Modal -->
<div class="modal fade" id="modaltbg3" data-bs-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="modaltbg3Label" aria-hidden="true">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title" id="modaltbg3Label"></h4>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<form id="forms_add">
				<input type="hidden" readonly id="aksi">
				<input type="hidden" name="uuid" readonly id="uuid">
				<div class="modal-body">
					<div class="row">
						<div class="col-6">
							<div class="mb-3">
								<label class="form-label" for="perusahaan">Perusahaan</label>
								<select id="perusahaan" name="perusahaan" class="form-control-hover-light form-control" style="width: 100%;"
									data-parsley-required="true" data-parsley-errors-container=".err_company" required="">
									<option value="">Pilih </option>
								</select>
								<span class="text-danger err_company"></span>
							</div>
						</div>
						<div class="col-6">
							<div class="mb-3">
								<label class="form-label" for="kode_tbg1">Trial Balance Group 1</label>
								<select id="kode_tbg1" name="kode_tbg1" class="form-control-hover-light form-control"
									style="width: 100%;" data-parsley-required="true" data-parsley-errors-container=".err_tbg1" required="">
									<option value="">Pilih perusahan dahulu</option>
								</select>
								<span class="text-danger err_tbg1"></span>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-6">
							<div class="mb-3">
								<label class="form-label" for="kode_tbg2">Trial Balance Group 2</label>
								<select id="kode_tbg2" name="kode_tbg2" class="form-control-hover-light form-control"
									style="width: 100%;" data-parsley-required="true" data-parsley-errors-container=".err_tbg2" required="">
									<option value="">Pilih perusahan dahulu</option>
								</select>
								<span class="text-danger err_tbg2"></span>
							</div>
						</div>
						<div class="col-6">
							<div class="mb-3">
								<label class="form-label" for="kode_tbg3">Trial Balance code</label>
								<input type="text" id="kode_tbg3" name="kode_tbg3" data-parsley-required="true"
									class="form-control-hover-light form-control kapital"
									data-parsley-errors-container=".err_tbg3" required=""
									placeholder="input singkatan cost center">
								<span class="text-danger err_tbg3"></span>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-12">
							<div class="mb-3">
								<label class="form-label" for="des">Deskripsi</label>
								<textarea class="form-control-hover-light form-control kapital" required=""
									data-parsley-required="true" data-parsley-errors-container=".err_desc"
									name="des" id="des" placeholder="input singkatan cost center"></textarea>
								<span class="text-danger err_desc"></span>
							</div>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-sm btn-outline-primary" id="btn_close" data-bs-dismiss="modal">Close</button>
					<button type="button" id="btnsubmit" class="btn btn-sm btn-primary"><i class="bi bi-send"></i> Simpan</button>
				</div>
			</form>
		</div>
	</div>
</div>
<!-- End Modal -->
<script>
	function initTable() {
		// kalau sebelumnya sudah ada instance, hancurkan dulu
		if (window.mytableDT && $.fn.dataTable.isDataTable('#mytable3')) {
			window.mytableDT.clear().destroy();
			window.mytableDT = null;
		}

		window.mytableDT = $('#mytable3').DataTable({
			processing: true,
			serverSide: true,
			ajax: {
				url: "<?= base_url('C_trial_balance/griddata_tbag_3'); ?>",
				type: "POST",
			},
			columnDefs: [{
				orderable: false,
				targets: -1
			}],
			destroy: true,
			retrieve: true
		});
	}

	$(document).ready(function() {
		initTable();

	});

	
	$('.kapital').on('input', function(e) {
		this.value = this.value.replace(/[^a-zA-Z0-9 /-]/g, '').toUpperCase();
	});

	$("#perusahaan").select2({
		placeholder: 'search code or name',
		allowClear: true,
		dropdownParent: $('#modaltbg3'),
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
</script>
<script>
	$(document).ready(function() {
		// Ketika perusahaan dipilih
		$('#perusahaan').on('change', function() {
			var companyCode = $(this).val();
			// Mengosongkan dropdown dan reset nilai default sebelum AJAX
			if (companyCode) {
				$('#kode_tbg1').empty().append('<option value="">Pilih</option>');
				$('#kode_tbg2').empty().append('<option value="">Pilih</option>');
				$.ajax({
					url: '<?= base_url('C_global/getTgb1ByCompany/'); ?>' + companyCode,
					method: 'GET',
					dataType: 'JSON',
					success: function(data) {
						data.forEach(function(val) {
							$('#kode_tbg1').append('<option value="' + val.code_trialbalance1 + '" data-company="' + val.code_company + '">' + val.code_trialbalance1 + '-' + val.description + ' ( ' + val.account_type + ' )' + '</option>');
						});
					}
				});
			}
		});

		$("#kode_tbg1").select2({
			dropdownParent: $('#modaltbg3')
		});
		$("#kode_tbg2").select2({
			dropdownParent: $('#modaltbg3')
		});
		$('#kode_tbg1').on('change', function() {
			var kode_tbg1 = $(this).val();
			var company = $('#kode_tbg1 option:selected').data('company');
			if (kode_tbg1) {
				$('#kode_tbg2').empty().append('<option value="">Pilih</option>');
				$.ajax({
					url: '<?= base_url('C_global/getTgb2'); ?>',
					method: 'POST',
					data: {
						companyCode: company,
						kode_tbg1: kode_tbg1,
					},
					dataType: 'JSON',
					success: function(data) {
						data.forEach(function(val) {
							$('#kode_tbg2').append('<option value="' + val.code_trialbalance2 + '" >' + val.code_trialbalance2 + '-' + val.description + '</option>');
						});
					}
				});
			}
		});

	});

	$('#btnsubmit').click(function(e) {
		e.preventDefault();
		var seturl;
		var aksi = $("#aksi").val();
		if (aksi == 'ADD') {
			seturl = "<?= base_url('C_trial_balance/simpandata') ?>";
		} else if (aksi == 'EDIT') {
			seturl = "<?= base_url('C_trial_balance/update') ?>";
		} else {
			swet_gagal("Parameter aksi tidak di temukan");
			return;
		}
		let form = $('#forms_add');
		form.parsley().validate();
		if (form.parsley().isValid()) {
			$.ajax({
				url: seturl,
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
						// refresh datatables
						if (window.mytableDT) {
							mytableDT.ajax.reload(null, false); // reload DataTable tanpa mereset pagination
						}
						$('#btn_close').click();
						$('#modaltbg3').modal('hide');
						form[0].reset();
						form.parsley().reset();
						hideLoader();
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

	function editforms(uuid) {
		// id button = btnsubmit
		$("#modaltbg3Label").text("Edit Group 3");
		$("#aksi").val("EDIT");
		$('#forms_add').parsley().reset();
		$("#btnsubmit").prop("disabled", true); // Menonaktifkan tombol
		$.ajax({
			url: '<?= base_url('C_trial_balance/editform'); ?>',
			method: 'POST',
			dataType: 'JSON',
			data: {
				uuid: uuid,
			},
			success: function(data) {
				if (data.hasil == 'true') {
					var posdata = data.posdata;
					// Menetapkan nilai-nilai ke elemen HTML
					$("#kode_tbg3").val(posdata.code_trialbalance3);
					$("#des").val(posdata.description);
					$("#uuid").val(posdata.uuid);
					var code_tbg1 = posdata.code_trialbalance1;
					var code_tbg2 = posdata.code_trialbalance2;
					var code_company = posdata.code_company;

					// Menambahkan options untuk #kode_tbg1 dan memilih yang sesuai
					$.each(data.tbag1_Bycompany, function(index, val) {
						var selected = (val.code_trialbalance1 === code_tbg1) ? 'selected' : '';
						$('#kode_tbg1').append('<option value="' + val.code_trialbalance1 + '" data-company="' + val.code_company + '" ' + selected + '>' + val.code_trialbalance1 + '-' + val.description + ' ( ' + val.account_type + ' )' + '</option>');
					});

					// Menambahkan options untuk #kode_tbg2 dan memilih yang sesuai
					$.each(data.tbag2_Bycompany, function(index, val) {
						var selected = (val.code_trialbalance2 === code_tbg2) ? 'selected' : '';
						$('#kode_tbg2').append('<option value="' + val.code_trialbalance2 + '" ' + selected + '>' + val.code_trialbalance2 + '-' + val.description + '</option>');
					});

					// Menambahkan options untuk #perusahaan dan memilih yang sesuai
					$.each(data.allcompany, function(index, val) {
						var selected = (val.code_company === code_company) ? 'selected' : '';
						$('#perusahaan').append('<option value="' + val.code_company + '" ' + selected + '>' + val.code_company + '-' + val.name + '</option>');
					});
				} else {
					swet_gagal(data.pesan);
				}
			},
			complete: function() {
				$("#btnsubmit").prop("disabled", false);
			}
		});
	}
	$('#btnmodaltbag').on('click', function() {
		$("#aksi").val("ADD");
		$("#modaltbg3Label").text("Tambah Group 3");
		$('#perusahaan').empty().append('<option value="">Pilih</option>');
		$('#kode_tbg1').empty().append('<option value="">Pilih</option>');
		$('#kode_tbg2').empty().append('<option value="">Pilih</option>');
		$("#kode_tbg3").val('');
		$("#des").val('');
		$('#forms_add').parsley().reset();
	});
</script>
