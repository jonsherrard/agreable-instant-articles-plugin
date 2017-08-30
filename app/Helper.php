<?php

namespace AgreableInstantArticlesPlugin;


/**
 * Class Helper
 *
 * @package AgreableInstantArticlesPlugin
 */
class Helper {

	/**
	 * Unique key
	 */
	const FLASH_MESSAGE_KEY = 'sharing_center_';

	/**
	 * @var OutletInterface[]
	 */
	private static $outlets;

	/**
	 * @return OutletInterface[]|mixed
	 */
	public static function getOutlets() {

		if ( ! isset( self::$outlets ) ) {
			self::$outlets = apply_filters( 'shortlist_get_outlets', [] );
		}

		return self::$outlets;

	}

	/**
	 * @param $name
	 *
	 * @return OutletInterface|bool
	 */
	public static function getOutletByKey( $name ) {
		$outlets = self::getOutlets();
		foreach ( $outlets as $index => $outlet ) {
			if ( $outlet->getUniqueKey() === $name ) {
				return $outlet;
			}
		}

		return false;
	}

	/**
	 * @param $permalink string
	 *
	 * @return string
	 */
	public static function reverseLinkReplacement( $permalink ) {

		$web_base_url = getenv( 'WEB_BASE_URL' );

		return rtrim( str_replace( rtrim( $web_base_url, '/' ), rtrim( getenv( 'WP_HOME' ), '/' ), $permalink ), '/' );
	}

	/**
	 * registers hook
	 */
	public static function admin_notices() {
		$id = get_current_user_id();
		if ( ! is_super_admin() ) {
			return '';
		}

		if ( $id && ( $notices = get_transient( self::FLASH_MESSAGE_KEY . $id ) ) ) {

			foreach ( $notices as $error ) {
				echo $error;
			}

			delete_transient( self::FLASH_MESSAGE_KEY . $id );
		}

		return true;
	}

	/**
	 * added to display notification in next possible admin_notices trigger, even if it's with different request
	 *
	 * @param $text
	 * @param $uId
	 * @param string $class
	 *
	 * @return false
	 */
	public static function set_notification( $text, $uId = null, $class = 'notice notice-success is-dismissible' ) {

		if ( ! is_super_admin() ) {
			return false;
		}

		$id = get_current_user_id();

		if ( $id ) {

			$notices = get_transient( self::FLASH_MESSAGE_KEY . $id );

			if ( ! is_array( $notices ) ) {
				$notices = [];
			}
			if ( ! is_null( $uId ) ) {
				$notices[ $uId ] = self::print_error( $text, $class );
			} else {
				$notices[] = self::print_error( $text, $class );
			}
			set_transient( self::FLASH_MESSAGE_KEY . $id, $notices, HOUR_IN_SECONDS );

		}

		return true;
	}

	/**
	 * Prints error
	 *
	 * @param $text
	 * @param string $class
	 *
	 * @return string
	 */
	public static function print_error( $text, $class = 'notice notice-success is-dismissible' ) {
		return "
		<div class='$class'>
			<p>$text</p>
		</div>
		";

	}

}