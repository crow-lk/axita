<div class="panel-side journal-scroll flex max-w-full overflow-x-auto overflow-y-hidden px-4 py-2 gap-4 max-xl:min-w-[270px]">
    <!-- Filter Block -->
    <div class="flex flex-col min-w-[200px]   pr-4">
        <div class="flex items-center justify-between mb-2">
            <p class="shimmer h-6 w-[60%]"></p>
            <span class="shimmer h-6 w-6"></span>
        </div>
        <div class="z-10 rounded-lg bg-white">
            <x-shop::shimmer.range-slider />
        </div>
    </div>

    <!-- Checkbox Filter Blocks -->
    @for ($i = 0; $i < 4; $i++)
        <div class="flex flex-col min-w-[200px] pr-4">
            <div class="flex items-center justify-between mb-2">
                <p class="shimmer h-[27px] w-3/5"></p>
               <span class="shimmer h-6 w-9"></span>
            </div>
            <div class="z-10 grid rounded-lg bg-white pb-3">
                @for ($j = 0; $j < 1; $j++)
                    <div class="flex items-center gap-x-4 mb-2">
                        <div class="shimmer h-5 w-9 rounded"></div>
                        <div class="p-2">
                            <div class="shimmer h-5 w-[100px]"></div>
                        </div>
                    </div>
                @endfor
            </div>
        </div>
    @endfor
</div>
