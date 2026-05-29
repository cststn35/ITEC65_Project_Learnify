Chart.defaults.font.family = "Inter, sans-serif";
Chart.defaults.font.size = 12;
Chart.defaults.color = "#333";

let name = "";
let today_study = 0;
let semester = 0;
let current_streak = 0;
let pending_tasks = 0;
let upcoming_tasks = 0;
let study_trend = 0;
let acadConsistency = 0;
let task_management = 0;
let priority = "N/A";
let daily_progress_goal = 0;
let priorities = [
  '<span class="inline-flex items-center gap-1 px-2 py-1 rounded-full bg-red-100 text-red-700 border border-red-200 text-xs font-medium">🔴 High Priority</span>',
  '<span class="inline-flex items-center gap-1 px-2 py-1 rounded-full bg-amber-100 text-amber-700 border border-amber-200 text-xs font-medium">🟡 Medium Priority</span>',
  '<span class="inline-flex items-center gap-1 px-2 py-1 rounded-full bg-emerald-100 text-emerald-700 border border-emerald-200 text-xs font-medium">🟢 Positive</span>',
];

let colors = ["red", "yellow", "green"];

async function fetchData() {
  const response = await fetch(
    `/${BASE_URL}/actions/dashboard/fetch_dashboard.php?userID=${userID}&semesterID=${semesterID}`,
  );
  const data = await response.json();
  if (data.success) {
    console.log(data.result);
    let analyticsData = data.result;
    daily_progress_goal =
      analyticsData["daily_progress_goal"][0]["daily_goal_minutes"];
    name =
      analyticsData["user_info"].length > 0
        ? analyticsData["user_info"][0]["name"]
        : null;
    today_study =
      analyticsData["today_study"].length > 0
        ? analyticsData["today_study"][0]
        : null;
    semester =
      analyticsData["semester"].length > 0
        ? analyticsData["semester"][0]
        : null;
    current_streak =
      analyticsData["streak"].length > 0 ? analyticsData["streak"][0] : null;
    pending_tasks =
      analyticsData["task_count"].length > 0
        ? analyticsData["task_count"][0]
        : null;
    upcoming_tasks =
      analyticsData["task_data"].length > 0 ? analyticsData["task_data"] : null;
    study_trend =
      analyticsData["study_trend"].length > 0
        ? analyticsData["study_trend"]
        : null;
    acadConsistency = analyticsData["academic_risk"];
    task_management = analyticsData["task_management"];
    initializeAnalytics();
  }
}

fetchData();

function initializeAnalytics() {
  console.log("ok");
  initializeGreeting();
  todayStudy();
  currentStreak();
  pendingTasks();
  upcomingTasks();
  startStudySession();
  studyTrendChart();
  academicConsistency();
  taskManagement();
}

function initializeGreeting() {
  const username = document.getElementById("user-name");
  username.textContent = name;
  const dailyProgressGoal = Number(daily_progress_goal);
  const currentProgress =
    today_study == null ? 0 : today_study["current_progress"];
  const minutesAway = Math.floor(dailyProgressGoal - currentProgress);

  const remainingMinutes = document.getElementById("rem-minutes");
  remainingMinutes.textContent =
    minutesAway < 0
      ? "You have reached your daily goal!"
      : `You are ${minutesAway} minutes away from your daily goal`;

  const semesterStatus = document.getElementById("semester-status");
  semesterStatus.textContent = `Semester: ${semester["semester_name"]} ${semester["school_year"]}`;
}

function todayStudy() {
  const dailyProgressGoal = Number(daily_progress_goal);
  const currentProgress =
    today_study == null ? 0 : today_study["current_progress"];
  const percentageProgress = Math.floor(
    (Number(currentProgress) / Number(dailyProgressGoal)) * 100,
  );
  const todayStudyText = document.getElementById("today-study-text");
  const todayStudyProgress = document.getElementById("today-study-progress");
  todayStudyText.textContent = `${currentProgress} / ${dailyProgressGoal} mins`;
  todayStudyProgress.textContent = `${percentageProgress > 100 ? "100" : percentageProgress}% complete`;

  let message = "",
    color = "";

  const todayStudyMessage = document.getElementById(
    "today-study-message-greet",
  );

  if (percentageProgress >= 100) {
    message = "🎉 Daily goal completed! Great job!";
    color = "text-emerald-600";
  } else if (percentageProgress >= 75) {
    message = "🔥 Almost there! Keep pushing!";
    color = "text-green-500";
  } else if (percentageProgress >= 50) {
    message = "📚 Halfway done. Stay consistent!";
    color = "text-blue-500";
  } else if (percentageProgress > 0) {
    message = "🚀 Good start! Keep going!";
    color = "text-yellow-500";
  } else {
    message = "🧩 No progress yet. Let’s start studying!";
    color = "text-slate-500";
  }

  todayStudyMessage.textContent = message;
  todayStudyMessage.classList.add(color);
}

