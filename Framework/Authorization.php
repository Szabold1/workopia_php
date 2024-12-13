<?php

namespace Framework;

class Authorization
{
    /**
     * Check if the current user owns a listing
     * @param int $listingsUserId
     * @return bool
     */
    public static function ownsListing($listingsUserId)
    {
        $user = Session::get('user');
        $userId = $user ? (int) $user['id'] : null;

        return $listingsUserId === $userId;
    }
}
