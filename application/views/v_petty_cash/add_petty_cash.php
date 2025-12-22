<!-- <style>
	.parsley-required {
		color: red;
	}
</style> -->
<div class="card">
	<div class="card-header">
		<div class="row align-items-center mb-2">
			<div class="col-md-12 d-flex justify-content-between">
				<h2 class="mb-0"><?= $judul ?></h2>
				<div class="div">
					<button class="btn btn-sm btn-primary" onclick="loadform('<?= $load_grid ?>')"><i class="bi bi-arrow-left-circle"></i> Back</button>
					<a href="javascript:void(0)" class="btn btn-sm btn-outline-primary" onclick="loadform('<?= $load_back ?>')">
						<i class="bi bi-arrow-clockwise"></i> Refresh
					</a>
				</div>
			</div>
		</div>
	</div>
	<div class="card-body p-4 p-md-5">
		<form id="voucherForm">
			<div class="row g-4 g-md-5 mb-4 mb-md-5">
				<div class="col-md-6">
					<div class="mb-3">
						<label class="form-label ">Proveniance</label>
						<div class="d-flex gap-4 mt-2">
							<div class="form-check">
								<input class="form-check-input" type="radio" name="proveniance" id="cash" value="cash"
									data-parsley-required="true" data-parsley-errors-container=".err_proveniance" required="">
								<label class="form-check-label cursor-pointer" for="cash">Cash</label>
							</div>
							<div class="form-check">
								<input class="form-check-input" type="radio" name="proveniance" id="bank" value="bank"
									data-parsley-required="true" data-parsley-errors-container=".err_proveniance" required="">
								<label class="form-check-label cursor-pointer" for="bank">Bank</label>
							</div>
						</div>
						<span class="text-danger err_proveniance"></span>
					</div>
					<div class="mb-3">
						<label class="form-label mt-4">Flow</label>
						<div class="d-flex gap-4 ">
							<div class="form-check">
								<input class="form-check-input" type="radio" name="flow" id="in" value="in"
									data-parsley-required="true" data-parsley-errors-container=".err_flow" required="">
								<label class="form-check-label cursor-pointer" for="in">In</label>
							</div>
							<div class="form-check">
								<input class="form-check-input" type="radio" name="flow" id="out" value="out"
									data-parsley-required="true" data-parsley-errors-container=".err_flow" required="">
								<label class="form-check-label cursor-pointer" for="out">Out</label>
							</div>
						</div>
						<span class="text-danger err_flow"></span>
					</div>
				</div>
				<div class="col-md-6">
					<div class="mb-3">
						<label for="voucherNo" class="form-label ">Voucher No</label>
						<input type="text" class="form-control" value="<?= $counter; ?>" readonly id="voucherNo" name="voucherNo" placeholder="Enter voucher number"
							data-parsley-required="true" data-parsley-errors-container=".err_voucherno" required="">
						<span class="text-danger err_voucherno"></span>
					</div>
					<div class="mb-3">
						<label for="date" class="form-label ">Date</label>
						<div class="input-group">
							<span class="input-group-text"><i class="bi bi-calendar"></i></span>
							<input type="text" class="form-control border-start-0 flatpiker" id="date" name="date" placeholder="Pick a date"
								data-parsley-required="true" data-parsley-errors-container=".err_date" required="">
						</div>
						<span class="text-danger err_date"></span>
					</div>
				</div>
			</div>
			<!-- Bank Details Table -->
			<div class="mb-4 mb-md-5">
				<div class="table-responsive rounded overflow-hidden">
					<table class="table mb-0" id="bankDetailsTable">
						<thead class="table-light">
							<tr>
								<th>Account No.</th>
								<th style="width: 35%;">Bank</th>
								<th style="width: 24%;">Due Date</th>
								<th style="width: 5%;">Action</th>
							</tr>
						</thead>
						<tbody id="bankDetailsBody">
							<tr data-index="0" class="align-middle">
								<td class="p-2">
									<select style="width: 100%;" name="akun_bank[]" data-parsley-required="true" data-parsley-errors-container=".err_akun1" required=""
										class="form-control select_account" style="width:100%">
										<option value="">pilih</option>
									</select>
									<span class="text-danger err_akun1"></span>
								</td>
								<td class="p-2">
									<input type="text" class="form-control" placeholder="Bank name" name="bank[]" data-parsley-required="true" data-parsley-errors-container=".err_bankname0" required="">
									<span class="text-danger err_bankname0"></span>
								</td>
								<td class="p-2">
									<input type="text" class="form-control flatpiker" placeholder="Pick a date" name="dueDate[]" data-parsley-required="true" data-parsley-errors-container=".err_datebank0" required="">
									<span class="text-danger err_datebank0"></span>
								</td>
								<td class="p-2 text-center"><i class="bi bi-trash text-danger fs-1 btnDeleteBank"></i></td>
							</tr>
						</tbody>
					</table>
					<div class="mt-2 d-flex justify-content-end mb-4">
						<button type="button" id="addBankBtn" class="btn btn-sm btn-success"> <i class="bi bi-plus-circle"></i> Add Bank Account</button>
					</div>
				</div>
			</div>

			<!-- Line Items Table -->
			<div class="mb-4 mb-md-5">
				<div class="table-responsive  rounded overflow-hidden">
					<table class="table mb-0" id="lineItemsTable">
						<thead class="table-light">
							<tr>
								<th>No. Account</th>
								<th style="width: 25%;">Description</th>
								<th style="width: 17%;">Debit</th>
								<th style="width: 17%;">Credit</th>
								<th style="width: 5%;">Action</th>
							</tr>
						</thead>
						<tbody id="lineItemsBody">
							<tr data-index="0" class="align-middle">
								<td class="p-2">
									<select name="akun_debitcredit[]" data-parsley-required="true" data-parsley-errors-container=".err_akunline0" required=""
										class="form-control select_account" style="width:100%">
										<option value="">pilih</option>
									</select>
									<span class="text-danger err_akunline0"></span>
								</td>
								<td class="p-2">
									<input type="text" class="form-control" placeholder="Description" name="description[]"
										data-parsley-required="true" data-parsley-errors-container=".err_des0" required="">
									<span class="text-danger err_des0"></span>
								</td>
								<td class="p-2">
									<input type="text" onkeyup="debitcredit(this)" class="form-control currency debit-input" id="debit0" placeholder="0" name="debit[]"
										data-parsley-errors-container=".err_debit0" required>
									<span class="text-danger err_debit0"></span>
								</td>
								<td class="p-2">
									<input type="text" onkeyup="debitcredit(this)" class="form-control currency credit-input" id="credit0" placeholder="0" name="credit[]"
										data-parsley-errors-container=".err_kredit0" required>
									<span class="text-danger err_kredit0"></span>
								</td>
								<td class="p-2 text-center"><i class="bi bi-trash text-danger fs-1 btnDeleteLineItem"></i></td>
							</tr>
						</tbody>
						<tfoot>
							<tr class="table-light">
								<td colspan="12" class="p-2 fw-bold">
									<div class="row">
										<div class="col-lg-4 col-md-4 col-12">
											<label class="form-label" for="totalDebit">Debit Amount</label>
											<input type="text" readonly id="totalDebit" name="totalDebit" class="form-control" placeholder="0">
										</div>
										<div class="col-lg-4 col-md-4 col-12">
											<label class="form-label" for="totalCredit">Credit Amount</label>
											<input type="text" readonly id="totalCredit" name="totalCredit" class="form-control" placeholder="0">
										</div>
										<div class="col-lg-4 col-md-4 col-12">
											<label class="form-label" for="totalDifference">Difference</label>
											<input type="text" readonly id="totalDifference" name="totalDifference" class="form-control" placeholder="0">
										</div>
									</div>
								</td>
							</tr>
						</tfoot>
					</table>
					<div class="mt-2 d-flex justify-content-end mb-4">
						<button type="button" id="addLineItemBtn" class="btn btn-sm btn-success"> <i class="bi bi-plus-circle"></i> Add Line Item</button>
					</div>
				</div>
			</div>
			<!-- Action Buttons -->
			<div class="d-flex justify-content-end gap-2 pt-3">
				<button type="button" id="btnsubmit" disabled class="btn btn-sm btn-danger"><i class="bi bi-send"></i> Save</button>
				<button type="reset" id="resetBtn" class="btn btn-sm btn-outline-danger"><i class="bi bi-eraser-fill"></i> Reset</button>
			</div>
		</form>
	</div>