function currentStreak() {
  const currentStreakDays = document.getElementById("current-streak-days");
  const longestStreakDays = document.getElementById("longest-streak");
  const streakDays = Number(current_streak["current_streak"] ?? 0);
  const longestStreak = Number(current_streak["longest_streak"] ?? 0);

  currentStreakDays.textContent = `${streakDays} Days`;
  longestStreakDays.textContent = `${longestStreak} Days`;

  const streakMessage = document.getElementById("streak-message");

  let message = "",
    color = "";

  let streak = streakDays;

  if (streak >= 30) {
    message = "🏆 Legendary streak! You're unstoppable.";
    color = "text-amber-500";
  } else if (streak >= 14) {
    message = "🔥 Amazing consistency! Keep the momentum.";
    color = "text-orange-500";
  } else if (streak >= 7) {
    message = "⚡ One week strong! You're building a habit.";
    color = "text-green-500";
  } else if (streak >= 3) {
    message = "📈 Nice streak! Keep showing up.";
    color = "text-blue-500";
  } else if (streak >= 1) {
    message = "🌱 Great start! Build your study habit.";
    color = "text-cyan-500";
  } else {
    message = "🧩 No streak yet. Study today to start one!";
    color = "text-slate-500";
  }

  streakMessage.textContent = message;
  streakMessage.classList.add(color);
}

function pendingTasks() {
  const all_tasks_count = Number(pending_tasks["all_tasks"] ?? 0);
  const pending_tasks_count = Number(pending_tasks["pending_tasks"] ?? 0);
  const high_priority_count = Number(pending_tasks["highprio_tasks"] ?? 0);

  const remainingTasks = document.getElementById("remaining-tasks");
  const highPriority = document.getElementById("high-priority-count");
  remainingTasks.textContent = `${pending_tasks_count} remaining`;
  highPriority.textContent = `${high_priority_count} High Priority`;

  const priorityMessage = document.getElementById("priority-message");

  let message = "";
  let color = "";

  if (pending_tasks_count === 0) {
    message = "🎉 All tasks completed! Great work today.";
    color = "text-emerald-600";
  } else if (pending_tasks_count <= 2) {
    message = `🔥 You're almost done!`;
    color = "text-green-500";
  } else if (pending_tasks_count <= 5) {
    message = `📚 Keep the momentum going.`;
    color = "text-blue-500";
  } else if (pending_tasks_count <= 10) {
    message = `⏳ Stay focused and take it one at a time.`;
    color = "text-yellow-500";
  } else {
    message = `🚀 Time to start making progress!`;
    color = "text-red-500";
  }

  if (all_tasks_count === 0) {
    message = `No tasks detected yet.`;
    color = "black";
  }

  priorityMessage.textContent = message;
  priorityMessage.classList.add(color);
}

function startStudySession() {
  const dailyProgressGoal = Number(daily_progress_goal);
  const currentProgress =
    today_study == null ? 0 : today_study["current_progress"];
  const percentageProgress = Math.floor(
    (Number(currentProgress) / Number(dailyProgressGoal)) * 100,
  );
  const todayStudyText = document.getElementById("start-study-text");
  const todayStudyProgress = document.getElementById("start-study-progress");
  const bar = document.getElementById("start-study-progress-bar");
  todayStudyText.textContent = `${currentProgress} / ${dailyProgressGoal} mins`;
  todayStudyProgress.textContent = `${percentageProgress > 100 ? "100" : percentageProgress}% complete`;

  let message = "",
    color = "";

  bar.style.width = `${percentageProgress > 100 ? 100 : percentageProgress}%`;

  const minutesAway = Math.floor(dailyProgressGoal - currentProgress);
  const remainingMinutes = document.getElementById("remaining-minutes");
  remainingMinutes.textContent =
    minutesAway < 0
      ? "You have reached your daily goal!"
      : `You are ${minutesAway} minutes away from your daily goal`;
}

function upcomingTasks() {
  if (upcoming_tasks === null) {
    return;
  }
  const taskContainer = document.getElementById("tasks-container");
  let color = "";
  let ball = "";
  let priority = "";
  taskContainer.innerHTML = "";
  upcoming_tasks.forEach((task) => {
    const today = new Date();
    let dateToday = `${today.getFullYear()}-${String(today.getMonth() + 1).padStart(2, "0")}-${String(today.getDate()).padStart(2, "0")}`;
    dateToday = new Date(dateToday);
    const deadline = new Date(task.deadline.split(" ")[0]);
    let deadlineMessage = "";
    const difference = (deadline - dateToday) / (1000 * 60 * 60 * 24);

    if (task.priority == "low") {
      color = "green";
      ball = "🟢";
      priority = "Low";
    } else if (task.priority == "medium") {
      color = "yellow";
      ball = "🟡";
      priority = "Medium";
    } else {
      color = "red";
      ball = "🔴";
      priority = "High";
    }

    if (difference < 0) {
      deadlineMessage = "Overdue";
    } else if (difference == 1) {
      deadlineMessage = "Tomorrow";
    } else {
      deadlineMessage = `In ${difference} days`;
    }
    taskContainer.innerHTML += `
    <div class="border-l-5 border-${color}-500 w-full p-3">
        <div class="flex gap-2"><span>${ball}</span><span
                class="text-lg font-semibold text-slate-700">${task.title}</span></div>
        <div class="flex gap-2 text-sm text-slate-600"><span>Due:
                ${deadlineMessage}</span><span>•</span><span>${priority} Priority</span></div>
    </div>
    `;
  });
}

