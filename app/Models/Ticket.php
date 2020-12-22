<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    use HasFactory;

    protected $guarded = ['slug'];

    public function publish() {
        $this->published_at = now()->toDateTimeString();
        return $this->save();
    }

    public function scopePublished($query) {
        return $query->whereNotNull('published_at');
    }
}
