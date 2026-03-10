<x-app-layout>

<x-slot name="header">
<h2 class="font-semibold text-xl text-gray-800 leading-tight">
Create Role
</h2>
</x-slot>

<div class="py-6">
<div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

<div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">

<form method="POST" action="{{ route('roles.store') }}">

@csrf

<!-- Role Name -->
<div class="mb-4">
<label class="block text-sm font-medium text-gray-700">
Role Name
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
class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
</textarea>
</div>

<!-- Submit -->
<div class="flex items-center justify-end">

<a href="{{ route('roles.index') }}"
class="mr-4 text-gray-600">
Cancel
</a>

<button type="submit"
class="bg-blue-500 text-white px-4 py-2 rounded">
Create Role
</button>

</div>

</form>

</div>
</div>
</div>

</x-app-layout>