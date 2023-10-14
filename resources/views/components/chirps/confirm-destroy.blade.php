@props(['chirp'])

<div
    _="on closeModal(destroy)
            add .animate-fade-out
            then wait for animationend
            then if destroy remove closest .chirp else remove me
        end
        on click if (event.target == event.currentTarget) trigger closeModal"
    class="modal fixed z-10 inset-0 overflow-y-auto flex justify-center items-center bg-black bg-opacity-50 animate-fade-in"
    style="backdrop-filter: blur(14px);">
    <div class="bg-white rounded p-6">
        <h2 class="text-xl border-b pb-2 mb-2">Confirm Action</h2>
        <p>Are you sure you want to delete this chirp?</p>
        <div class="flex justify-end mt-4 gap-4">
            <x-secondary-button _="on click trigger closeModal" >Cancel</x-secondary-button>
            <form>
                @csrf
                <x-danger-button
                    hx-delete="{{route('chirps.destroy', $chirp)}}"
                    hx-target="closest .chirp"
                    hx-swap="none"
                    _="on htmx:afterRequest trigger closeModal(destroy:true)"
                    >
                        Delete
                </x-danger-button>
            </form>
        </div>
    </div>
</div>
