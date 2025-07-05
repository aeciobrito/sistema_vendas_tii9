<?php

require_once __DIR__ . '/../model/Usuario.php';
require_once __DIR__ . '/../database/Database.php';

class UsuarioDAO
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    private function mapObject(array $row) : Usuario
    {
        $usuarioAtualizacao = null;

        if(!empty($row['usuario_atualizacao']))
        {
            if($row['id'] != $row['usuario_atualizacao']) 
            {
                $usuarioAtualizacao = $this->getById($row['usuario_atualizacao']);
            }
        }

        return new Usuario(
            $row['id'],
            $row['nome_completo'],
            $row['nome_usuario'],
            $row['senha'],
            $row['email'],
            $row['telefone'],
            $row['cpf'],
            $row['is_admin'],
            $row['ativo'],
            $row['token'],
            $row['data_criacao'],
            $row['data_atualizacao'],
            $usuarioAtualizacao
        );
    }

    public function create(Usuario $usuario, int $adminId): bool
    {
        $sql = "INSERT INTO usuario (nome_completo, nome_usuario, senha, email, telefone, cpf, is_admin, token, usuario_atualizacao) 
                VALUES (:nome_completo, :nome_usuario, :senha, :email, :telefone, :cpf, :is_admin, :token, :user_id)";
        
        $stmt = $this->db->prepare($sql);
        
        return $stmt->execute([
            ':nome_completo' => $usuario->getNomeCompleto(),
            ':nome_usuario' => $usuario->getNomeUsuario(),
            ':senha' => $usuario->getSenha(),
            ':email' => $usuario->getEmail(),
            ':telefone' => $usuario->getTelefone(),
            ':cpf' => $usuario->getCpf(),
            ':is_admin' => (int)$usuario->isAdmin(),
            ':token' => $usuario->getToken(),
            ':user_id' => $adminId
        ]);
    }

    public function getById(int $id): ?Usuario
    {
        $stmt = $this->db->prepare("SELECT * FROM usuario WHERE id = :id");
        $stmt->execute([':id' => $id]);
        $data = $stmt->fetch();

        return $data ? $this->mapObject($data) : null;
    }

    public function getAll(bool $somenteAtivos = true): array
    {        
        $sql = "SELECT * FROM usuario" . ($somenteAtivos? " WHERE ativo = 1" : "") .  "ORDER BY nome_completo";
        $stmt = $this->db->prepare($sql);
        $result = [];

        foreach($stmt->fetchAll() as $data) {
            $result[] = $this->mapObject($data);
        }

        return $result;
    }

    public function update(Usuario $usuario, int $adminId): bool
    {
        $sql = "UPDATE usuario SET 
                    nome_completo = :nome_completo, 
                    nome_usuario = :nome_usuario, 
                    email = :email, 
                    telefone = :telefone, 
                    cpf = :cpf, 
                    is_admin = :is_admin,
                    ativo = :ativo,
                    usuario_atualizacao = :user_id 
                WHERE id = :id";
        
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':id' => $usuario->getId(),
            ':nome_completo' => $usuario->getNomeCompleto(),
            ':nome_usuario' => $usuario->getNomeUsuario(),
            ':email' => $usuario->getEmail(),
            ':telefone' => $usuario->getTelefone(),
            ':cpf' => $usuario->getCpf(),
            ':is_admin' => (int)$usuario->isAdmin(),
            ':ativo' => (int)$usuario->isAtivo(),
            ':user_id' => $adminId
        ]);
    }

    public function softDelete(int $id, int $adminId): bool
    {
        $sql = "UPDATE usuario SET ativo = 0, usuario_atualizacao = :user_id WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([':id' => $id,':user_id' => $adminId]);
    }

    public function hardDelete(int $id): bool
    {
        $stmt = $this->db->prepare("DELETE FROM usuario WHERE id = :id");
        return $stmt->execute([':id' => $id]);
    }
}