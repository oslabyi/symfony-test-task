<?php

namespace App\Services;


interface IFileStoreService
{
	public function uploadFile($file): string;
}