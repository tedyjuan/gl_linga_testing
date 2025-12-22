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
		<form id="forms_journal_entry" data-parsley-validate>
			<div class="row">
				<div class="col-6">
					<div class="row mb-1">
						<label for="branch" class="col-sm-4 col-form-label">Branch</label>
						<div class="col-sm-8 input-group-sm">
							<input type="text" id="branch" value="<?= $head->code_depo . ' - ' . $head->branch_name ?>" class=" form-control" readonly>
						</div>
					</div>
					<div class="row mb-1">
						<label for="batch_type" class="col-sm-4 col-form-label">Batch Type</label>
						<div class="col-sm-8 input-group-sm">
							<input type="text" id="batch_type" value="<?= $head->code_journal_source . ' - ' . $head->journal_source_name ?>" class=" form-control" readonly>
						</div>
					</div>
					<div class="row mb-1">
						<label for="batch_date" class="col-sm-4 col-form-label">Batch Date</label>
						<div class="col-sm-8 input-group-sm">
							<input type="text" id="batch_date" value="<?= $head->transaction_date ?>" class=" form-control" readonly>
						</div>
					</div>
				</div>
				<div class="col-6">
					<div class="row mb-1">
						<label for="batch_number" class="col-sm-4 col-form-label">Batch Number</label>
						<div class="col-sm-8 input-group-sm">
							<input type="text" id="batch_number" value="<?= $head->batch_number ?>" class=" form-control" readonly>
						</div>
					</div>
					<div class="row mb-1">
						<label for="voucher_number" class="col-sm-4 col-form-label">Voucher Number</label>
						<div class="col-sm-8 input-group-sm">
							<input type="text" id="voucher_number" value="<?= $head->voucher_number ?>" class=" form-control" readonly>
						</div>
					</div>
					<div class="row mb-1">
						<label for="des_header" class="col-sm-4 col-form-label">Description</label>
						<div class="col-sm-8 input-group-sm">
							<input type="text" id="des_header" value="<?= $head->description ?>" class=" form-control" readonly>
						</div>
					</div>
				</div>
			</div>
			<!-- Line Items Table -->
			<hr>
			<h5 class="mb-3">Journal Items</h5>
			<div class="mb-4 mb-md-5">
				<div class="table-responsive  rounded overflow-hidden">
					<table class="table mb-0" id="lineItemsTable">
						<thead class="table-light">
							<tr>
								<th style="width: 15%;">Cost Center</th>
								<th>No. Account</th>
								<th>Description</th>
								<th style="width: 15%;">Debit</th>
								<th style="width: 15%;">Credit</th>
							</tr>
						</thead>
						<tbody id="lineItemsBody">
							<?php foreach ($journal_item as $row) : ?>
								<tr class="align-middle">
									<td class="p-2">
										<input type="text" title="<?= $row->code_cost_center . ' - ' . $row->group_team ?>"
											value="<?= $row->code_cost_center ?>" class="form-control" name="description[]" readonly>
									</td>
									<td class="p-2">
										<input type="text" title="<?= $row->code_coa . ' - ' . $row->account_name ?>"
											value="<?= $row->code_coa . ' - ' . $row->account_name ?>" class="form-control" name="description[]" readonly>
									</td>
									<td class="p-2">
										<input type="text" title="<?= $row->description; ?>" value="<?= $row->description; ?>" class="form-control" name="description[]" readonly>
									</td>
									<td class="p-2">
										<input type="text" value="<?= $row->debit; ?>" class="form-control format_number" name="debit[]" readonly>
									</td>
									<td class="p-2">
										<input type="text" value="<?= $row->credit; ?>" class="form-control format_number" name="credit[]" readonly>
									</td>
								</tr>
							<?php endforeach; ?>
						</tbody>
						<tfoot>
							<tr class="table-light">
								<td colspan="12" class="p-2 fw-bold">
									<div class="row">
										<div class="col-lg-4 col-md-4 col-12">
											<label class="form-label" for="totalDebit">Debit Amount</label>
											<input type="text" readonly id="totalDebit" value="<?= $head->total_debit ?>" class="form-control format_number">
										</div>
										<div class="col-lg-4 col-md-4 col-12">
											<label class="form-label" for="totalCredit">Credit Amount</label>
											<input type="text" readonly id="totalCredit" value="<?= $head->total_credit ?>" class="form-control format_number">
										</div>
										<div class="col-lg-4 col-md-4 col-12">
											<label class="form-label" for="totalDifference">Difference</label>
											<input type="text" readonly id="totalDifference" value="<?= $head->difference ?>" class="form-control format_number">
										</div>
									</div>
								</td>
							</tr>
						</tfoot>
					</table>
				</div>
			</div>
		</form>
	</div>
</div>
<script>
	$(document).ready(function() {
		$('.format_number').mask("#.##0", {
			reverse: true
		});
	});
</script>
