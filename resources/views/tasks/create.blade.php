<x-app-layout>

<x-slot name="header">
<h2 class="font-semibold text-xl text-gray-800 leading-tight">
Create Task
</h2>
</x-slot>

<div class="py-6">
<div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

<div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">

@if ($errors->any())
<div class="mb-4 text-red-600">
<ul>
@foreach ($errors->all() as $error)
<li>{{ $error }}</li>
@endforeach
</ul>
</div>
@endif

<form method="POST" action="{{ route('tasks.store') }}">
@csrf

<!-- Task Title -->
<div class="mb-4">
<label>Task Title</label>
<input type="text" name="title"
class="block w-full border rounded" required>
</div>

<!-- Description -->
<div class="mb-4">
<label>Description</label>
<textarea name="description"
class="block w-full border rounded"></textarea>
</div>

<!-- Project -->
<div class="mb-4">
<label>Project</label>

<select name="project_id" class="block w-full border rounded">

@foreach($projects as $project)

<option value="{{ $project->id }}">
{{ $project->name }}
</option>

@endforeach

</select>

</div>

<!-- Assigned User -->

<div class="mb-4">
<label>Assign To</label>

<select name="assigned_to" class="block w-full border rounded">

@foreach($users as $user)

<option value="{{ $user->id }}">
{{ $user->name }}
</option>

@endforeach

</select>

</div>

<!-- Start Date -->

<div class="mb-4">
<label>Start Date</label>

<input type="date"
name="start_date"
class="block w-full border rounded"
required>

</div>

<!-- Due Date -->

<div class="mb-4">
<label>Due Date</label>

<input type="date"
name="due_date"
class="block w-full border rounded"
required>

</div>

<button class="bg-blue-500 text-white px-4 py-2 rounded">
Create Task
</button>

</form>

</div>
</div>
</div>

</x-app-layout>