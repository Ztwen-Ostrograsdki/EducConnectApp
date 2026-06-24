@if ($show)
    <div class="fixed inset-0 z-50 flex items-center justify-center">

        {{-- Overlay --}}
        <div class="absolute inset-0 bg-black/90" wire:click="{{ $closeAction }}">
        </div>

        {{-- Modal --}}
        <div class="relative w-full max-w-md mx-4 bg-gray-900/70 rounded-xl shadow-2xl">

            {{-- Header --}}
            <div class="p-3 py-5">
                <h5 class="text-md font-bold text-sky-500 border-b">
                    {{ $title }}
                </h5>
            </div>

            {{-- Body --}}
            <div class="p-6">
                {{ $slot }}
            </div>

            {{-- Footer --}}
            <div class="flex justify-end gap-3 p-6">

                <button type="button" wire:click="{{ $closeAction }}"
                    class="px-4 py-2 text-black bg-gray-500 border rounded-lg hover:bg-gray-200">

                    {{ $cancelText }}
                </button>

                <button type="button" wire:click="{{ $confirmAction }}"
                    class="px-4 py-2 text-white bg-red-500 rounded-lg hover:bg-red-700">

                    {{ $confirmText }}
                </button>

            </div>

        </div>

    </div>
@endif

