<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Requests\Api\v1\StoreTicketRequest;
use App\Http\Requests\Api\v1\UpdateTicketRequest;
use App\Http\Resources\V1\TicketResource;
use App\Models\Ticket;
use App\Http\Filters\V1\TicketFilter;
use App\Http\Requests\Api\v1\ReplaceTicketRequest;
use App\Models\User;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class TicketController extends ApiController
{

    protected function getPolicyGate(): string
    {
        return 'v1.ticket';
    }

    /**
     * Display a listing of the resource.
     */
    public function index(TicketFilter $filters): AnonymousResourceCollection
    {
        return TicketResource::collection(Ticket::filter($filters)->paginate());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTicketRequest $request)
    {
        try {
            // policy
            $this->isAble('store', null);
            return new TicketResource(Ticket::create($request->mappedAttributes()));
            
        } catch (AuthorizationException $e) {
            return $this->error('You are not authorized to create a TKT', 401);
        }

    }

    /**
     * Display the specified resource.
     */
    public function show($ticket_id)
    {
        try {
            $ticket = Ticket::query()->findOrFail($ticket_id);

            if ($this->include('author')) {
                return new TicketResource($ticket->load('author'));
            }

            return new TicketResource($ticket);
        } catch (ModelNotFoundException $exception) {
            return $this->error('Ticket cannot be found', 404);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTicketRequest $request, $ticket_id)
    {
        try {
            $ticket = Ticket::findOrFail($ticket_id);
            $this->isAble('update', $ticket);

            $ticket->update($request->mappedAttributes());

            return new TicketResource($ticket);
        } catch (ModelNotFoundException $e) {
            // Handle the exception
            return $this->error('Ticket cannot be found', 404);
        } catch (AuthorizationException $e) {
            return $this->error('You are not authorized to update this TKT', 401);
        }
    }

    /**
     * Replace the specified resource in storage.
     */
    public function replace(ReplaceTicketRequest $request, $ticket_id)
    {
        try {
            $ticket = Ticket::findOrFail($ticket_id);
            $this->isAble('replace', $ticket);
            $ticket->update($request->mappedAttributes());

            return new TicketResource($ticket);
        } catch (ModelNotFoundException $e) {
            // Handle the exception
            return $this->error('Ticket cannot be found', 404);
        } catch (AuthorizationException $e) {
            return $this->error('You are not authorized to replace this TKT', 401);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($ticket_id)
    {
        try {
            $ticket = Ticket::findOrFail($ticket_id);
            $this->isAble('destroy', $ticket);
            $ticket->delete();

            return $this->ok('Ticket deleted successfully');
        } catch (ModelNotFoundException $exception) {
            return $this->error('Ticket cannot be found', 404);
        } catch (AuthorizationException $e) {
            return $this->error('You are not authorized to delete this TKT', 401);
        }
    }
}
