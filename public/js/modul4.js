$(document).ready(function () {
    // Inisialisasi Select2
    if ($('#select2Kota').length) {
        $('#select2Kota').select2({
            width: '100%',
            placeholder: '-- Pilih Kota --'
        });
    }

    // CARD 1 - SELECT BIASA
    $('#btnTambahKotaSelect').off('click').on('click', function (e) {
        e.preventDefault();

        let kota = $('#inputKotaSelect').val().trim();

        if (kota === '') {
            alert('Kota tidak boleh kosong');
            return;
        }

        $('#selectKota').append(`<option value="${kota}">${kota}</option>`);
        $('#inputKotaSelect').val('');
    });

    $('#selectKota').off('change').on('change', function () {
        let kota = $(this).val();
        $('#hasilKotaSelect').text(kota !== '' ? kota : 'Belum ada kota dipilih');
    });

    // CARD 2 - SELECT2
    $('#btnTambahKotaSelect2').off('click').on('click', function (e) {
        e.preventDefault();

        let kota = $('#inputKotaSelect2').val().trim();

        if (kota === '') {
            alert('Kota tidak boleh kosong');
            return;
        }

        let newOption = new Option(kota, kota, false, false);
        $('#select2Kota').append(newOption).trigger('change');

        $('#inputKotaSelect2').val('');
    });

    $('#select2Kota').off('change').on('change', function () {
        let kota = $(this).val();
        $('#hasilKotaSelect2').text(kota !== '' ? kota : 'Belum ada kota dipilih');
    });
});