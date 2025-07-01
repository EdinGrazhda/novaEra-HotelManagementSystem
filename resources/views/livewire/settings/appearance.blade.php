<section class="w-full">
    @include('partials.settings-heading')

    <x-settings.layout :heading="__('Appearance')" :subheading=" __('Update the appearance settings for your account')">
        <flux:radio.group x-data="{}" x-init="() => { setTimeout(() => { $flux.appearance = 'light' }, 0); }" variant="segmented" x-model="$flux.appearance">
            <flux:radio value="light" icon="sun">Light</flux:radio>
            <flux:radio value="dark" icon="moon">Dark</flux:radio>
            <flux:radio value="system" icon="computer-desktop">System</flux:radio>
        </flux:radio.group>
        
        <script>
            // Force light mode selection in the appearance settings
            document.addEventListener('livewire:initialized', function() {
                setTimeout(function() {
                    if (window.$flux && window.$flux.appearance) {
                        window.$flux.appearance = 'light';
                    }
                }, 100);
            });
        </script>
    </x-settings.layout>
</section>
