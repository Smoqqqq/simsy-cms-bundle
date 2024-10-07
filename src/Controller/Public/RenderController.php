<?php

declare(strict_types=1);

namespace Smoq\SimsyCMS\Controller\Public;

use Smoq\SimsyCMS\Entity\Page;
use Smoq\SimsyCMS\Service\BlockService;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route(name: 'simsy_cms_render_')]
class RenderController extends AbstractController
{
    #[Route('{url}', name: 'page', requirements: ['url' => '.*'], defaults: ['url' => '/'], priority: -1)]
    public function index(
        #[MapEntity(mapping: ['url' => 'url'])] Page $page,
        BlockService $blockService
    ): Response
    {
        return $this->render('@SimsyCMS/front/render/page.html.twig', [
            'page' => $page,
            'blockService' => $blockService
        ]);
    }
}
