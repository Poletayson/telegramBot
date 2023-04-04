<x-layer :title="__('Not Found')" :styles="$styles ?? null">
    <div class="relative flex items-top justify-center min-h-screen bg-gray-100 dark:bg-gray-900 sm:items-center sm:pt-0">
        <div class="max-w-100 mx-auto sm:px-6 lg:px-8">
            <div class="flex items-center pt-8 sm:justify-start sm:pt-0">
                <div class="px-4 text-lg text-gray-500 border-r border-gray-400 tracking-wider">
                    404
                </div>

                <div class="ml-4 text-lg text-gray-500 uppercase tracking-wider">
                    @if(isset($exception)){{$exception->getMessage()}}
                    @else Not Found
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-layer>
