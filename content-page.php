<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Listagem de Notícias</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        th, td {
            border: 1px solid #ccc;
            padding: 10px;
            text-align: left;
        }

        img {
            max-width: 100px;
            height: auto;
        }

        .acoes button {
            margin-right: 5px;
            padding: 5px 10px;
            cursor: pointer;
        }

        /* Estilo para o formulário de edição */
        .form-editar {
            display: none;
            margin-top: 20px;
            padding: 20px;
            border: 1px solid #ccc;
            background-color: #f9f9f9;
        }

        .form-editar input,
        .form-editar textarea {
            width: 100%;
            margin-bottom: 10px;
            padding: 10px;
            font-size: 16px;
        }

        .form-editar button {
            padding: 10px 20px;
            cursor: pointer;
        }
    </style>
</head>
<body>
    <h1>Listagem de Notícias</h1>

    <table id="tabela-noticias">
        <thead>
            <tr>
                <th>Título</th>
                <th>Categoria</th>
                <th>Data de Publicação</th>
                <th>Imagem</th>
                <th>Conteúdo</th> <!-- Nova coluna para conteúdo -->
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            <!-- Notícias serão carregadas dinamicamente aqui -->
        </tbody>
    </table>

    <!-- Formulário de edição (inicialmente escondido) -->
    <div class="form-editar" id="form-editar">
        <h2>Editar Notícia</h2>
        <form id="form-edicao" action="" method="POST">
            <input type="hidden" name="id" id="noticia-id">
            <label for="titulo">Título</label>
            <input type="text" name="titulo" id="titulo" required>

            <label for="conteudo">Conteúdo</label>
            <textarea name="conteudo" id="conteudo" required></textarea>

            <button type="submit">Salvar Alterações</button>
            <button type="button" id="cancelar-edicao">Cancelar</button>
        </form>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Carregar notícias
            fetch('<?php echo esc_url(rest_url('custom/v1/noticias')); ?>')
                .then(response => response.json())
                .then(data => {
                    const noticiasTable = document.getElementById('tabela-noticias').getElementsByTagName('tbody')[0];
                    if (data.success) {
                        data.noticias.forEach(noticia => {
                            const tr = document.createElement('tr');

                            // Título da notícia
                            const tdTitulo = document.createElement('td');
                            tdTitulo.textContent = noticia.titulo;

                            // Categoria da notícia
                            const tdCategoria = document.createElement('td');
                            tdCategoria.textContent = noticia.categoria;

                            // Data de publicação
                            const tdDataPublicacao = document.createElement('td');
							// Formata a data para exibir apenas o dia, mês e ano
                            tdDataPublicacao.textContent = new Date(noticia.data_publicacao).toLocaleDateString(); 


                            // Imagem destacada
                            const tdImagem = document.createElement('td');
                            if (noticia.imagem) {
                                const img = document.createElement('img');
                                img.src = noticia.imagem;
                                img.alt = `Imagem de ${noticia.titulo}`;
                                img.style.width = '100px'; // Ajuste o tamanho conforme necessário
                                tdImagem.appendChild(img);
                            } else {
                                tdImagem.textContent = 'Sem imagem';
                            }

                            // Conteúdo da notícia
                            const tdConteudo = document.createElement('td');
                            tdConteudo.textContent = noticia.conteudo; // Exibindo o conteúdo da notícia

                            // Ações (Editar e Excluir)
                            const tdAcoes = document.createElement('td');
                            tdAcoes.innerHTML = `
                                <button class="editar" data-id="${noticia.id}">Editar</button>
                                <button class="excluir" data-id="${noticia.id}">Excluir</button>
                            `;

                            // Adicionar células na linha
                            tr.appendChild(tdTitulo);
                            tr.appendChild(tdCategoria);
                            tr.appendChild(tdDataPublicacao);
                            tr.appendChild(tdImagem); // Adiciona a coluna da imagem
                            tr.appendChild(tdConteudo); // Adiciona a coluna do conteúdo
                            tr.appendChild(tdAcoes);

                            // Adicionar linha na tabela
                            noticiasTable.appendChild(tr);
                        });

                        // Adicionar eventos para editar e excluir
                        document.querySelectorAll('.editar').forEach(button => {
                            button.addEventListener('click', function () {
                                const noticiaId = this.getAttribute('data-id');
                                
                                // Carregar dados da notícia para editar
                                const noticia = data.noticias.find(noticia => noticia.id == noticiaId);
                                document.getElementById('noticia-id').value = noticia.id;
                                document.getElementById('titulo').value = noticia.titulo;
                                document.getElementById('conteudo').value = noticia.conteudo;

                                // Mostrar o formulário de edição
                                document.getElementById('form-editar').style.display = 'block';
                            });
                        });

                        document.querySelectorAll('.excluir').forEach(button => {
                            button.addEventListener('click', function () {
                                const noticiaId = this.getAttribute('data-id');
                                if (confirm('Tem certeza que deseja excluir esta notícia?')) {
                                    fetch(`<?php echo esc_url(rest_url('custom/v1/noticias')); ?>/${noticiaId}`, {
                                        method: 'DELETE',
                                        headers: {
                                            'Content-Type': 'application/json',
                                        }
                                    })
                                    .then(response => response.json())
                                    .then(data => {
                                        if (data.success) {
                                            alert('Notícia excluída com sucesso!');
                                            location.reload();
                                        } else {
                                            alert('Erro ao excluir notícia.');
                                        }
                                    })
                                    .catch(error => console.error('Erro ao excluir notícia:', error));
                                }
                            });
                        });

                        // Cancelar edição
                        document.getElementById('cancelar-edicao').addEventListener('click', function () {
                            document.getElementById('form-editar').style.display = 'none';
                        });

                        // Enviar o formulário de edição
                        document.getElementById('form-edicao').addEventListener('submit', function (event) {
                            event.preventDefault();

                            const formData = new FormData(this);
                            const noticiaId = formData.get('id');
                            const titulo = formData.get('titulo');
                            const conteudo = formData.get('conteudo');

                            fetch(`<?php echo esc_url(rest_url('custom/v1/noticias')); ?>/${noticiaId}`, {
                                method: 'PUT',
                                body: JSON.stringify({
                                    titulo: titulo,
                                    conteudo: conteudo,
                                }),
                                headers: {
                                    'Content-Type': 'application/json',
                                }
                            })
                            .then(response => response.json())
                            .then(data => {
                                if (data.success) {
                                    alert('Notícia atualizada com sucesso!');
                                    location.reload();
                                } else {
                                    alert('Erro ao atualizar notícia.');
                                }
                            })
                            .catch(error => console.error('Erro ao atualizar notícia:', error));
                        });
                    }
                })
                .catch(error => console.error('Erro ao carregar notícias:', error));
			
        });
    </script>
</body>
</html>
