<?php

class HomeController extends Action
{

    public function index()
    {
        $tickets = $this->ticketModel->getAll('aberto');

        $this->view('home', true, [
            'titulo' => 'FastPark - Home',
            'tickets' => $tickets,
        ]);
    }

    public function fechar($id)
    {
        if (!$id) {
            $this->redirect('/');
            return;
        }

        $this->ticketModel->closeTicket($id);

        $this->redirect('/');
    }

    public function info()
    {
        $layoutData = $this->getLayoutDataInfos();

        $this->json([
            'total' => number_format((float)$layoutData['total'] ?? 0, 2, ',', '.'),
            'veiculosHoje' => $layoutData['veiculosHoje'] ?? 0
        ]);
    }

    public function gerarTicket()
    {
        // Gera o ticket via model
        $ticketId = $this->ticketModel->createTicket(); // Cria ticket com hora atual e status 'aberto'

        // Mensagem de feedback
        $mensagens = [
            ['text' => "Ticket #$ticketId gerado com sucesso!", 'type' => 'success']
        ];

        // Redireciona para home passando mensagens
        $this->view('home', true, [
            'titulo' => 'FastPark - Home',
            'tickets' => $this->ticketModel->getAll('aberto'),
            'mensagens' => $mensagens
        ]);
    }
}
