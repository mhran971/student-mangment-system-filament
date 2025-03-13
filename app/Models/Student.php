<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Auth\User as Authenticatable;
class Student extends Authenticatable
{
    protected $guard = 'student';
    use HasFactory;
    protected $fillable = [
        'name',
        'email',
        'class_id',
        'section_id',
        'password'
    ];
    protected function casts(): array
    {
        return [
            'password' => 'hashed',
        ];
    }
    public function class(): BelongsTo
    {
        return $this->belongsTo(Classes::Class,'class_id');
    }

    public function section(): BelongsTo
    {
        return $this->belongsTo(Section::Class);
    }



}
