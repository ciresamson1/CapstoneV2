<x-app-layout>

<x-slot name="header">
<h2 class="font-semibold text-xl text-black-800 leading-tight">
Create Project
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

<form method="POST" action="{{ route('projects.store') }}">
@csrf

<!-- Project Name -->
<div class="mb-4">
<label class="block text-sm font-medium text-gray-700">
Project Name
</label>

<input type="text"
name="name"
class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"
required>
</div>

<!-- Description -->
<div class="mb-4">
<label class="block text-sm font-medium text-gray-700">
Description
</label>

<textarea name="description"
class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"></textarea>
</div>

<!-- Start Date -->
<div class="mb-4">
<label class="block text-sm font-medium text-gray-700">
Start Date
</label>

<input type="date"
name="start_date"
class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"
required>
</div>

<!-- Deadline -->
<div class="mb-4">
<label class="block text-sm font-medium text-gray-700">
Deadline
</label>

<input type="date"
name="end_date"
class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"
required>
</div>

<!-- Project Manager -->
<div class="mb-4">
<label class="block text-sm font-medium text-gray-700">
Project Manager
</label>

<select name="manager_id"
class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">

@foreach($users as $user)

<option value="{{ $user->id }}">
{{ $user->name }}
</option>

@endforeach

</select>
</div>

<div class="flex justify-end">

<a href="{{ route('projects.index') }}"
class="mr-4 text-gray-600">
Cancel
</a>

<button type="submit"
class="bg-blue-500 text-green px-4 py-2 rounded">
Create Project
</button>

</div>

</form>

</div>
</div>
</div>

</x-app-layout>