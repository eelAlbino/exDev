<form action="{{ route('transactions.index') }}" method="GET" class="mb-3">
    <div class="form-row">
        <div class="form-group col-md-8">
            <label for="description">Фильтр по описанию:</label>
            <input type="text" name="filter[description]" id="description" class="form-control" value="{{ $filter['description'] ?? '' }}">
        </div>
        <div class="form-group col-md-4">
            <button type="submit" class="btn btn-primary mt-4">Применить</button>
        </div>
    </div>
</form>