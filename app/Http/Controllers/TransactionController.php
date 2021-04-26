<?php

namespace App\Http\Controllers;

use App\Transaction;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Category;
use DB;

class TransactionController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (request()->type == "future") {
            $transactions = Transaction::where('user_id', '=', auth()->user()->id)->where('date', '>', Carbon::now()->format('Y-m-d'))->orderBy('date', 'desc')->with('category', 'account')->get()->groupBy('date');
        } elseif (request()->type == "past") {
            $date = Carbon::now();
            $transactions = Transaction::where('user_id', '=', auth()->user()->id)->whereMonth('date', $date->subMonth()->format('m'))->orderBy('date', 'desc')->with('category', 'account')->get()->groupBy('date');
        } elseif (request()->type == "current") {
            $transactions = Transaction::where('user_id', '=', auth()->user()->id)->whereMonth('date', Carbon::now()->format('m'))->where('date', '<=', Carbon::now()->format('Y-m-d'))->orderBy('date', 'desc')->with('category', 'account')->get()->groupBy('date');
        } else {
            $date = request()->type;
            $dateMonthArray = explode('-', $date);
            $transactions = Transaction::where('user_id', '=', auth()->user()->id)->whereMonth('date', date("m", strtotime($dateMonthArray[0])))->whereyear('date', $dateMonthArray[1])->orderBy('date', 'desc')->with('category', 'account')->get()->groupBy('date');
        }
        
        foreach ($transactions as $key => $transaction) {
            $income = 0;
            $expense = 0;
            foreach ($transaction as $detail) {
                if ($detail->transaction_type == 'income') {
                    $income = $income + $detail->amount;
                } else {
                    $expense = $expense + $detail->amount;
                }
            }
            $total = $income - $expense ;
            $transaction->put('day', Carbon::parse($key)->format('l'));
            $transaction->put('date', Carbon::parse($key)->format('d'));
            $transaction->put('month_with_year', Carbon::parse($key)->format('F Y'));
            $transaction->put('total', $total);
        }
         
        return response()->json($transactions);
    }

    public function transactionType($id)
    {
        $categories = Transaction::where('user_id', '=', auth()->user()->id)->where('transaction_type', '=', $id)->with('category')->with('account')->get();
        return response()->json($categories);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate(request(), [
            'category_id' => 'required',
            'date' => 'required',
            'amount' => 'required',
            'account_id' => 'required',
            'transaction_type' => 'required'
        ]);
        $transaction = new Transaction();
        $transaction->date = $request->date;
        $transaction->description = $request->description;
        $transaction->category_id = $request->category_id;
        $transaction->amount = $request->amount;
        $transaction->account_id = $request->account_id;
        $transaction->transaction_type = $request->transaction_type;
        $transaction->user_id = auth()->user()->id;
        $transaction->save();
        return response()->json($transaction);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\cr  $cr
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $transaction = Transaction::with('category', 'account')->find($id);
        return response()->json($transaction);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\cr  $cr
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\cr  $cr
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->validate(request(), [
            'category_id' => 'required',
            'date' => 'required',
            'amount' => 'required',
            'account_id' => 'required',
            'transaction_type' => 'required'
        ]);
        $transaction = Transaction::find($id);
        $transaction->date = $request->date;
        $transaction->description = $request->description;
        $transaction->category_id = $request->category_id;
        $transaction->amount = $request->amount;
        $transaction->account_id = $request->account_id;
        $transaction->transaction_type = $request->transaction_type;
        $transaction->user_id = auth()->user()->id;
        $transaction->save();
        return response()->json($transaction);
    }
    
    public function getAllTransactionMonth()
    {
        $months = Transaction::select(
            DB::raw("DATE_FORMAT(date,'%M-%Y') as months")
        )
        ->where('user_id', '=', auth()->user()->id)
        ->where('date', '<', Carbon::now()->subMonth())
        ->groupBy('months')
        ->orderBy('date', 'asc')
        ->get();
        return response()->json($months);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\cr  $cr
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $transaction = Transaction::find($id);
        return response()->json(['success' => $transaction->delete()]);
    }
}
