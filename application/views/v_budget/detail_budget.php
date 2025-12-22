<!-- Card -->
<style>
	.form-check-input {
		transform: scale(1.3);
	}
</style>

<div class="card">
	<div class="card-header">
		<div class="row align-items-center mb-2">
			<div class="col-md-12 d-flex justify-content-between">
				<h2 class="mb-0"><?= $judul ?></h2>
				<div class="div ">
					<?php if ($verify == null) { ?>
						<button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#staticBackdrop">
							Verify
						</button>
					<?php } ?>
					<button type="button" class="btn btn-sm btn-primary" onclick="loadform('<?= $load_grid ?>')"><i
							class="bi bi-arrow-left-circle"></i> Back
					</button>
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
					<input type="text" readonly id="perusahaan" value="<?= $data->code_company . ' - ' . $data->company_name ?>" class="form-control-hover-light form-control">
				</div>
			</div>
			<div class="col-6">
				<div class="mb-3">
					<label class="form-label" for="department">Departemen </label>
					<input type="text" readonly id="department" value="<?= $data->department_alias . ' - ' . $data->department_name;  ?>" class="form-control-hover-light form-control">
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-6">
				<div class="mb-3">
					<label class="form-label" for="saldo_awal">Saldo Awal</label>
					<input type="text" readonly id="saldo_awal" value="<?= $data->opening_balance; ?>" class="form-control-hover-light form-control curency">
				</div>
			</div>
			<div class="col-3">
				<div class="mb-3">
					<label class="form-label" for="perpanjang_angaran">Perpanjangan Anggaran </label>
					<input type="text" readonly id="perpanjang_angaran" value="<?= $data->extend_budget; ?>" class="form-control-hover-light form-control curency">
				</div>
			</div>
			<div class="col-3">
				<div class="mb-3">
					<label class="form-label" for="jumlah_project">Jumlah Project </label>
					<input type="number" readonly id="jumlah_project" value="<?= $data->project_amount; ?>" class="form-control-hover-light  form-control curency">
				</div>
			</div>
		</div>
	</div>
