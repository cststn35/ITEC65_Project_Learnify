const courseContainer = document.querySelector(".course-container");

async function fetch_courses(source){
    // console.log("called?");
    const response = await fetch(`/${BASE_URL}/actions/courses/fetch_courses.php?userID=${userID}&semesterID=${semesterID}`);
    const data = await response.json();
    //biggest challenge of rendering
    if(data.success){
        renderCourses(data.data,data.data2[0]);
    }
}

document.addEventListener('DOMContentLoaded',()=>{
    fetch_courses("from dom content");
});

function renderCourses(data,data2){
    courseContainer.innerHTML = "";
    const totalTab = document.getElementById("total-sub");
    const activeTab = document.getElementById("total-active");
    const archivedTab = document.getElementById("total-archived");

    totalTab.textContent = `Total: ${data2.total_subjects}`;
    activeTab.textContent = `Active: ${data2.total_subjects}`;
    archivedTab.textContent = `Archived: ${data2.archived_subjects}`;

    const status_pill = [
        '<span class="px-2 py-1 rounded-xl text-sm font-medium inline-flex items-center bg-green-100 text-green-700 border border-green-200">Active</span>',
        '<span class="px-2 py-1 rounded-xl text-sm font-medium inline-flex items-center bg-red-100 text-red-700 border border-red-200">Archived</span>'
    ];

    data.forEach(course=>{
        const subjectID = course.subject_id;
        const subjectName = course.name;
        const description = course.description
        const color = course.color;
        const taskCount = course.task_count
        let statusLook;

        //for status of course
        if(course.is_archived==0){
            statusLook = status_pill[0];
        } else {
            statusLook = status_pill[1];
        }

    courseContainer.innerHTML += `
    <div class="bg-white border-l-6 border-${color} shadow-sm rounded-lg mx-auto p-4 sm:p-6 w-full space-y-4 max-h-fit" data-course-id=${subjectID}>
        <!-- heading -->
        <div class="flex gap-2">
            <div class="text-center text-4xl">
                <i class='bx bxs-book text-${color}'></i>
            </div>
            <div class="flex flex-col">
                <div>
                    <h1 class="font-bold text-xl">${subjectName}</h1>
                </div>
                <div class="flex gap-3 text-gray-600 text-sm">
                    <p>${description}</p>
                </div>
            </div>
        </div>

        <!-- active tasks -->
        <div class="text-sm text-gray-600 flex gap-2">
            <span><i class='bx bx-task'></i></span>
            <span>Pending Tasks: ${taskCount}</span>
        </div>

        <!-- status -->
        ${statusLook}

        <!-- buttons -->
        <div class="flex gap-2">
            <button
                class="flex-1 flex justify-center items-center px-3.5 py-2 text-white text-sm font-semibold bg-blue-600 hover:bg-blue-700 border border-blue-600 rounded-lg transition-colors gap-2">
                <i class='fa-regular fa-eye'></i>
                <span>View</span>
            </button>

            <button onclick='editCourse(${subjectID},${userID},${semesterID})'
                class="px-3 py-2 text-white text-sm font-semibold bg-gray-300 hover:bg-gray-400 border border-gray-300 rounded-lg transition-colors flex items-center gap-2">
                <i class='bx bx-edit text-lg text-gray-600'></i>
            </button>

            <button onclick='deleteCourse(${subjectID},${userID},${semesterID})'
                class="px-3 py-2 text-white text-sm font-semibold bg-gray-300 hover:bg-gray-400 border border-gray-300 rounded-lg transition-colors flex items-center gap-2">
                <i class='bx bx-trash text-lg text-gray-600'></i>
            </button>
        </div>
    </div>
`;
    })
}