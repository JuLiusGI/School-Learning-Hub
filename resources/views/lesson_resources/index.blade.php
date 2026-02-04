<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Lesson Resources
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="flex items-center justify-between mb-6">
                        <form method="GET" action="{{ route('lesson-resources.index') }}" class="flex items-end gap-4">
                            <div>
                                <x-input-label for="lesson_id" value="Lesson" />
                                <select id="lesson_id" name="lesson_id" class="mt-1 block w-72 border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                    <option value="">All lessons</option>
                                    @foreach ($lessons as $lesson)
                                        <option value="{{ $lesson->id }}" @selected($filters['lesson_id'] == $lesson->id)>{{ $lesson->title }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <x-primary-button>
                                Filter
                            </x-primary-button>
                        </form>
                        <a href="{{ route('lesson-resources.create') }}">
                            <x-primary-button>
                                Add Resource
                            </x-primary-button>
                        </a>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full text-sm">
                            <thead>
                                <tr class="border-b">
                                    <th class="px-4 py-2 text-left">Lesson</th>
                                    <th class="px-4 py-2 text-left">Subject</th>
                                    <th class="px-4 py-2 text-left">Grade</th>
                                    <th class="px-4 py-2 text-left">School Year</th>
                                    <th class="px-4 py-2 text-left">Title</th>
                                    <th class="px-4 py-2 text-left">Type</th>
                                    <th class="px-4 py-2 text-left">File</th>
                                    <th class="px-4 py-2 text-right">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($resources as $resource)
                                    <tr class="border-b">
                                        <td class="px-4 py-2">{{ $resource->lesson?->title }}</td>
                                        <td class="px-4 py-2">{{ $resource->lesson?->subject?->name }}</td>
                                        <td class="px-4 py-2">{{ $resource->lesson?->grade?->level }}</td>
                                        <td class="px-4 py-2">{{ $resource->lesson?->schoolYear?->name }}</td>
                                        <td class="px-4 py-2">{{ $resource->title ?? 'Untitled' }}</td>
                                        <td class="px-4 py-2">{{ $resource->type }}</td>
                                        <td class="px-4 py-2">
                                            <a href="{{ Storage::url($resource->file_path) }}" class="text-indigo-600 hover:text-indigo-900" target="_blank" rel="noopener">
                                                View
                                            </a>
                                        </td>
                                        <td class="px-4 py-2 text-right">
                                            <div class="inline-flex items-center gap-2">
                                                <a href="{{ route('lesson-resources.edit', $resource) }}">
                                                    <x-secondary-button>
                                                        Edit
                                                    </x-secondary-button>
                                                </a>
                                                <form method="POST" action="{{ route('lesson-resources.destroy', $resource) }}">
                                                    @csrf
                                                    @method('DELETE')
                                                    <x-danger-button>
                                                        Delete
                                                    </x-danger-button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
