<?php

use App\Http\Controllers\Api\V1\AuthorTicketController;
use App\Http\Controllers\Api\V1\TicketController;
use App\Http\Controllers\Api\V1\AuthorController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->group(function() {
  Route::apiResource('tickets', TicketController::class)->except('update');
  Route::patch('tickets/{ticket}', [TicketController::class, 'update'])->name('tickets.update');
  Route::put('tickets/{ticket}', [TicketController::class, 'replace'])->name('tickets.replace');

  Route::apiResource('authors', AuthorController::class);
  Route::apiResource('authors.tickets', AuthorTicketController::class)->except('update');
  Route::patch('authors/{author}/tickets/{ticket}', [AuthorTicketController::class, 'update']);
  Route::put('authors/{author}/tickets/{ticket}', [AuthorTicketController::class, 'replace']);
});
