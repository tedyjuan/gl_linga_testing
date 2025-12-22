function tocontroller(controller, active, dropdown, head_nav) {
	$(".sub_reset").removeClass("active");

	if (active && active !== "") {
		$("#" + active).addClass("active");
	}

	if (dropdown && dropdown !== "") {
		$("#" + dropdown).addClass("show");
		$("[href='#" + dropdown + "']").attr("aria-expanded", "true");
	}

	if (head_nav && head_nav !== "") {
		$("#" + head_nav).addClass("active");
	}

	if (controller && controller !== "") {
		loadform(controller);
	}
}

function loadform(controller) {
	var base = BASE_URL;
	var url = base + "/" + controller;
	// Jika tidak ada di cache, lakukan AJAX request
	showLoader();
	$("#contentdata").load(url, function (response, status, xhr) {
		hideLoader(); // Sembunyikan preloader setelah AJAX selesai

		if (status === "error") {
			$("#contentdata").html(
				`<div class="alert alert-danger">Failed to load content.</div>`
			);
			return;
		}
		// Cek apakah response JSON dengan session expired
		try {
			let json = JSON.parse(response);
			if (json.session_expired) {
				swet_gagal(json.message);
				setTimeout(function () {
					window.location.href = json.redirect;
				}, 1000); // 2000 ms = 2 detik delay
				return;
			}
		} catch (e) {
			// Bukan JSON? lanjut seperti biasa
		}
	});
}
// function loadform(controller) {
// 	var base = BASE_URL;
// 	var url = base + "/" + controller;
// 	var cacheKey = "page_cache_" + controller;
// 	var cacheVersion = "v1.0.0"; // ganti versi kalau ada update besar
// 	// 1️⃣ Cek cache di localStorage
// 	let cached = localStorage.getItem(cacheKey);
// 	if (cached) {
// 		try {
// 			let data = JSON.parse(cached);
// 			if (data.version === cacheVersion) {
// 				console.log("✅ Loaded from cache:", controller);
// 				$("#contentdata").html(data.html);
// 				hideLoader();
// 				return;
// 			}
// 		} catch (e) {
// 			console.warn("Cache rusak, ambil baru...");
// 		}
// 	}

// 	// 2️⃣ Kalau cache tidak ada / versi beda, ambil dari server
// 	$("#contentdata").load(
// 		url + "?v=" + cacheVersion,
// 		function (response, status, xhr) {
// 			hideLoader();

// 			if (status === "error") {
// 				$("#contentdata").html(
// 					`<div class="alert alert-danger">Gagal memuat konten.</div>`
// 				);
// 				return;
// 			}

// 			// 3️⃣ Cek session expired (JSON)
// 			try {
// 				let json = JSON.parse(response);
// 				if (json.session_expired) {
// 					swet_gagal(json.message);
// 					setTimeout(function () {
// 						window.location.href = json.redirect;
// 					}, 1000);
// 					return;
// 				}
// 			} catch (e) {
// 				// Bukan JSON, lanjut
// 			}

// 			// 4️⃣ Simpan hasil ke localStorage
// 			localStorage.setItem(
// 				cacheKey,
// 				JSON.stringify({
// 					html: response,
// 					version: cacheVersion,
// 					timestamp: Date.now(),
// 				})
// 			);
// 			console.log("💾 Save Cached:", controller);
// 		}
// 	);
// }

function showLoader() {
	$("#preloader").show();
}

function hideLoader() {
	$("#preloader").hide();
}

function swet_sukses(pesan) {
	Swal.fire({
		icon: "success",
		title: pesan,
		showConfirmButton: false,
		timer: 1500,
	});
}
function swet_gagal(pesan) {
	Swal.fire({
		icon: "info",
		title: pesan,
		showConfirmButton: false,
		timer: 3000,
	});
}

function hapus(uuid, url_hapus, load_grid) {
	Swal.fire({
		icon: "question",
		title: "Are you sure!",
		text: "This data will be permanently deleted",
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
				url: url_hapus,
				method: "POST",
				dataType: "JSON",
				data: {
					uuid: uuid,
				},
				beforeSend: function () {
					showLoader();
				},
				success: function (data) {
					hideLoader();
					if (data.hasil == "true") {
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
			hideLoader();
			Swal.fire({
				icon: "info",
				title: "Information",
				html: "Deletion Canceled",
			});
		}
	});
}

function editform(route, uuid) {
	var url = route + "/" + uuid;
	loadform(url);
}
function clearPageCache() {
	for (let key in localStorage) {
		if (key.startsWith("page_cache_")) {
			localStorage.removeItem(key);
		}
	}
	Swal.fire({
		icon: "info",
		title: "Information",
		html: "Cache has been successfully cleared!",
	});
}
function duit() {
	$(".format_duit").on("input", function () {
		var value = $(this).val();
		var firstInput = value.charAt(0);
		var second = value.charAt(1);
		if (firstInput == 0) {
			$(this).val("");
			return;
		}
		if (firstInput === "-" && second === "0") {
			value = "-" + value.substring(2);
		}
		// Cek apakah minus ada di awal, jika ada simpan
		var isNegative = value.indexOf("-") === 0;
		// Hapus tanda minus sementara untuk pemrosesan
		value = value.replace("-", "").replace(/\D/g, ""); // Hapus non-digit
		// Format input dengan pemisah ribuan
		var formattedValue = value.replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1."); // Pemisah ribuan
		// Tambahkan tanda minus jika sebelumnya ada minus di awal
		if (isNegative) {
			formattedValue = "-" + formattedValue;
		}
		// Set nilai terformat kembali ke input
		$(this).val(formattedValue);
	});
}
function formatRupiah(angka) {
	if (angka == null || angka === "") return "0";
	return new Intl.NumberFormat("id-ID", {
		minimumFractionDigits: 0,
	}).format(angka);
}
