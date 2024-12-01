<?php

require 'server_configs.php';

session_start();


// Verificar se o usuário está logado
if (!isset($_SESSION['user_code'])) {
    exit(); //PAGINA NAO CARREGARÁ POIS NAO TEM NGM LOGADO - É PARA PREVENÇÃO DE ERROS E ROUBO DE DADOS
}




$nome       = '';
$perfil_img = '';
$is_adm     = '';
function load_user_data() //carrega variaveis do usuario obtidos pelo codigo ao logar
{
  global $nome, $perfil_img, $is_adm;
// Conexão com o banco de dados
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

// Obtendo o código do usuário logado
$user_code = $_SESSION['user_code'];

// Query para obter os dados do usuário
$sql = "SELECT nome, login, senha, codigo, idade, perfil_img, adm FROM users WHERE codigo = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_code); // Ligando o código do usuário

// Executando a query
$stmt->execute();
$result = $stmt->get_result();

// Verificando se encontrou o usuário
if ($result->num_rows > 0) {
    // Obtendo os dados do usuário
    $user = $result->fetch_assoc();
    // Exemplo de como acessar os dados
	
   $is_adm     =  $user['adm'];	
   $nome 	   =  $user['nome']; 
   $perfil_img =  $user['perfil_img'];
	
} else {
    echo "Usuário não encontrado!";
}

// Fechando a conexão
$stmt->close();
$conn->close();

}


// Verificar se o usuário está logado
if (isset($_SESSION['user_code'])) {
    load_user_data();
}



// Declaração da array global
global $livros;
$livros = []; // Inicializando a array global

function load_borrowed_books()
{
    global $livros; // Referência à array global
    $servername =  cfg_servername; // Substitua pelo seu servidor MySQL
    $username   =  cfg_username;      // Substitua pelo seu usuário MySQL
    $password   =  cfg_password;          // Substitua pela sua senha MySQL
    $dbname     =  cfg_dbname;  // Nome do banco de dados

    // Criando a conexão com o banco de dados
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Verificando a conexão
    if ($conn->connect_error) {
        die("Conexão falhou: " . $conn->connect_error);
    }

    // Código do usuário logado
    $codigo_usuario = $_SESSION['user_code'];

    // Query para selecionar os livros emprestados do usuário
    $sql = "SELECT codigo_usuario, codigo_livro FROM emprestados WHERE codigo_usuario = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $codigo_usuario);

    // Executando a query
    if ($stmt->execute()) {
        $result = $stmt->get_result();

        // Carregando os livros na array global
        while ($row = $result->fetch_assoc()) {
            $livros[] = $row; // Adiciona cada registro à array
        }
    } else {
        echo "Erro ao carregar os livros: " . $stmt->error;
    }

    // Fechando a conexão
    $stmt->close();
    $conn->close();
}

load_borrowed_books();


// Array global onde os dados dos livros serão armazenados
$livros_dados = [];

// Função que obtém os dados dos livros a partir do código do livro
function get_books_data_byCode($code)
{
    global $livros_dados;

    // Conexão com o banco de dados
    $servername =  cfg_servername; // Substitua pelo seu servidor MySQL
    $username   =  cfg_username;      // Substitua pelo seu usuário MySQL
    $password   =  cfg_password;          // Substitua pela sua senha MySQL
    $dbname     =  cfg_dbname;  // Nome do banco de dados

    // Criando a conexão com o banco de dados
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Verificando a conexão
    if ($conn->connect_error) {
        die("Conexão falhou: " . $conn->connect_error);
    }

    // Consulta para pegar os dados do livro com base no código
    $sql = "SELECT banner_link, nome, titulo, autor, data_lancamento, categoria, sinopse, descricao, codigo_livro, estrelas FROM livros WHERE codigo_livro = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $code); // Bind do código do livro
    $stmt->execute();
    $result = $stmt->get_result();

    // Verificando se algum livro foi encontrado
    if ($result->num_rows > 0) {
        // Armazenando os dados do livro na array global $livros_dados
        $livro = $result->fetch_assoc();
        $livros_dados = $livro;
    } else {
        echo "Livro não encontrado!";
    }

    // Fechando a conexão
    $stmt->close();
    $conn->close();
}

	


