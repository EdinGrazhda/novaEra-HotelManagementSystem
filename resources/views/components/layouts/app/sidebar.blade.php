<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        @include('partials.head')
    </head>
    <body class="min-h-screen bg-white dark:bg-zinc-800">
        <flux:sidebar sticky stashable class="border-e border-zinc-200 bg-zinc-50 dark:border-zinc-700 dark:bg-zinc-900">
            <flux:sidebar.toggle class="lg:hidden" icon="x-mark" />

            <a href="{{ route('dashboard') }}" class="me-5 flex items-center space-x-2 rtl:space-x-reverse" wire:navigate>
                <x-app-logo />
            </a>

            <flux:navlist variant="outline">
                @role('admin')
                <flux:navlist.group :heading="__('Platform')" class="grid">
                    <flux:navlist.item icon="home" :href="route('dashboard')" :current="request()->routeIs('dashboard')" wire:navigate>{{ __('Dashboard') }}</flux:navlist.item>
                    <flux:navlist.item icon="numbered-list" :href="route('rooms.index')" :current="request()->routeIs('rooms.index')" wire:navigate>{{ __('Rooms') }}</flux:navlist.item>
                    <flux:navlist.item icon="squares-plus" :href="route('menu.index')" :current="request()->routeIs('menu.index')" wire:navigate>{{ __('Menu') }}</flux:navlist.item>
                </flux:navlist.group>
                @endrole

                @role('receptionist')
                <flux:navlist.group :heading="__('Platform')" class="grid">
                    <flux:navlist.item icon="numbered-list" :href="route('rooms.index')" :current="request()->routeIs('rooms.index')" wire:navigate>{{ __('Rooms') }}</flux:navlist.item>
                    <flux:navlist.item icon="squares-plus" :href="route('menu.index')" :current="request()->routeIs('menu.index')" wire:navigate>{{ __('Menu') }}</flux:navlist.item>
                </flux:navlist.group>
                @endrole
                
                <flux:navlist.group :heading="__('Services')" class="grid">
                    @role(['admin', 'receptionist', 'cleaner'])
                    <flux:navlist.item 
                        icon="pencil-square" 
                        :href="route('cleaning.index')" 
                        :current="request()->routeIs('cleaning.index')" 
                        wire:navigate
                        onclick="sessionStorage.setItem('sidebarNavigation', 'cleaning');">
                        {{ __('Cleaning Service') }}
                    </flux:navlist.item>
                    @endrole

                    @role(['admin', 'receptionist', 'chef'])
                    <flux:navlist.item 
                        icon="rectangle-group" 
                        :href="route('menuService.index')" 
                        :current="request()->routeIs('menuService.index')" >
                        {{ __('Menu Service') }}
                    </flux:navlist.item>
                    @endrole
                    
                    @role(['admin', 'receptionist'])
                    <flux:navlist.item 
                        icon="calendar" 
                        :href="route('calendar.index')" 
                        :current="request()->routeIs('calendar.index')" >
                        {{ __('Room Calendar') }}
                    </flux:navlist.item>
                    @endrole

                    @role('admin')
                    <flux:navlist.group :heading="__('Roles & Permissions')" class="grid">
                        @can('manage-roles')
                        <flux:navlist.item icon="shield-check" :href="route('roles.index')" :current="request()->routeIs('roles.index')">{{ __('Manage Roles') }}</flux:navlist.item>
                        @endcan
                       
                    </flux:navlist.group>
                    @endrole

                       @role('admin')
                    <flux:navlist.group :heading="__('User Management')" class="grid">
                        @can('manage-users')
                        <flux:navlist.item icon="users" :href="route('users.index')" :current="request()->routeIs('users.*')">{{ __('Manage Users') }}</flux:navlist.item>
                        @endcan
                    </flux:navlist.group>
                    @endrole
                    
                </flux:navlist.group>
                
            </flux:navlist>

            <flux:spacer />

     

            <!-- Desktop User Menu -->
            <flux:dropdown position="bottom" align="start">
                <flux:profile
                    :name="auth()->user()->name"
                    :initials="auth()->user()->initials()"
                    icon-trailing="chevrons-up-down"
                />

                <flux:menu class="w-[220px]">
                    <flux:menu.radio.group>
                        <div class="p-0 text-sm font-normal">
                            <div class="flex items-center gap-2 px-1 py-1.5 text-start text-sm">
                                <span class="relative flex h-8 w-8 shrink-0 overflow-hidden rounded-lg">
                                    <span
                                        class="flex h-full w-full items-center justify-center rounded-lg bg-neutral-200 text-black dark:bg-neutral-700 dark:text-white"
                                    >
                                        {{ auth()->user()->initials() }}
                                    </span>
                                </span>

                                <div class="grid flex-1 text-start text-sm leading-tight">
                                    <span class="truncate font-semibold">{{ auth()->user()->name }}</span>
                                    <span class="truncate text-xs">{{ auth()->user()->email }}</span>
                                </div>
                            </div>
                        </div>
                    </flux:menu.radio.group>

                    <flux:menu.separator />

                    <flux:menu.radio.group>
                        <flux:menu.item :href="route('settings.profile')" icon="cog" wire:navigate>{{ __('Settings') }}</flux:menu.item>
                    </flux:menu.radio.group>

                    <flux:menu.separator />

                    <form method="POST" action="{{ route('logout') }}" class="w-full">
                        @csrf
                        <flux:menu.item as="button" type="submit" icon="arrow-right-start-on-rectangle" class="w-full">
                            {{ __('Log Out') }}
                        </flux:menu.item>
                    </form>
                </flux:menu>
            </flux:dropdown>
        </flux:sidebar>

        <!-- Mobile User Menu -->
        <flux:header class="lg:hidden">
            <flux:sidebar.toggle class="lg:hidden" icon="bars-2" inset="left" />

            <flux:spacer />

            <flux:dropdown position="top" align="end">
                <flux:profile
                    :initials="auth()->user()->initials()"
                    icon-trailing="chevron-down"
                />

                <flux:menu>
                    <flux:menu.radio.group>
                        <div class="p-0 text-sm font-normal">
                            <div class="flex items-center gap-2 px-1 py-1.5 text-start text-sm">
                                <span class="relative flex h-8 w-8 shrink-0 overflow-hidden rounded-lg">
                                    <span
                                        class="flex h-full w-full items-center justify-center rounded-lg bg-neutral-200 text-black dark:bg-neutral-700 dark:text-white"
                                    >
                                        {{ auth()->user()->initials() }}
                                    </span>
                                </span>

                                <div class="grid flex-1 text-start text-sm leading-tight">
                                    <span class="truncate font-semibold">{{ auth()->user()->name }}</span>
                                    <span class="truncate text-xs">{{ auth()->user()->email }}</span>
                                </div>
                            </div>
                        </div>
                    </flux:menu.radio.group>

                    <flux:menu.separator />

                    <flux:menu.radio.group>
                        <flux:menu.item :href="route('settings.profile')" icon="cog" wire:navigate>{{ __('Settings') }}</flux:menu.item>
                    </flux:menu.radio.group>

                    <flux:menu.separator />

                    <form method="POST" action="{{ route('logout') }}" class="w-full">
                        @csrf
                        <flux:menu.item as="button" type="submit" icon="arrow-right-start-on-rectangle" class="w-full">
                            {{ __('Log Out') }}
                        </flux:menu.item>
                    </form>
                </flux:menu>
            </flux:dropdown>
        </flux:header>

        {{ $slot }}

        @fluxScripts
    </body>
</html>
