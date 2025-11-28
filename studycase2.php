<?php

$produk = [
    ["kode" => "A001", "nama" => "Indomie Goreng", "harga" => 3500, "stok" => 100],
    ["kode" => "A002", "nama" => "Teh Botol Sosro", "harga" => 4000, "stok" => 50],
    ["kode" => "A003", "nama" => "Susu Ultra Milk", "harga" => 12000, "stok" => 30],
    ["kode" => "A004", "nama" => "Roti Tawar Sari Roti", "harga" => 15000, "stok" => 20],
    ["kode" => "A005", "nama" => "Minyak Goreng Bimoli 1L", "harga" => 18000, "stok" => 15]
];

function cariProduk($array_produk, $kode) {
    foreach ($array_produk as $p) {
        if ($p["kode"] == $kode) {
            return $p;
        }
    }
    return null;
}

function hitungSubtotal($harga, $jumlah) {
    return $harga * $jumlah;
}

function hitungDiskon($total) {
    if ($total >= 100000) {
        return $total * 0.10;
    } elseif ($total >= 50000) {
        return $total * 0.05;
    }
    return 0;
}

function hitungPajak($total) {
    return $total * 0.11;
}

function kurangiStok(&$produk, $jumlah) {
    $produk["stok"] -= $jumlah;
}

function formatRupiah($angka) {
    return "Rp " . number_format($angka, 0, ",", ".");
}

function buatStrukBelanja($transaksi, &$array_produk) {

    echo "\n========================================\n";
    echo "             MINIMARKET\n";
    echo "========================================\n";

    $subtotal = 0;

    foreach ($transaksi as $t) {
        $produk = cariProduk($array_produk, $t["kode"]);
        $jumlah = $t["jumlah"];

        $sub = hitungSubtotal($produk["harga"], $jumlah);
        echo $produk["nama"] . "\n";
        echo formatRupiah($produk["harga"]) . " x " . $jumlah . " = " . formatRupiah($sub) . "\n\n";

        $subtotal += $sub;

        for ($i = 0; $i < count($array_produk); $i++) {
            if ($array_produk[$i]["kode"] == $t["kode"]) {
                kurangiStok($array_produk[$i], $jumlah);
            }
        }
    }

    $diskon = hitungDiskon($subtotal);
    $setelah_diskon = $subtotal - $diskon;
    $ppn = hitungPajak($setelah_diskon);
    $total = $setelah_diskon + $ppn;

    echo "----------------------------------------\n";
    echo "Subtotal           = " . formatRupiah($subtotal) . "\n";
    echo "Diskon             = " . formatRupiah($diskon) . "\n";
    echo "PPN 11%            = " . formatRupiah($ppn) . "\n";
    echo "TOTAL BAYAR        = " . formatRupiah($total) . "\n";
    echo "========================================\n\n";

    echo "Stok Setelah Transaksi:\n";
    foreach ($array_produk as $p) {
        echo $p["nama"] . ": " . $p["stok"] . " pcs\n";
    }
}


// ===========================
// PROGRAM UTAMA
// ===========================
echo "=== Daftar Produk ===\n";
foreach ($produk as $p) {
    echo $p["kode"] . " - " . $p["nama"] . " (" . formatRupiah($p["harga"]) . ") Stok: " . $p["stok"] . "\n";
}

$transaksi = [];

while (true) {
    echo "\nMasukkan kode produk (atau stop): ";
    $kode = readline();

    if ($kode == "stop") {
        break;
    }

    $data = cariProduk($produk, $kode);

    if ($data == null) {
        echo "Kode tidak ditemukan!\n";
        continue;
    }

    echo "Jumlah beli: ";
    $jumlah = intval(readline());

    if ($jumlah > $data["stok"]) {
        echo "Stok tidak cukup!\n";
        continue;
    }

    $transaksi[] = ["kode" => $kode, "jumlah" => $jumlah];
}

buatStrukBelanja($transaksi, $produk);

?>
