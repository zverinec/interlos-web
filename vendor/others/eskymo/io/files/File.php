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
 * The file descriptor, which provides all manipulation with files.
 *
 * @author Jan Papousek
 * @version		$Id: File.php 137 2009-11-18 23:28:49Z jan.papousek $
 */
class File extends /*Nette\*/Object
{

	/**
	 * The error code for IOException if there is a problem to close the file.
	 *
	 * @var int
	 */
	const ERROR_CLOSE = 40;

	/**
	 * The error code for IOException if there is a problem to detect file info
	 *
	 * @var int
	 */
	const ERROR_FILE_INFO = 20;

	/**
	 * The error code for IOException if there is a general problem.
	 *
	 * @var int
	 */
	const ERROR_GENERAL = 30;

	/**
	 * The error code for IOException if there is a problem to open the file.
	 *
	 * @var int
	 */
	const ERROR_OPEN = 50;

	/**
	 * The error code for IOException if there is a security problem.
	 *
	 * @var int
	 */
	const ERROR_SECURITY = 10;

	/**
	 * File extension
	 *
	 * @var string
	 */
	private $extension;

	/**
	 * The file path
	 *
	 * @var string
	 */
	private $path;

	/**
	 * The file name.
	 *
	 * @var string
	 */
	private $name;

	/**
	 * The parent file.
	 *
	 * @var File
	 */
	 private $parent;

	 /**
	  * The file type.
	  *
	  * @var FileType
	  */
	private $type;

	/**
	 * It creates new file descriptor.
	 *
	 * @param string $path The asbtract path to the file.
	 * @throws NullPointerException if the $path is empty.
	 */
	public function  __construct($path) {
		if (empty($path)) {
			throw new NullPointerException("Argument 'path' is NULL.");
		}
		$this->path = rtrim($path, DIRECTORY_SEPARATOR);
	}

	/**
	 * It checks if the file is executable.
	 *
	 * @return boolean
	 * @throws FileNotFoundException if the file does not exist.
	 * @throws IOException if there is an I/O problem.
	 */
	public function canExecute() {
		if (!$this->exists()) {
			throw new FileNotFoundException($this->getPath());
		}
		Tools::tryError();
		$check = is_executable($this->getPath());
		if  (Tools::catchError($msg)) {
			throw new IOException($msg, self::ERROR_GENERAL);
		}
		return $check;
	}

	/**
	 * It checks if the file is readable.
	 *
	 * @return boolean
	 * @throws FileNotFoundException if the file does not exist.
	 */
	public function canRead() {
		if (!$this->exists()) {
			throw new FileNotFoundException($this->getPath());
		}
		Tools::tryError();
		$check = is_readable($this->getPath());
		if  (Tools::catchError($msg)) {
			throw new IOException($msg, self::ERROR_GENERAL);
		}
		return $check;
	}

	/**
	 * It checks if the file is writable.
	 *
	 * @return boolean
	 * @throws FileNotFoundException if the file does not exist.
	 */
	public function canWrite() {
		if (!$this->exists()) {
			throw new FileNotFoundException($this->getPath());
		}
		Tools::tryError();
		$check = is_writable($this->getPath());
		if  (Tools::catchError($msg)) {
			throw new IOException($msg, self::ERROR_GENERAL);
		}
		return $check;
	}

	/**
	 * It copies a file
	 *
	 * @return File Copy of this file
	 * @throws FileNotFoundException if the file does not exist.
	 * @throws IOException if there is an I/O problem.
	 */
	public function copy($destination) {
		if (empty($destination)) {
			throw new NullPointerException("destination");
		}
		if (!$this->exists()) {
			throw new FileNotFoundException($this->path);
		}
		$destFile = new File($destination);
		if (!$destFile->getParentFile()->canWrite()) {
			throw new IOException("The file cannot be copied.", self::ERROR_SECURITY);
		}
		Tools::tryError();
		$check = copy($this->getAbsolutePath(), $destination);
		if  (Tools::catchError($msg)) {
			throw new IOException($msg, self::ERROR_GENERAL);
		}
		return $destFile;
	}

	/**
	 * Atomically creates a new,empty file named by this abstract pathname
	 * if and only if a file with this name does not yet exist.
	 *
	 * @return boolean It returns TRUE, if the file does not exist
	 *		and was successfully created, otherwise FALSE.
	 * @throws IOException if there is an I/O problem.
	 */
	public function createNewFile() {
		if ($this->exists()) {
			return FALSE;
		}
		if ($this->getParentFile() != NULL && !$this->getParentFile()->canWrite()) {
			throw new IOException("The file can not be created.", self::ERROR_SECURITY);
		}
		// Create a new file
		Tools::tryError();
		$file = fopen($this->getPath(), "w+");
		if  (Tools::catchError($msg)) {
			throw new IOException($msg, self::ERROR_OPEN);
		}
		// Close the file
		Tools::tryError();
		fclose($file);
		if  (Tools::catchError($msg)) {
			throw new IOException($msg, self::ERROR_OPEN);
		}
	}

