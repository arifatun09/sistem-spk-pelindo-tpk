@extends('layouts.app')

@section('title', 'Edit Master Alat')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Master Alat / </span>Edit Master Alat</h4>
        <div class="col-xl-12">
            <div class="card mb-4">
                <h5 class="card-header">Masukkan data master alat</h5>
                <form action="{{ route('Alat-Master::update', $alatMaster->id) }}" method="post"
                    enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="name" class="col-md-2 col-form-label">Nama Alat</label>
                            <div class="col-md-12">
                                <input name="name" class="form-control @error('name') is-invalid @enderror"
                                    type="text" id="name" placeholder="Masukkan nama alat master"
                                    value="{{ $alatMaster->nama }}" />
                                @error('name')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="kode" class="col-md-2 col-form-label">Kode Alat</label>
                            <div class="col-md-12">
                                <input name="kode" class="form-control @error('kode') is-invalid @enderror"
                                    type="text" id="kode" placeholder="Masukkan kode alat master"
                                    value="{{ $alatMaster->kode }}" />
                                @error('kode')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                        <a href="{{ route('Alat-Master::index') }}" class="btn btn-secondary">Kembali</a>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
