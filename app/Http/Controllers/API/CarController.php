<?php

namespace App\Http\Controllers\Api;

use App\Models\Car;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Http\Requests\Api\Car\StoreCarRequest;


class CarController extends ApiController
{
    /**
     * @OA\Get(
     *     path="/api/car",
     *     summary="View list car",
     *     tags={"Car"},
     *     @OA\Parameter(
     *         description="limit. Default 5",
     *         in="query",
     *         name="limit",
     *         required=false,
     *         @OA\Schema(type="string"),
     *         @OA\Examples(example="int", value="1", summary="An int value."),
     *     ),
     *     @OA\Parameter(
     *         description="offset. Default 0",
     *         in="query",
     *         name="offset",
     *         required=false,
     *         @OA\Schema(type="string"),
     *         @OA\Examples(example="int", value="1", summary="An int value."),
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="OK",
     *         @OA\JsonContent(
     *           @OA\Property(
     *             property="success",
     *             type="string",
     *             example="true",
     *           ),
     *           @OA\Property(
     *             property="data",
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/Car")
     *           )
     *          )
     *        )
     *     )
     * )
     *
     * 
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $limit = $request->get('limit', 5);
        $offset = $request->get('offset', 0);
        return $this->sendResponse(Car::limit($limit)->offset($offset)->get());
    }

    /**
     * @OA\Post(
     *     path="/api/car",
     *     summary="Create car",
     *     tags={"Car"},
     *     @OA\Parameter(
     *         description="Model name",
     *         in="query",
     *         name="model",
     *         required=true,
     *         @OA\Schema(type="string"),
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="OK",
     *         @OA\JsonContent(
     *           @OA\Property(
     *             property="success",
     *             type="string",
     *             example="true",
     *           ),
     *           @OA\Property(
     *             property="data",
     *             type="object",
     *             ref="#/components/schemas/Car",
     *           )
     *          )
     *        )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="error",
     *         @OA\JsonContent(
     *           @OA\Property(
     *             property="success",
     *             type="string",
     *             example="false",
     *           ),
     *           @OA\Property(
     *             property="message",
     *             type="string",
     *             example="The user already has a car",
     *           ),
     *          )
     *        )
     *     )
     * )
     * 
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreCarRequest $request)
    {
        $validated = $request->validated();

        if ($request->user()->car !== Null) {
            return $this->sendError(trans('validation.attributes.car_already'), 400);
        }

        $car = $request->user()->car()->create($validated);

        return $this->sendResponse($car);
    }

    /**
     * @OA\Get(
     *     path="/api/car/my",
     *     summary="View my car",
     *     tags={"Car"},
     *     @OA\Response(
     *         response=200,
     *         description="OK",
     *         @OA\JsonContent(
     *           @OA\Property(
     *             property="success",
     *             type="string",
     *             example="true",
     *           ),
     *           @OA\Property(
     *             property="data",
     *             type="object",
     *             ref="#/components/schemas/Car",
     *           )
     *          )
     *        )
     *     )
     * )
     *
     * Display the specified resource.
     *
     * @param  \App\Models\Car  $car
     * @return \Illuminate\Http\Response
     */
    public function my(Request $request)
    {
        $car = $request->user()->car;
        if ($car == Null) {
            return $this->sendError(trans('validation.attributes.car_null'), 404);
        }

        return $this->sendResponse($car);
    }

    /**
     * @OA\Get(
     *     path="/api/car/{id}",
     *     summary="Show car by id",
     *     tags={"Car"},
     *     @OA\Parameter(
     *         description="Id car",
     *         in="path",
     *         name="id",
     *         required=true,
     *         @OA\Schema(type="string"),
     *         @OA\Examples(example="int", value="1", summary="An int value."),
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="OK",
     *         @OA\JsonContent(
     *           @OA\Property(
     *             property="success",
     *             type="string",
     *             example="true",
     *           ),
     *           @OA\Property(
     *             property="data",
     *             type="object",
     *             ref="#/components/schemas/Car",
     *           )
     *          )
     *        )
     *     )
     * )
     *
     * Display the specified resource.
     *
     * @param  \App\Models\Car  $car
     * @return \Illuminate\Http\Response
     */
    public function show(Car $car)
    {
        return $this->sendResponse($car);
    }

    /**
     * @OA\Put(
     *     path="/api/car/{id}",
     *     summary="Updates car",
     *     tags={"Car"},
     *     @OA\Parameter(
     *         description="Id car",
     *         in="path",
     *         name="id",
     *         required=true,
     *         @OA\Schema(type="string"),
     *         @OA\Examples(example="int", value="1", summary="An int value."),
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="OK",
     *         @OA\JsonContent(
     *           @OA\Property(
     *             property="success",
     *             type="string",
     *             example="true",
     *           ),
     *           @OA\Property(
     *             property="data",
     *             type="object",
     *             ref="#/components/schemas/Car",
     *           )
     *          )
     *        )
     *     )
     * )
     *
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Car  $car
     * @return \Illuminate\Http\Response
     */
    public function update(StoreCarRequest $request, Car $car)
    {
        $validated = $request->validated();
        $car->update($validated);
        return $this->sendResponse($car);
    }

    /**
     * @OA\Delete(
     *     path="/api/car/{id}",
     *     summary="Delete car",
     *     tags={"Car"},
     *     @OA\Parameter(
     *         description="Id car",
     *         in="path",
     *         name="id",
     *         required=true,
     *         @OA\Schema(type="string"),
     *         @OA\Examples(example="int", value="1", summary="An int value."),
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="OK",
     *         @OA\JsonContent(
     *           @OA\Property(
     *             property="success",
     *             type="string",
     *             example="true",
     *           ),
     *           @OA\Property(
     *             property="data",
     *             type="string",
     *             example="true",
     *           )
     *        )
     *     )
     * )
     * 
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Car  $car
     * @return \Illuminate\Http\Response
     */
    public function destroy(Car $car)
    {
        $car->delete();
        return $this->sendResponse(true);
    }
}
