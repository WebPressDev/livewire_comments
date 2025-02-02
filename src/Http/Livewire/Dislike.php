<?php

namespace Usamamuneerchaudhary\Commentify\Http\Livewire;


use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Livewire\Component;

class Dislike extends Component
{

    public $comment;
    public $count;


    public function mount(\Usamamuneerchaudhary\Commentify\Models\Comment $comment): void
    {
        $this->comment = $comment;
        $this->count = $comment->dislikes_count;
    }

    public function dislike(): void
    {
        $ip = request()->ip();
        $userAgent = request()->userAgent();
        if ($this->comment->isDisliked()) {
            $this->comment->removeDislike();

            $this->count--;
        } elseif (auth()->user()) {
            $this->comment->dislikes()->create([
                'user_id' => auth()->id(),
            ]);

            $this->count++;
        } elseif ($ip && $userAgent) {
            $this->comment->dislikes()->create([
                'ip' => $ip,
                'user_agent' => $userAgent,
            ]);

            $this->count++;
        }
    }

    /**
     * @return Factory|Application|View|\Illuminate\Contracts\Foundation\Application|null
     */
    public function render(
    ): \Illuminate\Contracts\View\Factory|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\View|\Illuminate\Contracts\Foundation\Application|null
    {
        return view('commentify::livewire.dislike');
    }

}
