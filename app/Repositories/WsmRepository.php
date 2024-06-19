<?php

namespace App\Repositories;

use App\Helpers\WeightedSumModel;
use App\Models\{
    Alat,
    CalculatePriorityWeights,
    Criteria,
    WsmNormalization,
    WsmPrepareNormalization,
    WsmResultNormalization,
};
use Error;
use Illuminate\Support\Facades\DB;

class WsmRepository
{
    public static function Calculate(): array
    {
        $status = false;
        $message = 'Tidak dapat menghitung Rekomendasi saat ini. Silakan coba lagi nanti';

        DB::beginTransaction();

        $_this = new self();

        try {

            if (!$_this->normalization()) {
                throw new Error('Normalisasi Gagal!');
            }

            if (!$_this->wsm_normalization()) {
                throw new Error('Rekomendasi Normalisasi Gagal!');
            }

            if (!$_this->wsm_result_normalization()) {
                throw new Error('Rekomendasi Hasil Normalisasi Gagal!');
            }

            $status = true;
            $message = 'Perhitungan Rekomendasi berhasil diselesaikan';

            DB::commit();
        } catch (\Throwable $th) {
            $message = $th->getMessage();

            DB::rollBack();
        }

        return [$status, $message];
    }

    protected function normalization(): bool
    {
        $status = false;
        $currentMonth = now()->format('m-Y');
        $equipments = Alat::where('periode', $currentMonth)->get();

        $alternatif_jam_tersedia = [];
        $alternatif_jam_operasi  = [];
        $alternatif_jam_bda      = [];
        $alternatif_jumlah_bda   = [];

        $_store = [];

        try {
            /** clear data before use */
            WsmPrepareNormalization::query()->delete();

            /** data store for each variable */
            foreach ($equipments as $equipment) {
                $alternatif_jam_tersedia[] = $equipment->jam_tersedia;
                $alternatif_jam_operasi[]  = $equipment->jam_operasi;
                $alternatif_jam_bda[]      = $equipment->jam_bda;
                $alternatif_jumlah_bda[]   = $equipment->jumlah_bda;
            }

            $cek_utilisasi = true;
            $cek_availability = true;
            $cek_reliability = true;
            $cek_idle = true;

            // Check if all the conditions are zero for any equipment
            foreach ($equipments as $equipment) {
                if ($equipment->utilisasi != 0) {
                    $cek_utilisasi = false;
                }
                if ($equipment->availability != 0) {
                    $cek_availability = false;
                }
                if ($equipment->reliability != 0) {
                    $cek_reliability = false;
                }
                if ($equipment->idle != 0) {
                    $cek_idle = false;
                }
            }

            // Calculate the number
            foreach ($equipments as $equipment) {
                $_jam_tersedia = WeightedSumModel::number_devide_max_of($equipment->jam_tersedia, $alternatif_jam_tersedia);
                $_jam_operasi  = WeightedSumModel::number_devide_max_of($equipment->jam_operasi, $alternatif_jam_operasi);
                $_jam_bda      = WeightedSumModel::number_devide_max_of($equipment->jam_bda, $alternatif_jam_bda);
                $_jumlah_bda   = WeightedSumModel::number_devide_max_of($equipment->jumlah_bda, $alternatif_jumlah_bda);

                // Only set to 1 if the flag is true and the current value is zero
                if ($cek_utilisasi && $equipment->utilisasi == 0) {
                    $equipment->utilisasi = 1;
                }

                if ($cek_availability && $equipment->availability == 0) {
                    $equipment->availability = 1;
                }

                if ($cek_reliability && $equipment->reliability == 0) {
                    $equipment->reliability = 1;
                }

                if ($cek_idle && $equipment->idle == 0) {
                    $equipment->idle = 1;
                }

                $_store[] = [
                    'alat_master_id'=> $equipment->alat_master_id,
                    'utilisasi'    => $equipment->utilisasi,
                    'availability' => $equipment->availability,
                    'reliability'  => $equipment->reliability,
                    'idle'         => $equipment->idle,
                    'jam_tersedia' => $_jam_tersedia * 100,
                    'jam_operasi'  => $_jam_operasi * 100,
                    'jam_bda'      => $_jam_bda * 100,
                    'jumlah_bda'   => $_jumlah_bda * 100,
                ];
            }


            /** store data to database */
            WsmPrepareNormalization::insert($_store);

            $status = true;
        } catch (\Throwable $th) {
            //throw $th;
        }

        return $status;
    }

