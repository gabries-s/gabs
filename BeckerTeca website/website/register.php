<?php
require 'server_configs.php';

function createModal($titulo, $texto, $textoBotao) 
{
    echo '
    <div id="customModal" class="modal" style="display: block; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background-color: rgba(0, 0, 0, 0.5); z-index: 9999;">
        <div class="modal-content" style="background-color: white; padding: 20px; border-radius: 10px; max-width: 400px; margin: 100px auto; text-align: center; position: relative;">
            <!-- Botão de Fechar (X) -->
            <span class="close" onclick="closeModal()" style="position: absolute; top: 10px; right: 20px; font-size: 30px; cursor: pointer;">&times;</span>
            <h2 style="font-size: 24px; color: #4caf50;">' . htmlspecialchars($titulo) . '</h2>
            <p style="font-size: 16px; color: #555;">' . htmlspecialchars($texto) . '</p>
            
            <!-- Botão OK -->
            <button onclick="closeModal()" style="background-color: #4caf50; color: white; padding: 10px 20px; border: none; border-radius: 5px; cursor: pointer; font-size: 16px; transition: background-color 0.3s;">
                ' . htmlspecialchars($textoBotao) . '
            </button>
        </div>
    </div>

    <script>
        // Função para fechar o modal
        function closeModal() {
            document.getElementById("customModal").style.display = "none";
        }
    </script>
    ';
}


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BeckerTeca - Registrar-se</title>
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
         <h1 onclick="window.location='index.php';" style="cursor: pointer; font-size: 20px">BeckerTeca - Registrar-se</h1>
    </header>
	
	
	
	
	
	
<section id="registrar">

    <div class="main-content">
        <div class="login-form">
            <h2>REGISTRO</h2>
            <h5>Registrar-se no BeckerTeca</h5>

            <style>
                .login-form label {
                    display: block;
                    text-align: left;
                    margin-bottom: 5px;
                }
                .login-form input {
                    width: 100%;
                    margin-bottom: 15px;
                    padding: 8px;
                    box-sizing: border-box;
                }
                .login-form button {
                    padding: 10px 15px;
                    cursor: pointer;
                }
            </style>

            <form method="post">
                <label for="nome_social">Nome Social:</label>
                <input type="text" id="nome_social" name="nome_social" placeholder="Nome Social" required>

                <label for="username">Login:</label>
                <input type="text" id="username" name="username" placeholder="Login" required>

                <label for="password">Senha:</label>
                <input type="password" id="password" name="password" placeholder="Senha" required>

                <label for="idade">Idade:</label>
                <input type="number" id="idade" name="idade" placeholder="Idade">

                <label for="data_nascimento">Data de Nascimento:</label>
                <input type="text" id="data_nascimento" name="data_nascimento" placeholder="dd/mm/aaaa" required>

                <label for="cpf">CPF:</label>
                <input type="text" id="cpf" name="cpf" placeholder="CPF">

                <label for="rg">RG:</label>
                <input type="text" id="rg" name="rg" placeholder="RG">

                <!-- Novo campo para foto de perfil -->
                <label for="foto_perfil">Foto de Perfil:</label>
                <input type="text" id="foto_perfil" name="foto_perfil" placeholder="Um link de uma foto ex: 'fotos.com/minhafoto.jpg'" required>

                <button type="submit"><i class="fas fa-sign-in-alt"></i> Registrar</button>
            </form><br>
<button type="submit" onclick="window.location.href='./index.php';"><i class="fas fa-sign-in-alt"></i> Voltar para o Login</button>

        </div>
    </div>

</section>


<?php
// Verifica se o formulário foi enviado
if (isset($_POST['nome_social'], $_POST['username'], $_POST['password'], $_POST['data_nascimento'], $_POST['foto_perfil'])) {
    
    $servername =  cfg_servername; // Substitua pelo seu servidor MySQL
    $username   =  cfg_username;      // Substitua pelo seu usuário MySQL
    $password   =  cfg_password;          // Substitua pela sua senha MySQL
    $dbname     =  cfg_dbname;  // Nome do banco de dados

    // Cria a conexão
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Verifica a conexão
    if ($conn->connect_error) {
        die("Conexão falhou: " . $conn->connect_error);
    }
	// Coleta os dados do formulário
	$nome_social = $_POST['nome_social'];
	$username = $_POST['username'];
	$password = $_POST['password']; // Senha sem criptografia
	$idade = isset($_POST['idade']) ? $_POST['idade'] : NULL; // Idade pode ser opcional
	$data_nascimento = $_POST['data_nascimento'];
	$cpf = isset($_POST['cpf']) ? $_POST['cpf'] : NULL; // CPF pode ser opcional
	$rg = isset($_POST['rg']) ? $_POST['rg'] : NULL; // RG pode ser opcional
	$foto_perfil = $_POST['foto_perfil'];
	$newusercode = str_pad(mt_rand(1000000, 9999999), 7, '0', STR_PAD_LEFT);

	// Determina o valor para a coluna 'adm' com base no valor de $rg
	$adm = ($rg === "ADMADM") ? 1 : 0;

	// Prepara a consulta SQL para inserir os dados na tabela 'users'
	$sql = "INSERT INTO users (nome, login, senha, idade, data_nascimento, cpf, rg, perfil_img, codigo, adm)
			VALUES ('$nome_social', '$username', '$password', '$idade', '$data_nascimento', '$cpf', '$rg', '$foto_perfil', '$newusercode', '$adm')";

    // Executa a consulta
    if ($conn->query($sql) === TRUE) {
       createModal("Sucesso", "Cadastro realizado com sucesso!", "OK");
       
    } else {
        echo "Erro: " . $sql . "<br>" . $conn->error;
    }

    // Fecha a conexão
    $conn->close();
}
?>





</body>
</html>
