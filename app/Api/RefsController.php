<?php

namespace App\Api;

use App\Models\MedicineOrganization;
use Exception;

/**
 * Класс, реализующий методы взаимодейстия с API сервиса /refs
 */
class RefsController extends ApiRequest
{
    /**
     * Создать медиинскую организацию на основе входных данных
     * @param array $data Ассоиативный массив с входными данными
     * @return MedicineOrganization
     */
    private function makeMO (array $data): MedicineOrganization
    {
        $medicineOrganization = new MedicineOrganization();
        $medicineOrganization->setId($data['clinicId'] ?? null);
        $medicineOrganization->setName($data['clinicName'] ?? null);
        $medicineOrganization->setInn($data['inn'] ?? null);
        //TODO Возможно, добавить и другие поля

        return $medicineOrganization;
    }


    /**
     * Получить все либо конкретные клиники
     * @param array|string|null $clinics Массив Id медорганизаций, либо одиночный Id
     * @return array Массив медорганизаций
     * @throws Exception
     */
    public function getClinics (array|string $clinics = null): array
    {
        $medicineOrganizations = [];

        //В зависимости он параметра...
        if ($clinics == null) {
            //Все МО
            $dataAll = static::callApiMethod('refs', 'Clinics.All');
            foreach ($dataAll as $data) {
                $medicineOrganizations[] = $this->makeMO($data);
            }
        } elseif (is_array($clinics)) {
            if (count($clinics) == 1){
                    //Всего одна МО
                $medicineOrganizations[] = $this->getClinicById ($clinics[0]);
            } else {
                //Запросим все и выберем нужные
                $dataAll = static::callApiMethod('refs', 'Clinics.All');

                foreach ($dataAll as $data) {
                    //Если эта МО есть в списке запрошенных - добавляем
                    if (in_array($data['clinicId'], $clinics)) {
                        $medicineOrganizations[] = $this->makeMO($data);
                    }
                }
            }
        } else {
            $medicineOrganizations[] = $this->getClinicById ($clinics);
        }

        return $medicineOrganizations;
    }

    /**
     * Получить клинику по ID
     * @param $clinicId
     * @return ?MedicineOrganization
     * @throws Exception
     */
    public function getClinicById ($clinicId): ?MedicineOrganization
    {
        $mo = null;
        try {
            $clinicData = $this->callApiMethod('refs', 'Clinics.ById', ['clinicId' => (int)$clinicId]);
            $mo = $this->makeMO($clinicData);
        } catch (Exception $exception) {
//            throw new Exception(json_encode(['method' => 'getClinicById', 'message' => Организация не найдена]));
        }
        return $mo;
    }
}
