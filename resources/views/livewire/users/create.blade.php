<div class="px-20">
    <form wire:submit="create">
        {{ $this->form }}
        <button
            type="submit"
            class='py-2.5 mt-6 px-6 text-sm bg-orange-50 text-orange-500 rounded-lg cursor-pointer font-semibold text-center shadow-xs transition-all duration-500 hover:bg-indigo-100'>
            Submit
        </button>
    </form>

    <x-filament-actions::modals />
</div>
