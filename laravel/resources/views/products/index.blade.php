<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Products</title>
</head>
<body>
    <h1>Products</h1>
    <p>Total: {{ $products->count() }} product(s)</p>

@if ($lowStock > 0)
    <p style="color:red">
        ⚠️ {{ $lowStock }} product(s) below the alert threshold
    </p>
@endif


    <p><a href="{{ route('products.create') }}">+ New product</a></p>

    @if (session('success'))
        <p style="color:green">{{ session('success') }}</p>
    @endif

    @if ($products->isEmpty())
        <p>No products yet.</p>
    @else
        <table border="1" cellpadding="6">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>SKU</th>
                    <th>Qty</th>
                    <th>Threshold</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($products as $p)
                    <tr>
                        <td>{{ $p->name }}</td>
                        <td>{{ $p->sku ?? '-' }}</td>
                        <td>{{ $p->quantity }}</td>
                        <td>{{ $p->alert_threshold }}</td>
                        <td>
                            <a href="{{ route('products.edit', $p) }}">Edit</a>
                            <a href="{{ route('movements.create', $p) }}">Movement</a>
                            <a href="{{ route('movements.index', $p) }}">History</a>



                            <form action="{{ route('products.destroy', $p) }}" method="POST" style="display:inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" onclick="return confirm('Delete?')">Delete</button>


                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</body>
</html>
