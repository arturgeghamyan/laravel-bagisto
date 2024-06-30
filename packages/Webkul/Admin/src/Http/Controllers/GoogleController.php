<?php

namespace Webkul\Admin\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\View\View;
use Webkul\Core\Repositories\CoreConfigRepository;

class GoogleController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct(protected CoreConfigRepository $coreConfigRepository) {}

    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        return view('admin::google.index');
    }

    /**
     * Display a listing of the resource.
     */
    public function fetchData(): JsonResponse
    {
        $client = new \GuzzleHttp\Client();

        $result = $client->request('GET', 'https://www.googleapis.com/pagespeedonline/v5/runPagespeed?url=' . request()->query('query'));
        $result = json_decode($result->getBody()->getContents(), true);

        return new JsonResponse([
            'data' => $result,
        ]);
    }
}
