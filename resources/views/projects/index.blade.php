<x-app-layout>

<x-slot name="header">
<h2 class="font-semibold text-xl text-gray-800 leading-tight">
Projects Management
</h2>
</x-slot>

<div class="py-6">
<div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

<div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">

<a href="{{ route('projects.create') }}"
class="bg-blue-500 text-green px-4 py-2 rounded">
Create Project
</a>

<table class="table-auto w-full mt-4">

<thead>
<tr>
<th>ID</th>
<th>Name</th>
<th>Manager</th>
<th>Status</th>
<th>Start Date</th>
<th>Deadline</th>
<th>Progress</th>
<th>Actions</th>
</tr>
</thead>

<tbody>

@foreach($projects as $project)

<tr class="border-b">

<td>{{ $project->id }}</td>
<td>{{ $project->name }}</td>
<td>{{ $project->manager->name ?? 'N/A' }}</td>
<td>{{ $project->status }}</td>
<td>{{ $project->start_date }}</td>
<td>{{ $project->end_date }}</td>

<td>

<div class="w-full bg-gray-200 rounded-full h-4">

<div class="bg-green-500 h-4 rounded-full"
style="width: {{ $project->progress() }}%">
</div>

</div>

<div class="text-sm mt-1">
{{ $project->progress() }} %
</div>

</td>

<td>

<a href="{{ route('projects.edit',$project->id) }}"
class="text-blue-500">Edit</a>

<form action="{{ route('projects.destroy',$project->id) }}"
method="POST"
style="display:inline">

@csrf
@method('DELETE')

<button class="text-red-500 ml-2">Delete</button>

</form>

</td>

</tr>

@endforeach

</tbody>

</table>

<div class="mt-4">
{{ $projects->links() }}
</div>

</div>
</div>
</div>

</x-app-layout>