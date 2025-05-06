<?php

use App\Models\Order;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/{record}/invoice/pdf', function ($id) {
    $order = Order::findOrFail($id);

    $pdf = Pdf::loadView('pdf.invoice-template', compact('order'));
    return $pdf->stream('invoice.pdf');
})->name('order.invoice.pdf');
