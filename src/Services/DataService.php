<?php

namespace App\Services;

use App\Helpers\TableHelper;
use DOMDocument;

class DataService implements IDataService
{

	const SORT = [
		'asc' => SORT_ASC,
		'desc' => SORT_DESC
	];

	/**
	 * @param array $list
	 * @param string $column
	 * @param string $order
	 * @return array list of sorted data
	 */
	public function sortBy(array $list, string $column, string $order = 'ASC'): array
	{
		$order = self::SORT[strtolower($order)] ?: SORT_ASC;
		$column = in_array($column, TableHelper::getColumnsList()) ? $column : 'total_score';
		array_multisort(array_column($list, $column), $order, array_column($list, 'name'), SORT_ASC, $list);

		return $list;
	}

	/**
	 * @param array $data
	 * @return array
	 */
	public function formatPositions(array $data): array
	{
		$data = $this->sortBy($data, 'total_score', 'desc');
		$i = 0;
		$prev = null;
		$total_positions = [];

		foreach ($data as &$row) {
			if ($prev == $row['total_score']) {

			} else {
				$prev = $row['total_score'];
				$i++;
			}
			$row['position'] = $i;
			if (isset($total_positions[$i])) {
				$total_positions[$i] = $total_positions[$i] + 1;
			} else {
				$total_positions[$i] = 1;
			}
		}

		$data = $this->formatDisplayedPositions($data, $total_positions);

		return $data;
	}


	/**
	 * @param array $list data for formatting
	 * @param array $total_positions
	 * @return array
	 */
	private function formatDisplayedPositions(array $list, array $total_positions): array
	{

		$temp = [];
		$next = 1;
		$ss = [];

		foreach ($list as &$row) {
			if (in_array($row['position'], $ss)) {
				$next++;
				continue;
			}
			$ss[] = $row['position'];

			$temp[$next] = $row['position'];
			$next++;

		}

		$temp = array_flip($temp);

		foreach ($list as &$row) {
			$pos = $row['position'];
			if ($total_positions[$pos] == 1) {
				$row['format_pos'] = $temp[$pos];
			} else {
				$row['format_pos'] = $temp[$pos] . '-' . ($temp[$pos] + $total_positions[$pos] - 1);
			}
		}

		return $list;

	}


	/**s
	 * @param array $data
	 * @return DOMDocument
	 */
	public function formatToXml(array $data)
	{
		$domtree = new DOMDocument('1.0', 'UTF-8');

		$xmlRoot = $domtree->createElement("Athletes");
		$xmlRoot = $domtree->appendChild($xmlRoot);

		foreach ($data as $person) {

			$athlete = $domtree->createElement("Athlete");
			$athlete = $xmlRoot->appendChild($athlete);

			$athlete->appendChild($domtree->createElement('Name', $person['name']));
			$athlete->appendChild($domtree->createElement('Total', $person['total_score']));
			$athlete->appendChild($domtree->createElement('Place', $person['format_pos']));

			$events = $domtree->createElement("Events");
			$events = $athlete->appendChild($events);

			$p['Results'] = [];

			foreach ($person['data'] as $e => $d) {

				$event = $domtree->createElement("Event");
				$event = $events->appendChild($event);

				$event->appendChild($domtree->createElement('Event', $e));
				$event->appendChild($domtree->createElement('Score', $d['score']));
				$event->appendChild($domtree->createElement('Performance', $d['res']));
			}
		}

		return $domtree;
	}
}