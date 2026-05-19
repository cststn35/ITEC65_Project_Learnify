let subject = "all-subjects";
let urgency = "due-date";
let status = "All";

const subjectFilter = document.getElementById("subject-filter");
const urgencyFilter = document.getElementById("urgency-filter");

const allBtn = document.getElementById("all");
const pendingBtn = document.getElementById("pending");
const dueSoonBtn = document.getElementById("due-soon");
const completedBtn = document.getElementById("completed");
const overdueBtn = document.getElementById("overdue");

const btns = document.querySelectorAll(".filter-btn");

btns.forEach((btn) => {
  btn.addEventListener("click", (e) => {
    btns.forEach((btn) => {
      btn.dataset.active = false;
    });
    e.target.dataset.active = true;
    status = e.target.textContent.trim();
    triggerFilter();
  });
});

subjectFilter.addEventListener("change", (e) => {
  subject = e.target.value;
  triggerFilter();
});

urgencyFilter.addEventListener("change", (e) => {
  urgency = e.target.value;
  triggerFilter();
});

async function triggerFilter() {
  console.log(subject, urgency, status);
  console.log(taskData);
  await fetch_task("from filter"); //refresh data
  //this will be tricky

  //filter subjects first
  taskData =
    subject == "all-subjects"
      ? taskData
      : taskData.filter((task) => {
          return task.subject_id == subject;
        });

  //filter by status
  taskData =
    status == "All"
      ? taskData
      : taskData.filter((task) => {
          const now = new Date();
          const due = new Date(task.deadline);
          const diffMs = due - now; //difference in second
          const diffHours = diffMs / (1000 * 60 * 60);

          if (status == "Overdue") {
            return diffMs < 0;
          } else if (status == "Due soon") {
            return diffHours <= 24;
          } else if (status == "Pending") {
            return diffHours > 24 && task.status != "completed";
          } else {
            return task.status == "completed";
          }
        });

  //sort option
  if (urgency == "due-date") {
    taskData.sort((a, b) => {
      return b.deadline.localeCompare(a.deadline);
    });
  }

  const difficultyOrder = {
    high: 1,
    medium: 2,
    low: 3,
  };

  if (urgency == "priority") {
    taskData.sort((a, b) => {
      return difficultyOrder[a.priority] - difficultyOrder[b.priority];
    });
  }
  console.log(taskData);

  renderTasks(taskData);
}
