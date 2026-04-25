<?php
/**
 * Administrator user count check.
 *
 * @package WPCareReport
 */

namespace WPCareReport\Checks;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class AdministratorUsersCheck extends AbstractCheck {
	public function run(): array {
		$query = new \WP_User_Query(
			array(
				'role'   => 'administrator',
				'fields' => 'ID',
				'number' => 100,
			)
		);
		$count = (int) $query->get_total();

		if ( $count > 1 ) {
			return $this->result(
				'administrator_users',
				self::CATEGORY_SECURITY,
				self::STATUS_RECOMMENDED,
				sprintf(
					/* translators: %d: number of administrator users. */
					__( '%d administrator users found', 'wp-care-report' ),
					$count
				),
				__( 'Multiple administrator accounts have full control of this site.', 'wp-care-report' ),
				__( 'Review administrator users and remove or downgrade accounts that do not need full access.', 'wp-care-report' ),
				6
			);
		}

		return $this->result(
			'administrator_users',
			self::CATEGORY_SECURITY,
			self::STATUS_GOOD,
			__( 'Administrator access is limited', 'wp-care-report' ),
			__( 'Only one administrator account was found.', 'wp-care-report' ),
			__( 'Review administrator access regularly and use lower roles where possible.', 'wp-care-report' ),
			6
		);
	}
}
