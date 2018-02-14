<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    public function path()
    {
        return '/category/' . $this->id;
    }
}
