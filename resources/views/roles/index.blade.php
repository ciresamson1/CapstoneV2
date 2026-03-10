<x-app-layout>

<x-slot name="header">
<h2 class="font-semibold text-xl text-gray-800 leading-tight">
Roles Management
</h2>
</x-slot>

<div class="py-6">
<div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

<div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">

<a href="{{ route('roles.create') }}" class="bg-blue-500 text-white px-4 py-2 rounded">
Create Role
</a>

@if(session('success'))
<div class="mt-4 text-green-600">
{{ session('success') }}
</div>
@endif

<table class="table-auto w-full mt-4">

<thead>
<tr>
<th>ID</th>
<th>Name</th>
<th>Description</th>
<th>Actions</th>
</tr>
</thead>

<tbody>

@foreach($roles as $role)

<tr class="border-b">

<td>{{ $role->id }}</td>
<td>{{ $role->name }}</td>
<td>{{ $role->description }}</td>

<td>

<a href="{{ route('roles.edit',$role->id) }}" class="text-blue-500">
Edit
</a>

<form action="{{ route('roles.destroy',$role->id) }}" method="POST" style="display:inline">

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
{{ $roles->links() }}
</div>

</div>
</div>
</div>

</x-app-layout>