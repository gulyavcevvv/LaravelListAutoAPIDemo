<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @OA\Schema(
 *     schema="Car",
 *     required={"model"},
 *     @OA\Property(
 *       property="id",
 *       type="integer",
 *       example="1",
 *     ),
 *     @OA\Property(
 *       property="model",
 *       type="string",
 *       example="BMW x5",
 *     ),
 *     @OA\Property(
 *       property="user_id",
 *       type="integer",
 *       example="1",
 *     )
 * )
 */
class Car extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'model',
    ];

    /**
     * Связь с  пользователем.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