    protected function wsm_normalization(): bool
    {
        $status = false;
        $criteria = Criteria::all();
        $equipments = WsmPrepareNormalization::all();

        $normalisasi_utilisasi    = [];
        $normalisasi_availability = [];
        $normalisasi_reliability  = [];
        $normalisasi_idle         = [];
        $normalisasi_jam_tersedia = [];
        $normalisasi_jam_operasi  = [];
        $normalisasi_jam_bda      = [];
        $normalisasi_jumlah_bda   = [];

        $_store = [];

        try {
            /** clear data before use */
            WsmNormalization::query()->delete();

            /** data store for each variable */
            foreach ($equipments as $equipment) {
                $normalisasi_utilisasi[]    = $equipment->utilisasi;
                $normalisasi_availability[] = $equipment->availability;
                $normalisasi_reliability[]  = $equipment->reliability;
                $normalisasi_idle[]         = $equipment->idle;
                $normalisasi_jam_tersedia[] = $equipment->jam_tersedia;
                $normalisasi_jam_operasi[]  = $equipment->jam_operasi;
                $normalisasi_jam_bda[]      = $equipment->jam_bda;
                $normalisasi_jumlah_bda[]   = $equipment->jumlah_bda;
            }

            /** calculate the number */
            foreach ($equipments as $equipment) {
                $_store[] = [
                    'alat_master_id'=> $equipment->alat_master_id,
                    'utilisasi'    => $this->wsm_normalization_count($criteria, 'Utilisasi', $equipment->utilisasi, $normalisasi_utilisasi),
                    'availability' => $this->wsm_normalization_count($criteria, 'Availability', $equipment->availability, $normalisasi_availability),
                    'reliability'  => $this->wsm_normalization_count($criteria, 'Reliability', $equipment->reliability, $normalisasi_reliability),
                    'idle'         => $this->wsm_normalization_count($criteria, 'Jam idle', $equipment->idle, $normalisasi_idle),
                    'jam_tersedia' => $this->wsm_normalization_count($criteria, 'Jam tersedia', $equipment->jam_tersedia, $normalisasi_jam_tersedia),
                    'jam_operasi'  => $this->wsm_normalization_count($criteria, 'Jam operasi', $equipment->jam_operasi, $normalisasi_jam_operasi),
                    'jam_bda'      => $this->wsm_normalization_count($criteria, 'Jumlah BDA', $equipment->jam_bda, $normalisasi_jam_bda),
                    'jumlah_bda'   => $this->wsm_normalization_count($criteria, 'Jam BDA', $equipment->jumlah_bda, $normalisasi_jumlah_bda),
                ];
            }

            /** store data to database */
            WsmNormalization::insert($_store);

            $status = true;
        } catch (\Throwable $th) {
            //throw $th;
        }

        return $status;
    }

    protected function wsm_result_normalization(): bool
    {
        $status = false;
        $equipments = WsmNormalization::all();
        $ahp_pw = CalculatePriorityWeights::query()->where('name', 'like', '%-pw')->get();
        $pw = [];

        if ($ahp_pw->count() == 0) {
            return $status;
        }

        foreach ($ahp_pw as $ahp) {
            $pw[$ahp->name] = $ahp->hasil;
        }

        $_store = [];

        try {
            /** clear data before use */
            WsmResultNormalization::query()->delete();

            /** calculate the number */
            foreach ($equipments as $equipment) {
                $raw_data = [
                    'utilisasi'    => WeightedSumModel::multiple($equipment->utilisasi, $pw['Utilisasi-pw']),
                    'availability' => WeightedSumModel::multiple($equipment->availability, $pw['Availability-pw']),
                    'reliability'  => WeightedSumModel::multiple($equipment->reliability, $pw['Reliability-pw']),
                    'idle'         => WeightedSumModel::multiple($equipment->idle, $pw['Jam idle-pw']),
                    'jam_tersedia' => WeightedSumModel::multiple($equipment->jam_tersedia, $pw['Jam tersedia-pw']),
                    'jam_operasi'  => WeightedSumModel::multiple($equipment->jam_operasi, $pw['Jam operasi-pw']),
                    'jumlah_bda'   => WeightedSumModel::multiple($equipment->jumlah_bda, $pw['Jumlah BDA-pw']),
                    'jam_bda'      => WeightedSumModel::multiple($equipment->jam_bda, $pw['Jam BDA-pw']),
                ];

                $_store[] = array_merge(
                    [
                        'alat_master_id'=> $equipment->alat_master_id,
                    ],
                    $raw_data,
                    [
                        'hasil'         => array_sum(array_values($raw_data)),
                    ]
                );
            }

            /** store data to database */
            WsmResultNormalization::insert($_store);

            /** sort by ranking */
            $wsm_results = WsmResultNormalization::all();
            $wsm_hasils  = DB::table('wsm_result_normalizations')->select('hasil')->get();
            $hasil = [];

            $__store = [];

            foreach ($wsm_hasils as $_hasil) {
                $hasil[] = $_hasil->hasil;
            }

            /** set ranking */
            foreach ($wsm_results as $wsm) {
                unset($wsm['id']);
                unset($wsm['created_at']);
                unset($wsm['updated_at']);

                $__store[] =  array_merge(
                    $wsm->toArray(),
                    [
                        'rangking' => WeightedSumModel::rank($wsm->hasil, $hasil)
                    ]
                );
            }

            /** clear data before use */
            WsmResultNormalization::query()->delete();

            /** store data to database */
            WsmResultNormalization::insert($__store);

            $status = true;
        } catch (\Throwable $th) {
            //throw $th;
        }

        return $status;
    }

    protected function wsm_normalization_count($criteria, $name, $number, $numbers): float
    {
        return $criteria->firstWhere('name', $name)->jenis === 'Cost'
            ? WeightedSumModel::min_devide_by_number($numbers, $number)
            : WeightedSumModel::number_devide_max_of($number, $numbers);
    }
}
