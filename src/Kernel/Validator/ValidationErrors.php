<?php
namespace Kernel\Validator;

class ValidationErrors
{
    private string $key;
    private string $rule;
    private array $attributes;
    private array $message = [
        'required' => 'Le champs %s est requis',
        'empty' =>'Le champs %s ne peut etre vide',
        'slug' => 'Le champs %s n\'est pas un slug valide',
        'minLength' => 'Le champs %s doit contenir plus de %d caracteres',
        'maxLength' => 'Le champs %s doit contenir moins de %d caracteres',
        'betweenLength' => 'Le champs %s doit contenir entre %d et %d caracteres',
        'datetime' => 'Le champs %s doit etre une date valide',
        'exists' => 'Le champs %s n\'existe pas dans la table %s',
        'filetype' => 'Le champs %s est invalide seuls (%s)',
        'uploaded' => 'Vous devez uploader un fichier pas seulement un fichier mais valide fais pas le malin',
        'email' => 'L\'email n\'est pas valide',
        'unique' => 'Le champs %s doit être unique',
        'confirm' => "Les deux mots de passe saisie sont différents"
    ];

    public function __construct(string $key,
                                string $rule,
                                array $attributes = [])
    {
        $this->key = $key;
        $this->rule = $rule;
        $this->attributes = $attributes;
    }
    public function __toString(): string
    {
        $params = array_merge([$this->message[$this->rule], $this->key], $this->attributes);
        return (string)call_user_func_array('sprintf', $params);
    }
}