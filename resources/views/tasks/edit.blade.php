<x-app-layout>

<x-slot name="header">
<h2 class="font-semibold text-xl text-gray-800 leading-tight">
Edit Task
</h2>
</x-slot>

<div class="py-6">
<div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

<div class="bg-white shadow-sm sm:rounded-lg p-6">

<form method="POST" action="{{ route('tasks.update',$task->id) }}">
@csrf
@method('PUT')

<div class="mb-4">
<label>Task Title</label>
<input type="text" name="title"
value="{{ $task->title }}"
class="block w-full border rounded">
</div>

<div class="mb-4">
<label>Description</label>
<textarea name="description"
class="block w-full border rounded">{{ $task->description }}</textarea>
</div>

<div class="mb-4">
<label>Project</label>

<select name="project_id" class="block w-full border rounded">

@foreach($projects as $project)

<option value="{{ $project->id }}"
@if($task->project_id == $project->id) selected @endif>

{{ $project->name }}

</option>

@endforeach

</select>

</div>

<div class="mb-4">
<label>Assigned To</label>

<select name="assigned_to" class="block w-full border rounded">

@foreach($users as $user)

<option value="{{ $user->id }}"
@if($task->assigned_to == $user->id) selected @endif>

{{ $user->name }}

</option>

@endforeach

</select>

</div>

<div class="mb-4">
<label>Status</label>

<select name="status" class="block w-full border rounded">

<option value="pending" {{ $task->status == 'pending' ? 'selected' : '' }}>
Pending
</option>

<option value="in_progress" {{ $task->status == 'in_progress' ? 'selected' : '' }}>
In Progress
</option>

<option value="completed" {{ $task->status == 'completed' ? 'selected' : '' }}>
Completed
</option>

</select>

</div>

<div class="mb-4">
<label>Start Date</label>

<input type="date"
name="start_date"
value="{{ $task->start_date }}"
class="block w-full border rounded">
</div>

<div class="mb-4">
<label>Due Date</label>

<input type="date"
name="due_date"
value="{{ $task->due_date }}"
class="block w-full border rounded">
</div>

<button class="bg-green-500 text-white px-4 py-2 rounded">
Update Task
</button>

</form>

</div>
</div>
</div>

</x-app-layout> 