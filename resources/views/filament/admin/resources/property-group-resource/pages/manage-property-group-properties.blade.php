<x-filament-panels::page>
    <div class="space-y-6">
        {{ $this->form }}
        
        <div class="flex justify-end space-x-2">
            <x-filament::button
                wire:click="assignProperties"
                color="success"
            >
                تعيين الخصائص السريع
            </x-filament::button>
            
            <x-filament::button
                wire:click="saveProperties"
                color="primary"
            >
                حفظ الخصائص
            </x-filament::button>
        </div>
    </div>
</x-filament-panels::page>
