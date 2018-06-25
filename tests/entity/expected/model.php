<?php
namespace tests\runtime;

use tests\runtime\PostForm;
use yii\db\Exception;

/**
 * Class Post
 * @package tests\runtime
 *
 * @property PostForm $form
 */
class Post extends PostAR
{
    public $form;

    /**
     * Post constructor.
     * @param array $config
     * @param PostForm|null $form
     */
    public function __construct(array $config = [], PostForm $form = null)
    {
        if (!is_null($form)){
            $this->form = $form;
        }
        parent::__construct($config);
    }

    /**
     * трансфер данных из формы в модель
     */
    public function getFromForm()
    {
        if (!is_null($this->form)){
            $this->ticker = $this->form->ticker;
            $this->name = $this->form->name;
            $this->type_id = $this->form->type_id;
            $this->exchange_id = $this->form->exchange_id;
            $this->google_link = $this->form->google_link;
            $this->sector_id = $this->form->sector_id;
        }
    }

    /**
     * добавление модели в БД
     * @return bool
     * @throws \Exception
     * @throws \Throwable
     */
    public function insertModel() : bool
    {
        $this->getFromForm();
        if (!$this->insert()){
            throw new Exception(\Yii::t('app', 'Ошибка при добавлении модели в БД'));
        }
        return true;
    }

    /**
     * обновление модели в БД
     * @return bool
     * @throws \Exception
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function updateModel() : bool
    {
        $this->getFromForm(); //TODO: убери, если не нужно
        if (!$this->update()){
            throw new Exception(\Yii::t('app', 'Ошибка при обновлении модели в БД'));
        }
        return true;
    }

    /**
     * удаление модели из БД
     * @return bool
     * @throws \Exception
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function deleteModel() : bool
    {
        if (!$this->delete()){
            throw new Exception(\Yii::t('app', 'Ошибка при удалении модели из БД'));
        }
        return true;
    }
}
