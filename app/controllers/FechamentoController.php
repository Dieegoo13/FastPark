<?php

class FechamentoController extends Action
{
    public function index()
    {
        $this->view('fechamento', true, [
            'titulo' => 'FastPark - Fechamento',
        ]);
    }

    protected function getLayoutDataInfos()
    {
        return [
            'total' =>  $this->ticketModel->getTotalArrecadado(),
            'veiculosHoje' => $this->ticketModel->getTotalVeiculosHoje()
        ];
    }
    
}
