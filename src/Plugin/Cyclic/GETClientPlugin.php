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

class GETClientPlugin extends AbstractCyclesDependentPlugin
{
	/** @var TransportInterface */
	private $transport;
	/** @var callable */
	private $responseHandler;

	/**
	 * GETClientPlugin constructor.
	 * @param TransportInterface $transport
	 * @param callable $responseHandler
	 * @param int $cycleInterval
	 * @param string|null $identifier
	 */
	public function __construct(TransportInterface $transport, callable $responseHandler, int $cycleInterval = 1, string $identifier = NULL)
	{
		parent::__construct($cycleInterval, $identifier);
		$this->transport = $transport;
		$this->responseHandler = $responseHandler;
	}

	/**
	 * @return callable
	 */
	public function getResponseHandler(): callable
	{
		return $this->responseHandler;
	}

	/**
	 * @return TransportInterface
	 */
	public function getTransport(): TransportInterface
	{
		return $this->transport;
	}


	/**
	 * Called every time the client could fetch data from server
	 *
	 * @param $GET
	 * @param CyclicPluginManagementInterface $pluginManagement
	 */
	protected function getWasSuccessful(&$GET, CyclicPluginManagementInterface $pluginManagement) {
	}

	/**
	 * Called if the client was not able to fetch the data from the remote server.
	 *
	 * @param CyclicPluginManagementInterface $pluginManagement
	 */
	protected function getDidFail(CyclicPluginManagementInterface $pluginManagement) {
	}



	protected function updateInterval(CyclicPluginManagementInterface $pluginManagement)
	{
		if($data = $this->getTransport()->fetchRequest()) {
			$this->getWasSuccessful($data, $pluginManagement);
			call_user_func($this->getResponseHandler(), $data, $pluginManagement);
		} else
			$this->getDidFail($pluginManagement);
	}
}