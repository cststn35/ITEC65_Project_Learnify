<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Learnify Landing Page</title>
    <script src="https://cdn.tailwindcss.com"></script>

    <style>
        html {
            scroll-behavior: smooth;
        }
    </style>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&display=swap"
        rel="stylesheet">
</head>

<body class="bg-slate-100 text-slate-800 font-[Inter]">
    <header class="bg-slate-800 text-white sticky top-0 z-50 shadow-md">
        <div class="max-w-7xl mx-auto flex justify-between items-center px-6 py-4">

            <div class="text-xl font-bold tracking-wide">Learnify</div>

            <nav class="hidden md:flex gap-6 text-sm text-slate-300">
                <a href="#features" class="hover:text-white">Features</a>
                <a href="#how" class="hover:text-white">How it Works</a>
                <a href="#gamification" class="hover:text-white">Gamification</a>
                <a href="#faq" class="hover:text-white">FAQ</a>
            </nav>

            <div class="flex gap-3">
                <a href="login-register.php">
                    <button class="px-4 py-2 text-sm bg-blue-600 hover:bg-blue-700 rounded-lg">
                        Get Started
                    </button>
                </a>
            </div>

        </div>
    </header>
    <section class="bg-slate-900 text-white">
        <div class="max-w-7xl mx-auto px-6 py-20 grid md:grid-cols-2 gap-10 items-center">

            <div>
                <h1 class="text-4xl md:text-5xl font-bold leading-tight">
                    Study Smarter. Stay Consistent. Track Your Growth with
                    <span class="text-blue-400">Learnify</span>
                </h1>

                <p class="mt-5 text-slate-300 text-lg">
                    Learnify is a gamified academic productivity system that helps students manage subjects,
                    create study sessions, take quizzes, and track performance using analytics, XP, streaks,
                    and rule-based recommendations.
                </p>

                <div class="mt-8 flex gap-4">
                    <a href="login-register.php">
                        <button class="bg-blue-600 hover:bg-blue-700 px-6 py-3 rounded-xl font-medium">
                            Get Started
                        </button>
                    </a>
                    <a href="#features" class="border border-slate-600 hover:bg-slate-800 px-6 py-3 rounded-xl">
                        Explore Features
                    </a>
                </div>

                <p class="mt-4 text-sm text-slate-400">
                    • Designed for all students
                </p>
            </div>
            <div class="bg-slate-800 p-5 rounded-xl shadow-xl border border-slate-700">

                <h3 class="text-sm text-slate-300 mb-4">Live Dashboard Preview</h3>

                <div class="grid grid-cols-2 gap-4">

                    <div class="bg-slate-900 p-4 rounded-xl">
                        <p class="text-xs text-slate-400">Current Streak</p>
                        <p class="text-lg font-bold text-blue-400">7 Days 🔥</p>
                    </div>

                    <div class="bg-slate-900 p-4 rounded-xl">
                        <p class="text-xs text-slate-400">Total XP</p>
                        <p class="text-lg font-bold text-blue-400">1,240 XP</p>
                    </div>

                    <div class="bg-slate-900 p-4 rounded-xl col-span-2">
                        <p class="text-xs text-slate-400">Weekly Study Progress</p>

                        <div class="flex items-end gap-1 h-20 mt-3">
                            <div class="w-3 bg-blue-500 h-6 rounded"></div>
                            <div class="w-3 bg-blue-500 h-10 rounded"></div>
                            <div class="w-3 bg-blue-500 h-14 rounded"></div>
                            <div class="w-3 bg-blue-500 h-8 rounded"></div>
                            <div class="w-3 bg-blue-500 h-16 rounded"></div>
                            <div class="w-3 bg-blue-500 h-12 rounded"></div>
                            <div class="w-3 bg-blue-500 h-20 rounded"></div>
                        </div>

                    </div>

                </div>
            </div>

        </div>
    </section>
    <section id="features" class="py-20 max-w-7xl mx-auto px-6">

        <h2 class="text-3xl font-bold text-center mb-12">
            Core Features
        </h2>

        <div class="grid md:grid-cols-3 gap-6">

            <div class="bg-white p-6 rounded-xl shadow-sm hover:shadow-md transition">
                <h3 class="font-semibold">📚 Study Sessions</h3>
                <p class="text-sm text-slate-600 mt-2">
                    Create monitored study sessions and upload learning materials.
                </p>
            </div>

            <div class="bg-white p-6 rounded-xl shadow-sm hover:shadow-md transition">
                <h3 class="font-semibold">📝 Auto-Generated Quizzes</h3>
                <p class="text-sm text-slate-600 mt-2">
                    Quizzes are automatically generated from uploaded materials to reinforce learning.
                </p>
            </div>

            <div class="bg-white p-6 rounded-xl shadow-sm hover:shadow-md transition">
                <h3 class="font-semibold">📊 Analytics Dashboard</h3>
                <p class="text-sm text-slate-600 mt-2">
                    Track study time, productivity, quiz performance, and academic trends.
                </p>
            </div>

            <div class="bg-white p-6 rounded-xl shadow-sm hover:shadow-md transition">
                <h3 class="font-semibold">🤖 Smart Coach</h3>
                <p class="text-sm text-slate-600 mt-2">
                    Rule-based recommendations based on user study behavior and performance.
                </p>
            </div>

            <div class="bg-white p-6 rounded-xl shadow-sm hover:shadow-md transition">
                <h3 class="font-semibold">🔥 XP System</h3>
                <p class="text-sm text-slate-600 mt-2">
                    Earn XP from study sessions, streaks, quizzes, and task completion.
                </p>
            </div>

            <div class="bg-white p-6 rounded-xl shadow-sm hover:shadow-md transition">
                <h3 class="font-semibold">🎯 Streak Tracking</h3>
                <p class="text-sm text-slate-600 mt-2">
                    Maintain daily consistency and build strong study habits.
                </p>
            </div>

        </div>
    </section>
    <section id="how" class="bg-slate-900 text-white py-20">

        <div class="max-w-7xl mx-auto px-6">

            <h2 class="text-3xl font-bold text-center mb-12">How Learnify Works</h2>

            <div class="grid md:grid-cols-4 gap-6 text-center">

                <div class="bg-slate-800 p-6 rounded-xl">
                    <p class="text-blue-400 text-2xl font-bold">1</p>
                    <p class="mt-2">Add Subjects and Create Tasks</p>
                </div>

                <div class="bg-slate-800 p-6 rounded-xl">
                    <p class="text-blue-400 text-2xl font-bold">2</p>
                    <p class="mt-2">Create Study Sessions</p>
                </div>

                <div class="bg-slate-800 p-6 rounded-xl">
                    <p class="text-blue-400 text-2xl font-bold">3</p>
                    <p class="mt-2">Take Quizzes</p>
                </div>

                <div class="bg-slate-800 p-6 rounded-xl">
                    <p class="text-blue-400 text-2xl font-bold">4</p>
                    <p class="mt-2">Track Progress</p>
                </div>

            </div>

        </div>
    </section>
    <section id="gamification" class="py-20 bg-slate-100">

        <div class="max-w-7xl mx-auto px-6">

            <h2 class="text-3xl font-bold text-center mb-10">
                Gamification System
            </h2>

            <div class="grid md:grid-cols-2 gap-6">

                <div class="bg-white p-6 rounded-xl shadow-sm">
                    <h3 class="font-semibold">🔥 XP System</h3>
                    <p class="text-sm text-slate-600 mt-2">
                        Students earn XP from completing study sessions, quizzes, and tasks.
                    </p>
                </div>

                <div class="bg-white p-6 rounded-xl shadow-sm">
                    <h3 class="font-semibold">📅 Streak System</h3>
                    <p class="text-sm text-slate-600 mt-2">
                        Encourages consistency by tracking daily study activity.
                    </p>
                </div>

            </div>

        </div>
    </section>
    <section class="py-20">

        <div class="max-w-7xl mx-auto px-6">

            <h2 class="text-3xl font-bold text-center mb-10">
                Learning Analytics
            </h2>

            <div class="grid md:grid-cols-3 gap-6">

                <div class="bg-white p-6 rounded-xl shadow-sm">
                    <h3 class="font-semibold">Study Behavior</h3>
                    <p class="text-sm text-slate-600 mt-2">Analyze study patterns over time.</p>
                </div>

                <div class="bg-white p-6 rounded-xl shadow-sm">
                    <h3 class="font-semibold">Performance Tracking</h3>
                    <p class="text-sm text-slate-600 mt-2">Monitor quiz and task results.</p>
                </div>

                <div class="bg-white p-6 rounded-xl shadow-sm">
                    <h3 class="font-semibold">Productivity Trends</h3>
                    <p class="text-sm text-slate-600 mt-2">Identify peak learning hours.</p>
                </div>

            </div>

            <h2 class="text-3xl text-center mt-10">
                and more...
            </h2>

        </div>
    </section>
    <section class="bg-slate-900 text-white py-20">

        <div class="max-w-4xl mx-auto text-center px-6">

            <h2 class="text-3xl font-bold">About Learnify</h2>

            <p class="mt-5 text-slate-300">
                Learnify is a web-based project designed to help students develop better study habits
                through structured learning, gamification, and performance tracking.
                It integrates productivity tools, analytics, and motivational systems into one platform.
            </p>

        </div>

    </section>
    <section id="faq" class="py-20 bg-slate-100">

        <div class="max-w-4xl mx-auto px-6">

            <h2 class="text-3xl font-bold text-center mb-10">FAQ</h2>

            <div class="space-y-4">

                <div class="bg-white p-5 rounded-xl shadow-sm">
                    <h3 class="font-semibold">Is Learnify free?</h3>
                    <p class="text-sm text-slate-600 mt-2">Yes, it is a free academic system project.</p>
                </div>

                <div class="bg-white p-5 rounded-xl shadow-sm">
                    <h3 class="font-semibold">Who is it for?</h3>
                    <p class="text-sm text-slate-600 mt-2">All students at any academic level.</p>
                </div>

                <div class="bg-white p-5 rounded-xl shadow-sm">
                    <h3 class="font-semibold">Does it use AI?</h3>
                    <p class="text-sm text-slate-600 mt-2">No. Smart Coach uses rule-based logic.</p>
                </div>

            </div>

        </div>

    </section>
    <section class="bg-blue-600 text-white text-center py-16">

        <h2 class="text-3xl font-bold">Start Improving Your Study Habits Today</h2>
        <p class="mt-3 text-blue-100">Track progress, stay consistent, and learn better with Learnify.</p>

        <a href="login-register.php">
            <button class="mt-6 bg-white text-blue-600 px-6 py-3 rounded-xl font-semibold hover:bg-slate-100">
                Get Started
            </button>
        </a>
    </section>
    <footer class="bg-slate-800 text-slate-300 py-10">

        <div class="max-w-7xl mx-auto px-6 grid md:grid-cols-3 gap-6">

            <div>
                <h3 class="text-white font-bold">Learnify</h3>
                <p class="text-sm mt-2">Academic productivity & learning system</p>
            </div>

            <div>
                <h3 class="text-white font-bold">Navigation</h3>
                <p class="text-sm mt-2">Features • Dashboard • FAQ</p>
            </div>

            <div>
                <h3 class="text-white font-bold">Project</h3>
                <p class="text-sm mt-2">Web-Based Productivity System • 2026</p>
            </div>

        </div>

    </footer>

</body>

</html>