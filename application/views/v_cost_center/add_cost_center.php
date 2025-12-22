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
						<label class="form-label" for="cost_center">Cost Center</label>
						<input type="text" id="cost_center" name="cost_center"
							class="form-control form-control" disabled placeholder="Auto-complate">
					</div>
				</div>
				<div class="col-6">
					<div class="mb-3">
						<label class="form-label" for="group_team">Group-Team CC</label>
						<input type="text" id="group_team" name="group_team"
							class="form-control form-control" disabled placeholder="Auto-complate">
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-6">
					<div class="mb-3">
						<label class="form-label" for="perusahaan">Perusahaan</label>
						<select id="perusahaan" name="perusahaan" class="form-control-hover-light form-control select2"
							data-parsley-required="true" data-parsley-errors-container=".err_perusahaan" required="">
							<option value="">Pilih</option>
							<?php foreach ($perusahaanList as $perusahaan) : ?>
								<option value="<?= $perusahaan->code_company ?>"><?= $perusahaan->code_company ?> -
									<?= $perusahaan->name ?>
								</option>
							<?php endforeach; ?>
						</select>
						<span class="text-danger err_perusahaan"></span>
					</div>
				</div>
				<div class="col-6">
					<div class="mb-3">
						<label class="form-label" for="depo">Depo</label>
						<select id="depo" name="depo" class="form-control-hover-light form-control select2"
							data-parsley-required="true" data-parsley-errors-container=".err_depo" required="">
							<option value="">Silahkan Pilih Perusahaan</option>
						</select>
						<span class="text-danger err_depo"></span>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-6">
					<div class="mb-3">
						<label class="form-label" for="Department">Department</label>
						<select id="Department" name="Department" class="form-control-hover-light form-control select2"
							data-parsley-required="true" data-parsley-errors-container=".err_department" required="">
							<option value="">Silahkan Pilih Perusahaan</option>
						</select>
						<span class="text-danger err_department"></span>
					</div>
				</div>
				<div class="col-6">
					<div class="mb-3">
						<label class="form-label" for="divisi">Divisi</label>
						<select id="divisi" name="divisi" class="form-control-hover-light form-control select2"
							data-parsley-required="true" data-parsley-errors-container=".err_divisi" required="">
							<option value="">Silahkan Pilih Perusahaan</option>
						</select>
						<span class="text-danger err_divisi"></span>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-6">
					<div class="mb-3">
						<label class="form-label" for="segment">Segment</label>
						<select id="segment" name="segment" class="form-control-hover-light form-control select2"
							data-parsley-required="true" data-parsley-errors-container=".err_segment" required="">
							<option value="">Silahkan Pilih Perusahaan</option>
						</select>
						<span class="text-danger err_segment"></span>
					</div>
				</div>
				<div class="col-6">
					<div class="mb-3">
						<label class="form-label" for="manager">Manager</label>
						<input type="text" id="manager" name="manager" placeholder="input manager" data-parsley-required="true"
							data-parsley-errors-container=".err_manager" required="" class="form-control-hover-light form-control">
						<span class="text-danger err_manager"></span>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-12">
					<div class="mb-3">
						<label class="form-label" for="description">Description</label>
						<textarea name="description" id="description" cols="3" rows="3" class="form-control-hover-light form-control" placeholder="input description"></textarea>
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
				url: "<?= base_url('C_cost_center/simpandata') ?>",
				type: 'POST',
				method: 'POST',
				dataType: 'JSON',
				data: form.serialize(),
				beforeSend: function() {
					// showLoader();
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
		$(".select2").select2();
		$('#manager').on('keyup', function() {
			var value = $(this).val();
			// Menghapus karakter selain angka, huruf, spasi, dan tanda minus
			var formattedValue = value.replace(/[^a-zA-Z0-9 -]/g, '');
			// Menyusun kembali input untuk huruf pertama kapital dan lainnya kecil
			formattedValue = formattedValue.replace(/\b\w/g, function(char) {
				return char.toUpperCase();
			}).replace(/\B\w/g, function(char) {
				return char.toLowerCase();
			});
			$('#manager').val(formattedValue);
		});
	})
	$(document).ready(function() {
		// Ketika perusahaan dipilih
		$('#perusahaan').on('change', function() {
			var companyCode = $(this).val();
			if (companyCode == '') {
				$('#group_team').val('');
				$('#cost_center').val('');
			}
			// Mengosongkan dropdown dan reset nilai default sebelum AJAX
			$('#depo').empty().append('<option value="">Pilih</option>');
			$('#Department').empty().append('<option value="">Pilih</option>');
			$('#divisi').empty().append('<option value="">Pilih</option>');
			$('#segment').empty().append('<option value="">Pilih</option>');
			if (companyCode) {
				$('#group_team').val('');
				$('#cost_center').val('');
				// Memuat Depo
				$.ajax({
					url: '<?= base_url('C_global/getDepoByCompany/'); ?>' + companyCode,
					method: 'GET',
					dataType: 'JSON',
					success: function(data) {
						data.forEach(function(depo) {
							$('#depo').append('<option value="' + depo.code_depo +
								'" data-alias="' + depo.alias +
								'" data-area="' + depo.code_area + '">' + depo.code_depo + '-' + depo.name + '</option>');
						});
					}
				});
				// Memuat Department
				$.ajax({
					url: '<?= base_url('C_global/getDepartmentByCompany/'); ?>' + companyCode,
					method: 'GET',
					dataType: 'JSON',
					success: function(data) {
						data.forEach(function(department) {
							$('#Department').append('<option value="' + department
								.code_department + '" data-alias="' + department
								.alias + '">' + department.alias + ' : ' + department.name + '</option>');
						});
					}
				});
				// Memuat Divisi
				$.ajax({
					url: '<?= base_url('C_global/getDivisiByCompany/'); ?>' + companyCode,
					method: 'GET',
					dataType: 'JSON',
					success: function(data) {
						data.forEach(function(divisi) {
							$('#divisi').append('<option value="' + divisi
								.code_divisi + '" data-alias="' + divisi.alias +
								'">' + divisi.alias + ' : ' + divisi.name + '</option>');
						});
					}
				});
				// Memuat Segment
				$.ajax({
					url: '<?= base_url('C_global/getSegmentByCompany/'); ?>' + companyCode,
					method: 'GET',
					dataType: 'JSON',
					success: function(data) {
						data.forEach(function(segment) {
							$('#segment').append('<option value="' + segment
								.code_segment + '"  data-alias="' + segment
								.alias + '">' + segment.alias + ' : ' + segment.name + '</option>');
						});
					}
				});
			}
		});
		// Event handler untuk menggabungkan nilai
		$('#depo, #Department, #divisi, #segment').on('change', function() {
			// Ambil data dari masing-masing elemen
			var selectedOption = $('#depo').find('option:selected');
			var dataArea = selectedOption.data('area') || '--'; 
			var code_dept = $('#Department').val() || '--'; 
			var code_divisi = $('#divisi').val() || '--';
			var code_segment = $('#segment').val() || '--'; 

			// Gabungkan nilai menjadi satu
			var concatenatedValue = dataArea + code_dept + code_divisi + code_segment;

			// Masukkan hasil gabungan ke dalam input dengan id 'cost_center'
			$('#cost_center').val(concatenatedValue);
		});

		$('#depo, #Department, #divisi, #segment').on('change', function() {
			var selectedOptionDepo = $('#depo').find('option:selected');
			var alias_depo = selectedOptionDepo.data('alias') || '--';
			var selectedOptionDept = $('#Department').find('option:selected');
			var alias_dept = selectedOptionDept.data('alias') || '--';
			var selectedOptionDivisi = $('#divisi').find('option:selected');
			var alias_divisi = selectedOptionDivisi.data('alias') || '--';
			var selectedOptionSegment = $('#segment').find('option:selected');
			var alias_segment = selectedOptionSegment.data('alias') || '--';
			var concatenatedValue = '';
			if (alias_depo) concatenatedValue += alias_depo + '/';
			if (alias_dept) concatenatedValue += alias_dept + '/';
			if (alias_divisi) concatenatedValue += alias_divisi + '/';
			if (alias_segment) concatenatedValue += alias_segment;
			concatenatedValue = concatenatedValue.endsWith('/') ? concatenatedValue.slice(0, -1) : concatenatedValue;
			$('#group_team').val(concatenatedValue);
		});
	});
</script>
