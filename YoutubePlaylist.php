<?php

namespace App\Http\Livewire;

use Livewire\Component;

class YoutubePlaylist extends Component
{
    public $videos = [];
    public $currentVideoIndex = null;
    public $isPlaying = false;

    public function mount()
    {
        // Example video data - you would typically load this from your database
        $this->videos = [
            [
                'id' => 'dQw4w9WgXcQ',
                'title' => 'Rick Astley - Never Gonna Give You Up',
            ],
            [
                'id' => 'jNQXAC9IVRw',
                'title' => 'Me at the zoo',
            ],
            [
                'id' => 'L_LUpnjgPso',
                'title' => 'Charlie bit my finger',
            ],
            [
                'id' => 'kJQP7kiw5Fk',
                'title' => 'Luis Fonsi - Despacito ft. Daddy Yankee',
            ],
        ];
    }

    public function playVideo($index)
    {
        $this->currentVideoIndex = $index;
        $this->isPlaying = true;
    }

    public function nextVideo()
    {
        if ($this->currentVideoIndex === null) {
            return;
        }

        // Calculate next index, loop back to 0 if at the end
        $nextIndex = ($this->currentVideoIndex + 1) % count($this->videos);
        $this->playVideo($nextIndex);
    }

    public function render()
    {
        return view('livewire.youtube-playlist');
    }
}
