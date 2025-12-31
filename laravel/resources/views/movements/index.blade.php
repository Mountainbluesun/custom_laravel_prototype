<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Historique</title>
</head>
<body>
<h1>Historique – {{ $product->name }}</h1>

<p>Stock actuel : <strong>{{ $product->quantity }}</strong></p>

<p>
    <a href="{{ route('movements.create', $product) }}">+ Nouveau mouvement</a> |
    <a href="{{ route('products.index') }}">← Retour produits</a>
</p>

@if ($movements->isEmpty())
    <p>Aucun mouvement enregistré.</p>
@else
    <table border="1" cellpadding="6">
        <thead>
            <tr>
                <th>Date</th>
                <th>Type</th>
                <th>Quantité</th>
                <th>Après stock</th>
                <th>Utilisateur</th>
                <th>Commentaire</th>


            </tr>
        </thead>
        <tbody>
        @foreach ($movements as $m)
            <tr>
                <td>{{ $m->created_at->format('Y-m-d H:i') }}</td>
                <td>{{ $m->type === 'in' ? 'Entrée' : 'Sortie' }}</td>
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
