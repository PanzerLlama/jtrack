<?php
/**
 * Created by PhpStorm.
 * User: Lech Buszczynski <lecho@phatcat.eu>
 * Date: 11/18/18
 * Time: 1:03 PM
 */

namespace App\Model;


class Breadcrumbs
{
    /**
     * @var array
     */
    private $elements = [];

    /**
     * @param string $url
     * @param string $label
     *
     * @return Breadcrumbs
     */
    public function addElement(string $url, string $label): Breadcrumbs
    {
        $this->elements[] = [
          'url'     => $url,
          'label'   => $label
        ];
        return $this;
    }

    /**
     * @return array
     */
    public function getElements(): array
    {
        return $this->elements;
    }
}