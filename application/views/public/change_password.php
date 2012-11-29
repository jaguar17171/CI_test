<div class="page-header">
    <h1>Change Password</h1>
</div>
<?=show_messages_from_session();?>
<?=validation_errors('<div class="infoblock error">', '</div>');?>

<?php echo form_open("auth/change_password", array('id'=>'form', 'class'=>'form-horizontal'));?>
    <?=form_input($user_id);?>
    <div class="control-group">
        <label class="control-label" for="old">Old Password:</label>
        <div class="controls">
            <?=form_input($old_password);?>
        </div>
    </div>
    <div class="control-group">
        <label class="control-label" for="new">New Password (at least <?=$min_password_length;?> characters long):</label>
        <div class="controls">
            <?=form_input($new_password);?>
        </div>
    </div>
    <div class="control-group">
        <label class="control-label" for="confirm">Confirm New Password:</label>
        <div class="controls">
            <?=form_input($new_password_confirm);?>
        </div>
    </div>
    <div class="control-group">
        <div class="controls">
            <?=form_submit('submit', 'Change', 'class="btn"');?>
        </div>
    </div>
<?php echo form_close();?>
