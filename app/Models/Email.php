<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Email extends Model
{
    /** @use HasFactory<\Database\Factories\EmailFactory> */
    use HasFactory;
    protected $fillable = [
        'email'
    ];
    
    public function setEmailAttribute($value)
    {
        $this->attributes['email'] = strtolower($value);
    }
    public function user():BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