</div>
<?php if ($verify != null) { ?>
	<div class="card mt-4 border border-secondary border-1">
		<div class="card-header">
			<div class="row">
				<div class="col-lg-4 col-md-4 col-12">
					<label class="form-label" for="ver_status">Status Verifikasi</label>
					<input type="text" value="<?= $verify['status_budgeting']; ?>" readonly id="ver_status" name="ver_status" class="form-control" placeholder="0">
				</div>
				<div class="col-lg-4 col-md-4 col-12">
					<label class="form-label" for="ver_user">User Verifikasi</label>
					<input type="text" value="<?= $verify['user_created']; ?>" readonly id="ver_user" name="ver_user" class="form-control" placeholder="0">
				</div>
				<div class="col-lg-4 col-md-4 col-12">
					<label class="form-label" for="ver_date">Tanggal verifikasi</label>
					<input type="text" value="<?= $verify['verification_date']; ?>" readonly id="ver_date" name="ver_date" class="form-control" placeholder="0">
				</div>
			</div>
			<div class="row">
				<div class="col-12">
					<label class="form-label" for="ver_des">Keterangan verifikasi </label>
					<textarea class="form-control" name="ver_des" id="ver_des" readonly><?= $verify['verification_notes']; ?></textarea>
				</div>
			</div>
		</div>
	</div>
<?php } ?>
<?php
$no = 1;
foreach ($data_project as $key => $row) { ?>
	<div class="card mt-4 border border-secondary border-1" id="project_<?= $no; ?>">
		<div class="card-body">
			<div class="row">
				<h2>Project <?= $no; ?></h2>
			</div>
			<div class="row">
				<div class="col-6">
					<div class="mb-3">
						<label class="form-label" for="project_name<?= $no; ?>">Name Project </label>
						<input readonly type="text" id="project_name<?= $no; ?>" value="<?= $row['project_name']; ?>" class="form-control form-control-hover-light ">
					</div>
				</div>
				<div class="col-6">
					<label class="form-label" for="project_file_<?= $no; ?>">File Proposal</label>
					<div class="input-group mb-3">
						<input readonly type="text" class="form-control" value="<?= $row['filename']; ?>" placeholder="Username" aria-label="Username" aria-describedby="basic-addon<?= $no; ?>">
						<a href="<?= base_url('download/' . $row['filename']); ?>" class="btn btn-primary">
							<i class="bi bi-cloud-download"></i>
							&nbsp;Download File</a>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-6">
					<div class="mb-3">
						<label class="form-label" for="usulan_anggaran_<?= $no; ?>">Usulan Anggaran </label>
						<input readonly type="text" id="usulan_anggaran_<?= $no; ?>" value="<?= $row['budget_proposal']; ?>" class="form-control form-control-hover-light curency">
					</div>
				</div>
				<div class="col-6">
					<div class="s">
						<label class="form-label" for="perpanjang_angaran">Tujuan Proyek </label>
					</div>
					<div class="form-check form-check-inline">
						<input type="checkbox" disabled id="p_angaran_<?= $no; ?>" class="form-check-input indeterminate-checkbox"
							<?php if ($row['goal_project'] == 'ALL' || $row['goal_project'] == 'OPEX') echo 'checked'; ?>>
						<label>Mengurangi Biaya (OPEX)</label>
					</div>
					<div class="form-check form-check-inline">
						<input type="checkbox" disabled id="meningkatkan_produktifitas<?= $no; ?>" class="form-check-input indeterminate-checkbox"
							<?php if ($row['goal_project'] == 'ALL' || $row['goal_project'] == 'CAPEX') echo 'checked'; ?>>
						<label>Meningkatkan Produktivitas (CAPEX)</label>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-12">
					<label class="form-label" for="desc_project">Deskripsi Project</label>
					<textarea readonly name="project_desc_<?= $no; ?>" id="project_desc_<?= $no; ?>" class="form-control form-control-hover-light "><?= $row['project_desc']; ?></textarea>
				</div>
			</div>
			<!-- Tabel Mengurangi Biaya -->
			<?php if (!empty($row['item_opex'])) { ?>
				<div class="container mt-5" id="tblMengurangiBiayaContainer_<?= $no; ?>">
					<h2>Mengurangi Biaya</h2>
					<input class="counta_<?= $no; ?>" type="hidden" value="0">
					<table class="table table-bordered table-thead-bordered table-sm" id="tblMengurangiBiaya_<?= $no; ?>" style="width: 100%;">
						<thead class="thead-light">
							<tr>
								<th style="width: 35%;">Nama Account</th>
								<th>Keterangan</th>
								<th style="width: 20%;">Jumlah</th>
							</tr>
						</thead>
						<tbody>
							<?php foreach ($row['item_opex'] as $ia) { ?>
								<tr>
									<td style="width: 35%;"><?= $ia['account_number'] . ' - ' .  $ia['name'] ?></td>
									<td><?= $ia['desc']; ?></td>
									<td class="curency" style="width: 20%;"><?= $ia['amount']; ?></td>
								</tr>
							<?php } ?>
						</tbody>
					</table>
				</div>
			<?php  } ?>
			<?php if (!empty($row['item_capex'])) { ?>
				<!-- Tabel Meningkatkan Produktivitas -->
				<div class="container mt-5" id="tblMeningkatkanProduktivitasContainer_<?= $no; ?>">
					<h2>Meningkatkan Produktivitas</h2>
					<input class="countb_<?= $no; ?>" type="hidden" value="0">
					<table class="table table-bordered table-thead-bordered table-sm" id="tblMeningkatkanProduktivitas_<?= $no; ?>" style="width: 100%;">
						<thead class="thead-light">
							<tr>
								<th>Keterangan</th>
								<th style="width: 20%;">Jumlah</th>
							</tr>
						</thead>
						<tbody>
							<?php foreach ($row['item_capex'] as $ib) { ?>
								<tr>
									<td><?= $ib['desc']; ?></td>
									<td class="curency" style="width: 20%;"><?= $ib['amount']; ?></td>
								</tr>
							<?php } ?>
						</tbody>
					</table>
				</div>
			<?php  } ?>
		</div>
	</div>
	<?php $no++; ?>
<?php } ?>

