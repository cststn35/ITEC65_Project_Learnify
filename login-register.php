<?php
session_start();
$overlayActive = $_SESSION['reg'] ?? false;
$firstName = $_SESSION["registration"]["firstName"] ?? "";
$lastName = $_SESSION["registration"]["lastName"] ?? "";
$email = $_SESSION["registration"]["email"] ?? "";
$regerror = $_SESSION["registration"]["error"] ?? "";
$logerror = $_SESSION["login"]["error"] ?? "";
$successfulreg = $_SESSION["successful_registration"] ?? "";
unset($_SESSION["reg"], $_SESSION["registration"]["firstName"], $_SESSION["registration"]["lastName"], $_SESSION["registration"]["email"], $_SESSION["registration"]["error"], $_SESSION["login"]["error"], $_SESSION["successful_registration"]); //so that these variables won't persist when refreshed
?>
<!doctype html>
<html>

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="./assets/css/login-register.css">
    <link rel="stylesheet" href="src/output.css">
    <link rel="stylesheet" href="node_modules/@fortawesome/fontawesome-free/css/all.min.css">
    <script src="node_modules/sweetalert2/dist/sweetalert2.all.min.js"></script>
</head>

<body class="font-[Inter]">
    <div class="wrapper flex relative overflow-hidden bg-[#0F172A]">
        <div class="w-1/2 min-h-screen flex justify-center items-center overflow-hidden">
            <!-- Registration section -->
            <div
                class="reg-wrapper form-wrapper min-h-3/4 w-full max-w-md rounded-lg relative flex flex-col justify-start items-center p-6 md:p-8 text-white <?= $overlayActive ? 'active' : '' ?>">
                <div class="headings mb-8 text-center">
                    <img src="./assets/images/-ssc_logo_1-d20a0c9e86d0b38d5dd3807b924e1bbb.png"
                        class="w-24 h-auto mx-auto">
                    <div>
                        <h1 class="text-3xl font-bold">LEARNIFY</h1>
                    </div>
                    <div>
                        <p>"Build Knowledge, One Streak at a Time"</p>
                    </div>
                </div>
                <form action="./actions/registration-process.php" method="POST" class="w-full space-y-4">
                    <div class="input-box w-full">
                        <p class="text-sm font-bold mb-1">First Name</p>
                        <label class="flex items-center border border-slate-600 rounded-md px-3 py-2 cursor-text">
                            <i class="fa fa-user mr-2 text-gray-500 text-lg fa-fw" aria-hidden="true"></i>
                            <input type="text" id="firstname" name="firstname" required class="w-full py-1 outline-none"
                                placeholder="Enter your first name" value="<?= $firstName ? $firstName : '' ?>">
                        </label>
                    </div>
                    <div class="input-box w-full">
                        <p class="text-sm font-bold mb-1">Last Name</p>
                        <label class="flex items-center border border-slate-600 rounded-md px-3 py-2 cursor-text">
                            <i class="fa fa-user mr-2 text-gray-500 text-lg fa-fw" aria-hidden="true"></i>
                            <input type="text" id="lastname" name="lastname" required class="w-full py-1 outline-none"
                                placeholder="Enter your last name" value="<?= $lastName ? $lastName : '' ?>">
                        </label>
                    </div>
                    <div class=" input-box w-full">
                        <p class="text-sm font-bold mb-1">Email Address</p>
                        <label class="flex items-center border border-slate-600 rounded-md px-3 py-2 cursor-text">
                            <i class="fa fa-envelope mr-2 text-gray-500 text-lg fa-fw" aria-hidden="true"></i>
                            <input type="email" id="email" name="email" required class="w-full py-1 outline-none"
                                placeholder="Enter your email" value=<?= $email ? $email : '' ?>>
                        </label>
                    </div>
                    <div class="input-box w-full">
                        <div class="flex justify-between">
                            <p class="text-sm font-bold">Password</p>
                        </div>
                        <label class="flex items-center border border-slate-600 rounded-md px-3 py-2 cursor-text">
                            <i class="fa fa-lock mr-2 text-gray-500 text-lg fa-fw" aria-hidden="true"></i>
                            <input type="password" id="regpassword1" name="password" required
                                class="w-full py-1 outline-none" placeholder="Enter your password">
                            <i class="fa fa-eye-slash text-gray-500 cursor-pointer" id="passToggle2"
                                aria-hidden="true"></i>
                        </label>
                    </div>
                    <div class="input-box w-full">
                        <div class="flex justify-between">
                            <p class="text-sm font-bold">Confirm Password</p>
                        </div>
                        <label class="flex items-center border border-slate-600 rounded-md px-3 py-2 cursor-text">
                            <i class="fa fa-lock mr-2 text-gray-500 text-lg fa-fw" aria-hidden="true"></i>
                            <input type="password" id="regpassword2" name="cpassword" required
                                class="w-full py-1 outline-none" placeholder="Enter your password">
                            <i class="fa fa-eye-slash text-gray-500 cursor-pointer" id="passToggle3"
                                aria-hidden="true"></i>
                        </label>
                    </div>
                    <button
                        class="w-full mt-6 bg-blue-600 text-white py-2.5 rounded-md font-semibold shadow-sm hover:bg-blue-700 active:scale-[0.98] transition"
                        type="submit">
                        Register
                    </button>
                </form>
                <?php if (isset($regerror)): ?>
                    <p class="text-sm text-red-600 mt-1">
                        <?php echo $regerror ?>
                    </p>
                <?php endif; ?>
            </div>
        </div>
        <!-- Login section -->
        <div class="w-1/2 min-h-screen flex justify-center items-center overflow-hidden">
            <div class="login-wrapper form-wrapper min-h-3/4 w-full max-w-md rounded-lg relative flex flex-col justify-start items-center p-6 md:p-8 text-white <?= $overlayActive ? 'active' : '' ?>"">
                <div class=" headings mb-8 text-center">
                <img src="./assets/images/-ssc_logo_1-d20a0c9e86d0b38d5dd3807b924e1bbb.png" class="w-24 h-auto mx-auto">
                <div>
                    <h1 class="text-3xl font-bold">LEARNIFY</h1>
                </div>
                <div>
                    <p>"Where Learning Becomes Habit"</p>
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
                        <!-- <p class="text-sm">Forgot password?</p> -->
                    </div>
                    <label class="flex items-center border border-slate-600 rounded-md px-3 py-2 cursor-text">
                        <i class="fa fa-lock mr-2 text-gray-500 text-lg fa-fw" aria-hidden="true"></i>
                        <input type="password" name="password" id="loginpassword" required
                            class="w-full py-1 outline-none" placeholder="Enter your password">
                        <i class="fa fa-eye-slash text-gray-500 cursor-pointer" id="passToggle" aria-hidden="true"></i>
                    </label>
                </div>
                <button
                    class="w-full mt-6 bg-blue-600 text-white py-2.5 rounded-md font-semibold shadow-sm hover:bg-blue-700 active:scale-[0.98] transition"
                    type="submit">
                    Login
                </button>
            </form>
            <?php if (isset($logerror)): ?>
                <p class="text-sm text-red-600 mt-1">
                    <?php echo $logerror ?>
                </p>
            <?php endif; ?>
        </div>
    </div>

    <div id="overlay"
        class="min-h-screen w-full absolute flex justify-center items-center z-10 <?= $overlayActive ? 'active' : '' ?>">
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
    <script>
        let success = <?= json_encode($successfulreg) ?>;
        if (success != "") {
            console.log('ok');
            Swal.fire({
                icon: "success",
                title: "Registered!",
                text: "You have been succesfully registered",
            });
        }
    </script>
    <script src="./assets/js/login-register.js"></script>
</body>

</html>