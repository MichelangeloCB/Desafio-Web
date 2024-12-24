<?php
/**
 * Template Name: Cadastro de Categorias
 */
get_header(); // Inclui o cabeçalho do tema
?>

<div class="container">
    <h1>Cadastrar Categoria</h1>
    <form id="form-cadastro-categoria">
        <label for="nome_categoria">Nome da Categoria:</label>
        <input type="text" id="nome_categoria" name="nome_categoria" required>
        <button type="submit">Cadastrar</button>
    </form>

    <div id="mensagem" style="margin-top: 20px; font-weight: bold;"></div>
</div>

<script>
document.getElementById('form-cadastro-categoria').addEventListener('submit', function(event) {
    event.preventDefault(); // Evita o envio padrão do formulário

    // Obtém o valor do campo de texto
    const nomeCategoria = document.getElementById('nome_categoria').value;

    // Faz a requisição para a API usando fetch
    fetch('<?php echo esc_url(home_url('/wp-json/custom/v1/categorias')); ?>', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({
            nome: nomeCategoria, // Parâmetro enviado para a API
        }),
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Erro na resposta da API');
        }
        return response.json(); // Converte a resposta para JSON
    })
    .then(data => {
        if (data.success) {
            // Sucesso: Mostra mensagem e limpa o formulário
            document.getElementById('mensagem').textContent = 'Categoria cadastrada com sucesso!';
            document.getElementById('mensagem').style.color = 'green';
            document.getElementById('form-cadastro-categoria').reset();
        } else {
            // Exibe mensagem de erro da API
            document.getElementById('mensagem').textContent = data.message || 'Erro ao cadastrar a categoria.';
            document.getElementById('mensagem').style.color = 'red';
        }
    })
    .catch(error => {
        // Erro inesperado
        console.error('Erro ao cadastrar categoria:', error);
        document.getElementById('mensagem').textContent = 'Erro ao cadastrar a categoria. Tente novamente.';
        document.getElementById('mensagem').style.color = 'red';
    });
});
</script>




<?php get_footer(); // Inclui o rodapé do tema ?>
