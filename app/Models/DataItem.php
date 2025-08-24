<?php

namespace App\Models;

use App\StatusEnum;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DataItem extends Model
{
    use HasFactory;
    protected $fillable = [
        'title',
        'description',
        'status'
    ];

    public function getStatusLabelAttribute(): string
    {
        return StatusEnum::from($this->status)->name;
    }

    public function scopeAllowed(Builder $query)
    {
        return $query->where('status', '=', StatusEnum::ALLOWED->value);
    }

    public function scopeProhibited(Builder $query)
    {
        return $query->where('status', '=',StatusEnum::PROHIBITED->value);
    }

}
