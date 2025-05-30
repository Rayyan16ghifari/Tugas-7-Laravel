<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TransactionController extends Controller
{
    // Menampilkan semua transaksi (khusus admin)
    public function index()
    {
        if (Auth::user()->role !== 'admin') {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $transactions = Transaction::with('user', 'book')->get();
        return response()->json($transactions);
    }

    // Menyimpan transaksi baru (khusus customer login)
    public function store(Request $request)
    {
        $validated = $request->validate([
            'book_id' => 'required|exists:books,id',
            'borrowed_at' => 'required|date',
        ]);

        $transaction = Transaction::create([
            'user_id' => Auth::id(),
            'book_id' => $validated['book_id'],
            'borrowed_at' => $validated['borrowed_at'],
            'returned_at' => null,
        ]);

        return response()->json($transaction, 201);
    }

    // Menampilkan detail transaksi tertentu (khusus customer login)
    public function show($id)
    {
        $transaction = Transaction::with('user', 'book')->findOrFail($id);

        if (Auth::user()->role !== 'admin' && $transaction->user_id !== Auth::id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        return response()->json($transaction);
    }

    // Mengupdate transaksi (khusus customer login)
    public function update(Request $request, $id)
    {
        $transaction = Transaction::findOrFail($id);

        if ($transaction->user_id !== Auth::id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $validated = $request->validate([
            'returned_at' => 'nullable|date',
        ]);

        $transaction->update($validated);

        return response()->json($transaction);
    }

    // Menghapus transaksi (khusus admin)
    public function destroy($id)
    {
        if (Auth::user()->role !== 'admin') {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $transaction = Transaction::findOrFail($id);
        $transaction->delete();

        return response()->json(['message' => 'Transaction deleted']);
    }
}
