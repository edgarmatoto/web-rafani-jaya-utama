<?php

namespace App\Helpers;

class Helper
{
    public static function terbilang($angka)
    {
        $angka = abs($angka);
        $bilangan = ["", "satu", "dua", "tiga", "empat", "lima",
            "enam", "tujuh", "delapan", "sembilan", "sepuluh", "sebelas"];
        $temp = "";

        if ($angka < 12) {
            $temp = " " . $bilangan[$angka];
        } elseif ($angka < 20) {
            $temp = self::terbilang($angka - 10) . " belas";
        } elseif ($angka < 100) {
            $temp = self::terbilang(intval($angka / 10)) . " puluh " . self::terbilang($angka % 10);
        } elseif ($angka < 200) {
            $temp = " seratus " . self::terbilang($angka - 100);
        } elseif ($angka < 1000) {
            $temp = self::terbilang(intval($angka / 100)) . " ratus " . self::terbilang($angka % 100);
        } elseif ($angka < 2000) {
            $temp = " seribu " . self::terbilang($angka - 1000);
        } elseif ($angka < 1000000) {
            $temp = self::terbilang(intval($angka / 1000)) . " ribu " . self::terbilang($angka % 1000);
        } elseif ($angka < 1000000000) {
            $temp = self::terbilang(intval($angka / 1000000)) . " juta " . self::terbilang($angka % 1000000);
        } else {
            $temp = "Angka terlalu besar";
        }

        return trim(preg_replace('/\s+/', ' ', $temp)); // rapikan spasi berlebih
    }
}
