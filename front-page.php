<?php
/* Template Name: Cadastro de Categorias e Notícias */

get_header();
?>

<h1>Cadastro de Categoria e Notícia</h1>

<!-- Formulário para cadastrar categoria -->
<h2>Cadastro de Categoria</h2>
<form id="form-cadastrar-categoria">
    <label for="nome_categoria">Nome da Categoria:</label>
    <input type="text" id="nome_categoria" name="nome_categoria" required>
    
    <button type="submit">Cadastrar Categoria</button>
</form>

<!-- Formulário para cadastrar notícia -->
<h2>Cadastro de Notícia</h2>
<form id="form-cadastrar-noticia">
    <label for="titulo">Título da Notícia:</label>
    <input type="text" id="titulo" name="titulo" required>

    <label for="conteudo">Conteúdo da Notícia:</label>
    <textarea id="conteudo" name="conteudo" required></textarea>

    <label for="imagem">Imagem da Notícia:</label>
    <input type="file" id="imagem" name="imagem">

    <label for="categoria">Categoria:</label>
    <select id="categoria" name="categoria" required>
        <!-- As categorias serão carregadas via JavaScript -->
    </select>

    <label for="data_publicacao">Data de Publicação:</label>
    <input type="date" id="data_publicacao" name="data_publicacao">

    <button type="submit">Cadastrar Notícia</button>
</form>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Carregar categorias no select
        fetch('<?php echo esc_url(rest_url('custom/v1/categorias')); ?>')
            .then(response => response.json())
            .then(data => {
                const categoriaSelect = document.getElementById('categoria');
                if (data.success) {
                    data.categorias.forEach(categoria => {
                        const option = document.createElement('option');
                        option.value = categoria.id;
                        option.textContent = categoria.nome;
                        categoriaSelect.appendChild(option);
                    });
                }
            });

        // Enviar os dados do formulário de categoria para a API
        document.getElementById('form-cadastrar-categoria').addEventListener('submit', function(event) {
            event.preventDefault(); // Impede o comportamento padrão de envio do formulário

            const nome_categoria = document.getElementById('nome_categoria').value;

            // Verificar se o nome da categoria foi informado
            if (!nome_categoria) {
                alert('Nome da categoria é obrigatório.');
                return;
            }

            fetch('<?php echo esc_url(rest_url('custom/v1/categorias')); ?>', {
                method: 'POST',
                body: JSON.stringify({ nome: nome_categoria }),
                headers: {
                    'Content-Type': 'application/json',
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Categoria cadastrada com sucesso!');
                    document.getElementById('form-cadastrar-categoria').reset();
                } else {
                    alert('Erro ao cadastrar a categoria.');
                }
            })
            .catch(error => console.error('Erro:', error));
        });

        // Enviar os dados do formulário de notícia para a API
        document.getElementById('form-cadastrar-noticia').addEventListener('submit', function(event) {
            event.preventDefault(); // Impede o comportamento padrão de envio do formulário

            const titulo = document.getElementById('titulo').value;
            const conteudo = document.getElementById('conteudo').value;
            const categoria = document.getElementById('categoria').value;
            const data_publicacao = document.getElementById('data_publicacao').value;
            const imagem = document.getElementById('imagem').files[0];

            if (!titulo || !conteudo || !categoria) {
                alert('Título, conteúdo e categoria são obrigatórios.');
                return;
            }

            const formData = new FormData();
            formData.append('titulo', titulo);
            formData.append('conteudo', conteudo);
            formData.append('categoria', categoria);
            formData.append('data_publicacao', data_publicacao);
            if (imagem) {
                formData.append('imagem', imagem);
            }

            fetch('<?php echo esc_url(rest_url('custom/v1/noticias')); ?>', {
                method: 'POST',
                body: formData,
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Notícia cadastrada com sucesso!');
                    document.getElementById('form-cadastrar-noticia').reset();
                } else {
                    alert('Erro ao cadastrar a notícia.');
                }
            })
            .catch(error => console.error('Erro:', error));
        });
    });
</script>

<?php
get_footer();
?>
