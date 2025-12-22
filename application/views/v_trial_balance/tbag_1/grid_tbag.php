<!-- Card -->
<div class="card">
	<div class="card-header">
		<div class="row align-items-center">
			<div class="col-md-12 d-flex justify-content-between">
				<h2 class="mb-0">Tiral Balance</h2>
			</div>
		</div>
	</div>
	<div class="card-body">
		<ul class="nav nav-tabs" id="myTab" role="tablist">
			<li class="nav-item" role="presentation">
				<button class="nav-link active" id="tbag-tab1" data-bs-toggle="tab" data-bs-target="#group-nav1" type="button" role="tab" aria-controls="group-nav1" aria-selected="true"><i class="bi bi-diagram-3-fill"></i> Group 1</button>
			</li>
			<li class="nav-item" role="presentation">
				<button class="nav-link" id="tbag-tab2" data-bs-toggle="tab" data-bs-target="#group-nav2" type="button" role="tab" aria-controls="group-nav2" aria-selected="false"><i class="bi bi-diagram-3-fill"></i> Group 2</button>
			</li>
			<li class="nav-item" role="presentation">
				<button class="nav-link" id="tbag-tab3" data-bs-toggle="tab" data-bs-target="#group-nav3" type="button" role="tab" aria-controls="group-nav3" aria-selected="false"><i class="bi bi-diagram-3-fill"></i> Group 3</button>
			</li>
			<li class="nav-item ms-auto" id="btnmodaltb3" style="display: none;">
				<div class="d-flex justify-content-end mb-2">
					<button type="button" class="btn btn-primary" id="btnmodaltbag" data-bs-toggle="modal" data-bs-target="#modaltbg3">
						<i class="bi bi-plus-circle"></i> Tambah
					</button>
				</div>
			</li>
		</ul>


		<!-- Konten Tab -->
		<div class="tab-content" id="myTabContent">
			<div class="tab-pane fade show active" id="group-nav1" role="tabpanel" aria-labelledby="tbag-tab1">
				<div class="card mt-4">
					<div class="card-body">
						<table class="table table-sm table-striped table-hover table-bordered" id="mytable" style="width: 100%">
							<thead>
								<tr class="table-primary">
									<th style="width: 30%">Company</th>
									<th style="width: 15%">Code</th>
									<th>Deskripsi</th>
									<th>Akun Type</th>
								</tr>
							</thead>
						</table>
					</div>
				</div>
			</div>
			<div class="tab-pane fade" id="group-nav2" role="tabpanel" aria-labelledby="tbag-tab2">
				<!-- Konten akan dimuat di sini -->
			</div>
			<div class="tab-pane fade" id="group-nav3" role="tabpanel" aria-labelledby="tbag-tab3">
				<!-- Konten akan dimuat di sini -->
			</div>
		</div>
	</div>
</div>

<script>
	$(document).ready(function() {
		$('#tbag-tab1').on('click', function() {
			loadform('<?= $load_grid ?>')
			$('#btnmodaltb3').hide();
		});
		$('#tbag-tab2').on('click', function() {
			$('#group-nav2').load('C_trial_balance/tbag_2');
			$('#btnmodaltb3').hide();
		});

		$('#tbag-tab3').on('click', function() {
			$('#group-nav3').load('C_trial_balance/tbag_3');
			$('#btnmodaltb3').show();
		});
	});
</script>

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
				url: "<?= base_url('C_trial_balance/griddata'); ?>",
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
