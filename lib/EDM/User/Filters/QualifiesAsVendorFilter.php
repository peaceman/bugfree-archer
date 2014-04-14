<?php
namespace EDM\User\Filters;

use Auth, Notification, Redirect, URL;

class QualifiesAsVendorFilter
{
	public function filter()
	{
		if (!Auth::check()) {
			return Redirect::guest(URL::route('auth.log-in'));
		}

		/** @var \User $user */
		$user = Auth::user();
		if (!$user->getQualifiesAsVendor()) {
			Notification::info(trans('user.profile.missing_address_for_selling'));
			return Redirect::guest(URL::route('user.profile', ['username' => $user->username]) . '#!address');
		}
	}
}
