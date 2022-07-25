<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use League\Fractal\Manager;
use League\Fractal\Resource\Collection;
use League\Fractal\Resource\Item;

abstract class APIController extends Controller
{
    /**
     * @OA\Info(
     *      version="1.0.0",
     *      title="Huiswerk App API Documentation",
     *      description="API documentation",
     *      @OA\Contact(
     *          email="test@example.com"
     *      ),
     *      @OA\License(
     *          name="Apache 2.0",
     *          url="http://www.apache.org/licenses/LICENSE-2.0.html"
     *      )
     * )
     *
     * @OA\Server(
     *      url=L5_SWAGGER_CONST_HOST,
     *      description="Documentation page"
     * )
     */

    protected $manager;
    protected $defaultTransformer;
    protected function __construct()
    {
        $this->manager = new Manager();
    }

    protected function multiple($collection)
    {
        $export = new Collection($collection, new $this->defaultTransformer);

        return $this->manager->createData($export)->toArray();
    }

    protected function single($model)
    {
        $export = new Item($model, new $this->defaultTransformer);

        return $this->manager->createData($export);
    }

    protected function success($message, $status_code)
    {
        return response()->json(
            [
                'data' => [
                    'success' => $message
                ]
            ],
            $status_code
        );
    }

    protected function error($reason, $status_code)
    {
        return response()->json(
            [
                'data' => [
                    'error' => $reason
                ]
            ],
            $status_code
        );
    }
}
