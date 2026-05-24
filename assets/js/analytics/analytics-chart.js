Chart.defaults.font.family = "Inter, sans-serif";
Chart.defaults.font.size = 12;
Chart.defaults.color = "#333";

let study_time = 0;
let quiz_average = 0;
let tasks_done = 0;
let streak = 0;
let study_trend = [];
let study_by_subject = 0;
let study_consistency = 0;
let peak_study_hours = 0;
let goal_achievement_rate = 0;
let planned_actual_study_time = 0;
let session_completion_rate = 0;
let task_completion = 0;
let quiz_trend = 0;
let subject_mastery = 0;
let studytime_quiz = 0;
let xp_growth = 0;
let xp_breakdown = 0;

async function fetchData() {
  const response = await fetch(
    `/${BASE_URL}/actions/analytics/fetch-analytics.php?userID=${userID}&semesterID=${semesterID}`,
  );
  const data = await response.json();
  if (data.success) {
    let analyticsData = data.result;
    console.log(analyticsData);
    study_time = analyticsData["total_study_time"][0]["total_hours"];
    quiz_average = analyticsData["quiz_average_score"][0]["quiz_average"];
    tasks_done = analyticsData["task_completion"];
    streak = analyticsData["streak"];
    study_trend = analyticsData.study_trend;
    study_by_subject = analyticsData["study_by_subject"];
    study_consistency = analyticsData["study_consistency"];
    peak_study_hours = analyticsData["peak_study_hours"];
    goal_achievement_rate = analyticsData["goal_achievement_rate"];
    planned_actual_study_time = analyticsData["planned_vs_actual"];
    session_completion_rate = analyticsData["session_completion_rate"];
    task_completion = analyticsData["task_on_time"];
    quiz_trend = analyticsData["quiz_trend"];
    subject_mastery = analyticsData["subject_mastery"];
    studytime_quiz = analyticsData["study_vs_quiz"];
    xp_growth = analyticsData["xp_growth"];
    xp_breakdown = analyticsData["xp_source_breakdown"];
    initializeAnalytics();
  }
}

fetchData();

function initializeAnalytics() {
  initializeKPI();
  studyTrendChart();
  studySubjectChart();
  consistencyChart();
  peakStudyChart();
  plannedActualTime();
  sessionCompletionChart();
  taskCompletionChart();
  quizTrend();
  subjectMasteryChart();
  studyQuizChart();
  xpGrowthChart();
  xpBreakdownChart();
}

function initializeKPI() {
  const studyTime = document.querySelector(".study-time-hours");
  const quizAvg = document.querySelector(".quiz-average");
  const tasksDone = document.querySelector(".tasks-done");
  const streaks = document.querySelector(".streak-count");
  studyTime.textContent = `${study_time ?? 0} Hour(s)`;
  quizAvg.textContent = `${quiz_average ?? 0}%`;
  tasksDone.textContent = `${tasks_done[0]["completed_tasks"] ?? 0}/${tasks_done[0]["total_tasks"]}`;
  streaks.textContent = `${streak[0]["current_streak"]} Day(s)`;
  console.log(study_time);
  console.log(quiz_average);
  console.log(tasks_done);
  console.log(streak);
}

