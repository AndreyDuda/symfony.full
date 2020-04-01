<?php
declare(strict_types=1);

namespace App\Controller\Auth;

use App\Model\User\UserCase\SignUp\Request\Command;
use App\Model\User\UserCase\SignUp\Request\Form;
use App\Model\User\UserCase\SignUp\Request\Handler;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SingUpController extends AbstractController
{
    private $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    /**
     * @Route("/signup", name="auth.singnaup")
     * @param Request $request
     * @param Handler $handler
     * @return Response
     */
    public function request(Request $request, Handler $handler): Response
    {
        $command = new Command();

        $form = $this->createForm(Form::class, $command);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $handler->handler($command);
                $this->addFlash('success', 'Check your email');
                return $this->redirectToRoute('/');
            } catch (\DomainException $e) {
                $this->logger->error($e->getMessage(), ['exception' => $e]);
            }
        }

        return $this->render('app/auth/signup', [
            'form' => $form->createView()
        ]);

    }
}