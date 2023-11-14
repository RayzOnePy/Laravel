<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class File extends Model
{
    use HasFactory;

    protected $guarded = [];

    public static function findByName(int $userId, string $name): ?Model
    {
        return File::query()->where('user_id', $userId)->where('full_name', $name)->first();
    }

    /**
     * @param int $userId
     * @return Builder[]|Collection
     */
    public static function findAllByUser(int $userId): Collection|array
    {
        return File::query()->where('user_id', $userId)->get();
    }

    public function getPathWithName(): string
    {
        return $this['path'] . '/' . $this['full_name'];
    }

    public function getPath(): string
    {
        return $this['path'];
    }

    public function getExtension(): string
    {
        return $this['extension'];
    }
}
