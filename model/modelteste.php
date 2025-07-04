<?php

require_once './Entidade.php';
require_once './Categoria.php';
require_once './FormaPagamento.php';
require_once './Usuario.php';

header('Content-Type: text/plain;');

echo "======================================\n";
echo "=== INICIANDO OS TESTES DE MODELS ====\n";
echo "======================================\n\n";

// 1 - CATEGORIA E FORMA DE PAGAMENTO
echo "1. Criando Categoria e Forma de Pagamento \n\n";
$categoriaEletro = new Categoria(1, 'Eletronicos', 'Dispositvios que encaixam na tomada', true);
$formaPagametno = new FormaPagamento(1, 'Cartão de Crédito', 'Pagamento via cartão', true);

print_r($categoriaEletro);
print_r($formaPagametno);
echo "\n";

// 2 - CRIANDO USUÁRIO (CLIENTE)
echo "2. Criando Usuário (Cliente) \n\n";
$cliente = new Usuario(10, 'João da Silva', 'joao.silva', 'super_senha', 'joao@mail.com',
    '11999998888', '123.456.789-00', false, true);

print_r($cliente);
echo "\n";

// 3 - PRODUTOS QUE PERTENCEM A UMA CATEGORIA
