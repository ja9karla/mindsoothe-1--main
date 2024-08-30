<?php 
    include 'connect.php';

    function isValidEmail($email) {
        return filter_var($email, FILTER_VALIDATE_EMAIL);
    }

    if(isset($_POST['signUp'])){
        $firstName = $_POST['firstName'];
        $lastName = $_POST['lastName'];
        $email = $_POST['email'];
        $password = $_POST['password'];
        $password = md5($password);

        // Validate email syntax
        if (!isValidEmail($email)) {
            echo "Invalid email format!";
            exit();
        }

        $checkEmail = "SELECT * FROM usersacc WHERE email='$email'";
        $result = $conn->query($checkEmail);

        if($result->num_rows > 0){
            echo "Email Address Already Exists!";
        } else {
            $insertQuery = "INSERT INTO usersacc (firstName, lastName, email, password)
                            VALUES ('$firstName', '$lastName', '$email', '$password')";
            if($conn->query($insertQuery) === TRUE){
                header("Location: Login.html");
                exit();
            } else {
                echo "Error: " . $conn->error;
            }
        }
    }

    if(isset($_POST['signIn'])){
        $email = $_POST['email'];
        $password = $_POST['password'];
        $password = md5($password);

        // Validate email syntax
        if (!isValidEmail($email)) {
            echo "Invalid email format!";
            exit();
        }

        $sql = "SELECT * FROM usersacc WHERE email='$email' AND password='$password'";
        $result = $conn->query($sql);

        if($result->num_rows > 0){
            session_start();
            $row = $result->fetch_assoc();
            $_SESSION['email'] = $row['email'];
            header("Location: homepage.php");
            exit();
        } else {
            
            echo "<script type='text/javascript'>
                alert('Not Found, Incorrect Email or Password');
                window.location.href = 'Login.html';
                </script>";
        }
    }
?>
