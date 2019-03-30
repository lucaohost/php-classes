<?php

require_once ('vendor/autoload.php');

use Cajudev\Classes\Arrays;

class Pessoa
{
    private $nome;
    protected $sobrenome;
    public $idade;

    public function __construct(string $nome, string $sobrenome, int $idade)
    {
        $this->nome      = $nome;
        $this->sobrenome = $sobrenome;
        $this->idade     = $idade;
    }

    public function toArray(): Arrays
    {
        return new Arrays($this);
    }
}


$pessoa = new Pessoa('Richard', 'Lopes', 24);
$arrayPessoa = $pessoa->toArray();
print_r( $arrayPessoa );

foreach ($arrayPessoa as $key => $value) {
    echo "Atributo: $key | Valor: $value" . PHP_EOL;
}