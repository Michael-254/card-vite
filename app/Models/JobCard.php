<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobCard extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'status', 'site', 'job_card_number', 'current_parent_id', 'current_child_id'];

    public function Confirmedrecords()
    {
        return $this->belongsToMany(Record::class)->withPivot('location', 'child_activity_id')->using(JobCardRecord::class)->withTimestamps();
    }

    public function signatures()
    {
        return $this->hasMany(Signature::class);
    }

    public function childactivity()
    {
        return $this->belongsTo(ChildActivity::class, 'current_child_id');
    }

    public function timelines()
    {
        return $this->hasMany(Timeline::class);
    }

    public function trees()
    {
        return $this->hasMany(Tree::class)->orderBy('tree_number', 'asc');
    }
}
