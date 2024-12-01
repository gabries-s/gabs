<?php
require 'server_configs.php';

session_start();


    $servername =  cfg_servername; // Substitua pelo seu servidor MySQL
    $username   =  cfg_username;      // Substitua pelo seu usuário MySQL
    $password   =  cfg_password;          // Substitua pela sua senha MySQL
    $dbname     =  cfg_dbname;  // Nome do banco de dados



// Connect to the database using PDO
try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    // Set PDO error mode to exceptions
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

// Check if the form was submitted
if (isset($_POST['username']) && isset($_POST['password'])) {
    $input_username = $_POST['username'];
    $input_password = $_POST['password'];

    // Prepare the SQL query to fetch the user from the database
    $sql = "SELECT * FROM users WHERE login = :username LIMIT 1";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':username', $input_username, PDO::PARAM_STR);

    // Execute the query
    $stmt->execute();

    // Check if the user exists
    if ($stmt->rowCount() > 0) {
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        // Check if the password matches
        if ($input_password == $user['senha']) {
            // Store the user code in the session
            $_SESSION['user_code'] = $user['codigo']; // Store user code

            // Redirect to the success page (can be a restricted area)
			session_start();
            header("Location: index.php");
            exit;
        } else {
            // Password is incorrect
            $_SESSION['login_error'] = 'Login ou senha inválidos!';
        }
    } else {
        // User not found
        $_SESSION['login_error'] = 'Login ou senha inválidos!';
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BeckerTeca - Login</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            height: 100vh;
        }
		
				/* Estilizando a barra de rolagem */
::-webkit-scrollbar {
    width: 8px; /* Define a largura da barra de rolagem */
}

/* Estilizando o fundo da barra de rolagem */
::-webkit-scrollbar-track {
    background: #d3d3d3; /* Cinza semi-claro */
}

/* Estilizando o polegar (parte que se movimenta) */
::-webkit-scrollbar-thumb {
    background: #90ee90; /* Verde-claro */
    border-radius: 4px; /* Bordas arredondadas para um visual mais moderno */
}

/* Adicionando um efeito ao passar o mouse no polegar */
::-webkit-scrollbar-thumb:hover {
    background: #76c776; /* Um verde-claro um pouco mais escuro ao passar o mouse */
}


        header {
            background-color: #28a745;
            color: white;
            padding: 15px 30px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            width: 100%;
            position: fixed;
            top: 0;
            left: 0;
            z-index: 100;
        }

        header h1 {
            margin: 0;
            padding-left: 10px;
            cursor: pointer;
        }

        .main-content {
            display: flex;
            justify-content: center;
            align-items: center;
            flex-grow: 1;
            margin-top: 80px;
        }

        .login-form {
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            padding: 30px;
            width: 100%;
            max-width: 400px;
            text-align: center;
        }

        .login-form input {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border-radius: 5px;
            border: 1px solid #ccc;
            font-size: 16px;
        }

        .login-form button {
            width: 100%;
            padding: 12px;
            background-color: #28a745;
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .login-form button:hover {
            background-color: #218838;
        }

        .error-message {
            color: red;
            margin-top: 10px;
        }

    </style>
</head>
<body>

    <header>
         <h1 onclick="window.location='index.php';" style="cursor: pointer; font-size: 20px">BeckerTeca</h1>
    </header>

    <div class="main-content">
        <div class="login-form">
            <h2>Login</h2>
            <h5>Faça login com suas credenciais</h5>

            <!-- Display error message if session error exists -->
            <?php if (isset($_SESSION['login_error'])): ?>
                <div class="error-message">
                    <?php echo $_SESSION['login_error']; ?>
                    <?php unset($_SESSION['login_error']); ?>
                </div>
            <?php endif; ?>

            <form action="login.php" method="post">
                <input type="text" name="username" placeholder="Username" required>
                <input type="password" name="password" placeholder="Password" required>
                <button type="submit"><i class="fas fa-sign-in-alt"></i> Log In</button>
            </form><br>
			 <button onclick="window.location.href='./register.php'" type="submit"><i class="fas fa-sign-in-alt"></i> Registrar-se</button>
        </div>
    </div>

</body>
</html>
