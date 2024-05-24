<?php

// app/Models/Post.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    // Other model properties and methods

    public function participants()
    {
        return $this->hasMany(Participant::class);
    }
    protected $hidden = ['created_at', 'updated_at'];
}

