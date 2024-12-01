<?php
require 'server_configs.php';


session_start();


// Verificar se o usuário está logado
if (!isset($_SESSION['user_code'])) {
    exit(); //PAGINA NAO CARREGARÁ POIS NAO TEM NGM LOGADO - É PARA PREVENÇÃO DE ERROS E ROUBO DE DADOS
}

function get_admin_users()
{
    $servername =  cfg_servername; // Substitua pelo seu servidor MySQL
    $username   =  cfg_username;      // Substitua pelo seu usuário MySQL
    $password   =  cfg_password;          // Substitua pela sua senha MySQL
    $dbname     =  cfg_dbname;  // Nome do banco de dados

    // Criando a conexão
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Verificando a conexão
    if ($conn->connect_error) {
        die("Conexão falhou: " . $conn->connect_error);
    }

    // Array para armazenar os administradores
    $admin_users = [];

    // Query para buscar usuários com adm = 1
    $sql = "SELECT nome, codigo, adm FROM users WHERE adm = 1";
    $stmt = $conn->prepare($sql);

    // Executando a query
    $stmt->execute();
    $result = $stmt->get_result();

    // Verificando se encontrou resultados
    if ($result->num_rows > 0) {
        // Adicionando os administradores ao array
        while ($user = $result->fetch_assoc()) {
            $admin_users[] = [
                'nome'   => $user['nome'],
                'codigo' => $user['codigo'],
                'adm'    => $user['adm']
            ];
        }
    } else {
        echo "Nenhum administrador encontrado!";
    }

    // Fechando a conexão
    $stmt->close();
    $conn->close();

    // Retornando o array de administradores
    return $admin_users;
}

function is_requester_adm() 
{
// Supomos que a função get_admin_users() já está definida e retorna o array de administradores
$admins = get_admin_users();
$is_admin = false; // Flag para verificar se o usuário é administrador

foreach ($admins as $admin) {
    if ($_SESSION['user_code'] == $admin['codigo']) {
        $is_admin = true; // Usuário encontrado na lista de administradores
        break; // Encerra o loop, pois já encontramos o usuário
    }
}

if (!$is_admin) {
    exit("Acesso negado!"); // Encerra o script se não for admin
}	
}
is_requester_adm();





$configurations = download_server_configs();
function download_server_configs() {
    // Configurações de conexão com o banco de dados
    $servername =  cfg_servername; // Substitua pelo seu servidor MySQL
    $username   =  cfg_username;      // Substitua pelo seu usuário MySQL
    $password   =  cfg_password;          // Substitua pela sua senha MySQL
    $dbname     =  cfg_dbname;  // Nome do banco de dados

    // Criando a conexão
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Verificando a conexão
    if ($conn->connect_error) {
        die("Conexão falhou: " . $conn->connect_error);
    }

    // Query para obter todas as colunas da tabela configuracoes
    $sql = "SELECT * FROM configuracoes LIMIT 1";  // Como só existe 1 linha, LIMIT 1 é opcional, mas ajuda a evitar problemas

    // Executando a query
    $result = $conn->query($sql);

    // Verificando se encontrou algum resultado
    if ($result->num_rows > 0) {
        // Obtendo os dados da única linha
        $config = $result->fetch_assoc();
    } else {
        echo "Nenhuma configuração encontrada!";
        $config = []; // Retorna um array vazio caso não haja dados
    }

    // Fechando a conexão
    $conn->close();

    // Retornando os dados da linha como um array
    return $config;
}


