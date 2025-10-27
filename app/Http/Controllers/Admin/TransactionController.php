<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\InventoryTransaction;
use App\Models\InventoryItem;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    public function index(Request $request)
    {
        $query = InventoryTransaction::with(['inventoryItem', 'creator']);

        // Filter by type
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        // Filter by inventory item
        if ($request->filled('inventory_item_id')) {
            $query->where('inventory_item_id', $request->inventory_item_id);
        }

        // Search by SKU or note
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('note', 'like', "%{$search}%")
                  ->orWhereHas('inventoryItem', function($query) use ($search) {
                      $query->where('sku', 'like', "%{$search}%")
                            ->orWhere('name', 'like', "%{$search}%");
                  });
            });
        }

        $transactions = $query->orderBy('created_at', 'desc')->paginate(20);

        $inventoryItems = InventoryItem::orderBy('name')->get();

        return view('admin.transactions.index', compact('transactions', 'inventoryItems'));
    }

    public function show(InventoryTransaction $transaction)
    {
        $transaction->load(['inventoryItem.category', 'creator']);

        return view('admin.transactions.show', compact('transaction'));
    }
}
