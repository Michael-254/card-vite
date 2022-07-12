<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Record extends Model
{
    use HasFactory;

    protected $fillable = ['child_activity_id','record'];
    
    public function Jcards(){
        return $this->belongsToMany(JobCard::class)->using(JobCardRecord::class)->withTimestamps();
    }

    public function activityChild(){
        return $this->belongsTo(ChildActivity::class,'child_activity_id');
    }
}
