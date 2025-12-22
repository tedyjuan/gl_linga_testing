<!-- Card -->
<div class="card">
	<div class="card-header">
		<div class="row align-items-center mb-3">
			<div class="col-md-12 d-flex justify-content-between">
				<h2 class="mb-0"><?= $judul; ?></h2>
				<div class="d-flex gap-2">
					<div>
						<div class="dropdown">
							<button type="button" class="btn btn-primary btn-sm" id="aksi-dropdown-' . $row->batch_number . '" data-bs-toggle="dropdown" aria-expanded="false">
								Action <i class="bi-chevron-down ms-1"></i>
							</button>
							<div class="dropdown-menu dropdown-menu-sm dropdown-menu-end" aria-labelledby="aksi-dropdown-' . $row->batch_number . '">
								<button class="dropdown-item" data-bs-toggle="modal" data-bs-target="#modalposting">
									<i class="bi bi-shift"></i> Un-Posting Journal
								</button>
								<button class="dropdown-item" data-bs-toggle="modal" data-bs-target="#modalfilter">
									<i class="bi bi-filter-square"></i> Filter Journal
								</button>
								<div class="dropdown-divider"></div>
								<a class="dropdown-item text-primary" href="<?= base_url('Report/export_excel');?>" target="_blank">
									<i class="bi bi-cloud-download"></i> Download Journal
								</a>
							</div>
						</div>
					</div>
					<a href="javascript:void(0)" class="btn btn-sm btn-outline-primary"
						onclick="loadform('<?= $load_grid ?>')">
						<i class="bi bi-arrow-clockwise"></i> Refresh
					</a>
				</div>
			</div>
		</div>
	</div>
	<div class="card-body ">
		<table class="table table-sm table-striped table-hover table-bordered " id="mytable" style="width: 100%">
			<thead>
				<tr class="table-primary">
					<Th>Batch Date</Th>
					<Th>Journal Type</Th>
					<Th>Batch Number</Th>
					<Th>Voucher Number</Th>
					<th style="width: 5%">Action</th>
				</tr>
			</thead>
		</table>
	</div>
</div>
<!-- Modal Filter-->
<div class="modal fade" id="modalfilter" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="modalfilterLabel">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header bg-secondary">
				<h4 class="modal-title mb-3 text-white" id="modalfilterLabel">FILTER JOURNAL</h4>
			</div>
			<form id="form_filter">
				<div class="modal-body">
					<div class="mb-2">
						<select style="width:100%" id="post_branch" name="post_branch" class="form-control-hover-light form-control select2"
							data-parsley-required="true" data-parsley-errors-container=".err_post_branch" required="">
							<option value=""></option>
							<?php foreach ($depos as $row) : ?>
								<option value="<?= $row->code_depo ?>"><?= $row->code_depo ?> - <?= $row->name ?></option>
							<?php endforeach; ?>
						</select>
						<span class="text-danger err_post_branch"></span>
					</div>
					<div class="mb-2">
						<label class="form-label" for="batch_voucher">Batch / Voucher</label>
						<input type="text" id="batch_voucher" name="batch_voucher" class="form-control" placeholder="search batch or voucher">
					</div>
					<div class="mb-2">
						<label class="form-label" for="date_periode">Date Period</label>
						<input type="text" id="date_periode" name="date_periode" class="form-control date_periode" placeholder="search date periode">
					</div>
					<div class="mb-2">
						<label class="form-label" for="journal_type">Journal Type</label>
						<select id="journal_type" name="journal_type" style="width:100%" class="form-control-hover-light form-control select2"
							data-parsley-required="true" data-parsley-errors-container=".err_journal_type" required="">
							<option value=""></option>
							<?php foreach ($journal_sources as $row) : ?>
								<option value="<?= $row->code_journal_source ?>"><?= $row->code_journal_source ?> - <?= $row->description ?></option>
							<?php endforeach; ?>
						</select>
						<span class="text-danger err_journal_type"></span>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" id="btn_cancel" class="btn btn-sm btn-outline-danger" data-bs-dismiss="modal"><i class="bi bi-x"></i> Close</button>
					<button type="button" class="btn btn-sm btn-primary" onclick="filters()"><i class="bi bi-search"></i> Search</button>
				</div>
			</form>
		</div>
	</div>
