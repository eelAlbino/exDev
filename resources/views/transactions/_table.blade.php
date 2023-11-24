<table class="table table-striped">
    <thead>
        <tr>
            <th scope="col"><a href="{{ route('transactions.index', ['sort_by' => 'id', 'sort_order' => $sortBy == 'id' && $sortOrder == 'asc' ? 'desc' : 'asc']) }}">ID</a></th>
            <th scope="col"><a href="{{ route('transactions.index', ['sort_by' => 'created_at', 'sort_order' => $sortBy == 'created_at' && $sortOrder == 'asc' ? 'desc' : 'asc']) }}">Дата создания</a></th>
            <th scope="col"><a href="{{ route('transactions.index', ['sort_by' => 'type', 'sort_order' => $sortBy == 'type' && $sortOrder == 'asc' ? 'desc' : 'asc']) }}">Тип транзакции</a></th>
            <th scope="col"><a href="{{ route('transactions.index', ['sort_by' => 'amount', 'sort_order' => $sortBy == 'amount' && $sortOrder == 'asc' ? 'desc' : 'asc']) }}">Сумма</a></th>
            <th scope="col">Описание</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($items as $item)
            <tr>
                <th scope="row">{{ $item->id }}</th>
                <td>{{ $item->created_at }}</td>
                <td>{{ $item->type }}</td>
                <td>{{ $item->amount }}</td>
                <td>{{ $item->description }}</td>
            </tr>
        @endforeach
    </tbody>
</table>