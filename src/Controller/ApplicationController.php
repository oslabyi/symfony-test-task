<?php

namespace App\Controller;


use App\Helpers\TableHelper;
use App\Services\DataService;
use App\Services\FileStoreService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;



class ApplicationController extends AbstractController
{

	private $fileStoreService;
	private $dataService;

	public function __construct(FileStoreService $fileStoreService, DataService $dataService)
	{
		$this->fileStoreService = $fileStoreService;
		$this->dataService = $dataService;
	}

	/**
	 * @Route("/", name="homepage")
	 */
	public function index(Request $request)
	{

		$data = [];
		$filename = '';
		$originalFile = '';
		$order_column = $request->get('sort_column') ?: 'total_score';

		if ($request->isMethod('POST')) {

			if (!empty($_FILES['csv']['tmp_name'])) {
				$filename = $this->fileStoreService->uploadFile($_FILES['csv']);
				$originalFile = $_FILES['csv']['name'];
				$order_column = 'total_score';

			} else {
				$filename = $request->get('saved_file');
				$originalFile = $request->get('original_file');
			}

			if ($filename) {
				$data = $this->fileStoreService->loadData($filename);

				$data = $this->dataService->formatPositions($data);
				$data = $this->dataService->sortBy($data, $order_column, $request->get('sort_order') ?: 'desc');

			}

		}

		return $this->render('application/index.html.twig', [
			'list' => $data,
			'filename' => $filename,
			'original_file' => $originalFile,
			'sort' => $request->get('sort_order') ?: 'desc',
			'column' => $order_column,
			'sort_columns' => TableHelper::getColumnsList()
		]);
	}

	/**
	 * @Route("/getXml", name="xml")
	 */
	public function xml(Request $request)
	{

        if(empty($request->get('filename'))){
            return $this->redirect('/');
        }

		$data = $this->fileStoreService->loadData($request->get('filename'));
		$data = $this->dataService->formatPositions($data);

		$domtree = $this->dataService->formatToXml($data);

		$response = new Response($domtree->saveXML());
		$response->headers->set('Content-Type', 'xml');
		$response->headers->set("Content-Disposition", "attachment; filename=test.xml;");

		return $response;
	}


}