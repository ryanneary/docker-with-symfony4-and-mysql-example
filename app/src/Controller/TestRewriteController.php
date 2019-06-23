<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class TestRewriteController extends AbstractController
{
    const INDEX_FILE = 'index.php';

    public function testRewrite(Request $request)
    {
        $uri = $request->getUri();
        if (strpos($uri, self::INDEX_FILE) !== false) {
            throw new \Exception(sprintf(
                'Rewriting must not be working, because [%s] was found in the URI [%s].',
                self::INDEX_FILE,
                $uri
            ));
        }
        return $this->render('example/test-rewrite.html.twig', [
            'uri' => $uri,
            'indexFile' => self::INDEX_FILE,
        ]);
    }
}