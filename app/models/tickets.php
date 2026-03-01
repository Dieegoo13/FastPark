<?php 

class Tickets 
{
    
    private $conn;
    private $table = 'tickets';

    public function __construct()
    {
        $this->conn = Database::getConnection();
    }

    public function getAll()
    {
        $query = "SELECT * FROM " . $this->table . " ORDER BY id ASC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
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
        $query = "
        SELECT COUNT(*) AS total
        FROM tickets
        WHERE DATE(entrada) = CURDATE()
        ";

        $stmt = $this->conn->query($query);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        return (int) ($result['total'] ?? 0);
    }

    public function calcularPermanencia($entrada, $saida = null)
    {

        $dataEntrada = new DateTime($entrada);


        $dataSaida = $saida
            ? new DateTime($saida)
            : new DateTime();


        $intervalo = $dataEntrada->diff($dataSaida);

        $minutos = ($intervalo->days * 24 * 60)
            + ($intervalo->h * 60)
            + $intervalo->i;

        return $minutos;
    }

    public function fecharTicket($id, $valor)
    {
        $query = "UPDATE " . $this->table . " SET valor = :valor, data_saida = NOW() WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':valor', $valor);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }

    // public function criarTicket($placa)
    // {
    //     $query = "INSERT INTO " . $this->table . " (placa, entrada) VALUES (:placa, NOW())";
    //     $stmt = $this->conn->prepare($query);
    //     $stmt->bindParam(':placa', $placa);
    //     return $stmt->execute();
    // }

    public function getOpenTickets()
    {
        $query = "SELECT id, entrada, saida FROM " . $this->table . " WHERE status = 'aberto'";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $tickets = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $result = [];

        foreach ($tickets as $ticket) {
            $entrada = new DateTime($ticket['entrada']);
            $saida   = $ticket['saida'] ? new DateTime($ticket['saida']) : new DateTime();

            $interval = $saida->getTimestamp() - $entrada->getTimestamp(); // segundos
            $minutos = ceil($interval / 60); // arredonda para cima

            $valor = ceil($minutos / 10) * 2; // regra: 10 min = 2 reais

            $result[] = [
                'id' => $ticket['id'],
                'entrada' => $ticket['entrada'],
                'saida' => $ticket['saida'],
                'permanencia' => $minutos,
                'valor' => $valor,
            ];
        }

        return $result;
    }
}



?>