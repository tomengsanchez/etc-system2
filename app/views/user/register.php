<div class="row">
    <div class="col-md-6 mx-auto">
        <div class="card card-body bg-light mt-5">
            <h2>Create An Account</h2>
            <p>Please fill out this form to register.</p>
            <form action="<?= URLROOT; ?>/user/register" method="post">
                <input type="hidden" name="csrf_token" value="<?= get_csrf_token(); ?>">
                <div class="form-floating mb-3">
                    <input type="text" name="name" class="form-control <?= (!empty($data['name_err'])) ? 'is-invalid' : ''; ?>" id="name" placeholder="Name" value="<?= $data['name']; ?>">
                    <label for="name">Name: <sup>*</sup></label>
                    <div class="invalid-feedback"><?= $data['name_err']; ?></div>
                </div>
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
                <div class="form-floating mb-3">
                    <input type="password" name="confirm_password" class="form-control <?= (!empty($data['confirm_password_err'])) ? 'is-invalid' : ''; ?>" id="confirm_password" placeholder="Confirm Password" value="<?= $data['confirm_password']; ?>">
                    <label for="confirm_password">Confirm Password: <sup>*</sup></label>
                    <div class="invalid-feedback"><?= $data['confirm_password_err']; ?></div>
                </div>

                <div class="row">
                    <div class="col">
                        <input type="submit" value="Register" class="btn btn-success btn-block w-100">
                    </div>
                    <div class="col">
                        <a href="<?= URLROOT; ?>/user/login" class="btn btn-light btn-block w-100">Have an account? Login</a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
