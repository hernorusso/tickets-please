<?php
namespace App\Http\Filters\V1;

use App\Http\Filters\V1\QueryFilter;

class TicketFilter extends QueryFilter
{
    public function createdAt($value): void
    {
        $dates = explode(',', $value);

        if (count($dates) > 1) {
            $this->builder->whereBetween('created_at', $dates);
        } else {
            $this->builder->whereDate('created_at', $value);
        }

    }
    public function include($value): void
    {
        // ToDo: Prevent 500 when passing an invalid relationship name
        $this->builder->with($value);
    }

    public function status($value): void
    {
        $this->builder->whereIn('status', explode(',', $value));
    }

    public function title($value): void
    {
        $likeStr = str_replace('*', '%', $value);
        $this->builder->where('title', 'like', $likeStr);
    }

    public function updatedAt($value): void
    {
        $dates = explode(',', $value);

        if (count($dates) > 1) {
            $this->builder->whereBetween('updated_at', $dates);
        } else {
            $this->builder->whereDate('updated_at', $value);
        }

    }

}
