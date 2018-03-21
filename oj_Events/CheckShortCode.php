<?php

if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}

/**
 * Class CheckShortCode
 *
 * Generate short code and unique voucher codes
 */
class CheckShortCode
{
    /**
     * @param $bean
     * @param $event
     * @param $arguments
     */
    function isCodeUnique($bean, $event, $arguments)
    {
        $short_code = $bean->shortcode_c;
        if (isset($bean->fetched_row['id'])) {
            if ($bean->fetched_row['shortcode_c'] != $short_code) {
                $this->checkUnique($short_code);
            }
        } else {
            $this->checkUnique($short_code);
        }

    }

    /**
     * @param $short_code
     * @throws SugarApiExceptionInvalidParameter
     */
    function checkUnique($short_code)
    {
        global $db;
        $sql = "SELECT 
    *
FROM
    oj_events e
        INNER JOIN
    oj_events_cstm cstm ON e.id = cstm.id_c
WHERE
    cstm.shortcode_c = '$short_code'
        AND e.deleted = 0";
        $res = $db->query($sql);
        if ($res->num_rows > 0) {
            throw new SugarApiExceptionInvalidParameter('Event Short Code must be unique!');
        }
    }

    /**
     * @param $bean
     * @param $event
     * @param $arguments
     */
    function generateVoucherCode($bean, $event, $arguments)
    {
        if (!isset($bean->fetched_row['id'])) {
            $bean->voucher_code = strtoupper(uniqid());
        } else {
            if (empty($bean->voucher_code)) {
                $bean->voucher_code = strtoupper(uniqid());
            }
        }
    }
}

?>