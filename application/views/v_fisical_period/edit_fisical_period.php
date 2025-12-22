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
						onclick="loadform('<?= $load_refresh ?>')">
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
						<label class="form-label" for="branch">Branch</label>
						<input type="text" name="branch" id="branch" value="<?= $data->code_depo . ' - ' . $data->depo_name ?>"
							class="form-control" readonly>
					</div>
				</div>
				<div class="col-6">
					<div class="mb-3">
						<label class="form-label" for="period">Period</label>
						<input type="text" name="period" id="period" value="<?= getMonthName($data->period) ?>" class="form-control" readonly>
					</div>
				</div>
				<div class="col-6">
					<div class="mb-3">
						<label class="form-label" for="year">year</label>
						<input type="text" name="year" id="year" value="<?= $data->year ?>" class="form-control" readonly>
					</div>
				</div>
				<div class="col-6">
					<div class="mb-3">
						<label class="form-label" for="status">Status</label>
						<select name="status" id="status" class="form-control select2" required>
							<option value="open" <?= $data->status == 'open' ? 'selected' : '' ?>>OPEN</option>
							<option value="closed" <?= $data->status == 'closed' ? 'selected' : '' ?>>CLOSE</option>
						</select>
						<span class="text-danger err_kodedivisi"></span>
					</div>
				</div>
			</div>

			<div class="col-md-12 d-flex justify-content-end">
				<div></div>
				<div>
					<button type="button" id="btnsubmit" class="btn btn-sm btn-primary"><i class="bi bi-send"></i>
						Save</button>
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
		var uuid = "<?= ($uuid) ?>";
		form.parsley().validate();
		if (form.parsley().isValid()) {
			$.ajax({
				url: "<?= base_url('C_fisical_period/update') ?>",
				type: 'POST',
				method: 'POST',
				dataType: 'JSON',
				data: form.serialize() + '&uuid=' + uuid,
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
			});
		}
	});

	$(document).ready(function() {
		$(".select2").select2({
			placeholder: 'Select...',
		});

	})
</script>
