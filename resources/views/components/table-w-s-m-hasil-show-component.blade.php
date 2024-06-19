<div class="table-responsive text-nowrap">
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>No</th>
                <th>Kode</th>
                <th>Nama alat</th>
                {{-- <th>Utilisasi</th>
                <th>Availability</th>
                <th>Reliability</th>
                <th>Idle</th>
                <th>Jam Tersedia</th>
                <th>Jam Operasi</th>
                <th>Jam BDA</th>
                <th>Jumlah BDA</th> --}}
                <th>Hasil</th>
                {{-- <th>Rangking</th> --}}
            </tr>
        </thead>
        <tbody>
            @php
            $indexing = ($wsm_result_normalisasi->currentPage() - 1) * $wsm_result_normalisasi->perPage() + 1;
            @endphp
            @foreach ($wsm_result_normalisasi as $_wsm_result_normalisasi)
            <tr>
                <td>{{ $indexing }}</td>
                <td>{{ $_wsm_result_normalisasi->alatMaster->kode }}</td>
                <td>{{ $_wsm_result_normalisasi->alatMaster->nama }}</td>
                {{-- <td>{{ $_wsm_result_normalisasi->utilisasi }}</td>
                <td>{{ $_wsm_result_normalisasi->availability }}</td>
                <td>{{ $_wsm_result_normalisasi->reliability }}</td>
                <td>{{ $_wsm_result_normalisasi->jam_operasi }}</td>
                <td>{{ $_wsm_result_normalisasi->idle }}</td>
                <td>{{ $_wsm_result_normalisasi->jam_tersedia }}</td>
                <td>{{ $_wsm_result_normalisasi->jam_bda }}</td>
                <td>{{ $_wsm_result_normalisasi->jumlah_bda }}</td> --}}
                <td>{{ $_wsm_result_normalisasi->hasil }}</td>
                {{-- <td>{{ $_wsm_result_normalisasi->rangking }}</td> --}}
                @php
                $indexing++;
                @endphp
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="d-flex justify-content-center mt-3">
        {{ $wsm_result_normalisasi->appends([
                'filter' => request()->get('filter'),
                'search' => request()->get('search'),
                'name' => 'wsm', // tetap menambahkan parameter 'name' secara manual
            ])->fragment('wsm')->links('pagination::bootstrap-4') }}
    </div>
</div>