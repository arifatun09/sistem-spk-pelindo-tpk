<?php

namespace App\View\Components;

use App\Models\WsmResultNormalization;
use Illuminate\View\Component;

class TableWSMHasilShowComponent extends Component
{
    public $filter;
    public $count;
    public $search;
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($filter = null, $count = null, $search = null)
    {
        $this->filter = $filter;
        $this->count = $count;
        $this->search = $search;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        // $wsm_result_normalisasi = WsmResultNormalization::query()->orderBy('rangking')->get();

        // dd($this->filter);

        // $wsm_result_normalisasi = WsmResultNormalization::query()
        //     ->orderBy('hasil', 'desc') // Urutkan berdasarkan 'hasil' dalam urutan menurun
        //     ->orderBy('rangking')      // Urutkan berdasarkan 'rangking' setelahnya
        //     ->get();

        // Menginisialisasi query dengan join pada tabel alat_master
        $query = WsmResultNormalization::query()
            ->join('alat_masters', 'wsm_result_normalizations.alat_master_id', '=', 'alat_masters.id')
            ->select('wsm_result_normalizations.*', 'alat_masters.nama')
            ->orderBy('hasil', 'desc') // Urutkan berdasarkan 'hasil' dalam urutan menurun
            ->orderBy('rangking');     // Urutkan berdasarkan 'rangking' setelahnya

        if ($this->filter) {
            $query->where('alat_masters.nama', 'like', "{$this->filter}%");
        }

        if ($this->count) {
            $query->limit($this->count);
        }

        if ($this->search){
            $query->where('alat_masters.kode', 'like', "%{$this->search}%")
            ->orWhere('alat_masters.nama', 'like', "%{$this->search}%");
        }

        $wsm_result_normalisasi = $query->paginate(10);


        return view('components.table-w-s-m-hasil-show-component', compact('wsm_result_normalisasi'));
    }
}
