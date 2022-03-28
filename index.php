
<?php

require_once 'function.php';
$data = new rajaongkir;

$kota = $data->get_city();
$kota_array = json_decode($kota,true);
// print_r($kota_array);
// die();
if ($kota_array['rajaongkir']['status']['code'] == 200) :
    $kota_result  = $kota_array['rajaongkir']['results'];
  else :
    die('This key has reached the daily limit.');
  endif;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">
    <link rel="stylesheet" href="https://select2.github.io/select2-bootstrap-theme/css/select2-bootstrap.css">
    <link rel="stylesheet" href="//cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
    <title>Cek Ongkir</title>

</head>
<body>
    <div class="container">
        <div class="row mt-3">
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        Cek Ongkir
                    </div>
                    <div class="card-body">
                        <form id="form-cek-ongkir">
                            <div class="form-group">
                                <label for="kota_asal">Kota Asal</label>
                                <select name="kota_asal" id="kota_asal" class="form-control" required="true">
                                    <option value=""></option>
                                    <?php foreach($kota_array['rajaongkir']['results'] as $key =>$value): ?>
                                        <option value="<?= $value['city_id']; ?>"><?= $value['city_name']; ?></option>
                                    <?php endforeach;?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="kota_tujuan">Kota tujuan</label>
                                <select name="kota_tujuan" id="kota_tujuan" class="form-control" required="true">
                                    <option value=""></option>
                                    <?php foreach($kota_array['rajaongkir']['results'] as $key =>$value): ?>
                                        <option value="<?= $value['city_id']; ?>"><?= $value['city_name']; ?></option>
                                    <?php endforeach;?>
                                </select>
                            </div>
                           
                            <div class="form-group">
                                <label for="berat">Berat kiriman</label>
                                <input type="number" id="berat" name="berat" class="form-control" min="1" max="30000">

                            </div>
                            <div class="form-group">
                                <button type="submit" id="btn-periksa-ongkir" class="btn btn-primary">Periksa ongkir</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header" id="hasil-pengecekan">
                        Hasil Pengecekan
                    </div>
                    <div class="card-body">
                        <table id="tabel-hasil-pengecekan" class="display">
                        <thead>
                            <tr>
                                <th width="1%">No</th>
                                <th>Kurir</th>
                                <th>Jenis layanan</th>
                                <th>Tarif</th>
                            </tr>
                        </thead>
                        <tbody>

                        </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>











<script src="https://code.jquery.com/jquery-3.5.1.min.js" integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js" integrity="sha384-B4gt1jrGC7Jh4AgTPSdUtOBvfO8shuf57BaghqFfPlYxofvL8/KUEfYiJOMMV+rV" crossorigin="anonymous" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="//cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>
<script>
 // Select2 untuk kota asal
    $('#kota_asal').select2({
        placeholder: "Pilih Kota Asal",
        theme: "bootstrap"
      });
 // Select2 untuk kota Tujuan
    $('#kota_tujuan').select2({
        placeholder: "Pilih Kota Tujuan",
        theme: "bootstrap"
    });

    $('#form-cek-ongkir').on('submit',function(e){
        e.preventDefault();

        // $('#btn-periksa-ongkir').prop('disabled', true)
        // .text('Loading...');
        let kota_asal = $('#kota_asal').select2('data')[0].text;
        let kota_tujuan = $('#kota_tujuan').select2('data')[0].text;
        let berat = $('#berat').val();

        $('#hasil-pengecekan').html(`Hasil pengecekan <b>${kota_asal}</b> ke <b>${kota_tujuan}</b> @${berat} gram`);
        hasil_pengecekan();
    });

    function hasil_pengecekan(){
        $('#tabel-hasil-pengecekan').DataTable({
            processing:true,
            serverSide:true,
            bDestroy:true,
            responsive:true,
            ajax:{
                url:'cost.php',
                type : 'POST',
                data:{
                    kota_asal: $('#kota_asal').val(),
                    kota_tujuan: $('#kota_tujuan').val(),
                    berat: $('#berat').val(),
                },
                complete: function(data){
                    resetForm('#form-cek-ongkir',['kota_asal','kota_tujuan']);
                    $('#btn-periksa-ongkir').prop('disabled', false)
              .text('Periksa Ongkir');
                }
            }
        });
    }
    function resetForm(form,select2 = []){
        $('#'+form[0].reset());
        if(select2.length > 0){
            $.each(select2,function(k,v){
                $('#'+v).val('').trigger('change');
            });
        }
    }
</script>
</body>
</html>