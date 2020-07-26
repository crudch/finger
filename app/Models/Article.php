<?php

namespace App\Models;


use App\System\Model;
use App\System\AppDate;

/**
 * Class Article
 *
 * @property int $id
 * @property string $title
 * @property string $short
 * @property string $text
 * @property string $img
 * @property AppDate $date
 *
 * @method static Article[] all()
 * @method static Article findById(int $id)
 */
class Article extends Model
{
    /**
     * @var string
     */
    protected string $title = '';

    /**
     * @var string
     */
    protected string $short = '';

    /**
     * @var string
     */
    protected string $text = '';

    /**
     * @var string
     */
    protected string $img = '';

    /**
     * @var AppDate
     */
    protected AppDate $date;


    public const TABLE = 'articles';

}
