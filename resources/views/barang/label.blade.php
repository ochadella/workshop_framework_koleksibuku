<!doctype html>
<html lang="id">
<head>
<meta charset="utf-8">
<title>Label Barang</title>

<style>
@page { size: A4 portrait; margin: 5mm; }

body {
    margin: 0;
    padding: 0;
    font-family: DejaVu Sans, sans-serif;
    font-size: 9pt;
}

/* 5 kolom x 8 baris */
table.sheet {
    width: 100%;
    border-collapse: collapse;
    table-layout: fixed;

    page-break-inside: avoid; /* ✅ cegah kepotong page */
    page-break-after: avoid;  /* ✅ cegah nambah halaman kosong */
}

tr { page-break-inside: avoid; } /* ✅ */
td.cell {
    width: 20%;

    /* ✅ turunin dikit biar gak “nyenggol” halaman 2 (DOMPDF rounding) */
    height: 34.5mm;

    vertical-align: top;
    padding: 2mm;
    border: none;

    box-sizing: border-box;
    overflow: hidden;
}

.nama  { font-weight: bold; font-size: 10pt; }
.harga { margin-top: 2px; }
.desk  { font-size: 8pt; }
.id    { font-size: 7pt; margin-top: 2px; }
</style>
</head>
<body>

@php
$cols = 5;
$rows = 8;
$totalSlots = $cols * $rows;

$x = max(1, min($cols, (int)$x));
$y = max(1, min($rows, (int)$y));

$startIndex = (($y - 1) * $cols) + ($x - 1);

$slots = array_fill(0, $totalSlots, null);

$i = 0;
for ($pos = $startIndex; $pos < $totalSlots; $pos++) {
    if ($i >= count($barangs)) break;
    $slots[$pos] = $barangs[$i];
    $i++;
}
@endphp

<table class="sheet">
@for ($r = 0; $r < $rows; $r++)
<tr>
@for ($c = 0; $c < $cols; $c++)
@php
$idx = ($r * $cols) + $c;
$b = $slots[$idx];
@endphp
<td class="cell">
@if($b)
<div class="nama">{{ $b->nama_barang }}</div>
<div class="harga">Rp {{ number_format($b->harga,0,',','.') }}</div>
<div class="desk">{{ $b->deskripsi }}</div>
<div class="id">{{ $b->id_barang }}</div>
@else
&nbsp;
@endif
</td>
@endfor
</tr>
@endfor
</table>

</body>
</html>