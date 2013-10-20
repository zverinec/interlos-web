<?php
/**
 * This source file is subject to the "New BSD License".
 *
 * For more information please see http://code.google.com/p/eskymofw/
 *
 * @copyright	Copyright (c) 2009 Jan Papoušek (jan.papousek@gmail.com),
 *				Jan Drábek (repli2dev@gmail.com)
 * @license		http://www.opensource.org/licenses/bsd-license.php
 * @link		http://code.google.com/p/eskymofw/
 */

/**
 * This class represents file type.
 *
 * @author      Jan Papousek
 * @version		$Id: FileType.php 84 2009-08-25 10:35:25Z jan.papousek $
 * @see         FileTypeFilter
 */
class FileType extends /*Nette\*/Object
{

	// TODO: Support more mime types

	const TXT = 1001;

	const HTML = 1003;

	const PHP = 1004;

	const CSS = 1005;

	const JS = 1006;

	const JSON = 1007;

	const XML = 1008;

	const SWF = 1009;

	const FLW = 1010;

	const PNG = IMAGETYPE_PNG;

	const JPEG = IMAGETYPE_JPEG;

	const GIF = IMAGETYPE_GIF;

	const BMP = IMAGETYPE_BMP;

	const TIFF = IMAGETYPE_TIFF_II; // FIXME: Check.

	const ICO = 1002;

	const SVG = 1028;

	const ZIP = 1011;

	const RAR = 1012;

	const EXE = 1013;

	const MSI = 1014;

	const CAB = 1015;

	const MP3 = 1016;

	const QUICKTIME = 1018;

	const MPEG = 1028;

	const PDF = 1019;

	const PSD = 1020;

	const POSTSCRIPT = 1021;

	const DOC = 1022;

	const RTF = 1023;

	const XLS = 1024;

	const PPT = 1025;

	const ODT = 1026;

	const ODS = 1027;

	/**
	 * Mime type.
	 *
	 * @var string
	 */
	private $mimeType;

	/**
	 * Supported mime types. 'type number' => 'mime type'
	 *
	 * @var array|int
	 */
	private static $supported = array(
		'text/plain'						=> self::TXT,
		'text/html'							=> self::HTML,
		'text/php'							=> self::PHP,
		'text/css'							=> self::CSS,
		'application/javascript'			=> self::JS,
		'application/json'					=> self::JSON,
		'application/xml'					=> self::XML,
		'application/x-shockwave-flash'		=> self::SWF,
		'video/x-flv'						=> self::FLW,

		// images
		'image/png'							=> self::PNG,
		'image/jpeg'						=> self::JPEG,
		'image/gif'							=> self::GIF,
		'image/bmp'							=> self::BMP,
		'image/vnd.microsoft.icon'			=> self::ICO,
		'image/tiff'						=> self::TIFF,
		'image/svg+xml'						=> self::SVG,

		// archives
		'application/zip'					=> self::ZIP,
		'application/x-rar-compressed'		=> self::RAR,
		'application/x-msdownload'			=> self::EXE,
		'application/x-msdownload'			=> self::MSI,
		'application/vnd.ms-cab-compressed'	=> self::CAB,

		// audio/video
		'audio/mpeg'						=> self::MP3,
		'video/quicktime'					=> self::QUICKTIME,
		'video/mpeg'						=> self::MPEG,

		// adobe
		'application/pdf'					=> self::PDF,
		'image/vnd.adobe.photoshop'			=> self::PSD,
		'application/postscript'			=> self::POSTSCRIPT,

		// ms office
		'application/msword'				=> self::DOC,
		'application/rtf'					=> self::RTF,
		'application/vnd.ms-excel'			=> self::XLS,
		'application/vnd.ms-powerpoint'		=> self::PPT,

		// open office
		'application/vnd.oasis.opendocument.text'			=> self::ODT,
		'application/vnd.oasis.opendocument.spreadsheet'	=>self::ODS
	);

	/**
	 * It creates a new instance of filetype.
	 * 
	 * @param string $mimeType Mime type.
	 * @throws NullPointerException if the $mimeTye is empty.
	 * @throws NotSupportedException if the Mime type is not supported.
	 */
	public function  __construct($mimeType) {
		if (empty($mimeType)) {
			throw new NullPointerException("mimeType");
		}
		$mimeType = String::lower($mimeType);
		if (empty(self::$supported[$mimeType])) {
			throw new NotSupportedException("This mime type is not supported: $mimeType.");
		}
		$this->mimeType = $mimeType;
	}

	/**
	 * It returns mime type.
	 *
	 * @return string
	 */
	public function getMimeType() {
		return $this->mimeType;
	}

	/**
	 * It returns code of the file type.
	 *
	 * @return int
	 */
	public function getTypeCode() {
		return self::$supported[$this->mimeType];
	}

	public function  __toString() {
		return $this->mimeType;
	}
}