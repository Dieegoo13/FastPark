<?php

class HomeController extends Action
{   

    public function index()
    {
        $tickets = $this->ticketModel->getAll();

        $this->view('home', true, [
            'titulo' => 'FastPark - Home',
            'tickets' => $tickets,
        ]);
    }

    protected function getLayoutDataInfos()
    {
        return [
            'total' => $this->ticketModel->getTotalArrecadado(),
            'veiculosHoje' => $this->ticketModel->getTotalVeiculosHoje()
        ];
    }
}


