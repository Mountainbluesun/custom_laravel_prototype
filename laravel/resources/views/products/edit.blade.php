<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Edit product</title>
</head>
<body>
    <h1>Edit product</h1>

    @if ($errors->any())
        <ul style="color:red">
            @foreach ($errors->all() as $e)
                <li>{{ $e }}</li>
            @endforeach
        </ul>
    @endif

    <form method="POST" action="{{ route('products.update', $product) }}">
        @csrf
        @method('PUT')

        <p>
            <label>Name</label><br>
            <input name="name" value="{{ old('name', $product->name) }}">
        </p>

        <p>
            <label>SKU (optional)</label><br>
            <input name="sku" value="{{ old('sku', $product->sku) }}">
        </p>

        <p>
            <label>Quantity</label><br>
            <input type="number" name="quantity" value="{{ old('quantity', $product->quantity) }}">
        </p>

        <p>
            <label>Alert threshold</label><br>
            <input type="number" name="alert_threshold" value="{{ old('alert_threshold', $product->alert_threshold) }}">
        </p>

        <button type="submit">Save</button>
    </form>

    <p><a href="{{ route('products.index') }}">← Back</a></p>
</body>
</html>
