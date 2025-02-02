<?php

namespace Usamamuneerchaudhary\Commentify\Scopes;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Usamamuneerchaudhary\Commentify\Models\CommentLike;
use Usamamuneerchaudhary\Commentify\Models\User;

trait HasDislikes
{
    /**
     * @return HasMany
     */
    public function dislikes(): HasMany
    {
        return $this->hasMany(CommentDislike::class);
    }

    /**
     * @return false|int
     */
    public function isDisliked(): bool|int
    {
        $ip = request()->ip();
        $userAgent = request()->userAgent();
        if (auth()->user()) {
            return User::with('dislikes')->whereHas('dislikes', function ($q) {
                $q->where('comment_id', $this->id);
            })->count();
        }

        if ($ip && $userAgent) {
            return $this->dislikes()->forIp($ip)->forUserAgent($userAgent)->count();
        }

        return false;
    }

    /**
     * @return bool
     */
    public function removeDislike(): bool
    {
        $ip = request()->ip();
        $userAgent = request()->userAgent();
        if (auth()->user()) {
            return $this->dislikes()->where('user_id', auth()->user()->id)->where('comment_id', $this->id)->delete();
        }

        if ($ip && $userAgent) {
            return $this->dislikes()->forIp($ip)->forUserAgent($userAgent)->delete();
        }

        return false;
    }
}
