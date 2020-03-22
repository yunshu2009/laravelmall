<?php

namespace App\Constants;

class SmsGrouponConstant
{
    /* 团购规则状态，正常上线则0，到期自动下线则1，管理手动下线则2 */
    const RULE_STATUS_ON = 0;
    const RULE_STATUS_DOWN_EXPIRE = 1;
    const RULE_STATUS_DOWN_ADMIN = 2;

    /* 团购活动状态，开团未支付则0，开团中则1，开团失败则2 */
    const STATUS_NONE = 0;
    const STATUS_ON = 1;
    const STATUS_SUCCEED = 2;
    const STATUS_FAIL = 3;
}
