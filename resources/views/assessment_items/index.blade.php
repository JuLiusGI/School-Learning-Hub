<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Assessment Items
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="flex items-center justify-between mb-6">
                        <form method="GET" action="{{ route('assessment-items.index') }}" class="flex items-end gap-4">
                            <div>
                                <x-input-label for="assessment_id" value="Assessment" />
                                <select id="assessment_id" name="assessment_id" class="mt-1 block w-72 border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                    <option value="">All assessments</option>
                                    @foreach ($assessments as $assessment)
                                        <option value="{{ $assessment->id }}" @selected($filters['assessment_id'] == $assessment->id)>
                                            {{ $assessment->title }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <x-primary-button>
                                Filter
                            </x-primary-button>
                        </form>
                        <a href="{{ route('assessment-items.create') }}">
                            <x-primary-button>
                                Add Item
                            </x-primary-button>
                        </a>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full text-sm">
                            <thead>
                                <tr class="border-b">
                                    <th class="px-4 py-2 text-left">Assessment</th>
                                    <th class="px-4 py-2 text-left">Item No</th>
                                    <th class="px-4 py-2 text-left">Question</th>
                                    <th class="px-4 py-2 text-left">Points</th>
                                    <th class="px-4 py-2 text-right">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($items as $item)
                                    <tr class="border-b">
                                        <td class="px-4 py-2">{{ $item->assessment?->title }}</td>
                                        <td class="px-4 py-2">{{ $item->item_no }}</td>
                                        <td class="px-4 py-2">{{ $item->question_text }}</td>
                                        <td class="px-4 py-2">{{ $item->points }}</td>
                                        <td class="px-4 py-2 text-right">
                                            <div class="inline-flex items-center gap-2">
                                                <a href="{{ route('assessment-items.edit', $item) }}">
                                                    <x-secondary-button>
                                                        Edit
                                                    </x-secondary-button>
                                                </a>
                                                <form method="POST" action="{{ route('assessment-items.destroy', $item) }}">
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
