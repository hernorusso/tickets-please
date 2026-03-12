<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Filters\V1\TicketFilter;
use App\Http\Resources\V1\TicketResource;
use App\Models\User;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class AuthorTicketController extends Controller
{
    public function index(User $author, TicketFilter $filters): AnonymousResourceCollection
    {
        return TicketResource::collection($author->tickets()->filter($filters)->paginate());
    }
}
