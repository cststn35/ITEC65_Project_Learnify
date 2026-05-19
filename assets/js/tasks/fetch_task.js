const taskContainer = document.querySelector(".tasks-container");
let taskData = "";

async function fetch_task(source) {
  const response = await fetch(
    `/${BASE_URL}/actions/tasks/fetch_task.php?userID=${userID}&semesterID=${semesterID}`,
  );
  const data = await response.json();
  //biggest challenge of rendering
  if (data.success) {
    taskData = data.data;
    renderTasks(data.data);
  }
}

document.addEventListener("DOMContentLoaded", () => {
  //   fetch_task("from dom content");
  triggerFilter();
});

function formatFullMDY(sqlTimestamp) {
  const date = new Date(sqlTimestamp);
  const options = {
    year: "numeric",
    month: "long",
    day: "numeric",
  };
  return date.toLocaleDateString("en-US", options);
}

function renderTasks(data) {
  taskContainer.innerHTML = "";
  const status_pill = [
    '<span class="px-2 py-1 rounded-xl text-xs font-medium inline-flex items-center bg-green-100 text-green-700 border border-green-200">Completed</span>',
    '<span class="px-2 py-1 rounded-xl text-xs font-medium inline-flex items-center bg-yellow-100 text-yellow-700 border border-yellow-200">Due soon</span>',
    '<span class="px-2 py-1 rounded-xl text-xs font-medium inline-flex items-center bg-red-100 text-red-700 border border-red-200">Overdue</span>',
    '<span class="px-2 py-1 rounded-xl text-xs font-medium inline-flex items-center bg-gray-100 text-gray-700 border border-gray-200">Pending</span>',
  ];

  const priority_pill = [
    '<span class="inline-flex items-center gap-1 px-2 py-1 rounded-full bg-red-100 text-red-700 border border-red-200 text-xs font-medium">🔴 High</span>',
    '<span class="inline-flex items-center gap-1 px-2 py-1 rounded-full bg-amber-100 text-amber-700 border border-amber-200 text-xs font-medium">🟡 Medium</span>',
    '<span class="inline-flex items-center gap-1 px-2 py-1 rounded-full bg-emerald-100 text-emerald-700 border border-emerald-200 text-xs font-medium">🟢 Low</span>',
  ];

  data.forEach((task) => {
    const taskID = task.tasks_id;
    const title = task.title;
    const subjectName = task.name;
    const deadline = formatFullMDY(task.deadline);
    const targetTime =
      task.estimated_seconds == null ? 0 : task.estimated_seconds / 60;
    const color = task.color;
    let statusLook;
    let priorityLook;

    //for status of task
    if (task.status == "completed") {
      statusLook = status_pill[0];
    } else {
      const now = new Date();
      const due = new Date(task.deadline);
      const diffMs = due - now; //difference in second
      const diffHours = diffMs / (1000 * 60 * 60);

      if (diffMs < 0) {
        //overdue
        statusLook = status_pill[2];
      } else if (diffHours <= 24) {
        //due soon
        statusLook = status_pill[1];
      } else {
        //pending
        statusLook = status_pill[3];
      }
    }

    //for priority of task
    if (task.priority == "low") {
      priorityLook = priority_pill[2];
    } else if (task.priority == "medium") {
      priorityLook = priority_pill[1];
    } else {
      priorityLook = priority_pill[0];
    }

    taskContainer.innerHTML += `
    <div
        class="one-card bg-white border border-slate-200 shadow-sm w-full rounded-lg mx-auto p-4 sm:p-6 flex flex-col gap-3"
        data-task-id="${taskID}"
    >
        <div class="flex flex-col gap-3">
            <div class="flex gap-2">
                <div class="text-center text-4xl">
                    <i class='bx bxs-book text-${color}'></i>
                </div>

                <div class="flex flex-col gap-2">
                    <div>
                        <h1 class="font-bold text-xl">
                            ${title}
                        </h1>
                    </div>

                    <div class="flex gap-3 text-gray-600">
                        <div class="flex items-center gap-1">
                            <i class='bx bx-book-open'></i>
                            <span class="truncate w-10 md:w-auto md:whitespace-normal md:overflow-visible">
                                ${subjectName}
                            </span>
                        </div>

                        <div class="flex items-center gap-1">
                            <i class='bx bx-calendar'></i>
                            <span>${deadline}</span>
                        </div>

                        <div class="flex items-center gap-1">
                            ${
                              targetTime !== 0
                                ? `<i class='bx bx-time'></i><span>${targetTime} mins</span>`
                                : ""
                            }
                        </div>
                    </div>
                </div>
            </div>

            <div>
                ${statusLook}
                ${priorityLook}
            </div>

            <div class="flex gap-2">
                <button
                    onclick="startStudy(${taskID},${userID})"
                    ${task.status === "completed" ? "disabled" : ""}
                    class="disabled:bg-blue-300 disabled:border-blue-300 disabled:cursor-not-allowed px-3.5 py-2 text-white text-sm font-semibold bg-blue-600 hover:bg-blue-700 border border-blue-600 rounded-md transition-colors flex gap-2"
                >
                    <i class='bx bx-play text-xl'></i>
                    <span>Start Study</span>
                </button>

                <button
                    onclick="completeTask(${taskID},${userID})"
                    ${task.status === "completed" ? "disabled" : ""}
                    class="disabled:bg-green-300 disabled:border-green-300 disabled:cursor-not-allowed px-3.5 py-2 text-white text-sm font-semibold bg-green-600 hover:bg-green-700 border border-green-600 rounded-md transition-colors flex gap-2"
                >
                    <i class="bx bx-check text-xl"></i>
                    <span>Mark as Done</span>
                </button>

                <!-- kebab button -->
                <div class="relative task-card">
                    <button
                        type="button"
                        data-action="dropdown-toggle"
                        aria-haspopup="true"
                        class="h-full px-3.5 py-2 text-slate-900 text-sm font-semibold rounded-md flex items-center gap-2 cursor-pointer bg-slate-200 border border-slate-100 transition-colors hover:bg-slate-300 focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-500"
                    >
                        <i class="fa-solid fa-ellipsis-vertical text-base"></i>
                    </button>

                    <!-- Dropdown Menu -->
                    <ul
                        data-dropdown
                        class="hidden absolute right-0 mt-2 p-2 min-w-48 w-full text-slate-800 text-sm font-medium bg-white border border-slate-300 rounded-md shadow-lg z-20 overflow-hidden"
                    >
                        <li>
                            <a
                                onclick='editTask(${taskID},${userID})'
                                class="w-full p-2 flex items-center gap-2 rounded-md hover:bg-slate-100"
                            >
                                <i class='bx bx-edit text-xl'></i>
                                Edit
                            </a>
                        </li>

                        <li>
                            <a
                                onclick='deleteTask(${taskID},${userID})'
                                class="w-full p-2 flex items-center gap-2 rounded-md hover:bg-slate-100"
                            >
                                <i class='bx bx-trash text-xl'></i>
                                Delete
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
`;
  });
}
