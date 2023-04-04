<?php

namespace App\Models;

class Human
{
    /**
     * @var string|null Имя
     */
    private string|null $name = null;


    /**
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @param string|null $name
     * @return Human
     */
    public function setName(?string $name)
    {
        $this->name = $name;
        return $this;
    }
}
