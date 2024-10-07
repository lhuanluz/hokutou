<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\User;
use Carbon\Carbon;
use App\Models\Transaction;

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
        $user = $cart->user;
        $products = Product::all(); // Carregar todos os produtos
        $installmentOptions = $this->calculateInstallments($cart->total_value);

        return view('carts.edit', compact('cart', 'user', 'products', 'installmentOptions'));
    }

    private function calculateInstallments($totalValue)
    {
        $installmentOptions = [1,2,3,4,5,6,7,8,9,10,11,12];
        /*
        if ($totalValue >= 200 && $totalValue <= 300) {
            $installmentOptions = [1, 2];
        } elseif ($totalValue >= 301 && $totalValue <= 450) {
            $installmentOptions = [1, 2, 3];
        } elseif ($totalValue >= 451 && $totalValue <= 600) {
            $installmentOptions = [1, 2, 3, 4];
        } elseif ($totalValue >= 601 && $totalValue <= 900) {
            $installmentOptions = [1, 2, 3, 4, 6];
        } elseif ($totalValue >= 901 && $totalValue <= 1200) {
            $installmentOptions = [1, 2, 3, 4, 6, 10];
        } elseif ($totalValue > 1200) {
            $installmentOptions = [1, 2, 3, 4, 6, 10, 12];
        }*/

        return $installmentOptions;
    }

    public function update(Request $request, Cart $cart)
    {
        $request->validate([
            'amount_paid' => 'required|numeric|min:0',
            'payment_method' => 'required|string',
            'installments' => 'nullable|integer|min:1',
            'status' => 'required|string', 'use_credit' => 'nullable|numeric|min:0|max:' . $cart->user->balance,
        ]);

        $cart->status = $request->status;
        $amountPaid = $request->amount_paid;
        $totalValue = $cart->total_value;
        $user = $cart->user;

        // Aplicar o crédito, se houver
        $creditUsed = $request->use_credit ? $request->use_credit : 0;
        $totalValue -= $creditUsed;

        // Atualizar o saldo do usuário (balance)
        if ($creditUsed > 0) {
            $user->balance -= $creditUsed;
        }

        if ($request->status == 'done') {
            $balanceDifference = $amountPaid - $totalValue;
            if ($balanceDifference != 0) {
                $user->balance += $balanceDifference;
            }

            // Preparar observações
            $observations = '';
            if ($creditUsed > 0) {
                $observations .= "Credit used: $creditUsed. ";
            }

            if ($balanceDifference < 0) {
                $observations .= "Remaining debt added to balance: " . abs($balanceDifference) . ".";
            } elseif ($balanceDifference > 0) {
                $observations .= "Remaining credit added to balance: $balanceDifference.";
            } else {
                $observations .= "No debt or credit was generated.";
            }

            // Calcular a taxa de acordo com o método de pagamento
            $paymentFee = 0;
            if ($request->payment_method === 'Credit') {
                if ($request->installments > 1) {
                    $paymentFee = 4.5;
                } else {
                    $paymentFee = 3;
                }
            }
            // Atualizar o balance do usuário com a diferença

            // Inserir a movimentação financeira
            Transaction::create([
                'transaction_date' => Carbon::now()->setTimezone('America/Sao_Paulo'),
                'amount' => $amountPaid,
                'transaction_type' => 'entry',
                'payment_method' => $request->payment_method,
                'installments' => $request->installments ?? 1,
                'payment_fee' => $paymentFee,
                'cart_id' => $cart->id,
                'observations' => $observations, // Adicionar observações
            ]);
        } elseif ($request->status == 'cancelled') {
            foreach ($cart->products as $product) {
                $productModel = Product::find($product['id']);
                if ($productModel) {
                    $productModel->quantity += $product['quantity'];
                    $productModel->save();
                }
            }
        }

        $cart->save();
        $user->save(); // Salvar o usuário com o balance atualizado

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
