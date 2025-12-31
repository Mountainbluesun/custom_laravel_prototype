<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Mouvement</title>
</head>
<body>
<h1>Mouvement – {{ $product->name }}</h1>
<p>Stock actuel : <strong>{{ $product->quantity }}</strong></p>

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
            <option value="in" {{ old('type') === 'in' ? 'selected' : '' }}>Entrée</option>
            <option value="out" {{ old('type') === 'out' ? 'selected' : '' }}>Sortie</option>
        </select>
    </p>

    <p>
        <label>Quantité</label><br>
        <input type="number" name="quantity" min="1" value="{{ old('quantity', 1) }}">
    </p>

    <p>
        <label>Commentaire</label><br>
        <input name="comment" value="{{ old('comment') }}">
    </p>

    <button type="submit">Valider</button>
</form>

<p><a href="{{ route('products.index') }}">← Retour</a></p>
</body>
</html>