<div class="card mt-4">
	<div class="card-header">
		<div class="row">
			<div class="col-lg-3 col-md-6 col-12">
				<label class="form-label">Saldo Awal </label>
				<input type="text" value="<?= $summary['opening_balance']; ?>" readonly id="f_saldo" name="f_saldo" class="form-control curency" placeholder="0">
			</div>
			<div class="col-lg-3 col-md-6 col-12">
				<label class="form-label" for="f_opex">Total Biaya</label>
				<input type="text" value="<?= $summary['opex']; ?>" readonly id="f_opex" name="f_opex" class="form-control curency" placeholder="0">
			</div>
			<div class="col-lg-3 col-md-6 col-12">
				<label class="form-label" for="f_capex">Total Produktivitas</label>
				<input type="text" value="<?= $summary['capex']; ?>" readonly id="f_capex" name="f_capex" class="form-control curency" placeholder="0">
			</div>
			<div class="col-lg-3 col-md-6 col-12">
				<label class="form-label" for="f_selisih">Selisih</label>
				<input type="text" value="<?= $summary['difference']; ?>" readonly id="f_selisih" name="f_selisih" class="form-control curency" placeholder="0">
			</div>
		</div>
	</div>
</div>



<!-- Modal -->
<div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="staticBackdropLabel">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="staticBackdropLabel">Modal title</h5>
			</div>
			<form id="forms_add">
				<div class="modal-body">
					<div class="col-12">
						<div class="mb-3">
							<label class="form-label" for="id_budget">ID Budget </label>
							<input type="text" id="id_budget" value="<?= $code; ?>" class="form-control" readonly>
						</div>
					</div>
					<div class="col-12">
						<div class="mb-3">
							<label class="form-label" for="status_budgeting">Verifikasi</label>
							<select id="status_budgeting" name="status_budgeting" class="form-control-hover-light form-control select2"
								data-parsley-required="true" data-parsley-errors-container=".err_status_budget" required=""
								placeholder="pilih verifikasi">
								<option value="">Pilih</option>
								<option value="APPROVED">APPROVED</option>
								<option value="REJECT">REJECT</option>
							</select>
							<span class="text-danger err_status_budget"></span>
						</div>
					</div>
					<div class="col-12">
						<label class="form-label" for="deskripsi_verify">Deskripsi</label>
						<textarea name="deskripsi_verify" id="deskripsi_verify" class="form-control-hover-light form-control"
							data-parsley-required="true" data-parsley-errors-container=".err_des_verify" required=""
							placeholder="input deskripsi"></textarea>
						<span class="text-danger err_des_verify"></span>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" id="btnsubmit" class="btn btn-sm btn-primary"><i class="bi bi-send"></i> Simpan</button>
					<button type="button" class="btn btn-sm btn-outline-danger" id="closeModalButton"><i class="bi bi-send"></i> Close</button>
				</div>
			</form>
		</div>
	</div>
</div>
<!-- End Modal -->
<script>
	$(document).ready(function() {
		$('#closeModalButton').click(function() {
			$('#staticBackdrop').modal('hide');
			$('#perusahaan').focus();
		});
		$('.curency').mask("#.##0", {
			reverse: true
		});
	});
	$('#btnsubmit').click(function(e) {
		e.preventDefault();
		let form = $('#forms_add');
		var uuid = "<?= ($uuid) ?>";
		form.parsley().validate();
		if (form.parsley().isValid()) {
			$.ajax({
				url: "<?= base_url('C_budget/verify') ?>",
				type: 'POST',
				method: 'POST',
				dataType: 'JSON',
				data: form.serialize() + '&uuid=' + uuid,
				beforeSend: function() {
					// showLoader();
				},
				success: function(data) {
					if (data.hasil == 'true') {
						$('#staticBackdrop').modal('hide');
						$('#perusahaan').focus();
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
</script>
