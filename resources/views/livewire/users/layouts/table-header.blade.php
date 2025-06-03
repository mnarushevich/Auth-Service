<?php

declare(strict_types=1);

?>
<div class="flex items-center justify-between h-16 bg-white border-b border-gray-200">
    <div class="flex items-center px-4">
        Users List
    </div>
    <div class="flex items-center pr-4">
        <a
            href="{{route('users.create')}}"
            wire:navigate
            class='py-2.5 px-6 text-sm bg-green-50 text-green-500 rounded-lg cursor-pointer font-semibold text-center shadow-xs transition-all duration-500 hover:bg-indigo-100'>
            Create
        </a>
    </div>
</div>
<?php 
