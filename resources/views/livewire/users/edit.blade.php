<?php

declare(strict_types=1);

?>
<div class="px-20">
    <form wire:submit.prevent>
        {{ $this->form }}
        <div class="mt-5">
            <a
                href="{{ route('users.show', $user) }}"
                wire:navigate
                class='py-2.5 px-6 mr-5 text-sm bg-blue-50 text-blue-400 rounded-lg cursor-pointer font-semibold text-center shadow-xs transition-all duration-500 hover:bg-indigo-100'>
                Cancel
            </a>
            <button
                wire:click="save"
                class='py-2.5 px-6 text-sm bg-orange-50 text-orange-500 rounded-lg cursor-pointer font-semibold text-center shadow-xs transition-all duration-500 hover:bg-indigo-100'>
                Submit
            </button>
        </div>
    </form>

    <x-filament-actions::modals />
</div>
<?php 
