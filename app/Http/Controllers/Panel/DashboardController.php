<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Api\ApiBaseController;
use App\Services\DashboardService;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;

class DashboardController extends ApiBaseController
{

    private $service;
    private $label;

    public function __construct(DashboardService $service)
    {

        $this->service = $service;
        $this->label = 'Painel de Controle';
    }

    public function dashboard(): View
    {

        return view('panel.home.dashboard');
    }

    public function reportSalesByDay(): JsonResponse
    {

        try {

            return $this->sendResponse($this->service->salesByDay());

        } catch (Exception $e) {

            return $this->sendError('Server Error.', $e);

        }
    }

    public function reportUserRegistrationByDay(): JsonResponse
    {

        try {

            return $this->sendResponse($this->service->usersRegistrationDay());

        } catch (Exception $e) {

            return $this->sendError('Server Error.', $e);

        }
    }

    public function reportSalesByCategory(): JsonResponse
    {

        try {

            return $this->sendResponse($this->service->salesByCategory());

        } catch (Exception $e) {

            return $this->sendError('Server Error.', $e);

        }
    }

    public function reportSalesByPaymentMethod(): JsonResponse
    {

        try {

            return $this->sendResponse($this->service->salesPaymentMethod());

        } catch (Exception $e) {

            return $this->sendError('Server Error.', $e);

        }
    }

    public function reportSalesByOptionPayment(): JsonResponse
    {

        try {

            return $this->sendResponse($this->service->salesOptionPayment());

        } catch (Exception $e) {

            return $this->sendError('Server Error.', $e);

        }
    }


    public function reportSalesByStatusSale(): JsonResponse
    {

        try {

            return $this->sendResponse($this->service->salesStatusSale());

        } catch (Exception $e) {

            return $this->sendError('Server Error.', $e);

        }
    }


    public function reportSalesByOptionDelivery(): JsonResponse
    {

        try {

            return $this->sendResponse($this->service->salesOptionDelivery());

        } catch (Exception $e) {

            return $this->sendError('Server Error.', $e);

        }
    }


    public function iframe()
    {
        Debugbar::disable();
        return view('panel.home.iframe');
    }
}

