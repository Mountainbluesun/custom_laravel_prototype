<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Produits</title>
</head>
<body>
    <h1>Produits</h1>
    <p>Total : {{ $products->count() }} produit(s)</p>

@if ($lowStock > 0)
    <p style="color:red">
        ⚠️ {{ $lowStock }} produit(s) sous le seuil d’alerte
    </p>
@endif


    <p><a href="{{ route('products.create') }}">+ Nouveau produit</a></p>

    @if (session('success'))
        <p style="color:green">{{ session('success') }}</p>
    @endif

    @if ($products->isEmpty())
        <p>Aucun produit pour l’instant.</p>
    @else
        <table border="1" cellpadding="6">
            <thead>
                <tr>
                    <th>Nom</th>
                    <th>SKU</th>
                    <th>Qté</th>
                    <th>Seuil</th>
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
                            <a href="{{ route('products.edit', $p) }}">Modifier</a>
                            <a href="{{ route('movements.create', $p) }}">Mouvement</a>
                            <a href="{{ route('movements.index', $p) }}">Historique</a>



                            <form action="{{ route('products.destroy', $p) }}" method="POST" style="display:inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" onclick="return confirm('Supprimer ?')">Supprimer</button>


                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</body>
</html>
