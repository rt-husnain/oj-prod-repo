<?php
// WARNING: The contents of this file are auto-generated.
?>
<?php
// Merged from custom/Extension/modules/oj_Events/Ext/LogicHooks/event_hook.php


$hook_array['before_save'][] = Array(
    1,
    'check for uniqueness of event short code',
    'custom/modules/oj_Events/CheckShortCode.php',
    'CheckShortCode',
    'isCodeUnique'
);
$hook_array['before_save'][] = Array(
    2,
    'Generate unique voucher code',
    'custom/modules/oj_Events/CheckShortCode.php',
    'CheckShortCode',
    'generateVoucherCode'
)


?>
