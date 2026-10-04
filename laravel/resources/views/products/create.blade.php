<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>New product</title>
</head>
<body>
    <h1>New product</h1>

    @if ($errors->any())
        <ul style="color:red">
            @foreach ($errors->all() as $e)
                <li>{{ $e }}</li>
            @endforeach
        </ul>
    @endif

    <form method="POST" action="{{ route('products.store') }}">
        @csrf

        <p>
            <label>Name</label><br>
            <input name="name" value="{{ old('name') }}">
        </p>

        <p>
            <label>SKU (optional)</label><br>
            <input name="sku" value="{{ old('sku') }}">
        </p>

        <p>
            <label>Quantity</label><br>
            <input type="number" name="quantity" value="{{ old('quantity', 0) }}">
        </p>

        <p>
            <label>Alert threshold</label><br>
            <input type="number" name="alert_threshold" value="{{ old('alert_threshold', 0) }}">
        </p>

        <button type="submit">Create</button>
    </form>

    <p><a href="{{ route('products.index') }}">← Back</a></p>
</body>
</html>
