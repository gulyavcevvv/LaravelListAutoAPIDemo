<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Route;

/**
 * @OA\Info(
 *      version="1.0.0",
 *      title="Laravel list car api demo",
 *      @OA\Contact(
 *          email="gulyavcevvv@gmail.com"
 *      ),
 *      @OA\License(
 *          name="Apache 2.0",
 *          url="http://www.apache.org/licenses/LICENSE-2.0.html"
 *      )
 * )
 *
 * @OA\Server(
 *      url=L5_SWAGGER_CONST_HOST,
 *      description="Demo list car api Server"
 * )
 * @OA\PathItem(path="/api")

 * @OA\Tag(
 *     name="Car",
 *     description="API Endpoints of car"
 * )
 * @OA\Tag(
 *     name="User",
 *     description="API Endpoints of user"
 * )
 *
 */
class ApiController extends Controller
{

    /**
     * Method not support
     *
     * @return Response
     */
    public function notSupport()
    {
        return $this->sendError('method not support');
    }

    /**
     * success response method.
     *
     * @param $result
     * @return Response
     */
    public function sendResponse($result)
    {
        $response = [
            'success' => true,
            'data'    => $result,
        ];
        return response()->json($response, 200);
    }

    /**
     * return error response.
     *
     * @param $error
     * @param $errorMessages
     * @param int $code
     * @return Response
     */
    public function sendError($error, $code = 404)
    {
        $response = [
            'success' => false,
            'message' => $error,
        ];
        return response()->json($response, $code);
    }
}
