<div class="page-header">
    <h1>Forgot Password</h1>
</div>
<?=show_messages_from_session();?>
<?=validation_errors('<div class="infoblock error">', '</div>');?>
<p>Please enter your email address so we can send you an email to reset your password.</p>

<?php echo form_open("auth/forgot_password", array('id'=>'form', 'class'=>'form-horizontal'));?>
    <div class="control-group">
        <label class="control-label" for="email">Email Address:</label>
        <div class="controls">
            <?=form_input($email);?>
        </div>
    </div>
    <div class="control-group">
        <div class="controls">
            <?=form_submit('submit', 'Submit', 'class="btn"');?>
        </div>
    </div>
<?php echo form_close();?>