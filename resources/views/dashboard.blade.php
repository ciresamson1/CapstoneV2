<x-app-layout>

<x-slot name="header">
<h2 class="font-semibold text-xl text-gray-800 leading-tight">
Dashboard
</h2>
</x-slot>

<div class="py-6">
<div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

<!-- Dashboard Cards -->
<div class="grid grid-cols-1 md:grid-cols-5 gap-6">

<div class="bg-white p-6 rounded shadow text-center">
<h3 class="text-gray-500">Projects</h3>
<p class="text-3xl font-bold">{{ $totalProjects }}</p>
</div>

<div class="bg-white p-6 rounded shadow text-center">
<h3 class="text-gray-500">Tasks</h3>
<p class="text-3xl font-bold">{{ $totalTasks }}</p>
</div>

<div class="bg-green-100 p-6 rounded shadow text-center">
<h3 class="text-green-700">Completed</h3>
<p class="text-3xl font-bold">{{ $completedTasks }}</p>
</div>

<div class="bg-yellow-100 p-6 rounded shadow text-center">
<h3 class="text-yellow-700">Pending</h3>
<p class="text-3xl font-bold">{{ $pendingTasks }}</p>
</div>

<div class="bg-red-100 p-6 rounded shadow text-center">
<h3 class="text-red-700">Overdue</h3>
<p class="text-3xl font-bold">{{ $overdueTasks }}</p>
</div>

</div>

<!-- Pie Chart -->
<div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-8">

<!-- Task Status Pie Chart -->
<div class="bg-white p-6 rounded shadow">
<h3 class="text-lg font-semibold mb-4">Task Status Overview</h3>
<canvas id="taskChart" height="150"></canvas>
</div>

<!-- Project Progress Chart -->
<div class="bg-white p-6 rounded shadow">
<h3 class="text-lg font-semibold mb-4">Project Progress</h3>
<canvas id="projectChart" height="150"></canvas>
</div>

<div class="bg-white p-6 rounded shadow mt-8">

<h3 class="text-lg font-semibold mb-4">Recent Tasks</h3>

<table class="min-w-full">

<thead>
<tr class="border-b">
<th class="text-left py-2">Task</th>
<th class="text-left py-2">Project</th>
<th class="text-left py-2">Status</th>
<th class="text-left py-2">Due Date</th>
</tr>
</thead>

<tbody>

<div class="bg-white p-6 rounded shadow mt-8">

<h3 class="text-lg font-semibold mb-4">Recent Projects</h3>

@foreach($recentProjects as $project)

@php
$total = $project->tasks->count();
$completed = $project->tasks->where('status','completed')->count();
$progress = $total > 0 ? round(($completed/$total)*100) : 0;
@endphp

<div class="mb-4">

<div class="flex justify-between mb-1">
<span class="font-medium">{{ $project->name }}</span>
<span>{{ $progress }}%</span>
</div>

<div class="w-full bg-gray-200 rounded h-3">
<div class="bg-blue-600 h-3 rounded" style="width: {{ $progress }}%"></div>
</div>

</div>

@endforeach

</div>

@foreach($recentTasks as $task)

<tr class="border-b">

<td class="py-2">{{ $task->title }}</td>

<td class="py-2">{{ $task->project->name ?? 'N/A' }}</td>

<td class="py-2">

@if($task->status == 'completed')
<span class="text-green-600">Completed</span>

@elseif($task->status == 'pending')
<span class="text-yellow-600">Pending</span>

@else
<span class="text-blue-600">{{ ucfirst($task->status) }}</span>

@endif

</td>

<td class="py-2">{{ $task->due_date }}</td>

</tr>

@endforeach

</tbody>

</table>

</div>

</div>

</div>
</div>

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>

const ctx = document.getElementById('taskChart');

new Chart(ctx, {
    type: 'pie',
    data: {
        labels: ['Completed','Pending','Overdue'],
        datasets: [{
            data: [
                {{ $completedTasks }},
                {{ $pendingTasks }},
                {{ $overdueTasks }}
            ],
            backgroundColor: [
                '#22c55e',
                '#facc15',
                '#ef4444'
            ]
        }]
    }
});

</script>

<script>

const projectCtx = document.getElementById('projectChart');

new Chart(projectCtx, {
    type: 'doughnut',
    data: {
        labels: ['Projects'],
        datasets: [{
            data: [{{ $totalProjects }}],
            backgroundColor: ['#3b82f6']
        }]
    }
});

</script>

</x-app-layout>