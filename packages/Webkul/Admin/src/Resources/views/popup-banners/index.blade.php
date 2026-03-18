<x-admin::layouts>
    <x-slot:title>
        @lang('admin::app.popup-banners.index.title')
    </x-slot>

    <div class="flex items-center justify-between gap-4 max-sm:flex-wrap">
        <p class="text-xl font-bold text-gray-800 dark:text-white">
            @lang('admin::app.popup-banners.index.title')
        </p>

        <div class="flex items-center gap-x-2.5">
            @if (bouncer()->hasPermission('popup-banners.create'))
                <a
                    href="{{ route('admin.popup-banners.create') }}"
                    class="primary-button"
                >
                    @lang('admin::app.popup-banners.index.create-btn')
                </a>
            @endif
        </div>
    </div>

    <!-- DataGrid -->
    <x-admin::datagrid
        :src="route('admin.popup-banners.index')"
    />
</x-admin::layouts>
