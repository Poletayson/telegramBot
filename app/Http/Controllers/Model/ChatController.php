<?php
//
//namespace App\Http\Controllers\Model;
//
//use App\Models\Chat;
//
//class ChatController
//{
//    /**
//     * Изменить модель
//     * @param int $id ID чата
//     * @param array $values Ассоциативный массив устанавливаемых значений
//     * @return array
//     */
//    public function update(int $id, array $values) {
//        $chat = Chat::find($id);    //Получаем запись
//
////        $result = ['result' => -1];
//
//        if ($chat == null) {
//            return ['result' => -1, 'text' => 'Чат не найден'];
//        }
//
//        if (empty($values)) {
//            return ['result' => 1, 'text' => 'Не заданы данные'];
//        }
//
//        if (isset($values['active']) && $chat->active != $values['active'])
//            $chat->active = $values['active'];
//
//        try {
//            //Были изменения - сохраняем
//            if ($chat->isDirty()) {
//                $chat->save();
//            }
//        } catch (\Exception $exception) {
//            return ['result' => -1, 'text' => 'Исключение: ' . $exception->getMessage()];
//        }
//
//        return ['result' => 0];
//    }
//}
