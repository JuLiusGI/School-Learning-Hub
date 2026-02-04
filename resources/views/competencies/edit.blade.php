<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Edit Competency
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form method="POST" action="{{ route('competencies.update', $competency) }}" class="space-y-6">
                        @csrf
                        @method('PUT')

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                            <div>
                                <x-input-label for="subject_id" value="Subject" />
                                <select id="subject_id" name="subject_id" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
                                    <option value="">Select subject</option>
                                    @foreach ($subjects as $subject)
                                        <option value="{{ $subject->id }}" @selected(old('subject_id', $competency->subject_id) == $subject->id)>{{ $subject->name }}</option>
                                    @endforeach
                                </select>
                                <x-input-error class="mt-2" :messages="$errors->get('subject_id')" />
                            </div>
                            <div>
                                <x-input-label for="grade_id" value="Grade" />
                                <select id="grade_id" name="grade_id" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
                                    <option value="">Select grade</option>
                                    @foreach ($grades as $grade)
                                        <option value="{{ $grade->id }}" @selected(old('grade_id', $competency->grade_id) == $grade->id)>{{ $grade->level }}</option>
                                    @endforeach
                                </select>
                                <x-input-error class="mt-2" :messages="$errors->get('grade_id')" />
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                            <div>
                                <x-input-label for="code" value="Code" />
                                <x-text-input id="code" name="code" type="text" class="mt-1 block w-full" value="{{ old('code', $competency->code) }}" required />
                                <x-input-error class="mt-2" :messages="$errors->get('code')" />
                            </div>
                            <div>
                                <x-input-label for="matatag_tag" value="MATATAG Tag" />
                                <x-text-input id="matatag_tag" name="matatag_tag" type="text" class="mt-1 block w-full" value="{{ old('matatag_tag', $competency->matatag_tag) }}" required />
                                <x-input-error class="mt-2" :messages="$errors->get('matatag_tag')" />
                            </div>
                        </div>

                        <div>
                            <x-input-label for="description" value="Description" />
                            <textarea id="description" name="description" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" rows="5" required>{{ old('description', $competency->description) }}</textarea>
                            <x-input-error class="mt-2" :messages="$errors->get('description')" />
                        </div>

                        <div class="flex items-center gap-3">
                            <x-primary-button>
                                Update Competency
                            </x-primary-button>
                            <a href="{{ route('competencies.index') }}" class="text-sm text-gray-600 hover:text-gray-900">
                                Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
