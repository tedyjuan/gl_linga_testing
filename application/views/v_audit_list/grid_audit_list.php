<!-- Card -->
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
		<table class="table table-sm table-striped table-hover table-bordered" id="mytable" style="width: 100%">
			<thead>
				<tr class="table-primary">
					<th>Date</th>
					<th>User</th>
					<th>Branch</th>
					<th>Batch Number</th>
					<th>Method</th>
					<th>Description</th>
					<th style="width: 5%;">Action</th>
				</tr>
			</thead>
		</table>
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
			serverSide: true, // Mengaktifkan server-side processing
			ajax: {
				url: "<?= base_url('C_audit_list/griddata'); ?>", // URL ke controller
				type: "POST", // Gunakan GET untuk request
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
	});
</script>
