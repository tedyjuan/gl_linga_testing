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
					<h2>Informasi Header</h2>
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
						<label class="form-label" for="id_number_ledger">ID Akun Ledger</label>
						<div class="input-group mb-3">
							<input type="text" class="form-control bg-soft-dark" id="id_number_ledger" name="id_number_ledger"
								value="<?= $data_ledger->account_number ?>" disabled>
						</div>
					</div>
					<div class="mb-3">
						<label class="form-label" for="nama_akun_ledger">Nama Ledger</label>
						<input type="text" id="nama_akun_ledger" name="nama_akun_ledger" data-parsley-required="true"
							data-parsley-errors-container=".err_nama_akun_ledger" required="" value="<?= $data_ledger->name_coa; ?>"
							class="form-control-hover-light form-control kapital"
							placeholder="input nama akun ledger">
						<span class="text-danger err_nama_akun_ledger"></span>
					</div>
					<div class="mb-3">
						<label class="form-label" for="type_costcenter">Type Cost Center</label>
						<div class="row">
							<div class="col-sm mb-2 mb-sm-0">
								<label class="form-control" for="formControlRadioEg1">
									<span class="form-check">
										<input type="radio" class="form-check-input" name="radio_type" id="formControlRadioEg1"
											<?= ($data_ledger->cost_center_type == 'depo') ? 'checked' : ''; ?> onclick="showtabel('depo')">
										<span class="form-check-label">Depo</span>
									</span>
								</label>
							</div>
							<div class="col-sm mb-2 mb-sm-0">
								<label class="form-control" for="formControlRadioEg2">
									<span class="form-check">
										<input type="radio" class="form-check-input" name="radio_type" id="formControlRadioEg2"
											<?= ($data_ledger->cost_center_type == 'unit') ? 'checked' : ''; ?> onclick="showtabel('satuan')">
										<span class="form-check-label">Satuan</span>
									</span>
								</label>
							</div>
						</div>
					</div>
					<div class="mb-3">
						<label class="form-label" for="deskripsi">Deskripsi</label>
						<input type="text" id="deskripsi" name="deskripsi" data-parsley-required="true"
							data-parsley-errors-container=".err_deskripsi" required="" value="<?= $data_ledger->des; ?>"
							class="form-control-hover-light form-control" placeholder="-">
						<span class="text-danger err_deskripsi"></span>
					</div>
					<table class="table table-sm  table-bordered" id="resulttabel_satuan" style="width: 100%; display: none;">
						<tbody>
							<tr class="table-primary">
								<th style="width: 100%">Start Cost Center Satuan</th>
							</tr>
							<tr>
								<td>
									<input type="text" id="cc_start" onclick="tabeldata('<?= $data->code_company ?>', 'cc_satuan_start')"
										name="cc_start" data-parsley-required="true" required="" readonly
										data-bs-toggle="offcanvas" data-bs-target="#offcanvasRight" aria-controls="offcanvasRight"
										data-parsley-errors-container=".err_cc_start" class="form-control-hover-light form-control" placeholder="-">
									<span class="text-danger err_cc_start"></span>
								</td>
							</tr>
							<tr class="table-primary">
								<th style="width: 100%">End Cost Center Satuan</th>
							</tr>
							<tr>
								<td>
									<input type="text" id="cc_end" onclick="tabeldata('<?= $data->code_company ?>', 'cc_satuan_end')"
										name="cc_end" data-parsley-required="true" required="" readonly
										data-bs-toggle="offcanvas" data-bs-target="#offcanvasRight" aria-controls="offcanvasRight"
										data-parsley-errors-container=".err_cc_end" class="form-control-hover-light form-control" placeholder="-">
									<span class="text-danger err_cc_end"></span>
								</td>
							</tr>
						</tbody>
					</table>
					<table class="table table-sm  table-bordered" id="resulttabel_depo" style="width: 100%; display: none;">
						<thead>
							<tr class="table-primary">
								<th style="width: 20%">Kode depo</th>
								<th style="width: 50%">Nama Depo</th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td style="width: 20%">
									<input type="text" id="cc_kode_depo" onclick="tabeldata('<?= $data->code_company ?>', 'cc_depo')"
										name="cc_kode_depo" readonly class="form-control-hover-light form-control" placeholder="-"
										data-bs-toggle="offcanvas" data-bs-target="#offcanvasRight" aria-controls="offcanvasRight">
								</td>
								<td style="width: 80%">
									<input type="text" id="cc_nama_depo" onclick="tabeldata('<?= $data->code_company ?>', 'cc_depo')"
										name="cc_nama_depo" data-parsley-required="true" required="" readonly
										data-bs-toggle="offcanvas" data-bs-target="#offcanvasRight" aria-controls="offcanvasRight"
										data-parsley-errors-container=".err_cc_nama_depo" class="form-control-hover-light form-control" placeholder="-">
									<span class="text-danger err_cc_nama_depo"></span>
								</td>
							</tr>
						</tbody>
					</table>

				</div>
			</div>
			<div class="col-md-12 d-flex justify-content-end">
				<div></div>
				<div>
					<button type="button" id="btnsubmit" class="btn btn-sm btn-primary"><i class="bi bi-send"></i>
						Update</button>
					<button type="reset" class="btn btn-sm btn-outline-danger"><i class="bi bi-eraser-fill"></i>
						Reset</button>
				</div>
			</div>
		</form>
	</div>
