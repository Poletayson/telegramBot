<x-layer :title="__('Server Error')" :styles="['/css/exception.css']">
    <div class="relative flex items-top justify-center min-h-screen bg-gray-100 dark:bg-gray-900 sm:items-center sm:pt-0">
        @php
            if(isset($exception)) {
                if(strlen($exception->getMessage()) > 150)
                    $widthClass = 'max-w-100';
                elseif(strlen($exception->getMessage()) > 80)
                    $widthClass = 'max-w-75';
                else
                    $widthClass = 'max-w-50';
            } else {
                $widthClass = 'max-w-100';
            }
        @endphp
        <div class="{{$widthClass}} mx-auto sm:px-6 lg:px-8">
            <div class="flex items-center pt-8 sm:justify-start sm:pt-0">
                <div class="px-4 text-lg text-gray-500 border-r border-gray-400 tracking-wider">
                    500
                </div>

                <div class="ml-4 text-lg text-gray-500 uppercase tracking-wider">
                    @if(isset($exception)){{$exception->getMessage()}}
                    @else Server Error
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-layer>
