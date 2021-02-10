<?php declare(strict_types=1);


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


namespace daita\MySmallPhpTools\Model;


use daita\MySmallPhpTools\Exceptions\InvalidItemException;
use daita\MySmallPhpTools\Exceptions\ItemNotFoundException;
use daita\MySmallPhpTools\Exceptions\MalformedArrayException;
use daita\MySmallPhpTools\IDeserializable;
use daita\MySmallPhpTools\Traits\TArrayTools;
use Exception;
use JsonSerializable;


/**
 * Class SimpleDataStore
 *
 * @package daita\MySmallPhpTools\Model
 */
class SimpleDataStore implements JsonSerializable {


	use TArrayTools;


	/** @var array */
	private $data = [];


	/**
	 * SimpleDataStore constructor.
	 *
	 * @param array $data
	 */
	public function __construct(array $data = []) {
		$this->data = $data;
	}


	/**
	 * @param string $key
	 * @param string $value
	 *
	 * @return SimpleDataStore
	 */
	public function s(string $key, string $value): self {
		$this->data[$key] = $value;

		return $this;
	}

	/**
	 * @param string $key
	 *
	 * @return string
	 */
	public function g(string $key): string {
		return $this->get($key, $this->data);
	}

	/**
	 * @param string $key
	 * @param string $value
	 *
	 * @return SimpleDataStore
	 */
	public function a(string $key, string $value): self {
		if (!array_key_exists($key, $this->data)) {
			$this->data[$key] = [];
		}

		$this->data[$key][] = $value;

		return $this;
	}


	/**
	 * @param string $key
	 * @param int $value
	 *
	 * @return SimpleDataStore
	 */
	public function sInt(string $key, int $value): self {
		$this->data[$key] = $value;

		return $this;
	}

	/**
	 * @param string $key
	 *
	 * @return int
	 */
	public function gInt(string $key): int {
		return $this->getInt($key, $this->data);
	}

	/**
	 * @param string $key
	 * @param int $value
	 *
	 * @return SimpleDataStore
	 */
	public function aInt(string $key, int $value): self {
		if (!array_key_exists($key, $this->data)) {
			$this->data[$key] = [];
		}

		$this->data[$key][] = $value;

		return $this;
	}


	/**
	 * @param string $key
	 * @param bool $value
	 *
	 * @return SimpleDataStore
	 */
	public function sBool(string $key, bool $value): self {
		$this->data[$key] = $value;

		return $this;
	}

	/**
	 * @param string $key
	 *
	 * @return bool
	 */
	public function gBool(string $key): bool {
		return $this->getBool($key, $this->data);
	}

	/**
	 * @param string $key
	 * @param bool $value
	 *
	 * @return SimpleDataStore
	 */
	public function aBool(string $key, bool $value): self {
		if (!array_key_exists($key, $this->data)) {
			$this->data[$key] = [];
		}

		$this->data[$key][] = $value;

		return $this;
	}


	/**
	 * @param string $key
	 * @param array $values
	 *
	 * @return SimpleDataStore
	 */
	public function sArray(string $key, array $values): self {
		$this->data[$key] = $values;

		return $this;
	}

	/**
	 * @param string $key
	 *
	 * @return array
	 */
	public function gArray(string $key): array {
		return $this->getArray($key, $this->data);
	}

	/**
	 * @param string $key
	 * @param array $values
	 *
	 * @return SimpleDataStore
	 */
	public function aArray(string $key, array $values): self {
		if (!array_key_exists($key, $this->data)) {
			$this->data[$key] = [];
		}

		$this->data[$key] = array_merge($this->data[$key], $values);

		return $this;
	}


	/**
	 * @param string $key
	 * @param JsonSerializable $value
	 *
	 * @return SimpleDataStore
	 */
	public function sObj(string $key, JsonSerializable $value): self {
		$this->data[$key] = $value;

		return $this;
	}

	/**
	 * @param string $key
	 * @param string $class
	 *
	 * @return JsonSerializable
	 * @throws InvalidItemException
	 */
	public function gObj(string $key, string $class = ''): JsonSerializable {
		try {
			$ret = $this->getObj($key, $this->data);
			if (is_array($ret) && $class !== '') {
				$item = new $class();
				if (!$item instanceof IDeserializable) {
					throw new Exception();
				}
				$item->import($ret);
				$ret = $item;
			}
		} catch (Exception $e) {
			throw new InvalidItemException();
		}

		return $ret;
	}

	/**
	 * @param string $key
	 * @param JsonSerializable $value
	 *
	 * @return SimpleDataStore
	 */
	public function aObj(string $key, JsonSerializable $value): self {
		if (!array_key_exists($key, $this->data)) {
			$this->data[$key] = [];
		}

		$this->data[$key][] = $value;

		return $this;
	}


	/**
	 * @param string $key
	 *
	 * @return mixed
	 * @throws ItemNotFoundException
	 */
	public function gItem(string $key) {
		if (!array_key_exists($key, $this->data)) {
			throw new ItemNotFoundException();
		}

		return $this->data[$key];
	}


	/**
	 * @return array
	 */
	public function gAll(): array {
		return $this->data;
	}

	/**
	 * @param array $data
	 *
	 * @return SimpleDataStore
	 */
	public function sAll(array $data): self {
		$this->data = $data;

		return $this;
	}


	public function keys(): array {
		return array_keys($this->data);
	}

	/**
	 * @param string $key
	 *
	 * @return bool
	 */
	public function haveKey(string $key): bool {
		return (array_key_exists($key, $this->data));
	}

	/**
	 * @param array $keys
	 *
	 * @param bool $must
	 *
	 * @return bool
	 * @throws MalformedArrayException
	 */
	public function haveKeys(array $keys, bool $must = false): bool {
		foreach ($keys as $key) {
			if (!$this->haveKey($key)) {
				if ($must) {
					throw new MalformedArrayException($key . ' missing in ' . json_encode($this->keys()));
				}

				return false;
			}
		}

		return true;
	}


	/**
	 * @return array
	 */
	public function jsonSerialize(): array {
		return $this->data;
	}

}

