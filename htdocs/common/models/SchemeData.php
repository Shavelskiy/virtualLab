<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "scheme_data".
 *
 * @property int $id
 * @property int $point1
 * @property int $point2
 * @property string $cur_u
 * @property string $cur_i
 * @property string $cur_r
 *
 * @property SchemePoints $point20
 * @property SchemePoints $point10
 */
class SchemeData extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'scheme_data';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['point1', 'point2'], 'required'],
            [['point1', 'point2'], 'integer'],
            [['cur_u', 'cur_i', 'cur_r'], 'string', 'max' => 255],
            [['point2'], 'exist', 'skipOnError' => true, 'targetClass' => SchemePoint::className(), 'targetAttribute' => ['point2' => 'id']],
            [['point1'], 'exist', 'skipOnError' => true, 'targetClass' => SchemePoint::className(), 'targetAttribute' => ['point1' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'point1' => 'Point1',
            'point2' => 'Point2',
            'cur_u' => 'Cur U',
            'cur_i' => 'Cur I',
            'cur_r' => 'Cur R',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPoint20()
    {
        return $this->hasOne(SchemePoint::className(), ['id' => 'point2']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPoint10()
    {
        return $this->hasOne(SchemePoint::className(), ['id' => 'point1']);
    }
}
