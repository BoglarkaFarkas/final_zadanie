<?php     
class Table {
    private $pdo;
    private $role = "student";
    function __construct() {
        require_once '../config.php';
        $this->pdo = $pdo;
    }
    private function executeQuery($sql, $params = []) {
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }
    private function getIDs() {
        $sql = "SELECT id FROM users WHERE role = :role";
        return $this->executeQuery($sql, [':role' => $this->role]);
    }
    private function getNames() {
        $sql = "SELECT name FROM users WHERE role = :role";
        return $this->executeQuery($sql, [':role' => $this->role]);
    }
    private function getLastNames() {
        $sql = "SELECT surname FROM users WHERE role = :role";
        return $this->executeQuery($sql, [':role' => $this->role]);
    }
    private function generatedExcercises($id){
        $sql = "SELECT * FROM generatedStudnet WHERE id_student = :id";
        return $this->executeQuery($sql, [':id' =>$id]);
    }
    private function generateTbody() {
        $html = '<tbody>';
        $ids = $this->getIDs();
        $names = $this->getNames();
        $lastNames = $this->getLastNames();
        $rowCount = count($ids);
        for ($i = 0; $i < $rowCount; $i++) {
            $html .= '
                <tr>
                    <td>' . $ids[$i] . '</td>
                    <td>' . $names[$i] . '</td>
                    <td>' . $lastNames[$i] . '</td>
                </tr>';
        }
        $html .= '</tbody>';
        return $html;
    }
    public function generateTable(){
        $html = '
        <table id="myTable">
            <thead>
                <tr>
                    <th id="id23">ID</th>
                    <th id="id21">Meno</th>
                    <th id="id22">Priezvisko</th>
                </tr>
            </thead>'
            . $this->generateTbody() . 
        '</table>';
    return $html;
    }
}
?>