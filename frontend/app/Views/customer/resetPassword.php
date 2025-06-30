<?php
?>

<?php if (!empty($error)): ?>
    <div class="container m-auto bg-red-500 py-2 px-4 rounded-md text-white flex justify-between">
        <p><?= htmlspecialchars($error) ?></p>
        <span class="cursor-pointer font-bold" onclick="this.parentNode.remove()"><sup>X</sup></span>
    </div>
<?php endif; ?>

<?php if (!empty($success)): ?>
    <div class="container m-auto bg-green-500 py-2 px-4 rounded-md text-white flex justify-between">
        <p><?= htmlspecialchars($success) ?></p>
        <span class="cursor-pointer font-bold" onclick="this.parentNode.remove()"><sup>X</sup></span>
    </div>
<?php endif; ?>

<div class="container mx-auto">
    <div class="max-w-md mx-auto bg-white">
        <div class="p-8">
            <form class="space-y-6" method="post" action="/?url=auth/resetPassword&token=<?= htmlspecialchars($token ?? '') ?>">
                <h2 class="text-2xl font-bold text-center text-gray-800">Reset Password</h2>
                <div>
                    <label for="resetPassword" class="block text-sm font-medium text-gray-700">New Password</label>
                    <input type="password" id="resetPassword" name="password" required class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                </div>
                <div>
                    <label for="confirmResetPassword" class="block text-sm font-medium text-gray-700">Confirm Password</label>
                    <input type="password" id="confirmResetPassword" name="confirm_password" required class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                </div>
                <div>
                    <button type="submit" class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-500 hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        Reset Password
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>