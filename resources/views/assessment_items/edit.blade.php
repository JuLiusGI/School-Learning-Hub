<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Edit Assessment Item
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form method="POST" action="{{ route('assessment-items.update', $assessmentItem) }}" class="space-y-6">
                        @csrf
                        @method('PUT')

                        <div>
                            <x-input-label for="assessment_id" value="Assessment" />
                            <select id="assessment_id" name="assessment_id" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
                                <option value="">Select assessment</option>
                                @foreach ($assessments as $assessment)
                                    <option value="{{ $assessment->id }}" @selected(old('assessment_id', $assessmentItem->assessment_id) == $assessment->id)>
                                        {{ $assessment->title }}
                                    </option>
                                @endforeach
                            </select>
                            <x-input-error class="mt-2" :messages="$errors->get('assessment_id')" />
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                            <div>
                                <x-input-label for="item_no" value="Item No" />
                                <x-text-input id="item_no" name="item_no" type="number" min="1" class="mt-1 block w-full" value="{{ old('item_no', $assessmentItem->item_no) }}" required />
                                <x-input-error class="mt-2" :messages="$errors->get('item_no')" />
                            </div>
                            <div>
                                <x-input-label for="points" value="Points" />
                                <x-text-input id="points" name="points" type="number" min="0" class="mt-1 block w-full" value="{{ old('points', $assessmentItem->points) }}" required />
                                <x-input-error class="mt-2" :messages="$errors->get('points')" />
                            </div>
                        </div>

                        <div>
                            <x-input-label for="question_text" value="Question" />
                            <textarea id="question_text" name="question_text" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" rows="4" required>{{ old('question_text', $assessmentItem->question_text) }}</textarea>
                            <x-input-error class="mt-2" :messages="$errors->get('question_text')" />
                        </div>

                        <div>
                            <x-input-label for="correct_answer" value="Correct Answer (Optional)" />
                            <textarea id="correct_answer" name="correct_answer" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" rows="3">{{ old('correct_answer', $assessmentItem->correct_answer) }}</textarea>
                            <x-input-error class="mt-2" :messages="$errors->get('correct_answer')" />
                        </div>

                        <div class="flex items-center gap-3">
                            <x-primary-button>
                                Update Item
                            </x-primary-button>
                            <a href="{{ route('assessment-items.index') }}" class="text-sm text-gray-600 hover:text-gray-900">
                                Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
