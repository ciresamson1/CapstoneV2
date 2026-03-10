<x-app-layout>

<x-slot name="header">
<h2 class="font-semibold text-xl text-gray-800 leading-tight">
Edit User
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

<form method="POST" action="{{ route('users.update', $user->id) }}">

@csrf
@method('PUT')

<!-- Name -->
<div class="mb-4">
<label class="block text-sm font-medium text-gray-700">
Name
</label>

<input type="text"
name="name"
value="{{ $user->name }}"
class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"
required>
</div>

<!-- Email -->
<div class="mb-4">
<label class="block text-sm font-medium text-gray-700">
Email
</label>

<input type="email"
name="email"
value="{{ $user->email }}"
class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"
required>
</div>

<!-- Role -->
<div class="mb-4">
<label class="block text-sm font-medium text-gray-700">
Role
</label>

<select name="role_id"
class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">

@foreach($roles as $role)

<option value="{{ $role->id }}"
@if($user->role_id == $role->id) selected @endif>

{{ $role->name }}

</option>

@endforeach

</select>

</div>

<!-- Buttons -->

<div class="flex justify-end">

<a href="{{ route('users.index') }}"
class="mr-4 text-gray-600">
Cancel
</a>

<button type="submit"
class="bg-green-500 text-white px-4 py-2 rounded">
Update User
</button>

</div>

</form>

</div>
</div>
</div>

</x-app-layout>