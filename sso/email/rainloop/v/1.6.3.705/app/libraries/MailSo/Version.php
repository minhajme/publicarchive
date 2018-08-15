<?php

namespace MailSo;

/**
 * @category MailSo
 */
final class Version
{
	/**
	 * @var string
	 */
	const APP_VERSION = '1.3.0';

	/**
	 * @var string
	 */
	const MIME_X_MAILER = 'MailSo';

	/**
	 * @return string
	 */
	public static function AppVersion()
	{
		return \MailSo\Version::APP_VERSION;
	}

	/**
	 * @return string
	 */
	public static function XMailer()
	{
		return \MailSo\Version::MIME_X_MAILER.'/'.\MailSo\Version::APP_VERSION;
	}

	/**
	 * @return string
	 */
	public static function Signature()
	{
		$oPhar = new \Phar('mailso.phar');
		return $oPhar->getSignature();
	}
}
