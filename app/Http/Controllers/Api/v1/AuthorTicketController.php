<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Filters\V1\TicketFilter;
use App\Http\Resources\V1\TicketResource;
use App\Models\Ticket;
use App\Models\User;
use App\Http\Requests\Api\v1\StoreTicketRequest;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class AuthorTicketController extends Controller
{
    public function index(User $author, TicketFilter $filters): AnonymousResourceCollection
    {
        return TicketResource::collection($author->tickets()->filter($filters)->paginate());
    }

     /**
     * Store a newly created resource in storage.
     */
    public function store(User $author, StoreTicketRequest $request)
    {
        $model = [
            'title' => $request->input('data.attributes.title'),
            'description' => $request->input('data.attributes.description'),
            'status' => $request->input('data.attributes.status', 'open'),
            'user_id' => $author->id,
        ];

        return new TicketResource(Ticket::create($model));
    }
}
