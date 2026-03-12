<?php

namespace App\Http\Filters\V1;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

abstract class QueryFilter
{
    protected Builder $builder;
    protected Request $request;
    protected array $allowedSorts = [];

    public function __construct(Request $request) {
        $this->request = $request;
    }

    public function apply(Builder $builder): Builder
    {
        $this->builder = $builder;

        foreach($this->request->all() as $key => $value){
            if(method_exists($this, $key)) {
                $this->$key($value);
            }
        }

        return $this->builder;
    }

    protected function filter($array): void
    {
        foreach ($array as $key => $value) {
            if (method_exists($this, $key)) {
                $this->$key($value);
            }
        }
    }

    protected function sort($value): void
    {
        $sortAttributes = explode(',', $value);
        
        foreach($sortAttributes as $sortAttribute) {
            $direction = 'asc';
            // Check if the value starts with a minus sign
            if (strpos($sortAttribute, '-') === 0) {
                $direction = 'desc';
                $sortAttribute = substr($sortAttribute, 1);
            }

            if(!in_array($sortAttribute, $this->allowedSorts) && !array_key_exists($sortAttribute, $this->allowedSorts)) {
                continue;
            }

            $columName = $this->allowedSorts[$sortAttribute] ?? $sortAttribute;
            
            $this->builder->orderBy($columName, $direction);
        }

    }
}
