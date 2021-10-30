<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\Order as OrderResource;
use App\Models\Order;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;

class OrderController extends BaseController
{
    /**
     * @param $id
     * @return JsonResponse
     */
    public function show($id): JsonResponse
    {
        $order = Order::query()->find($id);

        if (is_null($order)):
            return $this->sendError('Order does not exist.');
        endif;

        return $this->sendResponse('Order fetched.', new OrderResource($order));
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        $input = $request->all();

        $validator = Validator::make($input, [
            'number' => 'required|string|unique:orders,number',
            'full_name' => 'required|string',
            'sum' => 'required|numeric',
            'address' => 'required|string',
        ]);

        if($validator->fails()):
            return $this->sendError($validator->errors());
        endif;

        $order = Order::query()->create($input);

        return $this->sendResponse('Order created successfully.', new OrderResource($order), Response::HTTP_CREATED);
    }

    /**
     * @param Request $request
     * @param Order $order
     * @return JsonResponse
     */
    public function update(Request $request, Order $order): JsonResponse
    {
        $input = $request->all();

        $validator = Validator::make($input, [
            'full_name' => 'required|string',
            'sum' => 'required|numeric',
            'address' => 'required|string',
        ]);

        if($validator->fails()):
            return $this->sendError($validator->errors());
        endif;

        $order->full_name = $input['full_name'];
        $order->sum = $input['sum'];
        $order->address = $input['address'];
        $order->save();

        return $this->sendResponse('Order updated successfully.', new OrderResource($order), Response::HTTP_ACCEPTED);
    }
}
