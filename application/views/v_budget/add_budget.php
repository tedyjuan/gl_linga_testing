<style>
	.form-check-input {
		transform: scale(1.3);
	}
</style>
<form id="forms_add" method="post" enctype="multipart/form-data">
	<div class="card">
		<div class="card-header">
			<div class="row align-items-center mb-2">
				<div class="col-md-12 d-flex justify-content-between">
					<h2 class="mb-0"><?= $judul ?></h2>
					<div class="div ">
						<button type="button" class="btn btn-sm btn-primary" onclick="loadform('<?= $load_grid ?>')"><i
								class="bi bi-arrow-left-circle"></i> Back</button>
						<button type="button" class="btn btn-sm btn-outline-primary" onclick="loadform('<?= $load_back ?>')">
							<i class="bi bi-arrow-clockwise"></i> Refresh</button>
					</div>
				</div>
			</div>
		</div>
		<div class="card-body">
			<div class="row">
				<div class="col-6">
					<div class="mb-3">
						<label class="form-label" for="perusahaan">Perusahaan </label>
						<select id="perusahaan" name="perusahaan" class="form-control-hover-light form-control select2"
							data-parsley-required="true" data-parsley-errors-container=".err_company" required="">
							<option value="">Pilih</option>
							<?php foreach ($perusahaanList as $perusahaan) : ?>
								<option value="<?= $perusahaan->code_company ?>"><?= $perusahaan->code_company ?> -
									<?= $perusahaan->name ?>
								</option>
							<?php endforeach; ?>
						</select>
						<span class="text-danger err_company"></span>
					</div>
				</div>
				<div class="col-6">
					<div class="mb-3">
						<label class="form-label" for="department">Departemen </label>
						<select id="department" name="department" class="form-control-hover-light form-control select2"
							data-parsley-required="true" data-parsley-errors-container=".err_dept" required="">
							<option value="">Pilih Perusahaan Dahulu</option>
						</select>
						<span class="text-danger err_dept"></span>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-lg-6 col-sm-12  col-6">
					<div class="mb-3">
						<label class="form-label" for="saldo_awal">Saldo Awal</label>
						<input type="text" id="saldo_awal" name="saldo_awal" onkeyup="hitung_footer()"
							class="form-control-hover-light form-control curency" data-parsley-required="true"
							data-parsley-errors-container=".err_saldo_awal" required="" placeholder="input saldo awal">
						<span class="text-danger err_saldo_awal"></span>
					</div>
				</div>
				<div class="col-lg-3 col-sm-12 col-6">
					<div class="mb-3">
						<label class="form-label" for="perpanjang_angaran">Perpanjangan Anggaran </label>
						<input type="text" id="perpanjang_angaran" name="perpanjang_angaran"
							class="form-control-hover-light form-control curency" placeholder="input angaran"
							data-parsley-required="true" data-parsley-errors-container=".err_sing_cc" required="">
						<span class="text-danger err_sing_cc"></span>
					</div>
				</div>
				<div class="col-lg-3 col-12">
					<div class="mb-3">
						<label class="form-label" for="jumlah_project">Jumlah Project </label>
						<input type="number" id="jumlah_project" name="jumlah_project" class="form-control-hover-light bg-soft-dark form-control duadigit"
							data-parsley-required="true" data-parsley-errors-container=".err_jmlhprjek" required="" disabled
							placeholder="jumlah project">
						<span class="text-danger err_jmlhprjek"></span>
					</div>
				</div>
			</div>
			<div class="col-md-12 d-flex justify-content-end">
				<div></div>
				<div>
					<button type="button" id="btnsubmit" class="btn btn-sm btn-danger" disabled><i class="bi bi-send"></i> Save</button>
					<button type="reset" class="btn btn-sm btn-outline-danger"><i class="bi bi-eraser-fill"></i>
						Reset</button>
				</div>
			</div>
		</div>
	</div>
	<div id="projectFormsContainer"></div>

	<div style="margin-bottom: 2%;"></div>
	<div class="foot" id="foter_new">
		<div class="card">
			<div class="card-header">
				<div class="row">
					<div class="col-lg-3 col-md-6 col-12">
						<label class="form-label">Saldo Awal </label>
						<input type="text" readonly id="f_saldo" name="f_saldo" class="form-control" placeholder="0">
					</div>
					<div class="col-lg-3 col-md-6 col-12">
						<label class="form-label" for="f_opex">Total Biaya</label>
						<input type="text" readonly id="f_opex" name="f_opex" class="form-control" placeholder="0">
					</div>
					<div class="col-lg-3 col-md-6 col-12">
						<label class="form-label" for="f_capex">Total Produktivitas</label>
						<input type="text" readonly id="f_capex" name="f_capex" class="form-control" placeholder="0">
					</div>
					<div class="col-lg-3 col-md-6 col-12">
						<label class="form-label" for="f_selisih">Selisih</label>
						<input type="text" readonly id="f_selisih" name="f_selisih" class="form-control" placeholder="0">
					</div>
				</div>
			</div>
		</div>
	</div>
