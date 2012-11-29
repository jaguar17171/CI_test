<div class="page-header">
    <h1>Login</h1>
</div>
<p>Please login with your email/username and password below.</p>
<?=show_messages_from_session();?>
<?=validation_errors('<div class="infoblock error">', '</div>');?>

<?=form_open("auth/login", array('id'=>'form', 'class'=>'form-horizontal'));?>
    <div class="control-group">
        <label class="control-label" for="identity">Email/Username:</label>
        <div class="controls">
            <?=form_input($identity);?>
        </div>
    </div>
    <div class="control-group">
        <label class="control-label" for="password">Password:</label>
        <div class="controls">
            <?=form_input($password);?>
            <p><a href="auth/forgot_password">Forgot your password?</a></p>
            <p>New to <?=SITE_TITLE;?>? <a href="register">Sign up</a></p>
        </div>

    </div>
    <div class="control-group">
        <div class="controls">
            <label class="checkbox">
                <?=form_checkbox('remember', '1', FALSE, 'id="remember"');?> Remember me
            </label>
            <?=form_submit('submit', 'Login', 'class="btn"');?>
        </div>
    </div>
    <!--<button type="submit" class="btn">Sign in</button>-->

<?=form_close();?>