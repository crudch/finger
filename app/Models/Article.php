<?php

namespace App\Models;


use Exception;
use App\System\Model;
use App\System\AppDate;
use InvalidArgumentException;

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

    /**
     * @var string
     */
    public static string $table = 'articles';

    public function __construct()
    {
        $this->date = new AppDate();
    }

    /**
     * @param string|AppDate $date
     *
     * @return AppDate
     * @throws Exception
     */
    public function setDate($date): AppDate
    {
        if ($date instanceof AppDate) {
            return $date;
        }

        if (!is_string($date)) {
            throw new InvalidArgumentException('Некорректная передача данных');
        }

        return new AppDate($date);
    }

}