</form>

<script>
	$(document).ready(function() {
		$('.curency').mask("#.##0", {
			reverse: true
		});
		$('.duadigit').mask("00");
		// Ketika perusahaan dipilih
		$('#perusahaan').on('change', function() {
			var companyCode = $(this).val();
			$("#jumlah_project").val('')
			var container = $('#projectFormsContainer');
			container.empty();
			if (companyCode == '') {
				$("#jumlah_project").prop("disabled", true);
				$("#jumlah_project").addClass("bg-soft-dark");
			} else {
				$("#jumlah_project").prop("disabled", false);
				$("#jumlah_project").removeClass("bg-soft-dark");

			}
			// Mengosongkan dropdown dan reset nilai default sebelum AJAX
			$('#department').empty().append('<option value="">Pilih</option>');
			if (companyCode) {
				// Memuat Department
				$.ajax({
					url: '<?= base_url('C_global/getDepartmentByCompany/'); ?>' + companyCode,
					method: 'GET',
					dataType: 'JSON',
					beforeSend: function() {
						showLoader();
					},
					success: function(data) {
						hideLoader();
						data.forEach(function(department) {
							$('#department').append('<option value="' + department
								.code_department + '" data-alias="' + department
								.alias + '">' + department.alias + ' : ' + department.name + '</option>');
						});
					}
				});
			}
		});
	});
