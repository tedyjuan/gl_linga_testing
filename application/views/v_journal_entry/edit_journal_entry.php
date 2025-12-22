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
		<form id="forms_journal_entry" data-parsley-validate>
			<div class="row">
				<div class="col-6">
					<div class="row mb-1">
						<label for="branch" class="col-sm-4 col-form-label">Branch</label>
						<div class="col-sm-8 input-group-sm">
							<select style="width:100%" id="branch" name="branch" class="form-control" disabled>
								<option value="<?= $head->code_depo ?>"><?= $head->code_depo . ' - ' . $head->branch_name ?></option>
							</select>
						</div>
					</div>
					<div class="row mb-1">
						<label for="batch_type" class="col-sm-4 col-form-label">Batch Type</label>
						<div class="col-sm-8 input-group-sm">
							<select id="batch_type" name="batch_type" style="width:100%" class="form-control" disabled>
								<option value="<?= $head->code_journal_source ?>"><?= $head->code_journal_source . ' - ' . $head->journal_source_name ?></option>
							</select>
						</div>
					</div>
					<div class="row mb-1">
						<label for="batch_date" class="col-sm-4 col-form-label">Batch Date</label>
						<div class="col-sm-8 input-group-sm">
							<input type="text" id="batch_date" value="<?= $head->transaction_date; ?>" name="batch_date" data-parsley-required="true"
								data-parsley-errors-container=" .err_batch_date" required=""
								class="form-control-hover-light form-control flatpicker" placeholder="input batch date">
							<span class="text-danger err_batch_date"></span>
						</div>
					</div>
				</div>
				<div class="col-6">
					<div class="row mb-1">
						<label for="batch_number" class="col-sm-4 col-form-label">Batch Number</label>
						<div class="col-sm-8 input-group-sm">
							<input type="text" id="batch_number" value="<?= $head->batch_number ?>" name="batch_number" class="form-control" disabled>
						</div>
					</div>
					<div class="row mb-1">
						<label for="voucher_number" class="col-sm-4 col-form-label">Voucher Number</label>
						<div class="col-sm-8 input-group-sm">
							<input type="text" id="voucher_number" value="<?= $head->batch_number ?>" name="voucher_number" class="form-control" disabled>
						</div>
					</div>
					<div class="row mb-1">
						<label for="des_header" class="col-sm-4 col-form-label">Description</label>
						<div class="col-sm-8 input-group-sm">
							<input type="text" id="des_header" name="des_header" data-parsley-required="true"
								data-parsley-errors-container=".err_des_header" required=""
								class="form-control-hover-light form-control kapital" value="<?= $head->description ?>"
								placeholder="input description">
							<span class="text-danger err_des_header"></span>
						</div>
					</div>
				</div>
			</div>
			<!-- Line Items Table -->
			<hr>
			<h5 class="mb-3">Journal Items</h5>
			<div class="mb-4 mb-md-5">
				<div class="table-responsive  rounded overflow-hidden">
					<table class="table mb-0" id="lineItemsTable">
						<thead class="table-light">
							<tr>
								<th style="width: 20%;">Cost Center</th>
								<th style="width: 25%;">No. Account</th>
								<th>Description</th>
								<th style="width: 15%;">Debit</th>
								<th style="width: 15%;">Credit</th>
								<th style="width: 5%;">Action</th>
							</tr>
						</thead>
						<tbody id="lineItemsBody">
							<?php foreach ($items as $k => $rw) : ?>
								<tr data-index="<?= $rw->sequence_number; ?>" class="align-middle">
									<td class="p-2">
										<select name="cost_center[]" id="id_cc<?= $k ?>" data-parsley-required="true" data-parsley-errors-container=".err_cost_center<?= $k ?>" required=""
											class="form-control select_costcenter select2" style="width:100%">
											<option title="<?= $rw->code_cost_center . ' - ' . $rw->group_team ?>" value="<?= $rw->code_cost_center ?>"><?= $rw->code_cost_center ?></option>
										</select>
										<span class="text-danger err_cost_center<?= $k ?>"></span>
									</td>
									<td class="p-2">
										<select name="akun_debitcredit[]" data-parsley-required="true" data-parsley-errors-container=".err_akunline<?= $k ?>" required=""
											class="form-control select_account select2" style="width:100%">
											<option value="<?= $rw->code_coa ?>"><?= $rw->code_coa . ' - ' . $rw->account_name ?></option>
										</select>
										<span class="text-danger err_akunline<?= $k ?>"></span>
									</td>
									<td class="p-2">
										<input type="text" class="form-control kapital" placeholder="Description" name="description[]"
											data-parsley-required="true" data-parsley-errors-container=".err_des<?= $k ?>" required="" value="<?= $rw->description; ?>">
										<span class="text-danger err_des<?= $k ?>"></span>
									</td>
									<td class="p-2">
										<input type="text" value="<?= $rw->debit; ?>" onkeyup="debitcredit(this)" class="form-control currency debit-input" id="debit<?= $k ?>" placeholder="0" name="debit[]"
											data-parsley-errors-container=".err_debit<?= $k ?>" <?= $rw->debit == 0 ? 'readonly' : 'required' ?>>
										<span class="text-danger err_debit<?= $k ?>"></span>
									</td>
									<td class="p-2">
										<input type="text" value="<?= $rw->credit; ?>" onkeyup="debitcredit(this)" class="form-control currency credit-input" id="credit<?= $k ?>" placeholder="0" name="credit[]"
											data-parsley-errors-container=".err_kredit<?= $k ?>" <?= $rw->credit == 0 ? 'readonly' : 'required' ?>>
										<span class="text-danger err_kredit<?= $k ?>"></span>
									</td>
									<td class="p-2 text-center"><i class="bi bi-trash text-danger fs-1 btnDeleteLineItem"></i></td>
								</tr>

							<?php endforeach; ?>
						</tbody>
						<tfoot>
							<tr class="table-light">
								<td colspan="12" class="p-2 fw-bold">
									<div class="row">
										<div class="col-lg-4 col-md-4 col-12">
											<label class="form-label" for="totalDebit">Debit Amount</label>
											<input type="text" readonly id="totalDebit" value="<?= $head->total_debit ?>" name="totalDebit" class="form-control currency" placeholder="0">
										</div>
										<div class="col-lg-4 col-md-4 col-12">
											<label class="form-label" for="totalCredit">Credit Amount</label>
											<input type="text" readonly id="totalCredit" value="<?= $head->total_credit ?>" name="totalCredit" class="form-control currency" placeholder="0">
										</div>
										<div class="col-lg-4 col-md-4 col-12">
											<label class="form-label" for="totalDifference">Difference</label>
											<input type="text" readonly id="totalDifference" value="<?= $head->difference ?>" name="totalDifference" class="form-control currency" placeholder="0">
										</div>
									</div>
								</td>
							</tr>
						</tfoot>
					</table>
					<div id="showbtnadd" class="add-btn-wrapper">
						<div class="mt-2 d-flex justify-content-end mb-4">
							<button type="button" id="addLineItemBtn" class="btn btn-sm btn-success">
								<i class="bi bi-plus-circle"></i> Add Line Item
							</button>
						</div>
					</div>

				</div>
			</div>
			<!-- Action Buttons -->
			<div class="d-flex justify-content-end gap-2 pt-3">
				<button type="button" id="btnsubmit" class="btn btn-sm btn-primary"><i class="bi bi-send"></i> Save</button>
				<button type="reset" id="resetBtn" class="btn btn-sm btn-outline-danger"><i class="bi bi-eraser-fill"></i> Reset</button>
			</div>
		</form>
	</div>
