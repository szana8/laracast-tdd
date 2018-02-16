<?php

namespace App\Filters;


use Illuminate\Http\Request;

abstract class Filters
{
    /**
     * @var Request
     */
    protected $request;

    /**
     * @var
     */
    protected $builder;

    /**
     * @var array
     */
    protected $filters = ['by'];

    /**
     * IssueFilters constructor.
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * @param $builder
     * @return mixed
     */
    public function apply($builder)
    {
        $this->builder = $builder;

        collect($this->getFilters())
            ->filter(function($value, $filter) {
                return method_exists($this, $filter);
            })
            ->each(function ($value, $filter) {
                $this->$filter($value);
            });

//        foreach ($this->getFilters() as $filter => $value) {
//            if (method_exists($this, $filter)) {
//                $this->$filter($value);
//            }
//        }

        return $this->builder;
    }

    /**
     * @return array
     */
    public function getFilters()
    {
        return $this->request->only($this->filters);
    }

}