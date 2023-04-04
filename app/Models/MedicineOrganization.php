<?php

namespace App\Models;

/**
 * Медиинская организация
 */
class MedicineOrganization
{
    private $id = null;
    private string|null $name = null;
    private string|null $inn = null;

    public function __construct($id = null, $name = null, $inn = null)
    {
        $this->id = $id;
        $this->name = $name;
        $this->inn = $inn;
    }

    /**
     * Получить ID МО (clinicId)
     * @return null
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Задать ID МО (clinicId)
     * @param null|int $id
     */
    public function setId($id): void
    {
        $this->id = (int)$id;
    }

    /**
     * Получить название МО (clinicName)
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * Задать название МО (clinicName)
     * @param string|null $name
     */
    public function setName(?string $name): void
    {
        $this->name = $name;
    }

    /**
     * Получить ИНН МО
     * @return string|null
     */
    public function getInn(): ?string
    {
        return $this->inn;
    }

    /**
     * Задать ИНН МО
     * @param string|null $inn
     */
    public function setInn(?string $inn): void
    {
        $this->inn = $inn;
    }
}