?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BeckerTeca - Emprestados</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

	
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
		
		  .avaliacao {
            margin-top: 10px;
        }

        .estrela {
            font-size: 24px;
            color: #ccc;
            cursor: pointer;
            transition: color 0.3s ease;
        }
		
		
		 .login-btn {
            background-color: transparent;
            color: white;
            border: none;
            font-size: 18px;
            cursor: pointer;
            padding: 10px 15px;
            margin-right: 60px;
            transition: background-color 0.3s ease, color 0.3s ease;
        }

        .login-btn:hover {
            background-color: #218838;
        }

        .login-btn i {
            margin-right: 8px;
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

		
		/* Estilizar a barra de rolagem */
.book-slider {
    overflow-x: auto; /* Garante que o scroll horizontal apareça */
    scrollbar-width: thin; /* Para navegadores compatíveis com o padrão */
    scrollbar-color: #28a745 #f4f4f9; /* Cor da barra e do fundo */
}

/* Estilização específica para navegadores WebKit */
.book-slider::-webkit-scrollbar {
    height: 8px; /* Altura da barra de rolagem horizontal */
}

.book-slider::-webkit-scrollbar-thumb {
    background-color: #aaa; /* Cor do "polegar" (a parte móvel da barra) */
    border-radius: 10px; /* Deixa a barra com bordas arredondadas */
}

.book-slider::-webkit-scrollbar-track {
    background-color: #ddd; /* Cor do fundo da barra */
    border-radius: 10px; /* Arredondamento do fundo */
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
		
		
		
		/* Estilos Gerais */
body {
    font-family: Arial, sans-serif;
    background-color: #f4f4f9;
    margin: 0;
    padding: 0;
}

#SECTION_USER {
    width: 100%;
    max-width: 1200px;
    margin: 20px auto;
    padding: 20px;
}

/* Estilo do Perfil do Usuário */
.user-profile {
    text-align: center;
    margin-bottom: 30px;
}

.profile-img {
    width: 150px;
    height: 150px;
    border-radius: 50%;
    object-fit: cover;
    border: 3px solid #28a745;
}

h2 {
    font-size: 24px;
    color: #333;
    margin-top: 10px;
}

/* Estilo do título "Livros Emprestados" */
.borrowed-books-label {
    display: block;
    font-size: 22px;
    font-weight: bold;
    margin-bottom: 20px;
    color: #333;
    text-align: center;
}

/* Slider de Livros */
.book-slider {
    width: 100%;
    display: flex;
    overflow-x: auto;
    gap: 20px;
    padding-bottom: 20px;
    justify-content: flex-start; /* Modificado para garantir que os itens começam a partir da esquerda */
    scroll-behavior: smooth;
    padding-left: 20px; /* Adiciona um pequeno espaço à esquerda para evitar que o primeiro item fique cortado */
    box-sizing: border-box; /* Garantir que o padding seja considerado no cálculo da largura */
}

/* Ajustes para dispositivos móveis */
@media (max-width: 768px) {
    .book-slider {
        padding-left: 10px; /* Menos espaço à esquerda em dispositivos menores */
    }
}


.book-card {
    background-color: #fff;
    border-radius: 8px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    padding: 15px;
    width: 200px;
    text-align: center;
    flex-shrink: 0;
}

.book-img {
    width: 100%;
    height: 250px;
    object-fit: cover;
   

 .modal {
        display: none; /* Escondido por padrão */
        position: fixed;
        z-index: 1000;
        left: 0;
        top: 0;
		
        width: 100%;
        height: 100%;
        overflow: auto;
        background-color: rgba(0, 0, 0, 0.5); /* Fundo transparente */
    }

    .modal-content {
        background-color: white;
        margin: 15% auto;
        padding: 20px;
        border: 1px solid #888;
        width: 80%;
        max-width: 400px;
        border-radius: 8px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        text-align: center;
    }

    .close {
        color: #aaa;
        float: right;
        font-size: 28px;
        font-weight: bold;
        cursor: pointer;
    }

    .close:hover,
    .close:focus {
        color: black;
        text-decoration: none;
        cursor: pointer;
    }

    #confirmBorrow {
        background-color: #28a745;
        color: white;
        padding: 10px 20px;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        font-size: 16px;
        margin-top: 20px;
    }

    #confirmBorrow:hover {
        background-color: #218838;
    }

	
	/* Estilo básico para os botões */
