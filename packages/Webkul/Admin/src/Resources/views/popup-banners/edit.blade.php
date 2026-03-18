<x-admin::layouts>
    <x-slot:title>
        @lang('admin::app.popup-banners.edit.title')
    </x-slot>

    <x-admin::form
        :action="route('admin.popup-banners.update', $banner->id)"
        enctype="multipart/form-data"
        method="PUT"
    >
        <div class="flex items-center justify-between gap-4 max-sm:flex-wrap">
            <p class="text-xl font-bold text-gray-800 dark:text-white">
                @lang('admin::app.popup-banners.edit.title')
            </p>

            <div class="flex items-center gap-x-2.5">
                <a
                    href="{{ route('admin.popup-banners.index') }}"
                    class="transparent-button"
                >
                    @lang('admin::app.popup-banners.edit.back-btn')
                </a>

                <button
                    type="submit"
                    class="primary-button"
                >
                    @lang('admin::app.popup-banners.edit.save-btn')
                </button>
            </div>
        </div>

        <div class="mt-3.5 flex gap-2.5 max-xl:flex-wrap">
            <!-- Left Section -->
            <div class="flex flex-1 flex-col gap-2.5">
                <!-- General Information -->
                <div class="box-shadow rounded-md border border-gray-200 bg-white p-4 dark:border-gray-800 dark:bg-white">
                    <p class="mb-4 text-base font-semibold text-gray-800 dark:text-white">
                        @lang('admin::app.popup-banners.edit.general-information')
                    </p>

                    <!-- Banner Title -->
                    <x-admin::form.control-group>
                        <x-admin::form.control-group.label>
                            @lang('admin::app.popup-banners.edit.title-field')
                        </x-admin::form.control-group.label>

                        <x-admin::form.control-group.control
                            type="text"
                            name="title"
                            :value="old('title', $banner->title)"
                            :placeholder="trans('admin::app.popup-banners.edit.title-placeholder')"
                        />

                        <x-admin::form.control-group.error control-name="title" />
                    </x-admin::form.control-group>

                    <!-- Banner Image -->
                    <x-admin::form.control-group>
                        <x-admin::form.control-group.label>
                            @lang('admin::app.popup-banners.edit.image')
                        </x-admin::form.control-group.label>

                        <x-admin::form.control-group.control
                            type="file"
                            name="image"
                            :value="old('image', $banner->image_path)"
                        />

                        <p class="mt-2 text-xs text-gray-500">
                            @lang('admin::app.popup-banners.edit.image-size', ['width' => 1600, 'height' => 675])
                        </p>

                        <x-admin::form.control-group.error control-name="image" />
                    </x-admin::form.control-group>

                    <!-- Banner Link -->
                    <x-admin::form.control-group>
                        <x-admin::form.control-group.label>
                            @lang('admin::app.popup-banners.edit.link')
                        </x-admin::form.control-group.label>

                        <x-admin::form.control-group.control
                            type="text"
                            name="link"
                            :value="old('link', $banner->link)"
                            :placeholder="trans('admin::app.popup-banners.edit.link-placeholder')"
                        />

                        <x-admin::form.control-group.error control-name="link" />
                    </x-admin::form.control-group>
                </div>
            </div>

            <!-- Right Section -->
            <div class="flex w-[320px] flex-col gap-2.5 max-w-[320px] max-lg:w-full">
                <!-- Status -->
                <div class="box-shadow rounded-md border border-gray-200 bg-white p-4 dark:border-gray-800 dark:bg-white">
                    <p class="mb-4 text-base font-semibold text-gray-800 dark:text-white">
                        @lang('admin::app.popup-banners.edit.status')
                    </p>

                    <!-- Active Status -->
                    <x-admin::form.control-group class="!mb-0">
                        <x-admin::form.control-group.label>
                            @lang('admin::app.popup-banners.edit.is-active')
                        </x-admin::form.control-group.label>

                        <x-admin::form.control-group.control
                            type="switch"
                            name="is_active"
                            :value="1"
                            :checked="old('is_active', $banner->is_active)"
                        />

                        <x-admin::form.control-group.error control-name="is_active" />
                    </x-admin::form.control-group>
                </div>

                <!-- Sort Order -->
                <div class="box-shadow rounded-md border border-gray-200 bg-white p-4 dark:border-gray-800 dark:bg-white">
                    <p class="mb-4 text-base font-semibold text-gray-800 dark:text-white">
                        @lang('admin::app.popup-banners.edit.sort-order')
                    </p>

                    <x-admin::form.control-group>
                        <x-admin::form.control-group.label>
                            @lang('admin::app.popup-banners.edit.sort-order')
                        </x-admin::form.control-group.label>

                        <x-admin::form.control-group.control
                            type="text"
                            name="sort_order"
                            :value="old('sort_order', $banner->sort_order)"
                        />

                        <x-admin::form.control-group.error control-name="sort_order" />
                    </x-admin::form.control-group>
                </div>
            </div>
        </div>
    </x-admin::form>
</x-admin::layouts>
