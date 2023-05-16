<?php 
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    require_once '../config.php';
    
    function is_valid_password($password) {
        // Heslo musí mať aspoň 8 znakov, obsahovať veľké písmeno, číslicu a špeciálny znak
        if (preg_match('/^(?=.*[A-Z])(?=.*[0-9])(?=.*[!@#$%^&*()_+\-=\[\]{};:\\\\|,.<>\/?]).{8,}$/', $password)) {
            return true;
        }
        return false;
    }

    if ($_SERVER['REQUEST_METHOD'] == 'POST'){
        $first_name = trim($_POST['first-name']);
        $last_name = trim($_POST['last-name']);
        $type = $_POST['type'];
        $email = trim($_POST['email']);
        $password = trim($_POST['password']);
        $confirm_password = trim($_POST['confirm-password']);
        
        //Kontrola unikatnosti a validi emailu
        $sql_check_email = "SELECT * FROM users WHERE email = :email";
        $stmt_check_email = $pdo->prepare($sql_check_email);
        $stmt_check_email->execute([
            ':email' => $email
        ]);
        // Kontrola platnosti emailu
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            echo '<div class="alert alert-danger">Neplatny email.</div>';
        }
        //Kontrola zhody
        elseif ($stmt_check_email->rowCount() > 0) {
            echo '<div class="alert alert-danger">Tento email je už registrovaný.</div>';
        }
        // Kontrola, či zadané heslo spĺňa požiadavky
        elseif (!is_valid_password($password)) {
            echo '<div class="alert alert-danger">Heslo musí mať aspoň 8 znakov, obsahovať veľké písmeno, číslicu a špeciálny znak.</div>';
        }
        // Kontrola zhody hesiel
        elseif ($password != $confirm_password) {
            echo '<div class="alert alert-danger">Heslá sa nezhodujú.</div>';
        }
        // Kontrola vyberu role
        elseif ($type == "default"){
            echo '<div class="alert alert-danger">Vyberte si svoju rolu.</div>';
        }
        else{
            // Hashovanie hesla
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            $sql = "INSERT INTO users (name, surname, email, password, role)
                    VALUES (:first_name, :last_name, :email, :password, :type)";
            try{
                $stmt = $pdo->prepare($sql);
                $stmt->execute([
                    ':first_name' => $first_name,
                    ':last_name' => $last_name,
                    ':email' => $email,
                    ':password' => $hashed_password,
                    ':type' => $type 
                ]);
                header('Location: ../../index.php');
                exit;
            }catch (PDOException $e) {
                "Error: " . $e->getMessage();
            }
        }
    }
?>
<!DOCTYPE html>
<html lang="sk">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <!------------------------------------------CSS---------------------------------------->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-aFq/bzH65dt+w6FI2ooMVUpc+21e0SRygnTpmBvdBgSdnuTN7QbdgL+OapgHtvPp" crossorigin="anonymous">
    <link href="../../public/css/registration.css" rel="stylesheet">
</head>
<body>
    <section class="py-5">
        <div class="container">
            <form action="" method="post">
                <div id="id9" class="title">Registrácia</div>
                <div class="input-box underline fname">
                    <input type="text" id="first-name" name="first-name" placeholder="Zadaj krstné meno" required>
                    <div class="underline"></div>
                </div>
                <div class="input-box underline lname">
                    <input type="text" id="last-name" name="last-name" placeholder="Zadaj priezvisko" required>
                    <div class="underline"></div>
                </div>
                <div class="input-box underline email">
                    <input type="email" id="email" name="email" placeholder="Enter Your Email" required>
                    <div class="underline"></div>
                </div>
                <div class="input-box type form-floating">
                    <select class="form-select" name="type" id="floatingSelect" aria-label="Floating label">
                        <option id="id17" value="default" selected>Vyber si svoju rolu</option>
                        <option id="id18" value="student">Študent</option>
                        <option id="id19" value="ucitel">Učiteľ</option>
                    </select>
                    <label id="id16" for="floatingSelect">Rola</label>
                </div>
                <div class="input-box psw">
                    <input type="password" id="password" name="password" placeholder="Zadaj heslo" required>
                    <div class="underline"></div>
                </div>
                <div class="input-box cpsw">
                    <input type="password" id="confirm-password" name="confirm-password" placeholder="Potvrď heslo" required>
                    <div class="underline"></div>
                </div>
                <div class="input-box button">
                    <input id="id15" type="submit" name="" value="Pokračuj">
                </div>
                <button id="sk-button">SK</button>
                <button id="eng-button">ENG</button>
            </form>
        </div>
    </section>

    <script>

        document.getElementById("sk-button").addEventListener("click", function() {
        fetch('../../bilingual.json')
            .then(response => response.json())
            .then(data => {
            document.getElementById("id9").textContent = data.sk.id9;
            document.getElementById("first-name").placeholder = data.sk.firstname;
            document.getElementById("last-name").placeholder = data.sk.lastname;
            document.getElementById("email").placeholder = data.sk.email;
            document.getElementById("id17").textContent = data.sk.id17;
            document.getElementById("id18").textContent = data.sk.id18;
            document.getElementById("id19").textContent = data.sk.id19;
            document.getElementById("id18").textContent = data.sk.id18;
            document.getElementById("id16").textContent = data.sk.id16;
            document.getElementById("password").placeholder = data.sk.password;
            document.getElementById("confirm-password").placeholder = data.sk.confirmpassword;
            document.getElementById("id15").textContent = data.sk.id15;
            //document.getElementById("id5").textContent = data.sk.id5;
            //document.getElementById("id6").textContent = data.sk.id6;
            //document.getElementById("id7").innerHTML = data.sk.id7 + ' <a href="private/components/registration.php" id="id8">Zaregistrujte sa</a>';
            // document.getElementById("id8").innerHTML = ' <a href="private/components/registration.php" id="id8">Zaregistrujte sa</a>';
            });
        });

        document.getElementById("eng-button").addEventListener("click", function() {
        fetch('../../bilingual.json')
            .then(response => response.json())
            .then(data => {
            document.getElementById("id9").textContent = data.eng.id9;
            document.getElementById("first-name").placeholder = data.eng.firstname;
            document.getElementById("last-name").placeholder = data.eng.lastname;
            document.getElementById("email").placeholder = data.eng.email;
            document.getElementById("id17").textContent = data.eng.id17;
            document.getElementById("id18").textContent = data.eng.id18;
            document.getElementById("id19").textContent = data.eng.id19;
            document.getElementById("id18").textContent = data.eng.id18;
            document.getElementById("id16").textContent = data.eng.id16;
            document.getElementById("password").placeholder = data.eng.password;
            document.getElementById("confirm-password").placeholder = data.eng.confirmpassword;
            document.getElementById("id15").textContent = data.eng.id15;
            //document.getElementById("id5").textContent = data.eng.id5;
            //document.getElementById("id6").textContent = data.eng.id6;
            //document.getElementById("id7").innerHTML = data.eng.id7 + ' <a href="private/components/registration.php" id="id8">Sign up</a>';
            //document.getElementById("id8").textContent = data.eng.id8;
            });
        });
</script>
</body>