<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Historique global</title>
</head>
<body>
<h1>Historique global des mouvements</h1>


<form method="GET" action="{{ route('movements.global') }}" style="margin-bottom: 16px;">
    <label>Produit:</label>
    <select name="product_id">
        <option value="">-- Tous --</option>
        @foreach ($products as $p)
            <option value="{{ $p->id }}" {{ request('product_id') == $p->id ? 'selected' : '' }}>
                {{ $p->name }}
            </option>
        @endforeach
    </select>

    <label style="margin-left: 12px;">Type:</label>
    <select name="type">
        <option value="">-- Tous --</option>
        <option value="in"  {{ request('type') === 'in' ? 'selected' : '' }}>Entrée</option>
        <option value="out" {{ request('type') === 'out' ? 'selected' : '' }}>Sortie</option>
    </select>

    @php
    $nextDirDate = (request('sort') === 'created_at' && request('dir') === 'asc') ? 'desc' : 'asc';
    $nextDirQty  = (request('sort') === 'quantity' && request('dir') === 'asc') ? 'desc' : 'asc';
@endphp

<thead>
<tr>
    <th>
        <a href="{{ route('movements.global', array_merge(request()->query(), ['sort' => 'created_at', 'dir' => $nextDirDate])) }}">
            Date
        </a>
    </th>

    <th>Produit</th>
    <th>Type</th>
    <th>
        <a href="{{ route('movements.global', array_merge(request()->query(), ['sort' => 'quantity', 'dir' => $nextDirQty])) }}">
            Quantité
        </a>
    </th>
    <th>Commentaire</th>
    <th>Lien</th>
</tr>
</thead>

    <label style="margin-left: 12px;">Du:</label>
    <input type="date" name="date_from" value="{{ request('date_from') }}">

    <label style="margin-left: 12px;">Au:</label>
    <input type="date" name="date_to" value="{{ request('date_to') }}">

    <button type="submit" style="margin-left: 12px;">Filtrer</button>

    <a href="{{ route('movements.global') }}" style="margin-left: 8px;">Réinitialiser</a>
    @can('export', \App\Models\StockMovement::class)
    <a href="{{ route('movements.export', request()->query()) }}" style="margin-left: 8px;">
        Exporter CSV
    </a>

</form>

@if ($movements->isEmpty())
    <p>Aucun mouvement.</p>
@else
    <table border="1" cellpadding="6">
        <thead>
            <tr>
                <th>Date</th>
                <th>Produit</th>
                <th>Type</th>
                <th>Quantité</th>
                <th>Après stock</th>
                <th>Utilisateur</th>
                <th>Commentaire</th>
                <th>Lien</th>
            </tr>
        </thead>
        <tbody>
        @foreach ($movements as $m)
            <tr>
                <td>{{ $m->created_at->format('Y-m-d H:i') }}</td>
                <td>{{ $m->product->name }}</td>
                <td>{{ $m->type === 'in' ? 'Entrée' : 'Sortie' }}</td>
                <td>{{ $m->quantity }}</td>
                <td>{{ $m->stock_after }}</td>
                <td>{{ $m->user->name ?? '—' }}</td>
                <td>{{ $m->comment ?? '-' }}</td>
                <td>
                    <a href="{{ route('movements.index', $m->product) }}">Historique produit</a>
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
    <a href="{{ route('products.index') }}">← Retour produits</a>
</p>
@endif
</body>
</html>
