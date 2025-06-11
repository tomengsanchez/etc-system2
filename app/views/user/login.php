<div class="row">
    <div class="col-md-6 mx-auto">
        <div class="card card-body bg-light mt-5">
            <h2>Login</h2>
            <p>Please fill in your credentials to log in.</p>
            <form action="<?= URLROOT; ?>/user/login" method="post">
                <input type="hidden" name="csrf_token" value="<?= get_csrf_token(); ?>">
                <div class="form-floating mb-3">
                    <input type="email" name="email" class="form-control <?= (!empty($data['email_err'])) ? 'is-invalid' : ''; ?>" id="email" placeholder="name@example.com" value="<?= $data['email']; ?>">
                    <label for="email">Email: <sup>*</sup></label>
                    <div class="invalid-feedback"><?= $data['email_err']; ?></div>
                </div>
                <div class="form-floating mb-3">
                    <input type="password" name="password" class="form-control <?= (!empty($data['password_err'])) ? 'is-invalid' : ''; ?>" id="password" placeholder="Password" value="<?= $data['password']; ?>">
                    <label for="password">Password: <sup>*</sup></label>
                    <div class="invalid-feedback"><?= $data['password_err']; ?></div>
                </div>
                
                <div class="row">
                    <div class="col">
                        <input type="submit" value="Login" class="btn btn-success btn-block w-100">
                    </div>
                    <div class="col">
                        <a href="<?= URLROOT; ?>/user/register" class="btn btn-light btn-block w-100">No account? Register</a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
