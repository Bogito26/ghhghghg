<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Register</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h2 class="mb-4">Register</h2>
    <?php if(isset($_GET['error'])): ?>
        <div class="alert alert-danger"><?=htmlspecialchars($_GET['error'])?></div>
    <?php endif; ?>
    <form method="post" action="register_process.php">
        <div class="mb-3">
            <label>Name</label>
            <input type="text" class="form-control" name="name" required maxlength="100">
        </div>
        <div class="mb-3">
            <label>Email</label>
            <input type="email" class="form-control" name="email" required maxlength="100">
        </div>
        <div class="mb-3">
            <label>Password</label>
            <input type="password" class="form-control" id="password" name="password" required minlength="6">
            <div class="form-check mt-2">
                <input class="form-check-input" type="checkbox" id="showPassword" onclick="togglePassword()">
                <label class="form-check-label" for="showPassword">Show Password</label>
            </div>
        </div>
        <div class="mb-3">
            <label>Role</label>
            <select class="form-control" name="role" required>
                <option value="user">User</option>
                <option value="teacher">Teacher</option>
                <option value="admin">Admin</option>
            </select>
        </div>
        <button type="submit" class="btn btn-success">Register</button>
        <a href="login.php" class="btn btn-link">Login</a>
    </form>
</div>
<script>
function togglePassword() {
    const pw = document.getElementById("password");
    pw.type = pw.type === "password" ? "text" : "password";
}
</script>
</body>
</html>