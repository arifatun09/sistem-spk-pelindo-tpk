@extends('layouts.app')

@section('title', 'Alat Berat')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light"></span>Alat Berat</h4>
    @if (request()->user()->role == 'admin')
    <div class="card">
        <div class="card-header">
            <h4>Form Import Data</h4>
            <form action="{{ route('Alat::import') }}" method="post" enctype="multipart/form-data">
                @csrf
                <div class="input-group">
                    <input type="file" name="file" class="form-control" id="inputGroupFile04" aria-describedby="inputGroupFileAddon04" aria-label="Upload" required="required">
                    <button class="btn btn-outline-primary" type="submit" id="inputGroupFileAddon04">Import</button>
                </div>
                @if ($errors->has('file'))
                <div class="alert alert-danger mt-2">
                    {{ $errors->first('file') }}
                </div>
                @endif
            </form>
        </div>
    </div>
    @endif

    <div class="card mt-3">
        <div class="card-header">
            <div class="d-flex justify-content-between">
                <h4>Data Alat Berat</h4>

                <form action="" method="get" class="d-flex align-items-center gap-2">
                    <div class="input-group">
                        <input type="month" class="form-control" name="month" value="{{ request('month') }}">
                    </div>
                    <input type="text" name="search" class="form-control" placeholder="Cari Alat..." value="{{ request()->query('search') }}">
                    <button class="btn btn-outline-primary" type="submit">Cari</button>
                </form>
            </div>
        </div>
        <div class="card-body">
            @if (session('success'))
            <div class="alert alert-success alert-dismissible" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            @endif
            @if (count($alat) > 0)
            <div class="table-responsive">
                <table class="table table-bordered" width="100%">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Kode</th>
                            <th>Nama alat</th>
                            <th>Utilisasi</th>
                            <th>Availability</th>
                            <th>Reliability</th>
                            <th>Idle</th>
                            <th>Jam Tersedia</th>
                            <th>Jam Operasi</th>
                            <th>Jam BDA</th>
                            <th>Jumlah BDA</th>
                            <!-- <th width="10%">Update Pada</th> -->
                        </tr>
                    </thead>
                    <tbody>
                        @php
                        $indexing = ($alat->currentPage() - 1) * $alat->perPage() + 1;
                        @endphp
                        @foreach ($alat as $a)
                        <tr>
                            <td>{{ $indexing }}</td>
                            <td>{{ $a->alatMaster->kode }}</td>
                            <td>{{ $a->alatMaster->nama }}</td>
                            <td>{{ $a->utilisasi }}</td>
                            <td>{{ $a->availability }}</td>
                            <td>{{ $a->reliability }}</td>
                            <td>{{ $a->idle }}</td>
                            <td>{{ $a->jam_tersedia }}</td>
                            <td>{{ $a->jam_operasi }}</td>
                            <td>{{ $a->jam_bda }}</td>
                            <td>{{ $a->jumlah_bda }}</td>
                            @php
                            $indexing++;
                            @endphp
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="d-flex justify-content-center mt-3">
                {{ $alat->links('pagination::bootstrap-4') }}
            </div>
            @else
            <p>Belum ada data alat berat yang dimasukkan. Silahkan Admin melakukan import data terlebih dahulu.</p>
            @endif
        </div>
    </div>
</div>
@endsection