function studyTrendChart() {
  let datos = new Array(7).fill(0);
  let index = 0;
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

function studySubjectChart() {
  let subjects = [];
  let datos = [];
  const ctx = document.getElementById("studySubjectChart");
  study_by_subject.forEach((subject) => {
    subjects.push(subject.name);
    datos.push(subject.total_hours);
  });
  new Chart(ctx, {
    type: "bar",
    data: {
      labels: subjects,
      datasets: [
        {
          label: "Hours Studied",
          data: datos,
          backgroundColor: [
            "rgba(255, 99, 132, 0.2)",
            "rgba(255, 159, 64, 0.2)",
            "rgba(255, 205, 86, 0.2)",
            "rgba(75, 192, 192, 0.2)",
            "rgba(54, 162, 235, 0.2)",
            "rgba(153, 102, 255, 0.2)",
            "rgba(201, 203, 207, 0.2)",
          ],
          borderColor: [
            "rgb(255, 99, 132)",
            "rgb(255, 159, 64)",
            "rgb(255, 205, 86)",
            "rgb(75, 192, 192)",
            "rgb(54, 162, 235)",
            "rgb(153, 102, 255)",
            "rgb(201, 203, 207)",
          ],
          borderWidth: 1,
        },
      ],
    },
    options: {
      scales: {
        y: {
          beginAtZero: true,
        },
      },
      responsive: true,
      maintainAspectRatio: false,
    },
  });
}

function consistencyChart() {
  let percentage = study_consistency[0]["consistency_score"] ?? 0 / 100;
  let radius = 16;
  let circumference = 2 * Math.PI * radius;
  let offset = circumference * (1 - percentage);
  const chart = document.getElementById("consistencyProgress");
  chart.setAttribute("stroke-dashoffset", offset);
  document.getElementById("percentage").textContent =
    `${study_consistency[0]["consistency_score"] ?? 0}%`;
}

function peakStudyChart() {
  console.log(peak_study_hours);
  let datos = new Array(24).fill(0);
  peak_study_hours.forEach((hour) => {
    let index;
    switch (hour.start_hour) {
      case 0:
        index = 0;
        break;
      case 1:
        index = 1;
        break;
      case 2:
        index = 2;
        break;
      case 3:
        index = 3;
        break;
      case 4:
        index = 4;
        break;
      case 5:
        index = 5;
        break;
      case 6:
        index = 6;
        break;
      case 7:
        index = 7;
        break;
      case 8:
        index = 8;
        break;
      case 9:
        index = 9;
        break;
      case 10:
        index = 10;
        break;
      case 11:
        index = 11;
        break;
      case 12:
        index = 12;
        break;
      case 13:
        index = 13;
        break;
      case 14:
        index = 14;
        break;
      case 15:
        index = 15;
        break;
      case 16:
        index = 16;
        break;
      case 17:
        index = 17;
        break;
      case 18:
        index = 18;
        break;
      case 19:
        index = 19;
        break;
      case 20:
        index = 20;
        break;
      case 21:
        index = 21;
        break;
      case 22:
        index = 22;
        break;
      case 23:
        index = 23;
        break;
      default:
        index = -1;
    }

    datos[index] = hour.total_sessions;
  });
  let timeLabel = [
    "12 AM",
    "1 AM",
    "2 AM",
    "3 AM",
    "4 AM",
    "5 AM",
    "6 AM",
    "7 AM",
    "8 AM",
    "9 AM",
    "10 AM",
    "11 AM",
    "12 PM",
    "1 PM",
    "2 PM",
    "3 PM",
    "4 PM",
    "5 PM",
    "6 PM",
    "7 PM",
    "8 PM",
    "9 PM",
    "10 PM",
    "11 PM",
  ];
  const ctx = document.getElementById("peakStudyChart");
  new Chart(ctx, {
    type: "bar",
    data: {
      labels: timeLabel,
      datasets: [
        {
          label: "Total Study Sessions",
          data: datos,
          backgroundColor: [
            "rgba(255, 99, 132, 0.6)",
            "rgba(54, 162, 235, 0.6)",
            "rgba(255, 206, 86, 0.6)",
            "rgba(75, 192, 192, 0.6)",
            "rgba(153, 102, 255, 0.6)",
            "rgba(255, 159, 64, 0.6)",
            "rgba(199, 199, 199, 0.6)",
            "rgba(83, 102, 255, 0.6)",
            "rgba(255, 99, 255, 0.6)",
            "rgba(99, 255, 132, 0.6)",
            "rgba(255, 140, 0, 0.6)",
            "rgba(0, 200, 83, 0.6)",
            "rgba(0, 188, 212, 0.6)",
            "rgba(121, 85, 72, 0.6)",
            "rgba(63, 81, 181, 0.6)",
            "rgba(244, 67, 54, 0.6)",
            "rgba(156, 39, 176, 0.6)",
            "rgba(33, 150, 243, 0.6)",
            "rgba(255, 235, 59, 0.6)",
            "rgba(0, 150, 136, 0.6)",
            "rgba(255, 87, 34, 0.6)",
            "rgba(96, 125, 139, 0.6)",
            "rgba(205, 220, 57, 0.6)",
            "rgba(233, 30, 99, 0.6)",
          ],
          borderColor: [
            "rgba(255, 99, 132, 1)",
            "rgba(54, 162, 235, 1)",
            "rgba(255, 206, 86, 1)",
            "rgba(75, 192, 192, 1)",
            "rgba(153, 102, 255, 1)",
            "rgba(255, 159, 64, 1)",
            "rgba(199, 199, 199, 1)",
            "rgba(83, 102, 255, 1)",
            "rgba(255, 99, 255, 1)",
            "rgba(99, 255, 132, 1)",
            "rgba(255, 140, 0, 1)",
            "rgba(0, 200, 83, 1)",
            "rgba(0, 188, 212, 1)",
            "rgba(121, 85, 72, 1)",
            "rgba(63, 81, 181, 1)",
            "rgba(244, 67, 54, 1)",
            "rgba(156, 39, 176, 1)",
            "rgba(33, 150, 243, 1)",
            "rgba(255, 235, 59, 1)",
            "rgba(0, 150, 136, 1)",
            "rgba(255, 87, 34, 1)",
            "rgba(96, 125, 139, 1)",
            "rgba(205, 220, 57, 1)",
            "rgba(233, 30, 99, 1)",
          ],
          borderWidth: 1,
        },
      ],
    },
    options: {
      scales: {
        y: {
          beginAtZero: true,
        },
      },
      responsive: true,
      maintainAspectRatio: false,
      plugins: {
        legend: {
          display: false,
        },
      },
    },
  });
}

function plannedActualTime() {
  console.log(planned_actual_study_time);
  const avg_target_minutes = planned_actual_study_time[0]["avg_target_minutes"];
  const avg_actual_minutes = planned_actual_study_time[0]["avg_actual_minutes"];
  const ctx = document.getElementById("plannedActualChart");
  const labels = ["Average Target Minutes", "Average Actual Minutes"];
  const data = {
    labels: labels,
    datasets: [
      {
        axis: "y",
        label: "Minutes Studied",
        data: [avg_target_minutes, avg_actual_minutes],
        fill: false,
        backgroundColor: [
          "rgba(148, 163, 184, 0.7)",
          "rgba(59, 130, 246, 0.7)",
        ],
        borderColor: ["rgb(255, 99, 132)", "rgba(59, 130, 246, 1)"],
        borderWidth: 1,
      },
    ],
  };
  const config = {
    type: "bar",
    data,
    options: {
      indexAxis: "y",
      responsive: true,
      maintainAspectRatio: false,
      plugins: {
        legend: {
          display: false,
        },
      },
    },
  };
  new Chart(ctx, config);
}

function sessionCompletionChart() {
  console.log(session_completion_rate);
  const ctx = document.getElementById("sessionCompletionChart");

  let active = 0;
  let complete = 0;

  session_completion_rate.forEach((status) => {
    if (status.status == "completed") {
      complete += status.total;
    } else {
      active += status.total;
    }
  });

  const data = {
    labels: ["Incomplete", "Complete"],
    datasets: [
      {
        label: "Total Number",
        data: [active, complete],
        backgroundColor: ["rgb(255, 99, 132)", "rgb(255, 205, 86)"],
        hoverOffset: 4,
      },
    ],
  };
  const config = {
    type: "doughnut",
    data: data,
    options: {
      responsive: true,
      maintainAspectRatio: false,
    },
  };

  new Chart(ctx, config);
}

function taskCompletionChart() {
  console.log(task_completion);
  const on_time_percentages = Math.floor(
    ((Number(task_completion[0]["on_time_tasks"]) || 0) /
      (Number(task_completion[0]["completed_tasks"]) || 1)) *
      100,
  );

  let lates = 0;
  console.log(on_time_percentages);
  if ((Number(task_completion[0]["completed_tasks"]) || 0) === 0) {
    lates = 0;
  } else {
    lates = 100 - on_time_percentages;
  }

  const on_time = document.querySelector(".on-time");
  const on_time_percentage = document.querySelector(".on-time-percentage");
  const late = document.querySelector(".late");
  const late_percentage = document.querySelector(".late-percentage");

  on_time.textContent = `${on_time_percentages ?? 0}%`;
  on_time_percentage.style.width = `${on_time_percentages}%`;
  late.textContent = `${lates}%`;
  late_percentage.style.width = `${lates}%`;
}

function quizTrend() {
  console.log(quiz_trend);
  let datos = new Array(7).fill(0);
  let index = 0;
  quiz_trend.forEach((trend) => {
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
    datos[index] = trend.avg_score;
  });

  const ctx = document.getElementById("quizTrendChart");
  new Chart(ctx, {
    type: "line",
    data: {
      labels: ["Mon", "Tue", "Wed", "Thu", "Fri", "Sat", "Sun"],
      datasets: [
        {
          label: "Average Quiz Score (%)",
          data: datos,
          borderColor: "rgb(75, 192, 192)",
          fill: false,
          tension: 0.1,
        },
      ],
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
    },
  });
}

function subjectMasteryChart() {
  console.log(subject_mastery);
  let subjects = [];
  let datos = [];
  const ctx = document.getElementById("subjectMasteryChart");
  subject_mastery.forEach((subject) => {
    subjects.push(subject.name);
    datos.push(subject.mastery_score);
  });
  new Chart(ctx, {
    type: "bar",
    data: {
      labels: subjects,
      datasets: [
        {
          label: "Mastery Score (%)",
          data: datos,
          backgroundColor: [
            "rgba(255, 99, 132, 0.2)",
            "rgba(255, 159, 64, 0.2)",
            "rgba(255, 205, 86, 0.2)",
            "rgba(75, 192, 192, 0.2)",
            "rgba(54, 162, 235, 0.2)",
            "rgba(153, 102, 255, 0.2)",
            "rgba(201, 203, 207, 0.2)",
          ],
          borderColor: [
            "rgb(255, 99, 132)",
            "rgb(255, 159, 64)",
            "rgb(255, 205, 86)",
            "rgb(75, 192, 192)",
            "rgb(54, 162, 235)",
            "rgb(153, 102, 255)",
            "rgb(201, 203, 207)",
          ],
          borderWidth: 1,
        },
      ],
    },
    options: {
      indexAxis: "y",
      responsive: true,
      maintainAspectRatio: false,
    },
  });
}

function studyQuizChart() {
  console.log(studytime_quiz);
  let datos = [];
  studytime_quiz.forEach((element) => {
    datos.push({
      x: element.study_minutes,
      y: element.quiz_percent,
    });
  });
  const ctx = document.getElementById("studyQuizChart");
  const data = {
    datasets: [
      {
        label: "Minutes, Score (%)",
        data: datos,
        backgroundColor: "rgba(59, 130, 246, 0.8)",
      },
    ],
  };

  const config = {
    type: "scatter",
    data: data,
    options: {
      scales: {
        x: {
          title: {
            display: true,
            text: "Minutes Studied", // X-axis label
          },
        },
        y: {
          title: {
            display: true,
            text: "Quiz Score", // Y-axis label
          },
        },
      },
      responsive: true,
      maintainAspectRatio: false,
    },
  };

  new Chart(ctx, config);
}

function xpGrowthChart() {
  console.log(xp_growth);
  let datos = new Array(7).fill(0);
  let index = 0;
  xp_growth.forEach((trend) => {
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
    datos[index] = trend.total_xp;
  });

  const ctx = document.getElementById("xpGrowthChart");
  new Chart(ctx, {
    type: "line",
    data: {
      labels: ["Mon", "Tue", "Wed", "Thu", "Fri", "Sat", "Sun"],
      datasets: [
        {
          label: "XP Count",
          data: datos,
          borderColor: "rgb(75, 192, 192)",
          fill: false,
          tension: 0.1,
        },
      ],
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
    },
  });
}

function xpBreakdownChart() {
  let STUDY = 0;
  let TASK = 0;
  let STREAK = 0;
  let QUIZ = 0;
  console.log(xp_breakdown);
  xp_breakdown.forEach((xp) => {
    if (xp.category == "STUDY") {
      STUDY += Number(xp.total_xp);
    } else if (xp.category == "TASK") {
      TASK += Number(xp.total_xp);
    } else if (xp.category == "STREAK") {
      STREAK += Number(xp.total_xp);
    } else {
      QUIZ += Number(xp.total_xp);
    }
  });

  const ctx = document.getElementById("xpBreakdownChart");

  const data = {
    labels: ["Study", "Task", "Streak", "Quiz"],
    datasets: [
      {
        label: "Total Number",
        data: [STUDY, TASK, STREAK, QUIZ],
        backgroundColor: [
          "rgba(59, 130, 246, 0.8)",
          "rgba(249, 115, 22, 0.8)",
          "rgba(34, 197, 94, 0.8)",
          "rgba(139, 92, 246, 0.8)",
        ],
        hoverOffset: 4,
      },
    ],
  };
  const config = {
    type: "doughnut",
    data: data,
    options: {
      responsive: true,
      maintainAspectRatio: false,
    },
  };

  new Chart(ctx, config);
}
