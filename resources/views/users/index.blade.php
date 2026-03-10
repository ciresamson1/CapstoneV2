<x-app-layout>

<x-slot name="header">
<h2 class="font-semibold text-xl text-gray-800 leading-tight">
Users Management
</h2>
</x-slot>

<div class="py-6">
<div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

<div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">

<a href="{{ route('users.create') }}"
class="bg-blue-500 text-white px-4 py-2 rounded">
Create User
</a>

<table class="table-auto w-full mt-4">

<thead>
<tr>
<th>ID</th>
<th>Name</th>
<th>Email</th>
<th>Role</th>
<th>Actions</th>
</tr>
</thead>

<tbody>

@foreach($users as $user)

<tr class="border-b">

<td>{{ $user->id }}</td>
<td>{{ $user->name }}</td>
<td>{{ $user->email }}</td>
<td>{{ $user->role->name ?? 'No Role' }}</td>

<td>

<a href="{{ route('users.edit',$user->id) }}"
class="text-blue-500">
Edit
</a>

<form action="{{ route('users.destroy',$user->id) }}"
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
{{ $users->links() }}
</div>

</div>
</div>
</div>

</x-app-layout>