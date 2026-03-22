<x-app-layout>

<x-slot name="header">
<h2 class="font-semibold text-xl text-gray-800 leading-tight">
📁 {{ $project->name }}
</h2>
</x-slot>

<div class="py-6">
<div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

<!-- ========================= -->
<!-- PROJECT HEADER -->
<!-- ========================= -->
<div class="bg-white p-6 rounded-xl shadow">

    <div class="flex justify-between items-center">

        <div>
            <h3 class="text-lg font-bold">{{ $project->name }}</h3>

            <p class="text-sm text-gray-500 mt-1">
                Status: {{ ucfirst($project->status) }} |
                Duration: {{ $project->start_date }} → {{ $project->end_date }}
            </p>
        </div>

        <div class="flex gap-3">
            <a href="{{ route('projects.edit', $project->id) }}"
               class="bg-blue-600 text-white px-4 py-2 rounded">
                Edit Project
            </a>

            <a href="{{ route('tasks.create') }}"
               class="bg-green-600 text-white px-4 py-2 rounded">
                Assign Task
            </a>
        </div>

    </div>

</div>


<!-- ========================= -->
<!-- GANTT CHART -->
<!-- ========================= -->
<div class="bg-white p-6 rounded-xl shadow">

<h3 class="font-semibold mb-4">📊 Gantt Chart</h3>

@php
$start = \Carbon\Carbon::parse($timelineStart)->startOfDay();
$end = \Carbon\Carbon::parse($timelineEnd)->endOfDay();

$pxPerDay = 20;

// BUILD WEEKDAY ARRAY
$dates = [];
$current = $start->copy();

while ($current <= $end) {
    if (!$current->isWeekend()) {
        $dates[] = $current->copy();
    }
    $current->addDay();
}

// TODAY
$today = now()->startOfDay();

// FIND TODAY INDEX (SAFE)
$todayIndex = null;
foreach ($dates as $i => $date) {
    if ($date->equalTo($today)) {
        $todayIndex = $i;
        break;
    }
}

$todayLeft = $todayIndex !== null ? $todayIndex * $pxPerDay : null;
@endphp


<!-- MONTH ROW -->
<div class="flex ml-40 text-xs font-semibold text-gray-600">

@php
$groupedMonths = collect($dates)->groupBy(fn($d) => $d->format('M Y'));
@endphp

@foreach($groupedMonths as $month => $days)
    <div class="text-center border-r border-gray-300"
         style="width: {{ count($days) * $pxPerDay }}px">
        {{ $month }}
    </div>
@endforeach

</div>


<!-- DAY ROW -->
<div class="flex ml-40 mb-4 text-xs">

@foreach($dates as $i => $date)

    @php
        $isToday = $todayIndex !== null && $i === $todayIndex;

        // Alternate WEEK shading
        $weekIndex = floor($i / 5); // 5 weekdays = 1 week
        $weekBg = $weekIndex % 2 === 0 ? 'bg-gray-50' : 'bg-white';
    @endphp

    <div class="text-center border-r border-gray-200 relative {{ $weekBg }}
        {{ $isToday ? 'bg-red-200 text-red-700 font-bold' : 'text-gray-500' }}"
        style="width: {{ $pxPerDay }}px">

        {{ $date->format('d') }}

        @if($isToday)
            <div class="absolute -top-5 left-1/2 -translate-x-1/2 text-[10px] text-red-600 font-bold">
                TODAY
            </div>
        @endif

    </div>

@endforeach

</div>


<!-- ========================= -->
<!-- TASK ROWS -->
<!-- ========================= -->
<div class="space-y-4">

@foreach($ganttTasks as $task)

@php
$startDate = \Carbon\Carbon::parse($task['start']);
$endDate = \Carbon\Carbon::parse($task['end']);

// FIND INDEXES
$startIndex = null;
$endIndex = null;

foreach ($dates as $i => $date) {
    if ($startIndex === null && $date->gte($startDate)) {
        $startIndex = $i;
    }
    if ($date->lte($endDate)) {
        $endIndex = $i;
    }
}

$startIndex = $startIndex ?? 0;
$endIndex = $endIndex ?? count($dates) - 1;

$left = $startIndex * $pxPerDay;
$width = max(($endIndex - $startIndex + 1) * $pxPerDay, $pxPerDay);

// COLOR
$bg = match($task['status']) {
    'completed' => 'bg-green-500',
    'pending' => 'bg-yellow-400',
    default => 'bg-blue-500'
};
@endphp

<div class="flex items-center">

    <!-- TASK NAME -->
    <div class="w-40 text-sm">
        {{ $task['name'] }}
    </div>

    <!-- TIMELINE -->
    <div class="relative flex-1 h-6 rounded overflow-hidden">

        <!-- WEEK STRIPES BACKGROUND -->
        @foreach($dates as $i => $date)

            @php
                $weekIndex = floor($i / 5);
                $bgStripe = $weekIndex % 2 === 0 ? 'bg-gray-50' : 'bg-white';
            @endphp

            <div class="absolute top-0 bottom-0 {{ $bgStripe }}"
                 style="left: {{ $i * $pxPerDay }}px; width: {{ $pxPerDay }}px">
            </div>

            <!-- GRID LINE -->
            <div class="absolute top-0 bottom-0 border-l border-gray-200"
                 style="left: {{ $i * $pxPerDay }}px"></div>

        @endforeach

        <!-- TODAY LINE -->
        @if($todayLeft !== null)
        <div class="absolute top-0 bottom-0 z-20"
             style="left: {{ $todayLeft }}px">

            <div class="w-1 h-full bg-red-500"></div>
        </div>
        @endif

        <!-- TASK BAR -->
        <div class="absolute h-6 rounded {{ $bg }}"
             style="left: {{ $left }}px; width: {{ $width }}px">
        </div>

    </div>

</div>

@endforeach

</div>

</div>


<!-- ========================= -->
<!-- TASK TABLE -->
<!-- ========================= -->
<div class="bg-white p-6 rounded-xl shadow">

<h3 class="font-semibold mb-4">📋 Tasks</h3>

<table class="w-full text-sm">

<thead>
<tr class="border-b text-gray-500">
<th class="text-left py-2">Task</th>
<th>Assigned</th>
<th>Progress</th>
<th>Status</th>
<th>Due</th>
</tr>
</thead>

<tbody>

@foreach($ganttTasks as $task)

<tr class="border-b">

<td class="py-2">{{ $task['name'] }}</td>
<td>{{ $task['assigned'] }}</td>
<td>{{ $task['progress'] }}%</td>
<td>{{ ucfirst($task['status']) }}</td>
<td>{{ \Carbon\Carbon::parse($task['due'])->format('M d') }}</td>

</tr>

@endforeach

</tbody>

</table>

</div>

</div>
</div>

</x-app-layout>