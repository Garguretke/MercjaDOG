@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <nav class="navbar navbar-expand-md navbar-dark-subtle">
                        <div class="collapse navbar-collapse" id="navbarSupportedContent">
                            <ul class="navbar-nav me-auto">
                                @if (Route::has('emeal.get-index'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('emeal.get-index') }}">{{ __('eMeal') }}</a>
                                </li>
                                @endif
                                @if (Route::has('emeal.products'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('emeal.products') }}">{{ __('Produkty') }}</a>
                                </li>
                                @endif

                                @if (Route::has('emeal.recipes'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('emeal.recipes') }}">{{ __('Przepisy') }}</a>
                                </li>
                                @endif
                            </ul>
                        </div>
                    </nav>
                </div>        

                <div class="card-body mb-3">
                    <h1>Dodaj Nowy Przepis</h1>
                    <form method="POST" action="{{ route('emeal.recipes-store') }}">
                        @csrf
                        <div class="form-group mb-3">
                            <label for="name">Nazwa Przepisu:</label>
                            <input type="text" name="name" class="form-control" required>
                        </div>
                        <div class="form-group mb-3">
                            <label for="description">Opis:</label>
                            <textarea name="description" class="form-control"></textarea>
                        </div>

                        <!-- Tutaj przekazujemy dane JavaScript do przycisku modalu -->
                        {{-- <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addProductModal" data-recipe="{{ $recipe->id }}">
                            Dodaj Produkt do Przepisu
                        </button> --}}

                        {{-- @include('emeal.recipes-modal-add') --}}

                        <button type="submit" class="btn btn-primary">Dodaj Przepis</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
