<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Books extends Model
{

    protected $fillable = ["name", "author", "publisher","ownder_id"];
}
