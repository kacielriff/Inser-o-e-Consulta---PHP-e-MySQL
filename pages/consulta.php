<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/consulta.css">
    <link rel="stylesheet" href="../css/style.css">
    <title>Consulta</title>
</head>

<body>

    <div class="button-container">
        <a class="button" href="formulario.php">Inserir</a>
    </div>

    <?php
        require_once '../php/conexao.php';

        // define o número máximo de itens a serem exibidos por página.
        $limit = 20;

        // verifica se o parâmetro "page" está definido na URL e o converte em um número inteiro 
        // e não estiver definido, é atribuído o valor padrão de 1
        $page = isset($_GET['page']) ? intval($_GET['page']) : 1;

        // verifica se o número da página é válido
        if ($page <= 0) {
            echo '<p>Número de página inválido.</p>';
            exit;
        }

        // conta o número total de alunos no banco de dados
        $sqlCount = "SELECT COUNT(*) as total FROM cadastro_aluno";
        $queryCount = $conexao->query($sqlCount);
        $totalAlunos = $queryCount->fetchColumn();

        // calcula o número total de páginas
        $totalPaginas = ceil($totalAlunos / $limit);

        // Verifica se a página solicitada está dentro do intervalo válido
        if ($page > $totalPaginas) {
            echo '<p>Página não encontrada.</p>';
            exit; // Encerra a execução do script
        }

        // calcula o deslocamento necessário para a consulta SQL com base no 
        // número da página e no limite de itens por página
        $offset = ($page - 1) * $limit;

        $sql = "SELECT * FROM cadastro_aluno LIMIT $limit OFFSET $offset";
        $query = $conexao->query($sql);
        $alunos = $query->fetchAll(PDO::FETCH_ASSOC);

        // verifica se existem mais alunos para exibir ou se é possível exibir alunos anteriores 
        // se sim, são gerados botões de navegação usando o valor da página atual e os operadores + e - 
        // para ajustar o valor da página.
        if (count($alunos) > 0) {
            $table = '<div class="table-container">';
            $table .= '<table>';
            $table .= '<tr><th>Matrícula</th><th>Nome</th><th>Curso</th><th>Cidade</th><th>Idade</th></tr>';

            // gera as linhas da tabela com os resultados do banco
            foreach ($alunos as $aluno) {
                $table .= '<tr class="item">';
                $table .= '<td>' . $aluno['matricula'] . '</td>';
                $table .= '<td>' . $aluno['nome'] . '</td>';
                $table .= '<td>' . $aluno['curso'] . '</td>';
                $table .= '<td>' . $aluno['cidade'] . '</td>';
                $table .= '<td>' . $aluno['idade'] . '</td>';
                $table .= '</tr>';
            }

            $table .= '</table>';
            $table .= '</div>';

            echo $table;

            echo '<div class="button-container">';
            if ($page > 1) {
                echo '<a class="button small" href="?page=' . ($page - 1) . '"><< -20</a>';
            }
            if (count($alunos) >= $limit) {
                echo '<a class="button small" href="?page=' . ($page + 1) . '">+20 >></a>';
            }
            echo '</div>';
        } else {
            echo '<p>Nenhum aluno cadastrado.</p>';
        }

        // ele funciona como um site de listagem padrão, onde ele tem um limite chamado de offset
        // onde ele define o máximo de elemento carregados por página
        
        // o offset é multiplicado pela pagina em si, se passar um numero para o page no href ele vai
        // tentar carregar e possivelmente quebrar se não existirem elementos no banco atraves do offset
        
        // é um erro que pode ser tratado com algumas verificacoes, se a page for = 0 ou
        // page * offser > que o numero de elementos no banco ele vai dar erro, no primeiro
        // caso um erro de sintaxe no sql e no segundo caso ele vai cair no else que diz que nao
        // há alunos

        // tentei uma solução com algumas verificações como page <= 0 e uma query auxiliar para
        // verificar o número de paginas maximas, mas ainda assim pode quebrar caso haja inserção 
        // de dados manuais no banco
        
        // é facilmente modular, podendo ser adicionado botões para ordenar por matrícula, nome, curso
        // cidade e idade de forma crescente e decrescente
        
        // o design da tabela não está fixo e tem alguns problemas nela, não consegui resolver porque
        // estou sem energia elétrica desde 12/07, acabou por volta das 22 horas e to
        // fazendo na corrida com o gerador ligado
    ?>

</body>

</html>