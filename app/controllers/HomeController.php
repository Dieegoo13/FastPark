<?php

class HomeController extends Action
{   

    public function index()
    {
        $tickets = $this->ticketModel->getOpenTickets();

        $this->view('home', true, [
            'titulo' => 'FastPark - Home',
            'tickets' => $tickets,
        ]);
    }

    public function info()
    {
        $layoutData = $this->getLayoutDataInfos(); 

        $this->json([
            'total' => number_format((float)$layoutData['total'] ?? 0, 2, ',', '.'),
            'veiculosHoje' => $layoutData['veiculosHoje'] ?? 0
        ]);
    }
}