</div>

<script>
	$(document).ready(function() {
		let tanggalDb = "<?= $head->transaction_date ?>";
		let persetujuan = "<?= $close_period; ?>"; // on/off

		// Convert database date ke objek JS Date
		let d = new Date(tanggalDb);
		let year = d.getFullYear();
		let month = d.getMonth(); // 0 = Januari

		let minDate, maxDate;

		if (persetujuan === "off") {
			// batas hanya bulan ini (1 sampai akhir bulan)
			minDate = new Date(year, month, 1);

			// Dapatkan akhir bulan
			maxDate = new Date(year, month + 1, 0);
		} else {
			// persetujuan = on
			// minDate = 1 januari tahun tersebut
			minDate = new Date(year, 0, 1);

			// maxDate = akhir bulan dari tanggal yang dipilih
			maxDate = new Date(year, month + 1, 0);
		}

		// Inisialisasi Flatpickr
		$("#batch_date").flatpickr({
			dateFormat: "Y-m-d",
			minDate: minDate,
			maxDate: maxDate
		});
	});
	$(document).ready(function() {
		// $(".flatpicker").flatpickr({
		// 	dateFormat: "Y-m-d"
		// });


		$(".select2").select2({
			placeholder: 'Search...',
		});
		$('.currency').mask("#.##0", {
			reverse: true
		});
		$('.kapital').on('input', function(e) {
			this.value = this.value.replace(/[^a-zA-Z0-9 /-]/g, '').toUpperCase();
		});
		$('#addLineItemBtn').on('click', function() {
			var ll = Date.now();
			var newRow = `
				<tr data-index="${ll}" class="align-middle">
					<td class="p-2">
						<select name="cost_center[]" id="id_cc${ll}" data-parsley-required="true" data-parsley-errors-container=".err_cost_center${ll}" required=""
							class="form-control" style="width:100%">
						</select>
						<span class="text-danger err_cost_center${ll}"></span>
					</td>
					<td class="p-2">
						<select name="akun_debitcredit[]" data-parsley-required="true" data-parsley-errors-container=".err_akunline${ll}" required=""
							class="form-control select_account" style="width:100%">
						</select>
						<span class="text-danger err_akunline${ll}"></span>
					</td>
					<td class="p-2">
						<input type="text" class="form-control kapital" placeholder="Description"  name="description[]"
						data-parsley-required="true" data-parsley-errors-container=".err_des${ll}" required="">
						<span class="text-danger err_des${ll}"></span>
					</td>
					<td class="p-2">
						<input type="text" onkeyup="debitcredit(this)" class="form-control currency debit-input" id="debit${ll}" placeholder="0" name="debit[]"
						data-parsley-errors-container=".err_debit${ll}" required>
						<span class="text-danger err_debit${ll}"></span>
					</td>
					<td class="p-2">
						<input type="text" onkeyup="debitcredit(this)" class="form-control currency credit-input" id="credit${ll}" placeholder="0" name="credit[]"
						data-parsley-errors-container=".err_kredit${ll}" required>
						<span class="text-danger err_kredit${ll}"></span>
					</td>
					<td class="p-2 text-center">
						<i class="bi bi-trash text-danger fs-1 btnDeleteLineItem"></i>
					</td>
				</tr>
			`;
			$('#lineItemsBody').append(newRow);
			updateTotal();
			$('.currency').mask("#.##0", {
				reverse: true
			});
			$('.kapital').on('input', function(e) {
				this.value = this.value.replace(/[^a-zA-Z0-9 /-]/g, '').toUpperCase();
			});
			var id_element = 'id_cc' + ll;
			select_costcenter_byid(id_element);
			select_account();
		});


		// Update total debit and credit when line items change
		$('#lineItemsBody').on('input', '.debit-input, .credit-input', function() {
			updateTotal();
		});

		function updateTotal() {
			var totalDebit = 0;
			var totalCredit = 0;
			$('.currency').mask("#.##0", {
				reverse: true
			});
			// Menghitung total debit
			$('.debit-input').each(function() {
				var value = $(this).val();
				value = value.replace(/\./g, ''); // Menghapus titik
				totalDebit += parseFloat(value) || 0;
			});

			// Menghitung total kredit
			$('.credit-input').each(function() {
				var value = $(this).val();
				value = value.replace(/\./g, ''); // Menghapus titik
				totalCredit += parseFloat(value) || 0;
			});

			$('#totalDebit').val(totalDebit.toLocaleString('id-ID'));
			$('#totalCredit').val(totalCredit.toLocaleString('id-ID'));
			var difference = totalDebit - totalCredit;
			$('#totalDifference').val(difference.toLocaleString('id-ID'));
			if (difference == 0 && totalDebit == 0 && totalCredit == 0) {
				$("#totalDifference").removeClass('is-invalid');
				$("#totalDifference").removeClass('is-valid');
			} else {
				if (difference === 0) {
					$("#totalDifference").removeClass('is-invalid').addClass('is-valid');
				} else {
					$("#totalDifference").removeClass('is-valid').addClass('is-invalid');
				}
			}
		}
		// Delete line item row (with restriction for single row)
		$('#lineItemsBody').on('click', '.btnDeleteLineItem', function() {
			if ($('#lineItemsTable tbody tr').length > 1) {
				$(this).closest('tr').remove();
				updateTotal();
			} else {
				swet_gagal("You cannot delete the last row.");
			}
		});
		$('#btnsubmit').click(function(e) {
			e.preventDefault();
			var uuid = "<?= $head->uuid; ?>";
			// Ambil data dari form
			let formData = {
				uuid: uuid,
				batch_date: $('#batch_date').val(),
				des_header: $('#des_header').val(),
				lineItems: [],
			};

			// Mengambil data dari table line items
			$('#lineItemsBody tr').each(function() {
				var lineItem = {
					cost_center: $(this).find('select[name="cost_center[]"]').val(),
					accountNo: $(this).find('select[name="akun_debitcredit[]"]').val(),
					description: $(this).find('input[name="description[]"]').val(),
					debit: $(this).find('input[name="debit[]"]').val().replace(/\./g, ''), // Menghapus titik jika ada
					credit: $(this).find('input[name="credit[]"]').val().replace(/\./g, '') // Menghapus titik jika ada
				};
				formData.lineItems.push(lineItem);
			});
			// Validasi form jika perlu
			let form = $('#forms_journal_entry');
			form.parsley().validate();

			// Jika form valid, lanjutkan ke proses AJAX
			if (form.parsley().isValid()) {
				$.ajax({
					url: "<?= base_url('C_journal_entry/updatedata') ?>",
					type: 'POST',
					data: JSON.stringify(formData),
					contentType: 'application/json',
					dataType: 'JSON',
					beforeSend: function() {
						showLoader();
					},
					success: function(data) {
						console.log(data);

						if (data.hasil == 'true') {
							swet_sukses(data.pesan);
							loadform('<?= $load_grid ?>');
						} else {
							swet_gagal(data.pesan);
							hideLoader();
						}
					}
				});
			}
		});
	});
