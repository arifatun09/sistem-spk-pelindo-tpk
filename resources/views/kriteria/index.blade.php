@extends('layouts.app')

@section('title', 'Kriteria')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light"></span>Kriteria</h4>
        <div class="card">
            <div class="card-header" style="margin-bottom: 0px!important; padding-bottom: 0px!important">
                <h4>Data Kriteria</h4>
                <div class="d-flex align-items-center gap-2">
                    <form action="" method="get" class="d-flex align-items-center gap-2">
                        <input type="text" name="search" class="form-control" placeholder="Cari Alat..."
                            value="{{ request()->query('search') }}">
                        <button class="btn btn-outline-primary" type="submit">Cari</button>
                    </form>

                </div>
            </div>
            <div class="card-header">
                @if (request()->user()->role == 'admin')
                    <a href="{{ route('Kriteria::create') }}" class="btn btn-primary">+ Tambah Data</a>
                @endif
            </div>
            <div class="card-body">
                @if (session('success'))
                    <div class="alert alert-success alert-dismissible" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
                @if (count($kriteria) > 0)
                    <div class="table-responsive text-nowrap">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama Kriteria</th>
                                    <th>Jenis Kriteria</th>
                                    @if (request()->user()->role == 'admin')
                                        <th>Opsi</th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($kriteria as $k)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $k->name }}</td>
                                        <td>{{ $k->jenis }}</td>
                                        @if (request()->user()->role == 'admin')
                                            <td>
                                                <form action="{{ route('Kriteria::destroy', $k->id) }}" method="post"
                                                    class="d-inline" id="deleteForm{{ $k->id }}">
                                                    <a href="{{ route('Kriteria::edit', $k->id) }}"
                                                        class="btn btn-outline-info" data-bs-toggle="tooltip"
                                                        data-bs-offset="0,4" data-bs-placement="top" data-bs-html="true"
                                                        data-bs-original-title="<span>Edit</span>"><i
                                                            class="bx bx-edit-alt me-1"></i></a>
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="button" name="delete" class="btn btn-outline-danger"
                                                        data-bs-toggle="tooltip" data-bs-offset="0,4"
                                                        data-bs-placement="top" data-bs-html="true"
                                                        data-bs-original-title="<span>Delete</span>"
                                                        onclick="showDeleteConfirmationModal('{{ $k->id }}')">
                                                        <i class="bx bx-trash me-1"></i>
                                                    </button>
                                                </form>
                                            </td>
                                        @endif
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="d-flex justify-content-center mt-3">
                        {{ $kriteria->links('pagination::bootstrap-4') }}
                    </div>
                @else
                    <p>Belum ada data kriteria yang dimasukkan. Silahkan lakukan input data kriteria.</p>
                @endif
            </div>
        </div>
    </div>
    @include('layouts.delete-confirm')
@endsection

<style>
    .card-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
</style>

<script>
    function showDeleteConfirmationModal(formId) {
        var deleteForm = document.getElementById('deleteForm' + formId);
        if (deleteForm) {
            $('#deleteConfirmationModal').modal('show');
            document.getElementById('confirmDeleteButton').onclick = function() {
                deleteData(formId);
            };
        }
    }

    function deleteData(formId) {
        var deleteForm = document.getElementById('deleteForm' + formId);
        if (deleteForm) {
            deleteForm.submit();
        }
    }
</script>
