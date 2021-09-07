<?php

namespace Kernel;

use DateTime;
use Kernel\Validator\ValidationErrors;
use PDO;


class Validator
{
    private array $errros = [];
    private array $params;
    private const MIME_TYPES = [
        'jpg' => 'image/jpeg',
        'jpeg' => 'image/jpg',
        'png' => 'image/png',
        'pdf' => 'application/pdf'
    ];


    public function __construct(array $params)
    {
        $this->params = $params;
    }

    public function required(string ...$keys): self
    {
        foreach ($keys as $key) {
            $value = $this->getValue($key);
            if (is_null($value)) {
                $this->addError($key, 'required');
            }
        }
        return $this;
    }

    public function getErrors(): array
    {
        return $this->errros;
    }

    public function addError(string $key, string $rule, array $attributes = []): void
    {
        $this->errros[$key] = new ValidationErrors($key, $rule, $attributes);
    }

    public function uploaded(string $key): self
    {
        $file = $this->getValue($key);
        if ($file === null || $file->getError() !== UPLOAD_ERR_OK) {
            $this->addError($key, 'uploaded');
        }
        return $this;
    }


    /**
     * Vérifie le format de fichier
     * @param string $key
     * @param array $extensions
     * @return Validator
     */
    public function extension(string $key, array $extensions): self
    {
        $file = $this->getValue($key);
        if ($file !== null && $file->getError() === UPLOAD_ERR_OK) {
            $type = $file->getClientMediaType();
            $extension = mb_strtolower(pathinfo($file->getClientFilename(), PATHINFO_EXTENSION));
            $expectedType = self::MIME_TYPES[$extension] ?? null;
            if (!in_array($extension, $extensions) || $expectedType !== $type) {
                $this->addError($key, 'filetype', [join(',', $extensions)]);
            }
        }

        return $this;
    }

    public function slug(string $key): self
    {
        $value = $this->getValue($key);
        $pattern = "/^([a-z0-9]+-?)+$/";
        if (!is_null($value) && !preg_match($pattern, $value)) {
            $this->addError($key, 'slug');
        }
        return $this;
    }

    private function getValue(string $key): mixed
    {
        if (array_key_exists($key, $this->params))
            return $this->params[$key];
        return null;
    }

    public function notEmpty(string ...$keys): self
    {
        foreach ($keys as $key) {
            $value = $this->getValue($key);
            if (is_null($value) || empty($value)) {
                $this->addError($key, 'empty');
            }
        }
        return $this;
    }

    public function length(string $key, ?int $min, ?int $max = null): self
    {
        $value = $this->getValue($key);
        $length = mb_strlen($value);
        if ((!is_null($min) && !is_null($max))
            && ($length < $min || $length > $max)
        ) {
            $this->addError($key, 'betweenLength', [$min, $max]);
            return $this;
        } elseif (!is_null($min) && $length < $min) {
            $this->addError($key, 'minLength', [$min]);
            return $this;
        } elseif (!is_null($max) && $length > $max) {
            $this->addError($key, 'maxLength', [$max]);
        }
        return $this;
    }

    public function dateTime(string $key, string $format = "Y-m-d H:i:s"): self
    {
        $value = $this->getValue($key);
        $date = DateTime::createFromFormat($format, $value);
        $errors = DateTime::getLastErrors();
        if ($errors['error_count'] > 0 || $errors['warning_count'] > 0 || $date === false) {
            $this->addError($key, 'datetime', [$format]);
        }
        return $this;
    }

    public function isValid(): bool
    {
        return empty($this->getErrors());
    }

    /**
     * Vérifie que la clef existe dans la table donnée
     *
     * @param string $key
     * @param string $table
     * @param PDO $pdo
     * @return Validator
     */
    public function exists(string $key, string $table, PDO $pdo): self
    {
        $value = $this->getValue($key);
        $statement = $pdo->prepare("SELECT id FROM $table WHERE id = ?");
        $statement->execute([$value]);
        if ($statement->fetchColumn() === false) {
            $this->addError($key, 'exists', [$table]);
        }
        return $this;
    }

    public function email(string $email): self
    {
        $value = $this->getValue($email);
        if (filter_var($value, FILTER_VALIDATE_EMAIL) === false) {
            $this->addError($email, 'email');
        }
        return $this;
    }

    public function unique(string $key, mixed $table, ?PDO $pdo = null, ?int $exclude = null): self
    {
        if ($table instanceof  Table){
            $pdo = $table->getPdo();
            $table = $table->getTable();
        }
        $value = $this->getValue($key);
        $query = "SELECT id FROM $table WHERE $key = ?";
        $params = [$value];
        if ($exclude !== null){
            $query .= " AND id != ? ";
            $params = [$exclude];
        }
        $statement = $pdo->prepare($query);
        $statement->execute($params);
        if ($statement->fetchColumn() !== false ){
            $this->addError($key, 'unique', [$value]);
    }
        return $this;
    }

    public function confirm(string $key): self
    {
        $value = $this->getValue($key);
        $valueConfirm = $this->getValue($key . '_confirm');
        if ($value !== $valueConfirm) {
            $this->addError($key, 'confirm');
        }
        return $this;
    }
}