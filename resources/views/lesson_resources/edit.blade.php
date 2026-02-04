<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Edit Lesson Resource
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form method="POST" action="{{ route('lesson-resources.update', $lessonResource) }}" class="space-y-6" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div>
                            <x-input-label for="lesson_id" value="Lesson" />
                            <select id="lesson_id" name="lesson_id" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
                                <option value="">Select lesson</option>
                                @foreach ($lessons as $lesson)
                                    <option value="{{ $lesson->id }}" @selected(old('lesson_id', $lessonResource->lesson_id) == $lesson->id)>
                                        {{ $lesson->title }} - {{ $lesson->subject?->name }} ({{ $lesson->grade?->level }}) / {{ $lesson->schoolYear?->name }}
                                    </option>
                                @endforeach
                            </select>
                            <x-input-error class="mt-2" :messages="$errors->get('lesson_id')" />
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                            <div>
                                <x-input-label for="title" value="Title (Optional)" />
                                <x-text-input id="title" name="title" type="text" class="mt-1 block w-full" value="{{ old('title', $lessonResource->title) }}" />
                                <x-input-error class="mt-2" :messages="$errors->get('title')" />
                            </div>
                            <div>
                                <x-input-label for="type" value="Type" />
                                <x-text-input id="type" name="type" type="text" class="mt-1 block w-full" value="{{ old('type', $lessonResource->type) }}" required />
                                <x-input-error class="mt-2" :messages="$errors->get('type')" />
                            </div>
                        </div>

                        <div>
                            <x-input-label for="file" value="Replace File (Optional)" />
                            <input id="file" name="file" type="file" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" />
                            <div class="mt-2 text-sm text-gray-600">
                                Current file:
                                <a href="{{ Storage::url($lessonResource->file_path) }}" class="text-indigo-600 hover:text-indigo-900" target="_blank" rel="noopener">
                                    View
                                </a>
                            </div>
                            <x-input-error class="mt-2" :messages="$errors->get('file')" />
                        </div>

                        <div class="flex items-center gap-3">
                            <x-primary-button>
                                Update Resource
                            </x-primary-button>
                            <a href="{{ route('lesson-resources.index') }}" class="text-sm text-gray-600 hover:text-gray-900">
                                Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