</div>
<!-- Modal posting-->
<div class="modal fade" id="modalposting" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="modalpostingLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header bg-info">
				<h4 class="modal-title mb-3 text-white" id="modalpostingLabel">UN-POSTING JOURNAL</h4>
			</div>
			<form id="form_posting">
				<div class="modal-body">
					<div class="mb-2">
						<select style="width:100%" id="branch" name="branch" class="form-control-hover-light form-control select2"
							data-parsley-required="true" data-parsley-errors-container=".err_branch" required="">
							<option value=""></option>
							<?php foreach ($depos as $row) : ?>
								<option value="<?= $row->code_depo ?>"><?= $row->code_depo ?> - <?= $row->name ?></option>
							<?php endforeach; ?>
						</select>
						<span class="text-danger err_branch"></span>
					</div>
					<div class="mb-2">
						<label class="form-label" for="date_periode_journal">Date Period</label>
						<input type="text" id="date_periode_journal" name="date_periode_journal" class="form-control" placeholder="search date periode">
					</div>
					<div id="list_journal"></div>
				</div>
				<div class="modal-footer">
					<button type="button" id="btn_cancel" class="btn btn-sm btn-outline-danger" data-bs-dismiss="modal"><i class="bi bi-x"></i> Close</button>
					<button type="button" class="btn btn-sm btn-primary" onclick="unposting_journal()"><i class="bi bi-check2-circle"></i> Posting</button>
				</div>
			</form>
		</div>
	</div>
</div>
<script>
	function initTable() {

		// kalau sebelumnya sudah ada instance, hancurkan dulu
		if (window.mytableDT && $.fn.dataTable.isDataTable('#mytable')) {
			window.mytableDT.clear().destroy();
			window.mytableDT = null;
		}

		window.mytableDT = $('#mytable').DataTable({
			processing: true,
			serverSide: true,
			ajax: {
				url: "<?= base_url('C_journal_posted/griddata'); ?>",
				type: "POST",
				data: function(d) {
					d.batch_voucher = $('#batch_voucher').val();
					d.post_branch = $('#post_branch').val();
					d.date_periode = $('#date_periode').val();
					d.journal_type = $('#journal_type').val();
				}
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
		$(".select2").select2({
			placeholder: "Search Branch",
			allowClear: true,
			width: '100%'
		});
		// Dapatkan awal & akhir bulan ini
		let now = new Date();
		let lastDay = new Date(now.getFullYear(), now.getMonth() + 1, 0);
		$(".date_periode").flatpickr({
			mode: "range",
			maxDate: lastDay, // tanggal terakhir bulan ini
			dateFormat: "Y-m-d",

			// Pastikan range tetap dalam bulan yang sama
			onChange: function(selectedDates, dateStr, instance) {
				if (selectedDates.length === 2) {
					const start = selectedDates[0];
					const end = selectedDates[1];

					if (start.getMonth() !== end.getMonth()) {
						instance.setDate([start]);
						alert("Tanggal mulai dan akhir harus dalam bulan yang sama.");
					}
				}
			}
		});
		$('#date_periode_journal').datepicker({
			format: "yyyy-mm",
			startView: "months",
			minViewMode: "months",
			autoclose: true,
			orientation: "bottom auto"
		});
	});

	function unposting_journal() {
		let form = $('#form_posting');
		form.parsley().validate();
		if (form.parsley().isValid()) {
			$.ajax({
				url: "<?= base_url('C_journal_posted/unposting_journal') ?>",
				type: 'POST',
				method: 'POST',
				dataType: 'JSON',
				data: form.serialize(),
				beforeSend: function() {
					// showLoader();
				},
				success: function(data) {
					hideLoader();
					if (data.hasil == 'true') {
						swet_sukses(data.pesan);
						if (window.mytableDT) {
							window.mytableDT.ajax.reload(null, false);
						} else {
							initTable();
						}
						$('#modalposting').modal('hide');
						$('#form_posting')[0].reset();
						$('#branch').val('').trigger('change');
					} else {
						swet_gagal(data.pesan);
						var length = data.details.length;
						if (length > 0) {
							$('#list_journal').empty();
							$.each(data.details, function(index, item) {
								$('#list_journal').append(
									'<p>Batch Number ' + ': ' + item.batch_number + '</p>'
								);
							});
						} else {
							$('#modalposting').modal('hide');
							$('#form_posting')[0].reset();
							$('#branch').val('').trigger('change');
						}
					}

				},
			});
		}
	}

	function filters() {
		$('#btn_cancel').click();
		if (window.mytableDT) {
			window.mytableDT.ajax.reload(null, false); // reload data tanpa reset paging
		} else {
			initTable(); // fallback kalau belum ke-init
		}

		// RESET FORM SETELAH KIRIM
		$('#form_filter')[0].reset(); // reset input biasa
		$('#journal_type').val('').trigger('change'); // reset select2
		$('#post_branch').val('').trigger('change'); // reset select2
	}
</script>
