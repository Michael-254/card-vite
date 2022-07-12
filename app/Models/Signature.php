<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Signature extends Model
{
    use HasFactory;

    protected $fillable = ['role_id','user_id','job_card_id','child_activity_id'];

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function role(){
        return $this->belongsTo(Role::class);
    }

    public function son_activity(){
        return $this->belongsTo(ChildActivity::class,'child_activity_id');
    }
}
