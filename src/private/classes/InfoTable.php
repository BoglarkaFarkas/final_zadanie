<?php     
class InfoTable {
    private $pdo;
    private $role = "student";
    private $id;
    function __construct($id) {
        require_once '../config.php';
        $this->pdo = $pdo;
        $this->id = $id;
    }
    private function getExercises(){
        $sql = "SELECT id_example FROM generatedExamples WHERE id_student =:id";
        $stmt=$this->pdo->prepare($sql);
        $stmt->execute([
            ':id'=>$this->id
        ]);
        return $stmt->fetchAll();
    }
    private function getNames($ids){
        $names = array();
        foreach($ids as $id){
            $sql = "SELECT example_name FROM examples WHERE id=:id";
            $stmt=$this->pdo->prepare($sql);
            $stmt->execute([
                ':id'=>$id['id_example']
            ]);
            array_push($names, $stmt->fetch());
        }
        return $names;
    }
    private function getState($ids){
        $states = array();
        foreach($ids as $id){
            $sql = "SELECT status FROM generatedExamples WHERE id_example=:id";
            $stmt=$this->pdo->prepare($sql);
            $stmt->execute([
                ':id'=>$id['id_example']
            ]);
            $tmp=$stmt->fetch()['status'];
            if($tmp===null){
                array_push($states, "-");
            }
            if($tmp===0){
                array_push($states, "x");
            }
            if($tmp===1){
                array_push($states, "ok");
            }
            empty($tmp);
        }
        return $states;
    }
    private function points($ids){
        $points= array();
        foreach($ids as $id){
            $sql = "SELECT status FROM generatedExamples WHERE id_example=:id";
            $stmt=$this->pdo->prepare($sql);
            $stmt->execute([
                ':id'=>$id['id_example']
            ]);
            $tmp=$stmt->fetch()['status'];
            if($tmp===null){
                array_push($points, "0");
            }
            if($tmp===0){
                array_push($points, "0");
            }
            if($tmp===1){
                $sql = "SELECT points FROM examples WHERE id=:id";
                $stmt=$this->pdo->prepare($sql);
                $stmt->execute([
                    ':id'=>$id['id_example']
                ]);
                array_push($points, $stmt->fetch()['points']);
            }
            empty($tmp);
        }
        return $points;
    }
    private function generateTbody(){
        $html='<tbody>';
        $ids=$this->getExercises();
        $names=$this->getNames($ids);
        $states=$this->getState($ids);
        $points=$this->points($ids);
        $rowCount = count($ids);
        for($i=0; $i<$rowCount;$i++){
            $html.='
            <tr>
                <td>'.$names[$i]['example_name'].'</td>
                <td>'.$states[$i].'</td>
                <td>'.$points[$i].'</td>
            </tr>
            ';
        }
        $html .= '</tbody>';
        return $html;
    }

    public function generateTable(){
        $html = '
        <table id="myTable">
            <thead>
                <tr>
                    <th id="id59">Úloha</th>
                    <th id="id60">Stav</th>
                    <th id="id58">Body</th>
                </tr>
            </thead>'
            . $this->generateTbody() . 
        '</table>';
    return $html;
    }
}
?>