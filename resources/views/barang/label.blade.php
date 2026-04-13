<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <title>Label Barang</title>

  <style>
    @page {
      size: A4;
      margin: 2mm;
    }

    body {
      margin: 0;
      padding: 0;
      font-family: DejaVu Sans, sans-serif;
      font-size: 13px;
      line-height: 1.0;
    }

    table {
      width: auto;
      border-collapse: separate;
      border-spacing: 3mm 2mm;
    }

    td {
      width: 38mm;
      height: 18mm;
      text-align: center;
      vertical-align: middle;
      font-size: 10px;
      padding: 0;
      overflow: hidden;
      box-sizing: border-box;
    }

    .barcode-img {
      width: 28mm;
      height: 7mm;
      margin-bottom: 1mm;
    }

    .id-barang {
      font-size: 8px;
      font-weight: bold;
      margin-bottom: 1mm;
    }
  </style>
</head>
<body>

@php
  $cols = 5;
  $rows = 8;

  $data = $barangs ?? [];

  $x = isset($x) ? (int)$x : 1;
  $y = isset($y) ? (int)$y : 1;

  $x = max(1, min($cols, $x));
  $y = max(1, min($rows, $y));

  $kosong = (($y - 1) * $cols) + ($x - 1);
@endphp

<?php

$total = 40;

$isi = count($data) + $kosong;

$sisa = $total - $isi;

$semua = [];

/* kosong awal */
for ($i = 0; $i < $kosong; $i++) {
    $semua[] = "";
}

/* isi barang */
foreach ($data as $d) {
    $semua[] = $d;
}

/* kosong akhir */
for ($i = 0; $i < $sisa; $i++) {
    $semua[] = "";
}

?>

<table>
  <?php for ($i = 0; $i < 40; $i += 5) { ?>
    <tr>
      <?php for ($j = 0; $j < 5; $j++) { ?>
        <?php $index = $i + $j; ?>
        <td>
          <?php if ($semua[$index] != "") { ?>

            <?php if (!empty($semua[$index]->barcode)) { ?>
              <img src="<?php echo $semua[$index]->barcode; ?>" class="barcode-img" alt="Barcode">
              <br>
            <?php } ?>

            <div class="id-barang">
              <?php echo $semua[$index]->id_barang; ?>
            </div>

            <b><?php echo $semua[$index]->nama_barang; ?></b>
            <br><br>
            Rp <?php echo number_format($semua[$index]->harga, 0, ',', '.'); ?>

          <?php } ?>
        </td>
      <?php } ?>
    </tr>
  <?php } ?>
</table>

</body>
</html>