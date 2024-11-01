<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Student extends Model
{

    protected $fillable = [
        'name',
        'email',
        'class_id',
        'section_id'
    ];
    use HasFactory;

    public function class(): BelongsTo
    {
        return $this->belongsTo(Classes::Class,'class_id');
    }

    public function section(): BelongsTo
    {
        return $this->belongsTo(Section::Class);
    }



}
