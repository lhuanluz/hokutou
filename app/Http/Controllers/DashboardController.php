<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Transaction;
use App\Models\Category;
use App\Models\User;
use App\Models\Cart;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // Produtos com baixa quantidade em estoque
        $lowStockProducts = Product::whereColumn('quantity', '<=', 'minimum_quantity')->get();

        // Inicializar variáveis
        $todayIncome = 0;
        $todayExpenses = 0;

        // Obter todas as transações
        $transactions = Transaction::get();

        // Processar cada transação
        foreach ($transactions as $transaction) {
            $installments = $transaction->installments ?? 1;
            $installmentAmount = $transaction->amount / $installments;
            $startDate = Carbon::parse($transaction->transaction_date);

            for ($i = 0; $i < $installments; $i++) {
                $currentInstallmentDate = $startDate->copy()->addMonths($i);

                if ($currentInstallmentDate->isToday()) {
                    if ($transaction->transaction_type == 'entry') {
                        $todayIncome += $installmentAmount;
                    } elseif ($transaction->transaction_type == 'exit') {
                        $todayExpenses += $installmentAmount;
                    }
                }
            }
        }

        // Carrinhos finalizados hoje
        $todayCarts = Cart::whereDate('created_at', Carbon::today())
            ->where('status', 'done')
            ->get();

        // Dados dos produtos vendidos hoje
        $productsData = [];

        foreach ($todayCarts as $cart) {
            $products = $cart->products; // Usar diretamente o array armazenado

            foreach ($products as $product) {
                if (!isset($productsData[$product['id']])) {
                    $productsData[$product['id']] = [
                        'name' => Product::find($product['id'])->name,
                        'sold_quantity' => 0,
                        'total_earned' => 0,
                    ];
                }

                $productsData[$product['id']]['sold_quantity'] += $product['quantity'];
                $productsData[$product['id']]['total_earned'] += $product['price'] * $product['quantity'];
            }
        }

        $topProductsToday = collect($productsData)->sortByDesc('total_earned');

        // Categorias mais lucrativas
        $categories = Category::with('products')->get();

        $topCategories = $categories->map(function ($category) {
            $totalEarned = 0;

            foreach ($category->products as $product) {
                $totalEarned += $product->transactions()->sum('amount');
            }

            return [
                'name' => $category->name,
                'total_earned' => $totalEarned,
            ];
        })->sortByDesc('total_earned')->values()->all();

        // Maiores vendas
        $topSales = Transaction::orderByDesc('amount')->take(5)->get();

        // Cliente que mais gerou receita
        $topCustomer = User::withSum('transactions', 'amount')
            ->orderByDesc('transactions_sum_amount')
            ->first();

        // Transações de entrada e saída
        $dailyIncome = [];
        $dailyExpenses = [];

        foreach ($transactions as $transaction) {
            $installments = $transaction->installments ?? 1;
            $installmentAmount = $transaction->amount / $installments;
            $startDate = Carbon::parse($transaction->transaction_date);

            for ($i = 0; $i < $installments; $i++) {
                $dateKey = $startDate->copy()->addMonths($i)->format('Y-m-d');

                if ($transaction->transaction_type == 'entry') {
                    if (!isset($dailyIncome[$dateKey])) {
                        $dailyIncome[$dateKey] = 0;
                    }
                    $dailyIncome[$dateKey] += $installmentAmount;
                } elseif ($transaction->transaction_type == 'exit') {
                    if (!isset($dailyExpenses[$dateKey])) {
                        $dailyExpenses[$dateKey] = 0;
                    }
                    $dailyExpenses[$dateKey] += $installmentAmount;
                }
            }
        }

        // Garantir que todas as datas de entrada e saída estão representadas
        $allDates = array_unique(array_merge(array_keys($dailyIncome), array_keys($dailyExpenses)));
        sort($allDates);

        // Preencher as lacunas para garantir que todas as datas tenham valores
        $completeIncomeData = [];
        $completeExpenseData = [];

        foreach ($allDates as $date) {
            $completeIncomeData[$date] = $dailyIncome[$date] ?? 0;
            $completeExpenseData[$date] = $dailyExpenses[$date] ?? 0;
        }

        $monthlyIncomeData = [];
        $monthlyExpenseData = [];

        foreach ($transactions as $transaction) {
            $installments = $transaction->installments ?? 1;
            $installmentAmount = $transaction->amount / $installments;
            $startDate = Carbon::parse($transaction->transaction_date);

            for ($i = 0; $i < $installments; $i++) {
                $monthKey = $startDate->copy()->addMonths($i)->format('Y-m'); // Chave formatada apenas como mês

                if ($transaction->transaction_type == 'entry') {
                    if (!isset($monthlyIncomeData[$monthKey])) {
                        $monthlyIncomeData[$monthKey] = 0;
                    }
                    $monthlyIncomeData[$monthKey] += $installmentAmount;
                } elseif ($transaction->transaction_type == 'exit') {
                    if (!isset($monthlyExpenseData[$monthKey])) {
                        $monthlyExpenseData[$monthKey] = 0;
                    }
                    $monthlyExpenseData[$monthKey] += $installmentAmount;
                }
            }
        }

        // Garantir que todas as datas de entrada e saída estão representadas
        ksort($monthlyIncomeData);
        ksort($monthlyExpenseData);

        return view('dashboard', compact(
            'lowStockProducts',
            'todayIncome',
            'topProductsToday',
            'topCategories',
            'topSales',
            'topCustomer',
            'completeIncomeData',
            'completeExpenseData',
            'monthlyIncomeData',
            'monthlyExpenseData'
        ));
    }
}
