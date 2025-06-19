<?php if (!empty($loginError)): ?>
    <div class="container m-auto bg-red-500 py-2 px-4 rounded-md text-white flex justify-between">
        <p><?= htmlspecialchars($loginError) ?></p>
        <span class="cursor-pointer font-bold" onclick="return this.parentNode.remove()"><sup>X</sup></span>
    </div>
<?php endif; ?>

<?php if (!empty($registerSuccess)): ?>
    <div class="container m-auto bg-green-500 py-2 px-4 rounded-md text-white flex justify-between">
        <p><?= htmlspecialchars($registerSuccess) ?></p>
        <span class="cursor-pointer font-bold" onclick="return this.parentNode.remove()"><sup>X</sup></span>
    </div>
<?php endif; ?>

<div class="container mx-auto">
    <div class="max-w-md mx-auto bg-white">
        <div class="p-8">
            <!-- Toggle between Login and Signup -->
            <div class="flex justify-center mb-8">
                <button id="loginTab" class="px-6 py-2 font-medium text-white bg-blue-500 rounded-l-lg focus:outline-none">
                    Login
                </button>
                <button id="signupTab" class="px-6 py-2 font-medium text-blue-500 bg-white border border-blue-500 rounded-r-lg focus:outline-none">
                    Sign Up
                </button>
            </div>

            <!-- Login Form -->
            <form id="loginForm" class="space-y-6" method="post" action="/auth/login">
                <h2 class="text-2xl font-bold text-center text-gray-800">Welcome Back</h2>
                <div>
                    <label for="loginEmail" class="block text-sm font-medium text-gray-700">Email</label>
                    <input type="email" id="loginEmail" name="email" value="<?= htmlspecialchars($email ?? '') ?>" required class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                </div>
                <div>
                    <label for="loginPassword" class="block text-sm font-medium text-gray-700">Password</label>
                    <input type="password" id="loginPassword" name="password" value="<?= htmlspecialchars($password ?? '') ?>" required class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                </div>
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <input id="remember-me" name="remember-me" type="checkbox" class="h-4 w-4 text-blue-500 focus:ring-blue-500 border-gray-300 rounded">
                        <label for="remember-me" class="ml-2 block text-sm text-gray-700">Remember me</label>
                    </div>
                    <div class="text-sm">
                        <a href="#" class="font-medium text-blue-500 hover:text-blue-700">Forgot password?</a>
                    </div>
                </div>
                <div>
                    <button type="submit" class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-500 hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        Sign in
                    </button>
                </div>
            </form>

            <!-- Signup Form (Hidden by default) -->
            <form id="signupForm" class="space-y-6 hidden" method="post" action="/auth/register">
                <h2 class="text-2xl font-bold text-center text-gray-800">Create Account</h2>
                <div>
                    <label for="signupName" class="block text-sm font-medium text-gray-700">Full Name</label>
                    <input type="text" id="signupName" name="full_name" value="<?= htmlspecialchars($form_data['full_name'] ?? '') ?>" required class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                </div>
                <div>
                    <label for="signupEmail" class="block text-sm font-medium text-gray-700">Email</label>
                    <input type="email" id="signupEmail" name="email" value="<?= htmlspecialchars($form_data['email'] ?? '') ?>" required class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                </div>
                <div>
                    <label for="signupPassword" class="block text-sm font-medium text-gray-700">Password</label>
                    <input type="password" id="signupPassword" name="password" value="<?= htmlspecialchars($form_data['password'] ?? '') ?>" required class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                </div>
                <div>
                    <label for="confirmPassword" class="block text-sm font-medium text-gray-700">Confirm Password</label>
                    <input type="password" id="confirmPassword" name="confirm_password" value="<?= htmlspecialchars($form_data['confirm_password'] ?? '') ?>" required class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                </div>
                <div class="flex items-center">
                    <input id="terms" name="agree_terms" type="checkbox" value="1" <?= isset($form_data['agree_terms']) ? 'checked' : '' ?> required class="h-4 w-4 text-blue-500 focus:ring-blue-500 border-gray-300 rounded">
                    <label for="terms" class="ml-2 block text-sm text-gray-700">
                        I agree to the <a href="#" class="text-blue-500 hover:text-blue-700">Terms and Conditions</a>
                    </label>
                </div>
                <div>
                    <button type="submit" class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-500 hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        Create Account
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const loginTab = document.getElementById('loginTab');
        const signupTab = document.getElementById('signupTab');
        const loginForm = document.getElementById('loginForm');
        const signupForm = document.getElementById('signupForm');

        // Switch to Signup form
        signupTab.addEventListener('click', function(e) {
            e.preventDefault();
            loginForm.classList.add('hidden');
            signupForm.classList.remove('hidden');
            loginTab.classList.remove('bg-blue-500', 'text-white');
            loginTab.classList.add('bg-white', 'border', 'border-blue-500', 'text-blue-500');
            signupTab.classList.remove('bg-white', 'border', 'border-blue-500', 'text-blue-500');
            signupTab.classList.add('bg-blue-500', 'text-white');
        });

        // Switch to Login form
        loginTab.addEventListener('click', function(e) {
            e.preventDefault();
            signupForm.classList.add('hidden');
            loginForm.classList.remove('hidden');
            signupTab.classList.remove('bg-blue-500', 'text-white');
            signupTab.classList.add('bg-white', 'border', 'border-blue-500', 'text-blue-500');
            loginTab.classList.remove('bg-white', 'border', 'border-blue-500', 'text-blue-500');
            loginTab.classList.add('bg-blue-500', 'text-white');
        });
    });
</script>