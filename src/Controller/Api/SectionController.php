<?php

declare(strict_types=1);

namespace Smoq\SimsyCMS\Controller\Api;

use Doctrine\ORM\EntityManagerInterface;
use Smoq\SimsyCMS\Entity\Section;
use Smoq\SimsyCMS\Repository\PageRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/section')]
class SectionController extends AbstractController
{
    #[Route('/create')]
    public function index(Request $request, EntityManagerInterface $em, PageRepository $pageRepository): JsonResponse
    {
        $payload = $request->getPayload();
        $pageId = $payload->getInt('page', 0);
        $page = $pageRepository->find($pageId);

        if (!$page) {
            return $this->json([
                'success' => false,
                'message' => 'Page is required',
            ]);
        }

        if (!$name = $payload->getString('name')) {
            return $this->json([
                'success' => false,
                'message' => 'Name is required',
            ]);
        }

        $section = new Section();
        $section->setName($name);
        $page->addSection($section);

        if ($description = $payload->getString('description')) {
            $section->setDescription($description);
        }

        $em->persist($section);
        $em->flush();

        return $this->json([
            'success' => true,
            'sectionId' => $section->getId(),
        ]);
    }
}
