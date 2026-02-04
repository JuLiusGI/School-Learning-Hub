<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Edit Grade
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form method="POST" action="{{ route('grades.update', $grade) }}" class="space-y-6">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="updated_at" value="{{ $grade->updated_at?->toDateTimeString() }}">

                        <div>
                            <x-input-label for="level" value="Level" />
                            <x-text-input id="level" name="level" type="text" class="mt-1 block w-full" value="{{ old('level', $grade->level) }}" required />
                            <x-input-error class="mt-2" :messages="$errors->get('level')" />
                        </div>

                        <div class="flex items-center gap-3">
                            <x-primary-button>
                                Update Grade
                            </x-primary-button>
                            <a href="{{ route('grades.index') }}" class="text-sm text-gray-600 hover:text-gray-900">
                                Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
