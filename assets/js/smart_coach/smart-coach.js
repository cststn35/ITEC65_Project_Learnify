let consistency = 0;
let acadConsistency = 0;
let task_management = 0;
let efficiency = 0;
let session_stability = 0;
let focus_time = 0;
let subject_performance = 0;
let daily_progress = 0;
let streak_metric = 0;
let health = 0;

let title = "N/A";
let priority = "N/A";
let message = "N/A";
let color = "N/A";
let suggestion = "N/A";

let priorities = [
  '<span class="inline-flex items-center gap-1 px-2 py-1 rounded-full bg-red-100 text-red-700 border border-red-200 text-xs font-medium">🔴 High Priority</span>',
  '<span class="inline-flex items-center gap-1 px-2 py-1 rounded-full bg-amber-100 text-amber-700 border border-amber-200 text-xs font-medium">🟡 Medium Priority</span>',
  '<span class="inline-flex items-center gap-1 px-2 py-1 rounded-full bg-emerald-100 text-emerald-700 border border-emerald-200 text-xs font-medium">🟢 Positive</span>',
];

let colors = ["red", "yellow", "green"];

async function fetchData() {
  const response = await fetch(
    `/${BASE_URL}/actions/smart_coach/fetch-coach.php?userID=${userID}&semesterID=${semesterID}`,
  );
  const data = await response.json();
  if (data.success) {
    let coach = data.result;
    consistency = coach["consistency"];
    acadConsistency = coach["academic_risk"];
    task_management = coach["task_management"];
    efficiency = coach["efficiency"];
    session_stability = coach["session_stability"];
    focus_time = coach["focus_window"];
    subject_performance = coach["subject_performance"];
    daily_progress = coach["daily_progress"];
    streak_metric = coach["streak"];
    health = coach["study_health"];

    initializeCoach();
  }
}

fetchData();

function initializeCoach() {
  initializeKPI();
  studyConsistency();
  academicConsistency();
  taskManagement();
  studyPattern();
  sessionStability();
  focusWindow();
  subjectPerformance();
  dailyProgress();
  streakMetric();
}

function initializeKPI() {
  let radius = 16;
  let study_health = Math.floor(Number(health[0]["study_health_score"]));
  const healthProgress = document.querySelector(".health-progress");
  const healthProgress2 = document.querySelector(".health-progress-text");
  let circumference = 2 * Math.PI * radius;
  let offset = circumference * (1 - study_health / 100);
  healthProgress.setAttribute("stroke-dashoffset", offset);
  healthProgress2.textContent = `${study_health}`;

  let consistency_score = Math.floor(
    Number(consistency[0]["consistency_score"]),
  );
  const consistencyProgress = document.querySelector(".consistency-progress");
  const consistencyProgress2 = document.querySelector(
    ".consistency-progress-text",
  );
  offset = circumference * (1 - consistency_score / 100);
  consistencyProgress.setAttribute("stroke-dashoffset", offset);
  consistencyProgress2.textContent = `${consistency_score}`;
}

