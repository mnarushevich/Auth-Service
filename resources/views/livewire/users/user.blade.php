<div>
    <div class="container mx-auto px-4">
        <div class="flex flex-wrap -mx-1 lg:-mx-4">
            <div class="my-1 px-1 w-full lg:w-1/2">
                <div class="p-4 bg-white border rounded shadow">
                    <div class="flex items  -center">
{{--                        <div class="flex-shrink-0 mr-4">--}}
{{--                            <img class="w-12 h-12 rounded-full" src="{{ $user->profile_photo_url }}" alt="{{ $user->name }}">--}}
{{--                        </div>--}}
                        <div class="flex-1">
                            <h2 class="text-lg font-semibold text-gray-900">{{ $user->full_name }}</h2>
                            <p class="text-sm font-medium text-gray-500">{{ $user->email }}</p>
                        </div>

                        <div class="flex items  -center">
                            <a href="{{ route('users.edit', $user) }}" wire:navigate class="text-indigo-600 hover:text-indigo-900">Edit</a>

{{--                            <form action="{{ route('users.destroy', $user) }}" method="POST" class="ml-4">--}}
{{--                                @csrf--}}
{{--                                @method('DELETE')--}}
{{--                                <button type="submit" class="text-red-600 hover:text-red-900">Delete</button>--}}
{{--                            </form>--}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
