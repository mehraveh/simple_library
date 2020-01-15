<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;

class Book extends Model
{

    protected $fillable = ["name", "author", "publisher","ownder_id"];
}
