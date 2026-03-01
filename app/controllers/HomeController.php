<?php

class HomeController extends Action
{   

    public function index()
    {
        $tickets = $this->ticketModel->getAll();

        foreach ($tickets as &$ticket) {
            $ticket['permanencia'] =
                $this->ticketModel->calcularPermanencia(
                    $ticket['entrada'],
                    $ticket['data_saida'] ?? null
                );
        }

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


