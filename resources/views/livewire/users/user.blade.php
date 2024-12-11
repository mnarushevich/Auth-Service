<div class="px-20">
    <div class="bg-white overflow-hidden shadow rounded-lg border">
        <div class="flex items-center justify-between h-16 bg-white border-b border-gray-200">
            <div class="flex items-center">
                <div class="px-4 py-5 sm:px-6">
                    <h3 class="text-lg leading-6 font-medium text-gray-900">
                        {{ $user->full_name }}
                    </h3>
                    <p class="mt-1 max-w-2xl text-sm text-gray-500">
                        {{--                    This is some information about the user.--}}
                    </p>
                </div>
            </div>
            <div class="flex items-center pr-4">
                {{ ($this->deleteAction)(['user' => $user]) }}
                <x-filament-actions::modals/>

                <a
                    href="{{route('users.edit', $user)}}"
                    wire:navigate
                    class='py-2.5 ml-5 px-6 text-sm bg-orange-50 text-orange-500 rounded-lg cursor-pointer font-semibold text-center shadow-xs transition-all duration-500 hover:bg-indigo-100'>
                    Edit
                </a>
            </div>
        </div>
        <div class="border-t border-gray-200 px-4 py-5 sm:p-0">
            <dl class="sm:divide-y sm:divide-gray-200">
                <div class="py-3 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">
                        User ID:
                    </dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                        {{ $user->uuid }}
                    </dd>
                </div>
                <div class="py-3 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">
                        Email address
                    </dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                        {{ $user->email }}
                    </dd>
                </div>
                <div class="py-3 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">
                        Phone number
                    </dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                        {{ $user->phone }}
                    </dd>
                </div>
                <div class="py-3 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">
                        Country
                    </dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                        {{ $user->country }}
                    </dd>
                </div>
            </dl>
        </div>
    </div>
    <div class="bg-white overflow-hidden shadow rounded-lg border">
        <div class="border-t border-gray-200 px-4 py-5 sm:p-0">
            <dl class="sm:divide-y sm:divide-gray-200">
                <div class="py-3 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">
                        <button
                            wire:click="generateJWTToken"
                            class='py-2.5 px-2 text-sm bg-green-50 text-green-500 rounded-lg cursor-pointer font-semibold text-center shadow-xs transition-all duration-500 hover:bg-indigo-100'>
                            Generate JWT token
                        </button>
                    </dt>
                    <dd class="break-words p-2 mt-1 text-sm text-white  bg-gray-800 sm:mt-0 sm:col-span-2 font-mono rounded-md overflow-auto min-h-40">
                        {{ $token }}
                    </dd>
                </div>
            </dl>
        </div>
    </div>
</div>
