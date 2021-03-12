<?php


namespace models;


use core\DbModel;

class User extends DbModel
{
    public $firstName = '';
    public $lastName = '';
    public $email = '';
    public $password = '';
    public $confirmPassword = '';

    public function tableName(): string
    {
        return 'users';
    }

    public function attributes(): array
    {
        return ['firstName', 'lastName', 'email', 'password'];
    }

    public function labels(): array
    {
        return [
            'firstName' => 'Voornaam',
            'lastName' => 'Achternaam',
            'email' => 'Email',
            'password' => 'Wachtwoord',
            'confirmPassword' => 'Bevestig wachtwoord'
        ];
    }

    public function rules()
    {
        return [
            'firstName' => [self::RULE_REQUIRED],
            'lastName' => [self::RULE_REQUIRED],
            'email' => [self::RULE_REQUIRED, self::RULE_EMAIL],
            'password' => [self::RULE_REQUIRED, [self::RULE_MIN, 'min' => 8]],
            'confirmPassword' => [[self::RULE_MATCH, 'match' => 'password']],
        ];
    }

//    public function save()
//    {
//        $this->password = password_hash($this->password, PASSWORD_DEFAULT);
//
//        return parent::save();
//    }

    public function getDisplayName(): string
    {
        return $this->firstName . ' ' . $this->lastName;
    }
}