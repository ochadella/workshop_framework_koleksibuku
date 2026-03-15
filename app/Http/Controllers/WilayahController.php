<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class WilayahController extends Controller
{
    public function index()
    {
        return view('wilayah.index');
    }

    public function getProvinces()
    {
        $data = [
            ['id' => 1, 'name' => 'Jawa Barat'],
            ['id' => 2, 'name' => 'Jawa Tengah'],
            ['id' => 3, 'name' => 'Jawa Timur'],
        ];

        return response()->json($data);
    }

    public function getCities($province)
    {
        $data = [
            1 => [
                ['id' => 1, 'name' => 'Bandung'],
                ['id' => 2, 'name' => 'Bekasi'],
            ],
            2 => [
                ['id' => 3, 'name' => 'Semarang'],
                ['id' => 4, 'name' => 'Solo'],
            ],
            3 => [
                ['id' => 5, 'name' => 'Surabaya'],
                ['id' => 6, 'name' => 'Malang'],
            ],
        ];

        return response()->json($data[$province] ?? []);
    }

    public function getDistricts($city)
    {
        $data = [
            1 => [
                ['id' => 1, 'name' => 'Coblong'],
                ['id' => 2, 'name' => 'Lengkong'],
            ],
            2 => [
                ['id' => 3, 'name' => 'Bekasi Barat'],
                ['id' => 4, 'name' => 'Bekasi Timur'],
            ],
            3 => [
                ['id' => 5, 'name' => 'Tembalang'],
                ['id' => 6, 'name' => 'Banyumanik'],
            ],
            4 => [
                ['id' => 7, 'name' => 'Laweyan'],
                ['id' => 8, 'name' => 'Banjarsari'],
            ],
            5 => [
                ['id' => 9, 'name' => 'Wonokromo'],
                ['id' => 10, 'name' => 'Tegalsari'],
            ],
            6 => [
                ['id' => 11, 'name' => 'Klojen'],
                ['id' => 12, 'name' => 'Lowokwaru'],
            ],
        ];

        return response()->json($data[$city] ?? []);
    }

    public function getVillages($district)
    {
        $data = [
            1 => [
                ['id' => 1, 'name' => 'Dago'],
                ['id' => 2, 'name' => 'Lebak Gede'],
            ],
            2 => [
                ['id' => 3, 'name' => 'Turangga'],
                ['id' => 4, 'name' => 'Burangrang'],
            ],
            3 => [
                ['id' => 5, 'name' => 'Bintara'],
                ['id' => 6, 'name' => 'Kranji'],
            ],
            4 => [
                ['id' => 7, 'name' => 'Margahayu'],
                ['id' => 8, 'name' => 'Aren Jaya'],
            ],
            5 => [
                ['id' => 9, 'name' => 'Tembalang'],
                ['id' => 10, 'name' => 'Sendangmulyo'],
            ],
            6 => [
                ['id' => 11, 'name' => 'Ngesrep'],
                ['id' => 12, 'name' => 'Padangsari'],
            ],
            7 => [
                ['id' => 13, 'name' => 'Pajang'],
                ['id' => 14, 'name' => 'Sondakan'],
            ],
            8 => [
                ['id' => 15, 'name' => 'Kadipiro'],
                ['id' => 16, 'name' => 'Keprabon'],
            ],
            9 => [
                ['id' => 17, 'name' => 'Darmo'],
                ['id' => 18, 'name' => 'Jagir'],
            ],
            10 => [
                ['id' => 19, 'name' => 'Kedungdoro'],
                ['id' => 20, 'name' => 'Dr. Soetomo'],
            ],
            11 => [
                ['id' => 21, 'name' => 'Bareng'],
                ['id' => 22, 'name' => 'Kauman'],
            ],
            12 => [
                ['id' => 23, 'name' => 'Dinoyo'],
                ['id' => 24, 'name' => 'Merjosari'],
            ],
        ];

        return response()->json($data[$district] ?? []);
    }
}