$livros = get_all_books();
function get_all_books() 
{
	
    $servername =  cfg_servername; // Substitua pelo seu servidor MySQL
    $username   =  cfg_username;      // Substitua pelo seu usuário MySQL
    $password   =  cfg_password;          // Substitua pela sua senha MySQL
    $dbname     =  cfg_dbname;  // Nome do banco de dados

    // Criando a conexão
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Verificando a conexão
    if ($conn->connect_error) {
        die("Conexão falhou: " . $conn->connect_error);
    }

    // Query para obter todas as colunas e linhas da tabela livros
    $sql = "SELECT * FROM livros";

    // Executando a query
    $result = $conn->query($sql);

    // Verificando se encontrou algum resultado
    if ($result->num_rows > 0) {
        // Criando um array para armazenar os dados dos livros
        $livros = [];
        while ($row = $result->fetch_assoc()) {
            $livros[] = $row;  // Adiciona cada linha ao array $livros
        }
    } else {
        echo "Nenhum livro encontrado!";
        $livros = []; // Retorna um array vazio caso não haja dados
    }

    // Fechando a conexão
    $conn->close();

    // Retornando todos os livros como um array
    return $livros;
}


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


//createModal("Painel de Controle", "Aqui você poderá alterar, remover e acrescentar dados ao site!", "OK");

?>