</div>
<div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasRight" aria-labelledby="offcanvasRightLabel">
	<div class="offcanvas-header">
		<h5 id="offcanvasRightLabel">Type Cost Center</h5>
		<button type="button" class="btn-close" data-bs-dismiss="offcanvas" id="canvas_close" aria-label="Close"></button>
	</div>
	<div class="offcanvas-body" style=" padding-top: 0; padding-right: 10px;  padding-left: 10px;">
		<table class="table table-sm table-striped table-hover table-bordered" id="mytable" style="width: 100%;">
			<thead>
				<tr class="table-primary">
					<th style="width: 5%">Pilih</th>
					<th style="width: 95%">Cost Center</th>
				</tr>
			</thead>
		</table>
	</div>
</div>
<script>
	$(document).ready(function() {

		var initialType = '<?= $data_ledger->cost_center_type; ?>'; // 'depo' atau 'satuan'
		if (initialType == 'unit') {
			var type = 'satuan';
		} else {
			var type = 'depo';
		}
		// Panggil showtabel untuk menampilkan tabel sesuai tipe
		showtabel(type);
		$(".select2").select2();


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
			$.ajax({
				url: "<?= base_url('C_chart_of_account/updateLedger') ?>",
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

	function tabeldata(data_company, type) {
		// kalau sebelumnya sudah ada instance, hancurkan dulu
		if (window.mytableDT && $.fn.dataTable.isDataTable('#mytable')) {
			window.mytableDT.clear().destroy();
			window.mytableDT = null;
		}
		var url = '';
		if (type === 'cc_depo') {
			url = "<?= base_url('C_chart_of_account/griddata_depo') ?>"; // Ganti dengan URL untuk depo
		} else {
			url = "<?= base_url('C_chart_of_account/griddata_cc') ?>"; // Ganti dengan URL untuk cost center
		}
		window.mytableDT = $('#mytable').DataTable({
			processing: true,
			serverSide: true,
			destroy: true,
			retrieve: true,
			pageLength: 100,
			info: false,
			lengthChange: false,
			ajax: {
				url: url,
				type: "POST",
				data: {
					company_id: data_company,
				},
			},
			columnDefs: [{
					orderable: false,
					targets: 0
				},
				{
					orderable: true,
					targets: 1
				}
			],
		});
	}

	function pilihdepo(kode, nama) {
		$("#canvas_close").trigger('click');
		$('#forms_add').parsley().reset();
		$('#cc_kode_depo').val(kode);
		$('#cc_nama_depo').val(nama);
	}

	function pilihsatuan(param) {
		$("#canvas_close").trigger('click');
		$('#forms_add').parsley().reset();
		var start = $('#cc_start').val();
		var end = $('#cc_end').val();
		if (start == '') {
			$('#cc_start').val(param);
		} else {
			if (start != '' && end != '') {
				$('#cc_start').val(param);
				$('#cc_end').val('');
			} else {
				var startValue = start.match(/\((\d+)\)/);
				var endValue = param.match(/\((\d+)\)/);
				var startNum = startValue[1];
				var endNum = endValue[1];
				if (startNum > endNum) {
					swet_gagal("Inputan pertama harus lebih kecil dari inputan kedua.");
					$('#cc_start').val('');
					$('#cc_end').val('');
					return;
				}
				$('#cc_end').val(param);
			}
		}
	}


	function showtabel(type) {
		// Sembunyikan kedua tabel
		$('#resulttabel_satuan, #resulttabel_depo').hide();

		// Siapkan reference input
		const $satuan = $('#cc_start, #cc_end');
		const $depo = $('#cc_kode_depo, #cc_nama_depo');

		// Bersihkan error Parsley (kalau dipakai)
		if ($('#myForm').length && $('#myForm').parsley) {
			$('#myForm').parsley().reset();
		}

		// Matikan kewajiban pada semua input dulu
		$satuan.prop('required', false).removeAttr('data-parsley-required').prop('disabled', true);
		$depo.prop('required', false).removeAttr('data-parsley-required').prop('disabled', true);

		// (opsional) kosongkan nilai & error message yang tidak dipakai
		$satuan.val('');
		$('.err_cc_start, .err_cc_end').text('');
		$depo.val('');
		$('.err_cc_nama_depo').text('');

		if (type == 'satuan') {
			$('#resulttabel_satuan').show();
			var cc_min = "<?= isset($min->kode_cc) && !empty($min->kode_cc) ? $min->kode_cc : ''; ?>";
			var cc_max = "<?= isset($max->kode_cc) && !empty($max->kode_cc) ? $max->kode_cc : ''; ?>";
			if (cc_min && cc_max) {
				var cc_min_display = "(" + cc_min + ") <?= isset($min->group_team) ? $min->group_team : ''; ?>";
				var cc_max_display = "(" + cc_max + ") <?= isset($max->group_team) ? $max->group_team : ''; ?>";
				$("#cc_start").val(cc_min_display);
				$("#cc_end").val(cc_max_display);
			} 
			$satuan.prop('disabled', false).prop('required', true).attr('data-parsley-required', 'true');
		}

		if (type == 'depo') {
			$('#resulttabel_depo').show();
			var cc_depo_code = "<?= isset($depo->code_depo) && !empty($depo->code_depo) ? $depo->code_depo : ''; ?>";
			var cc_depo_name = "<?= isset($depo->name) && !empty($depo->name) ? $depo->name : ''; ?>";
			if (cc_depo_code && cc_depo_name) {
				$('#cc_kode_depo').val(cc_depo_code);
				$('#cc_nama_depo').val(cc_depo_name);
			} 
			$depo.prop('disabled', false).prop('required', true).attr('data-parsley-required', 'true');
		}

	}
</script>
