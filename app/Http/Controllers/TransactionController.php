<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\Product;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware(function ($request, $next) {
            if (auth()->user()->admin_level < 2) {
                return redirect()->route('dashboard')->with('error', 'Unauthorized access.');
            }
            return $next($request);
        });
    }

    public function index()
    {
        $transactions = Transaction::with('cart.user')->paginate(10);

        foreach ($transactions as $transaction) {
            $transaction->formatted_products = $this->formatProducts($transaction->cart->products);
        }

        return view('transactions.index', compact('transactions'));
    }

    private function formatProducts($products)
    {
        $formatted = [];

        foreach ($products as $product) {
            $productModel = Product::find($product['id']);
            if ($productModel) {
                $formatted[] = "{$productModel->name} - {$product['quantity']}un - {$product['price']}";
            }
        }

        return implode('<br>', $formatted);
    }
}

