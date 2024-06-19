<?php

namespace App\Repositories;

use App\Helpers\MatrixMultiplication;
use App\Models\{
    Anhipro,
    CalculatePriorityWeights,
    ConsistencyRatio,
    ConsistencyRatioResult,
    ConsistencyRatioResultConsistent,
    Geomean,
    Kriteria,
    PairComparisonMatrix,
};
use Error;
use Illuminate\Support\Facades\DB;

class AhpRepository
{
    public static function Calculate(): array
    {
        $status = false;
        $message = 'Tidak dapat menghitung Bobot Kriteria saat ini. Silakan coba lagi nanti';

        DB::beginTransaction();

        $geomean = Geomean::all();
        $criteria = Kriteria::all();

        $_this = new self();

        try {
            if (!$_this->pair_comparison_matrix($geomean, $criteria)) {
                throw new Error('Matriks Perbandingan Pasangan Gagal!');
            }

            if (!$_this->pair_comparison_matrix_result($criteria)) {
                throw new Error('Hasil Matriks Perbandingan Pasangan Gagal!');
            }

            if (!$_this->calculate_priority_weights($criteria)) {
                throw new Error('Menghitung Bobot Prioritas Gagal!');
            }

            if (!$_this->calculating_consistency_ratio($criteria)) {
                throw new Error('Menghitung Rasio Konsistensi Gagal!');
            }

            if (!$_this->devide_consistency_ratio_with_pw($criteria)) {
                throw new Error('Membagi Rasio Konsistensi Dengan Pw Gagal!');
            }

            if (!$_this->consistency_ratio()) {
                throw new Error('Menghitung Rasio Konsistensi Konsistensi Gagal!');
            }

            $status = true;
            $message = 'Perhitungan Bobot Kriteria berhasil diselesaikan';

            DB::commit();
        } catch (\Throwable $th) {
            $message = $th->getMessage();

            DB::rollBack();
        }

        return [$status, $message];
    }


    protected function pair_comparison_matrix($geomean, $criteria): bool
    {
        $status = false;

        try {
            /** clean table AnHiPro */
            Anhipro::query()->delete();

            /** duplicate geomean */
            $geomean_insert = [];

            foreach ($geomean as $_geomean) {
                $geomean_insert[] = [
                    'kriteria_id' => $_geomean->kriteria_id,
                    'hasil' => $_geomean->hasil,
                ];
            }

            /** ids for filter kriteria */
            $criteria_ids = [];

            foreach ($geomean as $_geomean) {
                $criteria_ids[] = $_geomean->kriteria_id;
            }

            /** where not in geomean */
            $criteria_not_in_geomean = $criteria->whereNotIn('id', $criteria_ids);

            /** get value 1/geomean */
            foreach ($criteria_not_in_geomean as $cnig) {
                $jenis = $cnig->name; // reverse -> jenis
                $name = $cnig->jenis; // reverse -> name

                $_criteria_id = $criteria
                    ->where('jenis', $jenis)
                    ->where('name', $name)
                    ->first();

                $_geomean = $geomean->firstWhere('kriteria_id', $_criteria_id->id);
                $_geomean = $_geomean->hasil != 0 ? $_geomean->hasil : 1;

                $append_to_geomean_insert = [
                    'kriteria_id' => $cnig->id,
                    'hasil' => number_format(1 / $_geomean, 2)
                ];

                $geomean_insert[] = $append_to_geomean_insert;
            }

            /** insert duplication to database */
            Anhipro::insert($geomean_insert);

            $status = true;
        } catch (\Throwable $th) {
            //throw $th;
        }

        return $status;
    }

    protected function pair_comparison_matrix_result($criteria): bool
    {
        $status = false;

        try {
            $anhipro = Anhipro::all();
            $criteria_gb_jenis = $criteria->groupBy('jenis');

            /** delete PCM record */
            PairComparisonMatrix::query()->delete();

            foreach ($criteria_gb_jenis as $index => $criteria) {
                $criteria_count = 0;

                foreach ($criteria as $_index => $_criteria) {
                    $anhipro_result = $anhipro->firstWhere('kriteria_id', $_criteria->id);
                    
                    if ($anhipro_result !== null) {
                        $criteria_count += $anhipro_result->hasil;
                    }
                }

                /** insert to PCM */
                PairComparisonMatrix::insert([
                    'name' => $index,
                    'hasil' => number_format($criteria_count, 2)
                ]);
            }

            $status = true;
        } catch (\Throwable $th) {
            //throw $th;
        }

        return $status;
    }

