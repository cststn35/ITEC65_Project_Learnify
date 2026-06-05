<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Learnify Setup</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        body {
            overflow: hidden;
        }

        .bg-learnify {
            position: absolute;
            inset: 0;
            background:
                radial-gradient(circle at 20% 20%, rgba(30, 58, 138, 0.6), transparent 45%),
                radial-gradient(circle at 80% 30%, rgba(59, 130, 246, 0.4), transparent 50%),
                radial-gradient(circle at 40% 80%, rgba(15, 23, 42, 0.9), transparent 55%),
                linear-gradient(120deg, #0F172A, #0B1220);
            animation: drift 18s ease-in-out infinite alternate;
            filter: blur(70px);
            transform: scale(1.3);
        }

        @keyframes drift {
            0% {
                transform: scale(1.3) translate(0px, 0px);
            }

            100% {
                transform: scale(1.35) translate(40px, -25px);
            }
        }

        .shake {
            animation: shake 0.25s ease-in-out;
        }

        @keyframes shake {

            0%,
            100% {
                transform: translateX(0);
            }

            25% {
                transform: translateX(-5px);
            }

            50% {
                transform: translateX(5px);
            }
        }
    </style>
</head>

<body class="bg-[#0F172A] text-white font-[Inter]">

    <!-- background -->
    <div class="fixed inset-0 -z-10 overflow-hidden">
        <div class="bg-learnify"></div>
    </div>

    <!-- center wrapper -->
    <div class="fixed inset-0 flex items-center justify-center p-4">
        <div class="w-full max-w-xl bg-[#0F172A]/70 backdrop-blur-xl border border-slate-700 rounded-xl shadow-2xl p-6">
            <div class="flex justify-center">
                <img src="../assets/images/-ssc_logo_1-d20a0c9e86d0b38d5dd3807b924e1bbb.png" alt="Logo" width="100"
                    class="p-0">
            </div>

            <!-- header -->
            <div class="mb-6 text-center">
                <h2 class="text-2xl font-bold">Welcome to Learnify!</h2>
                <p class="text-slate-400 text-sm">Setup your profile before starting learning</p>

                <div class="flex gap-2 mt-4">
                    <div id="p1" class="h-1 flex-1 bg-blue-600 rounded"></div>
                    <div id="p2" class="h-1 flex-1 bg-slate-700 rounded"></div>
                    <div id="p3" class="h-1 flex-1 bg-slate-700 rounded"></div>
                </div>
            </div>

            <!-- slider -->
            <div class="overflow-hidden">
                <div id="slider" class="flex transition-transform duration-500 ease-in-out">

                    <!-- step 1 -->
                    <div class="min-w-full px-1">
                        <h3 class="text-lg font-semibold mb-4">Profile Picture</h3>

                        <div class="flex flex-col items-center gap-4">

                            <img id="preview" src="../assets/images/default-profile.png"
                                class="w-24 h-24 rounded-full border border-slate-600 object-cover">

                            <input type="file" id="profilePic"
                                class="text-sm file:px-3 file:py-2 file:bg-slate-800 file:text-slate-200 file:border file:border-slate-600 file:rounded-xl">

                            <button onclick="nextStep()"
                                class="px-5 py-2 bg-blue-600 hover:bg-blue-700 rounded-md font-semibold transition">
                                Next
                            </button>
                        </div>
                    </div>

                    <!-- step 2 -->
                    <div class="min-w-full px-1">
                        <h3 class="text-lg font-semibold mb-4">Daily Goal</h3>

                        <input id="daily_goal" type="number"
                            class="w-full px-3 py-2 bg-[#0B1220] border border-slate-700 rounded-md focus:border-blue-500 outline-none"
                            placeholder="Minutes per day">

                        <div class="flex justify-between mt-6">
                            <button onclick="prevStep()"
                                class="px-4 py-2 border border-slate-600 rounded-md hover:bg-slate-800">
                                Back
                            </button>

                            <button onclick="nextStep()"
                                class="px-5 py-2 bg-blue-600 hover:bg-blue-700 rounded-md font-semibold">
                                Next
                            </button>
                        </div>
                    </div>

                    <!-- step 3 -->
                    <div class="min-w-full px-1">
                        <h3 class="text-lg font-semibold mb-4">Learning Period</h3>

                        <div class="space-y-3">

                            <select id="systemType"
                                class="w-full px-3 py-2 bg-[#0B1220] border border-slate-700 rounded-md">
                                <option value="">Select system type</option>
                                <option>Semester-based</option>
                                <option>Term-based</option>
                                <option>Quarter-based</option>
                                <option>Module-based</option>
                            </select>

                            <input class="w-full px-3 py-2 bg-[#0B1220] border border-slate-700 rounded-md"
                                placeholder="Current period" id="period_name">
                            <!-- start Date -->
                            <div>
                                <label class="text-sm text-slate-300">Start Date</label>
                                <input type="date"
                                    class="w-full mt-1 px-3 py-2 bg-[#0B1220] border border-slate-700 rounded-md"
                                    id="start_date" min="<?= date('Y-m-d') ?>">
                            </div>

                            <!-- end Date -->
                            <div>
                                <label class="text-sm text-slate-300">End Date</label>
                                <input type="date"
                                    class="w-full mt-1 px-3 py-2 bg-[#0B1220] border border-slate-700 rounded-md"
                                    id="end_date" min="<?= date('Y-m-d') ?>">
                            </div>

                            <input class="w-full px-3 py-2 bg-[#0B1220] border border-slate-700 rounded-md"
                                placeholder="Year Level / School Year" id="year_level">
                        </div>

                        <div class="flex justify-between mt-6">
                            <button onclick="prevStep()"
                                class="px-4 py-2 border border-slate-600 rounded-md hover:bg-slate-800">
                                Back
                            </button>

                            <button class="px-5 py-2 bg-emerald-600 hover:bg-emerald-500 rounded-md font-semibold"
                                onclick="registerUser()">
                                Finish Setup
                            </button>
                        </div>
                    </div>

                </div>
            </div>

        </div>
    </div>

    <script>
        const BASE_URL = window.location.pathname.split("/")[1];
        const userID = <?= json_encode($_SESSION['user_id']) ?>;
        console.log(userID);
        console.log(<?= json_encode($_SESSION['semester_id']) ?>);

        let step = 1;

        function updateSlider() {
            document.getElementById("slider")
                .style.transform = `translateX(-${(step - 1) * 100}%)`;

            document.getElementById("p1").className =
                "h-1 flex-1 rounded " + (step >= 1 ? "bg-blue-600" : "bg-slate-700");

            document.getElementById("p2").className =
                "h-1 flex-1 rounded " + (step >= 2 ? "bg-blue-600" : "bg-slate-700");

            document.getElementById("p3").className =
                "h-1 flex-1 rounded " + (step >= 3 ? "bg-blue-600" : "bg-slate-700");
        }

        function validateStep() {
            let valid = true;

            if (step === 2) {
                const goal = document.getElementById("daily_goal");
                if (!goal.value || goal.value <= 0) {
                    shake(goal);
                    valid = false;
                }
            }

            if (step === 3) {
                const sys = document.getElementById("systemType");
                const start_date = document.getElementById('start_date');
                const end_date = document.getElementById('end_date');
                const period_name = document.getElementById('period_name');
                const year_level = document.getElementById('year_level');

                if (!sys.value) { shake(sys); valid = false; }
                if (!period_name.value) { shake(period_name); valid = false; }
                if (!start_date.value) { shake(start_date); valid = false; }
                if (!end_date.value) { shake(end_date); valid = false; }
                if (!year_level.value.trim()) { shake(year_level); valid = false; }
            }

            return valid;
        }

        function shake(el) {
            el.classList.add("shake", "border-red-500");
            setTimeout(() => el.classList.remove("shake", "border-red-500"), 250);
        }

        function nextStep() {
            if (!validateStep()) return;
            if (step < 3) step++;
            updateSlider();
        }

        function prevStep() {
            if (step > 1) step--;
            updateSlider();
        }

        /* profile preview */
        document.getElementById("profilePic")?.addEventListener("change", (e) => {
            const file = e.target.files?.[0];
            if (!file) return;

            const reader = new FileReader();
            reader.onload = () => {
                document.getElementById("preview").src = reader.result;
            };
            reader.readAsDataURL(file);
        });

        updateSlider();



        async function registerUser() {
            let valid = validateStep();
            if (!valid) return;
            const formData = new FormData();
            const file = document.getElementById('profilePic');
            const daily_goal = document.getElementById('daily_goal').value;
            const start_date = document.getElementById('start_date').value;
            const end_date = document.getElementById('end_date').value;
            const period_name = document.getElementById('period_name').value;
            const year_level = document.getElementById('year_level').value;

            if (start_date >= end_date) {
                Swal.fire({
                    icon: "error",
                    title: "Failed",
                    text: "Start date must not be equal or greater than end date",
                });
                return;
            }

            formData.append('profile', file.files[0]);
            formData.append('daily_goal', daily_goal);
            formData.append('start_date', start_date);
            formData.append('end_date', end_date);
            formData.append('period_name', period_name);
            formData.append('year_level', year_level);
            formData.append('userID', <?= json_encode($_SESSION['user_id']) ?>);

            try {
                const response = await fetch("../actions/save-setup.php", {
                    method: "POST",
                    body: formData,
                });

                const data = await response.json();
                if (data.success) {
                    window.location.href = "../pages/dashboard.php";
                }
            } catch (error) {
                Swal.fire({
                    icon: "error",
                    title: "Failed!",
                    text: "There was an error saving your profile",
                });
            }

        }
    </script>

</body>

</html>