</script>
<script>
	$(document).ready(function() {

		// Ketika jumlah project diinputkan
		$('#jumlah_project').on('input', function() {
			var jumlahProject = $(this).val();
			if (jumlahProject <= 10) {
				var container = $('#projectFormsContainer');

				// Kosongkan container sebelumnya
				container.empty();

				// Tambahkan form baru sebanyak jumlah yang dimasukkan
				for (var i = 1; i <= jumlahProject; i++) {
					var formProject = `
                <div class="card mt-4 border border-secondary border-1" id="project_${i}">
                    <div class="card-body">
                        <div class="row">
                            <h2>Project ${i}</h2>
                        </div>
                        <div class="row">
                            <div class="col-6">
                                <div class="mb-3">
                                    <label class="form-label" for="project_name_${i}">Nama Project </label>
                                    <input type="text" id="project_name_${i}" name="project_name_${i}[]"
                                        class="form-control form-control-hover-light " placeholder="Nama Project "
										data-parsley-required="true" data-parsley-errors-container=".err_project_name${i}" required="">
                                    <span class="text-danger err_project_name${i}"></span>
                                </div>
                            </div>
                            <div class="col-6">
								<div class="mb-3">
									<label class="form-label" for="project_file_${i}">Unggah Proposal</label>
									<input type="file" id="project_file_${i}" name="project_file[${i}][]"
										class="form-control form-control-hover-light" placeholder="Unggah Proposal"
										data-parsley-required="true" data-parsley-errors-container=".err_namafile${i}" required="">
									<span class="text-danger err_namafile${i}"></span>
								</div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-6">
                                <div class="mb-3">
                                    <label class="form-label" for="usulan_anggaran_${i}">Usulan Anggaran </label>
                                    <input type="text" id="usulan_anggaran_${i}" name="usulan_anggaran_${i}[]"
                                        class="form-control form-control-hover-light curency" placeholder="Usulan Anggaran "
										data-parsley-required="true" data-parsley-errors-container=".err_usulan_angaran${i}" required="">
                                    <span class="text-danger err_usulan_angaran${i}"></span>
                                </div>
                            </div>
							<div class="col-6">
								<div class="">
									<label class="form-label" for="perpanjang_angaran">Tujuan Proyek </label>
								</div>
								<div class="form-check form-check-inline">
									<input type="checkbox" id="p_angaran_${i}" name="tujuan${i}[]" class="form-check-input indeterminate-checkbox" data-parsley-checkbox-required="true" onchange="form_angaran(this)">
									<label class="form-check-label" for="p_angaran_${i}">Mengurangi Biaya (OPEX)</label>
								</div>
								<div class="form-check form-check-inline">
									<input type="checkbox" id="meningkatkan_produktifitas${i}" name="tujuan${i}[]" class="form-check-input indeterminate-checkbox" data-parsley-checkbox-required="true" onchange="form_prod_aktif(this)">
									<label class="form-check-label" for="meningkatkan_produktifitas${i}">Meningkatkan Produktivitas (CAPEX)</label>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-12">
								<label class="form-label" for="desc_project">Deskripsi Project</label>
									<textarea name="project_desc_${i}" id="project_desc_${i}"
									class="form-control form-control-hover-light " placeholder="Deskripsi Project ${i}"
									data-parsley-required="true" data-parsley-errors-container=".err_des${i}" required=""></textarea>
								  <span class="text-danger err_des${i}"></span>
							</div>
						</div>
						<!-- Tabel Mengurangi Biaya -->
						<div class="mt-5" id="tblMengurangiBiayaContainer_${i}" style="display: none;">
							<h2>Mengurangi Biaya</h2>
							<input class="counta_${i}" type="hidden" value="0">
							<table class="table table-bordered table-thead-bordered table-sm" id="tblMengurangiBiaya_${i}" style="width: 100%;">
								<thead class="thead-light">
									<tr>
										<th style="width: 35%;">Nama Account</th>
										<th>Keterangan</th>
										<th style="width: 20%;">Jumlah</th>
										<th style="width: 5%;">aksi</th>
									</tr>
								</thead>
								<tbody></tbody>
							</table>
							<div class="mt-2 d-flex justify-content-end">
								<button type="button" id="counta_${i}" class="btn btn-sm btn-success" onclick="addrow_a(this)"> <i class="bi bi-plus-circle"></i> Tambah item</button>
							</div>
						</div>
						<!-- Tabel Meningkatkan Produktivitas -->
						<div class="mt-5" id="tblMeningkatkanProduktivitasContainer_${i}" style="display: none;">
							<h2>Meningkatkan Produktivitas</h2>
							<input class="countb_${i}" type="hidden" value="0">
							<table class="table table-bordered table-thead-bordered table-sm" id="tblMeningkatkanProduktivitas_${i}" style="width: 100%;">
								<thead class="thead-light">
									<tr>
										<th>Keterangan</th>
										<th style="width: 20%;">Jumlah</th>
										<th style="width: 5%;">aksi</th>
									</tr>
								</thead>
								<tbody></tbody>
							</table>
							<div class="mt-2 d-flex justify-content-end">
								<button type="button" id="countb_${i}" class="btn btn-sm btn-success" onclick="addrow_b(this)"> <i class="bi bi-plus-circle"></i> Tambah item</button>
							</div>
						</div>
                    </div>
                </div>
            `;
					container.append(formProject);
				}
				$('.curency').mask("#.##0", {
					reverse: true
				});
			} else {
				var container = $('#projectFormsContainer');
				container.empty();
				swet_gagal("Maxsimal 10 Project");
				$("#jumlah_project").val('')

			}

		});
		select_account();
	});

	function form_angaran(e) {
		var id = e.id; // Mengambil ID dari elemen
		var isChecked = e.checked;
		var number = id.match(/\d+$/);
		var newId = "counta_" + number
		if (isChecked) {
			$("#tblMengurangiBiayaContainer_" + number).show();
			e.id = newId;
			addrow_a(e);
			$(".counta_" + number).val(1);
		} else {
			$("#tblMengurangiBiayaContainer_" + number).hide();
			$("#tblMengurangiBiaya_" + number).find(".counta_" + number).remove();
			$(".counta_" + number).val(0);
		}
	}

	function form_prod_aktif(e) {
		var id = e.id;
		var isChecked = e.checked;
		var number = id.match(/\d+$/);
		var newId_b = "countb_" + number
		if (isChecked) {
			$("#tblMeningkatkanProduktivitasContainer_" + number).show();
			e.id = newId_b
			addrow_b(e);
			$(".countb_" + number).val(1);
		} else {
			$("#tblMeningkatkanProduktivitasContainer_" + number).hide();
			$("#tblMeningkatkanProduktivitas_" + number).find(".countb_" + number).remove();
			$(".countb_" + number).val(0);
		}

	}

	function addrow_a(e) {
		var id = e.id;
		var number = parseInt(id.match(/\d+$/));
		var getcounter = $("." + id).val();
		var counter = parseInt(getcounter) + 1;
		$("." + id).val(counter);
		var newRow = `
								<tr class="${id}" id="hapus${id}${counter}">
									<td>
										<select id="account_${id}${counter}" name="account_${id}[]"
											data-parsley-required="true" data-parsley-errors-container=".err_akun${id}${counter}" required=""
											 class="form-control select_account" style="width:100%">
											<option value="">pilih</option>
										</select>
                                    	<span class="text-danger err_akun${id}${counter}"></span>
									</td>
									<td>
										<input id="keterangan_${id}${counter}" name="keterangan_${id}[]" type="text" 
										data-parsley-required="true" data-parsley-errors-container=".err_ket${id}${counter}" required=""
										class="form-control" placeholder="keterangan">
                                    	<span class="text-danger err_ket${id}${counter}"></span>
									</td>
									<td>
										<input id="jumlah_a_${id}${counter}" onkeyup="hitung_footer()" name="jumlah_a_${id}[]"  type="text" 
										data-parsley-required="true" data-parsley-errors-container=".err_jum${id}${counter}" required=""
										class="form-control curency opex" placeholder="Jumlah">
                                    	<span class="text-danger err_jum${id}${counter}"></span>
									</td>
									<td class="text-center"><i id="row_${id}${counter}" data-hapusrow="${id}" class="bi bi-trash text-danger fs-1" onclick="hapus_a(this)"></i> </td>
								</tr>
							`;
		// Append new row to the table
		$("#tblMengurangiBiaya_" + number).find("tbody").append(newRow);
		$('.curency').mask("#.##0", {
			reverse: true
		});
		select_account();
	}

	function addrow_b(e) {
		var id = e.id;
		var number = parseInt(id.match(/\d+$/));
		var getcounter = $("." + id).val();
		var counter = parseInt(getcounter) + 1;
		$("." + id).val(counter);
		var newRow = `
					<tr class="${id}" id="hapus${id}${counter}">
						<td>
							<input id="keterangan_b_${id}${counter}" name="keterangan_b_${id}[]" type="text"
							data-parsley-required="true" data-parsley-errors-container=".err_kete${id}${counter}" required=""
							 class="form-control" placeholder="keterangan">
                            <span class="text-danger err_kete${id}${counter}"></span>
						</td>
						<td>
							<input id="jumlah_b_${id}${counter}" name="jumlah_b_${id}[]" type="text" onkeyup="hitung_footer()"
							class="form-control curency capex" placeholder="jumlah"
							data-parsley-required="true" data-parsley-errors-container=".err_jmlh${id}${counter}" required="">
							<span class="text-danger err_jmlh${id}${counter}"></span>
						</td>
						<td class="text-center"><i id="row_${id}${counter}" data-hapusrow="${id}" class="bi bi-trash text-danger fs-1" onclick="hapus_b(this)"></i> </td>
					</tr>
							`;
		// Append new row to the table
		$("#tblMeningkatkanProduktivitas_" + number).find("tbody").append(newRow);
		$('.curency').mask("#.##0", {
			reverse: true
		});
	}

	function hapus_a(e) {
		var id = e.id;
		var rowIdentifier = $(e).data('hapusrow');
		var param_no = parseInt(rowIdentifier.match(/\d+$/)[0]);
		var dataCount = $(`input[name="jumlah_counta_${param_no}[]"]`).length;
		if (dataCount == 1) {
			swet_gagal("Tidak bisa menghapus data terakhir");
			return; // Menghentikan penghapusan
		}
		var number = parseInt(id.match(/\d+$/)[0]);
		$("#hapuscounta_" + number).remove();
	}

	function hapus_b(e) {
		var id = e.id;
		var rowIdentifier = $(e).data('hapusrow');
		var param_no = parseInt(rowIdentifier.match(/\d+$/)[0]);
		var dataCount = $(`input[name="jumlah_b_countb_${param_no}[]"]`).length;
		if (dataCount == 1) {
			swet_gagal("Tidak bisa menghapus data terakhir");
			return; // Menghentikan penghapusan
		}

		var number = parseInt(id.match(/\d+$/));
		$("#hapuscountb_" + number).remove();
	}

	function select_account() {
		var code_company = $('#perusahaan').val();
		$(".select_account").select2({
			placeholder: 'Cari kode atau nama akun',
			allowClear: true,
			ajax: {
				url: "<?= base_url('C_budget/Coa_expense') ?>",
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
	// Menambahkan validasi custom menggunakan Parsley.js (definisikan sekali saja)
	$('#btnsubmit').click(function(e) {
		e.preventDefault();

		let isValid = true; // Flag untuk mengecek apakah semua validasi lulus

		// Periksa setiap grup checkbox untuk validasi
		$('input[name^="tujuan"]').each(function() {
			let groupName = $(this).attr('name');
			// Cek apakah ada checkbox yang dipilih dalam grup ini
			if ($(`input[name="${groupName}"]:checked`).length === 0) {
				// Jika tidak ada checkbox yang dipilih, beri tanda error
				$(`input[name="${groupName}"]`).closest('.col-6').find('.error-message').remove();
				$(`input[name="${groupName}"]`).closest('.col-6').append('<div class="error-message text-danger">Harap pilih setidaknya satu opsi.</div>');
				isValid = false; // Set flag validasi menjadi false
			} else {
				// Jika ada checkbox yang dipilih, hapus pesan error
				$(`input[name="${groupName}"]`).closest('.col-6').find('.error-message').remove();
			}
		});

		// Menambahkan validasi custom menggunakan Parsley.js
		let form = $('#forms_add');
		form.parsley().validate();

		// Jika form valid, lanjutkan ke proses AJAX
		if (form.parsley().isValid() && isValid) {
			let formData = new FormData();

			// Mengumpulkan data dari setiap elemen form secara manual
			formData.append('perusahaan', $("select[name='perusahaan']").val());
			formData.append('department', $("select[name='department']").val());
			formData.append('saldo_awal', $("input[name='saldo_awal']").val());
			formData.append('perpanjang_angaran', $("input[name='perpanjang_angaran']").val());
			formData.append('jumlah_project', $("input[name='jumlah_project']").val());

			// Loop untuk mengumpulkan project data (nama proyek, anggaran, tujuan, dll.)
			for (let i = 1; i <= $("input[name='jumlah_project']").val(); i++) {
				formData.append('projects[' + i + '][project_name]', $(`input[name='project_name_${i}[]']`).val());
				formData.append('projects[' + i + '][usulan_anggaran]', $(`input[name='usulan_anggaran_${i}[]']`).val());
				formData.append('projects[' + i + '][project_desc]', $(`textarea[name='project_desc_${i}']`).val());

				// Menambahkan file
				let fileInput = $(`input[name='project_file[${i}][]']`)[0];
				if (fileInput.files.length > 0) {
					formData.append('projects[' + i + '][project_file]', fileInput.files[0]);
				}

				// Mengumpulkan data tujuan proyek (counta dan countb)
				$(`select[name='account_counta_${i}[]']`).each(function(index) {
					let counta = {
						account: $(this).val(),
						keterangan: $(`input[name='keterangan_counta_${i}[]']`).eq(index).val(),
						jumlah: $(`input[name='jumlah_a_counta_${i}[]']`).eq(index).val()
					};
					formData.append('projects[' + i + '][counta][]', JSON.stringify(counta));
				});

				$(`input[name='keterangan_b_countb_${i}[]']`).each(function(index) {
					let countb = {
						keterangan: $(this).val(),
						jumlah: $(`input[name='jumlah_b_countb_${i}[]']`).eq(index).val(),
					};
					formData.append('projects[' + i + '][countb][]', JSON.stringify(countb));
				});
			}

			// Kirim data dalam format FormData menggunakan AJAX
			$.ajax({
				url: "<?= base_url('C_budget/simpandata') ?>",
				type: 'POST',
				data: formData,
				processData: false, // Jangan memproses data (karena file)
				contentType: false, // Jangan set content-type (FormData otomatis menentukannya)
				dataType: 'JSON',
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
	// Fungsi untuk memformat angka menjadi currency Indonesia
	function formatCurrency(amount) {
		var formattedAmount = new Intl.NumberFormat('id-ID', {
			style: 'currency',
			currency: 'IDR'
		}).format(amount);
		return formattedAmount.replace(',00', '');
	}
	// Fungsi untuk membersihkan dan mengubah format ke integer
	function parseCurrency(value) {
		// Pastikan value adalah string, lalu menghapus "Rp" dan semua tanda titik (.) dari nilai
		if (typeof value === 'string') {
			return parseInt(value.replace(/[^\d]/g, ''), 10) || 0;
		}
		return 0;
	}


	function hitung_footer() {
		var total_opex = 0;
		var total_capex = 0;
		var value_i = 0;
		var value_r = 0;
		var value_s = 0;
		$('.opex').each(function() {
			var value_r = $(this).val().trim();
			if (value_r === '' || value_r === null || value_r === undefined) {
				value_r = 0;
			}
			total_opex += parseCurrency(value_r);
			$("#f_opex").val(formatCurrency(total_opex));
		});

		$('.capex').each(function() {
			var value_i = $(this).val().trim();
			if (value_i === '' || value_i === null || value_i === undefined) {
				value_i = 0;
			}
			total_capex += parseCurrency(value_i);
			$("#f_capex").val(formatCurrency(total_capex));
		});

		var saldoawal = $("#saldo_awal").val().replace(/\./g, ''); // Menghapus tanda titik untuk saldo awal
		value_s = saldoawal === '' ? 0 : parseCurrency(saldoawal); // Menggunakan parseCurrency untuk membersihkan dan mengkonversi
		$("#f_saldo").val(formatCurrency(value_s));

		var jumlah = value_s - (total_opex + total_capex); // Menghitung selisih
		$("#f_selisih").val(formatCurrency(jumlah));
		if (value_s == 0 && total_opex == 0 && total_capex == 0) {
			$("#f_selisih").removeClass('is-invalid');
			$("#f_selisih").removeClass('is-valid');
			$("#btnsubmit").removeClass('btn-primary').addClass('btn-danger');
			$("#btnsubmit").prop('disabled', true);
		} else {
			if (jumlah === 0) {
				$("#f_selisih").removeClass('is-invalid').addClass('is-valid');
				$("#btnsubmit").removeClass('btn-danger').addClass('btn-primary');
				$("#btnsubmit").prop('disabled', false);
			} else {
				$("#f_selisih").removeClass('is-valid').addClass('is-invalid');
				$("#btnsubmit").removeClass('btn-primary').addClass('btn-danger');
				$("#btnsubmit").prop('disabled', true);
			}
		}

	}
</script>
