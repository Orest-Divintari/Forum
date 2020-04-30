<?php

namespace App\Filters;
use Illuminate\Http\Request;
use App\User;
use App\Filters\Filters;

class ThreadFilters extends Filters{

    protected $filters = ['by', 'popular'];

    public function by($username)
    {   
        $user = User::whereName($username)->firstOrFail();
        $this->builder = $this->builder
                            ->where('user_id', $user->id)
                            ->latest();
    }

    public function popular(){

        $this->builder->getQuery()->orders = [];
        return $this->builder
                    ->orderBy('replies_count', 'desc');
    }
}