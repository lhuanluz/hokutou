<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\User;
use Carbon\Carbon;

class CartController extends Controller
{
    public function addProduct(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $user = User::find($request->user_id);
        $product = Product::find($request->product_id);
        $today = Carbon::today();

        // Validação da quantidade em estoque
        if ($request->quantity > $product->quantity) {
            return redirect()->back()->withErrors(['quantity' => 'A quantidade selecionada excede o estoque disponível.']);
        }

        // Verificar se o usuário já tem um carrinho "open" hoje
        $cart = Cart::where('user_id', $user->id)
            ->where('status', 'open')
            ->whereDate('created_at', $today)
            ->first();

        if (!$cart) {
            // Criar um novo carrinho se não houver um "open" para hoje
            $cart = Cart::create([
                'user_id' => $user->id,
                'products' => [],
                'total_value' => 0,
                'discount_coupon' => null,
                'discount_value' => 0,
                'status' => 'open',
            ]);
        }

        // Adicionar ou atualizar o produto no carrinho
        $products = $cart->products;
        $productExists = false;

        foreach ($products as &$cartProduct) {
            if ($cartProduct['id'] == $product->id) {
                $cartProduct['quantity'] += $request->quantity;
                $productExists = true;
                break;
            }
        }

        if (!$productExists) {
            $products[] = [
                'id' => $product->id,
                'quantity' => $request->quantity,
                'price' => $product->sale_value,
            ];
        }

        // Atualizar o carrinho com os novos produtos
        $cart->products = $products;

        // Recalcular o total do carrinho
        $totalValue = 0;
        foreach ($products as $cartProduct) {
            $totalValue += $cartProduct['quantity'] * $cartProduct['price'];
        }

        $cart->total_value = $totalValue;
        $cart->save();

        // Atualizar o estoque do produto
        $product->quantity -= $request->quantity;
        $product->save();

        return redirect()->route('carts.index')->with('success', 'Product added to cart successfully.');
    }
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware(function ($request, $next) {
            if (auth()->user()->admin_level < 1) {
                return redirect()->route('dashboard')->with('error', 'Unauthorized access.');
            }
            return $next($request);
        });
    }

    public function index()
    {
        $carts = Cart::with('user')
            ->where('status', '!=', 'cancelled')
            ->get();

        foreach ($carts as $cart) {
            $cart->formatted_products = $this->formatProducts($cart->products);
        }

        return view('carts.index', compact('carts'));
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

    public function create()
    {
        $users = User::all(); // Fetch all users for the admin to assign the cart
        $products = Product::all(); // Fetch all products for the admin to add to the cart
        return view('carts.create', compact('users', 'products'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'products' => 'required|array',
            'total_value' => 'required|numeric',
            'discount_coupon' => 'nullable|string',
            'discount_value' => 'nullable|numeric',
            'status' => 'required|string',
        ]);

        Cart::create([
            'user_id' => $request->user_id,
            'products' => $request->products,
            'total_value' => $request->total_value,
            'discount_coupon' => $request->discount_coupon,
            'discount_value' => $request->discount_value,
            'status' => $request->status,
        ]);

        return redirect()->route('carts.index')->with('success', 'Cart created successfully.');
    }

    public function edit(Cart $cart)
    {
        $users = User::all(); // Carregar todos os usuários
        $products = Product::all(); // Carregar todos os produtos

        return view('carts.edit', compact('cart', 'users', 'products'));
    }


    public function update(Request $request, Cart $cart)
    {
        $request->validate([
            'amount_paid' => 'required|numeric|min:0',
            'status' => 'required|string',
        ]);

        // Atualizar o status e o valor pago
        $cart->status = $request->status;
        $amountPaid = $request->amount_paid;
        $totalValue = $cart->total_value;

        if ($request->status == 'done') {
            $balanceDifference = $amountPaid - $totalValue;

            $user = $cart->user;

            if ($balanceDifference != 0) {
                // Se balanceDifference for negativo, adiciona ao saldo devedor do usuário
                // Se balanceDifference for positivo, adiciona como crédito
                $user->balance += $balanceDifference;
                $user->save();
            }
        } elseif ($request->status == 'cancelled') {
            // Devolver os produtos ao estoque
            foreach ($cart->products as $product) {
                $productModel = Product::find($product['id']);
                if ($productModel) {
                    $productModel->quantity += $product['quantity'];
                    $productModel->save();
                }
            }
        }

        $cart->save();

        return redirect()->route('carts.index')->with('success', 'Cart updated successfully.');
    }



    public function destroy(Cart $cart)
    {
        $cart->delete();
        return redirect()->route('carts.index')->with('success', 'Cart deleted successfully.');
    }
    public function addProductForm()
    {
        $users = User::all(); // Fetch all users
        $products = Product::all(); // Fetch all products with quantities

        return view('carts.addProduct', compact('users', 'products'));
    }
}
