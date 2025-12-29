<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('All Posts') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if (session('success'))
                <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                    {{ session('success') }}
                </div>
            @endif

            @auth
                <div class="mb-4">
                    <a href="{{ route('posts.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                        Create New Post
                    </a>
                </div>
            @endauth

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    @forelse ($posts as $post)
                        <div class="mb-6 pb-6 border-b">
                            <h3 class="text-xl font-bold mb-2">
                                <a href="{{ route('posts.show', $post) }}" class="text-blue-600 hover:text-blue-800">
                                    {{ $post->title }}
                                </a>
                            </h3>
                            <p class="text-gray-600 mb-2">{{ Str::limit($post->content, 200) }}</p>
                            <div class="flex items-center justify-between">
                                <p class="text-sm text-gray-500">
                                    By {{ $post->user ? $post->user->name : 'Unknown User' }} | {{ $post->created_at->diffForHumans() }}
                                </p>
                                <div class="flex items-center gap-3">
                                    <a href="{{ route('posts.show', $post) }}#comments" class="inline-flex items-center text-gray-600 hover:text-blue-600 text-sm">
                                        <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                                        </svg>
                                        {{ $post->comments->count() }} {{ Str::plural('Comment', $post->comments->count()) }}
                                    </a>
                                </div>
                            </div>
                        </div>
                    @empty
                        <p class="text-gray-500">No posts yet. Be the first to create one!</p>
                    @endforelse

                    <div class="mt-4">
                        {{ $posts->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>