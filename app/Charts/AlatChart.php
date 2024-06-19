<?php

namespace App\Charts;

use ArielMejiaDev\LarapexCharts\LarapexChart;
use App\Models\Alat;
use Carbon\Carbon;

class AlatChart
{
    protected $chart;

    public function __construct(LarapexChart $chart)
    {
        $this->chart = $chart;
    }

    public function build(): \ArielMejiaDev\LarapexCharts\DonutChart
    {
        // Tentukan bulan dan tahun ini
        $currentMonth = now()->format('m-Y');

        // Mendapatkan jumlah berdasarkan kriteria nama dan filter bulan ini
        $data = [
            Alat::whereHas('alatMaster', function($query) {
                $query->where('nama', 'like', 'FL%');
            })
            ->where('periode', $currentMonth)
            ->count(),
            Alat::whereHas('alatMaster', function($query) {
                $query->where('nama', 'like', 'QCC%');
            })
            ->where('periode', $currentMonth)
            ->count(),
            Alat::whereHas('alatMaster', function($query) {
                $query->where('nama', 'like', 'RST%');
            })
            ->where('periode', $currentMonth)
            ->count(),
            Alat::whereHas('alatMaster', function($query) {
                $query->where('nama', 'like', 'RTG%');
            })
            ->where('periode', $currentMonth)
            ->count(),
            Alat::whereHas('alatMaster', function($query) {
                $query->where('nama', 'like', 'TTR%');
            })
            ->where('periode', $currentMonth)
            ->count(),
        ];

        // Label untuk setiap bagian donat
        $labels = [
            'Forklift',
            'Quay Container Crane',
            'Reach Stacker',
            'Rubber Tyred Gantry',
            'Terminal Tractor',
        ];

        // Membuat grafik donat dan menambahkan data serta label
        return $this->chart->donutChart()
            ->setWidth(500)
            ->setHeight(500)
            ->addData($data)
            ->setLabels($labels);
    }
}
