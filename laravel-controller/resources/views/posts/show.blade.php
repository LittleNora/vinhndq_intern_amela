<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">

                    <h1 class="text-center font-bold">{{ $post->title }}</h1>
                    <p class="text-center text-gray-500 dark:text-gray-400">{{ $post->user->name }}</p>

                    <div class="my-5">
                        <a href="{{ route('posts.index') }}"
                           class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            Back
                        </a>

                        @can('update', $post)
                            <a href="{{ route('posts.edit', $post->id) }}"
                               class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                Edit
                            </a>
                        @endcan

                        @can('delete', $post)
                            <form action="{{ route('posts.destroy', $post->id) }}" method="POST"
                                  class="inline"
                                  onclick="return confirm('{{ __("Really want to delete?") }}')"
                            >
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                        class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                                    Delete
                                </button>
                            </form>
                        @endcan
                    </div>

                    <p class="text-gray-500 dark:text-gray-400">{{ $post->content }}</p>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>
