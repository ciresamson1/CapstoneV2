<x-app-layout>

<x-slot name="header">
<h2 class="font-semibold text-xl text-gray-800 leading-tight">
Project Gantt Chart
</h2>

<div class="flex justify-end mb-4">
<button id="todayBtn" class="px-4 py-2 bg-blue-600 text-white rounded">
Today
</button>
</div>

</x-slot>

<div class="py-6">
<div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/frappe-gantt@0.6.1/dist/frappe-gantt.css">



<div id="gantt-wrapper" style="min-width:1500px">
<svg id="gantt" style="height:500px;"></svg>
</div>



</div>
</div>

<script src="https://cdn.jsdelivr.net/npm/frappe-gantt@0.6.1/dist/frappe-gantt.min.js"></script>

<style>

/* disable dragging and resizing */
.bar-wrapper {
    pointer-events: none;
}

/* hide resize handles */
.handle-group {
    display: none !important;
}


</style>

<script>

document.addEventListener("DOMContentLoaded", function () {

let tasks = @json($tasks);

var gantt = new Gantt("#gantt", tasks, {
    view_mode: "Week",
    readonly: true
});

document.getElementById("todayBtn").addEventListener("click", function(){

    gantt.change_view_mode("Week");

    setTimeout(function(){
        const today = new Date();
        const column = document.querySelector('[data-date="'+today.toISOString().slice(0,10)+'"]');

        if(column){
            column.scrollIntoView({behavior:"smooth", inline:"center"});
        }

    }, 200);

});

});

</script>

</x-app-layout>