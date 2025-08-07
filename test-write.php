<?php
// Crie um arquivo test-write.php
<?php
$test_file = __DIR__ . '/test_write.txt';
if (file_put_contents($test_file, 'Teste de escrita: ' . date('Y-m-d H:i:s'))) {
    echo "Sucesso! Arquivo criado em: " . $test_file;
} else {
    echo "Erro! Não foi possível escrever no arquivo.";
    echo "Permissões do diretório: " . substr(sprintf('%o', fileperms(__DIR__)), -4);
}
?>