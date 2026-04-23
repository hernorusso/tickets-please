<?php

namespace App\Policies\V1;

use App\Models\Ticket;
use App\Models\User;
use App\Permisions\V1\Abilities;

class TicketPolicy
{

    public function destroy(User $user, Ticket $ticket): bool
    {
        if ($user->tokenCan(Abilities::DeleteTicket)) {
            return true;
        } else if ($user->tokenCan(Abilities::DeleteOwnTicket)) {
            return $user->id === $ticket->user_id;
        }

        return false;
    }

    public function replace(User $user, Ticket $ticket): bool
    {
        return $user->tokenCan(Abilities::ReplaceTicket);
    }

    public function store(User $user): bool
    {
        return $user->tokenCan(Abilities::CreateTicket) ||
            $user->tokenCan(Abilities::CreateOwnTicket);
    }
    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Ticket $ticket): bool
    {
        if ($user->tokenCan(Abilities::UpdateTicket)) {
            return true;
        } else if ($user->tokenCan(Abilities::UpdateOwnTicket)) {
            return $user->id === $ticket->user_id;
        }

        return false;
    }
}
