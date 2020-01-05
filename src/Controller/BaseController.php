<?php
/**
 * Created by PhpStorm.
 * User: Lech Buszczynski <lecho@phatcat.eu>
 * Date: 11/18/18
 * Time: 1:01 PM
 */
declare(strict_types=1);

namespace App\Controller;


use App\Model\Breadcrumbs;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class BaseController extends AbstractController
{
    /**
     * @var Breadcrumbs
     */
    private $breadcrumbs;

    public function __construct()
    {
        $this->breadcrumbs = new Breadcrumbs();
    }

    /**
     * @return array
     */
    public function getResponseArray(): array
    {
        return [
            'breadcrumbs' => $this->getBreadcrumbs()
        ];
    }

    /**
     * @param string $url
     * @param string $label
     *
     * @return BaseController
     */
    public function addBreadcrumb(string $url, string $label): self
    {
        $this->breadcrumbs->addElement($url, $label);
        return $this;
    }

    /**
     * @return Breadcrumbs
     */
    public function getBreadcrumbs(): Breadcrumbs
    {
        return $this->breadcrumbs;
    }
}