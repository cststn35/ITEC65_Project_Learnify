let taskData = "";

async function fetch_schedule() {
  const response = await fetch(
    `/${BASE_URL}/actions/dashboard/fetch_schedule.php?userID=${userID}&semesterID=${semesterID}`,
  );
  const data = await response.json();
  if (data.success) {
    renderSchedule(data.data);
  }
}

fetch_schedule();

function convertTo12Hour(time24) {
  const [hourStr, minute, second] = time24.split(":");
  let hour = parseInt(hourStr, 10);

  const ampm = hour >= 12 ? "PM" : "AM";

  hour = hour % 12;
  hour = hour ? hour : 12; // convert 0 → 12

  return `${hour}:${minute} ${ampm}`;
}

function renderSchedule(data) {
  const messageContSched = document.querySelector(".add-class-message");
  if (data.length == 0) {
    messageContSched.classList.remove("hidden");
  } else {
    if (!messageContSched.classList.contains("hidden")) {
      messageContSched.classList.add("hidden");
    }
  }

  const allAraw = document.querySelectorAll(".araw");
  allAraw.forEach((div) => {
    div.innerHTML = "";
  });

  data.forEach((schedule) => {
    let container = "";
    if (schedule.day_of_week == 1) {
      container = document.querySelector(".sunday");
    } else if (schedule.day_of_week == 2) {
      container = document.querySelector(".monday");
    } else if (schedule.day_of_week == 3) {
      container = document.querySelector(".tuesday");
    } else if (schedule.day_of_week == 4) {
      container = document.querySelector(".wednesday");
    } else if (schedule.day_of_week == 5) {
      container = document.querySelector(".thursday");
    } else if (schedule.day_of_week == 6) {
      container = document.querySelector(".friday");
    } else if (schedule.day_of_week == 7) {
      container = document.querySelector(".saturday");
    }

    let start_time = convertTo12Hour(schedule.start_time);
    let end_time = convertTo12Hour(schedule.end_time);

    container.innerHTML += `
            <div class="cursor-pointer bg-white border border-slate-200 border-l-4 border-l-amber-300 shadow-sm rounded-2xl p-4 flex flex-col items-center justify-center
    hover:shadow-md hover:-translate-y-0.5 transition-all duration-200" onclick='editSchedule(${schedule.schedule_id})'>
                <div class="font-semibold text-slate-800 text-sm text-center">
                    ${schedule.name}
                </div>
                <div class="text-sm text-slate-500 mt-1">
                    ${start_time} - ${end_time}
                </div>
                <div class="text-sm text-slate-500 mt-1">
                    ${schedule.teacher}
                </div>
                <span
                    class="mt-3 inline-flex items-center px-2.5 py-1 rounded-full bg-slate-100 text-slate-600 text-xs font-medium">
                    ${schedule.room}
                </span>
            </div>
    `;
  });
}
