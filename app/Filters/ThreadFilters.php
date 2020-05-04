<?php

namespace App\Filters;

use App\Filters\Filters;
use App\User;

class ThreadFilters extends Filters
{

    protected $filters = ['by', 'popular', 'unanswered'];

    public function by($username)
    {
        $user = User::whereName($username)->firstOrFail();
        $this->builder = $this->builder
            ->where('user_id', $user->id)
            ->latest();
    }

    public function popular()
    {

        $this->builder->getQuery()->orders = [];
        return $this->builder
            ->orderBy('replies_count', 'desc');
    }

    public function unanswered()
    {
        $this->builder->where('replies_count', 0);

    }
}