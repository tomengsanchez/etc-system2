<?php require_once APPROOT . '/views/inc/header.php'; ?>
<div class="row">
    <div class="col-md-6 mx-auto">
        <div class="card card-body bg-light mt-5">
            <h2>Create An Account</h2>
            <p>Please fill out this form to register with us</p>
            <form action="<?php echo URLROOT; ?>/user/register" method="post">
                <div class="form-group mb-3">
                    <label for="name">Name: <sup>*</sup></label>
                    <input type="text" name="name" class="form-control form-control-lg <?php echo (!empty($data['name_err'])) ? 'is-invalid' : ''; ?>" value="<?php echo $data['name'] ?? ''; ?>">
                    <span class="invalid-feedback"><?php echo $data['name_err'] ?? ''; ?></span>
                </div>
                <div class="form-group mb-3">
                    <label for="email">Email: <sup>*</sup></label>
                    <input type="email" name="email" class="form-control form-control-lg <?php echo (!empty($data['email_err'])) ? 'is-invalid' : ''; ?>" value="<?php echo $data['email'] ?? ''; ?>">
                    <span class="invalid-feedback"><?php echo $data['email_err'] ?? ''; ?></span>
                </div>
                <div class="form-group mb-3">
                    <label for="password">Password: <sup>*</sup></label>
                    <input type="password" name="password" class="form-control form-control-lg <?php echo (!empty($data['password_err'])) ? 'is-invalid' : ''; ?>" value="<?php echo $data['password'] ?? ''; ?>">
                    <span class="invalid-feedback"><?php echo $data['password_err'] ?? ''; ?></span>
                </div>
                <div class="form-group mb-3">
                    <label for="confirm_password">Confirm Password: <sup>*</sup></label>
                    <input type="password" name="confirm_password" class="form-control form-control-lg <?php echo (!empty($data['confirm_password_err'])) ? 'is-invalid' : ''; ?>" value="<?php echo $data['confirm_password'] ?? ''; ?>">
                    <span class="invalid-feedback"><?php echo $data['confirm_password_err'] ?? ''; ?></span>
                </div>

                <div class="row">
                    <div class="col">
                        <input type="submit" value="Register" class="btn btn-success btn-block w-100">
                    </div>
                    <div class="col">
                        <a href="<?php echo URLROOT; ?>/user/login" class="btn btn-light btn-block w-100">Have an account? Login</a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<?php require_once APPROOT . '/views/inc/footer.php'; ?>
