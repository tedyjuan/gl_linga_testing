<!-- Card -->
<div class="card">
	<div class="card-header">
		<div class="row align-items-center mb-3">
			<div class="col-md-12 d-flex justify-content-between">
				<h2 class="mb-0"><?= $judul; ?></h2>
				<div class="div">
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
		<table class="table table-sm table-striped table-hover table-bordered" id="mytable" style="width: 100%">
			<thead>
				<tr class="table-primary">
					<th style="width: 15%">Code Company</th>
					<th style="width: 15%">ID Akun</th>
					<th>Nama Akun</th>
					<th style="width: 15%">Type Akun</th>
					<th style="width: 5%">Action</th>
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
				url: "<?= base_url('C_chart_of_account/griddata'); ?>", // URL ke controller
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

	function add_data(route, uuid) {
		var url = route + "/" + uuid;
		loadform(url);
	}


</script>