</div>

<script>
	$(document).ready(function() {
		$(".flatpiker").flatpickr({
			dateFormat: "Y-m-d"
		});
		$('.currency').mask("#.##0", {
			reverse: true
		});

		select_account();
		$('#addBankBtn').on('click', function() {
			var id = Date.now();
			var newRow = `
                <tr data-index="${$('#bankDetailsTable tbody tr').length}" class="align-middle">
                    <td class="p-2">
						<select  name="akun_bank[]" data-parsley-required="true" data-parsley-errors-container=".err_akun${id}" required=""
								class="form-control select_account" style="width:100%">
							<option value="">pilih</option>
						</select>
						<span class="text-danger err_akun${id}"></span>
					</td>
                   <td class="p-2">
						<input type="text" class="form-control" placeholder="Bank name" name="bank[]" data-parsley-required="true" data-parsley-errors-container=".err_bankname${id}" required="">
						<span class="text-danger err_bankname${id}"></span>
					</td>
					<td class="p-2">
						<input type="text" class="form-control flatpiker" placeholder="Pick a date" name="dueDate[]" data-parsley-required="true" data-parsley-errors-container=".err_datebank${id}" required="">
						<span class="text-danger err_datebank${id}"></span>
					</td>
                    <td class="p-2  text-center"><i class="bi bi-trash text-danger fs-1 btnDeleteBank "></i></td>
                </tr>
            `;
			$('#bankDetailsBody').append(newRow);
			select_account();
			$(".flatpiker").flatpickr({
				dateFormat: "Y-m-d"
			});

		});

		// Add new line item row
		$('#addLineItemBtn').on('click', function() {
			var ll = Date.now();
			var newRow = `
				<tr data-index="${$('#lineItemsTable tbody tr').length}" class="align-middle">
					<td class="p-2">
						<select name="akun_debitcredit[]" data-parsley-required="true" data-parsley-errors-container=".err_akunline${ll}" required=""
							class="form-control select_account" style="width:100%">
							<option value="">pilih</option>
						</select>
						<span class="text-danger err_akunline${ll}"></span>
					</td>
					<td class="p-2">
						<input type="text" class="form-control" placeholder="Description" name="description[]"
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
			select_account();
			updateTotal();
			$('.currency').mask("#.##0", {
				reverse: true
			});
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
				$("#btnsubmit").removeClass('btn-primary').addClass('btn-danger');
				$("#btnsubmit").prop('disabled', true);
			} else {
				if (difference === 0) {
					$("#totalDifference").removeClass('is-invalid').addClass('is-valid');
					$("#btnsubmit").removeClass('btn-danger').addClass('btn-primary');
					$("#btnsubmit").prop('disabled', false);
				} else {
					$("#totalDifference").removeClass('is-valid').addClass('is-invalid');
					$("#btnsubmit").removeClass('btn-primary').addClass('btn-danger');
					$("#btnsubmit").prop('disabled', true);
				}
			}
		}
		// Delete bank row (with restriction for single row)
		$('#bankDetailsBody').on('click', '.btnDeleteBank', function() {
			// Check if there is only one row left
			if ($('#bankDetailsTable tbody tr').length > 1) {
				$(this).closest('tr').remove();
			} else {
				swet_gagal("You cannot delete the last row.");
			}
		});

		// Delete line item row (with restriction for single row)
		$('#lineItemsBody').on('click', '.btnDeleteLineItem', function() {
			if ($('#lineItemsTable tbody tr').length > 1) {
				$(this).closest('tr').remove();
				updateTotal();
			} else {
				swet_gagal("You cannot delete the last row.");
			}
		});

	});

	function select_account() {
		var code_company = $('#perusahaan').val();
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


	$('#btnsubmit').click(function(e) {
		e.preventDefault();

		// Ambil data dari form
		let formData = {
			proveniance: $("input[name='proveniance']:checked").val(), // Mengambil value dari radio button Proveniance
			flow: $("input[name='flow']:checked").val(), // Mengambil value dari radio button Flow
			voucherNo: $('#voucherNo').val(),
			date: $('#date').val(),
			lineItems: [], // Array untuk menyimpan data line items
			bankDetails: [] // Array untuk menyimpan data bank details
		};

		// Mengambil data dari table line items
		$('#lineItemsBody tr').each(function() {
			var lineItem = {
				accountNo: $(this).find('select[name="akun_debitcredit[]"]').val(),
				description: $(this).find('input[name="description[]"]').val(),
				debit: $(this).find('input[name="debit[]"]').val().replace(/\./g, ''), // Menghapus titik jika ada
				credit: $(this).find('input[name="credit[]"]').val().replace(/\./g, '') // Menghapus titik jika ada
			};
			formData.lineItems.push(lineItem);
		});

		// Mengambil data dari table bank details
		$('#bankDetailsBody tr').each(function() {
			var bankDetail = {
				accountNo: $(this).find('select[name="akun_bank[]"]').val(),
				bankName: $(this).find('input[name="bank[]"]').val(),
				dueDate: $(this).find('input[name="dueDate[]"]').val()
			};
			formData.bankDetails.push(bankDetail);
		});

		// Validasi form jika perlu
		let form = $('#voucherForm');
		form.parsley().validate();

		// Jika form valid, lanjutkan ke proses AJAX
		if (form.parsley().isValid()) {
			$.ajax({
				url: "<?= base_url('C_petty_cash/simpandata') ?>", // Ganti dengan URL controller yang sesuai
				type: 'POST',
				data: JSON.stringify(formData), // Mengirim data dalam format JSON
				contentType: 'application/json', // Menentukan bahwa data yang dikirim adalah JSON
				dataType: 'JSON',
				// beforeSend: function() {
				// 	showLoader(); // Jika Anda ingin menampilkan loader sebelum request AJAX
				// },
				success: function(data) {
					if (data.hasil == 'true') {
						swet_sukses(data.pesan); // Menampilkan pesan sukses
						loadform('<?= $load_grid ?>'); // Reload grid atau lakukan tindakan sesuai kebutuhan
					} else {
						swet_gagal(data.pesan); // Menampilkan pesan gagal
						hideLoader(); // Menyembunyikan loader
					}
				},
				error: function(xhr) {
					if (xhr.status === 422) {
						let errors = xhr.responseJSON.errors;
						$.each(errors, function(key, value) {
							$(`.err_${key}`).html(value[0]); // Menampilkan error di elemen terkait
						});
					} else {
						swet_gagal("Terjadi kesalahan server (" + xhr.status + ")");
					}
				},
			});
		}
	});

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
</script>
