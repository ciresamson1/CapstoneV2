<x-app-layout>

<x-slot name="header">
<h2 class="font-semibold text-xl text-gray-800 leading-tight">
Edit Project
</h2>
</x-slot>

<div class="py-6">
<div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

<div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">

<form method="POST" action="{{ route('projects.update',$project->id) }}">
@csrf
@method('PUT')

<div class="mb-4">
<label>Project Name</label>
<input type="text" name="name"
value="{{ $project->name }}"
class="block w-full border rounded">
</div>

<div class="mb-4">
<label>Description</label>
<textarea name="description"
class="block w-full border rounded">{{ $project->description }}</textarea>
</div>

<div class="mb-4">
<label>Start Date</label>
<input type="date" name="start_date"
value="{{ $project->start_date }}"
class="block w-full border rounded">
</div>

<div class="mb-4">
<label>Deadline</label>
<input type="date" name="end_date"
value="{{ $project->end_date }}"
class="block w-full border rounded">
</div>

<div class="mb-4">
<label>Project Manager</label>

<select name="manager_id" class="block w-full border rounded">

@foreach($users as $user)

<option value="{{ $user->id }}"
@if($project->manager_id == $user->id) selected @endif>

{{ $user->name }}

</option>

@endforeach

</select>

</div>

<button class="bg-green-500 text-green px-4 py-2 rounded">
Update Project
</button>

</form>

</div>
</div>
</div>

</x-app-layout>