<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Conversation extends Model
{
    use HasFactory;

    protected $fillable = ['tenant_id', 'admin_id', 'last_message', 'last_message_at'];
    public function messages()
    {
        return $this->hasMany(Message::class);
    }

    public function tenant()
    {
        return $this->belongsTo(User::class, 'tenant_id');
    }

    // علاقة لمعرفة الأدمن في هذه المحادثة
    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }
}
