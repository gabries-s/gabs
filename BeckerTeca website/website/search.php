<?phpl
require 'server_configs.php';


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

$conn->set_charset("utf8mb4");

// Pegando o termo de busca da URL com o parâmetro GET
$termoBusca = isset($_GET['busca']) ? $_GET['busca'] : ''; // Se não houver, termoBusca será vazio

// Consulta para buscar livros que contenham a palavra-chave nas colunas nome, titulo e descricao
$sql = "SELECT id, titulo, nome, descricao, banner_link, estrelas FROM livros WHERE
        titulo LIKE '%$termoBusca%' OR nome LIKE '%$termoBusca%' OR descricao LIKE '%$termoBusca%'";

// Executando a consulta
$result = $conn->query($sql);

// Verificar se a consulta falhou
if (!$result) {
    die("Erro na consulta SQL: " . $conn->error);
}

// Verificando se há resultados
$livros = [];
if ($result->num_rows > 0) {
    // Convertendo os resultados para o formato de array
    while ($row = $result->fetch_assoc()) {
        $livros[] = [
            'id' => $row['id'],
            'titulo' => strval($row['titulo']),
            'nome' => strval($row['nome']),
            'descricao' => strval($row['descricao']),
            'banner_link' => strval($row['banner_link']),
            'estrelas' => (int)$row['estrelas'] // A quantidade de estrelas
        ];
    }
} else {
    $livros = [];
}

// Fechando a conexão
$conn->close();
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <!-- Link para o Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            text-align: center;
            margin: 0;
            padding: 0;
            background-color: #f4f4f9;
        }

        header {
            color: white;
            padding: 15px 30px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        header h1 {
            margin: 0;
            padding-left: 10px;
        }

        h2 {
            color: #333;
            margin-top: 30px;
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

    </style>
</head>
<body>

    <header>
        <!-- Adicione um formulário para buscar livros -->
        <form method="GET" action="index.php">
          
        </form>
    </header>

    <h2>Resultado da sua busca por "<?php echo htmlspecialchars($termoBusca); ?>"</h2>

    <div class="livros">
        <?php if (!empty($livros)): ?>
            <?php foreach ($livros as $livro): ?>
                <div class="livro">
                    <img src="<?php echo $livro['banner_link']; ?>" alt="Capa do Livro">
                    <h3><?php echo htmlspecialchars($livro['titulo']); ?></h3>
                    <p><em><?php echo $livro['descricao']; ?></em></p>

                    <!-- Exibição das estrelas -->
                    <div class="avaliacao">
                        <?php
                        $estrelas = $livro['estrelas'];
                        for ($i = 1; $i <= 5; $i++) {
                            echo '<span class="estrela' . ($i <= $estrelas ? ' checked' : '') . '" data-value="' . $i . '">&#9733;</span>';
                        }
                        ?>
                    </div>

                    <!-- Botão de pegar emprestado -->
                    <button onclick="addToCart(<?php echo $livro['id']; ?>)">
                        <i class="fas fa-cart-plus"></i> Pegar emprestado
                    </button>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>Nenhum livro encontrado com o termo "<?php echo htmlspecialchars($termoBusca); ?>".</p>
        <?php endif; ?>
    </div>

    <script>
        // Função para adicionar ao carrinho (apenas um exemplo, você pode adaptá-la conforme necessário)
        function addToCart(livroId) 
		{
            alert("Livro com ID " + livroId + " adicionado ao carrinho de empréstimos.");
        }
    </script>

</body>
</html>
