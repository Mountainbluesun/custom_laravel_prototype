    @php
    $nextDirDate = (request('sort') === 'created_at' && request('dir') === 'asc') ? 'desc' : 'asc';
    $nextDirQty  = (request('sort') === 'quantity' && request('dir') === 'asc') ? 'desc' : 'asc';
@endphp
<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Global history</title>
</head>
<body>
<h1>Global movement history</h1>


<form method="GET" action="{{ route('movements.global') }}" style="margin-bottom: 16px;">
    <label>Product:</label>
    <select name="product_id">
        <option value="">-- All --</option>
        @foreach ($products as $p)
            <option value="{{ $p->id }}" {{ request('product_id') == $p->id ? 'selected' : '' }}>
                {{ $p->name }}
            </option>
        @endforeach
    </select>

    <label style="margin-left: 12px;">Type:</label>
    <select name="type">
        <option value="">-- All --</option>
        <option value="in"  {{ request('type') === 'in' ? 'selected' : '' }}>In</option>
        <option value="out" {{ request('type') === 'out' ? 'selected' : '' }}>Out</option>
    </select>

    <label style="margin-left: 12px;">From:</label>
    <input type="date" name="date_from" value="{{ request('date_from') }}">

    <label style="margin-left: 12px;">To:</label>
    <input type="date" name="date_to" value="{{ request('date_to') }}">

    <button type="submit" style="margin-left: 12px;">Filter</button>

    <a href="{{ route('movements.global') }}" style="margin-left: 8px;">Reset</a>
    @can('export', \App\Models\StockMovement::class)
    <a href="{{ route('movements.export', request()->query()) }}" style="margin-left: 8px;">
        Export CSV
    </a>
    @endcan

</form>

@if ($movements->isEmpty())
    <p>No movements.</p>
@else
    <table border="1" cellpadding="6">
        <thead>
            <tr>
                <th><a href="{{ route('movements.global', array_merge(request()->query(), ['sort' => 'created_at', 'dir' => $nextDirDate])) }}">Date</a></th>
                <th>Product</th>
                <th>Type</th>
                <th><a href="{{ route('movements.global', array_merge(request()->query(), ['sort' => 'quantity', 'dir' => $nextDirQty])) }}">Quantity</a></th>
                <th>Stock after</th>
                <th>User</th>
                <th>Comment</th>
                <th>Link</th>
            </tr>
        </thead>
        <tbody>
        @foreach ($movements as $m)
            <tr>
                <td>{{ $m->created_at->format('Y-m-d H:i') }}</td>
                <td>{{ $m->product->name }}</td>
                <td>{{ $m->type === 'in' ? 'In' : 'Out' }}</td>
                <td>{{ $m->quantity }}</td>
                <td>{{ $m->stock_after }}</td>
                <td>{{ $m->user->name ?? '—' }}</td>
                <td>{{ $m->comment ?? '-' }}</td>
                <td>
                    <a href="{{ route('movements.index', $m->product) }}">Product history</a>
                </td>


            </tr>
        @endforeach
        </tbody>
    </table>

    <div style="margin-top: 12px;">
        {{ $movements->links() }}
    </div>
@endif

<p style="margin-top: 16px;">
    <a href="{{ route('products.index') }}">← Back to products</a>
</p>
</body>
</html>
