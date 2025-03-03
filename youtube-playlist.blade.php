<div class="container mx-auto p-4">
    <div class="flex flex-col md:flex-row gap-6">
        <!-- Video Player -->
        <div class="w-full md:w-2/3">
            @if($currentVideoIndex !== null)
                <div class="relative pb-[56.25%] h-0">
                    <iframe 
                        id="youtube-player"
                        class="absolute top-0 left-0 w-full h-full"
                        src="https://www.youtube.com/embed/{{ $videos[$currentVideoIndex]['id'] }}?enablejsapi=1&autoplay=1" 
                        frameborder="0" 
                        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" 
                        allowfullscreen>
                    </iframe>
                </div>
                <h2 class="text-xl mt-2 font-bold">{{ $videos[$currentVideoIndex]['title'] }}</h2>
            @else
                <div class="bg-gray-200 rounded-lg flex items-center justify-center aspect-video">
                    <p class="text-gray-600 text-lg">Select a video to play</p>
                </div>
            @endif
        </div>

        <!-- Playlist -->
        <div class="w-full md:w-1/3">
            <h3 class="text-lg font-semibold mb-3">Playlist</h3>
            <div class="bg-gray-100 rounded-lg p-4 h-[400px] overflow-y-auto">
                <ul>
                    @foreach($videos as $index => $video)
                        <li 
                            class="p-2 mb-2 rounded hover:bg-gray-200 cursor-pointer {{ $currentVideoIndex === $index ? 'bg-blue-100' : '' }}"
                            wire:click="playVideo({{ $index }})"
                        >
                            <div class="flex items-center">
                                <div class="flex-shrink-0 w-24">
                                    <img src="https://img.youtube.com/vi/{{ $video['id'] }}/default.jpg" alt="{{ $video['title'] }}" class="w-full rounded">
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm font-medium">{{ $video['title'] }}</p>
                                </div>
                            </div>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('livewire:load', function() {
        window.addEventListener('message', function(event) {
            // Check if message is from YouTube API
            if (event.origin !== "https://www.youtube.com") return;
            
            try {
                const data = JSON.parse(event.data);
                // Check if video ended
                if (data.event === "onStateChange" && data.info === 0) {
                    // Video ended, go to next video
                    @this.nextVideo();
                }
            } catch (e) {
                // Not a JSON object or other error, ignore
            }
        });
    });

    // Load YouTube API
    var tag = document.createElement('script');
    tag.src = "https://www.youtube.com/iframe_api";
    var firstScriptTag = document.getElementsByTagName('script')[0];
    firstScriptTag.parentNode.insertBefore(tag, firstScriptTag);

    var player;

    function onYouTubeIframeAPIReady() {
        // The player might not exist yet when the first video loads
        initPlayer();
    }

    // Initialize player whenever the iframe source changes
    Livewire.on('videoChanged', () => {
        setTimeout(initPlayer, 500); // Give the iframe time to load
    });

    function initPlayer() {
        const iframe = document.getElementById('youtube-player');
        if (!iframe) return;
        
        player = new YT.Player('youtube-player', {
            events: {
                'onStateChange': onPlayerStateChange
            }
        });
    }

    function onPlayerStateChange(event) {
        if (event.data === YT.PlayerState.ENDED) {
            @this.nextVideo();
        }
    }
</script>
