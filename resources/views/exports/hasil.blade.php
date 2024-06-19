<div class="header">
    <h1><strong>Sistem Pendukung Keputusan Pelindo Terminal Petikemas</strong></h1>
    <h2><strong>Hasil Rekomendari Perbaikan Alat</strong></h2>
    <h3>Tanggal Ekspor: {{ \Carbon\Carbon::now()->format('d-m-Y') }}</h3>
</div>
<table>
    <thead>
        <tr>
            <th>No</th>
            <th>Kode</th>
            <th>Nama</th>
            <th>Utilisasi</th>
            <th>Availability</th>
            <th>Reliability</th>
            <th>Idle</th>
            <th>Jam Tersedia</th>
            <th>Jam Operasi</th>
            <th>Jam Bda</th>
            <th>Jumlah Bda</th>
            <th>Hasil</th>
            <!-- <th>Rangking</th> -->
        </tr>
    </thead>
    <tbody>
        @foreach ($results as $result)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $result->alatMaster->kode }}</td>
                <td>{{ $result->alatMaster->nama }}</td>
                <td>{{ $result->utilisasi }}</td>
                <td>{{ $result->availability }}</td>
                <td>{{ $result->reliability }}</td>
                <td>{{ $result->idle }}</td>
                <td>{{ $result->jam_tersedia }}</td>
                <td>{{ $result->jam_operasi }}</td>
                <td>{{ $result->jam_bda }}</td>
                <td>{{ $result->jumlah_bda }}</td>
                <td>{{ $result->hasil }}</td>
                <!-- <td>{{ $result->rangking }}</td> -->
            </tr>
        @endforeach
    </tbody>
</table>
