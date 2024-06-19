@extends('layouts.app')

@section('title', 'Master Alat')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light"></span>Master Alat</h4>
        <div class="card">
            <div class="card-header" style="margin-bottom: 0px!important; padding-bottom: 0px!important">
                <h4>Master Alat</h4>
                @if (in_array(request()->user()->role, ['admin', 'teknik']))
                    <div class="d-flex gap-2 align-items-center">
                        <form action="{{ route('Alat-Master::index') }}" method="get"
                            class="d-flex align-items-center gap-2">
                            <input type="text" name="search" class="form-control" placeholder="Cari Alat..."
                                value="{{ request()->query('search') }}">
                            <button class="btn btn-outline-primary" type="submit">Cari</button>
                        </form>
                    </div>
                @endif
            </div>
            @if (in_array(request()->user()->role, ['admin', 'teknik']))
                <div class="card-header">
                    <a href="{{ route('Alat-Master::create') }}" class="btn btn-primary">+ Tambah Alat</a>
                </div>
            @endif
            <div class="card-body">
                <x-SessionAlertComponent />

                <div class="table-responsive text-nowrap">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>No.</th>
                                <th>Kode Alat</th>
                                <th>Nama Alat</th>
                                @if (in_array(request()->user()->role, ['admin', 'teknik']))
                                    <th>Opsi</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody class="table-border-bottom-0">
                            @php
                                $indexing = ($alatMaster->currentPage() - 1) * $alatMaster->perPage() + 1;
                            @endphp

                            @foreach ($alatMaster as $alatmaster)
                                <tr>
                                    <td>{{ $indexing }}</td>

                                    <td>{{ $alatmaster->kode }}</td>
                                    <td>{{ $alatmaster->nama }}</td>

                                    @if (in_array(request()->user()->role, ['admin', 'teknik']))
                                        <td>
                                            <form action="{{ route('Alat-Master::destroy', $alatmaster->id) }}"
                                                method="post" class="d-inline" id="deleteForm{{ $alatmaster->id }}">
                                                <a href="{{ route('Alat-Master::edit', $alatmaster->id) }}"
                                                    class="btn btn-outline-info" data-bs-toggle="tooltip"
                                                    data-bs-offset="0,4" data-bs-placement="top" data-bs-html="true"
                                                    data-bs-original-title="<span>Edit</span>">
                                                    <i class="bx bx-edit-alt me-1"></i>
                                                </a>
                                                @csrf
                                                @method('DELETE')
                                                <button type="button" name="delete" class="btn btn-outline-danger"
                                                    data-bs-toggle="tooltip" data-bs-offset="0,4" data-bs-placement="top"
                                                    data-bs-html="true" data-bs-original-title="<span>Delete</span>"
                                                    onclick="showDeleteConfirmationModal('{{ $alatmaster->id }}')">
                                                    <i class="bx bx-trash me-1"></i>
                                                </button>
                                            </form>
                                        </td>
                                    @endif
                                    @php
                                        $indexing++;
                                    @endphp
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="d-flex justify-content-center mt-3">
                    {{ $alatMaster->links('pagination::bootstrap-4') }}
                </div>
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
    function showDeleteConfirmationModal(token) {
        var deleteForm = document.getElementById('deleteForm' + token);
        if (deleteForm) {
            $('#deleteConfirmationModal').modal('show');
            document.getElementById('confirmDeleteButton').onclick = function() {
                deleteData(token);
            };
        }
    }

    function deleteData(token) {
        var deleteForm = document.getElementById('deleteForm' + token);
        if (deleteForm) {
            deleteForm.submit();
        }
    }
</script>
