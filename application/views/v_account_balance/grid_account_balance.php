<!-- Card -->
<style>
	.coa-selected {
		background-color: #0d6efd !important;
		/* biru bootstrap */
		color: white !important;
		font-weight: bold;
		cursor: pointer;
	}

	#tableAccount tbody tr {
		cursor: pointer;
	}
</style>

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
		<form id="forms_add">
			<div class="row">

				<div class="col-6">
					<div class="mb-3">
						<label class="form-label" for="company">Company</label>
						<select style="width: 100%;" id="company" name="company" class="form-control-hover-light form-control"
							data-parsley-required="true" data-parsley-errors-container=".err_company" required="">
						</select>
						<span class="text-danger err_company"></span>
					</div>
				</div>
				<div class="col-6">
					<div class="mb-3">
						<label class="form-label" for="branch">Branch</label>
						<select style="width: 100%;" id="branch" name="branch" class="form-control-hover-light form-control select2"
							data-parsley-required="true" data-parsley-errors-container=".err_branch" required="">
						</select>
						<span class="text-danger err_branch"></span>
					</div>
				</div>
				<div class="col-6">
					<div class="mb-3">
						<label class="form-label" for="year">Year</label>
						<select style="width: 100%;" id="year" name="year" class="form-control-hover-light form-control select2"
							data-parsley-required="true" data-parsley-errors-container=".err_year" required="">
						</select>
						<span class="text-danger err_year"></span>
					</div>
				</div>
				<div class="col-6">
					<div class="mb-3">
						<label class="form-label" for="cost_center">Cost Center</label>
						<select style="width: 100%;" id="cost_center" name="cost_center" class="form-control-hover-light form-control select2"
							data-parsley-required="true" data-parsley-errors-container=".err_cost_center" required="">
						</select>
						<span class="text-danger err_cost_center"></span>
					</div>
				</div>
			</div>
			<div class="row mt-3">
				<div class="col-4">
					<div class="mb-3">
						<div class="input-group input-group-merge input-group-sm">
							<div class="input-group-prepend input-group-text" id="searchTabelAddOn">
								<i class="bi-search"></i>
							</div>
							<input type="text" class="form-control " id="searchTabel" placeholder="search account number" aria-label="search account number" aria-describedby="searchTabelAddOn">
						</div>
						<table class="table table-bordered table-hover border-secondary  mt-2" id="tableAccount">
							<thead>
								<tr>
									<th style="width: 35%;">ACCOUNT NO</th>
									<th style="width: 65%;">DESCRIPTION</th>
								</tr>
							</thead>
							<tbody>
								<tr>
									<td colspan="2" class="text-center text-danger">Tidak ada data</td>
								</tr>
							</tbody>
						</table>
					</div>
				</div>
				<div class="col-8">
					<div class="row mb-1">
						<label for="inputEmail3" class="col-sm-3 col-form-label ">Opening Balance</label>
						<div class="col-sm-9">
							<div class="col-sm-9 input-group input-group-sm">
								<input type="text" id="opening_balance" class="form-control format_duit" placeholder="0" disabled>
								<span class="input-group-text bg-primary text-white" id="edit_op" title="Edit" style="display: none;">
									<i class="bi bi-pen"></i>
								</span>
								<span class="input-group-text bg-success text-white" id="save_op" title="Save" style="display: none;">
									<i class="bi bi-save"></i>
								</span>
								<span class="input-group-text bg-danger text-white" id="edit_batal" title="Cancel" style="display: none;">
									<i class="bi bi-x-lg"></i>
								</span>
							</div>
						</div>
					</div>
					<div class="row mb-1">
						<label for="closing_balance" class="col-sm-3 col-form-label">Closing Balance</label>
						<div class="col-sm-9 input-group-sm">
							<input type="text" class="form-control" id="closing_balance" placeholder="0" disabled>
						</div>
					</div>
					<table class="table table-bordered border-secondary  mt-2" id="tabel_opening_balance">
						<thead>
							<tr>
								<th style="width: 10%;">Periode</th>
								<th style="width: 45%;">DEBIT</th>
								<th style="width: 45%;">CREDIT</th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td colspan="3" class="text-center text-danger">Tidak ada data</td>
							</tr>
						</tbody>
					</table>
				</div>
			</div>
		</form>
	</div>
