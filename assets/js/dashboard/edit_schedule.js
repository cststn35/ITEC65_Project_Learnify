let schedule_id = 0;
function editSchedule(scheduleID) {
  schedule_id = scheduleID;
  fetch_sched_edit(scheduleID);
}

async function fetch_sched_edit(scheduleID) {
  const response = await fetch(
    `/${BASE_URL}/actions/dashboard/fetch_schedule_edit.php?scheduleID=${scheduleID}`,
  );
  const data = await response.json();
  if (data.success) {
    openEditModal(data.data[0]);
  }
}

const modalOverlay = document.querySelector("#modalOverlay");
const schedFormContainer = document.querySelector(".schedule-form-container");
const formLogo = schedFormContainer.querySelector("#modal-title i");
const formHeading = schedFormContainer.querySelector("#modal-title span");
const button = schedFormContainer.querySelector("#schedule-submit");

const createAsignatura = schedFormContainer.querySelector(".create-subject");
const delSched = document.querySelector("#delSched");

const formContainer = schedFormContainer.querySelector("form");
const subjectInput = schedFormContainer.querySelector(".subjectInput select");
const dayInput = schedFormContainer.querySelector(".dayInput select");
const startTimeInput = schedFormContainer.querySelector(
  ".startTimeInput input",
);
const endTimeInput = schedFormContainer.querySelector(".endTimeInput input");
const teacherInput = schedFormContainer.querySelector(".teacherInput input");
const roomInput = schedFormContainer.querySelector(".roomInput input");

function openEditModal(data) {
  //obtaining the values needed for the form
  const subject = data.subject_id;
  const day = data.day_of_week;
  const start = data.start_time;
  const end = data.end_time;
  const teacher = data.teacher;
  const room = data.room;

  //edit the contents first, setting create task modal into edit task modal
  modalOverlay.classList.remove("opacity-0");
  modalOverlay.classList.remove("pointer-events-none");
  modalOverlay.classList.remove("scale-95");
  createAsignatura.classList.add("hidden");
  delSched.classList.remove("hidden");
  formHeading.textContent = "Edit/Delete Schedule";
  formLogo.classList.replace("bxs-plus-square", "bx-edit");
  button.textContent = "Edit Schedule";
  formContainer.removeEventListener("submit", submitCreatedSchedule); //to prevent the create task event listener from being called
  formContainer.addEventListener("submit", submitEditedSchedule);

  //injecting the obtained values
  subjectInput.querySelector(`option[value='${subject}']`).selected = true;
  dayInput.querySelector(`option[value='${day}']`).selected = true;
  startTimeInput.value = start;
  endTimeInput.value = end;
  teacherInput.value = teacher;
  roomInput.value = room;
}

async function submitEditedSchedule(e) {
  // stop normal form submission first
  e.preventDefault();

  //confirmation alert
  const result = await Swal.fire({
    title: "Edit Schedule?",
    text: "Do you want to edit this schedule?",
    icon: "question",

    showCancelButton: true,

    confirmButtonText: "Yes",
    cancelButtonText: "Cancel",
  });

  //if user clicks yes
  if (result.isConfirmed) {
    const formData = new FormData(formContainer);
    formData.append("scheduleID", schedule_id);
    const response = await fetch(
      `/${BASE_URL}/actions/dashboard/edit_schedule.php?userID=${userID}&semesterID=${semesterID}`,
      {
        method: "POST",
        body: formData,
      },
    );
    const data = await response.json();
    if (data.success) {
      Swal.fire({
        icon: "success",
        title: "Edited!",
        text: "The schedule has been successfully edited",
      });
      modalOverlay.classList.add("opacity-0");
      modalOverlay.classList.add("pointer-events-none");
      modalOverlay.classList.add("scale-95");
      fetch_schedule();
      resetFormAppearance();
    } else {
      Swal.fire({
        icon: "error",
        title: "Schedule Conflict",
        text: "Your new schedule conflicts with other existing schedules",
      });
    }
  }
}

delSched.addEventListener("click", async () => {
  //for deletion of schedule
  const result = await Swal.fire({
    title: "Are you sure?",
    text: "You won't be able to revert this!",
    icon: "warning",
    showCancelButton: true,
    confirmButtonColor: "#3085d6",
    cancelButtonColor: "#d33",
    confirmButtonText: "Yes, delete it!",
  });

  if (!result.isConfirmed) {
    return;
  }

  const response = await fetch(
    `/${BASE_URL}/actions/dashboard/delete_schedule.php?scheduleID=${schedule_id}`,
  );
  const data = await response.json();
  if (data.success) {
    Swal.fire({
      icon: "success",
      title: "Deleted!",
      text: "The schedule has been successfully deleted",
    });
    modalOverlay.classList.add("opacity-0");
    modalOverlay.classList.add("pointer-events-none");
    modalOverlay.classList.add("scale-95");
    fetch_schedule();
    resetFormAppearance();
  } else {
    Swal.fire({
      icon: "error",
      title: "Failed",
      text: data.error || data.message,
    });
  }
});

function resetFormAppearance() {
  formContainer.reset();
  createAsignatura.classList.remove("hidden");
  delSched.classList.add("hidden");
  formHeading.textContent = "Add Class Schedule";
  formLogo.classList.replace("bx-edit", "bxs-plus-square");
  button.textContent = "Create Schedule";
  formContainer.removeEventListener("submit", submitEditedSchedule);
  formContainer.addEventListener("submit", submitCreatedSchedule); //add the listener back to create task
}
