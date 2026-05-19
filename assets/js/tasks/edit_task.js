let task_id;
let user_id;

function editTask(taskID, userID) {
  task_id = taskID;
  user_id = userID;
  fetch_task_edit(taskID, userID);
}

async function fetch_task_edit(taskID, userID) {
  const response = await fetch(
    `/${BASE_URL}/actions/tasks/fetch_task.php?userID=${userID}&tasks_id=${taskID}&semesterID=${semesterID}`,
  );
  const data = await response.json();
  if (data.success) {
    openEditModal(data.data[0]);
  }
}

const modalOverlay = document.querySelector("#modalOverlay");
const taskFormContainer = document.querySelector(".task-form-container");
const formLogo = taskFormContainer.querySelector("#modal-title i");
const formHeading = taskFormContainer.querySelector("#modal-title span");
const button = taskFormContainer.querySelector("#task-submit");

const formContainer = taskFormContainer.querySelector("form");
const titleInput = taskFormContainer.querySelector(".titleInput input");
const descriptionInput = taskFormContainer.querySelector(
  ".descriptionInput input",
);
const subjectInput = taskFormContainer.querySelector(".subjectInput select");
const deadlineinput = taskFormContainer.querySelector(".deadlineInput input");
const priorityInput = taskFormContainer.querySelector(".priorityInput select");
const timeInput = taskFormContainer.querySelector(".timeInput input");

function openEditModal(data) {
  //obtaining the values needed for the form
  const title = data.title;
  const desc = data.description;
  const subject = data.subject_id;
  const deadline = data.deadline;
  const priority = data.priority;
  const time =
    data.estimated_seconds !== null ? parseInt(data.estimated_seconds) / 60 : 0;

  //edit the contents first, setting create task modal into edit task modal
  modalOverlay.classList.remove("opacity-0");
  modalOverlay.classList.remove("pointer-events-none");
  modalOverlay.classList.remove("scale-95");
  formHeading.textContent = "Edit Task";
  formLogo.classList.replace("bxs-plus-square", "bx-edit");
  button.textContent = "Edit Task";
  formContainer.removeEventListener("submit", submitCreatedTask); //to prevent the create task event listener from being called
  formContainer.addEventListener("submit", submitEditedTask);

  //injecting the obtained values
  titleInput.value = title;
  descriptionInput.value = desc;
  subjectInput.querySelector(`option[value='${subject}']`).selected = true;
  deadlineinput.value = deadline.split(" ")[0]; //remove time portion
  priorityInput.querySelector(`option[value=${priority}]`).selected = true;
  timeInput.value = time;
}

async function submitEditedTask(e) {
  // stop normal form submission first
  e.preventDefault();
  console.log("I should be triggered");

  //confirmation alert
  const result = await Swal.fire({
    title: "Edit Task?",
    text: "Do you want to edit this task?",
    icon: "question",

    showCancelButton: true,

    confirmButtonText: "Yes",
    cancelButtonText: "Cancel",
  });

  //if user clicks yes
  if (result.isConfirmed) {
    const formData = new FormData(formContainer);
    const response = await fetch(
      `/${BASE_URL}/actions/tasks/edit_task.php?userID=${user_id}&tasks_id=${task_id}&semesterID=${semesterID}`,
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
        text: "The task has been successfully edited",
      });
      triggerFilter();
      modalOverlay.classList.add("opacity-0");
      modalOverlay.classList.add("pointer-events-none");
      modalOverlay.classList.add("scale-95");
      resetFormAppearance();
    } else {
      Swal.fire({
        icon: "error",
        title: "Failed",
        text: data.error || data.message,
      });
    }
  }
}

function resetFormAppearance() {
  formContainer.reset();
  formHeading.textContent = "Create Task";
  formLogo.classList.replace("bx-edit", "bxs-plus-square");
  button.textContent = "Create Task";
  formContainer.removeEventListener("submit", submitEditedTask);
  formContainer.addEventListener("submit", submitCreatedTask); //add the listener back to create task
}
