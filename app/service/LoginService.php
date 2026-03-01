<?php

class LoginService
{
    public static function validarDados($email, $senha)
    {
        if (empty($email) || empty($senha)) {
            MessageService::setError("Preencha todos os campos.");
            return false;
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            MessageService::setError("E-mail inválido.");
            return false;
        }

        return true;
    }

    public static function validarUsuario($email, $senha)
    {
        $usuarioModel = new Usuario();
        $usuario = $usuarioModel->findByEmail($email);

        if (!$usuario) {
            MessageService::setError("Usuário não encontrado.");
            return false;
        }

        if (!password_verify($senha, $usuario['senha'])) {
            MessageService::setError("Senha incorreta.");
            return false;
        }

        return $usuario;
    }

    public static function validarSenha($senha, $confirm)
    {
        if ($senha !== $confirm) {
            MessageService::setError("As senhas devem ser iguais.");
            return false;
        }

        return true;
    }
}
