<?php
require 'server_configs.php';

session_start();


// Verificar se o usuário está logado
if (!isset($_SESSION['user_code'])) {
    exit(); //PAGINA NAO CARREGARÁ POIS NAO TEM NGM LOGADO - É PARA PREVENÇÃO DE ERROS E ROUBO DE DADOS
}

function get_admin_users()
{
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
function get_all_books() {
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



$emprestados = get_total_emprestados();
function get_total_emprestados() {
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

    // Query para contar os registros da tabela 'emprestados'
    $sql = "SELECT COUNT(*) as total FROM emprestados";

    // Executando a query
    $result = $conn->query($sql);

    // Verificando se obteve resultado
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $total = $row['total']; // Obtém o total do resultado
    } else {
        $total = 0; // Caso não haja registros
    }

    // Fechando a conexão
    $conn->close();

    // Retornando o total de emprestados
    return $total;
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


createModal("Painel de Controle", "Aqui você poderá alterar, remover e acrescentar dados e funcionalidades ao site!", "OK");

?>





<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BeckerTeca - Painel</title>
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
         <h1 onclick="window.location='index.php';" style="cursor: pointer; font-size: 20px"><?php echo $configurations['titulo_site']; ?> - Painel ADM</h1>
    </header>
	
	
<section id="CONTROLS">

<p>
    <!-- Ferramentas da Livraria -->
    <div class="tool-section">
        <h2>Ferramentas da Livraria</h2>
		
        <button onclick="window.location.href='./newbook.php';"   >Inserir Novo Livro</button>
        <button onclick="window.location.href='./modifybook.php';">Modificar Livro</button>
        <button onclick="window.location.href='./removebook.php';">Remover Livro</button>
        <button onclick="window.location.href='./remove_all_books.php';">Remover Todos os Livros</button>
        <div class="info">
            <strong>Quantidade de Livros:</strong> <span id="book-count"><?php echo count($livros); ?></span>
        </div>
        <div class="info">
            <strong>Livros Emprestados:</strong> <span id="borrowed-books"><?php echo ($emprestados); ?></span>
        </div>
    </div>


    <!-- Controle de Usuários -->
    <div class="tool-section">
        <h2>Controle de Usuários</h2>
		
        <button onclick="window.location.href='./user_manager.php';">Gerenciar Usuário</button>
        <button>Remover Usuário</button>
        <button onclick="window.location.href='./remove_all_users.php';">Remover Todos os Usuários</button>
    </div>




<form method="POST">
    <!-- Configurações do Site -->
    <div class="tool-section">
        <h2>Configurações do Site</h2>
        <label for="site-title">Título do Site:</label>
        <div>
            <input type="text" id="site-title" name="site-title" placeholder="Digite o título do site" value="<?php echo $configurations['titulo_site']; ?>" />
        </div>
      <div class="radio-group">
    <label>Habilitar Categorias:</label>
    <label><input type="radio" name="categories" value="sim" <?php echo isset($configurations['habilitar_categorias']) && $configurations['habilitar_categorias'] == 1 ? 'checked' : ''; ?> /> Sim</label>
    <label><input type="radio" name="categories" value="nao" <?php echo isset($configurations['habilitar_categorias']) && $configurations['habilitar_categorias'] == 0 ? 'checked' : ''; ?> /> Não</label>
</div>

<div class="radio-group">
    <label>Habilitar Livros:</label>
    <label><input type="radio" name="books" value="sim" <?php echo isset($configurations['habilitar_livros']) && $configurations['habilitar_livros'] == 1 ? 'checked' : ''; ?> /> Sim</label>
    <label><input type="radio" name="books" value="nao" <?php echo isset($configurations['habilitar_livros']) && $configurations['habilitar_livros'] == 0 ? 'checked' : ''; ?> /> Não</label>
</div>

<div class="radio-group">
    <label>Habilitar Avaliação:</label>
    <label><input type="radio" name="rating" value="sim" <?php echo isset($configurations['habilitar_avaliacao']) && $configurations['habilitar_avaliacao'] == 1 ? 'checked' : ''; ?> /> Sim</label>
    <label><input type="radio" name="rating" value="nao" <?php echo isset($configurations['habilitar_avaliacao']) && $configurations['habilitar_avaliacao'] == 0 ? 'checked' : ''; ?> /> Não</label>
</div>

<div class="radio-group">
    <label>Habilitar Botão Emprestar:</label>
    <label><input type="radio" name="borrow" value="sim" <?php echo isset($configurations['habilitar_botao_emprestar']) && $configurations['habilitar_botao_emprestar'] == 1 ? 'checked' : ''; ?> /> Sim</label>
    <label><input type="radio" name="borrow" value="nao" <?php echo isset($configurations['habilitar_botao_emprestar']) && $configurations['habilitar_botao_emprestar'] == 0 ? 'checked' : ''; ?> /> Não</label>
</div>

<div class="radio-group">
    <label>Entrar em Manutenção</label>
    <label><input type="radio" name="manu" value="sim" <?php echo isset($configurations['habilitar_manutencao']) && $configurations['habilitar_manutencao'] == 1 ? 'checked' : ''; ?> /> Sim</label>
    <label><input type="radio" name="manu" value="nao" <?php echo isset($configurations['habilitar_manutencao']) && $configurations['habilitar_manutencao'] == 0 ? 'checked' : ''; ?> /> Não</label>
</div>

        
        <button type="submit">SALVAR ALTERAÇÕES</button>
    </div>
</form>

	
<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Detectando o título do site
    if (isset($_POST['site-title'])) {
        
        $NovoTitulo = $_POST['site-title'];
		

        // Verificando e mapeando 'sim' para 1 e 'não' para 0, se não definido, considera 0
        $HabCategorias = isset($_POST['categories']) && $_POST['categories'] == 'sim' ? 1 : 0;

        $HabLivros 	  = isset($_POST['books']) && $_POST['books']   == 'sim' ? 1 : 0;

        $HabAvaliacao = isset($_POST['rating']) && $_POST['rating'] == 'sim' ? 1 : 0;

        $HabEmprestar = isset($_POST['borrow']) && $_POST['borrow'] == 'sim' ? 1 : 0;
		
		$HabManutencao = isset($_POST['manu']) && $_POST['manu']     == 'sim' ? 1 : 0;
		
	
		
    $servername =  cfg_servername; // Substitua pelo seu servidor MySQL
    $username   =  cfg_username;      // Substitua pelo seu usuário MySQL
    $password   =  cfg_password;          // Substitua pela sua senha MySQL
    $dbname     =  cfg_dbname;  // Nome do banco de dados

		// Cria a conexão
		$conn = new mysqli($servername, $username, $password, $dbname);

		// Checa se a conexão foi bem-sucedida
		if ($conn->connect_error) {
			die("Falha na conexão: " . $conn->connect_error);
		}

		// Defina os novos valores para as colunas
		$titulo_site 			   = $NovoTitulo;
		$habilitar_categorias      = $HabCategorias; // 1 para habilitar, 0 para desabilitar
		$habilitar_livros          = $HabLivros;
		$habilitar_manutencao      = $HabManutencao;
		$habilitar_avaliacao 	   = $HabAvaliacao;
		$habilitar_botao_emprestar = $HabEmprestar;

		// A consulta SQL para atualizar os dados na tabela 'configuracoes'
		$sql = "UPDATE configuracoes 
				SET titulo_site = ?, 
					habilitar_categorias = ?, 
					habilitar_livros = ?, 
					habilitar_manutencao = ?, 
					habilitar_avaliacao = ?, 
					habilitar_botao_emprestar = ? 
				    WHERE id = 1"; // Considerando que há apenas uma linha na tabela, usando WHERE id = 1

		// Prepara e executa a consulta
		$stmt = $conn->prepare($sql);
		$stmt->bind_param("siiiii", $titulo_site, $habilitar_categorias, $habilitar_livros, $habilitar_manutencao, $habilitar_avaliacao, $habilitar_botao_emprestar);

		if ($stmt->execute()) {
			createModal("Aviso", "Informações alteradas com sucesso!", "OK");
		} else {
			echo "Erro ao atualizar configurações: " . $stmt->error;
		}

		// Fecha a conexão
		$stmt->close();
		$conn->close();


    }
}
?>


	
	
</section>





</body>
</html>