<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BeckerTeca - Novo Livro</title>
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

        #CONTROLS {
            padding: 20px;
            margin-top: 20px;
        }

        .tool-section {
            margin-bottom: 30px;
            padding: 20px;
            background-color: #ffffff;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .tool-section h2 {
            font-size: 22px;
            color: #28a745;
            margin-bottom: 15px;
        }

        .tool-section button {
            background-color: #28a745;
            color: white;
            border: none;
            border-radius: 5px;
            padding: 10px 15px;
            margin: 5px;
            font-size: 14px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .tool-section button:hover {
            background-color: #218838;
        }

        .tool-section .info {
            margin: 10px 0;
            font-size: 16px;
        }

        .tool-section label {
            display: block;
            margin: 10px 0;
            font-weight: bold;
        }

        .tool-section input[type="text"],
        .tool-section input[type="number"] {
            width: 100%;
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 5px;
            margin-top: 5px;
        }

        .radio-group {
            margin: 10px 0;
        }

        .radio-group label {
            margin-right: 15px;
            font-weight: normal;
        }

        .radio-group input {
            margin-right: 5px;
        }
    </style>
</head>
<body>

    <header>
         <h1 onclick="window.location='cpanel.php';" style="cursor: pointer; font-size: 20px"><?php echo $configurations['titulo_site']; ?> - Novo Livro</h1>
    </header>
	
	
<section id="CONTROLS">

<form method="POST">
    <!-- INSERIR NOVO LIVRO -->
    <div class="tool-section">
        <h2>INSERIR NOVO LIVRO</h2>

        <label for="book_title">Título do livro:</label>
        <div>
            <input type="text" id="book_title" name="book_title" placeholder="Digite o título do livro" value="" required />
        </div>
        <p>

        <label for="book_name">Nome:</label>
        <div>
            <input type="text" id="book_name" name="book_name" placeholder="Digite o nome do livro" value="" />
        </div>
        <p>

        <label for="book_author">Autor:</label>
        <div>
            <input type="text" id="book_author" name="book_author" placeholder="Digite o nome do autor" value="" required />
        </div>
        <p>

        <label for="book_banner_url">Banner do livro URL (fotos com extensão: .jpg, .png, .bmp):</label>
        <div>
            <input type="text" id="book_banner_url" name="book_banner_url" placeholder="Digite a URL do banner do livro" value="" required  />
        </div>
        <p>

        <label for="book_cover_url">Capa do livro URL (fotos com extensão: .jpg, .png, .bmp):</label>
        <div>
            <input type="text" id="book_cover_url" name="book_cover_url" placeholder="Digite a URL da capa do livro" value="" />
        </div>
        <p>

        <label for="book_category">Categoria: (separe por vírgulas ex: romance, conto, didático...)</label>
        <div>
            <input type="text" id="book_category" name="book_category" placeholder="Digite a categoria do livro" value="" required />
        </div>
        <p>
		
		 <label for="book_release_data">Data de lançamento: (dd-mm-aaaa ex.: 24-05-2024)</label>
        <div>
            <input type="text" id="book_release_data" name="book_release_data" placeholder="Digite a data de lançamento do livro" value="" required  />
        </div>
        <p>

        <label for="book_description">Descrição:</label>
        <div>
            <input type="text" id="book_description" name="book_description" placeholder="Digite a descrição do livro" value="" />
        </div>
        <p>

        <label for="book_synopsis">Sinopse:</label>
        <div>
            <input type="text" id="book_synopsis" name="book_synopsis" placeholder="Digite a sinopse do livro" value="" />
        </div>
        <p>

        <label for="book_rating">Avaliação Estrelas: (de 0 - 5)</label>
        <div>
            <input type="text" id="book_rating" name="book_rating" placeholder="Digite a avaliação do livro" value="" required  />
        </div>
        <p>

        <button type="submit">SALVAR ALTERAÇÕES</button>
    </div>
</form>
	

	
	
</section>






<?php


/// Verifica se o formulário foi enviado
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Inicializa variáveis
    $book_title = isset($_POST['book_title']) ? $_POST['book_title'] : '';
    $book_name = isset($_POST['book_name']) ? $_POST['book_name'] : '';
    $book_author = isset($_POST['book_author']) ? $_POST['book_author'] : '';
    $book_banner_url = isset($_POST['book_banner_url']) ? $_POST['book_banner_url'] : '';
    $book_cover_url = isset($_POST['book_cover_url']) ? $_POST['book_cover_url'] : '';
    $book_category = isset($_POST['book_category']) ? $_POST['book_category'] : '';
    $book_description = isset($_POST['book_description']) ? $_POST['book_description'] : '';
    $book_synopsis = isset($_POST['book_synopsis']) ? $_POST['book_synopsis'] : '';
    $book_rating = isset($_POST['book_rating']) ? $_POST['book_rating'] : '';

    // Gerar um código único para o livro (pode ser alterado conforme necessidade)
    $book_code = str_pad(mt_rand(1000000, 9999999), 7, '0', STR_PAD_LEFT);

    // Verifica se a data de lançamento foi enviada no formato dd-mm-yyyy
    if (isset($_POST['book_release_data']) && !empty($_POST['book_release_data'])) {
        // Converte a data para o formato yyyy-mm-dd
        $book_launch_date = DateTime::createFromFormat('d-m-Y', $_POST['book_release_data']);
        if ($book_launch_date) {
            $book_launch_date = $book_launch_date->format('Y-m-d'); // Formato do banco de dados
        } else {
            echo "Data inválida! O formato deve ser DD-MM-YYYY.";
            exit;
        }
    } else {
        // Se não foi enviada, usa a data atual
        $book_launch_date = date('Y-m-d');
    }

    // Configurações de conexão com o banco de dados
    $servername =  cfg_servername; // Substitua pelo seu servidor MySQL
    $username   =  cfg_username;      // Substitua pelo seu usuário MySQL
    $password   =  cfg_password;          // Substitua pela sua senha MySQL
    $dbname     =  cfg_dbname;  // Nome do banco de dados

    // Criando a conexão
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Verificando a conexão
    if ($conn->connect_error) {
        die("Conexão falhou: " . $conn->connect_error);
    }

    // Query de inserção
    $sql = "INSERT INTO livros (banner_link, capa_link, nome, titulo, autor, data_lancamento, codigo_livro, categoria, sinopse, descricao, estrelas) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    // Preparar a consulta
    if ($stmt = $conn->prepare($sql)) {
        // Bind dos parâmetros
        $stmt->bind_param("sssssssssss", 
            $book_banner_url, 
            $book_cover_url, 
            $book_name, 
            $book_title, 
            $book_author, 
            $book_launch_date, 
            $book_code, 
            $book_category, 
            $book_synopsis, 
            $book_description, 
            $book_rating
        );

        // Executando a consulta
        if ($stmt->execute()) {
           createModal("Aviso", "Livro inserido com sucesso!", "OK");
        } else {
            createModal("Erro ao inserir livro",  $stmt->error, "OK");
        }

        // Fechando a consulta
        $stmt->close();
    } else {
        echo "Erro na preparação da consulta: " . $conn->error;
    }

    // Fechando a conexão
    $conn->close();
}




?>

</body>
</html>

