<!-- Card -->
<div class="card">
	<div class="card-header">
		<div class="row align-items-center mb-3">
			<div class="col-md-12 d-flex justify-content-between">
				<h2 class="mb-0"><?= $judul; ?></h2>
				<div>
					<a href="javascript:void(0)" class="btn btn-sm btn-outline-primary"
						onclick="loadform('<?= $load_grid ?>')">
						<i class="bi bi-arrow-clockwise"></i> Refresh
					</a>
				</div>
			</div>
		</div>
	</div>

	<div class="card-body">
		<form id="forms_generate">
			<div class="row">
				<div class="col-6">
					<div class="mb-3">
						<label class="form-label" id="company" for="company">Company</label>
						<input type="text" id="company" name="company" value="<?= $company; ?>" class="form-control" disabled>
					</div>
				</div>
				<div class="col-6">
					<div class="mb-3">
						<label class="form-label" for="type_report">Type Report</label>
						<input type="text" id="type_report" name="type_report" value="Profit And Lost Statement" class="form-control" disabled>
					</div>
				</div>


			</div>

			<!-- PERIODE -->
			<div class="row">
				<div class="col-3">
					<div class="mb-3">
						<label class="form-label" for="set_periode">Periode</label>
						<select id="set_periode" name="set_periode" class="form-control select2"
							data-parsley-required="true"
							data-parsley-errors-container=".err_set_periode">
							<option value="">Select Period</option>
							<option value="MTD">MTD - Month To Date</option>
							<option value="YTD">YTD - Year To Date</option>
						</select>
						<span class="text-danger err_set_periode"></span>
					</div>
				</div>

				<div class="col-3">
					<div class="mb-3">
						<label class="form-label" id="idlabel" for="mtd_ytd_date">Year Or Month</label>
						<input type="text" id="mtd_ytd_date" name="mtd_ytd_date" disabled
							class="form-control"
							data-parsley-required="true"
							data-parsley-errors-container=".err_mtd_ytd_date"
							placeholder="Select date" readonly>
						<span class="text-danger err_mtd_ytd_date"></span>
					</div>
				</div>

				<div class="col-3">
					<div class="mb-3">
						<label class="form-label" for="start_date">Start Date</label>
						<input type="text" id="start_date" name="start_date"
							class="form-control" disabled
							data-parsley-required="true"
							data-parsley-errors-container=".err_start_date"
							placeholder="Select date">
						<span class="text-danger err_start_date"></span>
					</div>
				</div>

				<div class="col-3">
					<div class="mb-3">
						<label class="form-label" for="end_date">End Date</label>
						<input type="text" id="end_date" name="end_date"
							class="form-control" disabled
							data-parsley-required="true"
							data-parsley-errors-container=".err_end_date"
							placeholder="Select date">
						<span class="text-danger err_end_date"></span>
					</div>
				</div>
			</div>

			<div class="col-md-12 d-flex justify-content-end">
				<div>
					<button type="button" id="btnsubmit" class="btn btn-sm btn-primary">
						<i class="bi bi-send"></i> Generate
					</button>
					<button type="reset" class="btn btn-sm btn-outline-danger">
						<i class="bi bi-eraser-fill"></i> Reset
					</button>
				</div>
			</div>

		</form>
	</div>
</div>
<script>
	$(document).ready(function() {
		$(".select2").select2();
		initMonthPicker();

		// Fungsi hitung jumlah hari dalam bulan
		function getLastDay(year, month) {
			return new Date(year, month, 0).getDate();
		}

		$("#set_periode").on("change", function() {
			let periode = $(this).val();

			// Reset semua
			$("#mtd_ytd_date").removeData("real");
			$("#mtd_ytd_date").datepicker("destroy");
			$("#start_date, #end_date").val("");

			if (periode == '') {
				$("#idlabel").text("Year Or Month");
				$("#mtd_ytd_date").val("").prop("disabled", true);
				return;
			}

			if (periode === "YTD") {
				$("#idlabel").text("Year");
				$("#mtd_ytd_date").prop("disabled", false);
				initYearPicker();
			}

			if (periode === "MTD") {
				$("#idlabel").text("Month - Year");
				$("#mtd_ytd_date").prop("disabled", false);
				initMonthPicker();
			}

			$("#mtd_ytd_date").val(""); // reset input
		});

		// MODE MONTH-YEAR (MTD)
		function initMonthPicker() {
			$("#start_date, #end_date").val("");

			$('#mtd_ytd_date').datepicker({
				format: "MM yyyy",
				startView: "months",
				minViewMode: "months",
				autoclose: true
			}).on('changeDate', function(e) {
				let year = e.date.getFullYear();
				let month = ("0" + (e.date.getMonth() + 1)).slice(-2);

				$('#mtd_ytd_date')
					.removeData("real")
					.attr("data-real", `${year}-${month}`);
			});
		}

		// MODE YEAR ONLY (YTD)
		function initYearPicker() {
			$("#start_date, #end_date").val("");

			$("#mtd_ytd_date").datepicker({
				format: "yyyy",
				startView: "years",
				minViewMode: "years",
				autoclose: true
			}).on("changeDate", function(e) {
				let year = e.date.getFullYear();
				$("#mtd_ytd_date").val(year);
				$("#mtd_ytd_date")
					.removeData("real")
					.attr("data-real", year);
			});
		}

		// HANDLE PENGISIAN start_date & end_date
		$("#mtd_ytd_date").on("change", function() {
			let periode = $("#set_periode").val();
			$("#end_date").prop("disabled", false).val("");

			let start, end;

			if (periode === 'YTD') {

				let year = $(this).val();
				start = `${year}-01-01`;
				end = `${year}-12-31`; // full tahun

			} else {

				let tahun_bulan = $("#mtd_ytd_date").data("real"); // contoh 2025-04
				let [year, month] = tahun_bulan.split('-');

				let lastDay = getLastDay(year, month); // hitung jumlah hari bulan

				start = `${tahun_bulan}-01`;
				end = `${tahun_bulan}-${lastDay}`;
			}

			$("#start_date").val(start);

			// Destroy dulu agar tidak conflict
			$("#end_date").datepicker("destroy").datepicker({
				format: "yyyy-mm-dd",
				autoclose: true,
				startDate: start,
				endDate: end
			});
		});

	});
	$('#btnsubmit').click(function(e) {
		e.preventDefault();
		let form = $('#forms_generate');
		form.parsley().validate();
		if (!form.parsley().isValid()) return;
		const type_periode = encodeURIComponent($("#set_periode").val());
		const mtd_ytd_date = encodeURIComponent($('#mtd_ytd_date').data('real'));
		const start_date = encodeURIComponent($("#start_date").val());
		const end_date = encodeURIComponent($("#end_date").val());
		const url = "<?= base_url('CR_profit_and_lost/Report') ?>?" +
			"type_periode=" + type_periode +
			"&mtd_ytd_date=" + mtd_ytd_date +
			"&start_date=" + start_date +
			"&end_date=" + end_date;
		window.open(url, "_blank");
	});
</script>
