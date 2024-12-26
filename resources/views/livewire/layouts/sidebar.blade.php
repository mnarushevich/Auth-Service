<div class="hidden md:flex flex-col w-64 bg-gray-800">
    <div class="flex items-center justify-center h-16 bg-gray-900">
        <span class="text-white font-bold uppercase">Auth Service</span>
    </div>
    <div class="flex flex-col flex-1 overflow-y-auto">
        <nav class="flex-1 px-2 py-4 bg-gray-800">
            <a href="{{ route('users.index') }}" wire:navigate
               class="flex items-center px-4 py-2 text-gray-100 hover:bg-gray-700">Users</a>
        </nav>
    </div>
</div>
