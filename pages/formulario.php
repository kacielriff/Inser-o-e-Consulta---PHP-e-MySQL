<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/formulario.css">
    <link rel="stylesheet" href="../css/style.css">
    <title>Formulário</title>
</head>

<body>
    <?php
    require_once '../php/conexao.php';

    $mensagem = '';
    $camposInvalidos = array();

    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        $nome = isset($_POST["nome"]) ? $_POST["nome"] : '';
        $curso = isset($_POST["curso"]) ? $_POST["curso"] : '';
        $cidade = isset($_POST["cidade"]) ? $_POST["cidade"] : '';
        $idade = isset($_POST["idade"]) ? $_POST["idade"] : '';

        // Validação dos campos
        if (empty($nome) || empty($curso) || empty($cidade) || empty($idade)) {
            $mensagem = "Por favor, preencha todos os campos.";
            $camposInvalidos = array('nome', 'curso', 'cidade', 'idade');
        } else {
            if (!preg_match("/^[A-Za-zÀ-ÖØ-öø-ÿ\s]+$/", $nome)) {
                $camposInvalidos[] = 'nome';
                $mensagem = "O campo Nome só deve conter letras e espaços.";
                $nome = '';
            }
            if (!preg_match("/^[A-Za-zÀ-ÖØ-öø-ÿ\s]+$/", $curso)) {
                $camposInvalidos[] = 'curso';
                $mensagem = "O campo Curso só deve conter letras e espaços.";
                $curso = '';
            }
            if (!preg_match("/^[A-Za-zÀ-ÖØ-öø-ÿ\s]+$/", $cidade)) {
                $camposInvalidos[] = 'cidade';
                $mensagem = "O campo Cidade só deve conter letras e espaços.";
                $cidade = '';
            }
            if (!is_numeric($idade) || $idade < 0 || $idade > 150) {
                $camposInvalidos[] = 'idade';
                $mensagem = "A idade deve ser um número válido entre 0 e 150.";
                $idade = '';
            }

            if (empty($camposInvalidos)) {
                $sql = "INSERT INTO cadastro_aluno (nome, curso, cidade, idade) VALUES (:nome, :curso, :cidade, :idade)";
                $query = $conexao->prepare($sql);
                $query->bindParam(':nome', $nome);
                $query->bindParam(':curso', $curso);
                $query->bindParam(':cidade', $cidade);
                $query->bindParam(':idade', $idade);

                if ($query->execute()) {
                    $mensagem = 'Registro bem-sucedido!';
                    $nome = $curso = $cidade = $idade = '';
                } else {
                    $mensagem = 'Erro ao inserir os dados.';
                }
            }
        }

        echo '<script>alert("' . $mensagem . '");</script>';
    }
    ?>

    <div class="button-container">
        <a class="button" href="consulta.php">Consultar</a>
    </div>

    <div class="container">
        <div class="img-container">
            <img src="../images/register.svg" alt="Register Image">
        </div>

        <div class="form-container">
            <h2>Registrar aluno</h2>

            <form action="" method="post">
                <div class="input-group">
                    <label for="nome">Nome Completo</label>
                    <input type="text" id="nome" name="nome" placeholder="Digite o seu nome completo" value="<?php echo isset($nome) ? $nome : ''; ?>" <?php if (in_array('nome', $camposInvalidos)) echo 'class="error-field"'; ?>>
                </div>

                <div class="input-group">
                    <label for="curso">Curso</label>
                    <input type="text" id="curso" name="curso" placeholder="Digite o seu curso" value="<?php echo isset($curso) ? $curso : ''; ?>" <?php if (in_array('curso', $camposInvalidos)) echo 'class="error-field"'; ?>>
                </div>

                <div class="input-group">
                    <label for="cidade">Cidade</label>
                    <input type="text" id="cidade" name="cidade" placeholder="Digite a sua cidade" value="<?php echo isset($cidade) ? $cidade : ''; ?>" <?php if (in_array('cidade', $camposInvalidos)) echo 'class="error-field"'; ?>>
                </div>

                <div class="input-group">
                    <label for="idade">Idade</label>
                    <input type="number" id="idade" name="idade" placeholder="Digite a sua idade" min="0" max="150" value="<?php echo isset($idade) ? $idade : ''; ?>" <?php if (in_array('idade', $camposInvalidos)) echo 'class="error-field"'; ?>>
                </div>

                <div class="divider"></div>

                <div class="input-group">
                    <button class="small">Cadastrar</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Função para preencher os campos do formulário com dados
        function preencherFormulario() {
            document.getElementById('nome').value = 'João Silva';
            document.getElementById('curso').value = 'Engenharia';
            document.getElementById('cidade').value = 'São Paulo';
            document.getElementById('idade').value = 25;
        }
        
        // Chamada da função para preencher o formulário
        preencherFormulario();
    </script>
</body>

</html>