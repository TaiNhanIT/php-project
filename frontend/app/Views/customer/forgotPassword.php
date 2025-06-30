<?php if (!empty($forgotError)): ?>
    <div class="container m-auto bg-red-500 py-2 px-4 rounded-md text-white flex justify-between">
        <p><?= htmlspecialchars($forgotError) ?></p>
        <span class="cursor-pointer font-bold" onclick="return this.parentNode.remove()"><sup>X</sup></span>
    </div>
<?php endif; ?>

<?php if (!empty($forgotSuccess)): ?>
    <div class="container m-auto bg-green-500 py-2 px-4 rounded-md text-white flex justify-between">
        <p><?= htmlspecialchars($forgotSuccess) ?></p>
        <span class="cursor-pointer font-bold" onclick="return this.parentNode.remove()"><sup>X</sup></span>
    </div>
<?php endif; ?>
<div class="container mx-auto">
    <div class="max-w-md mx-auto bg-white p-8">
        <h2 class="text-2xl font-bold text-center text-gray-800">Forgot Password</h2>
        <form method="post" action="/auth/forgotPassword" class="space-y-6">
            <div>
                <label for="forgotEmail" class="block text-sm font-medium text-gray-700">Email</label>
                <input type="email" id="forgotEmail" name="email" required class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
            </div>
            <div>
                <button type="submit" class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-500 hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    Send Reset Link
                </button>
            </div>
        </form>
    </div>
</div>