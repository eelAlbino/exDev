@extends('layouts.app')

@section('content')
    <div class="container mt-4">
        <h2>Список транзакций</h2>
        @include('transactions._filter', [
        	'filter' => $filter
        ])
        @include('transactions._table', [
        	'items' => $transactions,
        	'sortBy' => $sortBy,
        	'sortOrder' => $sortOrder
        ])
        <div class="d-flex justify-content-center">
            {{ $transactions->links('pagination::bootstrap-4') }}
        </div>
    </div>
@endsection
