@extends('layouts.app')

@section('title', 'Nilai Kriteria')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light"></span>Nilai Kriteria</h4>
        <div class="card">
            <div class="card-header">
                <h4>Perbandingan Berpasangan</h4>
                @if (count($cekUser) < 1)
                    <a href="{{ route('Bobot::create') }}" class="btn btn-primary">+ Tambah Nilai</a>
                @endif
            </div>
            <div class="card-body">
                <x-SessionAlertComponent />
            </div>
        </div>

        {{-- @foreach ($bobot as $user_id => $grouped)
            @foreach ($grouped as $token => $_bobot)
                <x-BobotShowComponent :userId="$user_id" :token="$token" :bobot="$_bobot" :gmmCriteria="$gmm_criteria" />
            @endforeach
        @endforeach --}}

        {{-- <div id="carouselExample" class="carousel slide" data-bs-ride="carousel">
            <div class="carousel-indicators">
                <button type="button" data-bs-target="#carouselExample" data-bs-slide-to="0" class=""
                    aria-label="Slide 1"></button>
                <button type="button" data-bs-target="#carouselExample" data-bs-slide-to="1" aria-label="Slide 2"
                    class="active" aria-current="true"></button>
                <button type="button" data-bs-target="#carouselExample" data-bs-slide-to="2" aria-label="Slide 3"
                    class=""></button>
            </div>
            <div class="carousel-inner">
                <div class="carousel-item">
                    <img class="d-block w-100" src="../assets/img/elements/13.jpg" alt="First slide">
                    <div class="carousel-caption d-none d-md-block">
                        <h3>First slide</h3>
                        <p>Eos mutat malis maluisset et, agam ancillae quo te, in vim congue pertinacia.</p>
                    </div>
                </div>
                <div class="carousel-item active">
                    <img class="d-block w-100" src="../assets/img/elements/2.jpg" alt="Second slide">
                    <div class="carousel-caption d-none d-md-block">
                        <h3>Second slide</h3>
                        <p>In numquam omittam sea.</p>
                    </div>
                </div>
                <div class="carousel-item">
                    <img class="d-block w-100" src="../assets/img/elements/18.jpg" alt="Third slide">
                    <div class="carousel-caption d-none d-md-block">
                        <h3>Third slide</h3>
                        <p>Lorem ipsum dolor sit amet, virtute consequat ea qui, minim graeco mel no.</p>
                    </div>
                </div>
            </div>
            <a class="carousel-control-prev" href="#carouselExample" role="button" data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Previous</span>
            </a>
            <a class="carousel-control-next" href="#carouselExample" role="button" data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Next</span>
            </a>
        </div> --}}

        <div id="carouselExample" class="carousel slide carousel-dark " data-bs-ride="carousel">
            {{-- <div class="carousel-indicators">
                @foreach ($bobot as $user_id => $grouped)
                    @foreach ($grouped as $index => $_bobot)
                        <button type="button" data-bs-target="#carouselExample" data-bs-slide-to="{{ $index }}"
                            class="" aria-label="Slide {{ $index }}"></button>
                    @endforeach
                @endforeach
            </div> --}}
            <div class="carousel-inner">
                @php $isFirst = true; @endphp
                @foreach ($bobot as $user_id => $grouped)
                    @foreach ($grouped as $token => $_bobot)
                        <div class="carousel-item {{ $isFirst ? 'active' : '' }}">
                            <x-BobotShowComponent :userId="$user_id" :token="$token" :bobot="$_bobot" :gmmCriteria="$gmm_criteria" />
                            {{-- <img class="d-block w-100" src="../assets/img/elements/18.jpg" alt="Third slide">
                            <div class="carousel-caption d-none d-md-block">
                                <h3>Third slide</h3>
                                <p>Lorem ipsum dolor sit amet, virtute consequat ea qui, minim graeco mel no.</p>
                            </div> --}}
                            @php $isFirst = false; @endphp
                        </div>
                    @endforeach
                @endforeach
            </div>
            <a class="carousel-control-prev" href="#carouselExample" role="button" data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Previous</span>
            </a>
            <a class="carousel-control-next" href="#carouselExample" role="button" data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Next</span>
            </a>
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
