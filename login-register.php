<!doctype html>
<html>

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <link rel="stylesheet" href="./assets/css/login-register.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>

<body class="font-[Inter]">
    <div class="wrapper flex relative overflow-hidden bg-[#0F172A]">
        <div class="w-1/2 min-h-screen flex justify-center items-center overflow-hidden">
            <!-- Registration section -->
            <div
                class="reg-wrapper form-wrapper min-h-3/4 w-full max-w-md rounded-lg relative flex flex-col justify-start items-center p-6 md:p-8 text-white">
                <div class="headings mb-8 text-center">
                    <img src="./assets/images/-ssc_logo_1-d20a0c9e86d0b38d5dd3807b924e1bbb.png"
                        class="w-24 h-auto mx-auto">
                    <div>
                        <h1 class="text-3xl font-bold">Learnify</h1>
                    </div>
                    <div>
                        <p>Join Learnify today!</p>
                    </div>
                </div>
                <form action="./actions/registration-process.php" method="POST" class="w-full space-y-4">
                    <div class="input-box w-full">
                        <p class="text-sm font-bold mb-1">Full Name</p>
                        <label class="flex items-center border border-slate-600 rounded-md px-3 py-2 cursor-text">
                            <i class="fa fa-user mr-2 text-gray-500 text-lg fa-fw" aria-hidden="true"></i>
                            <input type="text" id="fullname" name="fullname" required class="w-full py-1 outline-none"
                                placeholder="Last Name, First Name, M.I.">
                        </label>
                    </div>
                    <div class="input-box w-full">
                        <p class="text-sm font-bold mb-1">Email Address</p>
                        <label class="flex items-center border border-slate-600 rounded-md px-3 py-2 cursor-text">
                            <i class="fa fa-envelope mr-2 text-gray-500 text-lg fa-fw" aria-hidden="true"></i>
                            <input type="email" id="email" name="email" required class="w-full py-1 outline-none"
                                placeholder="Enter your email">
                        </label>
                    </div>
                    <div class="input-box w-full">
                        <div class="flex justify-between">
                            <p class="text-sm font-bold">Password</p>
                        </div>
                        <label class="flex items-center border border-slate-600 rounded-md px-3 py-2 cursor-text">
                            <i class="fa fa-lock mr-2 text-gray-500 text-lg fa-fw" aria-hidden="true"></i>
                            <input type="password" id="password" name="password" required class="w-full py-1 outline-none"
                                placeholder="Enter your password">
                            <i class="fa fa-eye-slash text-gray-500" aria-hidden="true"></i>
                        </label>
                    </div>
                    <div class="input-box w-full">
                        <div class="flex justify-between">
                            <p class="text-sm font-bold">Confirm Password</p>
                        </div>
                        <label class="flex items-center border border-slate-600 rounded-md px-3 py-2 cursor-text">
                            <i class="fa fa-lock mr-2 text-gray-500 text-lg fa-fw" aria-hidden="true"></i>
                            <input type="password" id="cpassword" name="cpassword" required class="w-full py-1 outline-none"
                                placeholder="Enter your password">
                            <i class="fa fa-eye-slash text-gray-500" aria-hidden="true"></i>
                        </label>
                    </div>
                    <button
                        class="w-full mt-6 bg-blue-600 text-white py-2.5 rounded-md font-semibold shadow-sm hover:bg-blue-700 active:scale-[0.98] transition"
                        type="submit">
                        Register
                    </button>
                </form>
                <?php if (isset($_GET["error"])): ?>
                    <p class="text-sm text-red-600 mt-1">
                        <?php echo $_GET["error"] ?>
                    </p>
                <?php endif; ?>
            </div>
        </div>
        <!-- Login section -->
        <div class="w-1/2 min-h-screen flex justify-center items-center overflow-hidden">
            <div
                class="login-wrapper form-wrapper min-h-3/4 w-full max-w-md rounded-lg relative flex flex-col justify-start items-center p-6 md:p-8 text-white">
                <div class="headings mb-8 text-center">
                    <img src="./assets/images/-ssc_logo_1-d20a0c9e86d0b38d5dd3807b924e1bbb.png"
                        class="w-24 h-auto mx-auto">
                    <div>
                        <h1 class="text-3xl font-bold">LEARNIFY</h1>
                    </div>
                    <div>
                        <p>Log in to continue</p>
                    </div>
                </div>
                <form action="./actions/login-process.php" method="POST" class="w-full space-y-4">
                    <div class="input-box w-full">
                        <p class="text-sm font-bold mb-1">Email Address</p>
                        <label class="flex items-center border border-slate-600 rounded-md px-3 py-2 cursor-text">
                            <i class="fa fa-envelope mr-2 text-gray-500 text-lg fa-fw" aria-hidden="true"></i>
                            <input type="email" id="email" name="email" required class="w-full py-1 outline-none"
                                placeholder="Enter your email">
                        </label>
                    </div>
                    <div class="input-box w-full">
                        <div class="flex justify-between">
                            <p class="text-sm font-bold">Password</p>
                            <p class="text-sm">Forgot password?</p>
                        </div>
                        <label class="flex items-center border border-slate-600 rounded-md px-3 py-2 cursor-text">
                            <i class="fa fa-lock mr-2 text-gray-500 text-lg fa-fw" aria-hidden="true"></i>
                            <input type="password" name="password" required class="w-full py-1 outline-none"
                                placeholder="Enter your password">
                            <i class="fa fa-eye-slash text-gray-500" aria-hidden="true"></i>
                        </label>
                    </div>
                    <button
                        class="w-full mt-6 bg-blue-600 text-white py-2.5 rounded-md font-semibold shadow-sm hover:bg-blue-700 active:scale-[0.98] transition"
                        type="submit">
                        Login
                    </button>
                </form>
                <?php if (isset($_GET["error"])): ?>
                    <p class="text-sm text-red-600 mt-1">
                        <?php echo $_GET["error"] ?>
                    </p>
                <?php endif; ?>

            </div>
        </div>

        <div id="overlay" class="min-h-screen w-full absolute flex justify-center items-center z-10">
            <div class="w-1/2 text-center z-20">
                <div class="text-center">
                    <h1
                        class="text-7xl font-extrabold tracking-wide bg-gradient-to-r from-cyan-200 via-sky-400 to-blue-500 bg-clip-text text-transparent">
                        Welcome Back!
                    </h1>
                    <p class="text-white mt-3 text-2xl">
                        Already have an account?
                    </p>
                    <button
                        class="clickme px-6 py-2 border border-white text-white rounded-md font-semibold transition hover:bg-blue-500 hover:text-white mt-5">
                        Login here
                    </button>
                </div>
            </div>
            <div class="w-1/2 text-center z-20">
                <div class="text-center">
                    <h1
                        class="text-7xl font-extrabold tracking-wide bg-gradient-to-r from-cyan-200 via-sky-400 to-blue-500 bg-clip-text text-transparent">
                        Hello, Welcome!
                    </h1>
                    <p class="text-white mt-3 text-2xl">
                        Don't have an account yet?
                    </p>
                    <button
                        class="clickme px-6 py-2 border border-white text-white rounded-md font-semibold transition hover:bg-blue-500 hover:text-white mt-5">
                        Register here
                    </button>
                </div>
            </div>
        </div>
    </div>
    <script src="./assets/js/login-register.js"></script>
</body>

</html>