<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function aiSuggestedCategory()
    {
        return $this->belongsTo(Category::class, 'ai_suggested_category_id');
    }

    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }

    public function replies()
    {
        return $this->hasMany(TicketReply::class);
    }

    public function aiSuggestion()
    {
        return $this->hasOne(AiSuggestion::class);
    }
}