.btn {
    padding: 10px 20px;
    font-size: 16px;
    cursor: pointer;
    border: none;
    margin-top: 10px;
    display: block;
    width: 100%;
    text-align: center;
    background-color: #007bff;
    color: white;
    border-radius: 5px;
    transition: background-color 0.3s;
}

.btn:hover {
    background-color: #0056b3;
}

/* Estilo para o ícone */
.fa-arrow-right {
    margin-right: 8px;
}

	
    </style>
</head>
<body>

    <header>
         <h1 onclick="window.location='index.php';" style="cursor: pointer; font-size: 18px">BeckerTeca - Emprestados</h1>
	
		 <button onclick="window.location.href='logout.php';" class="login-btn">
                <i class="fas fa-sign-out-alt"></i> Sair
            </button>	 
    </header>
	


<section id="SECTION_USER">
  
    <div class="borrowed-books">
        <label class="borrowed-books-label"><h1><br>O que os leitores estão lendo...</h1></label>
       
	   
	   
	   <?php
	   
	   $borrowed_books = get_borrowed_books();

	// Verifica se existem livros emprestados e exibe os dados
	if (count($borrowed_books) > 0) 
	{
   

    // Itera sobre o array de livros emprestados e exibe cada um
    foreach ($borrowed_books as $book) {
      
	  $user_data = get_user_data_by_code($book['codigo_usuario']);
	  get_books_data_byCode($book['codigo_livro']);
		
echo "<div style='width: 300px; margin: 20px auto; text-align: center; border: 1px solid #ddd; border-radius: 10px; overflow: hidden; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); font-family: Arial, sans-serif;'>";

// Foto do usuário
echo "<div style='background-color: #f5f5f5; padding: 10px;'>";
echo "<img src='" . $user_data['perfil_img'] . "' style='width: 80px; height: 80px; border-radius: 50%; margin-bottom: 10px;'>";
echo "<p style='font-size: 16px; color: #555; margin: 0;'>" . $user_data['nome'] . "</p>";
echo "</div>";

// Banner do livro
echo "<div style='background-color: #fff;'>";
echo "<img src='" . $livros_dados['banner_link'] . "' style='width: 100%; height: 400px; object-fit: fill;'>";
echo "</div>";

// Informações do livro
echo "<div style='padding: 15px;'>";
echo "<h3 style='font-size: 18px; color: #333; margin: 0 0 10px;'>" . $livros_dados['titulo'] . "</h3>";
echo "<p style='font-size: 14px; color: #666; margin: 0;'><strong>Autor:</strong> " . $livros_dados['autor'] . "</p>";

// Estrelas dinâmicas
echo "<p style='font-size: 14px; color: #666; margin: 5px 0;'><strong>Estrelas:</strong> ";
$max_stars = 5; // Número máximo de estrelas
$current_stars = intval($livros_dados['estrelas']); // Estrelas do livro

// Estrelas preenchidas
for ($i = 0; $i < $current_stars; $i++) {
    echo "<span style='color: gold; font-size: 16px;'>&#9733;</span>"; // Unicode estrela preenchida
}

// Estrelas vazias
for ($i = $current_stars; $i < $max_stars; $i++) {
    echo "<span style='color: #ccc; font-size: 16px;'>&#9734;</span>"; // Unicode estrela vazia
}

echo "</p>";
echo "</div>";


echo "</div>";

	
	

   
	
      
    }

    echo "</table>";
	} else {
    echo "<center><p>Não há livros emprestados no momento.</p></center>";
	}


	?>
	   
	      
    </div>
	
