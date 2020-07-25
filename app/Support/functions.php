<?php

function readStorage($path)
{
    try {
        if (!is_readable($path)) {
            throw new InvalidArgumentException('Файла не существует');
        }

        if (!$resource = @fopen($path, 'rb')) {
            throw new InvalidArgumentException('Невозможно прочитать файл');
        }

        while (false !== $line = fgets($resource)) {
            yield $line;
        }
    } catch (Exception $e) {
        return;
    } finally {
        if (!empty($resource) && is_resource($resource)) {
            fclose($resource);
        }
    }
}