<?php
// src/DTO\RegistrationFormDTO.php

namespace App\DTO;

use Symfony\Component\Validator\Constraints as Assert;

class RegistrationFormDTO
{
    /**
     * @Assert\NotBlank
     */
    public string $username;

    /**
     * @Assert\NotBlank
     * @Assert\Email
     */
    public string $email;

    /**
     * @Assert\NotBlank
     * @Assert\Length(min=6)
     */
    public string $plainPassword;
}
