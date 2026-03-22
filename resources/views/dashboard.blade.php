<x-app-layout>

<x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        Dashboard
    </h2>
</x-slot>

<div class="py-6">
<div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

    <!-- ========================= -->
    <!-- HEADER + QUICK ACTIONS -->
    <!-- ========================= -->
    <div class="bg-white p-6 rounded shadow flex justify-between items-center">

        <div>
            <h2 class="text-xl font-semibold">
                Welcome {{ auth()->user()->name }} 👋
            </h2>

            <p class="text-gray-500 mt-1">
                You have 
                <span class="text-yellow-600 font-semibold">{{ $pendingTasks }}</span> pending,
                <span class="text-red-600 font-semibold">{{ $overdueTasks }}</span> overdue and
                <span class="text-blue-600 font-semibold">{{ $totalProjects }}</span> active projects.
            </p>
        </div>

        <!-- QUICK ACTIONS (MOVED HERE) -->
        <div class="flex gap-2">
            <a href="{{ route('projects.create') }}"
               class="bg-blue-600 text-white px-4 py-2 rounded text-sm">
               + Project
            </a>

            <a href="{{ route('tasks.create') }}"
               class="bg-green-600 text-white px-4 py-2 rounded text-sm">
               + Task
            </a>

            <a href="{{ route('users.create') }}"
               class="bg-purple-600 text-white px-4 py-2 rounded text-sm">
               + User
            </a>
        </div>

    </div>


    <!-- ========================= -->
    <!-- KPI CARDS -->
    <!-- ========================= -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">

        <div class="bg-white p-5 rounded shadow">
            <p class="text-sm text-gray-500">Active Projects</p>
            <p class="text-2xl font-bold text-blue-600">{{ $activeProjects }}</p>
        </div>

        <div class="bg-white p-5 rounded shadow">
            <p class="text-sm text-gray-500">My Tasks</p>
            <p class="text-2xl font-bold text-indigo-600">{{ $myTasks }}</p>
        </div>

        <div class="bg-white p-5 rounded shadow">
            <p class="text-sm text-gray-500">Due Soon</p>
            <p class="text-2xl font-bold text-yellow-500">{{ $dueSoonCount }}</p>
        </div>

        <div class="bg-white p-5 rounded shadow">
            <p class="text-sm text-gray-500">Overdue</p>
            <p class="text-2xl font-bold text-red-600">{{ $overdueTasks }}</p>
        </div>

    </div>


    <!-- ========================= -->
    <!-- GANTT CHART -->
    <!-- ========================= -->
    <div class="bg-white p-6 rounded shadow">

        <h3 class="text-lg font-semibold mb-4">
            📊 Project Timeline ({{ $latestProject->name ?? 'No Project' }})
        </h3>

        @if($latestProject && count($ganttTasks))

        @php
            $totalDays = max(\Carbon\Carbon::parse($timelineStart)->diffInDays($timelineEnd),1);
            $pxPerDay = 25;

            $today = now();
            $todayOffset = $timelineStart 
                ? \Carbon\Carbon::parse($timelineStart)->diffInDays($today, false) 
                : 0;

            $todayOffset = max(0, min($todayOffset, $totalDays));
            $todayLeft = $todayOffset * $pxPerDay;
        @endphp

        <!-- DATE HEADER -->
        <div class="flex mb-4 ml-40">
            @for($i = 0; $i <= $totalDays; $i+=3)
                <div class="text-xs text-gray-500" style="width: {{ $pxPerDay * 3 }}px">
                    {{ \Carbon\Carbon::parse($timelineStart)->addDays($i)->format('M d') }}
                </div>
            @endfor
        </div>

        <!-- TASKS -->
        <div class="space-y-3">

        @foreach($ganttTasks as $task)

        @php
            $start = \Carbon\Carbon::parse($task['start']);
            $end = \Carbon\Carbon::parse($task['end']);

            $offset = $start->diffInDays($timelineStart);
            $duration = max($start->diffInDays($end),1);

            $left = $offset * $pxPerDay;
            $width = $duration * $pxPerDay;

            $bg = match($task['color']) {
                'green' => 'bg-green-500',
                'yellow' => 'bg-yellow-400',
                'red' => 'bg-red-500',
                default => 'bg-blue-500'
            };
        @endphp

        <div class="flex items-center">

            <div class="w-40 text-sm">{{ $task['name'] }}</div>

            <div class="relative flex-1 h-6 bg-gray-100 rounded">

                <!-- TODAY LINE -->
                <div class="absolute top-0 bottom-0 z-20"
                     style="left: {{ $todayLeft }}px">
                    <div class="w-1 h-full bg-red-500"></div>
                </div>

                <!-- BAR -->
                <div class="absolute h-6 rounded {{ $bg }}"
                     style="left: {{ $left }}px; width: {{ $width }}px">
                </div>

            </div>

        </div>

        @endforeach

        </div>

        @else
            <p class="text-gray-400">No tasks available</p>
        @endif

    </div>


    <!-- ========================= -->
    <!-- TASKS + ACTIVITY -->
    <!-- ========================= -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

        <!-- MY TASKS -->
        <div class="bg-white p-6 rounded shadow">

            <h3 class="text-lg font-semibold mb-4">📋 My Tasks</h3>

            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b text-gray-500">
                        <th class="text-left py-2">Task</th>
                        <th>Status</th>
                        <th>Due</th>
                    </tr>
                </thead>

                <tbody>
                @forelse($myRecentTasks as $task)
                    <tr class="border-b">
                        <td class="py-2">{{ $task->title }}</td>
                        <td class="text-xs">
                            <span class="font-semibold
                                {{ $task->status == 'completed' ? 'text-green-600' :
                                   ($task->status == 'pending' ? 'text-yellow-600' : 'text-blue-600') }}">
                                {{ ucfirst($task->status) }}
                            </span>
                        </td>
                        <td class="text-xs text-gray-500">
                            {{ \Carbon\Carbon::parse($task->due_date)->format('M d') }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="text-center text-gray-400 py-4">
                            No tasks assigned
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>

        </div>

        <!-- ACTIVITY -->
        <div class="bg-white p-6 rounded shadow">

            <h3 class="text-lg font-semibold mb-4">🔔 Recent Activity</h3>

            <div class="space-y-3 text-sm">

                @forelse($activities as $activity)
                    <div>
                        <p>{{ $activity['message'] }}</p>
                        <p class="text-xs text-gray-400">
                            {{ \Carbon\Carbon::parse($activity['time'])->diffForHumans() }}
                        </p>
                    </div>
                @empty
                    <p class="text-gray-400">No activity</p>
                @endforelse

            </div>

        </div>

    </div>

</div>
</div>

</x-app-layout>