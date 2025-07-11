<?php

require_once __DIR__ . '/../dao/CategoriaDAO.php';
require_once __DIR__ . '/../model/Pedido.php';
require_once __DIR__ . '/../model/ItemPedido.php';
require_once __DIR__ . '/../model/Usuario.php';
require_once __DIR__ . '/../database/Database.php';

class PedidoDAO
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    private function mapObject(array $row): Pedido
    {
        $usuarioDAO = new UsuarioDAO();
        $formaPgamentoDAO = new FormaPagamentoDAO();
        $itemPedido = new ItemPedidoDAO();

        $cliente = $usuarioDAO->getById($row['cliente_id']);
        $formaPagamento = $formaPgamentoDAO->getById($row['forma_pagamento_id']);

        $usuarioAtualizacao = null;
        if(!empty($row['usuario_atualizacao'])) {
            $usuarioAtualizacao = $usuarioDAO->getById($row['usuario_atualizacao']);
        }

        $pedido = new Pedido(
            $row['id'],
            $cliente,
            $row['data_pedido'],
            $formaPagamento,
            $row['status'],
            (bool)$row['ativo'],
            [],
            $row['data_criacao'],
            $row['data_atualizacao'],
            $usuarioAtualizacao
        );

        $itens = $itemPedido->getByPedidoId($pedido->getId());
        $pedido->setItens($itens);

        return $pedido;
    }
    
    public function create(Pedido $pedido): int|false
    {
        $this->db->beginTransaction();

        try {
            $sql = "INSERT INTO pedido (cliente_id, data_pedido, forma_pagamento_id, status, usuario_atualizacao) 
                            VALUES (:cliente_id, :data_pedido, :forma_pagamento_id, :status, :user_id)";
            
            $stmtPedido = $this->db->prepare($sql);

            $stmtPedido->execute([
                ':cliente_id' => $pedido->getCliente()->getId(),
                ':data_pedido' => $pedido->getDataPedido(),
                ':forma_pagamento_id' => $pedido->getFormaPagamento()->getId(),
                ':status' => $pedido->getStatus(),
                ':user_id' => $pedido->getCliente()->getId() // O próprio cliente cria o pedido
            ]);

            $pedidoId = $this->db->lastInsertId();
            $itemPedidoDao = new ItemPedidoDAO();

            foreach($pedido->getItens() as $item) {
                if(!$itemPedidoDao->create($item, $pedidoId)) {
                    $this->db->rollBack();
                    return false;
                }
            }

            $this->db->commit();
            return (int)$pedidoId;
        } catch (Exception $e) {
            $this->db->rollBack();
            return false;
        }
    }

    public function getById(int $id): ?Pedido
    {
        $stmt = $this->db->prepare("SELECT * FROM pedido WHERE id = :id");
        $stmt->execute([':id' => $id]);
        $data = $stmt->fetch();
        return $data ? $this->mapObject($data) : null;
    }

    public function getAll(bool $somenteAtivos = true): array
    {
        $sql = "SELECT * FROM pedido" . ($somenteAtivos ? " WHERE ativo = 1" : "") . " ORDER BY data_pedido DESC";
        $stmt = $this->db->query($sql);
        $result = [];
        foreach ($stmt->fetchAll() as $row) {
            $result[] = $this->mapObject($row);
        }
        return $result;
    }

    // Atualizar o status é uma operação comum e mais simples.
    public function updateStatus(int $id, string $status, int $usuarioId): bool
    {
        $sql = "UPDATE pedido SET status = :status, usuario_atualizacao = :user_id WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':id' => $id,
            ':status' => $status,
            ':user_id' => $usuarioId
        ]);
    }
    
    public function softDelete(int $id, int $usuarioId): bool
    {
        $sql = "UPDATE pedido SET ativo = 0, usuario_atualizacao = :user_id WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([':id' => $id, ':user_id' => $usuarioId]);
    }
    
    // Hard delete em pedidos geralmente não é recomendado por causa do histórico.
    // A deleção em cascata no banco já removeria os itens do pedido.
    public function hardDelete(int $id): bool
    {
        $stmt = $this->db->prepare("DELETE FROM pedido WHERE id = :id");
        return $stmt->execute([':id' => $id]);
    }
}