	/**
	 * It deletes the file.
	 *
	 * @throws FileNotFoundException if the file does not exist.
	 * @throws IOException if there is an I/O problem.
	 */
	public function delete() {
		if (!$this->exists()) {
			throw new FileNotFoundException($this->path);
		}
		$parent = $this->getParentFile();
		if (!empty($parent) && !$parent->canWrite()) {
			throw new IOException("The file '".$this->getPath()."' cannot be deleted.", self::ERROR_SECURITY);
		}
		Tools::tryError();
		if ($this->isDirectory()) {
			rmdir($this->getPath());
		}
		else {
			unlink($this->getPath());
		}
		if  (Tools::catchError($msg)) {
			throw new IOException($msg, self::ERROR_GENERAL);
		}
	}

	/**
	 * It checks the file existence
	 *
	 * @return boolean It returns TRUE, if the file exists, otherwise FALSE.
	 */
	public function exists() {
		return file_exists($this->getPath());
	}

	/**
	 * It returns abosolute file system path to the file.
	 *
	 * @return string
	 * @throws FileNotFoundException if the file does not exist.
	 */
	public function getAbsolutePath() {
		if (!$this->exists()) {
			throw new FileNotFoundException($this->getPath());
		}
		return realpath($this->getPath());
	}

	/**
	 * It returns a file extension
	 *
	 * @return string|NULL
	 */
	public function getExtension() {
		if (empty($this->extension)) {
			$this->extension = pathinfo($this->getPath(), PATHINFO_EXTENSION);
		}
		return $this->extension;
	}

	/**
	 * It returns time of last modifying (Unix timestamp)
	 *
	 * @return int
	 * @throws FileNotFoundException if the file does not exist.
	 * @throws IOException if there is an I/O problem.
	 */
	public function getLastModified() {
		if (!$this->exists()) {
			throw new FileNotFoundException($this->getPath());
		}
		Tools::tryError();
		$time = filectime($this->getPath());
		if (empty($time)) {
			throw new IOException("There is a problem to get time when the file was last modified.", self::ERROR_GENERAL);
		}
		if  (Tools::catchError($msg)) {
			throw new IOException($msg, self::ERROR_GENERAL);
		}
		return $time;
	}

	/**
	 * It returns file name.
	 *
	 * @return string
	 */
	public function getName() {
		if (empty($this->name)) {
			$this->name = basename($this->path);
		}
		return $this->name;
	}

	/**
	 * Returns the abstract pathname of this abstract pathname's parent,
	 * or null if this pathname does not name a parent directory.
	 *
	 * @return File
	 * @throws IOException if the file has no parent file
	 */
	public function getParentFile() {
		if (empty($this->parent)) {
			$dirname = dirname($this->path);
			// FIXME: UNIX dependent
			if ($dirname != $this->path && $dirname != DIRECTORY_SEPARATOR) {
				$this->parent = new File($dirname);
			}
			else {
				throw new IOException("The file '".$this->getPath()."' has no parent file.", self::ERROR_GENERAL);
			}
		}
		return $this->parent;
	}

	/**
	 * It returns file path
	 *
	 * @return string
	 */
	public function getPath() {
		return $this->path;
	}

	/**
	 * It returns file size id bytes.
	 *
	 * @return int
	 * @throws FileNotFoundException if the file does not exist.
	 * @throws IOException if there is an I/O problem
	 */
	public function getSize() {
		if (!$this->exists()) {
			throw new FileNotFoundException($this->path);
		}
		Tools::tryError();
		$size = filesize($this->path);
		if (Tools::catchError($msg)) {
			throw new IOException($msg, self::ERROR_GENERAL);
		}
		return $size;
	}
	/**
	 * It returns file type.
	 *
	 * @return FileType
	 * @throws FileNotFoundException if the file does not exist.
	 * @throws IOException if there is a problem to get a file type
	 */
	public function getType() {
		if (empty($this->type)) {
			if (!$this->exists()) {
				throw new FileNotFoundException($this->path);
			}
			if (class_exists("finfo")) {
				$finfo = new finfo(FILEINFO_MIME,$this->getPath());
				if (!$fi) {
					throw new IOException("There is a problem to detect type of file '".$this->getPath()."'.", self::ERROR_FILE_INFO);
				}
				$mimeType = $info->file($this->getPath());
				$finfo->close();
			}
			elseif (function_exists("mime_content_type")) {
				$mimeType = mime_content_type($this->getPath());
			}
			else {
				throw new IOException("There is a problem to get content type of the file. Class 'finfo' and function 'mime_content_type' are not avaiable.");
			}
			$this->type = new FileType($mimeType);
		}
		return $this->type;
	}

