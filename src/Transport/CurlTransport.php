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

namespace Ikarus\SPS\Transport;


class CurlTransport implements TransportInterface
{
	/** @var string */
	private $URL;
	/** @var float Timeout in seconds */
	private $timeout;

	/**
	 * CurlTransport constructor.
	 * @param string $URL
	 */
	public function __construct(string $URL, float $timeout = 1.0)
	{
		$this->URL = $URL;
		$this->timeout = $timeout;
	}


	/**
	 * @inheritDoc
	 */
	public function sendRequest(array $body): bool
	{
		$curl = curl_init($this->getURL());
		$opts = [
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_ENCODING => "",
			CURLOPT_MAXREDIRS => 10,
			CURLOPT_TIMEOUT_MS => $this->getTimeout() * 1000,
			CURLOPT_FOLLOWLOCATION => true,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST => 'POST',
			CURLOPT_POST => empty($body) ? 0 : 1,
			CURLOPT_POSTFIELDS => $body
		];
		curl_setopt_array($curl, $opts);

		$data = curl_exec($curl);
		curl_close($curl);
		return $data ? true : false;
	}

	/**
	 * @inheritDoc
	 */
	public function fetchRequest()
	{
		$curl = curl_init($this->getURL());
		$opts = [
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_ENCODING => "",
			CURLOPT_MAXREDIRS => 10,
			CURLOPT_TIMEOUT_MS => $this->getTimeout() * 1000,
			CURLOPT_FOLLOWLOCATION => true,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST => 'GET'
		];
		curl_setopt_array($curl, $opts);

		$data = curl_exec($curl);
		curl_close($curl);

		if($data)
			return json_decode($data, true);
		return NULL;
	}

	/**
	 * @return string
	 */
	public function getURL(): string
	{
		return $this->URL;
	}

	/**
	 * @return float
	 */
	public function getTimeout(): float
	{
		return $this->timeout;
	}
}