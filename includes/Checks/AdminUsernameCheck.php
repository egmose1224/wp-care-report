<?php
/**
 * Admin username check.
 *
 * @package WPCareReport
 */

namespace WPCareReport\Checks;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class AdminUsernameCheck extends AbstractCheck {
	public function run(): array {
		$user = get_user_by( 'login', 'admin' );

		if ( $user ) {
			return $this->result(
				'admin_username',
				self::CATEGORY_SECURITY,
				self::STATUS_RECOMMENDED,
				__( 'Username "admin" exists', 'wp-care-report' ),
				__( 'A user account with the login name "admin" exists.', 'wp-care-report' ),
				__( 'Use individual administrator accounts with less predictable usernames.', 'wp-care-report' ),
				6
			);
		}

		return $this->result(
			'admin_username',
			self::CATEGORY_SECURITY,
			self::STATUS_GOOD,
			__( 'No "admin" username found', 'wp-care-report' ),
			__( 'No user account with the login name "admin" was found.', 'wp-care-report' ),
			__( 'Continue using named user accounts instead of shared administrator logins.', 'wp-care-report' ),
			6
		);
	}
}
