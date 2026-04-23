<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Filters\V1\TicketFilter;
use App\Http\Requests\Api\v1\ReplaceTicketRequest;
use App\Http\Resources\V1\TicketResource;
use App\Models\Ticket;
use App\Models\User;
use App\Http\Requests\Api\v1\StoreTicketRequest;
use App\Http\Requests\Api\v1\UpdateTicketRequest;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class AuthorTicketController extends ApiController
{
    protected function getPolicyGate(): string
    {
        return 'v1.ticket';
    }

    public function index(User $author, TicketFilter $filters): AnonymousResourceCollection
    {
        return TicketResource::collection($author->tickets()->filter($filters)->paginate());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTicketRequest $request, User $author)
    {
        try {
            // policy
            $this->isAble('store', null);
            return new TicketResource(Ticket::create($request->mappedAttributes(["author" => "user_id"])));
            
        } catch (AuthorizationException $e) {
            return $this->error('You are not authorized to create a TKT', 401);
        }
    }

    /**
     * Replace the specified resource in storage.
     */
    public function replace(ReplaceTicketRequest $request, $author_id, $ticket_id)
    {
        try {
            $ticket = Ticket::query()
                ->where('id', $ticket_id)
                ->where('user_id', $author_id)
                ->firstOrFail();
            
            $this->isAble('replace', $ticket);
            $ticket->update($request->mappedAttributes(["author" => "user_id"]));

            return new TicketResource($ticket);
        } catch (AuthorizationException $e) {
            // Handle the exception
            return $this->error('You are not authorized to replace this TKT', 401);
        } catch (ModelNotFoundException $e) {
            // Handle the exception
            return $this->error('Ticket cannot be found', 404);
        }
    }

    /**
     * Replace the specified resource in storage.
     */
    public function update(UpdateTicketRequest $request, $author_id, $ticket_id)
    {
        try {
            $ticket = Ticket::query()
                ->where('id', $ticket_id)
                ->where('user_id', $author_id)
                ->firstOrFail();

                $this->isAble('update', $ticket);
                $ticket->update($request->mappedAttributes());

                return new TicketResource($ticket);
        } catch (AuthorizationException $e) {
            // Handle the exception
            return $this->error('You are not authorized to replace this TKT', 401);
        } catch (ModelNotFoundException $e) {
            // Handle the exception
            return $this->error('Ticket cannot be found', 404);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($author_id, $ticket_id)
    {
        try {
            $ticket = Ticket::query()
                ->where('id', $ticket_id)
                ->where('user_id', $author_id)
                ->firstOrFail();

            $this->isAble('destroy', $ticket);
            $ticket->delete();
            return $this->ok('Ticket deleted successfully');
    
        } catch (AuthorizationException $e) {
            // Handle the exception
            return $this->error('You are not authorized to replace this TKT', 401);
        } catch (ModelNotFoundException $exception) {
            return $this->error('Ticket cannot be found', 404);
        }
    }
}
