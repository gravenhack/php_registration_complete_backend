    <?php

    $conn = new PDO('mysql:host=your_server_name;dbname=yourdbname', 'your_user_name', 'your_user_password');

    const ERROR_LOG_FILE = 'error.log';

    $error = [];

    if (isset($_POST['submit'])) {

        try {
            if (verify_name($_POST['name']) && verify_password($_POST['password']) && verify_email($_POST['email']) &&  verify_password_match_confirmed_password($_POST['password'], $_POST['confirm_password'])) {

                $email = $_POST['email'];

                if (verify_if_user_exists_by_mail($conn, $email)) {
                    $query = 'INSERT INTO users(name, email,password, created_at) VALUES (:name,:email,:password,:created_at)';

                    $stm = $conn->prepare($query);

                    $name = htmlspecialchars($_POST['name']);
                    
                    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
                    $created_at =  date('Y-m-d H:i:s');

                    $stm->bindParam(':name', $name);
                    $stm->bindParam(':email', $email);
                    $stm->bindParam(':password', $password);
                    $stm->bindParam(':created_at', $created_at);



                    $stm = $stm->execute();

                    echo "You are now registered";
                }else{
                    echo "User already exist";
                }
            }
        } catch (PDOException $th) {


            file_put_contents(ERROR_LOG_FILE, $th->getMessage() . PHP_EOL, FILE_APPEND);
        }
    }

    function verify_name($name): bool
    {

        if (strlen(trim($name)) < 3) {

            return false;
        }
        if (!preg_match("/^[a-zA-ZÀ-ÿ' -]+$/", $name)) {

            return false;
        }


        return true;
    }

    function verify_password($password): bool
    {
        if (strlen($password) < 8) {

            return false;
        }
        if (!preg_match("/[A-Z]/", $password)) {

            return false;
        }

        if (!preg_match("/[a-z]/", $password)) {

            return false;
        }

        if (!preg_match("/[0-9]/", $password)) {

            return false;
        }

        if (!preg_match("/[\W]/", $password)) {

            return false;
        }


        return true;
    }

    function verify_email($email): bool
    {

        return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
    }

    function verify_if_user_exists_by_mail(PDO $pdo_instance, $email)
    {


        $query_exists_by_mail = 'SELECT * FROM users WHERE email = :email ';

        $stm = $pdo_instance->prepare($query_exists_by_mail);

        $stm->bindParam(':email', $email);

        $stm->execute();

        $result = $stm->rowCount();


        if ($result > 0) {

            return false;
        }
        return true;
    }

    function verify_password_match_confirmed_password($password, $confirm_password): bool
    {

        return $password === $confirm_password;
    }

    ?>

    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Register - page crud_01_php</title>
    </head>

    <body>
        <form action="" method="post">
            <div>
                <label for="">Name</label>
                <input type="text" name="name" id="name" placeholder="enter your name">
                <?php if (isset($_POST['name'])) {
                    if (!verify_name($_POST['name'])) {
                        echo "Name should containe at least three charaters and only valid characters";
                    }
                } ?>
            </div>
            <div>
                <label for="email">Email</label>
                <input type="email" name="email" id="email" placeholder="enter your email">
                <?php if (isset($_POST['email'])) {
                    if (!verify_email($_POST['email'])) {
                        echo "Invalid email";
                    }
                } ?>
            </div>

            <div>
                <label for="password">Password</label>
                <input type="password" name="password" id="password" placeholder="enter your password">
                <?php if (isset($_POST['password'])) {
                    if (!verify_password($_POST['password'])) {
                        echo "password should contain at least 8 characters, at least one uppercase characters  password should containe at least one lowercase characters  password should containe at least one number  password should contain at least one special character";
                    }
                } ?>

            </div>
            <div>
                <label for="">Confirm password</label>
                <input type="password" name="confirm_password" id="confirm_password" placeholder="confirm your password">
                <?php if (isset($_POST['password']) && isset($_POST['confirm_password'])) {

                    if (!verify_password_match_confirmed_password($_POST['password'], $_POST['confirm_password'])) {
                        echo "password and confirm password don't match";
                    }
                } ?>
            </div>

            <input type="submit" name="submit" value="Register">

        </form>
    </body>

    </html>