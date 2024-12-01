<?php

require 'server_configs.php';

session_start();

// Variáveis globais para as configurações do site etc...
$titulo_site = '';
$idioma = '';
$adm_login = '';
$adm_senha = '';
$habilitar_categorias = '';
$habilitar_livros = '';
$habilitar_logins = '';
$habilitar_manutencao = '';
$habilitar_avaliacao = '';
$habilitar_botao_comprar = '';
$habilitar_botao_emprestar = '';

function load_config_data() 
{
    global $titulo_site, $idioma, $adm_login, $adm_senha, $habilitar_categorias, $habilitar_livros, $habilitar_logins, $habilitar_manutencao, $habilitar_avaliacao, $habilitar_botao_comprar, $habilitar_botao_emprestar;

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

    // Query para obter os dados da tabela "configuracoes"
    $sql = "SELECT titulo_site, idioma, adm_login, adm_senha, habilitar_categorias, habilitar_livros, habilitar_logins, habilitar_manutencao, habilitar_avaliacao, habilitar_botao_comprar, habilitar_botao_emprestar FROM configuracoes WHERE id = 1"; // Supondo que exista uma coluna 'id' para filtrar a configuração
    $stmt = $conn->prepare($sql);

    // Executando a query
    $stmt->execute();
    $result = $stmt->get_result();

    // Verificando se encontrou as configurações
    if ($result->num_rows > 0) {
        // Obtendo os dados da configuração
        $config = $result->fetch_assoc();
        
        // Atribuindo os valores às variáveis globais
        $titulo_site 			   = $config['titulo_site'];
        $idioma 				   = $config['idioma'];
        $adm_login 				   = $config['adm_login']; //TA NA DB O LOGIN
        $adm_senha   		  	   = $config['adm_senha']; //TA NA DB TBM A SENHA
        $habilitar_categorias      = $config['habilitar_categorias'];
        $habilitar_livros 		   = $config['habilitar_livros'];
        $habilitar_logins 		   = $config['habilitar_logins'];
        $habilitar_manutencao 	   = $config['habilitar_manutencao']; //MSMA COISA DO QUE ESTA EMBAIXO
        $habilitar_avaliacao	   = $config['habilitar_avaliacao'];
        $habilitar_botao_comprar   = $config['habilitar_botao_comprar']; // ESSE É CASO QUEIRA USAR, NO CASO É PARA USO FUTURO....
        $habilitar_botao_emprestar = $config['habilitar_botao_emprestar'];
        
    } else {
        echo "Configurações não encontradas!";
    }

    // Fechando a conexão
    $stmt->close();
    $conn->close();
}

load_config_data(); //carrega variaveis do site

if ($habilitar_manutencao) 
{ exit(); } //EM CASO DE MANUTENÇÃO, NAO CARREGARÁ NADA!

