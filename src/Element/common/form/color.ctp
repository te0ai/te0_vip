<div id="wrap_<?php h($_['id']) ?>" class="form-group">
    <input
    type="color"
    id="<?php h($_['id']) ?>"
    class="form-control form-check <?php h($_['class']) ?>"
    name="<?php h($_['name']) ?>"
    value="<?php h($_['default_setting']) ?>"
    data-required-flg="<?php h($_['required_flg']) ?>"
    data-regex="<?php h($_['regex']) ?>"
    data-regex-error="<?php h($_['regex_error']) ?>"
    />
    <div id="error_<?php h($_['id']) ?>"><?php h($_['error']) ?></div>
</div>
