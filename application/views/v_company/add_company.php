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
                        onclick="loadform('<?= $load_back ?>')">
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
                        <label class="form-label" for="company_code">Code Company</label>
                        <input type="text" id="company_code" name="company_code" data-parsley-minlength="3"
                            class="form-control-hover-light form-control" placeholder="input kode perusahaan max:5 min:3 karakter"
                            data-parsley-required="true" data-parsley-errors-container=".err_code" required="">
                        <span class="text-danger err_code"></span>
                    </div>
                </div>
                <div class="col-6">
                    <div class="mb-3">
                        <label class="form-label" for="company_name">Name Company</label>
                        <input type="text" id="company_name" name="company_name"
                            class="form-control-hover-light form-control" placeholder="input nama perusahaan"
                            data-parsley-required="true" data-parsley-errors-container=".err_name" required="">
                        <span class="text-danger err_name"></span>
                    </div>
                </div>
            </div>
            <div class="col-md-12 d-flex justify-content-end">
                <div></div>
                <div>
                    <button type="button" id="btnsubmit" class="btn btn-sm btn-primary"><i class="bi bi-send"></i>
                        Simpan</button>
                    <button type="reset" class="btn btn-sm btn-outline-danger"><i class="bi bi-eraser-fill"></i>
                        Reset</button>
                </div>
            </div>
        </form>
    </div>
</div>
<script>
    $(document).ready(function() {
        $('#company_code').on('input', function(e) {
            this.value = this.value.replace(/[^a-zA-Z]/g, '').toUpperCase();
            if (this.value.length > 5) {
                this.value = this.value.substring(0, 5);
            }
        });

        $('#company_name').on('keyup', function() {
            var value = $(this).val();
            var formattedValue = value.replace(/\b\w/g, function(char) {
                return char.toUpperCase();
            }).replace(/\B\w/g, function(char) {
                return char.toLowerCase();
            });
            $('#company_name').val(formattedValue);
        });
    });
    $('#btnsubmit').click(function(e) {
        e.preventDefault();
        let form = $('#forms_add');
        form.parsley().validate();
        if (form.parsley().isValid()) {
            $.ajax({
                url: "<?= base_url('C_company/simpandata') ?>",
                type: 'POST',
                method: 'POST',
                dataType: 'JSON',
                data: form.serialize(),
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
                error: function(xhr) {
                    
                    if (xhr.status === 422) {
                        let errors = xhr.responseJSON.errors;
                        $.each(errors, function(key, value) {
                            $(`.err_${key}`).html(value[0]);
                        });
                    } else {
                        swet_gagal("Terjadi kesalahan server (" + xhr.status + ")");
                    }
                },
            });
        }
    });
</script>
