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


namespace daita\MySmallPhpTools\Traits\Nextcloud\nc21;


use daita\MySmallPhpTools\Db\Nextcloud\nc21\NC21Signatory;
use daita\MySmallPhpTools\Exceptions\SignatoryException;
use daita\MySmallPhpTools\Traits\TArrayTools;
use OC;
use OCP\IConfig;


/**
 * Trait TNC21LocalSignatory
 *
 * @package daita\MySmallPhpTools\Traits\Nextcloud\nc21
 */
trait TNC21LocalSignatory {


	use TArrayTools;
	use TNC21Setup;
	use TNC21Signatory;


	/**
	 * @param string $id
	 * @param bool $generate
	 *
	 * @return NC21Signatory
	 * @throws SignatoryException
	 */
	public function getLocalSignatory(string $id, bool $generate = false): NC21Signatory {
		$app = $this->setup('app');
		if ($app === '') {
			$app = 'signatories';
		}

		$signatories = json_decode(OC::$server->get(IConfig::class)->getAppValue($app, 'key_pairs'), true);
		if (!is_array($signatories)) {
			$signatories = [];
		}

		$signatory = new NC21Signatory($id);
		$sign = $this->getArray($id, $signatories);
		if (empty($sign)) {
			if (!$generate) {
				throw new SignatoryException();
			}

			$this->generateKeys($signatory);
			$signatories[$id] = [
				'publicKey'  => $signatory->getPublicKey(),
				'privateKey' => $signatory->getPrivateKey()
			];
			OC::$server->get(IConfig::class)->setAppValue($app, 'signatories', json_encode($signatories));

			return $signatory;
		}

		return $signatory->setPublicKey($this->get('publicKey', $sign))
						 ->setPrivateKey($this->get('privateKey', $sign));
	}

}

