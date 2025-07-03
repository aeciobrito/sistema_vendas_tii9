<?php

/*
CREATE TABLE IF NOT EXISTS categoria (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    descricao TEXT,
    data_criacao DATETIME DEFAULT CURRENT_TIMESTAMP,
    data_atualizacao DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    usuario_atualizacao INT,
    ativo TINYINT(1) DEFAULT 1
);
*/
class Categoria
{
    private ?int $id;
    private ?string $dataCriacao;
    private ?string $dataAtualizacao;
    private ?int $usuarioAtualizacao;
    private bool $ativo;
    private string $nome;
    private ?string $descricao;

    public function __construct(?int $id, string $nome, ?string $descricao, bool $ativo = true,
        ?string $dataCriacao = null, ?string $dataAtualizacao = null, ?int $usuarioAtualizacao = null)
    {
        $this->id = $id;
        $this->dataCriacao = $dataCriacao;
        $this->dataAtualizacao = $dataAtualizacao;
        $this->usuarioAtualizacao = $usuarioAtualizacao;
        $this->ativo = $ativo;
        $this->nome = $nome;
        $this->descricao = $descricao;
    }

    public function getId(): ?int { return $this->id; }
    public function isAtivo(): bool { return $this->ativo; }
    public function getDataCriacao(): ?string { return $this->dataCriacao; }
    public function getDataAtualizacao(): ?string { return $this->dataAtualizacao; }
    public function getUsuarioAtualizacao(): ?int { return $this->usuarioAtualizacao; }
    public function getNome(): string { return $this->nome; }
    public function getDescricao(): ?string { return $this->descricao; }
}