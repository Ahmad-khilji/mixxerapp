<?php

// app/Models/Participant.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Participant extends Model
{
    // Other model properties and methods

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function post()
    {
        return $this->belongsTo(Post::class);
    }
    protected $hidden = ['created_at', 'updated_at'];
}
