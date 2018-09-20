<?php

namespace App\Services;


interface IDataService
{
	public function sortBy(array $data, string $column, string $order = 'ASC'): array;

	public function formatPositions(array $data): array;

	public function formatToXml(array $data);
}