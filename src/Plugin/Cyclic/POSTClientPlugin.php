<?php
/**
 * Copyright (c) 2019 TASoft Applications, Th. Abplanalp <info@tasoft.ch>
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in all
 * copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
 * SOFTWARE.
 */

namespace Ikarus\SPS\Plugin\Cyclic;


use Ikarus\SPS\Plugin\Management\CyclicPluginManagementInterface;
use Ikarus\SPS\Transport\TransportInterface;
use TASoft\Util\ValueStorage;

class POSTClientPlugin extends AbstractCyclesDependentPlugin
{
	/** @var TransportInterface */
	private $transport;
	/** @var ValueStorage|null */
	private $valueStorage;

	/**
	 * POSTClientPlugin constructor.
	 * @param TransportInterface $transport
	 * @param ValueStorage|null $storage
	 * @param int $cycleInterval
	 * @param string|null $identifier
	 */
	public function __construct(TransportInterface $transport, ValueStorage $storage = NULL, int $cycleInterval = 1, string $identifier = NULL)
	{
		parent::__construct($cycleInterval, $identifier);
		$this->transport = $transport;
		$this->valueStorage = $storage;
	}


	/**
	 * @inheritDoc
	 */
	public function updateInterval(CyclicPluginManagementInterface $pluginManagement)
	{
		if($vs = $this->getValueStorage()) {
			$POST = [];
			foreach($vs as $key => $value) {
				$POST[$key] = $value;
			}

			$this->postWillSend($POST, $pluginManagement);
			if($this->getTransport()->sendRequest($POST))
				$this->postWasSuccessful($POST, $pluginManagement);
			else
				$this->postDidFail($POST, $pluginManagement);
		}
	}

	/**
	 * Called right before sending the post
	 *
	 * @param $POST
	 * @param CyclicPluginManagementInterface $pluginManagement
	 */
	protected function postWillSend(&$POST, CyclicPluginManagementInterface $pluginManagement) {
	}

	/**
	 * Called every time the client could post data
	 *
	 * @param $POST
	 * @param CyclicPluginManagementInterface $pluginManagement
	 */
	protected function postWasSuccessful($POST, CyclicPluginManagementInterface $pluginManagement) {
	}

	/**
	 * Called if the client was not able to post the data to the remote server.
	 *
	 * @param $POST
	 * @param CyclicPluginManagementInterface $pluginManagement
	 */
	protected function postDidFail($POST, CyclicPluginManagementInterface $pluginManagement) {
	}

	/**
	 * @return ValueStorage|null
	 */
	public function getValueStorage(): ValueStorage
	{
		return $this->valueStorage;
	}

	/**
	 * @param ValueStorage|null $valueStorage
	 * @return static
	 */
	public function setValueStorage(ValueStorage $valueStorage)
	{
		$this->valueStorage = $valueStorage;
		return $this;
	}

	/**
	 * @return TransportInterface
	 */
	public function getTransport(): TransportInterface
	{
		return $this->transport;
	}
}