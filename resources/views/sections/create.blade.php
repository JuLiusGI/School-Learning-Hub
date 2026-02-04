<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Add Section
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form method="POST" action="{{ route('sections.store') }}" class="space-y-6">
                        @csrf

                        <div>
                            <x-input-label for="grade_id" value="Grade" />
                            <select id="grade_id" name="grade_id" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
                                <option value="">Select grade</option>
                                @foreach ($grades as $grade)
                                    <option value="{{ $grade->id }}" @selected(old('grade_id') == $grade->id)>{{ $grade->level }}</option>
                                @endforeach
                            </select>
                            <x-input-error class="mt-2" :messages="$errors->get('grade_id')" />
                        </div>

                        <div>
                            <x-input-label for="name" value="Section Name" />
                            <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" value="{{ old('name') }}" required />
                            <x-input-error class="mt-2" :messages="$errors->get('name')" />
                        </div>

                        <div>
                            <x-input-label for="adviser_user_id" value="Adviser" />
                            <select id="adviser_user_id" name="adviser_user_id" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                <option value="">Unassigned</option>
                                @foreach ($advisers as $adviser)
                                    <option value="{{ $adviser->id }}" @selected(old('adviser_user_id') == $adviser->id)>{{ $adviser->name }}</option>
                                @endforeach
                            </select>
                            <x-input-error class="mt-2" :messages="$errors->get('adviser_user_id')" />
                        </div>

                        <div class="flex items-center gap-3">
                            <x-primary-button>
                                Save Section
                            </x-primary-button>
                            <a href="{{ route('sections.index') }}" class="text-sm text-gray-600 hover:text-gray-900">
                                Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
