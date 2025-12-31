<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Nouveau produit</title>
</head>
<body>
    <h1>Nouveau produit</h1>

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
            <label>Nom</label><br>
            <input name="name" value="{{ old('name') }}">
        </p>

        <p>
            <label>SKU (optionnel)</label><br>
            <input name="sku" value="{{ old('sku') }}">
        </p>

        <p>
            <label>Quantité</label><br>
            <input type="number" name="quantity" value="{{ old('quantity', 0) }}">
        </p>

        <p>
            <label>Seuil d’alerte</label><br>
            <input type="number" name="alert_threshold" value="{{ old('alert_threshold', 0) }}">
        </p>

        <button type="submit">Créer</button>
    </form>

    <p><a href="{{ route('products.index') }}">← Retour</a></p>
</body>
</html>
