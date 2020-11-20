<?php
declare(strict_types=1);


/**
 * Some tools for myself.
 *
 * This file is licensed under the Affero General Public License version 3 or
 * later. See the COPYING file.
 *
 * @author Maxence Lange <maxence@artificial-owl.com>
 * @copyright 2020, Maxence Lange <maxence@artificial-owl.com>
 * @license GNU AGPL version 3 or any later version
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as
 * published by the Free Software Foundation, either version 3 of the
 * License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 */


namespace daita\MySmallPhpTools\Traits\Nextcloud\nc20;


use Exception;
use OC;
use OC\HintException;
use Psr\Log\LoggerInterface;


/**
 * Trait TNC20Logger
 *
 * @package daita\MySmallPhpTools\Traits\Nextcloud\nc20
 */
trait TNC20Logger {


	use TNC20Setup;


	static $EMERGENCY = 4;
	static $ALERT = 3;
	static $CRITICAL = 3;
	static $ERROR = 3;
	static $WARNING = 2;
	static $NOTICE = 1;
	static $INFO = 1;
	static $DEBUG = 0;


	/**
	 * @param Exception $e
	 * @param int $level
	 */
	public function exception(Exception $e, int $level = 3): void {
		$this->logger()
			 ->log(
				 $level,
				 $e->getMessage(),
				 [
					 'app'       => $this->setup('app'),
					 'exception' => $e
				 ]
			 );
	}


	/**
	 * @param string $message
	 * @param bool $trace
	 * @param array $serializable
	 */
	public function emergency(string $message, bool $trace = false, array $serializable = []): void {
		$this->log(self::$EMERGENCY, $message, $trace, $serializable);
	}

	/**
	 * @param string $message
	 * @param bool $trace
	 * @param array $serializable
	 */
	public function alert(string $message, bool $trace = false, array $serializable = []): void {
		$this->log(self::$ALERT, $message, $trace, $serializable);
	}

	/**
	 * @param string $message
	 * @param bool $trace
	 * @param array $serializable
	 */
	public function warning(string $message, bool $trace = false, array $serializable = []): void {
		$this->log(self::$WARNING, $message, $trace, $serializable);
	}

	/**
	 * @param string $message
	 * @param bool $trace
	 * @param array $serializable
	 */
	public function notice(string $message, bool $trace = false, array $serializable = []): void {
		$this->log(self::$NOTICE, $message, $trace, $serializable);
	}

	/**
	 * @param string $message
	 * @param bool $trace
	 * @param array $serializable
	 */
	public function debug(string $message, bool $trace = false, array $serializable = []): void {
		$this->log(self::$DEBUG, $message, $trace, $serializable);
	}


	/**
	 * @param int $level
	 * @param string $message
	 * @param bool $trace
	 * @param array $serializable
	 */
	public function log(int $level, string $message, bool $trace = false, array $serializable = []): void {
		$opts = ['app' => $this->setup('app')];
		if ($trace) {
			$opts['exception'] = new HintException($message, json_encode($serializable));
		}

		$this->logger()
			 ->log($level, $message, $opts);
	}


	/**
	 * @return LoggerInterface
	 */
	public function logger(): LoggerInterface {
		if (isset($this->logger) && $this->logger instanceof LoggerInterface) {
			return $this->logger;
		} else {
			return OC::$server->get(LoggerInterface::class);
		}
	}

}