$nome = '';
$perfil_img = '';
function load_user_data() //carrega variaveis do usuario obtidos pelo codigo ao logar
{
  global $nome, $perfil_img;
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
$sql = "SELECT nome, login, senha, codigo, idade, perfil_img FROM users WHERE codigo = ?";
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
	
   $nome 	   = $user['nome']; 
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

// Verificando se foi enviado um termo de busca
$busca = isset($_GET['busca']) ? $_GET['busca'] : '';

// Consulta para buscar livros - PARA A BARRA DE PESQUISA!
if ($busca) {
    // Se houver termo de busca, filtra os resultados
    $sql = "SELECT id, disponivel, codigo_livro, nome, titulo, autor, banner_link, ROUND(AVG(estrelas), 1) as estrelas 
            FROM livros 
            WHERE nome LIKE '%$busca%' 
            OR titulo LIKE '%$busca%' 
            OR autor LIKE '%$busca%' 
            OR codigo_livro LIKE '%$busca%' 
            GROUP BY id";
} else {
    // Se não houver termo de busca, busca todos os livros
    $sql = "SELECT id, disponivel, codigo_livro, nome, titulo, autor, banner_link, ROUND(AVG(estrelas), 1) as estrelas 
            FROM livros 
            GROUP BY id";
}

$result = $conn->query($sql);

// Verificando se há resultados
$livros = [];
if ($result->num_rows > 0) {
    // Convertendo os resultados para o formato de array
    while($row = $result->fetch_assoc()) {
        $livros[] = [
            'id' => strval($row['id']),
            'codigo_livro' => strval($row['codigo_livro']),
            'disponivel' => strval($row['disponivel']),
            'nome' => strval($row['nome']),
            'titulo' => strval($row['titulo']),
            'autor' => strval($row['autor']),
            'banner_link' => strval($row['banner_link']),
            'estrelas' => strval($row['estrelas']) // Garantindo que a média seja tratada como texto
        ];
    }
} else {
    echo "<script>document.getElementById('NO_BOOK').style.display = 'flex';</script>";
}

// Fechando a conexão
$conn->close();
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $titulo_site; ?></title>
    <!-- Link para o Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
	
    <style>
        /* Estilos que você já possui */
        body {
            font-family: Arial, sans-serif;
            text-align: center;
            margin: 0;
            padding: 0;
            background-color: #f4f4f9;
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
        }

        header h1 {

            margin: 0;
            padding-left: 0px;
        }

        .login-btn {
            background-color: transparent;
            color: white;
            border: none;
            font-size: 18px;
            cursor: pointer;
            padding: 10px 15px;
            margin-left: 20px;
            transition: background-color 0.3s ease, color 0.3s ease;
        }

        .login-btn:hover {
            background-color: #218838;
        }

        .login-btn i {
            margin-right: 8px;
        }

.search-bar {
    display: flex;
    align-items: center;
    background-color: white;
    border-radius: 5px;
    padding: 5px 15px;
    width: 100%;
    max-width: 400px;
    margin: 20px auto;
}

.search-bar form {
    display: flex;
    width: 100%;
    align-items: center; /* Garante que os itens dentro do form fiquem alinhados verticalmente */
}

.search-bar input {
    border: none;
    outline: none;
    padding: 8px;
    width: 100%; /* O input vai ocupar o máximo de espaço disponível */
    font-size: 16px;
}

.search-bar button {
    background-color: transparent;
    border: none;
    color: #28a745;
    cursor: pointer;
    font-size: 20px;
    padding: 8px;
}

        .categories {
            margin: 20px 0;
            display: flex;
            justify-content: center;
            flex-wrap: nowrap;
            overflow-x: auto;
        }

        .category-btn {
            padding: 8px 15px;
            margin: 5px;
            font-size: 14px;
            background-color: white;
            border: 2px solid #555;
            border-radius: 5px;
            color: #555;
            cursor: pointer;
            transition: background-color 0.3s ease, color 0.3s ease;
            white-space: nowrap;
        }

        .category-btn:hover {
            background-color: #0d0;
        }
		
		 .category-btn:click {
            background-color: #0f0;
        }

        .category-btn.selected {
            background-color: #b6e8b1;
            color: #333;
            border: 2px solid #28a745;
        }

        h2 {
            color: #333;
        }

        .livros {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 20px;
        }

        .livro {
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            padding: 15px;
            width: calc(25% - 20px);
            text-align: center;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .livro:hover {
            transform: scale(1.05);
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.15);
        }

        .livro img {
            width: 100%;
            height: 350px; 
            object-fit: fill;
            border-radius: 5px;
        }

        .livro h3 {
            font-size: 20px;
            color: #333;
            margin: 15px 0;
        }

        .livro button {
            padding: 10px 15px;
            background-color: #28a745;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
        }

        .livro button:hover {
            background-color: #218838;
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

        .estrela.checked {
            color: #ffb800;
        }

        @media (max-width: 768px) {
            .livro {
                width: calc(33.33% - 20px);
            }

            .search-bar {
                max-width: 300px;
            }

            .category-btn {
                font-size: 12px;
                padding: 6px 12px;
            }

            .categories {
                justify-content: flex-start;
            }
        }

        @media (max-width: 480px) {
            .livro {
                width: calc(50% - 20px);
            }

            .category-btn {
                font-size: 10px;
                padding: 5px 10px;
            }

            .search-bar {
                max-width: 260px;
            }
        }
		
.user-profile {		
    display: flex;
    align-items: center;
    gap: 5px; /* Espaço entre a imagem e o botão */
}

.profile-image {
	
    width: 38px; /* Tamanho da imagem */
	
    height: 35px; /* Tamanho da imagem */
    border-radius: 50%; /* Torna a imagem redonda */
    object-fit: fill; /* Garante que a imagem preencha o espaço sem deformar */
    border: 1px solid #888; /* Borda branca para destaque */
	
}

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

    </style>

</head>
<body>

<header>
    <h1 onclick="window.location='index.php';" style="cursor: pointer; font-size: 18px">BeckerTeca</h1>

    <?php if (!isset($_SESSION['user_code'])): ?>
        <!-- Exibe o botão de login somente se o usuário não estiver logado -->
        <button onclick="window.location.href='login.php';" class="login-btn">
            <i class="fas fa-sign-in-alt"></i> Login
        </button>
    <?php else: ?>
        <!-- Exibe a imagem do perfil se o usuário estiver logado -->
        <div class="user-profile">
           <img src="<?php echo isset($perfil_img) && !empty($perfil_img) ? $perfil_img : 'https://www.shutterstock.com/image-vector/blank-avatar-photo-place-holder-600nw-1095249842.jpg'; ?>" 
     alt="Imagem do usuário" 
     onclick="window.location.href='./profile.php';" 
     class="profile-image" 
     style="cursor: pointer;">

			<p style=" top:43px; font-size: 13px;" class="user-name"><?php echo $nome; ?></p>
            <button onclick="window.location.href='./logout.php';" class="login-btn">
                <i class="fas fa-sign-out-alt"></i> Sair
            </button>
        </div>
    <?php endif; ?>
</header>





<!-- Modal de Emprestimo do livro, NÃO REMOVA ESSA PARTE! -->
<div id="borrowModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeModal()">&times;</span>
        <h2>Confirmação</h2>
        <p>Deseja realmente pegar este livro emprestado?</p>
		
		<form method="POST" >
		<input type="hidden" id="codigo_livro_modal" name="codigo_livro" value="">
        <button type="submit" name="confirmBorrow" id="confirmBorrow">Confirmar</button>
		</form>
		
    </div>
</div>

    <h2>Livros Disponíveis</h2>

    <div class="search-bar">
        <form method="get">
            <input type="text" name="busca" id="searchInput" placeholder="Buscar por título, autor, descrição etc..." value="<?php echo htmlspecialchars($busca); ?>">
            <button type="submit" id="searchButton"><i class="fas fa-search"></i></button>
        </form>
    </div>
	

 <section id="SECTION1">

    <?php
if ($habilitar_categorias == 1) 
{
    echo '<div class="categories">';
    echo '<button class="category-btn">Contos</button>';
    echo '<button class="category-btn">Dark Romance</button>';
    echo '<button class="category-btn">Romance</button>';
    echo '<button class="category-btn">Motivação</button>';
    echo '<button class="category-btn">Educativo</button>';
    echo '</div>';
}
?>

<!-- AQUI PEGA OS LIVROS ENCONTRADOS E COMEÇA A INSERIR NA PÁGINA! --->
    <div class="livros">
        <?php foreach ($livros as $livro): ?>
            <div class="livro">
                <img onclick="" src="<?php echo $livro['banner_link']; ?>" alt="Capa do Livro">
                <h2><?php echo htmlspecialchars($livro['titulo']); ?></h2>
				 <br><span style="color:#555;"><?php echo htmlspecialchars($livro['autor']); ?></span><p>

              <?php
			  
			  
   if ($habilitar_avaliacao == 1) 
   {
    echo '<div class="avaliacao">';
    $estrelas = (int)$livro['estrelas'];
    for ($i = 1; $i <= 5; $i++) {
        echo '<span class="estrela' . ($i <= $estrelas ? ' checked' : '') . '" data-value="' . $i . '">&#9733;</span>';
    }
    echo '</div>';
   }


?>

				
				
<?php
if ($habilitar_botao_emprestar == 1) {
   echo "<button class='btn-emprestar' 
                data-codigo-livro=\"" . $livro['codigo_livro'] . "\" 
                onclick=\"addToCart(" . $livro['id'] . ", '" . $livro['codigo_livro'] . "'); atualizarCodigoLivro('" . $livro['codigo_livro'] . "')\">
                <i class='fas fa-cart-plus'></i> Pegar emprestado
         </button>";
}

?> 



				
            </div>
        <?php endforeach; ?>
    </div>

<p>
<p>
    
</section>
	
	
	
	<div id="NO_BOOK">
	<h3> NENHUM RESULTADO ENCONTRADO! </h3>
	</div>
	
  
  
  
  <script>
	

	
	document.getElementById('NO_BOOK').style.display = 'none'; // Oculta a div

        // // Função para adicionar livro ao carrinho
        // function addToCart(livroId) {
            // alert('Livro ID ' + livroId + ' adicionado ao carrinho!');
        // }
    

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

//DETECTA AO USUÁRIO PEGAR UM LIVRO EMPRESTADO CLICANDO NO CONFIRMAR DO MODAL

if ($_SERVER['REQUEST_METHOD'] === 'POST') 
{
	
	if (isset($_POST['confirmBorrow'])) 
	{	
    $codigoLivro = isset($_POST['codigo_livro']) ? $_POST['codigo_livro'] : 'Não definido';
	
	
   $conn = new mysqli("localhost", "root", "", "livraria");

// Verificar conexão
if ($conn->connect_error) {
    die("Falha na conexão: " . $conn->connect_error);
}

// Valores das variáveis
$codigo = 1; // Código do usuário (exemplo)
$codigoLivro = $_POST['codigo_livro']; // Pegando o valor do formulário

// Preparar a consulta SQL
$sql = "INSERT INTO emprestados (codigo_usuario, codigo_livro) VALUES (?, ?)";

// Preparar o statement
$stmt = $conn->prepare($sql);

// Verificar se a preparação deu certo
if ($stmt === false) {
    die("Erro ao preparar o statement: " . $conn->error);
}

// Bind dos parâmetros
$stmt->bind_param("ii", $_SESSION['user_code'], $codigoLivro);

// Executar o statement
if ($stmt->execute()) {
	
	//QUANDO O LIVRO FOR PEGO EMPRESTADO, ELE CRIA UM MODAL DE AVISO.
    echo '
    <div id="successModal" class="modal" style="display: block;">
        <div class="modal-content">
            <span class="close" onclick="closeModal()">&times;</span>
            <h2>Sucesso!</h2>
            <p>O livro foi emprestado com sucesso!</p>
        </div>
    </div>
    <script>
        // Função para fechar o modal
        function closeModal() {
            document.getElementById("successModal").style.display = "none";
        }
    </script>
    ';
		
	}
		
} }

?>

</body>
</html>
