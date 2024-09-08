@props([
    'light_logo' => null,
    'dark_logo' => null,
    'logo_height' => null,
])



<figure class="flex items-center justify-center gap-4 ">
    @php
        $light_logo  = \Filament\Facades\Filament::getPanel()->getBrandLogo();
        $dark_logo  = \Filament\Facades\Filament::getPanel()->getDarkModeBrandLogo();
        $logo_height  = \Filament\Facades\Filament::getPanel()->getBrandLogoHeight();
    @endphp
    @if($light_logo)
        <img src="{{ asset($light_logo) }}"
             alt="Logo"
             style="height: {{ $logo_height }}"
             class="dark:hidden">
    @endif
    @if($dark_logo)
        <img src="{{ asset($dark_logo) }}"
             alt="Logo"
             style="height: {{ $logo_height }}"
             class="hidden dark:flex">
    @endif
</figure>
