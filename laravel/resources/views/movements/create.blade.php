<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Movement</title>
</head>
<body>
<h1>Movement – {{ $product->name }}</h1>
<p>Current stock: <strong>{{ $product->quantity }}</strong></p>

@if ($errors->any())
    <ul style="color:red">
        @foreach ($errors->all() as $e)
            <li>{{ $e }}</li>
        @endforeach
    </ul>
@endif

<form method="POST" action="{{ route('movements.store', $product) }}">
    @csrf

    <p>
        <label>Type</label><br>
        <select name="type">
            <option value="in" {{ old('type') === 'in' ? 'selected' : '' }}>In</option>
            <option value="out" {{ old('type') === 'out' ? 'selected' : '' }}>Out</option>
        </select>
    </p>

    <p>
        <label>Quantity</label><br>
        <input type="number" name="quantity" min="1" value="{{ old('quantity', 1) }}">
    </p>

    <p>
        <label>Comment</label><br>
        <input name="comment" value="{{ old('comment') }}">
    </p>

    <button type="submit">Submit</button>
</form>

<p><a href="{{ route('products.index') }}">← Back</a></p>
</body>
</html>
