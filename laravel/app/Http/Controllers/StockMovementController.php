<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\StockMovement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StockMovementController extends Controller
{
    public function store(Request $request, Product $product)
{
    $data = $request->validate([
        'type' => ['required', 'in:in,out'],
        'quantity' => ['required', 'integer', 'min:1'],
        'comment' => ['nullable', 'string', 'max:255'],
    ]);

    if (!auth()->check()) {
        abort(403);
    }
    return DB::transaction(function () use ($data, $product) {

        // Reload and lock the product row (prevents double-click / concurrent updates)
        $product = Product::whereKey($product->id)->lockForUpdate()->first();

        $qty = (int) $data['quantity'];

        // Reject an outgoing movement larger than the current stock
        if ($data['type'] === 'out' && $qty > $product->quantity) {
            return back()
                ->withErrors(['quantity' => 'Insufficient stock for this outgoing movement.'])
                ->withInput();
        }

        // Compute the stock after the movement
        $newStock = $data['type'] === 'in'
            ? $product->quantity + $qty
            : $product->quantity - $qty;

        // Update stock
        $product->update(['quantity' => $newStock]);

        // Audit trail
        StockMovement::create([
            'product_id'  => $product->id,
            'user_id'     => auth()->id(),
            'type'        => $data['type'],
            'quantity'    => $qty,
            'stock_after' => $newStock,
            'comment'     => $data['comment'] ?? null,
        ]);

        return redirect()->route('products.index')
            ->with('success', 'Movement recorded ✅');
    });
}

    public function index(Product $product)
    {
        $movements = $product->movements()
            ->orderBy('created_at', 'desc')
            ->get();

        // $query = StockMovement::query()->with(['product', 'user']);

        return view('movements.index', compact('product', 'movements'));
    }

    public function global(Request $request)
    {
        $filters = $request->validate([
            'product_id' => ['nullable', 'integer', 'exists:products,id'],
            'type' => ['nullable', 'in:in,out'],
            'date_from' => ['nullable', 'date'],
            'date_to' => ['nullable', 'date'],
        ]);


        $query = StockMovement::query()->with(['product', 'user']);


        if (!empty($filters['product_id'])) {
            $query->where('product_id', $filters['product_id']);
        }

        if (!empty($filters['type'])) {
            $query->where('type', $filters['type']);
        }

        if (!empty($filters['date_from'])) {
            $query->whereDate('created_at', '>=', $filters['date_from']);
        }

        if (!empty($filters['date_to'])) {
            $query->whereDate('created_at', '<=', $filters['date_to']);
        }

        $sort = $request->get('sort', 'created_at');
        $dir  = $request->get('dir', 'desc');

        if (!in_array($sort, ['created_at', 'quantity'], true)) {
            $sort = 'created_at';
        }

        if (!in_array($dir, ['asc', 'desc'], true)) {
            $dir = 'desc';
        }

        $movements = $query
            ->orderBy($sort, $dir)
            ->paginate(15)
            ->withQueryString();

        $products = Product::orderBy('name')->get(['id', 'name']);

        return view('movements.global', compact('movements', 'products', 'sort', 'dir'));
    }



    public function export(Request $request)
    {
    // Same validation as the global page
    $filters = $request->validate([
        'product_id' => ['nullable', 'integer', 'exists:products,id'],
        'type' => ['nullable', 'in:in,out'],
        'date_from' => ['nullable', 'date'],
        'date_to' => ['nullable', 'date'],
        'sort' => ['nullable', 'in:created_at,quantity'],
        'dir' => ['nullable', 'in:asc,desc'],
    ]);

    $query = StockMovement::query()->with('product');

    if (!empty($filters['product_id'])) {
        $query->where('product_id', $filters['product_id']);
    }
    if (!empty($filters['type'])) {
        $query->where('type', $filters['type']);
    }
    if (!empty($filters['date_from'])) {
        $query->whereDate('created_at', '>=', $filters['date_from']);
    }
    if (!empty($filters['date_to'])) {
        $query->whereDate('created_at', '<=', $filters['date_to']);
    }

    $sort = $filters['sort'] ?? 'created_at';
    $dir  = $filters['dir'] ?? 'desc';

    $filename = 'stock_movements_' . now()->format('Ymd_His') . '.csv';

    $headers = [
        'Content-Type' => 'text/csv; charset=UTF-8',
        'Content-Disposition' => "attachment; filename=\"$filename\"",
    ];

    return response()->streamDownload(function () use ($query, $sort, $dir) {
        $out = fopen('php://output', 'w');

        // UTF-8 BOM (useful for Excel)
        fprintf($out, chr(0xEF).chr(0xBB).chr(0xBF));

        // CSV headers
        fputcsv($out, ['date', 'product', 'type', 'quantity', 'stock_after', 'comment']);

        $query->orderBy($sort, $dir)
            ->chunk(500, function ($rows) use ($out) {
                foreach ($rows as $m) {
                    fputcsv($out, [
                        $m->created_at->format('Y-m-d H:i:s'),
                        $m->product->name,
                        $m->type,
                        $m->quantity,
                        $m->stock_after,
                        $m->comment ?? '',
                    ]);
                }
            });

        fclose($out);
    }, $filename, $headers);
}
public function create(Product $product)
{
    return view('movements.create', compact('product'));
}

}
