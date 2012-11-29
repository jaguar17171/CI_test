<div class="page-header">
    <h1>Sign Up</h1>
</div>
<p>Please enter the information below.</p>
<?=show_messages_from_session();?>
<?=validation_errors('<div class="infoblock error">', '</div>');?>

<?php echo form_open("auth/register", array('id'=>'form', 'class'=>'form-horizontal'));?>
    <div class="control-group">
        <label class="control-label" for="email">Email:</label>
        <div class="controls">
            <?=form_input($email);?>
        </div>
    </div>
    <div class="control-group">
        <label class="control-label" for="password">Password:</label>
        <div class="controls">
            <?=form_input($password);?>
        </div>
    </div>
    <div class="control-group">
        <label class="control-label" for="password_confirm">Confirm Password:</label>
        <div class="controls">
            <?=form_input($password_confirm);?>
        </div>
    </div>
    <div class="control-group">
        <label class="control-label" for="first_name">First Name:</label>
        <div class="controls">
            <?=form_input($first_name);?>
        </div>
    </div>
    <div class="control-group">
        <label class="control-label" for="last_name">Last Name:</label>
        <div class="controls">
            <?=form_input($last_name);?>
        </div>
    </div>
    <div class="control-group">
        <div class="controls">
            <?=form_submit('submit', 'Sign Up', 'class="btn"');?>
        </div>
    </div>

<?php echo form_close();?>