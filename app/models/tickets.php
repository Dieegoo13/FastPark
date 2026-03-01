<?php

class Tickets
{
    private $conn;
    private $table = 'tickets';

    public function __construct()
    {
        $this->conn = Database::getConnection();
    }

    // ==================== LEITURA ====================

    public function getAll($status = null)
    {
        $query  = "SELECT * FROM " . $this->table;
        $params = [];

        if ($status) {
            $query .= " WHERE status = :status";
            $params[':status'] = $status;
        }

        $stmt = $this->conn->prepare($query);
        $stmt->execute($params);
        $tickets = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($tickets as &$ticket) {
            $entrada = new DateTime($ticket['entrada']);
            $saida   = $ticket['saida'] ? new DateTime($ticket['saida']) : new DateTime();

            $minutos = ceil(($saida->getTimestamp() - $entrada->getTimestamp()) / 60);

            $ticket['permanencia'] = $minutos;
            $ticket['valor']       = ceil($minutos / 10) * 2;
        }

        return $tickets;
    }

    public function getById($id)
    {
        $query = "SELECT * FROM " . $this->table . " WHERE id = :id";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getTotalArrecadado()
    {
        $query = "SELECT SUM(valor) AS total FROM " . $this->table . " WHERE status = 'fechado'";

        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        return $result['total'] ?? 0;
    }

    public function getTotalVeiculosHoje()
    {
        $query = "SELECT COUNT(*) AS total FROM tickets WHERE DATE(entrada) = CURDATE()";

        $stmt   = $this->conn->query($query);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        return (int) ($result['total'] ?? 0);
    }

    // ==================== ESCRITA ====================

    public function createTicket()
    {
        $query = "INSERT INTO tickets (entrada, status) VALUES (NOW(), 'aberto')";

        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        return $this->conn->lastInsertId();
    }

    public function update($id, $data)
    {
        $fields = [];
        $params = [':id' => $id];

        foreach ($data as $key => $value) {
            $fields[]       = "$key = :$key";
            $params[":$key"] = $value;
        }

        $query = "UPDATE " . $this->table . " SET " . implode(', ', $fields) . " WHERE id = :id";

        $stmt = $this->conn->prepare($query);
        return $stmt->execute($params);
    }

    public function closeTicket($id)
    {
        $ticket = $this->getById($id);

        if (!$ticket || $ticket['status'] === 'fechado') {
            return false;
        }

        $entrada  = new DateTime($ticket['entrada']);
        $saida    = new DateTime();
        $minutos  = ceil(($saida->getTimestamp() - $entrada->getTimestamp()) / 60);
        $valor    = ceil($minutos / 10) * 2;

        return $this->update($id, [
            'saida'       => $saida->format('Y-m-d H:i:s'),
            'permanencia' => $minutos,
            'valor'       => $valor,
            'status'      => 'fechado'
        ]);
    }

    // ==================== UTILITÁRIOS ====================

    public function calcularPermanencia($entrada, $saida = null)
    {
        $dataEntrada = new DateTime($entrada);
        $dataSaida   = $saida ? new DateTime($saida) : new DateTime();
        $intervalo   = $dataEntrada->diff($dataSaida);

        return ($intervalo->days * 24 * 60)
            + ($intervalo->h * 60)
            + $intervalo->i;
    }
}
