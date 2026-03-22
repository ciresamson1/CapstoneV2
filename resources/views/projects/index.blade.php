<x-app-layout>

<x-slot name="header">
<h2 class="font-semibold text-xl text-gray-800 leading-tight">
Projects Management
</h2>
</x-slot>

<div class="py-6">
<div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

<!-- MAIN CARD -->
<div class="bg-white rounded-xl shadow p-6">

    <!-- HEADER: ACTION + FILTERS -->
    <div class="flex flex-col md:flex-row justify-between items-center mb-6 gap-4">

        <!-- CREATE BUTTON -->
        <a href="{{ route('projects.create') }}"
           class="bg-blue-600 text-white px-5 py-2 rounded-lg shadow hover:bg-blue-700">
            + Create Project
        </a>

        <!-- FILTERS -->
        <form method="GET" action="{{ route('projects.index') }}" class="flex flex-wrap gap-2">

            <input type="text" name="search"
                   value="{{ request('search') }}"
                   placeholder="Search project..."
                   class="border rounded-lg px-3 py-2 text-sm w-48">

            <select name="manager" class="border rounded-lg px-3 py-2 text-sm">
                <option value="">All Managers</option>
                @foreach($managers as $manager)
                    <option value="{{ $manager->id }}"
                        {{ request('manager') == $manager->id ? 'selected' : '' }}>
                        {{ $manager->name }}
                    </option>
                @endforeach
            </select>

            <select name="status" class="border rounded-lg px-3 py-2 text-sm">
                <option value="">All Status</option>
                <option value="planning">Planning</option>
                <option value="in_progress">In Progress</option>
                <option value="completed">Completed</option>
            </select>

            <button class="bg-gray-900 text-white px-4 py-2 rounded-lg text-sm">
                Apply
            </button>

        </form>
    </div>


    <!-- PROJECT LIST -->
    <div class="space-y-4">

        @forelse($projects as $project)

        <div class="border rounded-xl p-4 hover:shadow-md transition">

            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">

                <!-- LEFT INFO -->
                <div class="flex flex-col gap-1">

                    <div class="flex items-center gap-3">
                        <span class="text-xs bg-gray-200 px-2 py-1 rounded">
                            #{{ $project->id }}
                        </span>

                        <h3 class="font-semibold text-lg">
                            {{ $project->name }}
                        </h3>
                    </div>

                    <div class="text-sm text-gray-500">
                        Manager: {{ $project->manager->name ?? 'N/A' }}
                    </div>

                    <div class="text-sm">
                        <span class="px-2 py-1 rounded text-xs
                            {{ $project->status == 'completed' ? 'bg-green-100 text-green-600' : '' }}
                            {{ $project->status == 'planning' ? 'bg-yellow-100 text-yellow-600' : '' }}
                            {{ $project->status == 'in_progress' ? 'bg-blue-100 text-blue-600' : '' }}">
                            {{ ucfirst(str_replace('_',' ', $project->status)) }}
                        </span>
                    </div>

                </div>


                <!-- MIDDLE: DATES -->
                <div class="text-sm text-gray-500">
                    <div>Start: {{ $project->start_date }}</div>
                    <div>Deadline: {{ $project->end_date }}</div>
                </div>


                <!-- PROGRESS -->
                <div class="w-full md:w-1/4">

                    <div class="w-full bg-gray-200 rounded-full h-3">
                        <div class="bg-green-500 h-3 rounded-full"
                             style="width: {{ $project->progress() }}%">
                        </div>
                    </div>

                    <div class="text-xs mt-1 text-gray-600">
                        {{ $project->progress() }}%
                    </div>

                </div>


                <!-- ACTIONS -->
                <div class="flex gap-3 text-sm">

                    <a href="{{ route('projects.show', $project->id) }}"
                       class="text-green-600 hover:underline">
                        View
                    </a>

                    <a href="{{ route('projects.edit', $project->id) }}"
                       class="text-blue-600 hover:underline">
                        Edit
                    </a>

                    <form action="{{ route('projects.destroy', $project->id) }}" method="POST">
                        @csrf
                        @method('DELETE')

                        <button class="text-red-600 hover:underline"
                                onclick="return confirm('Delete this project?')">
                            Delete
                        </button>
                    </form>

                </div>

            </div>

        </div>

        @empty

        <div class="text-center text-gray-400 py-10">
            No projects found
        </div>

        @endforelse

    </div>


    <!-- PAGINATION -->
    <div class="mt-6">
        {{ $projects->links() }}
    </div>

</div>

</div>
</div>

</x-app-layout>