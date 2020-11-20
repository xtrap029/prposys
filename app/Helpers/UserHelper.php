<?php

namespace App\Helpers;

use App\Company;
use App\User;

final class UserHelper {
    public static function switch_company($company, $user = '') {
        
        if (!$user) {
            $user = auth()->id();
        }

        $user = User::where('id', $user)->first();

        if (Company::where('id', $company)->first()) {
            $data['company_id'] = $company;
            return $user->update($data);
        } else {
            return false;
        }

    }
}
?>