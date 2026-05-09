//code for modal functionalities (open, close, create) from readymadeui
const openBtn = document.getElementById("openModal");
const closeBtn = document.getElementById("closeModal");
const cancelBtn = document.getElementById("cancelBtn");
const overlay = document.getElementById("modalOverlay");
const dialog = overlay.querySelector("[role='dialog']");
const sumite = document.getElementById("task-submit");

// Open modal and lock body scroll
openBtn.onclick = () => {
    overlay.classList.remove("hidden");
    document.body.style.overflow = "hidden";
    dialog.focus();
};

// Close modal and restore focus/scroll
function closeModal() {
    overlay.classList.add("hidden");
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
        const focusable = dialog.querySelectorAll("button, [href], input, select, textarea, [tabindex]:not([tabindex='-1'])");
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

taskForm.addEventListener("submit",submitCreatedTask);

async function submitCreatedTask(e){
    // stop normal form submission first
    e.preventDefault();
    console.log("Will i get triggered too?")

    // confirmation alert
    const result = await Swal.fire({
        title: "Create Task?",
        text: "Do you want to save this task?",
        icon: "question",

        showCancelButton: true,

        confirmButtonText: "Yes",
        cancelButtonText: "Cancel"
    });

    // if user clicks yes
    if (result.isConfirmed) {
        const formData = new FormData(taskForm);
        const response = await fetch(`/${BASE_URL}/actions/tasks/create_task.php?userID=${userID}&semesterID=${semesterID}`,{
            method: "POST",
            body: formData
        });
        const data = await response.json();
        if(data.success){
            Swal.fire({
                icon: "success",
                title: "Created!",
                text: "The task has been successfully created"
            });

            fetch_task("from swal");
            taskForm.reset();
        } else {
            Swal.fire({
                icon: "error",
                title: "Failed",
                text: data.error
            });
        }
    }
}

