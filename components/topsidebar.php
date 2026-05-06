<!-- sidebar -->
<div id="sidebar"
    class="h-screen fixed md:relative md:row-start-1 md:row-span-2 bg-slate-900 z-20 transform -translate-x-full md:translate-x-0 transition-transform duration-300 ease-in-out">
    <div class="h-[60px] px-3 md:p-4 flex items-center">
        <span>
            <img src="../assets/images/-ssc_logo_1-d20a0c9e86d0b38d5dd3807b924e1bbb.png" alt="Logo" width="50"
                class="p-0">
        </span>
        <span>
            <h1 class="font-bold text-white">Smart Study App</h1>
            <p class="text-sm text-white">Learn Smarter</p>
        </span>
        <span id="chevron-right" class="md:hidden flex items-center">
            <i class='bx bx-chevron-left text-4xl text-white ml-5'></i>
        </span>
    </div>
    <hr class="border-gray-600" />
    <ul class="mt-4">
        <li class="w-[90%] mx-auto my-3">
            <a href="../pages/dashboard.php"
                class="flex items-center text-white rounded-xl hover:bg-white hover:text-black transition-colors">
                <span class="w-[50px] h-[50px] flex items-center justify-center">
                    <i class='bx bx-home text-2xl'></i>
                </span>
                <span class="ml-2 font-bold">
                    Dashboard
                </span>
            </a>
        </li>
        <li class="w-[90%] mx-auto my-3">
            <a href="#"
                class="flex items-center text-white rounded-xl hover:bg-white hover:text-black transition-colors">
                <span class="w-[50px] h-[50px] flex items-center justify-center">
                    <i class='bx bx-timer text-2xl'></i>
                </span>
                <span class="ml-2 font-bold">
                    Study Sessions
                </span>
            </a>
        </li>
        <li class="w-[90%] mx-auto my-3">
            <a href="../pages/tasks.php"
                class="flex items-center text-white rounded-xl hover:bg-white hover:text-black transition-colors">
                <span class="w-[50px] h-[50px] flex items-center justify-center">
                    <i class='bx bx-check-square text-2xl'></i>
                </span>
                <span class="ml-2 font-bold">
                    Tasks
                </span>
            </a>
        </li>
        <li class="w-[90%] mx-auto my-3">
            <a href="../pages/courses.php"
                class="flex items-center text-white rounded-xl hover:bg-white hover:text-black transition-colors">
                <span class="w-[50px] h-[50px] flex items-center justify-center">
                    <i class='bx bx-book-open text-2xl'></i>
                </span>
                <span class="ml-2 font-bold">
                    Courses
                </span>
            </a>
        </li>
        <li class="w-[90%] mx-auto my-3">
            <a href="#"
                class="flex items-center text-white rounded-xl hover:bg-white hover:text-black transition-colors">
                <span class="w-[50px] h-[50px] flex items-center justify-center">
                    <i class='bx bx-bar-chart-alt-2 text-2xl'></i>
                </span>
                <span class="ml-2 font-bold">
                    Analytics
                </span>
            </a>
        </li>
        <li class="w-[90%] mx-auto my-3">
            <a href="#"
                class="flex items-center text-white rounded-xl hover:bg-white hover:text-black transition-colors">
                <span class="w-[50px] h-[50px] flex items-center justify-center">
                    <i class='bx bx-star text-2xl'></i>
                </span>
                <span class="ml-2 font-bold">
                    Smart Coach
                </span>
            </a>
        </li>
        <li class="w-[90%] mx-auto my-3">
            <a href="#"
                class="flex items-center text-white rounded-xl hover:bg-white hover:text-black transition-colors">
                <span class="w-[50px] h-[50px] flex items-center justify-center">
                    <i class='bx bx-badge text-2xl'></i>
                </span>
                <span class="ml-2 font-bold">
                    Badges
                </span>
            </a>
        </li>
    </ul>
</div>
<!-- topbar -->
<div class="md:col-start-2 bg-slate-700 flex justify-between items-center py-2 px-3 md:py-4 md:px-6">
    <span class="text-xl md:text-2xl font-bold text-white">
        <?= $pageTitle ?>
    </span>
    <span class="flex items-center gap-1 md:gap-4">
        <!-- Streak -->
        <div
            class="hidden md:block flex items-center gap-2 px-3 py-1.5 rounded-lg bg-amber-500/20 border border-amber-400/30 backdrop-blur-sm">
            <i class='bx bxs-hot text-amber-400 text-sm'></i>
            <span class="text-amber-400 text-sm">7</span>
        </div>
        <!-- Badge -->
        <div
            class="hidden md:block flex items-center gap-2 px-3 py-1.5 rounded-lg bg-amber-500/20 border border-amber-400/30 backdrop-blur-sm">
            <i class="fa-solid fa-bolt-lightning text-amber-400 text-sm"></i>
            <span class=" text-amber-400 text-sm">1250
        </div>
        <!-- Notification -->
        <button class="relative p-2 rounded-lg hover:bg-slate-600 transition-colors text-slate-200 flex items-center">
            <i class='bx bx-bell text-2xl'></i>
            <span class="absolute top-1 right-1 w-2 h-2 bg-red-500 rounded-full ring-2 ring-slate-700"></span>
        </button>
        <button class="flex items-center gap-2 px-3 py-2 rounded-lg hover:bg-slate-600 transition-colors">
            <div
                class="w-8 h-8 rounded-full bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center ring-2 ring-slate-500">
            </div>
            <i class='bx bx-chevron-down text-2xl text-slate-300'></i>
        </button>
        <span class="md:hidden cursor-pointer">
            <i id="hamburger" class="bx bx-menu text-white text-2xl"></i>
        </span>
    </span>
</div>
<script>
    const chevron = document.getElementById("chevron-right");
    const hamburger = document.getElementById("hamburger");
    const sidebar = document.getElementById("sidebar");
    chevron.addEventListener('click', () => {
        sidebar.classList.toggle("translate-x-0");
    });
    hamburger.addEventListener('click', () => {
        sidebar.classList.toggle("translate-x-0");
    });
</script>