    protected function calculate_priority_weights($criteria): bool
    {
        $status = false;

        try {
            /**
             * get an duplicate and calcuated gmm from anhipro table
             * get pari comparison matrix result
             * criteria grouping by name
             */
            $anhipro = Anhipro::all();
            $pairComparisonMatrix = PairComparisonMatrix::all();
            $criteria_gb_name = $criteria->groupBy('name');

            /** clear table */
            CalculatePriorityWeights::query()->delete();

            foreach ($criteria_gb_name as $index => $criteria) {
                $jumlah = 0;

                foreach ($criteria as $_index => $_criteria) {
                    $anhipro_result = $anhipro->firstWhere('kriteria_id', $_criteria->id);
                    $pairComparisonMatrix_result = $pairComparisonMatrix->firstWhere('name', $_criteria->jenis);
                
                    $hasil = 0;
                    if ($anhipro_result !== null && $pairComparisonMatrix_result !== null) {
                        $hasil = $anhipro_result->hasil / $pairComparisonMatrix_result->hasil;
                    }
                    $jumlah += $hasil;
                }

                $pw = number_format($jumlah / 8, 2);
                $jumlah = number_format($jumlah, 3);

                $store = [
                    [
                        'name' => "{$index}-jumlah",
                        'hasil' => $jumlah,
                    ],
                    [
                        'name' => "{$index}-pw",
                        'hasil' => $pw,
                    ],
                ];

                /** CPW insert to database */
                CalculatePriorityWeights::insert($store);
            }

            $status = true;
        } catch (\Throwable $th) {
            //throw $th;
        }

        return $status;
    }

    protected function calculating_consistency_ratio($criteria): bool
    {
        $status = false;

        try {

            $anhipro = Anhipro::all();
            $criteria_gb_name = $criteria->groupBy('name');

            /** delete prev result */
            ConsistencyRatio::query()->delete();

            $ratio = [];
            $matrix1 = [];
            $matrix2 = [];

            $calculatePriorityWeights = CalculatePriorityWeights::query()->where('name', 'like', '%-pw')->get();

            foreach ($calculatePriorityWeights as $weight) {
                $matrix2[] = $weight->hasil;
            }

            foreach ($criteria_gb_name as $index => $criteria) {
                foreach ($criteria as $_criteria) {
                    $anhipro_result = $anhipro->firstWhere('kriteria_id', $_criteria->id);
                    
                    $hasil = $anhipro_result !== null ? $anhipro_result->hasil : null;
            
                    $matrix1[$index][] = $hasil;
                }
            }

            $ratio = MatrixMultiplication::count($matrix1, $matrix2);
            $store = [];

            foreach ($ratio as $index => $_ratio) {
                $store[] = [
                    'name' => $index,
                    'hasil' => $_ratio,
                ];
            }

            /** store to database the result */
            ConsistencyRatio::insert($store);

            $status = true;
        } catch (\Throwable $th) {
            // throw $th;
        }

        return $status;
    }

    protected function devide_consistency_ratio_with_pw($criteria): bool
    {
        $status = false;

        try {
            /** take CCR result */
            $consistencyRatio = ConsistencyRatio::all();

            /** delete prev result */
            ConsistencyRatioResult::query()->delete();

            /** get the pw */
            $calculatePriorityWeights = CalculatePriorityWeights::query()->where('name', 'like', '%-pw')->get();

            $store = [];

            foreach ($consistencyRatio as $ratio) {
                $_ratio = $ratio !== null ? $ratio->hasil : null;
                $calculate_priority_weight = $calculatePriorityWeights->firstWhere('name', "{$ratio->name}-pw");
                $pw = $calculate_priority_weight !== null ? $calculate_priority_weight->hasil : null;
            
                $hasil = ($pw !== null && $_ratio !== null) ? $_ratio / $pw : null;
            
                $store[] = [
                    'name' => $ratio->name,
                    'hasil' => $hasil,
                ];
            }

            /** store to database */
            ConsistencyRatioResult::insert($store);

            $status = true;
        } catch (\Throwable $th) {
            //throw $th;
        }

        return $status;
    }

    protected function consistency_ratio(): bool
    {
        $status = false;

        try {
            /** get thr result from devide concistency ratio*/
            $consistencyRatioResult = ConsistencyRatioResult::all();

            /** delete entry before */
            ConsistencyRatioResultConsistent::query()->delete();

            $result = 0;

            foreach ($consistencyRatioResult as $ratio) {
                $result += $ratio->hasil;
            }


            /** c. Menghitung λmaks */
            $amaks = $result / 8;

            /** d. Menghitung Consistency Index(CI) */
            $ci = ($amaks - 8) / (8 - 1);

            /** e. Menghitung Consistency Ratio(CR) */
            $cr = $ci / 1.41;

            ConsistencyRatioResultConsistent::insert([
                'hasil' => $cr
            ]);

            $status = true;
        } catch (\Throwable $th) {
            //throw $th;
        }

        return $status;
    }
}
