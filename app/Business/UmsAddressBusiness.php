<?php

namespace App\Business;

use App\Constants\ResultCode;
use App\Helper\CommonResult;
use App\Models\Mysql\UmsAddress;
use Illuminate\Support\Facades\DB;

class UmsAddressBusiness extends BaseBusiness
{
    protected static $model = 'UmsAddress';
    protected static $select = ['id', 'name','province','city','county','postal_code','tel','is_default'];

    public static function getList(array $attributes)
    {
        $attributes['page'] = $attributes['page'] ?? 1;
        $attributes['limit'] = $attributes['limit'] ?? 10;

        $condition = [
            'user_id'   =>  $attributes['userId']
        ];
        $list = self::queryListByCondition($attributes['page'], $attributes['limit'], $condition, 'is_default', 'desc');
        $total = self::queryCountByCondition($condition);

        $page = CommonResult::formatPaged($attributes['page'], $attributes['limit'], $total);

        return CommonResult::formatBody(array_merge(['list'=>$list], $page));
    }

    public static function delete(array $attributes)
    {
        $address = UmsAddress::query()
                ->where('id', $attributes['id'])
                ->where('user_id', $attributes['userId'])
                ->first();

        if (! $address) {
            return CommonResult::formatError(ResultCode::BAD_REQUEST);
        }

        UmsAddress::destroy($address['id']);

        return CommonResult::formatBody();
    }

    public static function save(array $attributes)
    {
        DB::beginTransaction();

        if (isset($attributes['isDefault']) && $attributes['isDefault']) {
            $reset = self::resetDefault($attributes['userId']);
        }
        if (! $reset) {
            DB::rollBack();
            return CommonResult::formatError(ResultCode::UPDATE_DB_ERROR);
        }

        $attributes = self::transformInput($attributes);
        if (isset($attributes['id']) && $attributes['id']) {  // 修改
            $save = UmsAddress::where('id', $attributes['id'])
                              ->where('user_id', $attributes['user_id'])
                              ->update($attributes);

        } else {   // 添加
            $save = UmsAddress::create($attributes);
        }
        if (! $save) {
            DB::rollBack();
            return CommonResult::formatError(ResultCode::UPDATE_DB_ERROR);
        }

        DB::commit();

        return CommonResult::formatBody();
    }

    public static function resetDefault($userId)
    {
        return UmsAddress::where('user_id', $userId)->update(['is_default'=>0]);
    }

    public static function queryDefaultAddressId($userId)
    {
        $obj = UmsAddress::query()
                    ->where('user_id', $userId)
                    ->where('is_default', 0)
                    ->first();

        return is_null($obj) ? 0 : $obj->id;
    }

    public static function queryAddressById($userId, $addressId)
    {
        return UmsAddress::query()->where('user_id', $userId)
                                  ->where('address_id', $addressId)
                                  ->first();
    }
}
