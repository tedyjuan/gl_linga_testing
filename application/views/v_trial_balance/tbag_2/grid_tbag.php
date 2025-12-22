<!-- Card -->
<div class="card mt-4">
	<div class="card-body">
		<table class="table table-sm table-striped table-hover table-bordered" id="mytable2" style="width: 100%">
			<thead>
				<tr class="table-primary">
					<th>Company</th>
					<th>Code</th>
					<th>Deskripsi</th>
					<th>Code TBG-1</th>
				</tr>
			</thead>
		</table>
	</div>
</div>

<script>
	function initTable() {
		// kalau sebelumnya sudah ada instance, hancurkan dulu
		if (window.mytableDT && $.fn.dataTable.isDataTable('#mytable2')) {
			window.mytableDT.clear().destroy();
			window.mytableDT = null;
		}

		window.mytableDT = $('#mytable2').DataTable({
			processing: true,
			serverSide: true,
			ajax: {
				url: "<?= base_url('C_trial_balance/griddata_tbag_2'); ?>",
				type: "POST",
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
	});

	
</script>