</section>

<script>
	
    // Função para abrir o modal de aviso e de sucesso - AMBOS AO PEGAR UM LIVRO EMPRESTADO!
    function addToCart(livroId) {
        <?php if (!isset($_SESSION['user_code'])) : ?>
           document.body.insertAdjacentHTML('beforeend', '<div id="successModal" class="modal" style="display: block; position: fixed; z-index: 1000; left: 0; top: 0; width: 100%; height: 100%; background-color: rgba(0, 0, 0, 0.5);"><div class="modal-content" style="background-color: #fff; margin: 15% auto; padding: 20px; border: 1px solid #888; width: 50%; text-align: center; border-radius: 10px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);"><span class="close" style="color: #aaa; float: right; font-size: 28px; font-weight: bold; cursor: pointer;" onclick="this.closest(\'.modal\').remove()">&times;</span><h2>Aviso!</h2><p>Você precisa estar logado para pegar um livro emprestado!</p></div></div>');
        <?php else: ?>
            const modal = document.getElementById('borrowModal');
            modal.style.display = 'block';

            // Botão de confirmação
            const confirmButton = document.getElementById('confirmBorrow');
            confirmButton.onclick = function() {
                modal.style.display = 'none';
                //alert('Livro ID ' + livroId + ' emprestado com sucesso!');
                // Aqui você pode enviar uma requisição para o servidor para registrar o empréstimo
            };
        <?php endif; ?>
    }

    // Função para fechar o modal
    function closeModal() {
        const modal = document.getElementById('borrowModal');
        modal.style.display = 'none';
    }

    // Fechar o modal ao clicar fora dele
    window.onclick = function(event) {
        const modal = document.getElementById('borrowModal');
        if (event.target == modal) {
            modal.style.display = 'none';
        }
    };

	// FUNÇAO IMPORTANTE, NAO APAGUE!
	function atualizarCodigoLivro(codigoLivro) {
    // Atualiza o valor do input oculto
    document.getElementById("codigo_livro_modal").value = codigoLivro;
}


</script>

<?php

function get_borrowed_books() {
    // Variáveis de conexão com o banco de dados
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

    // Array para armazenar os registros dos livros emprestados
    $borrowed_books = [];

    // Query para obter os registros da tabela 'emprestados'
    $sql = "SELECT codigo_usuario, codigo_livro FROM emprestados";
    
    // Executando a query
    $result = $conn->query($sql);

    // Verificando se a consulta retornou resultados
    if ($result->num_rows > 0) {
        // Iterando sobre os resultados e armazenando no array
        while ($row = $result->fetch_assoc()) {
            // Adiciona cada registro de empréstimo ao array
            $borrowed_books[] = [
                'codigo_usuario' => $row['codigo_usuario'],
                'codigo_livro'   => $row['codigo_livro']
            ];
        }
    } else {
        // Caso não haja registros, podemos retornar um array vazio
        $borrowed_books = [];
    }

    // Fechando a conexão
    $conn->close();

    // Retornando o array com os registros
    return $borrowed_books;
}


function get_user_data_by_code($user_code) {
    // Variáveis de conexão com o banco de dados
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

    // Query para obter os dados do usuário com base no código
    $sql = "SELECT adm, nome, login, senha, codigo, idade, perfil_img, data_nascimento, cpf, rg FROM users WHERE codigo = ?";
    
    // Preparando a consulta
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_code); // O "i" indica que $user_code é um inteiro

    // Executando a consulta
    $stmt->execute();
    $result = $stmt->get_result();

    // Inicializando o array que armazenará os dados do usuário
    $user_data = [];

    // Verificando se encontrou o usuário
    if ($result->num_rows > 0) {
        // Obtendo os dados do usuário
        $user_data = $result->fetch_assoc();
    } else {
        // Caso não encontre o usuário, você pode retornar um array vazio
        $user_data = [];
    }

    // Fechando a conexão
    $stmt->close();
    $conn->close();

    // Retornando os dados do usuário ou um array vazio se não encontrado
    return $user_data;
}






?>


</body>
</html>

