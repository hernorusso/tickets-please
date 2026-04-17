<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Filters\V1\TicketFilter;
use App\Http\Requests\Api\v1\ReplaceTicketRequest;
use App\Http\Resources\V1\TicketResource;
use App\Models\Ticket;
use App\Models\User;
use App\Http\Requests\Api\v1\StoreTicketRequest;
use App\Http\Requests\Api\v1\UpdateTicketRequest;
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
    public function store(User $author, StoreTicketRequest $request)
    {
        $model = $request->mappedAttributes();
        $model['user_id'] = $author->id;

        return new TicketResource(Ticket::create($model));
    }

    /**
     * Replace the specified resource in storage.
     */
    public function replace(ReplaceTicketRequest $request, $author_id, $ticket_id)
    {
        try {
            $ticket = Ticket::findOrFail($ticket_id);

            if ($ticket->user_id == $author_id) {

                $ticket->update($request->mappedAttributes());

                return new TicketResource($ticket);
            }

            // ToDo: do someeething if the users do not match. 

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
            $ticket = Ticket::findOrFail($ticket_id);

            if ($ticket->user_id == $author_id) {
                $ticket->update($request->mappedAttributes());

                return new TicketResource($ticket);
            }

            // ToDo: do someeething if the users do not match. 

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
            $ticket = Ticket::findOrFail($ticket_id);

            if ($ticket->user_id == $author_id) {
                $ticket->delete();
                return $this->ok('Ticket deleted successfully');
            }

            return $this->error('Ticket cannot be found', 404);
        } catch (ModelNotFoundException $exception) {
            return $this->error('Ticket cannot be found', 404);
        }
    }
}
