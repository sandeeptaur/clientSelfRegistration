<?php

namespace App\Traits;

use Exception;
use Illuminate\Http\Response;
use BadMethodCallException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Illuminate\Validation\ValidationException;
use Symfony\Component\ErrorHandler\Error\ClassNotFoundError;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Prophecy\Doubler\Generator\Node\ClassNode;

trait ExceptionTrait
{
    public function success($message = null, $httpCode = Response::HTTP_OK, $result = null)
    {
        $response = [
            'success' => true,
            'data' => $result,
            'message' => $message,
        ];
        return response()->json($response, $httpCode);
    }

    public function validationError($validationErrors)
    {

    $errors=[];
    foreach (array_map('current', $validationErrors->toArray()) as $key => $value) {
        $nvalue= str_replace('.', ' ', $value);

         Arr::set($errors, $key, $nvalue);
       }

        $response = [
            'success' => false,
            'errors' => $errors,
        ];
        return response()->json($response, Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    public function errors($errors = null, $httpCode = Response::HTTP_INTERNAL_SERVER_ERROR)
    {
        $response = [
            'success' => false,
            'errors' => ['message' => $errors],
        ];
        return response()->json($response, $httpCode);
    }

    public function exceptionError($request, Exception $exception)
    {
        switch ($exception) {
            case $exception instanceof ClassNotFoundError:
                return $this->errors($exception->getMessage(), Response::HTTP_NOT_FOUND);
                break;

            case $exception instanceof MethodNotAllowedHttpException:
                return $this->errors("Method is not supported for this route");
                break;
            case $exception instanceof ModelNotFoundException:
                return $this->errors("Model does not exists", Response::HTTP_NOT_FOUND);
                break;
            case $exception instanceof NotFoundHttpException:
                return $this->errors("Resource url not found", Response::HTTP_NOT_FOUND);
                break;
            case $exception instanceof BadMethodCallException:
                return $this->errors($exception->getMessage(), Response::HTTP_NOT_FOUND);
                break;
            case $exception instanceof ValidationException:
                return $this->validationError($exception->validator->getMessageBag(), Response::HTTP_UNPROCESSABLE_ENTITY);
                break;
            default:
            return $this->errors($exception->validator->getMessageBag(), Response::HTTP_INTERNAL_SERVER_ERROR);
            // return $this->errors($exception->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
