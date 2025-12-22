<!-- Card -->
<div class="card">
	<div class="card-header">
		<div class="row align-items-center mb-2">
			<div class="col-md-12 d-flex justify-content-between">
				<h2 class="mb-0"><?= $judul ?></h2>
				<div class="div ">
					<button class="btn btn-sm btn-primary" onclick="loadform('<?= $load_grid ?>')">
						<i class="bi bi-arrow-left-circle"></i> Back</button>
					<a href="javascript:void(0)" class="btn btn-sm btn-outline-primary"
						onclick="loadform('<?= $load_refresh ?>')">
						<i class="bi bi-arrow-clockwise"></i> Refresh
					</a>
				</div>
			</div>
		</div>
	</div>
	<div class="card-body">
		<table class="table table-sm table-hover table-bordered" id="mytable" style="width: 100%">

			<tbody>
				<?php foreach ($menu as $key => $row) : ?>
					<tr>
						<?php if ($row->sort_order_baru == 0) : ?>
							<td class="bg-soft-primary">
								<strong><?= $row->name; ?></strong>
							</td>
							<td class="bg-soft-primary text-center" style="width: 15%"></td>
						<?php else : ?>
							<td><?= $row->name; ?></td>
							<td class="text-center" style="width: 15%">
								<input onclick="submenus(this)"
									<?php if ($row->role_id != null) : ?> checked <?php endif; ?>
									type="checkbox" data-idmenu="<?= $row->id_menu ?>" data-parent="<?= $row->parent_id ?>"
									id="formCheck<?= $row->parent_id . $key ?>" class="form-check-input" style="transform: scale(1.7);">
							</td>
						<?php endif; ?>
					</tr>
				<?php endforeach; ?>

			</tbody>
		</table>
	</div>
</div>

<script>
	function submenus(e) {
		var parent = $(e).data("parent");
		var isChecked = $(e).prop('checked');
		var idmenu = $(e).data('idmenu');
		if (isChecked) {
			var initialCheckedStatus = false; // kebalikanya untuk keperluan cancel
			var msg = "The menu you selected will be activated?"
		} else {
			var initialCheckedStatus = true;
			var msg = "The menu you selected will be deactivated?"
		}
		Swal.fire({
			icon: "question",
			title: "Are you sure!",
			text: msg,
			showCancelButton: true,
			confirmButtonColor: "#3085d6",
			cancelButtonColor: "#d33",
			cancelButtonText: "Cancel",
			confirmButtonText: "Yes !",
			reverseButtons: true,
			allowOutsideClick: false
		}).then((result) => {
			if (result.isConfirmed) {
				var allCheckboxes = $('input[data-parent="' + parent + '"]');
				var checkedCount = allCheckboxes.filter(':checked').length;
				var totalCount = allCheckboxes.length;
				var idparen = parent + '-' + 0;
				// Update checkbox data-header sesuai dengan status
				if (checkedCount === 0) {
					var menuhead = "off";
				} else {
					var menuhead = "on";
				}

				var uuid = "<?= $uuid; ?>";
				$.ajax({
					type: "POST",
					url: "<?= base_url('C_role/set_role_menu'); ?>",
					method: "POST",
					dataType: "JSON",
					data: {
						uuid: uuid, 
						idmenu: idmenu, 
						menuhead: menuhead,
						parent: parent,
					},
					success: function(data) {
						if (data.hasil == "true") {
							hideLoader();
							swet_sukses(data.pesan);
							loadform('<?= $load_refresh ?>')
						} else {
							$(e).prop('checked', initialCheckedStatus);
							Swal.fire({
								icon: "info",
								title: "Information",
								text: data.pesan,
							});
						}
					},
				});
			} else if (result.dismiss === "cancel") {
				$(e).prop('checked', initialCheckedStatus);
				Swal.fire({
					icon: "info",
					title: "Information",
					text: "The action is cancelled.",
				});
			}
		});
	}
</script>