</script>
<script>
	function debitcredit(element) {
		var id = element.id;
		var nilai = element.value;
		var firstInput = nilai.charAt(0);
		var second = nilai.charAt(1);
		if (firstInput == 0) {
			$(element).val("");
		}

		let id_number = id.replace(/\D/g, "");
		let id_text = id.replace(/[^a-zA-Z]/g, "");
		if (id_text == 'debit') {
			if (nilai == '' || nilai == 0) {
				// console.log("debit tidak ada nilai → credit terbuka & wajib");
				$("#credit" + id_number).prop('required', true).prop('readonly', false);
			} else {
				// debit ada nilai → credit terkunci & tidak wajib
				// console.log("debit ada nilai → credit terkunci & tidak wajib");
				$("#debit" + id_number).prop('required', true).prop('readonly', false);
				$("#credit" + id_number).prop('required', false).prop('readonly', true);
			}


		} else if (id_text == 'credit') {
			if (nilai == '' || nilai == 0) {
				// console.log("credit tidak ada nilai → debit terbuka & wajib");
				$("#debit" + id_number).prop('required', true).prop('readonly', false);

			} else {
				// console.log("credit ada nilai → debit terkunci & tidak wajib");
				$("#debit" + id_number).prop('required', false).prop('readonly', true);
				$("#credit" + id_number).prop('required', true).prop('readonly', false);
			}
		}


		$("#debit" + id_number).parsley().validate();
		$("#credit" + id_number).parsley().validate();
	}

	function select_account() {
		var code_company = "<?= $head->code_company; ?>";
		var branch = $('#branch').val();
		$(".select_account").select2({
			placeholder: 'Search account',
			minimumInputLength: 1,
			allowClear: true,
			ajax: {
				url: "<?= base_url('C_petty_cash/Coa_all') ?>",
				dataType: "json",
				delay: 250,
				data: function(params) {
					return {
						cari: params.term,
						code_company: code_company,
					};
				},
				processResults: function(data) {
					var results = [];
					$.each(data, function(index, item) {
						results.push({
							id: item.account_number,
							text: item.account_number + ' - ' + item.name,
						});
					});
					return {
						results: results
					};
				}
			}
		});
	}

	function select_costcenter_byid(id_element) {
		var code_company = "<?= $head->code_company; ?>";
		var branch = $('#branch').val();
		$("#" + id_element).select2({
			placeholder: 'Search account',
			minimumInputLength: 1,
			ajax: {
				url: "<?= base_url('C_journal_entry/Costcenter_all') ?>",
				dataType: "json",
				delay: 250,
				data: function(params) {
					return {
						cari: params.term,
						code_company: code_company,
					};
				},
				processResults: function(data) {
					return {
						results: data.map(function(item) {
							return {
								id: item.code_cost_center,
								code: item.code_cost_center,
								group: item.group_team,
								text: item.code_cost_center + ' - ' + item.group_team,
							};
						})
					};
				}
			},

			// Tampilan saat dropdown (normal)
			templateResult: function(data) {
				if (data.loading) return data.text;
				return data.code + " - " + data.group;
			},

			// Tampilan setelah dipilih
			templateSelection: function(data) {
				return data.code || data.text;
			}
		});
	}

	function select_costcenter() {
		var code_company = '<?= $head->code_company; ?>';
		var branch = $('#branch').val();
		$(".select_costcenter").select2({
			placeholder: 'Search account',
			minimumInputLength: 1,
			ajax: {
				url: "<?= base_url('C_journal_entry/Costcenter_all') ?>",
				dataType: "json",
				delay: 250,
				data: function(params) {
					return {
						cari: params.term,
						code_company: code_company,
					};
				},
				processResults: function(data) {
					return {
						results: data.map(function(item) {
							return {
								id: item.code_cost_center,
								code: item.code_cost_center,
								group: item.group_team,
								text: item.code_cost_center + ' - ' + item.group_team,
							};
						})
					};
				}
			},

			// Tampilan saat dropdown (normal)
			templateResult: function(data) {
				if (data.loading) return data.text;
				return data.code + " - " + data.group;
			},

			// Tampilan setelah dipilih
			templateSelection: function(data) {
				return data.code || data.text;
			}
		});
	}
</script>
<script>
	$(document).ready(function() {
		initSelect2All();
	});


	/* ==========================================================
	   SAFE Destroy Select2 (hanya destroy kalau sudah init)
	   ========================================================== */
	function safeDestroySelect2(selector) {
		$(selector).each(function() {
			// Select2 aktif kalau punya class ini
			if ($(this).hasClass("select2-hidden-accessible")) {
				$(this).select2("destroy");
			}
		});
	}


	/* ==========================================================
	   MAIN INIT FUNCTION (panggil setiap render / setelah append row)
	   ========================================================== */
	function initSelect2All() {

		// destroy dulu (aman tidak error)
		safeDestroySelect2('.select_costcenter');
		safeDestroySelect2('.select_account');

		// init ulang semua select cost center
		select_costcenter();

		// init ulang semua select account
		select_account();
	}
</script>
