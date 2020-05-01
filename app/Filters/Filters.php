<?php
namespace App\Filters;

use Illuminate\Http\Request;

abstract class Filters
{

    protected $request;
    protected $builder;
    protected $filters = [];
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function apply($builder)
    {

        $this->builder = $builder;

        foreach ($this->getFilters() as $filter) {

            if (method_exists($this, $filter)) {
                // $this->request->filter can be for example
                // request('by') where filter == by
                // thus it will return the value for the session key 'by'
                $this->$filter($this->request->$filter);
            }
        }

        return $this->builder;

    }

    public function getFilters()
    {
        return array_intersect(array_keys($this->request->all()), $this->filters);
    }
}