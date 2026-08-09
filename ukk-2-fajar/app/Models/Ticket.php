<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    protected $fillable = [
        'ticket_number', 'name', 'email', 'title', 'description', 
        'category_id', 'attachment', 'status', 'priority', 
        'ai_summary', 'ai_suggested_category_id', 'ai_suggested_priority', 'ai_processed'
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
    
    public function suggestedCategory()
    {
        return $this->belongsTo(Category::class, 'ai_suggested_category_id');
    }

    public function replies()
    {
        return $this->hasMany(Reply::class);
    }
}
