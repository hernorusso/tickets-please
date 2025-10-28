<?php

use App\Http\Controllers\Api\v1\TicketController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use function Pest\Laravel\json;


Route::get('tickets', [TicketController::class, 'index'])->middleware('auth:sanctum');
Route::get('tickets/{ticket}', [TicketController::class, 'show'])
    ->name('tickets.show')
    ->middleware('auth:sanctum');