</div>
<script>
	$(document).ready(function() {
		tahun();
		duit();
		$(".select2").select2({
			placeholder: "Pilih",
		});

		$("#company").select2({
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

		$(".flatpiker-year").flatpickr({
			dateFormat: "Y",
			minDate: "1900",
			maxDate: "2100",
			onReady(_, __, fp) {
				fp.currentYearElement.removeAttribute("disabled"); // enable year select
				fp.monthElements.forEach(m => m.style.display = "none"); // hide months
			}
		});
	})
	// Klik EDIT
	$('#edit_op').on('click', function() {
		$('#opening_balance').prop('disabled', false).focus();

		// tombol tampil / sembunyi
		$('#edit_op').hide();
		$('#save_op').show();
		$('#edit_batal').show();
	});

	// Klik CANCEL
	$('#edit_batal').on('click', function() {

		// disable input lagi & kembalikan value awal
		$('#opening_balance').prop('disabled', true);

		// tombol tampil / sembunyi
		$('#edit_op').show();
		$('#save_op').hide();
		$('#edit_batal').hide();
	});

	// Klik SAVE
	$('#save_op').on('click', function() {
		let value = $('#opening_balance').val();
		var year = $("#year").val();
		var company = $("#company").val();
		var branch = $("#branch").val();
		var cost_center = $("#cost_center").val();
		let code_coa = $("#tableAccount tbody tr.coa-selected").data("coa");

		// lakukan AJAX
		$.ajax({
			url: '<?= base_url('C_account_balance/savedata'); ?>', // ganti dengan URL kamu
			type: 'POST',
			method: 'POST',
			dataType: 'JSON',
			data: {
				opening_balance: value,
				company: company,
				branch: branch,
				year: year,
				cost_center: cost_center,
				code_coa: code_coa,
			},
			beforeSend: function() {
				showLoader();
			},
			success: function(data) {
				// setelah sukses, kembali ke mode awal
				$('#opening_balance').prop('disabled', true);
				$('#edit_op').show();
				$('#save_op').hide();
				$('#edit_batal').hide();
				if (data.hasil == 'true') {
					$("#closing_balance").val(formatRupiah(data.ending_balance));
					swet_sukses(data.pesan);
				} else {
					swet_gagal(data.pesan);
				}
				hideLoader();
			},
			error: function(err) {
				console.error('Error:', err);
				alert('Gagal menyimpan data');
			}
		});
	});

	$('#searchTabel').on('keyup', function() {
		var value = $(this).val().toLowerCase();

		$('#tableAccount tbody tr').filter(function() {
			$(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
		});
	});

	$('#company').on('change', function() {
		var companyCode = $(this).val();
		$('#branch').empty().append('<option value="">Pilih</option>');
		$('#cost_center').empty().append('<option value="">Pilih</option>');
		reset_coa();
		reset_dc();

		if (companyCode) {
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
						$('#branch').append('<option value="' + depo.code_depo + '"  data-company="' + depo.code_company + '">' + depo.code_depo + '-' + depo.name + '</option>');
					});
				}
			});

		}
	});
	$('#branch').on('change', function() {
		var branch = $(this).val();
		reset_coa();
		reset_dc();

		var company = $('#branch option:selected').data('company');
		$('#cost_center').empty().append('<option value="">Pilih</option>');
		if (branch) {
			$.ajax({
				url: '<?= base_url('C_global/getCostCenterByDepo'); ?>',
				method: 'POST',
				data: {
					company: company,
					branch: branch,
				},
				dataType: 'JSON',
				beforeSend: function() {
					showLoader();
				},
				success: function(data) {
					hideLoader();
					data.forEach(function(val) {
						$('#cost_center').append('<option value="' + val.code_cost_center + '"  data-company="' + val.code_company + '">' + val.code_cost_center + ' - ' + val.group_team + '</option>');
					});
				}
			});
		}
	});

	$('#cost_center').on('change', function() {
		var cost_center = $(this).val();
		var company = $('#cost_center option:selected').data('company');
		$('#tableAccount tbody').empty();
		reset_dc();
		if (cost_center) {
			$.ajax({
				url: '<?= base_url('C_global/getAccountCenter'); ?>',
				method: 'POST',
				data: {
					company: company,
					cost_center: cost_center,
				},
				dataType: 'JSON',
				beforeSend: function() {
					showLoader();
				},
				success: function(data) {
					hideLoader();
					if (data.length == 0) {
						$('#tabel_opening_balance tbody').append(`
							<tr>
								<td colspan="2" class="text-center text-danger">Tidak ada data</td>
							</tr>`);
						return;
					}
					// loop data & isi row tabel
					data.forEach(function(val) {
						$('#tableAccount tbody').append(`
							<tr data-coa="${val.code_coa}">
								<td>${val.code_coa}</td>
                           		<td>${val.name}</td>
							</tr>`);
					});
				},
				error: function() {
					hideLoader();
					alert("Error loading data");
				}
			});

		}
	});
	// event click untuk pilih COA
	$(document).on("click", "#tableAccount tbody tr", function() {

		// hapus highlight dari semua baris
		$("#tableAccount tbody tr").removeClass("coa-selected");

		// tambahkan highlight ke baris yg diklik
		$(this).addClass("coa-selected");

		// ambil COA dari atribut data-coa
		let coa = $(this).data("coa");

		// panggil fungsi
		pilihcoa(coa);
	});

	function pilihcoa(coa) {
		var year = $("#year").val();
		var branch = $("#branch").val();
		var cost_center = $("#cost_center").val();
		$("#edit_op").hide()
		reset_dc();
		$.ajax({
			url: '<?= base_url('C_account_balance/getCOA'); ?>',
			method: 'POST',
			data: {
				year: year,
				branch: branch,
				cost_center: cost_center,
				coa: coa
			},
			dataType: 'JSON',
			beforeSend: function() {
				showLoader();
			},
			success: function(data) {
				hideLoader();
				$("#edit_op").show()
				// SET OPENING BALANCE
				$("#opening_balance").val(
					data.opening_balance ? formatRupiah(data.opening_balance) : ""
				);

				// SET CLOSING BALANCE
				$("#closing_balance").val(
					data.ending_balance ? formatRupiah(data.ending_balance) : ""
				);
				// =====================================
				// BANGUN TABEL PERIODE 1–12
				// =====================================
				let rows = "";

				// Jika data.period kosong, tampilkan pesan
				if (!data.period || data.period.length === 0) {
					rows = `<tr><td colspan="3" class="text-center text-danger">Tidak ada data</td></tr>`;
				} else {
					for (let i = 0; i < 12; i++) {
						let row = data.period[i] || {
							debit: 0,
							credit: 0
						};
						rows += `
                        <tr>
                            <td class="text-center">${String(i + 1).padStart(2, '0')}</td>
                            <td>${formatRupiah(row.debit)}</td>
                            <td>${formatRupiah(row.credit)}</td>
                        </tr>
                    `;
					}
				}

				$("#tabel_opening_balance tbody").html(rows);
			},
			error: function() {
				hideLoader();
				alert("Error loading data");
			}
		});
	}

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

	function reset_coa() {
		$('#tableAccount tbody').empty();
		$('#tableAccount tbody').append(`<tr><td colspan="2" class="text-center text-danger">Tidak ada data</td></tr>`);
	}

	function reset_dc() {
		$('#opening_balance').val('');
		$('#closing_balance').val('');
		$('#opening_balance').prop('disabled', true);
		$('#edit_op').hide();
		$('#save_op').hide();
		$('#edit_batal').hide();
		$('#tabel_opening_balance tbody').empty();
		$('#tabel_opening_balance tbody').append(`<tr><td colspan="3" class="text-center text-danger">Tidak ada data</td></tr>`);
	}
</script>