function studyTrendChart() {
  let datos = new Array(7).fill(0);
  let index = 0;
  if (study_trend !== null) {
    study_trend.forEach((trend) => {
      switch (trend.weekday) {
        case "Sunday":
          index = 6;
          break;
        case "Monday":
          index = 0;
          break;
        case "Tuesday":
          index = 1;
          break;
        case "Wednesday":
          index = 2;
          break;
        case "Thursday":
          index = 3;
          break;
        case "Friday":
          index = 4;
          break;
        case "Saturday":
          index = 5;
          break;
        default:
          break;
      }
      datos[index] = trend.total_minutes;
    });
  }

  const ctx = document.getElementById("studyTrendChart");
  new Chart(ctx, {
    type: "line",
    data: {
      labels: ["Mon", "Tue", "Wed", "Thu", "Fri", "Sat", "Sun"],
      datasets: [
        {
          label: "Minutes Studied",
          data: datos,
          borderColor: "rgb(59, 130, 246)",
          backgroundColor: "rgba(59, 130, 246, 0.2)",
          fill: true,
          tension: 0.4,
        },
      ],
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
    },
  });
}

function academicConsistency() {
  let quizCount = Number(acadConsistency[0]["quiz_count"]);
  let score = Number(acadConsistency[0]["avg_quiz_score"]);
  const studyConsistencyCont = document.querySelector(".priority-cards");
  if (score >= 85) {
    title = "Academic Performance Stable";
    priority = priorities[2];
    message = "Your quiz performance is stable across subjects";
    suggestion = "Continue reinforcing concepts.";
    color = colors[2];
  } else if (score >= 70) {
    title = "Moderate Academic Risk";
    priority = priorities[1];
    message = "Some quiz performance improvement is needed.";
    suggestion = "Spend extra review time before quizzes.";
    color = colors[1];
  } else {
    title = "High Academic Risk";
    priority = priorities[0];
    message = "Quiz performance suggests possible learning difficulty.";
    suggestion =
      "Review weaker topics and take more practice quizzes. If this is your first time, start taking practice quizzes.";
    color = colors[0];
  }

  if (quizCount === 0) {
    console.log("ok");
    title = "No Quizzes Detected Yet";
    priority = "";
    message = "";
    suggestion = "Start taking quizzes to measure your academic performance.";
    color = "blue";
  }

  studyConsistencyCont.innerHTML += `
  <div class="study-consistency-cont bg-white shadow-sm rounded-lg p-4 sm:p-6 border-l-6 border-${color}-600 space-y-2">
    <div class="flex justify-between">
        <div class="flex items-center gap-2">
            <span class="bg-${color}-800 rounded-xl p-2 flex items-center justify-center"><i
                    class='bx bx-pencil text-xl text-white'></i></span>
            <span class="font-semibold">${title}</span>
        </div>
        ${priority}
    </div>
    <div>${message}</div>
    <hr class="border-t border-gray-300">
    <div class="flex items-center gap-1">
        <span><i class='bx bx-bulb'></i></span>
        <span class="text-sm">${suggestion}</span>
    </div>
  </div>
  `;
}

function taskManagement() {
  let score = Number(task_management[0]["pending_high_priority"]);
  const studyConsistencyCont = document.querySelector(".priority-cards");
  if (score == 0) {
    title = "Task Management Stable";
    priority = priorities[2];
    message = "No unfinished high-priority tasks detected.";
    suggestion = "Maintain your planning habits.";
    color = colors[2];
  } else if (score <= 2) {
    title = "Priority Task Reminder";
    priority = priorities[1];
    message = "You have a few important tasks waiting.";
    suggestion = "Complete urgent tasks first.";
    color = colors[1];
  } else {
    title = "High Priority Task Alert";
    priority = priorities[0];
    message = "Several important tasks remain unfinished.";
    suggestion = "Prioritize urgent work before low-priority tasks.";
    color = colors[0];
  }

  studyConsistencyCont.innerHTML += `
  <div class="study-consistency-cont bg-white shadow-sm rounded-lg p-4 sm:p-6 border-l-6 border-${color}-600 space-y-2">
    <div class="flex justify-between">
        <div class="flex items-center gap-2">
            <span class="bg-${color}-800 rounded-xl p-2 flex items-center justify-center"><i
                    class='bx bx-task text-xl text-white'></i></span>
            <span class="font-semibold">${title}</span>
        </div>
        ${priority}
    </div>
    <div>${message}</div>
    <hr class="border-t border-gray-300">
    <div class="flex items-center gap-1">
        <span><i class='bx bx-bulb'></i></span>
        <span class="text-sm">${suggestion}</span>
    </div>
  </div>
  `;
}