function studyConsistency() {
  let score = Number(consistency[0]["consistency_score"]);
  const studyConsistencyCont = document.querySelector(".priority-cards");
  if (score >= 85) {
    title = "Strong Study Consistency";
    priority = priorities[2];
    message = "You consistently meet your daily study goal.";
    suggestion = "Maintain your current routine.";
    color = colors[2];
  } else if (score >= 60) {
    title = "Moderate Study Consistency";
    priority = priorities[1];
    message = "You meet your study goal sometimes, but not consistently.";
    suggestion = "Try shorter but more regular sessions.";
    color = colors[1];
  } else {
    title = "Low Study Consistency";
    priority = priorities[0];
    message = "You frequently miss your daily study goal.";
    suggestion =
      "Aim for smaller but consistent study sessions daily. If this is your first time, start building one.";
    color = colors[0];
  }

  studyConsistencyCont.innerHTML += `
  <div class="study-consistency-cont bg-white shadow-sm rounded-lg p-4 sm:p-6 border-l-6 border-${color}-600 space-y-2">
    <div class="flex justify-between">
        <div class="flex items-center gap-2">
            <span class="bg-${color}-800 rounded-xl p-2 flex items-center justify-center"><i
                    class='fa-solid fa-bullseye text-xl text-white'></i></span>
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

function studyPattern() {
  let avg_target_minutes = Number(efficiency[0]["avg_target_minutes"]);
  let avg_actual_minutes = Number(efficiency[0]["avg_actual_minutes"]);
  let score = avg_target_minutes - avg_actual_minutes;
  const cont = document.querySelector(".proCoachCont");

  if (score <= 10) {
    title = "Efficient Study Pattern";
    priority = priorities[2];
    message =
      "Your planned study duration closely matches reality or goes beyond the plan.";
    suggestion = "Keep your current pacing.";
    color = colors[2];
  } else if (score <= 25) {
    title = "Minor Overplanning";
    priority = priorities[1];
    message = "You often stop earlier than planned.";
    suggestion = "Adjust session duration to realistic targets.";
    color = colors[1];
  } else {
    title = "Overplanning Detected";
    priority = priorities[0];
    message = "Study sessions are much shorter than planned duration.";
    suggestion = "Try shorter yet realistic sessions (25–40 mins).";
    color = colors[0];
  }

  if (avg_target_minutes == 0 && avg_actual_minutes == 0) {
    title = "No Study Pattern Detected Yet";
    message = "";
    suggestion = "Start building your study session first.";
    priority = "";
    color = "blue";
  }

  cont.innerHTML += ` <div class="bg-white shadow-sm rounded-lg p-4 sm:p-6 border-l-6 border-${color}-500 space-y-4 w-full">
                        <!-- header -->
                        <div class="flex justify-between">
                            <div class="flex items-center gap-2">
                                <span class="bg-${color}-800 rounded-xl p-2 flex items-center justify-center"><i
                                        class='bx bx-alarm text-xl text-white'></i></span>
                                <span class="font-semibold">${title}</span>
                            </div>
                            ${priority}

                        </div>
                        <div>
                            <div class="flex justify-between">
                                <span>Avg Target</span>
                                <span>${avg_target_minutes} minute(s)</span>
                            </div>
                            <div class="flex justify-between">
                                <span>Avg Actual</span>
                                <span class="font-semibold">${avg_actual_minutes} minute(s)</span>
                            </div>
                        </div>
                        <!-- comment -->
                        <div>${message}</div>
                        <hr class="border-t border-gray-300">
                        <div class="flex items-center gap-1">
                            <span><i class='bx bx-bulb'></i></span>
                            <span class="text-sm">${suggestion}</span>
                        </div>
                    </div>`;
}

function sessionStability() {
  let score = Number(session_stability[0]["completion_rate"]);
  const cont = document.querySelector(".proCoachCont");
  if (score >= 85) {
    title = "Session Stability Strong";
    priority = priorities[2];
    message = "Most study sessions are completed successfully.";
    suggestion = "Maintain your environment and routine.";
    color = colors[2];
  } else if (score >= 70) {
    title = "Moderate Session Stability";
    priority = priorities[1];
    message = "Some sessions end early or are abandoned.";
    suggestion = "Reduce distractions during study time.";
    color = colors[1];
  } else {
    title = "Session Abandonment Concern";
    priority = priorities[0];
    message =
      "Many sessions are abandoned before completion or no sessions are created yet.";
    suggestion = "Try shorter sessions and study in focused environments.";
    color = colors[0];
  }

  if (score === 0) {
    title = "No Session Detected Yet";
    priority = "";
    message = "";
    suggestion = "Start taking study session first.";
    color = "blue";
  }

  cont.innerHTML += `<div class="bg-white shadow-sm rounded-lg p-4 sm:p-6 border-l-6 border-${color}-500 space-y-4 w-full">
                        <div class="flex justify-between">
                            <div class="flex items-center gap-2">
                                <span class="bg-${color}-800 rounded-xl p-2 flex items-center justify-center"><i
                                        class='fa-solid fa-graduation-cap text-xl text-white'></i></span>
                                <span class="font-semibold">${title}</span>
                            </div>
                            ${priority}
                        </div>
                        <div>
                            <span class="font-bold text-2xl">${score}%</span>
                            <span class="text-sm">of sessions are completed</span>
                        </div>
                        <div>${message}</div>
                        <hr class="border-t border-gray-300">
                        <div class="flex items-center gap-1">
                            <span><i class='bx bx-bulb'></i></span>
                            <span class="text-sm">${suggestion}</span>
                        </div>
                    </div>`;
}

function focusWindow() {
  const cont = document.querySelector(".focusCont");
  let time = "",
    meridiem = "";
  if (focus_time.length !== 0) {
    time = Number(focus_time[0]["start_hour"]) || 0;
    meridiem = time > 11 ? "PM" : "AM";
    time = Math.abs(time - 12);
  }

  cont.innerHTML += `<div class="bg-white shadow-sm rounded-lg p-4 sm:p-6 border-l-6 border-blue-500 space-y-2">
                    <!-- header -->
                    <div class="flex justify-between">
                        <div class="flex items-center gap-2">
                            <span class="bg-blue-800 rounded-xl p-2 flex items-center justify-center"><i
                                    class='bx bx-bell text-xl text-white'></i></span>
                            <span class="font-semibold">Best Focus Time</span>
                        </div>

                    </div>
                    <!-- comment -->
                    <div>${focus_time.length === 0 ? "" : `You focus and perform best around <b>${time} ${meridiem}`}</b></div>
                    <hr class="border-t border-gray-300">
                    <div class="flex items-center gap-1">
                        <span><i class='bx bx-bulb'></i></span>
                        <span class="text-sm">${focus_time.length === 0 ? "Start building your study session first." : "Schedule difficult subjects during this time."}</span>
                    </div>
                </div>`;
}

function subjectPerformance() {
  const cont = document.querySelector(".acadCoachCont");
  let length, weakSub, strongSub, score, score2, color, priority;
  if (subject_performance.length !== 0) {
    length = subject_performance.length;
    weakSub =
      Number(subject_performance[length - 1]["avg_score"]) <= 75
        ? subject_performance[length - 1]["subject_name"]
        : "N/A";
    strongSub = subject_performance[0]["subject_name"];
    score =
      Number(subject_performance[length - 1]["avg_score"]) <= 75
        ? Number(subject_performance[length - 1]["avg_score"])
        : 0;
    score2 = Number(subject_performance[0]["avg_score"]);
    color = weakSub != "N/A" ? "red" : "gray";
    priority = weakSub != "N/A" ? priorities[0] : "";
  } else {
    color = "blue";
    priority = "";
  }

  cont.innerHTML += `
  <div class="bg-white shadow-sm rounded-lg p-4 sm:p-6 border-l-6 border-${color}-500 space-y-4 w-full">
                    <div class="flex justify-between">
                        <div class="flex items-center gap-2">
                            <span class="bg-${color}-800 rounded-xl p-2 flex items-center justify-center"><i
                                    class='bx bx-down-arrow-alt text-xl text-white'></i></span>
                            <span class="font-semibold">Subject To Improve</span>
                        </div>
                        ${priority}
                    </div>
                    <div>
                        <div class="font-bold text-2xl">${weakSub || "No subjects/quizzes detected yet"}</div>
                        <div>
                            <div class="flex justify-between">
                                <span>Quiz Average</span>
                                <span>${score || 0}%</span>
                            </div>
                            <div>
                                <div class="w-full h-2 bg-slate-400 rounded-lg">
                                    <div class="w-[${score || 0}%] h-2 bg-red-500 rounded-lg"></div>
                                </div>
                            </div>
                        </div>

                    </div>
                    <div>${weakSub === undefined ? "" : weakSub != "N/A" ? `${weakSub} has the lowest mastery score` : "No weak subject detected (lower than 75%)"}
                    </div>
                    <hr class="border-t border-gray-300">
                    <div class="flex items-center gap-1">
                        <span><i class='bx bx-bulb'></i></span>
                        <span class="text-sm">
                        ${weakSub === undefined ? "Start adding subjects and taking quiz first." : weakSub != "N/A" ? `Consider reviewing notes and take quizzes after studying for this subject.` : "No concern right now"}
                        </span>
                    </div>
                </div>`;

  cont.innerHTML += `
  <div class="bg-white shadow-sm rounded-lg p-4 sm:p-6 border-l-6 border-green-500 space-y-4 w-full">
                    <div class="flex justify-between">
                        <div class="flex items-center gap-2">
                            <span class="bg-green-800 rounded-xl p-2 flex items-center justify-center"><i
                                    class='bx bx-up-arrow-alt text-xl text-white'></i></span>
                            <span class="font-semibold">Subject Most Mastered</span>
                        </div>
                        ${strongSub === undefined ? "" : priorities[2]}
                    </div>
                    <div>
                        <div class="font-bold text-2xl">${strongSub || "No subjects/quizzes detected yet"}</div>
                        <div>
                            <div class="flex justify-between">
                                <span>Quiz Average</span>
                                <span>${score2 || 0}%</span>
                            </div>
                            <div>
                                <div class="w-full h-2 bg-slate-400 rounded-lg">
                                    <div class="w-[${score2 || 0}%] h-2 bg-green-500 rounded-lg"></div>
                                </div>
                            </div>
                        </div>

                    </div>
                    <div>${strongSub === undefined ? "" : `${strongSub} has the highest mastery score among the subjects.`}
                    </div>
                    <hr class="border-t border-gray-300">
                    <div class="flex items-center gap-1">
                        <span><i class='bx bx-bulb'></i></span>
                        <span class="text-sm">${strongSub === undefined ? "Start adding subjects and taking quiz first." : "Continue reinforcing topics and maintain the grit."}</span>
                    </div>
                </div>`;
}

function dailyProgress() {
  const cont = document.querySelector(".streak-cont");
  let score = Number(daily_progress[0]["progress_percent"]);
  let remaining =
    Number(daily_progress[0]["daily_goal_minutes"]) -
    Number(daily_progress[0]["total_minutes"]);
  if (score >= 100) {
    title = "Daily Goal Achieved";
    priority = priorities[2];
    message = "You completed today's study goal.";
    suggestion = "Optional light review only.";
    color = colors[2];
  } else if (score >= 70) {
    title = "Almost There";
    priority = priorities[1];
    message = "You are close to today's goal.";
    suggestion = `Study ${remaining} more minutes.`;
    color = "blue";
  } else {
    title = "Daily Goal Reminder";
    priority = priorities[1];
    message = "Today's study goal is still far from completion.";
    suggestion = "Start a focused study session.";
    color = colors[1];
  }

  cont.innerHTML += `
  <div class="bg-white shadow-sm rounded-lg p-4 sm:p-6 border-l-6 border-${color}-600 space-y-2 w-full">
    <div class="flex justify-between">
        <div class="flex items-center gap-2">
            <span class="bg-${color}-800 rounded-xl p-2 flex items-center justify-center"><i
                    class='fa-solid fa-bullseye text-xl text-white'></i></span>
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

function streakMetric() {
  const cont = document.querySelector(".streak-cont");
  let score = Number(streak_metric[0]["current_streak"]);
  if (score >= 30) {
    title = "Exceptional Streak";
    priority = priorities[2];
    message = "You are maintaining excellent consistency.";
    suggestion = "Keep momentum going.";
    color = colors[2];
  } else if (score >= 7) {
    title = "Streak Active";
    priority = priorities[2];
    message = "Strong habit formation is developing.";
    suggestion = `Keep the streak alive.`;
    color = priorities[2];
  } else {
    title = "Build Your Momentum";
    priority = priorities[2];
    message = "You are still building study consistency.";
    suggestion = "Study daily to grow your streak.";
    color = "blue";
  }

  cont.innerHTML += `
  <div class="bg-white shadow-sm rounded-lg p-4 sm:p-6 border-l-6 border-${color}-600 space-y-2 w-full">
    <div class="flex justify-between">
        <div class="flex items-center gap-2">
            <span class="bg-${color}-800 rounded-xl p-2 flex items-center justify-center"><i
                    class='bx bxs-hot text-xl text-white'></i></span>
            <span class="font-semibold">${title}</span>
        </div>
        ${color == "blue" ? "" : priority}
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
