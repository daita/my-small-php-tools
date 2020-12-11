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


namespace daita\MySmallPhpTools\Model\Nextcloud\nc21;


use daita\MySmallPhpTools\Traits\TArrayTools;
use JsonSerializable;


/**
 * Class NC21NodeInfo
 *
 * @package daita\MySmallPhpTools\Model\Nextcloud\nc21
 */
class NC21Webfinger implements JsonSerializable {


	use TArrayTools;


	/** @var string */
	private $subject = '';

	/** @var array */
	private $aliases = [];

	/** @var array */
	private $properties = [];

	/** @var array */
	private $links = [];


	/**
	 * NC21Webfinger constructor.
	 *
	 * @param array $json
	 */
	public function __construct(array $json) {
		$this->setSubject($this->get('subject', $json));
		$this->setAliases($this->getArray('subject', $json));
		$this->setProperties($this->getArray('properties', $json));
		$this->setLinks($this->getArray('links', $json));
	}


	/**
	 * @return string
	 */
	public function getSubject(): string {
		return $this->subject;
	}

	/**
	 * @param string $subject
	 *
	 * @return self
	 */
	public function setSubject(string $subject): self {
		$this->subject = $subject;

		return $this;
	}


	/**
	 * @return array
	 */
	public function getAliases(): array {
		return $this->aliases;
	}

	/**
	 * @param array $aliases
	 *
	 * @return self
	 */
	public function setAliases(array $aliases): self {
		$this->aliases = $aliases;

		return $this;
	}


	/**
	 * @return array
	 */
	public function getProperties(): array {
		return $this->properties;
	}

	/**
	 * @param array $properties
	 *
	 * @return self
	 */
	public function setProperties(array $properties): self {
		$this->properties = $properties;

		return $this;
	}


	/**
	 * @return array
	 */
	public function getLinks(): array {
		return $this->links;
	}

	/**
	 * @param array $links
	 *
	 * @return NC21Webfinger
	 */
	public function setLinks(array $links): self {
		$this->links = $links;

		return $this;
	}


	/**
	 * @return array
	 */
	public function jsonSerialize(): array {
		return [
			'subject'    => $this->getSubject(),
			'aliases'    => $this->getAliases(),
			'properties' => $this->getProperties(),
			'links'      => $this->getLinks()
		];
	}

}

