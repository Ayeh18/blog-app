<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ $post->title }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if (session('success'))
                <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 text-gray-900">
                    <div class="mb-4">
                        <p class="text-sm text-gray-500 mb-4">
                            By {{ $post->user->name }} | {{ $post->created_at->diffForHumans() }}
                        </p>
                        <div class="prose max-w-none">
                            {{ $post->content }}
                        </div>
                    </div>

                    @auth
                        @if ($post->user_id === Auth::id())
                            <div class="mt-6 flex gap-2">
                                <a href="{{ route('posts.edit', $post) }}"
                                    class="bg-yellow-500 hover:bg-yellow-700 text-white font-bold py-2 px-4 rounded">
                                    Edit
                                </a>
                                <form method="POST" action="{{ route('posts.destroy', $post) }}"
                                    onsubmit="return confirm('Are you sure?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                                        Delete
                                    </button>
                                </form>
                            </div>
                        @endif
                    @endauth
                </div>
            </div>

            <!-- Comments Section -->
            <div id="comments" class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-bold mb-4">Comments ({{ $post->comments->count() }})</h3>

                    @auth
                        <form method="POST" action="{{ route('comments.store', $post) }}" class="mb-6">
                            @csrf
                            <div class="mb-4">
                                <textarea name="content" rows="3" placeholder="Add a comment..."
                                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('content') border-red-500 @enderror">{{ old('content') }}</textarea>
                                @error('content')
                                    <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <button type="submit"
                                class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                Add Comment
                            </button>
                        </form>
                    @else
                        <p class="mb-6 text-gray-600">Please <a href="{{ route('login') }}"
                                class="text-blue-600 hover:text-blue-800">login</a> to comment.</p>
                    @endauth

                    <div class="space-y-4">
                        @forelse ($post->comments as $comment)
                            <div class="border-l-4 border-gray-300 pl-4 py-2">
                                <p class="text-gray-700">{{ $comment->content }}</p>
                                <div class="flex items-center justify-between mt-2">
                                    <p class="text-sm text-gray-500">
                                        {{ $comment->user ? $comment->user->name : 'Unknown User' }} |
                                        {{ $comment->created_at->diffForHumans() }}
                                    </p>
                                    @auth
                                        @if ($comment->user_id === Auth::id())
                                            <form method="POST" action="{{ route('comments.destroy', $comment) }}"
                                                onsubmit="return confirm('Are you sure?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-800 text-sm">
                                                    Delete
                                                </button>
                                            </form>
                                        @endif
                                    @endauth
                                </div>
                            </div>
                        @empty
                            <p class="text-gray-500">No comments yet. Be the first to comment!</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
