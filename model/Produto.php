<?php

class Produto
{
    private ?int $id;
    private ?string $dataCriacao;
    private ?string $dataAtualizacao;
    private ?int $usuarioAtualizacao;
    private bool $ativo;
    private string $nome;
    private ?string $descricao;
    private float $preco;
    private ?Categoria $categoria;

    public function __construct(
        ?int $id, string $nome, ?string $descricao, float $preco, ?Categoria $categoria, bool $ativo = true, 
        ?string $dataCriacao = null, ?string $dataAtualizacao = null, ?int $usuarioAtualizacao = null
    ) {
        $this->id = $id;
        $this->dataCriacao = $dataCriacao;
        $this->dataAtualizacao = $dataAtualizacao;
        $this->usuarioAtualizacao = $usuarioAtualizacao;
        $this->ativo = $ativo;
        $this->nome = $nome;
        $this->descricao = $descricao;
        $this->preco = $preco;
        $this->categoria = $categoria;
    }

    public function getId(): ?int { return $this->id; }
    public function isAtivo(): bool { return $this->ativo; }
    public function getDataCriacao(): ?string { return $this->dataCriacao; }
    public function getDataAtualizacao(): ?string { return $this->dataAtualizacao; }
    public function getUsuarioAtualizacao(): ?int { return $this->usuarioAtualizacao; }
    public function getNome(): string { return $this->nome; }
    public function getDescricao(): ?string { return $this->descricao; }
    public function getPreco(): float { return $this->preco; }
    public function getCategoria(): ?Categoria { return $this->categoria; }
}