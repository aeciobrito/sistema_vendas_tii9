<?php

header('Content-Type: text/plain; charset=utf-8');

require_once __DIR__ . '/Entidade.php';
require_once __DIR__ . '/Usuario.php'; 
require_once __DIR__ . '/Categoria.php';
require_once __DIR__ . '/FormaPagamento.php';
require_once __DIR__ . '/Produto.php';
require_once __DIR__ . '/ItemPedido.php';
require_once __DIR__ . '/Pedido.php';

echo "===============================================\n";
echo "INICIANDO TESTE DE INSTANCIAÇÃO DE MODELS\n";
echo "===============================================\n\n";

// Etapa preliminar: Criar um usuário admin para os registros de auditoria
echo "0. Criando Usuário Administrador para auditoria...\n";
$dataAtual = date('Y-m-d H:i:s');
$adminUser = new Usuario(
    1, 'Admin Sistema', 'admin', 'senha_admin', 'admin@sistema.com', 
    null, null, true, true, null, $dataAtual, $dataAtual, null
);
print_r($adminUser);
echo "\n";


// 1. Criando entidades simples
echo "1. Criando Categoria e Forma de Pagamento...\n";
$categoriaEletronicos = new Categoria(
    1, 'Eletrônicos', 'Dispositivos eletrônicos e acessórios', true, 
    $dataAtual, $dataAtual, $adminUser
);
$formaPagamentoCartao = new FormaPagamento(
    1, 'Cartão de Crédito', 'Pagamento via cartão de crédito', true, 
    $dataAtual, $dataAtual, $adminUser
);

print_r($categoriaEletronicos);
print_r($formaPagamentoCartao);
echo "\n";

// 2. Criando um usuário (que será nosso cliente)
echo "2. Criando um Usuário (Cliente)...\n";
$cliente = new Usuario(
    10, 'João da Silva Souza', 'joao.silva', 'senha_super_segura', 'joao@exemplo.com',
    '11999998888', '123.456.789-00', false, true, null,
    $dataAtual, $dataAtual, $adminUser
);

print_r($cliente);
echo "\n";

// 3. Criando produtos que pertencem a uma categoria
echo "3. Criando Produtos...\n";
$produtoNotebook = new Produto(
    101, 'Notebook Gamer', 'Notebook de alta performance para jogos', 7500.50, $categoriaEletronicos, true,
    $dataAtual, $dataAtual, $adminUser
);
$produtoMouse = new Produto(
    102, 'Mouse Sem Fio', 'Mouse óptico sem fio com 6 botões', 150.75, $categoriaEletronicos, true,
    $dataAtual, $dataAtual, $adminUser
);

print_r($produtoNotebook);
print_r($produtoMouse);
echo "\n";
