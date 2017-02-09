<?php

class FileUploader
{
    protected $tmpName;

    public function __construct($attribute)
    {
        $this->tmpName = $_FILES[$attribute]['tmp_name'];
        switch (true) {
            case !isset($_FILES[$attribute]):
                throw new \Exception('Файл не найден');
            case !is_uploaded_file($_FILES[$attribute]['tmp_name']):
                throw new \Exception('Файл не был загружен');
            case $_FILES[$attribute]['size'] > 1024 * 512:
                //return json_encode(['error' => 'Ошибка: Размер файла должен быть меньше 512кб']);
            case $_FILES[$attribute]['size'] + 1024 * 512 > disk_free_space('/'):
                //return json_encode(['error' => 'Ошибка: Не хватает места на сервере']);
            case file_exists('../tests_json/' . $_FILES[$attribute]['name']):
                //return json_encode(['error' => 'Ошибка: Файл уже существует']);
            case !json_decode(file_get_contents($_FILES[$attribute]['tmp_name'])):
                //return json_encode(['error' => 'Ошибка: JSON не валидный']);
            default:
                //return json_encode(['success' => $_FILES[$attribute]['name']]);
        }
    }

    public function saveAs($fileName)
    {
        move_uploaded_file($this->tmpName, $fileName);
    }

    public function getSize()
    {

    }

    /**
     * Возвращает mime тип
     */
    public function getType()
    {

    }
}