	/**
	 * It checks if the file is directory.
	 *
	 * @return boolean
	 * @throws FileNotFoundException if the file does not exist.
	 */
	public function isDirectory() {
		if (!$this->exists()) {
			throw new FileNotFoundException($this->path);
		}
		return is_dir($this->getPath());
	}

	/**
	 * It checks if the file is file (not directory).
	 *
	 * @return boolean
	 * @throws FileNotFoundException if the file does not exist.
	 */
	public function isFile() {
		if (!$this->exists()) {
			throw new FileNotFoundException($this->path);
		}
		return is_file($this->getPath());
	}

	public function __toString() {
		return $this->getPath();
	}

	/**
	 * It returns files located in this directory.
	 *
	 * @param IFileFilter $filter The file filter.
	 * @return array|File
	 * @throws FileNotFoundException if the file does not exist.
	 * @throws NotSupportedException if this file is not a directory.
	 */
	public function listFiles(IFileFilter $filter = NULL) {
		if (!$this->exists()) {
			throw new FileNotFoundException($this->path);
		}
		if (!empty($filter) && ($filter instanceof FileNameFilter)) {
			$fileNameFilter = $filter;
		}
		else {
			$fileNameFilter = NULL;
		}
		$result = array();
		$files = $this->listPaths($fileNameFilter);
		foreach ($files AS $filename) {
			$file = new File($filename);
			if (!empty($filter) && !($filter instanceof FileNameFilter)) {
				if (!$filter->accepts($file)) {
					continue;
				}
			}
			$result[] = $file;
		}
		return $result;
	}

	/**
	 * It returns paths to the files located in this directory.
	 *
	 * @param FileNameFilter $filter The file name filter.
	 * @return array|string
	 * @throws FileNotFoundException if the file does not exist.
	 * @throws NotSupportedException if this file is not a directory.
	 */
	public function listPaths(FileNameFilter $filter = NULL) {
		if (!$this->isDirectory()) {
			throw new NotSupportedException();
		}
		if (!empty ($filter)) {
			$rule = $filter->getRule();
		}
		else {
			$rule = "*";
		}
		Tools::tryError();
		$list = glob($this->getPath() . DIRECTORY_SEPARATOR . $rule);
		if (Tools::catchError($msg)) {
			throw new IOException($msg, self::ERROR_GENERAL);
		}
		return $list;
	}

	/**
	 * It makes a directory describing by this abstract path, if it does not exist.
	 *
	 * @param string $access Access rights
	 * @return boolean It returns TRUE, if the directory was created, otherwise FALSE.
	 * @throws NullPointerException if the $access is empty
	 * @throws IOException if there is a problem to create directory.
	 */
	public function mkdir($access = "0777") {
		if (empty($access)) {
			throw new NullPointerException("access");
		}
		if ($this->exists()) {
			return FALSE;
		}
		if ($this->getParentFile() != NULL && !$this->getParentFile()->canWrite()) {
			throw new IOException("The directory can not be created.", self::ERROR_SECURITY);
		}
		Tools::tryError();
		// FIXME: The access is problem
		$check = mkdir($this->getPath()/*, $access*/);
		if (Tools::catchError($msg)) {
			throw new IOException($msg, self::ERROR_GENERAL);
		}
		return $check;
	}

	/**
	 * It makes a directory and all parent directories which do not already exist.
	 *
	 * @param string $access Access rights
	 * @return boolean It returns TRUE, if the directory was created, otherwise FALSE.
	 * @throws NullPointerException if the $access is empty
	 * @throws IOException if there is a problem to create directory.
	 */
	public function mkdirs($access = "0777") {
		if (empty($access)) {
			throw new NullPointerException("access");
		}
		if ($this->exists()) {
			return FALSE;
		}
		if ($this->parent != NULL && !$this->parent->canWrite()) {
			throw new IOException("The directory can not be created.", self::ERROR_SECURITY);
		}
		Tools::tryError();
		$check = mkdir($this->getPath(), $access, TRUE);
		if (Tools::catchError($msg)) {
			throw new IOException($msg, self::ERROR_GENERAL);
		}
		return $check;
	}
}

