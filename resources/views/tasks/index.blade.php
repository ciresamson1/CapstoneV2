<x-app-layout>

<x-slot name="header">
<h2 class="font-semibold text-xl text-gray-800 leading-tight">
Tasks Management
</h2>
</x-slot>

<div class="py-6">
<div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

<div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">

<a href="{{ route('tasks.create') }}"
class="bg-blue-500 text-green px-4 py-2 rounded">
Create Task
</a>

<table class="table-auto w-full mt-4">

<thead>
<tr>
<th>ID</th>
<th>Title</th>
<th>Project</th>
<th>Assigned To</th>
<th>Status</th>
<th>Due Date</th>
<th>Actions</th>
</tr>
</thead>

<tbody>

@foreach($tasks as $task)

<tr class="border-b">

<td>{{ $task->id }}</td>
<td>{{ $task->title }}</td>
<td>{{ $task->project->name }}</td>
<td>{{ $task->assignee->name }}</td>
<td>{{ $task->status }}</td>
<td>{{ $task->due_date }}</td>

<td>

<a href="{{ route('tasks.edit',$task->id) }}"
class="text-blue-500">
Edit
</a>

<form action="{{ route('tasks.destroy',$task->id) }}"
method="POST"
style="display:inline">

@csrf
@method('DELETE')

<button class="text-red-500 ml-2">
Delete
</button>

</form>

</td>

</tr>

@endforeach

</tbody>

</table>

<div class="mt-4">
{{ $tasks->links() }}
</div>

</div>
</div>
</div>

</x-app-layout>
