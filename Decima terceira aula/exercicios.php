<?php
declare(strict_types=1);

error_reporting(E_ALL);
ini_set('display_errors', '1');

class Pessoa
{
    private string $nome;
    private string $sobrenome;
    private ?int $idade;
    private string $email;
    private string $cidade;
    private string $estado;
    private string $parentesco;
    private string $created_at;

    public function __construct(
        string $nome,
        string $sobrenome,
        ?int $idade = null,
        string $email = '',
        string $cidade = '',
        string $estado = '',
        string $parentesco = 'outro'
    ) {
        $this->nome = $this->sanitize($nome);
        $this->sobrenome = $this->sanitize($sobrenome);
        $this->idade = $idade;
        $this->email = filter_var($email, FILTER_VALIDATE_EMAIL) ? $email : '';
        $this->cidade = $this->sanitize($cidade);
        $this->estado = $this->sanitize($estado);
        $this->parentesco = $this->sanitize($parentesco);
        $this->created_at = date('c');
    }

    private function sanitize(string $v): string
    {
        return trim(filter_var($v, FILTER_SANITIZE_FULL_SPECIAL_CHARS));
    }

    public function getNome(): string { return $this->nome; }
    public function getSobrenome(): string { return $this->sobrenome; }
    public function getIdade(): ?int { return $this->idade; }
    public function getEmail(): string { return $this->email; }
    public function getCidade(): string { return $this->cidade; }
    public function getEstado(): string { return $this->estado; }
    public function getParentesco(): string { return $this->parentesco; }
    public function getCreatedAt(): string { return $this->created_at; }

    public function setNome(string $v): void { $this->nome = $this->sanitize($v); }
    public function setSobrenome(string $v): void { $this->sobrenome = $this->sanitize($v); }
    public function setIdade(?int $v): void { $this->idade = $v; }
    public function setEmail(string $v): void { $this->email = filter_var($v, FILTER_VALIDATE_EMAIL) ? $v : ''; }
    public function setCidade(string $v): void { $this->cidade = $this->sanitize($v); }
    public function setEstado(string $v): void { $this->estado = $this->sanitize($v); }
    public function setParentesco(string $v): void { $this->parentesco = $this->sanitize($v); }

    public function toArray(): array
    {
        return [
            'nome' => $this->nome,
            'sobrenome' => $this->sobrenome,
            'idade' => $this->idade,
            'email' => $this->email,
            'cidade' => $this->cidade,
            'estado' => $this->estado,
            'parentesco' => $this->parentesco,
            'created_at' => $this->created_at,
        ];
    }

    public function toJson(): string
    {
        return json_encode($this->toArray(), JSON_UNESCAPED_UNICODE);
    }

    public function toTxtLine(): string
    {
        $parts = [
            $this->parentesco,
            $this->nome . ' ' . $this->sobrenome,
            $this->idade !== null ? (string)$this->idade : '',
            $this->email,
            $this->cidade,
            $this->estado,
            $this->created_at
        ];
        return implode(" | ", $parts);
    }
}

$familiaData = [
    ['Lucas','Silva',25,'lucas.silva@example.com','Blumenau','Santa Catarina','eu'],
    ['Carlos','Silva',55,'carlos.silva@example.com','Blumenau','Santa Catarina','pai'],
    ['Marcia','Silva',53,'marcia.silva@example.com','Blumenau','Santa Catarina','mae'],
    ['Rafael','Silva',28,'rafael.silva@example.com','Joinville','Santa Catarina','irmao'],
    ['Ana','Silva',22,'ana.silva@example.com','Florianopolis','Santa Catarina','irma'],
];


$familia = [];
foreach ($familiaData as $d) {
    [$nome, $sobrenome, $idade, $email, $cidade, $estado, $parentesco] = $d;
    $familia[] = new Pessoa(
        (string)$nome,
        (string)$sobrenome,
        is_numeric($idade) ? (int)$idade : null,
        (string)$email,
        (string)$cidade,
        (string)$estado,
        (string)$parentesco
    );
}


$dir = __DIR__;
$txtFile = $dir . DIRECTORY_SEPARATOR . 'familia.txt';
$jsonFile = $dir . DIRECTORY_SEPARATOR . 'familia.json';

function saveTxt(string $path, array $pessoas): bool
{
    $lines = [];
    foreach ($pessoas as $p) {
        if ($p instanceof Pessoa) {
            $lines[] = $p->toTxtLine();
        }
    }
    $content = implode(PHP_EOL, $lines) . PHP_EOL;
    $tmp = $path . '.tmp';
    if (file_put_contents($tmp, $content, LOCK_EX) === false) return false;
    return rename($tmp, $path);
}

function saveJson(string $path, array $pessoas): bool
{
    $arr = [];
    foreach ($pessoas as $p) {
        if ($p instanceof Pessoa) $arr[] = $p->toArray();
    }
    $json = json_encode($arr, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    if ($json === false) return false;
    $tmp = $path . '.tmp';
    if (file_put_contents($tmp, $json, LOCK_EX) === false) return false;
    return rename($tmp, $path);
}

$okTxt = saveTxt($txtFile, $familia);
$okJson = saveJson($jsonFile, $familia);

echo "<h2>Persistência de instâncias - resultado</h2>";
echo "<p>Registros no array: " . count($familia) . "</p>";
echo "<ul>";
echo "<li>familia.txt: " . ($okTxt ? 'gravado' : 'erro') . "</li>";
echo "<li>familia.json: " . ($okJson ? 'gravado' : 'erro') . "</li>";
echo "</ul>";

echo "<h3>Preview JSON</h3>";
echo "<pre>" . htmlspecialchars(json_encode(array_map(fn($p)=>$p->toArray(), $familia), JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE)) . "</pre>";

echo "<p>Edite os dados no arquivo PHP e reexecute para salvar outras pessoas.</p>";
?>