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



<!-- SMART KPI CARDS -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-6">

<!-- Active Projects -->
<div class="bg-white p-6 rounded shadow">
    <h3 class="text-gray-500 text-sm">Active Projects</h3>
    <p class="text-3xl font-bold text-blue-600">
        {{ $activeProjects }}
    </p>
</div>

<!-- My Tasks -->
<div class="bg-white p-6 rounded shadow">
    <h3 class="text-gray-500 text-sm">My Tasks</h3>
    <p class="text-3xl font-bold text-indigo-600">
        {{ $myTasks }}
    </p>
</div>

<!-- Due Soon -->
<div class="bg-white p-6 rounded shadow">
    <h3 class="text-gray-500 text-sm">Due Soon (5 days)</h3>
    <p class="text-3xl font-bold text-yellow-500">
        {{ $dueSoonCount }}
    </p>
</div>

<!-- Overdue -->
<div class="bg-white p-6 rounded shadow">
    <h3 class="text-gray-500 text-sm">Overdue Tasks</h3>
    <p class="text-3xl font-bold text-red-600">
        {{ $overdueTasks }}
    </p>
</div>

</div>


<!-- GANTT CHART -->
<div class="bg-white p-6 rounded shadow">

<h3 class="text-lg font-semibold mb-4">
📊 Project Timeline ({{ $latestProject->name ?? 'No Project' }})
</h3>

@if($latestProject && count($ganttTasks))

@php
$totalDays = max(\Carbon\Carbon::parse($timelineStart)->diffInDays($timelineEnd),1);
$pxPerDay = 25;

// TODAY POSITION
$today = \Carbon\Carbon::now();

$todayOffset = 0;

if ($timelineStart && $timelineEnd) {

    // signed difference (correct)
    $todayOffset = \Carbon\Carbon::parse($timelineStart)->diffInDays($today, false);

    // clamp
    if ($todayOffset < 0) {
        $todayOffset = 0;
    }

    if ($todayOffset > $totalDays) {
        $todayOffset = $totalDays;
    }
}

$todayLeft = $todayOffset * $pxPerDay;
@endphp


<!-- DATE HEADER -->
<div class="flex mb-4 ml-40">
@for($i = 0; $i <= $totalDays; $i+=3)
    @php
        $date = \Carbon\Carbon::parse($timelineStart)->addDays($i);
    @endphp
    <div class="text-xs text-gray-500" style="width: {{ $pxPerDay * 3 }}px">
        {{ $date->format('M d') }}
    </div>
@endfor
</div>

<!-- TASK ROWS -->
<div class="space-y-4">

@foreach($ganttTasks as $task)

@php
$start = \Carbon\Carbon::parse($task['start']);
$end = \Carbon\Carbon::parse($task['end']);

$offsetDays = $start->diffInDays($timelineStart);
$durationDays = max($start->diffInDays($end),1);

$left = $offsetDays * $pxPerDay;
$width = $durationDays * $pxPerDay;

// COLOR
$bg = match($task['color']) {
    'green' => 'bg-green-500',
    'yellow' => 'bg-yellow-400',
    'red' => 'bg-red-500',
    default => 'bg-blue-500'
};
@endphp

<div class="flex items-center">

    <!-- TASK NAME -->
    <div class="w-40 text-sm">
        {{ $task['name'] }}
    </div>

    <!-- TIMELINE -->
    <div class="relative flex-1 h-6 bg-gray-100 rounded overflow-hidden">

        <!-- GRID LINES -->
        @for($i = 0; $i <= $totalDays; $i+=3)
            <div class="absolute top-0 bottom-0 border-l border-gray-200"
                 style="left: {{ $i * $pxPerDay }}px"></div>
        @endfor

        <!-- TODAY LINE -->
        <div class="absolute top-0 bottom-0 z-20"
     style="left: {{ $todayLeft }}px">

    <div class="w-1 h-full bg-red-500"></div>

    <div class="absolute -top-6 -left-4 text-xs text-red-500 font-bold">
        Today
    </div>

</div>

        <!-- TASK BAR -->
        <div class="absolute h-6 rounded {{ $bg }}"
             style="left: {{ $left }}px; width: {{ $width }}px">
        </div>

    </div>

</div>

@endforeach

</div>

<!-- LEGEND -->
<div class="mt-6 flex gap-6 text-sm">
    <span class="flex items-center gap-2">
        <span class="w-3 h-3 bg-green-500 rounded-full"></span> On Track
    </span>

    <span class="flex items-center gap-2">
        <span class="w-3 h-3 bg-yellow-400 rounded-full"></span> Near Deadline
    </span>

    <span class="flex items-center gap-2">
        <span class="w-3 h-3 bg-red-500 rounded-full"></span> Overdue
    </span>
</div>

@else
<p class="text-gray-500">No tasks available</p>
@endif

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