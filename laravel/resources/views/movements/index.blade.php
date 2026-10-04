<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>History</title>
</head>
<body>
<h1>History – {{ $product->name }}</h1>

<p>Current stock: <strong>{{ $product->quantity }}</strong></p>

<p>
    <a href="{{ route('movements.create', $product) }}">+ New movement</a> |
    <a href="{{ route('products.index') }}">← Back to products</a>
</p>

@if ($movements->isEmpty())
    <p>No movements recorded.</p>
@else
    <table border="1" cellpadding="6">
        <thead>
            <tr>
                <th>Date</th>
                <th>Type</th>
                <th>Quantity</th>
                <th>Stock after</th>
                <th>User</th>
                <th>Comment</th>


            </tr>
        </thead>
        <tbody>
        @foreach ($movements as $m)
            <tr>
                <td>{{ $m->created_at->format('Y-m-d H:i') }}</td>
                <td>{{ $m->type === 'in' ? 'In' : 'Out' }}</td>
                <td>{{ $m->quantity }}</td>
                <td>{{ $m->stock_after }}</td>
                <td>{{ $m->user->name ?? '—' }}</td>
                <td>{{ $m->comment ?? '-' }}</td>


            </tr>
        @endforeach
        </tbody>
    </table>
@endif
</body>
</html>
