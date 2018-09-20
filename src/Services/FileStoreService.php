<?php

namespace App\Services;

use App\Helpers\TableHelper;
use League\Csv\Reader;

class FileStoreService implements IFileStoreService
{

	/**
	 * @param $file
	 * @return string
	 * @throws \Exception
	 */
	public function uploadFile($file): string
	{
		$csv = Reader::createFromPath($file['tmp_name'], 'r');
		$csv->setDelimiter(';');
		$records = $csv->fetchAll();

		$koef = TableHelper::getEventKoefTable();

		foreach ($records as $record) {
			$item = [];
			$item['data'] = [];
			$item['total_score'] = 0;

			$cols = count($record) - 1;
			for ($i = 0; $i <= $cols; $i++) {
				if ($i == 0) {
					$item['name'] = $record[$i];
				} else {
					$event = $koef[$i - 1];
					if ($i == $cols) {
						list($min, $sec, $milisec) = explode('.', $record[$i]);
						$p = (int)$min * 60 + (int)$sec + (int)$milisec / 100;
					} else {
						$p = $record[$i];
					}

					$item['data'][$event['name']] =
						[
							'score' => ceil($event['a'] * pow(abs($p - $event['b']), $event['c'])),
							'res' => $record[$i]
						];
					$item[$event['name']] = ceil($event['a'] * pow(abs($p - $event['b']), $event['c']));
					$item['total_score'] += $item['data'][$event['name']]['score'];
				}
			}
			$list[] = $item;
		}

		if (!is_dir('../public/temp/')) {
			mkdir('../public/temp/', 0777);
		}
		$filename = md5(md5(random_bytes(8)));
		file_put_contents('../public/temp/' . $filename . '.json', json_encode($list));

		return $filename;

	}


	public function loadData($filename)
	{
		if (is_file('../public/temp/' . $filename . '.json')) {
			$data = file_get_contents('../public/temp/' . $filename . '.json');
			return json_decode($data, true);
		}

		return [];
	}

}