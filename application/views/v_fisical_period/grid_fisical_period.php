<!-- Card -->
<div class="card">
	<div class="card-header">
		<div class="row align-items-center mb-3">
			<div class="col-md-12 d-flex justify-content-between">
				<h2 class="mb-0"><?= $judul; ?></h2>
				<div class="d-flex gap-2">
					<button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#modalfilter">
						<i class="bi bi-search"></i> Filter
					</button>
					<button class="btn btn-sm btn-primary" onclick="loadform('<?= $load_add ?>')">
						<i class="bi bi-plus-circle"></i> Add data
					</button>
					<a href="javascript:void(0)" class="btn btn-sm btn-outline-primary"
						onclick="loadform('<?= $load_grid ?>')">
						<i class="bi bi-arrow-clockwise"></i> Refresh
					</a>
				</div>
			</div>
		</div>
	</div>
	<div class="card-body">
		<!-- <div class="row">
			<div class="col-4">
				<div class="input-group mb-3 col-4">
					<input type="text" readonly id="tahun" name="tahun" class="form-control" placeholder="Search year" aria-label="Search year" aria-describedby="button-addon2" autocomplete="off">
					<span class="input-group-text btn btn-primary" onclick="filters()"> Search</span>
				</div>
			</div>
		</div> -->

		<table class="table table-sm table-striped table-hover table-bordered" id="mytable" style="width: 100%">
			<thead>
				<tr class="table-primary">
					<th>Branch</th>
					<th>Period</th>
					<th>Status</th>
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
				<h4 class="modal-title mb-3 text-white" id="modalfilterLabel">FILTER FISCAL PERIOD</h4>
			</div>
			<form id="form_filter">
				<div class="modal-body">
					<div class="mb-2">
						<label class="form-label" for="tahun">Years</label>
						<input type="text" id="tahun" name="tahun" class="form-control" placeholder="search year">
					</div>
					<div class="mb-2">
						<label class="form-label" for="branch">Branch</label>
						<select style="width:100%" id="branch" name="branch" class="form-control-hover-light form-control select2"
							data-parsley-required="true" data-parsley-errors-container=".err_branch" required="">
							<option value=""></option>
							<?php foreach ($depos as $row) : ?>
								<option value="<?= $row->code_depo ?>"><?= $row->code_depo ?> - <?= $row->name ?></option>
							<?php endforeach; ?>
						</select>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" data-bs-dismiss="modal" id="btn_cancel" class="btn btn-sm btn-outline-danger">
						<i class="bi bi-x"></i> Close
					</button>
					<button type="button" class="btn btn-sm btn-primary" onclick="filters()"><i class="bi bi-search"></i> Search</button>
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
			pageLength: 12,
			searching: false,
			lengthChange: false,
			ajax: {
				url: "<?= base_url('C_fisical_period/griddata'); ?>", // URL ke controller
				type: "POST",
				data: function(d) {
					d.tahun = $('#tahun').val();
					d.post_branch = $('#branch').val();
				}
			},
			columnDefs: [{
				orderable: false,
				targets: -1 // Menonaktifkan sorting pada kolom terakhir (aksi)
			}],
			// Opsi tambahan yang membantu saat SPA:
			destroy: true, // Auto-destroy jika di-init di elemen yang sama
			retrieve: true // Jika sudah ada instance, gunakan instance tersebut
		});
	}

	$(document).ready(function() {
		initTable();
		$(".select2").select2({
			placeholder: "Search Branch",
			allowClear: true,
			width: '100%'
		});
		$('#tahun').datepicker({
			format: "yyyy",
			startView: "years",
			minViewMode: "years",
			maxViewMode: "years",
			autoclose: true
		});
	});


	function filters() {
		$('#btn_cancel').click();
		if (window.mytableDT) {
			window.mytableDT.ajax.reload(null, false);
		} else {
			initTable();
		}
		$('#form_filter')[0].reset();
		$('#branch').val('').trigger('change');
	}
</script>
