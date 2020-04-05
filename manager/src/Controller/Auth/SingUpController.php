<?php
declare(strict_types=1);

namespace App\Controller\Auth;

use App\Model\User\UserCase\SignUp;
use App\Model\User\UserCase\SignUp\Request\Handler as SignUpRequestHandler;
use App\Model\User\UserCase\SignUp\Confirm\Handler as SignUpConfirmHandler;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

class SingUpController extends AbstractController
{
    private $logger;
    private $translator;

    public function __construct(LoggerInterface $logger, TranslatorInterface $translator)
    {
        $this->logger = $logger;
        $this->translator = $translator;
    }

    /**
     * @Route("/signup", name="auth.singnaup")
     * @param Request $request
     * @param SignUpRequestHandler $handler
     * @return Response
     */
    public function request(Request $request, SignUpRequestHandler $handler): Response
    {
        $command = new SignUp\Request\Command();

        $form = $this->createForm(SignUp\Request\Form::class, $command);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $handler->handler($command);
                $this->addFlash('success', 'Check your email');
                return $this->redirectToRoute('/');
            } catch (\DomainException $e) {
                $this->logger->error($e->getMessage(), ['exception' => $e]);
                $this->addFlash('error', $this->translator->trans($e->getMessage(). []. 'exceptions'));
            }
        }

        return $this->render('app/auth/signup.html.twig', [
            'form' => $form->createView()
        ]);

    }

    /**
     * @Route("/signup/{token}", name="auth.signup.confirm")
     * @param string $token
     * @param SignUpConfirmHandler $handler
     * @return Response
     */
    public function confirm(string $token, SignUpConfirmHandler $handler): Response
    {
        $command = new SignUp\Confirm\Command($token);

        try {
            $handler->handler($command);
            $this->addFlash('success', 'Email is successfully confirmed.');
            return $this->redirect('home');
        } catch (\DomainException $e) {
            $this->logger->error($e->getMessage(), ['exception' => $e]);
            $this->addFlash('error', $e->getMessage());
            return $this->redirectToRoute('home');
        }

    }
}