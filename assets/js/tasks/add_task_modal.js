//code for modal functionalities (open, close, create) from readymadeui
const openBtn = document.getElementById("openModal");
const closeBtn = document.getElementById("closeModal");
const cancelBtn = document.getElementById("cancelBtn");
const overlay = document.getElementById("modalOverlay");
const dialog = overlay.querySelector("[role='dialog']");
const sumite = document.getElementById("task-submit");

// Open modal and lock body scroll
openBtn.onclick = () => {
  overlay.classList.remove("opacity-0");
  overlay.classList.remove("pointer-events-none");
  overlay.classList.remove("scale-95");
  document.body.style.overflow = "hidden";
  dialog.focus();
};

// Close modal and restore focus/scroll
function closeModal() {
  overlay.classList.add("opacity-0");
  overlay.classList.add("pointer-events-none");
  overlay.classList.add("scale-95");
  document.body.style.overflow = "";
  openBtn.focus();
  resetFormAppearance();
}

closeBtn.onclick = cancelBtn.onclick = closeModal;

// Close when clicking outside the dialog
overlay.onclick = (e) => {
  if (e.target === overlay) closeModal();
};

// Keyboard accessibility
document.addEventListener("keydown", (e) => {
  if (overlay.classList.contains("hidden")) return;

  // Close on ESC
  if (e.key === "Escape") closeModal();

  // Focus trapping logic
  if (e.key === "Tab") {
    const focusable = dialog.querySelectorAll(
      "button, [href], input, select, textarea, [tabindex]:not([tabindex='-1'])",
    );
    const first = focusable[0];
    const last = focusable[focusable.length - 1];

    if (e.shiftKey && document.activeElement === first) {
      e.preventDefault();
      last.focus();
    } else if (!e.shiftKey && document.activeElement === last) {
      e.preventDefault();
      first.focus();
    }
  }
});

//js for adding task
const taskForm = document.getElementById("task-form");

taskForm.addEventListener("submit", submitCreatedTask);

async function submitCreatedTask(e) {
  // stop normal form submission first
  e.preventDefault();
  console.log("Will i get triggered too?");

  // confirmation alert
  const result = await Swal.fire({
    title: "Create Task?",
    text: "Do you want to save this task?",
    icon: "question",

    showCancelButton: true,

    confirmButtonText: "Yes",
    cancelButtonText: "Cancel",
  });

  // if user clicks yes
  if (result.isConfirmed) {
    //log first the xp
    let xp_earned = 2;
    const fd2 = new FormData();
    fd2.append("userID", userID);
    fd2.append("semesterID", semesterID);
    fd2.append("reason", "TASK_CREATED");
    fd2.append("xp", xp_earned);
    await fetch(`/${BASE_URL}/actions/log_xp.php`, {
      method: "POST",
      body: fd2,
    });

    //create task
    const formData = new FormData(taskForm);
    const response = await fetch(
      `/${BASE_URL}/actions/tasks/create_task.php?userID=${userID}&semesterID=${semesterID}`,
      {
        method: "POST",
        body: formData,
      },
    );
    const data = await response.json();
    if (data.success) {
      Swal.fire({
        icon: "success",
        title: "Created!",
        html: `<div>The task has been successfully created</div><br/>
        <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-amber-100 text-amber-700 border border-amber-200 text-sm font-semibold shadow-sm">
        <i class="bx bx-star text-base"></i>+2 XP</div>
        `,
      });

      // fetch_task("from swal");
      triggerFilter();
      taskForm.reset();
    } else {
      Swal.fire({
        icon: "error",
        title: "Failed",
        text: data.error,
      });
    }
  }
}

//for inline addition of subject/course in task form
const createSubject = document.querySelector(".create-subject");
const addSubjectForm = document.querySelector("#course-form-container");
const cancelBtn2 = document.querySelector("#cancelBtn2");
createSubject.onclick = toggleSubjectAdd;
cancelBtn2.onclick = toggleSubjectAdd;

function toggleSubjectAdd() {
  addSubjectForm.classList.toggle("hidden");
}

let selectedColor = "";
function changeBorder(color) {
  const allIcons = document.querySelectorAll(".icon");

  //reset all borders
  allIcons.forEach((icon) => {
    icon.style.backgroundColor = "";
    icon.style.borderColor = "#E2E8F0";
  });

  const iconItem = document.getElementById(color);

  iconItem.style.backgroundColor = "#EFF6FF";
  iconItem.style.borderColor = "#3b82f6";

  selectedColor = color; //database purposes
}

const courseForm = document.getElementById("course-submit");
courseForm.addEventListener("click", submitCreatedCourse);
async function submitCreatedCourse(e) {
  const description = document.getElementById("description-sub");
  const course = document.getElementById("course");
  if (course.value == "" || selectedColor == "") {
    Swal.fire({
      icon: "error",
      title: "Missing field!",
      text: "Don't forget to fill all fields",
    });
    console.log(description.value, course.value, selectedColor);
    return;
  }

  // confirmation alert
  const result = await Swal.fire({
    title: "Create Subject",
    text: "Do you want to save this subject?",
    icon: "question",

    showCancelButton: true,

    confirmButtonText: "Yes",
    cancelButtonText: "Cancel",
  });

  // if user clicks yes
  if (result.isConfirmed) {
    const formData = new FormData();
    formData.append("description", description.value);
    formData.append("course", course.value);
    formData.append("color", selectedColor);
    const response = await fetch(
      `/${BASE_URL}/actions/courses/create_courses.php?userID=${userID}&semesterID=${semesterID}`,
      {
        method: "POST",
        body: formData,
      },
    );
    const data = await response.json();
    if (data.success) {
      Swal.fire({
        icon: "success",
        title: "Created!",
        text: "The subject has been successfully created",
      });

      toggleSubjectAdd();
      fetchSubjects(userID);
    } else {
      Swal.fire({
        icon: "error",
        title: "Failed",
        text: data.error,
      });
    }
  }
}
