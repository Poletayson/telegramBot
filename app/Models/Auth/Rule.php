<?php

namespace App\Models\Auth;

/**
 * Правило, разрешающее пользователю совершение определенного действия или доступ к определенным ресурсам
 * Пока попробуем обойтись без этого класса!
 */
class Rule
{
    /**
     * @var string Идентификатор правила.
     * Регистрозависимая строка.
     * Это может быть строковое представление UUID, но сравнение идентификаторов всё равно делается в строковом виде с учетом регистра
     */
    private string $id;

    /**
     * @var string Название правила
     */
    private string $name;

    /**
     * @var string Дополнительное описание правила, поясняющее его использование
     */
    private string $description;

    /**
     * @var string Название группы, к которой относится правило
     */
    private string $groupName;

    /**
     * @return string Идентификатор правила
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @param string $id Идентификатор правила
     */
    public function setId(string $id): void
    {
        $this->id = $id;
    }

    /**
     * @return string Название правила
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name Название правила
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return string Дополнительное описание правила, поясняющее его использование
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @param string $description Дополнительное описание правила, поясняющее его использование
     */
    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    /**
     * @return string Название группы, к которой относится правило
     */
    public function getGroupName(): string
    {
        return $this->groupName;
    }

    /**
     * @param string $groupName Название группы, к которой относится правило
     */
    public function setGroupName(string $groupName): void
    {
        $this->groupName = $groupName;
    }
}
