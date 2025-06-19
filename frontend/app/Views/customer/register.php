<div class="register-container">
      <h2>Đăng ký tài khoản</h2>
      <?php if (!empty($register_error)): ?>
             <div class="error-msg"><?= $register_error ?></div>
      <?php endif; ?>
      <?php if (!empty($register_success)): ?>
             <div class="success-msg"><?= $register_success ?></div>
      <?php endif; ?>
      <form method="post" action="?controller=auth&action=register" autocomplete="off">
             <input type="text" name="last_name" placeholder="Họ" required autocomplete="off"
                    value="<?= htmlspecialchars($last_name ?? '') ?>">
             <input type="text" name="first_name" placeholder="Tên" required autocomplete="off"
                    value="<?= htmlspecialchars($first_name ?? '') ?>">
             <input type="email" name="email" placeholder="Email" required autocomplete="off"
                    value="<?= htmlspecialchars($email ?? '') ?>">
             <input type="text" name="phone" placeholder="Số điện thoại" required autocomplete="off"
                    value="<?= htmlspecialchars($phone ?? '') ?>">
             <input type="password" name="password" placeholder="Mật khẩu" required autocomplete="new-password">
             <input type="text" name="street" placeholder="Địa chỉ (Số nhà, đường...)" required autocomplete="off"
                    value="<?= htmlspecialchars($street ?? '') ?>">
             <input type="text" name="city" placeholder="Thành phố" required autocomplete="off"
                    value="<?= htmlspecialchars($city ?? '') ?>">
             <input type="text" name="country_code" placeholder="Quốc gia (VN...)" required autocomplete="off"
                    value="<?= htmlspecialchars($country_code ?? '') ?>">
             <button type="submit">Đăng ký</button>
      </form>
      <a class="switch-link" href="?controller=auth&action=login">Bạn đã có tài khoản? Đăng nhập tại đây</a>
</div>