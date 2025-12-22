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
						<label class="form-label" for="voucher_no">No Voucher </label>
						<input type="text" readonly id="voucher_no" value="<?= $data->voucher_no ?>" class="form-control-hover-light form-control">
					</div>
				</div>
				<div class="col-6">
					<div class="mb-3">
						<label class="form-label" for="trans_date">Date </label>
						<input type="text" readonly id="trans_date" value="<?= $data->trans_date;  ?>" class="form-control-hover-light form-control">
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-6">
					<div class="mb-3">
						<label class="form-label" for="proveniance">Proveniance</label>
						<input type="text" readonly id="proveniance" value="<?= $data->proveniance; ?>" class="form-control-hover-light form-control">
					</div>
				</div>
				<div class="col-6">
					<div class="mb-3">
						<label class="form-label" for="flow">Flow </label>
						<input type="text" readonly id="flow" value="<?= $data->flow; ?>" class="form-control-hover-light form-control">
					</div>
				</div>
			</div>
			<?php if (!empty($list_bank)) { ?>
				<h2 class="mt-4">Account Bank</h2>
				<table class="table table-bordered table-thead-bordered table-sm" style="width: 100%;">
					<thead class="thead-light">
						<tr>
							<th style="width: 5%;">No</th>
							<th style="width: 35%;">Account No</th>
							<th>Bank Name</th>
							<th style="width: 20%;">Due Date</th>
						</tr>
					</thead>
					<tbody>
						<?php foreach ($list_bank as $ia) { ?>
							<tr>
								<td><?= $ia->item_number?></td>
								<td><?= $ia->account_no  . " - " . $ia->name ?></td>
								<td><?= $ia->bank_name; ?></td>
								<td><?= $ia->trans_date; ?></td>
							</tr>
						<?php } ?>
					</tbody>
				</table>
			<?php  } ?>
			<?php if (!empty($list_item)) { ?>
				<h2 class="mt-4">Account Item Price</h2>
				<table class="table table-bordered table-thead-bordered table-sm" style="width: 100%;">
					<thead class="thead-light">
						<tr>
							<th style="width: 5%;">No</th>
							<th style="width: 35%;">Account No</th>
							<th>Description</th>
							<th style="width: 20%;">Debit</th>
							<th style="width: 20%;">Credit</th>
						</tr>
					</thead>
					<tbody>
						<?php foreach ($list_item as $ia) { ?>
							<tr>
								<td><?= $ia->item_number ?></td>
								<td><?= $ia->account_no   . " - " . $ia->name ?></td>
								<td><?= $ia->description; ?></td>
								<td><?= $ia->debit; ?></td>
								<td><?= $ia->credit; ?></td>
							</tr>
						<?php } ?>
					</tbody>
					<tfoot>
						<tr class="table-light">
							<td colspan="12" class="p-2 fw-bold">
								<div class="row">
									<div class="col-lg-4 col-md-4 col-12">
										<label class="form-label" for="totalDebit">Debit Amount</label>
										<input type="text" readonly value="<?= $amount->debit_amount; ?>" class="form-control">
									</div>
									<div class="col-lg-4 col-md-4 col-12">
										<label class="form-label" for="totalCredit">Credit Amount</label>
										<input type="text" readonly value="<?= $amount->credit_amount; ?>" class="form-control">
									</div>
									<div class="col-lg-4 col-md-4 col-12">
										<label class="form-label" for="totalDifference">Difference</label>
										<input type="text" readonly value="<?= $amount->difference; ?>" class="form-control">
									</div>
								</div>
							</td>
						</tr>
					</tfoot>
				</table>
			<?php  } ?>
		</form>
	</div>
</div>
