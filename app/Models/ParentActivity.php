<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ParentActivity extends Model
{
    use HasFactory;

    protected $fillable = ['parent_title', 'parent_number'];

    public function childs()
    {
        return $this->hasMany(ChildActivity::class, 'parent_activity_id');
    }

}
