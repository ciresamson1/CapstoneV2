<x-app-layout>

<x-slot name="header">
<h2 class="font-semibold text-xl text-gray-800 leading-tight">
Dashboard
</h2>
</x-slot>

<div class="py-6">
<div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8"><div class="bg-white p-6 rounded shadow mb-6">

<h2 class="text-xl font-semibold">
Welcome {{ auth()->user()->name }} 👋
</h2>

<p class="text-gray-500 mt-1">
You have <span class="font-semibold text-yellow-600">{{ $pendingTasks }}</span> pending tasks,
<span class="font-semibold text-red-600">{{ $overdueTasks }}</span> overdue tasks
and <span class="font-semibold text-blue-600">{{ $totalProjects }}</span> active projects.
</p>

</div>



<!-- KPI CARDS -->
<div class="grid grid-cols-1 md:grid-cols-6 gap-6">

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

<div class="bg-blue-100 p-6 rounded shadow text-center">
<h3 class="text-blue-700">Users</h3>
<p class="text-3xl font-bold">{{ $totalUsers }}</p>
</div>

</div>


<!-- CHARTS -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

    <!-- LEFT SIDE -->
    <div class="bg-white p-6 rounded shadow">
        <h3 class="text-lg font-semibold mb-4">Task Status</h3>
        <canvas id="taskChart"></canvas>
    </div>

    <!-- RIGHT SIDE -->
    <div class="space-y-6">

        <!-- Activity Log -->
        @foreach($activities as $activity)

            <div class="flex justify-between border-b py-2 text-sm">

            <span class="text-gray-700">
            {{ $activity['message'] }}
            </span>

            <span class="text-gray-400">
            {{ \Carbon\Carbon::parse($activity['time'])->diffForHumans() }}
            </span>

            </div>

        @endforeach

        </div>
        <!-- Recent Projects -->
        <div class="bg-white p-6 rounded shadow">

            <h3 class="text-lg font-semibold mb-4">Recent Projects</h3>

            @foreach($recentProjects as $project)

            @php
            $total = $project->tasks->count();
            $completed = $project->tasks->where('status','completed')->count();
            $progress = $total > 0 ? round(($completed/$total)*100) : 0;
            @endphp

            <div class="mb-4">

                <div class="flex justify-between mb-1">
                    <span>{{ $project->name }}</span>
                    <span>{{ $progress }}%</span>
                </div>

                <div class="w-full bg-gray-200 h-3 rounded">
                    <div class="bg-blue-600 h-3 rounded" style="width: {{ $progress }}%"></div>
                </div>

            </div>

            @endforeach

        </div>

    </div>

</div>


<!-- Tasks Due Soon -->
<div class="bg-white p-6 rounded shadow mt-6">

<h3 class="text-lg font-semibold mb-4">Tasks Due Soon</h3>

@foreach($dueSoonTasks as $task)

<div class="flex justify-between border-b py-2">
    <span>{{ $task->title }}</span>
    <span class="text-red-500">
        {{ \Carbon\Carbon::parse($task->due_date)->format('M d') }}
    </span>
</div>

@endforeach

</div>


<!-- RECENT TASKS TABLE -->
<div class="bg-white p-6 rounded shadow">

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

<td class="py-2">
{{ \Carbon\Carbon::parse($task->due_date)->format('M d, Y') }}
</td>

</tr>

@endforeach

</tbody>

</table>

</div>


<!-- QUICK ACTIONS -->
<div class="bg-white p-6 rounded shadow">

<h3 class="text-lg font-semibold mb-4">Quick Actions</h3>

<div class="flex gap-4">

<a href="{{ route('projects.create') }}"
class="bg-blue-600 text-white px-4 py-2 rounded">
New Project
</a>

<a href="{{ route('tasks.create') }}"
class="bg-green-600 text-white px-4 py-2 rounded">
New Task
</a>

<a href="{{ route('users.create') }}"
class="bg-purple-600 text-white px-4 py-2 rounded">
Add User
</a>

</div>

<!-- WORKLOADS -->
<div class="bg-white p-6 rounded shadow">

<h3 class="text-lg font-semibold mb-4">Team Workload</h3>

<canvas id="teamChart"></canvas>

</div>
</div>


</div>
</div>


<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>



<script>

const taskCtx = document.getElementById('taskChart');

new Chart(taskCtx, {
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
    },
    options:{
        plugins:{
            legend:{
                position:'bottom'
            }
        }
    }
});

const teamCtx = document.getElementById('teamChart');

new Chart(teamCtx, {

type: 'bar',

data: {
labels: {!! json_encode($userNames) !!},
datasets: [{
label: 'Tasks Assigned',
data: {!! json_encode($userTaskCounts) !!},
backgroundColor: '#6366f1'
}]
},

options:{
scales:{
y:{beginAtZero:true}
}
}

});

setInterval(function(){

fetch('/dashboard')

.then(response => response.text())

.then(html => {

const parser = new DOMParser();
const doc = parser.parseFromString(html,'text/html');

const newActivities = doc.querySelector('#activity-log');

document.querySelector('#activity-log').innerHTML =
newActivities.innerHTML;

});

}, 10000);

</script>



</x-app-layout>