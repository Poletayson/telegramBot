<?php

namespace App\Models\Auth;

/**
 * Ресурсы, доступные пользователю
 */
class UserResources
{
    /**
     * Список кодов МО в РМИС, с данными которых может работать пользователь
     * @var array
     */
    public array $clinics = [];

    /**
     * Список идентификаторов предопределенных правил, которые разрешены пользователю
     * @var array
     */
    public array $resources = [];

    /**
     * Список кодов штатных единиц, талоны которых может читать пользователь (для "приватных" штаток,
     * таких как нарколог, психиатр)
     * @var array
     */
    public array $workers = [];

    /**
     * Список кодов штатных единиц, талоны которых может изменять пользователь (для "приватных" штаток,
     * таких как нарколог, психиатр)
     * @var array
     */
    public array $workersEditor = [];

    /**
     * Список кодов МО из базы стационара с которыми может работат пользователь (для старой версии госпитализации)
     * @var array
     */
    public array $hospitals = [];

    /**
     * Список кодом учебных учреждений, с учащимися которых может работать пользователь (для прививок)
     * @var array
     */
    public array $educInst = [];

//    /**
//     * Получить список кодов МО в РМИС, с данными которых может работать пользователь
//     * @return array
//     */
//    public function getClinics(): array
//    {
//        return $this->clinics;
//    }
//
//    /**
//     * Задать список кодов МО в РМИС, с данными которых может работать пользователь
//     * @param array $clinics
//     * @return UserResources
//     */
//    public function setClinics(array $clinics): UserResources
//    {
//        $this->clinics = $clinics;
//        return $this;
//    }
//
//    /**
//     * @return array
//     */
//    public function getResources(): array
//    {
//        return $this->resources;
//    }
//
//    /**
//     * @param array $resources
//     * @return UserResources
//     */
//    public function setResources(array $resources): UserResources
//    {
//        $this->resources = $resources;
//        return $this;
//    }
//
//    /**
//     * @return array
//     */
//    public function getWorkers(): array
//    {
//        return $this->workers;
//    }
//
//    /**
//     * @param array $workers
//     * @return UserResources
//     */
//    public function setWorkers(array $workers): UserResources
//    {
//        $this->workers = $workers;
//        return $this;
//    }
//
//    /**
//     * @return array
//     */
//    public function getWorkersEditor(): array
//    {
//        return $this->workersEditor;
//    }
//
//    /**
//     * @param array $workersEditor
//     * @return UserResources
//     */
//    public function setWorkersEditor(array $workersEditor): UserResources
//    {
//        $this->workersEditor = $workersEditor;
//        return $this;
//    }
//
//    /**
//     * @return array
//     */
//    public function getHospitals(): array
//    {
//        return $this->hospitals;
//    }
//
//    /**
//     * @param array $hospitals
//     * @return UserResources
//     */
//    public function setHospitals(array $hospitals): UserResources
//    {
//        $this->hospitals = $hospitals;
//        return $this;
//    }
//
//    /**
//     * @return array
//     */
//    public function getEducInst(): array
//    {
//        return $this->educInst;
//    }
//
//    /**
//     * @param array $educInst
//     * @return UserResources
//     */
//    public function setEducInst(array $educInst): UserResources
//    {
//        $this->educInst = $educInst;
//        return $this;
//    }
}
