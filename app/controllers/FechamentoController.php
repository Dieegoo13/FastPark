<?php

class FechamentoController extends Action
{
    public function index()
    {
        $tickets = $this->ticketModel->getAll('fechado');
        
        $this->view('fechamento', true, [
            'titulo' => 'FastPark - Fechados',
            'tickets' => $tickets
            
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


    // protected function getLayoutDataInfos()
    // {
    //     return [
    //         'total' =>  $this->ticketModel->getTotalArrecadado(),
    //         'veiculosHoje' => $this->ticketModel->getTotalVeiculosHoje()
    //     ];
    // }
    
    
}
