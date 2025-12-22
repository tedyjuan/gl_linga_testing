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
					<th>No Voucher</th>
					<th>Date</th>
					<th>Proveniance</th>
					<th>Flow</th>
					<th>Status</th>
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
				url: "<?= base_url('C_petty_cash/griddata'); ?>", // URL ke controller
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

	function voids(uuid) {
		Swal.fire({
			icon: "question",
			title: "Are you sure!",
			text: "This data void action cannot be undone.",
			showCancelButton: true,
			confirmButtonColor: "#3085d6",
			cancelButtonColor: "#d33",
			cancelButtonText: "Cancel",
			confirmButtonText: "Yes !",
			reverseButtons: true,
		}).then((result) => {
			if (result.value) {
				$.ajax({
					type: "POST",
					url: "<?= base_url('C_petty_cash/voiddata'); ?>",
					method: "POST",
					dataType: "JSON",
					data: {
						uuid: uuid,
					},
					beforeSend: function() {
						showLoader();
					},
					success: function(data) {
						if (data.hasil == "true") {
							hideLoader();
							swet_sukses(data.pesan);
							if (window.mytableDT && $.fn.dataTable.isDataTable("#mytable")) {
								window.mytableDT.ajax.reload(null, false);
							} else {
								initTable(); // fallback kalau tabel belum pernah di-init
							}
						} else {
							Swal.fire({
								icon: "info",
								title: "Information",
								text: data.pesan,
							});
						}
					},
				});
			} else if (result.dismiss === "cancel") {
				Swal.fire({
					icon: "info",
					title: "Information",
					html: "Void Canceled",
				});
			}
		});
	}
</script>
