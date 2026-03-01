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
        $query = "SELECT SUM(valor) AS total FROM tickets";
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
        WHERE DATE(saida) = CURDATE()
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

}



?>