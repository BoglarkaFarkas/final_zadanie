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
        $sql = "SELECT * FROM generatedExamples WHERE id_student = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            ':id' => $id
        ]);
        return $stmt->fetchAll();
        //return $this->executeQuery($sql, [':id' =>$id]);
    }
    private function isWrong($examples){
        $isWrong = array();
        foreach($examples as $example){
            if($example['status']== 0){
                array_push($isWrong, $example);
            }
        }
        return $isWrong;
    }
    private function isDone($examples){
        $isDone = array();
        foreach($examples as $example){
            if($example['status']== 1){
                array_push($isDone, $example);
            }
        }
        return $isDone;
    }
    private function submitted($examples){
        $submitted = array();
        foreach($examples as $example){
            if($example['status']===0 || $example['status']==1){
                array_push($submitted, $example);
            }
        }
        return $submitted;
    }
    private function points($submitted){
        $points=0;
        foreach($submitted as $submit){
            if($submit['status']==1){
                $sql = "SELECT points FROM examples WHERE id=:id";
                $stmt = $this->pdo->prepare($sql);
                $stmt->execute([
                    ':id' => $submit['id_example']
                ]);
                $tmp=$stmt->fetchAll();
                foreach ($tmp as $item) {
                    if (isset($item['points'])) {
                        $points += $item['points'];
                    }
                }
            }
        }
        return $points;
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
                    <td>'. count($this->generatedExcercises($ids[$i])).'</td>
                    <td>'.count($this->submitted($this->generatedExcercises($ids[$i]))).'</td>
                    <td>'.$this->points($this->submitted($this->generatedExcercises($ids[$i]))).'</td>
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
                    <th id="id56"> Vygenerované príklady</th>
                    <th id="id57">Odovzdané</th>
                    <th id="id58">Body</th>
                </tr>
            </thead>'
            . $this->generateTbody() . 
        '</table>';
    return $html;
    }
}
?>